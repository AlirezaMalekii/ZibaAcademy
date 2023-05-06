<?php

namespace App\Http\Resources\V1\Announcement;

use App\Http\Resources\V1\User\UserCollection;
use App\Http\Resources\V1\User\UserResource;
use App\Models\User;
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
            'workshop_id'=>$this->workshop_id,
            'title'=>$this->title,
            'message'=>$this->message,
            'kavenegar_data'=>json_decode($this->kavenegar_data),
            'drivers'=>json_decode($this->drivers),
            'send_at'=>jdate($this->send_at)->format('Y-m-d H:i:s'),
//            'users'=>$this->when(is_null($this->users),'همه کاربران این ورکشاپ درنظر گرفته شود',new UserCollection(User::whereIn('id',json_decode($this->users))->get()))
//            'users'=>$this->when(is_null($this->users),'همه کاربران این ورکشاپ درنظر گرفته شود',UserResource::collection(User::whereIn('id',json_decode($this->users))->get()))
            'users'=>isset($this->users)? UserResource::collection(User::whereIn('id',json_decode($this->users))->get()) : 'همه کاربران این ورکشاپ درنظر گرفته شود',
        ];
    }
}
