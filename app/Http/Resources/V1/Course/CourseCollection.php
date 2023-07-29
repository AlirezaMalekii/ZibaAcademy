<?php

namespace App\Http\Resources\V1\Course;

use App\Http\Resources\V1\Category\CategoryCollection;
use App\Http\Resources\V1\File\FileResource;
use App\Http\Resources\V1\User\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CourseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'description' => $item->description,
                    'created_at' => jdate($item->created_at)->format('Y-m-d H:i:s'),
                    'files' => FileResource::collection($item->files),
                    'categories' => $item->when($item->categories, new CategoryCollection($item->categories), 'تعریف نشده'),
                    'price' => $item->price,
                    'time'=>$item->time,
                    'discount'=>$item->discount,
                    'status'=>$item->status,
                ];
        });
    }
}
