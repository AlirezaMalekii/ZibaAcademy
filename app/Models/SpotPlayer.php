<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotPlayer extends Model
{
    use HasFactory;
    protected $fillable = [
        'license_id','url','license_key'
    ];
    public function order_item()
    {
        //return $this->belongsTo(OrderItem::class, 'order_item_id');
        return $this->hasOne(OrderItem::class, 'order_item_id');
    }
}
