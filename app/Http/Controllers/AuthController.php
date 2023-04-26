<?php

namespace App\Http\Controllers;

use App\Events\ForgetPassword;
use App\Events\UserLoginOtp;
use App\Http\Controllers\AdminController;
use App\Listeners\SendSmsWithOtp;
use App\Models\Otp;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class AuthController extends AdminController
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|Email|max:255|unique:users',
            'phone' => 'required|digits:11|unique:users',
            'password' => ['required', 'confirmed','string','min:8',Rules\Password::defaults()],
        ]);
        $data_user=array_merge($fields,['password' => bcrypt($fields['password'])]);
        $user = User::create($data_user);
        auth()->loginUsingId($user->id);
        return redirect()->intended(RouteServiceProvider::HOME);

//        $token = $user->createToken('AuthUserRegisterToken')->plainTextToken;
      /*  $response = [
            'message' => "ثبت نام شما با موفقیت انجام شد.لطفا وارد حساب کابری خود شوید.",
            'user_id' => $user->id,
            'user_name' => $user->name,
//            'token' => $token
        ];*/
//        return response($response, 201);
    }

    public function login_with_password(Request $request)
    {

        $fields = $request->validate([
            'phone' => 'required|digits:11',
            'password' => 'required|string|min:6',
        ]);
//        Check User phone
        $user = User::where('phone', $fields['phone'])->first();
//        $user_role = "user";
//        Check User Password
        if (!$user || !Hash::check($fields['password'],$user->password) || !$user->active) {
            return back()->withErrors(['error'=>'نام کاربری یا پسورد اشتباه است']);
        }
        if ($user->level==='admin'){
            $token=self::code(User::class,'admin_token',70);
            $user->update(['admin_token'=>$token]);
        }
        auth()->loginUsingId($user->id);
        return redirect()->intended(RouteServiceProvider::HOME);

//        if (!$user->active) {
//            $user->update([
//                'active' => true
//            ]);
//        }


        /*if ($user->hasRole("super_admin")) {
            $token = $user->createToken('AuthSuperAdminLoginToken', ["level-super_admin"])->plainTextToken;
            $user_role = "super_admin";
        } else if ($user->hasAnyRole()) {
            $permissions = [];
            foreach ($user->role()->get() as $role) {
                foreach ($role->permissions()->get() as $permission) {
                    $permissions[] = $permission->name;
                }
            }
            $token = $user->createToken('AuthAdminLoginToken', array_merge(["level-admin"], $permissions))->plainTextToken;
            $user_role = "admin";
        } else {
            $token = $user->createToken('AuthUserLoginToken', ["level-user"])->plainTextToken;
            $user_role = "user";
        }*/

//        $quiz_controller = new QuizController();
//        $get_user_by_phone_response = $quiz_controller->get_user_by_phone($user->phone);
//        if (isset($get_user_by_phone_response->data[0])){
//            $quiz_user_id = $get_user_by_phone_response->data[0]->id;
//        }else{
//            $quiz_register_response = $quiz_controller->register($user->name, $user->phone, $fields['password']);
//            $quiz_user_id = $quiz_register_response->user_id;
//        }
//        if ($user_role === "user"){
//            $quiz_controller->remove_role_from_user($quiz_user_id);
//        }else{
//            $quiz_controller->add_role_to_user(1 , $quiz_user_id);
//        }

//        $quiz_login_response = $quiz_controller->login($user->phone, $fields['password']);
//        if (isset($quiz_login_response->token)) {
//            $user->update([
//                'quiz_token' => $quiz_login_response->token
//            ]);
//        }

/*        return response([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'token' => $token,
//            'quiz_token' => $user->quiz_token,
            'role' => $user_role
        ], 201)
            ->withCookie(cookie('token', $token, 10080)->withHttpOnly(true)->withSecure(true))
            ->withCookie(cookie('user_name', $user->name, 10080)->withHttpOnly(true)->withSecure(true))
            ->withCookie(cookie('role', $user_role, 10080)->withHttpOnly(true)->withSecure(true));*/
    }

    public function create_otp(Request $request){
        $fields = $request->validate([
            'phone' => 'required|digits:11',
        ]);
        if ($user=User::wherePhone($request->only('phone'))->first()){
            if ($user->active){
                event(new UserLoginOtp($user));
                $phone=$request->input('phone');
                return redirect('login-with-otp')->with(compact('phone'));
            }
        }else{
            return redirect('login-with-otp')->withErrors(['findError'=>'یافت نشد']);
        }


    }

    public function login_with_otp(Request $request){
        $fields = $request->validate([
            'phone' => 'required|digits:11',
            'code' => 'required|digits:9'
        ]);
        $authenticationCode = Otp::whereCode($request->input('code'))->first();

        if (!$authenticationCode) {
            return back()->withErrors(['error'=>'این کد فعال سازی وجود ندارد']);
        }

        if ($authenticationCode->expire < Carbon::now()) {
            return back()->withErrors(['error'=>'این کد فعال منقضی شده است.']);

        }

        if ($authenticationCode->used) {
            return back()->withErrors(['error'=>'این کد استفاده شده است.']);
        }

        $authenticationCode->update([
            'used' => true
        ]);



        if ($authenticationCode->user->level==='admin'){
            $token=self::code(User::class,'admin_token',70);
            $authenticationCode->user->update(['admin_token'=>$token]);
        }
        auth()->loginUsingId($authenticationCode->user->id);
        return redirect()->intended(RouteServiceProvider::HOME);



    }

    public function authentication(Request $request)
    {
        $authenticationCode = ActivationCode::whereCode($request->input('code'))->first();

        if (!$authenticationCode) {
            return response([
                'message' => ' این کد فعال سازی وجود ندارد. ',
                'status' => 'failed'
            ], 403);
        }

        if ($authenticationCode->expire < Carbon::now()) {
            return response([
                'message' => ' کد فعال سازی منقضی شده. ',
                'status' => 'failed'
            ], 403);
        }

        if ($authenticationCode->used == true) {
            return response([
                'message' => ' کد فعال سازی استفاده شده ',
                'status' => 'failed'
            ], 403);
        }


        if (!$authenticationCode->user->active) {
            $authenticationCode->user()->update([
                'active' => true
            ]);

        }

        $authenticationCode->update([
            'used' => true
        ]);


        $authenticationCode->user()->update([
            'api_token' => Str::random(60),
        ]);

        auth()->loginUsingId($authenticationCode->user->id);


        return new UserResource(auth()->user());

    }

    public function check_api_token($api_token)
    {
        $user = User::where('api_token', $api_token)->first();
        if (isset($user->id)) {
            return response([
                'data' => [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'status' => 'ok'
            ], 200);
        } else {
            return response([
                'message' => 'این توکن اعتبار لازم را ندارد.',
                'status' => 'unauthenticated'
            ], 403);
        }
    }

    public function forget_request(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();
        if (isset($user->phone)) {
            new ForgetPassword($user);
            return \response([
                'message' => 'کد تایید هویت ارسال شد.لطفا کد ارسال شده به شماره تلفن را در فیلد مربوطه وارد نمایید.',
                'status' => 'ok'
            ]);
        } else {
            return \response([
                'message' => 'شماره تلفن در سیستم ثبت نشده است.',
                'status' => 'failed'
            ]);
        }

    }

    public function forget_confirm(Request $request)
    {
        $code = ActivationCode::where('code', $request->code)->first();
        $user = $code->user()->first();

        if (!$code) {
            return response([
                'message' => ' این کد تایید هویت وجود ندارد. ',
                'status' => 'failed'
            ], 403);
        }

        if ($code->expire < Carbon::now()) {
            return response([
                'message' => ' کد تایید هویت منقضی شده. ',
                'status' => 'failed'
            ], 403);
        }

        if ($code->used == true) {
            return response([
                'message' => ' کد تایید هویت استفاده شده ',
                'status' => 'failed'
            ], 403);
        }

        if (!$code->user->active) {
            $code->user()->update([
                'active' => true
            ]);

        }

        $code->update([
            'used' => true
        ]);

        $new_password = Str::random(8);

        $response = $this->send_sms_lookup([
            'receptor' => $code->phone,
            'template' => "NewPassword",
            'token' => $new_password,
        ]);
        $user->update([
            'password' => bcrypt($new_password)
        ]);

        return \response([
            'message' => "پسورد شما با موفقیت تغییر کرد.پسورد جدید به شماره تلفن ثبت شده در سیستم توسط شما، ارسال شد.در صورت عدم دریافت پیامک مربوطه ،لطفا تا 15 دقيقه صبر نماييد و سپس مجددا به دريافت پسورد جديد از طريق فراموشي پسورد اقدام بفرماييد.لطفا پس از ورود به حساب کاربری با پسورد جدید، اقدام به تعویض پسورد خود کنید.",
            'status' => 'success'
        ]);
    }


    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response([
            'message' => 'logged out'
        ]);
    }
}
