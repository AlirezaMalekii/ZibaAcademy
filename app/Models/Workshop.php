<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workshop extends Model
{
    use HasFactory,Sluggable,SoftDeletes;

    protected $guarded = [];
//    protected $dates = [
//        'event_time',
//        'created_at',
//        'updated_at',
//        'deleted_at'
//    ];
//
//    const UPDATED_AT = 'updated_at';

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
    public function order_items()
    {
        return $this->morphMany(OrderItem::class, 'itemable');
    }
    public function discount_items()
    {
        return $this->morphMany(DiscountItem::class, 'discountable');
    }

    public function gallery()
    {
        return $this->morphOne(Gallery::class, 'galleryable');
    }
    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'workshop_id');
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['title'],
                'onUpdate'=>true
            ]
        ];
    }
}
