<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id' , 'order_id' , 'transaction_id' ,'price' ,'payment' ,'code'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class , 'creator_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
