<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\User\UserAdminResource;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function check_admin(Request $request)
    {
        if (!$request->checkAdminCode){
            return response([
                'message' => 'کدی ارسال نشده است.',
                'status' => 'failed'
            ]);
        }
        if(!($user=User::where('admin_token',$request->checkAdminCode)->first())){
            return response([
                'message' => 'کد ارسال شده صحیح نمی باشد.',
                'status' => 'failed'
            ]);
        }
    $token = $user->createToken('AuthAdminLoginToken')->plainTextToken;
        /*  $response = [
              'message' => "ثبت نام شما با موفقیت انجام شد.لطفا وارد حساب کابری خود شوید.",
              'user_id' => $user->id,
              'user_name' => $user->name,
  //            'token' => $token
          ];*/
//        return response($response, 201);
        return new UserAdminResource($user,$token);
    }
}
