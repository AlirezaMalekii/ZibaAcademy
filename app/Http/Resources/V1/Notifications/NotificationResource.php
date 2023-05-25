<?php

namespace App\Http\Resources\v1\Notification;

use App\Http\Resources\v1\CourseCollection;
use App\Http\Resources\v1\CRM\UserResource;
use App\Http\Resources\v1\ImageResource;
use App\Http\Resources\v1\Order\OrderResource;
use App\Http\Resources\v1\Organization\OrganizationCollection;
use App\Http\Resources\v1\Organization\OrganizationResource;
use App\Http\Resources\v1\User;
use App\Http\Resources\v1\UserCollection;
use App\Models\Classroom;
use App\Models\Teacher;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->notifiable_type === "App\\Models\\User") {
            $notifiable = new UserResource($this->notifiable);
        }
        if ($this->type === 'App\\Notifications\\VideoEncryptionCompletedNotification') {
            $type = "VideoEncryption";
            $episode_title = $this->data['videoable'] ? $this->data['videoable']['title'] : null;
            $title = "عملیات رمزنگاری ویدیو قسمت $episode_title با موفقیت انجام شد. ";
            $data = [
                'video_id' => $this->data['video_id'] ?? null,
                'episode_id' => $this->data['videoable'] ? $this->data['videoable']['id'] : null,
                'episode_title' => $episode_title,
            ];
        }

        if ($this->type === 'App\\Notifications\\NotifyUserOfCompletedImport') {
            $type = "ExcelImport";
            $classroom_title = $this->data['classroom_id'] ? isset(Classroom::where('id' , $this->data['classroom_id'])->first()->id) ? Classroom::where('id' , $this->data['classroom_id'])->first()->title : null : null;
            $title = " فایل اکسل آپلود کاربران کلاس $classroom_title با موفقیت پردازش شد. ";
            $data = [
                'count_of_users_successfully_added' => $this->data['count_of_users_successfully_added'] ?? null,
                'count_of_users_failed_to_add' => $this->data['count_of_users_successfully_added'] ?? null,
                'failures' => $this->data['failures'] ?? null,
            ];
        }

        return [
            'id' => $this->id,
            'title' => $title,
            'type' => $type,
            'notifiable' => $notifiable,
            'data' => $data,
            'read_at' => jdate($this->read_at)->format('Y-m-d H:i:s'),
            'updated_at' => jdate($this->updated_at)->format('Y-m-d H:i:s'),
            'created_at' => jdate($this->created_at)->format('Y-m-d H:i:s')
        ];
    }

//    public function with($request)
//    {
//        return [
//            'status' => 'ok'
//        ];
//    }
}
