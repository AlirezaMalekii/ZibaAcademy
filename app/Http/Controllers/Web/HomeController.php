<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Workshop;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $number_of_workshops = Workshop::all()->count();
        $held_workshops = Workshop::where('event_time', '<', now())->with('city')->get();
        $last_video_of_workshop = Workshop::latest('id')->first()->files()->whereIn('type', ['video', 'aparat'])->first();
//        $last_video_of_workshop = Workshop::find(19)->files()->whereIn('type', ['video', 'aparat'])->first();
        $stream_video=$last_video_of_workshop->type=='aparat';
        if ($stream_video){
            $video_url=$last_video_of_workshop->file['htmlCode'];
        }else{
            $video_url="storage".$last_video_of_workshop->file['path'];
        }
//        $blogs = Blog::latest()->take(4)->withCount('comments')->get();
        $blogs = Blog::latest()->take(4)->select('id','description','title','viewCount')->withCount(['comments'=>function($query){
            $query->where('approved',1)->latest();
        }])->with('categories:title')->with(['files'=>function($query){
            $query->where('type','cover');
        }])->get();
//        dd($blogs);
        return view('layouts.index', compact('number_of_workshops', 'held_workshops', 'blogs', 'video_url','stream_video'));
    }
}

