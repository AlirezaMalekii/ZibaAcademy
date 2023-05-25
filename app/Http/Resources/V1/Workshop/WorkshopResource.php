<?php

namespace App\Http\Resources\V1\Workshop;

use App\Http\Resources\V1\Category\CategoryCollection;
use App\Http\Resources\V1\City\CityResource;
use App\Http\Resources\V1\File\FileResource;
use App\Http\Resources\V1\Gallery\galleryResource;
use App\Http\Resources\V1\User\UserResource;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkshopResource extends JsonResource
{
    protected $detail;

    public function __construct($resource, $datail = true)
    {
        parent::__construct($resource);
        $this->detail = $datail;
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->detail) {
            return [
                'id' => $this->id,
                'creator' => new UserResource(User::find($this->creator_id)),
                'title' => $this->title,
                'description' => $this->description,
                'body' => $this->body,
                'slug' => $this->slug,
                'created_at' => jdate($this->created_at)->format('Y-m-d H:i:s'),
//            'event_time'=>jdate($this->event_time)->format('Y-m-d H:i:s'),
                'event_time' => \Carbon\Carbon::parse($this->event_time)->timestamp,
                'capacity' => $this->capacity,
                'files' => FileResource::collection($this->files),
                'categories' => $this->when($this->categories, new CategoryCollection($this->categories), 'تعریف نشده'),
                'gallery' => new galleryResource($this->gallery),
                'city' => new CityResource(City::find($this->city_id)),
                'period' => $this->period,
                'price' => $this->price
            ];
        } else {
            return [
                'id' => $this->id,
                'title' => $this->title,
            ];
        }
    }
}
