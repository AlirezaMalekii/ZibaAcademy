<?php

namespace App\Http\Resources\V1\Category;

use App\Http\Resources\V1\User\UserResource;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    protected $showChild;
    public function __construct(mixed $resource,$showChild=true)
    {
        parent::__construct($resource);
        $this->showChild=$showChild;
    }

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
            'creator'=>new UserResource($this->creator),
            'title'=>$this->title,
            'type'=>$this->type,
            'parent'=>new CategoryResource($this->parent,false) ,
            //CategoryResource::collection($this->parent),
            'child'=>$this->when($this->showChild,new CategoryCollection($this->children))
        ];
    }
    public function with($request)
    {
        return [
            'status'=>'ok'
        ];
    }
}
