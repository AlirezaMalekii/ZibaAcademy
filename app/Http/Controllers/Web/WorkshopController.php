<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\File;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class WorkshopController extends AdminController
{
    public function index()
    {
        //$ongoing_workshops=Workshop::where('event_time','>',now())->select('city_id','title','event_time','id')->get()->toArray();
        // $held_workshops=Workshop::where('event_time','<',now())->select('city_id','title','event_time','id')->get()->toArray();
        /* $ongoing_workshops=Workshop::where('event_time','>',now())->select('city_id','title','event_time','id')->with(['files'=>function($query){
             $query->where('type','cover');
         }])->get()->toArray();
         $held_workshops=Workshop::where('event_time','<',now())->select('city_id','title','event_time','id')->with(['files'=>function($query){
             $query->where('type','cover');
         }])->get()->toArray();*/
        $ongoing_workshops = Workshop::where('event_time', '>', now())->select('city_id', 'title', 'event_time', 'id', 'slug')->get()->toArray();
        $held_workshops = Workshop::where('event_time', '<', now())->select('city_id', 'title', 'event_time', 'id', 'slug')->get()->toArray();
//        dd($ongoing_workshops,$held_workshops);
        return view('layouts.workshop.workshops', compact('ongoing_workshops', 'held_workshops'));
    }

    public function show(Workshop $workshop)
    {
        if ($workshop->event_time > now()){
          $workshop_data=  $workshop->only('slug','price','title','body');
          $image=$workshop->files()->where('type','banner')->select('file')->get()->toArray();
          $workshop_data['price']=$this->convertToPersianNumber(number_format($workshop_data['price'], 0, '،', '،'));
          $workshop_data['city']=City::find($workshop->city_id)->name;
          $workshop_data['date']=jdate($workshop->event_time)->format('Y/m/d');
          $workshop_data['hour']=jdate($workshop->event_time)->format('H:i');
          $video_of_workshop=$workshop->files()->whereIn('type', ['video', 'aparat'])->first();
            $stream_video=$video_of_workshop->type=='aparat';
            if ($stream_video){
                $video_url=$video_of_workshop->file['htmlCode'];
            }else{
                $video_url="storage".$video_of_workshop->file['path'];
            }
//            dd($workshop_data);
//            dd(jdate($workshop->event_time)->format('H:i'));

            return view('layouts.workshop.inside-new-workshops',compact('image','workshop_data','video_url','stream_video'));
        }
        if ($workshop->event_time <= now()){

        }
        return back()->withErrors(['error'=>'یافت نشد']);
    }
    public function workshop_register(Workshop $workshop){
        if ($workshop->event_time > now()){
            $workshop_data=  $workshop->only('slug','price','title','capacity');
            $image=$workshop->files()->where('type','banner')->select('file')->get()->toArray();
            $workshop_data['price']=$this->convertToPersianNumber(number_format($workshop_data['price'], 0, '،', '،'));
            $workshop_data['capacity']=$this->convertToPersianNumber($workshop_data['capacity']);
            $workshop_data['city']=City::find($workshop->city_id)->name;
            $workshop_data['date']=jdate($workshop->event_time)->format('Y/m/d');
            $workshop_data['hour']=jdate($workshop->event_time)->format('H:i');
            $workshop_data['time']=jdate($workshop->event_time)->format('%d %B');

            return view('layouts.workshop.workshop-register',compact('image','workshop_data'));
        }
        if ($workshop->event_time <= now()){
            return redirect()->route('workshops')->withErrors([
               'workshopRegisterEnd'=>'مهلت ثبت نام در این ورکشاپ تمام شده است'
            ]);
        }
        return view('layouts.workshop.workshop-register');
    }
}
