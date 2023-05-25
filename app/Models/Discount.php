<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use HasFactory , SoftDeletes;

    protected $guarded = [];


    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('used_at');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function discount_items()
    {
        return $this->hasMany(DiscountItem::class,'discount_id');
    }
    public function scopeFilter($query)
    {
        $keyword = request('keyword');
        if (isset($keyword) && trim($keyword) != '') {
            $query->where('code' , $keyword)
                ->orWhere('id' , $keyword)
            ;


        }
        return $query;
    }
}
