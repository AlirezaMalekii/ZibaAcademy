<?php

namespace App\Http\Resources\V1\Order;

use App\Http\Resources\V1\User\UserResource;
use App\Models\User;
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
                    'user'=>new UserResource(User::find($item->creator_id)),
//                    'user_id'=>$item->id,
                    'discount_amount'=>$item->discount_amount,
                    'total_price'=>$item->total_price,
                    'status'=>$item->status,
                    'is_paid'=>$item->is_paid,
                    "created_at"=>jdate($item->created_at)->format('Y-m-d H:i:s'),
                ];
            })
        ];

    }
}
