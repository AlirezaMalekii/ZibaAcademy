<?php

namespace App\Http\Resources\V1\Order;

use App\Http\Resources\V1\Ticket\TicketResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrederResource extends JsonResource
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
            'user_id'=>$this->user_id,
            'creator_id'=>$this->creator_id,
            'discount_amount'=>$this->discount_amount,
            'discount_id'=>$this->discount_id,
            'total_price'=>$this->total_price,
            'is_paid'=>$this->is_paid ? "پرداخت شده": "پرداخت نشده",
            'type'=>$this->type,
            'payment_gate'=>$this->payment_gate,
            'created_at' => jdate($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => jdate($this->updated_at)->format('Y-m-d H:i:s'),
            'order_item'=>OrederItemResource::collection($this->items),
            //'ticket'=>TicketResource::collection($this->tickets()),
            'status'=>$this->status
        ];
    }
}
