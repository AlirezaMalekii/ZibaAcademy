<?php

namespace App\Http\Controllers\Api\v1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Workshop\WorkshopCollection;
use App\Http\Resources\V1\Workshop\WorkshopResource;
use App\Models\File;
use App\Models\Workshop;
use Illuminate\Filesystem\Filesystem as FileSystem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WorkshopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return WorkshopCollection
     */
    public function index()
    {
        $categoryPiginate = Workshop::paginate(8);

        return new WorkshopCollection($categoryPiginate);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'city_id' => 'required|numeric',
            'description' => [Rule::excludeIf(!isset($request->description)), 'string'],
            'body' => [Rule::excludeIf(!isset($request->body)), 'string'],
            'event_time' => 'required|date',
            'capacity' => [Rule::excludeIf(!isset($request->capacity)), 'numeric', 'integer'],
            'category_id' => [Rule::excludeIf(!isset($request->category_id)), 'array'],
            "category_id.*" => [Rule::excludeIf(!isset($request->category_id)), 'numeric', 'exists:App\Models\Category,id'],
            'cover_image' => 'array|required',
            'gallery_images' => [Rule::excludeIf(!isset($request->gallery_images)), 'array'],
        ]);
        try {
            $workshop = Workshop::create([
                'creator_id' => auth()->user()->id,
                'title' => $data['title'],
                'city_id' => $data['city_id'],
                'description' => $data['description'] ?? null,
                'body' => $data['body'] ?? null,
                'event_time' => $data['event_time'],
                'capacity' => $data['capacity'] ?? 0,
            ]);

            if (isset($data['category_id'])) {
                $workshop->categories()->sync($data['category_id']);
            }

            if (isset($data['gallery_images'])) {
                $gallery = $workshop->gallery()->create([
                    'creator_id' => auth()->user()->id,
                    'title' => $data['title']
                ]);
                $collection_galleries = collect($data['gallery_images']);
                foreach ($collection_galleries as $gallery_file) {
                    $array_cover_image = explode('/', $gallery_file['thumb']);
                    $extension_cover = explode('.', $array_cover_image[5]);
                    $gallery->files()->create([
                        'creator_id' => auth()->user()->id,
                        'file' => $gallery_file,
                        'type' => 'image',
                        'file_name' => $array_cover_image[4],
                        'extension' => $extension_cover[1],
                        'accessibility' => 'free'
                    ]);
                }
            }
            $array_cover_image = explode('/', $data['cover_image']['thumb']);
            $extension_cover = explode('.', $array_cover_image[5]);
            $cover_image = $workshop->files()->create([
                'creator_id' => auth()->user()->id,
                'file' => $data['cover_image'],
                'type' => 'cover',
                'file_name' => $array_cover_image[4],
                'extension' => $extension_cover[1],
                'accessibility' => 'free'
            ]);
            /*if(isset($data['gallery_images'])){
                $collection_dallery=collect($data['gallery_images']);
                dd();
            }*/
            return response([
                'data' => new WorkshopResource($workshop),
                'message' => "بلاگ به صورت کامل ثبت شد",
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
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $workshop = Workshop::find($id);
        if (!$workshop) {
            return response([
                'message' => "یافت نشد",
                'status' => 'success'
            ], 400);
        }
        return new WorkshopResource($workshop);
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
        $workshop = Workshop::find($id);
        if (!$workshop) {
            return response([
                'message' => "یافت نشد",
                'status' => 'success'
            ], 400);
        }
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'city_id' => 'required|numeric',
            'description' => [Rule::excludeIf(!isset($request->description)), 'string'],
            'body' => [Rule::excludeIf(!isset($request->body)), 'string'],
            'event_time' => 'required|date',
            'capacity' => [Rule::excludeIf(!isset($request->capacity)), 'numeric', 'integer'],
            'category_id' => [Rule::excludeIf(!isset($request->category_id)), 'array'],
            "category_id.*" => [Rule::excludeIf(!isset($request->category_id)), 'numeric', 'exists:App\Models\Category,id'],
            'cover_image' => 'array|required',
            'gallery_images' => [Rule::excludeIf(!isset($request->gallery_images)), 'array'],
        ]);
        try {
            $workshop->update([
                'title' => $data['title'],
                'city_id' => $data['city_id'],
                'description' => $data['description'] ?? null,
                'body' => $data['body'] ?? null,
                'event_time' => $data['event_time'],
                'capacity' => $data['capacity'] ?? 0,
            ]);
            if (isset($data['category_id'])) {
                $workshop->categories()->sync($data['category_id']);
            }
            $array_cover_image = explode('/', $data['cover_image']['thumb']);
            $extension_cover = explode('.', $array_cover_image[5]);
            $workshop_cover = $workshop->files()->where('type', 'cover')->first();
            if ($workshop_cover->file_name != $array_cover_image[4]) {
                $array_file_image = explode('/', $workshop_cover->file['thumb']);
                $filePicture = implode('/', [$array_file_image[0], $array_file_image[1], $array_file_image[2], $array_file_image[3], $array_file_image[4]]);
                $file_system = new FileSystem();
                $file_system->deleteDirectory(public_path($filePicture));
                $cover_image = $workshop->files()->update([
                    'file' => $data['cover_image'],
                    'file_name' => $array_cover_image[4],
                    'extension' => $extension_cover[1],
                ]);
            }
            if (isset($data['gallery_images'])) {
                $gallery = $workshop->gallery;
                if (!$gallery) {
                    $gallery = $workshop->gallery()->create([
                        'creator_id' => auth()->user()->id,
                        'title' => $data['title']
                    ]);
                }
                $collection_galleries = collect($data['gallery_images']);
                foreach ($collection_galleries as $gallery_file) {
                    $array_cover_image = explode('/', $gallery_file['thumb']);
                    $extension_cover = explode('.', $array_cover_image[5]);
                    $gallery->files()->create([
                        'creator_id' => auth()->user()->id,
                        'file' => $gallery_file,
                        'type' => 'image',
                        'file_name' => $array_cover_image[4],
                        'extension' => $extension_cover[1],
                        'accessibility' => 'free'
                    ]);
                }
            }
            return response([
                'data' => new WorkshopResource($workshop),
                'message' => "تغییرات ورکشاب به صورت کامل ثبت شد",
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

        $workshop = Workshop::find($id);
        if (!$workshop) {
            return response([
                'message' => "یافت نشد",
                'status' => 'failed'
            ], 400);
        }
//        foreach ($workshop->files as $file){
        $file = $workshop->files()->first();
        if (isset($file)) {
            $array_file_image = explode('/', $file->file['thumb']);
            $filePicture = implode('/', [$array_file_image[0], $array_file_image[1], $array_file_image[2], $array_file_image[3], $array_file_image[4]]);
            $file_system = new FileSystem();
            $file_system->deleteDirectory(public_path($filePicture));
            $file->delete();
        }
//        }
        $gallery = $workshop->gallery;
        if (isset($gallery)) {
            foreach ($gallery->files()->get() as $file) {
                $array_file_image = explode('/', $file->file['thumb']);
                $filePicture = implode('/', [$array_file_image[0], $array_file_image[1], $array_file_image[2], $array_file_image[3], $array_file_image[4]]);
                $file_system = new FileSystem();
                $file_system->deleteDirectory(public_path($filePicture));
                $file->delete();
            }
            $gallery->delete();
        }
        $workshop->categories()->detach($workshop->categories()->pluck('id'));
        $workshop->delete();
        return response([
            'message' => 'عملیات با موفقیت انجام شد',
            'status' => 'success'
        ], 200);
    }
}
