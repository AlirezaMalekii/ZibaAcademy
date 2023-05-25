<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeCreateToken()
    {
        do {
            $token = Str::random(60);
            $check_token = static::whereToken($token)->get();
        } while (!$check_token->isEmpty());
        return $token;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function workshop()
    {
        return $this->belongsTo(Workshop::class, 'workshop_id');
    }

    public function order_item()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

}
