<?php

namespace App\Http\Resources\V1\Discount;

use App\Models\Course;
use App\Models\Workshop;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountItemResource extends JsonResource
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
            'type'=>$this->discountable_type=='App\Models\Course'?'course':'workshop',
            'type_id'=>$this->discountable_id,
            'created_at'=>jdate($this->created_at)->format('Y-m-d H:i:s'),
            'title'=>$this->discountable_type=='App\Models\Course'? Course::find($this->discountable_id)->title : Workshop::find($this->discountable_id)->title
        ];
    }
}
