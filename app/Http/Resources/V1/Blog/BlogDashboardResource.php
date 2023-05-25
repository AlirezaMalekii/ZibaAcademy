<?php

namespace App\Http\Resources\V1\Blog;

use App\Models\Blog;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogDashboardResource extends JsonResource
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
            'title'=>$this->title ,
            'viewCount'=>$this->viewCount,
            'cover_image' => $this->files()->where('type','cover')->first()->file['thumb']
        ];
    }
}
