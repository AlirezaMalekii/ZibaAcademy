<?php

namespace App\Http\Resources\V1\Workshop;

use App\Http\Resources\V1\Category\CategoryCollection;
use App\Http\Resources\V1\File\FileResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WorkshopCollection extends ResourceCollection
{
    protected $without_gallery;

    public function __construct(mixed $resource, $without_gallery = true)
    {
        parent::__construct($resource);
        $this->without_gallery = $without_gallery;

    }

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
            if ($this->without_gallery) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'description' => $item->description,
                    'created_at' => jdate($item->created_at)->format('Y-m-d H:i:s'),
                    'files' => FileResource::collection($item->files),
                    'categories' => $this->when($item->categories, new CategoryCollection($item->categories), 'تعریف نشده'),
                    'period'=>$item->period,
                    'price'=>$item->price
                ];
            }else{
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                ];
            }
        });
    }
}
