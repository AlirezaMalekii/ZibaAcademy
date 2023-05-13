<?php

namespace App\Http\Resources\V1\User;

use App\Http\Resources\V1\Ticket\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInfoResource extends JsonResource
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
            'name' => $this->name,
            'lastname'=>$this->lastname,
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'province' => $this->province,
            'city' => $this->city,
            'created_at' => jdate($this->created_at)->format('Y-m-d H:i:s'),
            'created_by'=>$this->created_by,
            'level'=>$this->level,
            'active'=>$this->active,
            'tickets'=>new TicketResource(Ticket::find(1)),
        ];
    }
}
