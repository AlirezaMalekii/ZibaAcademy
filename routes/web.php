<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//example
//view
Route::view('register','example.register');
Route::view('login-with-otp','example.login-with-otp');
Route::view('login-with-password','example.login_with_password');
//endview

//testing
Route::get('state_of_login',function (){
   if (auth()->check())
       return 'yes';
   return 'no';
});
Route::get('logout',function (){
   if (auth()->check()){
       if (auth()->user()->level==='admin'){
           auth()->user()->tokens()->delete();
       }
       auth()->logout();
   }
   return 'no';
});
//endtesting
//end example

Route::group([],function (){
    Route::post('register',[AuthController::class,'register'])->name('register');
    Route::post('login-with-otp',[AuthController::class,'login_with_otp'])->name('lwo');
    Route::post('login-with-password',[AuthController::class,'login_with_password'])->name('lwp');
    Route::post('make-otp',[AuthController::class,'create_otp'])->name('make-otp');
});



