<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
//        $blogs=Blog::latest()->paginate(6);
        $blogs = Blog::latest()->select('id', 'slug', 'title', 'body', 'viewCount')->withCount(['comments' => function ($query) {
            $query->where('approved', 1)->latest();
        }])->with('categories:title')->with(['files' => function ($query) {
            $query->where('type', 'cover');
        }])->paginate(6);
        $blog_title = Blog::latest()->select('title', 'slug')->get()->take(5);
        return view('layouts.blog.index', compact('blogs', 'blog_title'));
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $blogs = Blog::query()
            ->where('title', 'LIKE', "%{$search}%")
            ->orWhere('body', 'LIKE', "%{$search}%")
            ->withCount(['comments' => function ($query) {
                $query->where('approved', 1)->latest();
            }])->with('categories:title')->with(['files' => function ($query) {
                $query->where('type', 'cover');
            }])->paginate(6);
        $blog_title = Blog::latest()->select('title', 'slug')->get()->take(5);

        return view('layouts.blog.index', compact('blogs','blog_title'));


    }

    public function show(Blog $blog)
    {
        $blog->increment('viewCount');
        $data_blog = $blog->only('title', 'description', 'created_at', 'viewCount', 'body','slug');
        $getCategory = $blog->categories()->get();
        $categories = $getCategory->pluck('title')->toArray();
        $comments = $blog->comments()->where('approved', true)->where('parent_id',0)->select('id','name', 'comment', 'created_at')->with('comments')->get()->toArray();
        $closeBlogs = Blog::whereHas('categories', function ($query) use ($getCategory) {
            $query->whereIn('id', $getCategory->pluck('id')->toArray());
        })->get()->take(4);
        $closeBlogs = $closeBlogs->except([$blog->id]);
        $image = $blog->files()->where('type', 'image')->first()->file['thumb'];
        return view('layouts.blog.show', compact('data_blog', 'categories', 'comments', 'closeBlogs', 'image'));
//       dd($image);
    }

    public function create_comment(Blog $blog, Request $request)
    {
        $data = $request->validate([
            'comment' => 'string|required|max:511',
        ]);
        $loginUser = auth()->user();
        $blog->comments()->create([
            'creator_id' => $loginUser->id,
            'name' => $loginUser->name . " " . $loginUser->lastname,
            'comment' => $data['comment'],
        ]);
        return back()->with('success', 'کامنت شما ثبت شد. پس از بازبینی در سایت قرار خواهد گرفت');
    }
}
