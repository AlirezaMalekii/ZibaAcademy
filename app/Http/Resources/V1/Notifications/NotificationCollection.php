<?php

namespace App\Http\Resources\v1\Notification;

use App\Http\Resources\v1\CRM\UserResource;
use App\Models\Classroom;
use Illuminate\Http\Resources\Json\ResourceCollection;

class NotificationCollection extends ResourceCollection
{

    protected $count_of_all_unread_notifications;
    public function __construct($resource , $count_of_all_unread_notifications = null)
    {
        parent::__construct($resource);
        $this->count_of_all_unread_notifications = $count_of_all_unread_notifications;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection
     */
    public function toArray($request)
    {

        return $this->collection->map(function ($item) {

            if ($item->notifiable_type === "App\\Models\\User") {
                $notifiable = new UserResource($item->notifiable);
            }
            if ($item->type === 'App\\Notifications\\VideoEncryptionCompletedNotification') {
                $type = "VideoEncryption";
                $episode_title = $item->data['videoable'] ? $item->data['videoable']['title'] : null;
                $title = "عملیات رمزنگاری ویدیو قسمت $episode_title با موفقیت انجام شد. ";
                $data = [
                    'video_id' => $item->data['video_id'] ?? null,
                    'episode_id' => $item->data['videoable'] ? $item->data['videoable']['id'] : null,
                    'episode_title' => $episode_title,
                ];
            }

            if ($item->type === 'App\\Notifications\\NotifyUserOfCompletedImport') {
                $type = "ExcelImport";
                $classroom_title = $item->data['classroom_id'] ? isset(Classroom::where('id' , $item->data['classroom_id'])->first()->id) ? Classroom::where('id' , $item->data['classroom_id'])->first()->title : null : null;
                $title = "فایل اکسل آپلود کاربران کلاس $classroom_title با موفقیت پردازش شد. ";
                $data = [
                    'count_of_users_successfully_added' => $item->data['count_of_users_successfully_added'] ?? null,
                    'count_of_users_failed_to_add' => $item->data['count_of_users_successfully_added'] ?? null,
                    'failures' => $item->data['failures'] ?? null,
                ];
            }

            return [
                'id' => $item->id,
                'title' => $title,
                'type' => $type,
                'notifiable' => $notifiable,
                'data' => $data,
                'read_at' => $item->read_at ? jdate($item->read_at)->format('Y-m-d H:i:s') : null,
                'updated_at' => jdate($item->updated_at)->format('Y-m-d H:i:s'),
                'created_at' => jdate($item->created_at)->format('Y-m-d H:i:s')
            ];

        });
    }

    public function with($request)
    {
        return [
            'count_of_all_unread_notifications' => $this->count_of_all_unread_notifications
        ];
    }
}
