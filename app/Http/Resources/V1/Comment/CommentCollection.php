<?php

namespace App\Http\Resources\V1\Comment;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);

//        return $this->collection->map(function ($item){
//            return [
//                'id'=>$item->id,
//                'creator_id'=>$item->creator_id,
//                'name'=>$item->name,
//                'phone'=>$item->phone,
//                'email'=>$item->mail,
//                'parent_id'=>$item->parent_id,
//                'approved'=>$item->approved,

//            ];
//        });
    }
}
