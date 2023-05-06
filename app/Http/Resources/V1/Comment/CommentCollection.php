<?php

namespace App\Http\Resources\V1\Comment;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentCollection extends ResourceCollection
{
    protected $pagination;

    public function __construct(mixed $resource, $pagination = true)
    {
        parent::__construct($resource);
        $this->pagination = $pagination;

    }

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (!$this->pagination) {
            return parent::toArray($request);
        } else {
            return [
                'data' => $this->collection->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'creator_id' => $item->creator_id,
                        'name' => $item->name,
                        'parent_id' => $item->parent_id,
                        'approved' => $item->approved,
                        'created_at' => jdate($item->created_at)->format('Y-m-d H:i:s'),
                    ];
                })
            ];
        }
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
