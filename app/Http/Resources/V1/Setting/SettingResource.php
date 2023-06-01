<?php

namespace App\Http\Resources\V1\Setting;

use App\Http\Resources\V1\File\FileResource;
use App\Http\Resources\V1\Ticket\TicketResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
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
            'title_home' => $this->title_home,
            'body_home' => $this->body_home,
            'files'=>new FileResource($this->files()->get()->first()),
            'updated_at' => jdate($this->updated_at)->format('Y-m-d H:i:s'),

        ];
    }
}
