<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory,Sluggable,SoftDeletes;
    protected $guarded = [];
    public function scopeFilter($query)
    {

        $keyword = request('keyword');
        if (isset($keyword) && trim($keyword) != '') {
            $exploded = explode(' ', $keyword);
            $banned_words = ['و', 'های'];
            $query->where('title', 'LIKE', '%' . $keyword . '%');
            foreach ($exploded as $unique_key) {

                if (!in_array($unique_key, $banned_words) && !is_numeric($unique_key)) {

//                    if (stripos($keyword, $unique_key) !== false) {
                    $query->orWhere('title', 'LIKE', '%' . $unique_key . '%');
//                        $query->orWhere('title', 'sounds like',  '%' . $unique_key . '%' );
//                    }
                }

            }
            $query->orWhere('title', '=', $keyword);

        }
        return $query;
    }
    public function creator()
    {
        return $this->belongsTo(User::class , 'creator_id');
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
    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class);
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
