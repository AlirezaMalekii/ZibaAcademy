<?php

namespace App\Http\Resources\V1\Workshop;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkshopDashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'registration_number' => $this->registration_number,
            'cover_image' => $this->files()->where('type','cover')->first()->file['thumb']
        ];
    }
}
