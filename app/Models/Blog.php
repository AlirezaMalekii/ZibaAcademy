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
}
