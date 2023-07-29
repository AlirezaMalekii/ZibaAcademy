<?php

namespace App\Http\Resources\V1\Order;

use App\Http\Resources\V1\Course\CourseResource;
use App\Http\Resources\V1\Ticket\TicketCollection;
use App\Http\Resources\V1\Ticket\TicketResource;
use App\Http\Resources\V1\Workshop\WorkshopResource;
use App\Models\Course;
use App\Models\Workshop;
use Illuminate\Http\Resources\Json\JsonResource;

class OrederItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "order_id" => $this->order_id,
            "itemable_type" => $this->itemable_type == 'App\Models\Workshop' ? 'ورکشاپ' : 'دوره',
            "itemable_id" => $this->itemable_id,
            "item_name"=>$this->itemable_type == 'App\Models\Workshop'? new WorkshopResource(Workshop::find($this->itemable_id),false) : new CourseResource(Course::find($this->itemable_id),false),
            "quantity" => $this->quantity,
            "price" => $this->price,
            "created_at" => jdate($this->created_at)->format('Y-m-d H:i:s'),
            'ticket' => new TicketCollection($this->tickets()->get()),
        ];
    }
}
