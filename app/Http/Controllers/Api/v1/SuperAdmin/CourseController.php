<?php

namespace App\Http\Controllers\Api\v1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Course\CourseDashboardCollection;
use App\Http\Resources\V1\Course\CourseResource;
use App\Http\Resources\V1\Course\CourseCollection;
use App\Models\Course;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Filesystem\Filesystem as FileSystem;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coursePiginate = Course::filter()->latest()->paginate(8);
//        return new CourseDashboardCollection($coursePiginate);
        return new CourseCollection($coursePiginate);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*
           $table->enum('status', ['active', 'is_draft', 'inactive']);
        */
        $data = $request->validate([
            'title' => 'required|string|max:255',//
            'description' => [Rule::excludeIf(!isset($request->description)), 'string'],//
            'body' => ['required', 'string'],//
            'category_id' => [Rule::excludeIf(!isset($request->category_id)), 'array'],
            "category_id.*" => [Rule::excludeIf(!isset($request->category_id)), 'numeric',
                Rule::exists('categories', 'id')->where(function (Builder $query) {
                    return $query->where('type', 'course');
                }),
            ],
            'cover_image' => 'array|required',//
            'banner' => 'array|required',//
            'support_way' => [Rule::excludeIf(!isset($request->support_way)), 'string','max:255'], //
            'delivery_way' => [Rule::excludeIf(!isset($request->delivery_way)), 'string','max:255'],//
            'episode_count' => [Rule::excludeIf(!isset($request->episode_count)), 'string','max:255'],//
            'section_count' => [Rule::excludeIf(!isset($request->section_count)), 'string','max:255'],//
            'level' => [Rule::excludeIf(!isset($request->level)), 'string','max:255'],//
            'discount' => [Rule::excludeIf(!isset($request->discount)), 'integer'],//
            'time' => [Rule::excludeIf(!isset($request->time)), 'string','max:255'],//
            'prerequisite' => [Rule::excludeIf(!isset($request->prerequisite)), 'string'],//
            'spotplayer_course_id' => [Rule::excludeIf(!isset($request->spotplayer_course_id)), 'string','max:255'],//
            'video_type' => ['required', 'max:25', 'string', Rule::in(['aparat', 'video'])],
            'video' => 'array|required',
            'price' => 'required|integer'//
        ]);
        try {
            if ($request->video_type == 'video') {
                if ($data['video']['filePath'] == null) {
                    return response([
                        'message' => 'ویدیو انتخاب نشده است.',
                        'status' => 'failed'
                    ], 400);
                }
            }
            if ($request->video_type == 'aparat') {
                if ($data['video'] == null) {
                    return response([
                        'message' => 'ویدیو انتخاب نشده است.',
                        'status' => 'failed'
                    ], 400);
                }
            }
            $loginUser=auth()->user();
            $loginUserId=$loginUser->id;
            $course = Course::create([
                'creator_id' => $loginUserId,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'support_way' => $data['support_way'] ?? null,
                'delivery_way' => $data['delivery_way'] ?? null,
                'spotplayer_course_id' => $data['spotplayer_course_id'] ?? null,
                'episode_count' => $data['episode_count'] ?? 0,
                'section_count' => $data['section_count'] ?? 0,
                'prerequisite' => $data['prerequisite'] ?? null,
                'time' => $data['time'] ?? '00:00:00',
                'discount' => $data['discount'] ?? 0,
                'level' => $data['level'] ?? null,
                'body' => $data['body'],
                'price'=>$data['price']
            ]);
            if ($data['video_type'] == 'aparat') {
                $course->files()->create([
                    'creator_id' => $loginUserId,
                    'file' => $data['video'],
                    'type' => $data['video_type'],
                    'accessibility' => 'free'
                ]);
            }
            if ($data['video_type'] == 'video') {
                $course->files()->create([
                    'creator_id' => auth()->user()->id,
                    'file' => $data['video']['filePath'],
                    'type' => $data['video_type'],
                    'extension' => $data['video']['fileExtension'] ?? null,
                    'accessibility' => 'free'
                ]);
            }

            if (isset($data['category_id'])) {
                $course->categories()->sync($data['category_id']);
            }
            $array_cover_image = explode('/', $data['cover_image']['thumb']);
            $extension_cover = explode('.', $array_cover_image[5]);
            $cover_image = $course->files()->create([
                'creator_id' => $loginUserId,
                'file' => $data['cover_image'],
                'type' => 'cover',
                'file_name' => $array_cover_image[4],
                'extension' => $extension_cover[1],
                'accessibility' => 'free'
            ]);
            $array_banner_image = explode('/', $data['banner']['thumb']);
            $extension_inside_image = explode('.', $array_banner_image[5]);
            $cover_image = $course->files()->create([
                'creator_id' => $loginUserId,
                'file' => $data['banner'],
                'type' => 'banner',
                'file_name' => $array_banner_image[4],
                'extension' => $extension_inside_image[1],
                'accessibility' => 'free'
            ]);
            return response([
                //'data' => new CourseResource($course),
                'message' => "دوره به صورت کامل ثبت شد",
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => 'بخشی از عملیات با خطا مواجه شد.'
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return CourseResource
     */
    public function show($id)
    {
        $course= Course::find($id);
        if (!$course) {
            return response([
                'message' => "یافت نشد",
                'status' => 'success'
            ], 400);
        }
        return new CourseResource($course);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response([
                'message' => "یافت نشد",
                'status' => 'success'
            ], 400);
        }
        $loginUser=auth()->user();
        $loginUserId=$loginUser->id;
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => [Rule::excludeIf(!isset($request->description)), 'string'],
            'body' => ['required', 'string'],
            'category_id' => [Rule::excludeIf(!isset($request->category_id)), 'array'],
            "category_id.*" => [
                Rule::excludeIf(!isset($request->category_id)), 'numeric', Rule::exists('categories','id')->where(function (Builder $query){
                return $query->where('type', 'course');
            }),
                ],
            'cover_image' => 'array|required',
            'banner' => 'array|required',
            'video_type' => ['required', 'max:25', 'string', Rule::in(['aparat', 'video'])],
            'video' => 'array|required',
            'price'=>'required|integer',
            'support_way' => [Rule::excludeIf(!isset($request->support_way)), 'string','max:255'], //
            'delivery_way' => [Rule::excludeIf(!isset($request->delivery_way)), 'string','max:255'],//
            'episode_count' => [Rule::excludeIf(!isset($request->episode_count)), 'string','max:255'],//
            'section_count' => [Rule::excludeIf(!isset($request->section_count)), 'string','max:255'],//
            'level' => [Rule::excludeIf(!isset($request->level)), 'string','max:255'],//
            'discount' => [Rule::excludeIf(!isset($request->discount)), 'integer'],//
            'time' => [Rule::excludeIf(!isset($request->time)), 'string','max:255'],//
            'prerequisite' => [Rule::excludeIf(!isset($request->prerequisite)), 'string'],//
            'spotplayer_course_id' => [Rule::excludeIf(!isset($request->spotplayer_course_id)), 'string','max:255'],//
            'status' => [Rule::excludeIf(!isset($request->status)), 'max:25', 'string', Rule::in(['active', 'is_draft', 'inactive'])],
        ]);
        try {
            if ($request->video_type == 'video') {
                if ($data['video']['filePath'] == null) {
                    return response([
                        'message' => 'ویدیو انتخاب نشده است.',
                        'status' => 'failed'
                    ], 400);
                }
            }
            if ($request->video_type == 'aparat') {
                if ($data['video'] == null) {
                    return response([
                        'message' => 'ویدیو انتخاب نشده است.',
                        'status' => 'failed'
                    ], 400);
                }
            }
            if ($data['status'] !='is_draft'){
                if (!isset($data['spotplayer_course_id'])){
                    return response([
                        'message' => 'امکان تغییر وضعیت در شرایطی که spotplayer_course_id انتخاب نشده است وجود ندارد.',
                        'status' => 'failed'
                    ], 400);
                }
            }
            $course->update([
                'creator_id' => $loginUserId,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'support_way' => $data['support_way'] ?? null,
                'delivery_way' => $data['delivery_way'] ?? null,
                'spotplayer_course_id' => $data['spotplayer_course_id'] ?? null,
                'episode_count' => $data['episode_count'] ?? 0,
                'section_count' => $data['section_count'] ?? 0,
                'prerequisite' => $data['prerequisite'] ?? null,
                'time' => $data['time'] ?? '00:00:00',
                'discount' => $data['discount'] ?? 0,
                'level' => $data['level'] ?? null,
                'body' => $data['body'],
                'price'=>$data['price'],
                'status'=>$data['status'],
            ]);
            if ($data['video_type'] == 'aparat') {
                $video = $course->files()->where(function ($q) {
                    $q->where('type', 'aparat')->orWhere('type', 'video');
                })->first();

                if ($video->type == 'video') {
                    if (file_exists(public_path($video->file['path']))) {
                        $filedeleted = unlink(public_path($video->file['path']));
                    }
                }
                $video->delete();

                $course->files()->create([
                    'creator_id' => $loginUserId,
                    'file' => $data['video'],
                    'type' => $data['video_type'],
                    'accessibility' => 'free'
                ]);
            }
            if ($data['video_type'] == 'video') {
                if ($video = $course->files->where('type', 'video')->first()) {
                    if ($video->file['path'] != $data['video']['filePath']['path']) {
                        if (file_exists(public_path($video->file['path']))) {
                            $filedeleted = unlink(public_path($video->file['path']));
                        }
                        $video->delete();
                        $course->files()->create([
                            'creator_id' => $loginUserId,
                            'file' => $data['video']['filePath'],
                            'type' => $data['video_type'],
                            'extension' => $data['video']['fileExtension'],
                            'accessibility' => 'free'
                        ]);
                    }
                }else{
                    $video = $course->files->where('type', 'aparat')->first();
                    $video->delete();
                    $course->files()->create([
                        'creator_id' => $loginUserId,
                        'file' => $data['video']['filePath'],
                        'type' => $data['video_type'],
                        'extension' => $data['video']['fileExtension'],
                        'accessibility' => 'free'
                    ]);
                }
            }

            if (isset($data['category_id'])) {
                $course->categories()->sync($data['category_id']);
            }
            $array_cover_image = explode('/', $data['cover_image']['thumb']);
            $extension_cover = explode('.', $array_cover_image[5]);
            $course_cover = $course->files()->where('type', 'cover')->first();
            if ($course_cover->file_name != $array_cover_image[4]) {
                $array_file_image = explode('/', $course_cover->file['thumb']);
                $filePicture = implode('/', [$array_file_image[0], $array_file_image[1], $array_file_image[2], $array_file_image[3], $array_file_image[4]]);
                $file_system = new FileSystem();
                $file_system->deleteDirectory(public_path($filePicture));
                $cover_image = $course_cover->update([
                    'file' => $data['cover_image'],
                    'file_name' => $array_cover_image[4],
                    'extension' => $extension_cover[1],
                ]);
            }

            $array_banner_image = explode('/', $data['banner']['thumb']);
            $extension_banner = explode('.', $array_banner_image[5]);
            $course_banner = $course->files()->where('type', 'banner')->first();
            if ($course_banner->file_name != $array_banner_image[4]) {
                $array_file_image = explode('/', $course_banner->file['thumb']);
                $filePicture = implode('/', [$array_file_image[0], $array_file_image[1], $array_file_image[2], $array_file_image[3], $array_file_image[4]]);
                $file_system = new FileSystem();
                $file_system->deleteDirectory(public_path($filePicture));
                $banner_image = $course_banner->update([
                    'file' => $data['banner'],
                    'file_name' => $array_banner_image[4],
                    'extension' => $extension_banner[1],
                ]);
            }
            return response([
                //'data' => new CourseResource($course),
                'message' => "تغییرات دوره به صورت کامل ثبت شد",
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response([
                'message' => "یافت نشد",
                'status' => 'failed'
            ], 400);
        }
//        foreach ($workshop->files as $file){
        $file = $course->files()->get()->where('type', 'cover')->first();
        if (isset($file)) {
            $array_file_image = explode('/', $file->file['thumb']);
            $filePicture = implode('/', [$array_file_image[0], $array_file_image[1], $array_file_image[2], $array_file_image[3], $array_file_image[4]]);
            $file_system = new FileSystem();
            $file_system->deleteDirectory(public_path($filePicture));
            $file->delete();
        }
        $banner = $course->files()->get()->where('type', 'banner')->first();
        if (isset($banner)) {
            $array_file_image = explode('/', $banner->file['thumb']);
            $filePicture = implode('/', [$array_file_image[0], $array_file_image[1], $array_file_image[2], $array_file_image[3], $array_file_image[4]]);
            $file_system = new FileSystem();
            $file_system->deleteDirectory(public_path($filePicture));
            $banner->delete();
        }
        $video = $course->files()->where(function ($q) {
            $q->where('type', 'aparat')->orWhere('type', 'video');
        })->first();
        if ($video->type == 'video') {
            if (file_exists(public_path($video->file['path']))) {
                $filedeleted = unlink(public_path($video->file['path']));
            }
        }
        $video->delete();
        $course->categories()->detach($course->categories()->pluck('id'));
        $course->delete();
        return response([
            'message' => 'عملیات با موفقیت انجام شد',
            'status' => 'success'
        ], 200);
    }
}
