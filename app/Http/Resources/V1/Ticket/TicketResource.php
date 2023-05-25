<?php

namespace App\Http\Resources\V1\Ticket;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
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
            'id'=>$this->id,
            'creator_id'=>$this->creator_id,
            'user_id'=>$this->user_id,
            'token'=>$this->token,
            'workshop_id'=>$this->workshop_id,
            'state'=>$this->order_item->order->is_paid
        ];
    }
}
