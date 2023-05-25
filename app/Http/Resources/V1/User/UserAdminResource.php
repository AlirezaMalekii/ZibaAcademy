<?php

namespace App\Http\Resources\V1\User;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAdminResource extends JsonResource
{
    protected $token;
    public function __construct(mixed $resource,$token)
    {
        parent::__construct($resource);
        $this->token=$token;
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
            'message' => "ادمین بودن کاربر محرز شد.",
            'user_id' => $this->id,
            'user_name' => $this->name,
            'token' => $this->token,
            'status'=>'success'
        ];
    }
}
