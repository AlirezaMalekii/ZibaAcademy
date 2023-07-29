<?php

namespace App\Http\Resources\V1\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseDashboardResource extends JsonResource
{
    protected $without_countsell;

    public function __construct($resource, $without_countsell = true)
    {
        parent::__construct($resource);
        $this->without_countsell = $without_countsell;
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->without_countsell) {
            return [
                'title' => $this->title,
                'viewCount' => $this->viewCount,
                'cover_image' => $this->files()->where('type', 'cover')->first()->file['thumb']
            ];
        } else {
            return [
                'title' => $this->title,
                'viewCount' => $this->viewCount,
                'cover_image' => $this->files()->where('type', 'cover')->first()->file['thumb'],
                'count_sell' => $this->order_items_count,
            ];
        }
    }
}
