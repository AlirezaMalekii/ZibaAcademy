<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    use HasFactory;

    protected $guarded = [];



    public function creator()
    {
        return $this->belongsTo(User::class , 'creator_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class , 'city_id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function gallery()
    {
        return $this->morphOne(Gallery::class, 'galleryable');
    }
}
