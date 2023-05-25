<?php

namespace App\Http\Resources\V1\Gallery;

use App\Http\Resources\V1\File\FileResource;
use App\Http\Resources\V1\Workshop\WorkshopResource;
use Illuminate\Http\Resources\Json\JsonResource;

class galleryResource extends JsonResource
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
//            'type_of_file'=>$this->fileable_type ,
//            'type_of_file_id'=>$this->fileable_id ,
//            'file'=>$this->file,
            'title'=>$this->title,
           'file'=> FileResource::collection($this->files),
            'created_at' => jdate($this->created_at)->format('Y-m-d H:i:s'),
            'workshop_cover'=>$this->galleryable()->first()->files()->where('type','cover')->first()
        ];
    }
}
