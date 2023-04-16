<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;


    public function scopeCreateToken()
    {
        do {
            $token = Str::random(60);
            $check_token = static::whereToken($token)->get();
        } while(!$check_token->isEmpty());
        return $token;
    }


    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
