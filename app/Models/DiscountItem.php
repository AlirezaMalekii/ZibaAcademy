<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountItem extends Model
{
    use HasFactory;
    protected $fillable=[
      'discount_id'
    ];
    protected $table='discount_item';
}
