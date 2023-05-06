<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class File extends Model
{
    use HasFactory;

//    protected $guarded = [];
    protected $fillable=['creator_id','file','type','file_name','extension','accessibility'];
    protected $casts = [
        'file' => 'array',
    ];
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function fileable()
    {
        return $this->morphTo();
    }
}
