<?php

namespace App\Http\Resources\V1\Course;

use App\Http\Resources\V1\Category\CategoryCollection;
use App\Http\Resources\V1\File\FileResource;
use App\Http\Resources\V1\User\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    protected $detail;

    public function __construct($resource, $datail = true)
    {
        parent::__construct($resource);
        $this->detail = $datail;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->detail) {
            return [
                'id' => $this->id,
                'creator' => new UserResource(User::find($this->creator_id)),
                'title' => $this->title,
                'description' => $this->description,
                'body' => $this->body,
                'slug' => $this->slug,
                'created_at' => jdate($this->created_at)->format('Y-m-d H:i:s'),
                'files' => FileResource::collection($this->files),
                'categories' => $this->when($this->categories, new CategoryCollection($this->categories), 'تعریف نشده'),
                'price' => $this->price,
                'time'=>$this->time,
                'discount'=>$this->discount,
                'status'=>$this->status,
                'viewCount'=>$this->viewCount,
                'prerequisite'=>$this->prerequisite,
                'section_count'=>$this->section_count,
                'episode_count'=>$this->episode_count,
                'support_way'=>$this->support_way,
                'delivery_way'=>$this->delivery_way,
                'spotplayer_course_id'=>$this->spotplayer_course_id,
                'level'=>$this->level,
            ];
        } else {
            return [
                'id' => $this->id,
                'title' => $this->title,
            ];
        }
    }
}
