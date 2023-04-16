<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;


    public function galleryable()
    {
        return $this->morphTo();
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
