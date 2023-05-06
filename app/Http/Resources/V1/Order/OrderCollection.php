<?php

namespace App\Http\Resources\V1\Order;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($item) {
                return [
                  'id'=>$item->id,
                    'user_id'=>$item->user_id,
                    'discount_amount'=>$item->discount_amount,
                    'total_price'=>$item->total_price,
                    'is_paid'=>$item->is_paid ? "پرداخت ثبت نشده": "پرداخت شده"
                ];
            })
        ];

    }
}
