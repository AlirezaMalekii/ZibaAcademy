<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable=[
        'creator_id',
        'title'
    ];

    public function galleryable()
    {
        return $this->morphTo();
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
//    public function workshop()
//    {
//        return $this->morphOne(Workshop::class, 'galleryable');
//    }
}
