<?php

namespace App\Http\Resources\V1\Announcement;

use App\Http\Resources\V1\Course\CourseResource;
use App\Http\Resources\V1\User\UserCollection;
use App\Http\Resources\V1\User\UserResource;
use App\Http\Resources\V1\Workshop\WorkshopResource;
use App\Models\Course;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Http\Resources\Json\JsonResource;

class AnnouncementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'workshop_id'=>new WorkshopResource(Workshop::find($this->workshop_id), false) ,
            'course_id'=>new CourseResource(Course::find($this->course_id), false) ,
            'title'=>$this->title,
            'message'=>$this->message,
            'kavenegar_data'=>json_decode($this->kavenegar_data),
            'drivers'=>json_decode($this->drivers),
//            'send_at'=>jdate($this->send_at)->format('Y-m-d H:i:s'),
            'send_at'=>\Carbon\Carbon::parse($this->send_at)->timestamp,
//            'users'=>$this->when(is_null($this->users),'همه کاربران این ورکشاپ درنظر گرفته شود',new UserCollection(User::whereIn('id',json_decode($this->users))->get()))
//            'users'=>$this->when(is_null($this->users),'همه کاربران این ورکشاپ درنظر گرفته شود',UserResource::collection(User::whereIn('id',json_decode($this->users))->get()))
//            'users'=>isset($this->users)? UserResource::collection(User::whereIn('id',json_decode($this->users))->get()) : 'همه کاربران این ورکشاپ درنظر گرفته شود',
            'users'=>isset($this->users)? new UserCollection(User::whereIn('id',json_decode($this->users))->get(),false)  : null,
        ];
    }
}
