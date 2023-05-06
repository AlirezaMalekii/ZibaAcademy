<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function creator()
    {
        return $this->belongsTo(User::class , 'creator_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'parent_id', 'id');
    }
    public function setCommentAttribute($value)
    {
        $this->attributes['comment']=str_replace(PHP_EOL,"</br>",$value);
    }
    public function commentable()
    {
        return $this->morphTo();
    }

}
