<?php

namespace App\Http\Resources\V1\Blog;

use App\Http\Resources\V1\Category\CategoryCollection;
use App\Http\Resources\V1\File\FileResource;
use App\Http\Resources\V1\User\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
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
            'id' => $this->id,
            'creator' => new UserResource(User::find($this->creator_id)),
            'title'=>$this->title ,
            'description'=>$this->description ,
            'body'=>$this->body,
            'slug'=>$this->slug,
            'file_name'=>$this->file_name,
            'viewCount'=>$this->viewCount,
            'created_at' => jdate($this->created_at)->format('Y-m-d H:i:s'),
            'files'=> FileResource::collection($this->files),
            'categories'=>$this->when($this->categories,new CategoryCollection($this->categories),'تعریف نشده')
        ];
    }
}
