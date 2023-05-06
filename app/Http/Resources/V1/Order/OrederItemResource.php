<?php

namespace App\Http\Resources\V1\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class OrederItemResource extends JsonResource
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
            "id"=>$this->id,
                "order_id"=>$this->order_id,
                "itemable_type"=>$this->itemable_type,
                "itemable_id"=>$this->itemable_id,
                "quantity"=>$this->quantity,
                "price"=>$this->price,
                "created_at"=>jdate($this->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
