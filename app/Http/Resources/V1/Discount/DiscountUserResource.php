<?php

namespace App\Http\Resources\V1\Discount;

use App\Http\Resources\V1\User\UserResource;
use App\Models\Discount;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        return parent::toArray($request);
        return [
            'user'=>new UserResource(User::find($this->user_id)),
            'discount'=>new DiscountResource(Discount::find($this->discount_id),true),
            'used_at'=>jdate($this->used_at)->format('Y-m-d H:i:s'),
            'created_at'=>jdate($this->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
