<?php

namespace App\Http\Resources\V1\Comment;

use App\Models\Comment;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'id' => $this->id,
            'creator_id' => $this->creator_id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->mail,
            'parent_id' => $this->parent_id,
            'approved' => $this->approved ? "تایید شده" : "تایید نشده",
            'comment' => $this->comment,
            'commentable_type' => $this->commentable_type,
            'commentable_id' => $this->commentable_id,
            'created_at' => jdate($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => jdate($this->updated_at)->format('Y-m-d H:i:s'),
            'rely'=>new CommentCollection($this->comments,false)
        ];
    }
}
