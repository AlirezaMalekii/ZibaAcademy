<?php

namespace App\Http\Resources\V1\Course;

use App\Http\Resources\V1\Category\CategoryCollection;
use App\Http\Resources\V1\File\FileResource;
use App\Http\Resources\V1\User\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CourseDashboardCollection extends ResourceCollection
{
    protected $without_countsell;

    public function __construct($resource, $without_countsell = true)
    {
        parent::__construct($resource);
        $this->without_countsell = $without_countsell;
    }
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
            if ($this->without_countsell) {
                return [
                    'title' => $item->title,
                    'viewCount' => $item->viewCount,
                    'cover_image' => $item->files()->where('type', 'cover')->first()->file['thumb']
                ];
            } else {
                return [
                    'title' => $item->title,
                    //'viewCount' => $item->viewCount,
                    'cover_image' => $item->files()->where('type', 'cover')->first()->file['thumb'],
                    'count_sell' => $item->order_items_count,
                ];
            }
        });
    }
}
