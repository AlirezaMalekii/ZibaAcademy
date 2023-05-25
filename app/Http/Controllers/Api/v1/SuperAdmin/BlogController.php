<?php

namespace App\Http\Controllers\Api\v1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Blog\BlogCollection;
use App\Http\Resources\V1\Blog\BlogResource;
use App\Models\Blog;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Kavenegar\Exceptions\ApiException;
use Illuminate\Filesystem\Filesystem as FileSystem;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return BlogCollection
     */
    public function index()
    {
        $blogPiginate = Blog::filter()->latest()->paginate(20);
        return new BlogCollection($blogPiginate);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'cover_image' => 'array|required',
            'blog_images' => 'array|required',
            'title' => 'required|string|max:255',
//            'category_id' => [Rule::excludeIf(!isset($request->category_id)),'numeric','exists:App\Models\Category,id'],
            'category_id' => [Rule::excludeIf(!isset($request->category_id)), 'array'],
            "category_id.*" => [Rule::excludeIf(!isset($request->category_id)), 'numeric', 'exists:App\Models\Category,id'],
            'description' => 'required|string',
//            'body' => 'required|string',
            'body' => [Rule::excludeIf(!isset($request->body)), 'string'],
        ]);
        try {
            $blog = Blog::create([
                'creator_id' => auth()->user()->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'body' => $data['body'] ?? null,
            ]);
            if (isset($data['category_id'])) {
                $blog->categories()->sync($data['category_id']);
            }
            $array_cover_image = explode('/', $data['cover_image']['thumb']);
            $extension_cover = explode('.', $array_cover_image[5]);
            $cover_image = $blog->files()->create([
                'creator_id' => auth()->user()->id,
                'file' => $data['cover_image'],
                'type' => 'cover',
                'file_name' => $array_cover_image[4],
                'extension' => $extension_cover[1],
                'accessibility' => 'free'
            ]);
            $array_blog_image = explode('/', $data['blog_images']['thumb']);
            $extension_blog = explode('.', $array_blog_image[5]);
            $blog_images = $blog->files()->create([
                'creator_id' => auth()->user()->id,
                'file' => $data['blog_images'],
                'type' => 'image',
                'file_name' => $array_blog_image[4],
                'extension' => $extension_blog[1],
                'accessibility' => 'free'
            ]);
            return response([
                'data' => new BlogResource($blog),
                'message' => "بلاگ به صورت کامل ثبت شد",
                'status' => 'success'
            ], 200);
//        }catch (ApiException $e){
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
     * @return BlogResource
     */
    public function show($id)
    {
        $blog = Blog::find($id);
        if (!$blog) {
            return response([
                'message' => "یافت نشد",
                'status' => 'failed'
            ], 400);
        }
        return new BlogResource($blog);
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
        $blog = Blog::find($id);
        if (!$blog) {
            return response([
                'message' => "یافت نشد",
                'status' => 'failed'
            ], 400);
        }
        $data = $request->validate([
            'cover_image' => 'array|required',
            'blog_images' => 'array|required',
            'title' => 'required|string|max:255',
//            'category_id' => [Rule::excludeIf(!isset($request->category_id)),'numeric','exists:App\Models\Category,id'],
            'category_id' => [Rule::excludeIf(!isset($request->category_id)), 'array'],
            "category_id.*" => [Rule::excludeIf(!isset($request->category_id)), 'numeric', 'exists:App\Models\Category,id'],
            'description' => 'required|string',
//            'body' => 'required|string',
            'body' => [Rule::excludeIf(!isset($request->body)), 'string'],
        ]);
        try {
            $blog->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'body' => $data['body'] ?? null,
            ]);
            if (isset($data['category_id'])) {
                $blog->categories()->sync($data['category_id']);
            }
            $array_cover_image = explode('/', $data['cover_image']['thumb']);
            $extension_cover = explode('.', $array_cover_image[5]);
            $blog_cover = $blog->files()->where('type', 'cover')->first();
           // dd($blog_cover);
            if ($blog_cover->file_name != $array_cover_image[4]) {
                $array_file_image = explode('/', $blog_cover->file['thumb']);
                $filePicture = implode('/', [$array_file_image[0], $array_file_image[1], $array_file_image[2], $array_file_image[3], $array_file_image[4]]);
//                preg_replace('/string$/', '', $str);
//                return $filePicture;
                $file_system = new FileSystem();
                $file_system->deleteDirectory(public_path($filePicture));

                $cover_image = $blog_cover->update([
                    'file' => $data['cover_image'],
                    'file_name' => $array_cover_image[4],
                    'extension' => $extension_cover[1],
                ]);
            }
            $array_blog_image = explode('/', $data['blog_images']['thumb']);
            $extension_blog = explode('.', $array_blog_image[5]);
            $blog_image_in = $blog->files()->where('type', 'image')->first();
            if ($blog_image_in->file_name != $array_blog_image[4]) {
                $array_file_image = explode('/', $blog_image_in->file['thumb']);
                $filePicture = implode('/', [$array_file_image[0], $array_file_image[1], $array_file_image[2], $array_file_image[3], $array_file_image[4]]);
                $file_system = new FileSystem();
                $file_system->deleteDirectory(public_path($filePicture));
                $cover_image = $blog_image_in->update([
                    'file' => $data['blog_images'],
                    'file_name' => $array_blog_image[4],
                    'extension' => $extension_blog[1],
                ]);
            }
            return response([
                'data' => new BlogResource($blog),
                'message' => "بلاگ به صورت کامل ثبت شد",
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => 'بخشی از عملیات با خطا مواجه شد.'
            ], 400);;
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
        $blog = Blog::find($id);
        if (!$blog) {
            return response([
                'message' => "یافت نشد",
                'status' => 'failed'
            ], 400);
        }
        foreach ($blog->files as $file) {
            $array_file_image = explode('/', $file->file['thumb']);
            $filePicture = implode('/', [$array_file_image[0], $array_file_image[1], $array_file_image[2], $array_file_image[3], $array_file_image[4]]);
//                preg_replace('/string$/', '', $str);
//                return $filePicture;
            $file_system = new FileSystem();
            $file_system->deleteDirectory(public_path($filePicture));
            $file->delete();
        }
        $blog->categories()->detach($blog->categories()->pluck('id'));
        $blog->delete();
        return response([
            'message' => 'عملیات با موفقیت انجام شد',
            'status' => 'success'
        ], 200);
    }
}
