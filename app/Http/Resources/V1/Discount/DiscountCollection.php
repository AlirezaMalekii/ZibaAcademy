<?php

namespace App\Http\Resources\V1\Discount;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DiscountCollection extends ResourceCollection
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
                    'id' => $item->id,
                    'code' => $item->code,
//                'active'=>$this->active==1?'فعال':'غیر فعال'
//                    'active' => $item->active ? 'فعال' : 'غیر فعال',
                    'active' => $item->active,
                    'type' => $item->type,
//                    'percent' => isset($item->percent) ? $item->percent . '%' : '_',
//                    'amount' => isset($item->amount) ? 'تومان' . $item->amount : '_',
                    'percent' => $item->percent,
                    'amount' => $item->amount,
                    'use_limit' => $item->use_limit,
                    'expire_date' => jdate($item->expire_date)->format('Y-m-d H:i:s'),
                ];
            }),
        ];
    }
}
