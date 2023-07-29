<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'quantity',
        'price'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function itemable()
    {
        return $this->morphTo();
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    public function spotplayer(){
        return $this->hasOne(SpotPlayer::class, 'order_item_id');
    }
}
