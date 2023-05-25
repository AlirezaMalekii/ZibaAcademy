<?php

namespace App\Http\Resources\V1\Payment;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'price'=>$this->price,
            'payment'=>$this->payment,
            'created_at' => jdate($this->created_at)->format('Y-m-d H:i:s'),
//            'updated_at' => jdate($this->updated_at)->format('Y-m-d H:i:s'),
            'code'=>$this->code
        ];
    }
}
