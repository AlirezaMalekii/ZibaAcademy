<?php

namespace App\Http\Resources\V1\Discount;

use App\Http\Resources\V1\User\UserResource;
use App\Models\DiscountItem;
use App\Models\DiscountUser;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{
    protected $create;

    public function __construct($resource, $create = false)
    {
        parent::__construct($resource);
        $this->create = $create;
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (!$this->create) {
            return [
                'id' => $this->id,
                'code' => $this->code,
//                'active' => $this->active ? 'فعال' : 'غیر فعال',
                'active' => $this->active,
                'creator' => new UserResource(User::find($this->creator_id)),
                'type' => $this->type,
//                'percent'=>isset($this->percent)?$this->percent.'%':'_',
//                'amount'=>isset($this->amount)?'تومان'.$this->amount:'_',
                'percent'=>$this->percent,
                'amount'=>$this->amount,
                'use_limit'=>$this->use_limit,
                'expire_date' => isset($this->expire_date)?jdate($this->expire_date)->format('Y-m-d H:i:s'):null,
                'created_at'=>jdate($this->created_at)->format('Y-m-d H:i:s'),
                'discount_item'=>DiscountItemResource::collection($this->discount_items),
                'discount_user'=>$this->type=='private'? DiscountUserResource::collection(DiscountUser::where('discount_id',$this->id)->get()):null,
            ];
        } else {
            return [
                'id' => $this->id,
                'code' => $this->code,
//                'active'=>$this->active==1?'فعال':'غیر فعال'
//                'active' => $this->active ? 'فعال' : 'غیر فعال'
            ];
        }
    }
}
