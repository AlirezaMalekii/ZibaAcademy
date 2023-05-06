<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Announcement extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable=[
        'workshop_id','users','title','message','kavenegar_data','drivers','send_at','status'
    ];
    protected $casts = [
        'users' => 'array',
        'kavenegar_data'=>'array',
        'drivers'=>'array'
    ];
}
