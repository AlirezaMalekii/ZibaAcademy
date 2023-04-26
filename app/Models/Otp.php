<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'phone',
        'code',
        'expire',
        'used'
    ];

//    usage ==> Otp::createCode($user)->code;
    public function scopeCreateCode($query,$user)
    {
        $code = $this->code();
        return $query->create([
            'user_id' => $user->id,
            'phone' => $user->phone,
            'code' => $code,
            'expire' => Carbon::now()->addSeconds(120)
        ]);
    }

    /**
     * @throws \Exception
     */
    private function code()
    {
        do {
            $code = random_int(100001 , 999999999);
            $check_code = static::whereCode($code)->get();
        } while(!$check_code->isEmpty());
        return $code;
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
