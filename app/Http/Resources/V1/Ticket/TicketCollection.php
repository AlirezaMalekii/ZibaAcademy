<?php

namespace App\Http\Resources\V1\Ticket;

use App\Http\Resources\V1\User\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TicketCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($item) {
                return [
                    'id' => $item->id,
                    'creator' =>new UserResource(User::find($item->creator_id)) ,
                    'user' => new UserResource(User::find($item->user_id)) ,
                    'token' => $item->token,
                    'workshop_id' => $item->workshop_id,
                    'state' => $item->order_item->order->is_paid
                ];
            }),
        ];
    }
}
