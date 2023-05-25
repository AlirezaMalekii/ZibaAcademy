<?php

namespace App\Http\Controllers\Api\v1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Gallery\galleryResource;
use App\Http\Resources\V1\Workshop\WorkshopCollection;
use App\Models\File;
use App\Models\Gallery;
use App\Models\Workshop;
use Illuminate\Filesystem\Filesystem as FileSystem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Query\Builder;

class GalleryController extends Controller
{
    public function delete_files(Request $request, $gallery_id)
    {
        $gallery = Gallery::find($gallery_id);
        if (!$gallery) {
            return response([
                'message' => "یافت نشد",
                'status' => 'failed'
            ], 400);
        }
        $request->validate([
            'file_id' => [
                'required', 'numeric',
                Rule::exists('files', 'id')->where(function (Builder $query) use ($gallery_id) {
                    return $query->where('fileable_type', 'App\Models\Gallery')->where('fileable_id', $gallery_id);
                }),
            ]
        ]);
        $file=File::find($request->file_id);
        $array_file_image = explode('/', $file->file['thumb']);
        $filePicture = implode('/', [$array_file_image[0], $array_file_image[1], $array_file_image[2], $array_file_image[3],$array_file_image[4]]);
        $file_system=new FileSystem();
        $file_system->deleteDirectory(public_path($filePicture));
        $file->delete();
        return response([
            'message' => 'عکس مورد نظر حذف شد',
            'status' => 'success'
        ], 200);
//        return new galleryResource($gallery);
    }
    public function workshops_without_gallery(){
         $gallery_id=Gallery::where('galleryable_type','App\Models\Workshop')->get()->pluck('galleryable_id')->toArray();
//        $workshops_without_gallery=Workshop::all()->except($gallery_id)->pluck('title','id')->toArray();
        $workshops_without_gallery=Workshop::all()->except($gallery_id);
        return new WorkshopCollection($workshops_without_gallery,false);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $galleries=Gallery::latest()->paginate(8);
        return galleryResource::collection($galleries);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data=$request->validate([
            'title' => 'required|string|max:255',
            'gallery_type'=>'required|string|max:20',
            'gallery_type_id'=>'required|numeric',
            'gallery_images' => [Rule::excludeIf(!isset($request->gallery_images)), 'array'],
        ]);
        if($data['gallery_type']!='workshop'){
            return response([
                'message' => 'این تایپ شامل گالری نمی باشد',
                'status' => 'بخشی از عملیات با خطا مواجه شد.'
            ], 400);
        }
        if ($data['gallery_type']=='workshop'){
            $workshop = Workshop::find($data['gallery_type_id']);
            if (!$workshop) {
                return response([
                    'message' => "یافت نشد",
                    'status' => 'failed'
                ], 400);
            }
            if ($workshop->gallery){
                return response([
                    'message' => "برای این ورکشاپ پیش تر گالری ایجاد شده است.",
                    'status' => 'failed'
                ], 400);
            }
            $gallery = $workshop->gallery()->create([
                'creator_id' => auth()->user()->id,
                'title' => $data['title']
            ]);
            if (isset($data['gallery_images'])) {
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
                'data' => new GalleryResource($gallery),
                'message' => "گالری به صورت کامل ثبت شد",
                'status' => 'success'
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $gallery = Gallery::find($id);
        if (!$gallery) {
            return response([
                'message' => "یافت نشد",
                'status' => 'success'
            ], 400);
        }
        return new galleryResource($gallery);
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
        $data=$request->validate([
            'title' => 'required|string|max:255',
            'gallery_images' => [Rule::excludeIf(!isset($request->gallery_images)), 'array'],
        ]);
        $gallery = Gallery::find($id);
        if (!$gallery) {
            return response([
                'message' => "یافت نشد",
                'status' => 'success'
            ], 400);
        }
        $gallery->update(['title'=>$data['title']]);
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
            return response([
                'data' => new GalleryResource($gallery),
                'message' => "گالری به صورت کامل آپدیت شد",
                'status' => 'success'
            ], 200);
        }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $gallery = Gallery::find($id);
        if (!$gallery) {
            return response([
                'message' => "یافت نشد",
                'status' => 'success'
            ], 400);
        }
        foreach ($gallery->files()->get() as $file) {
            $array_file_image = explode('/', $file->file['thumb']);
            $filePicture = implode('/', [$array_file_image[0], $array_file_image[1], $array_file_image[2], $array_file_image[3], $array_file_image[4]]);
            $file_system = new FileSystem();
            $file_system->deleteDirectory(public_path($filePicture));
            $file->delete();
        }
        $gallery->delete();
        return response([
            'message' => 'عملیات با موفقیت انجام شد',
            'status' => 'success'
        ], 200);
    }
}
