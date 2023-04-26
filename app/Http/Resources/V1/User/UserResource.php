<?php

namespace App\Http\Resources\V1\User;

use Illuminate\Http\Resources\Json\JsonResource;
use function jdate;

class UserResource extends JsonResource
{

//    protected $detail;

    public function __construct(mixed $resource, $detail = true)
    {
        parent::__construct($resource);
//        $this->detail = $detail;

    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name."". $this->lastname,
            'phone' => $this->phone,
        ];

    }

}
