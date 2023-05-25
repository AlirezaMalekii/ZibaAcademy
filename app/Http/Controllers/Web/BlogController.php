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
        $blogs=Blog::latest()->select('id','slug','title','body','viewCount')->withCount(['comments'=>function($query){
            $query->where('approved',1)->latest();
        }])->with('categories:title')->with(['files'=>function($query){
            $query->where('type','cover');
        }])->paginate(6);
        return view('layouts.blog.index',compact('blogs'));
    }
    public function search(Request $request){
        $search=$request->search;
        $blogs = Blog::query()
            ->where('title', 'LIKE', "%{$search}%")
            ->orWhere('body', 'LIKE', "%{$search}%")
            ->withCount(['comments'=>function($query){
                $query->where('approved',1)->latest();
            }])->with('categories:title')->with(['files'=>function($query){
                $query->where('type','cover');
            }])->paginate(6);
        return view('layouts.blog.index',compact('blogs'));


    }
}
