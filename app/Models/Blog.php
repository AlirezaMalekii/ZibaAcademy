<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory,Sluggable,SoftDeletes;

    protected $fillable=[
        'creator_id','title','description','body'
    ];
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
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
    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
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
}
