<?php

namespace App\Http\Resources\V1\File;

use App\Http\Resources\V1\User\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
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
//            'creator' => new UserResource(User::find($this->creator_id)),
            'type_of_file'=>$this->fileable_type ,
            'type_of_file_id'=>$this->fileable_id ,
            'file'=>$this->file,
            'type'=>$this->type,
            'file_name'=>$this->file_name,
            'created_at' => jdate($this->created_at)->format('Y-m-d H:i:s')
        ];
    }
}
