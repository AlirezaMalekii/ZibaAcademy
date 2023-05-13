<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\WorkshopController;
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

//example

//view
Route::view('register','example.register');
Route::view('login-with-otp','example.login-with-otp');
Route::view('login-with-password','example.login_with_password');
//endview
//end example
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
Route::group(['namespace' => '\App\Http\Controllers\Web'], function () {
    Route::get('/',[HomeController::class,'index'])->name('home');
    Route::get('workshops',[WorkshopController::class,'index'])->name('workshops');
    Route::get('workshops/{workshop:slug}',[WorkshopController::class,'show'])->name('choose-workshop');
});
Route::group(['namespace' => '\App\Http\Controllers\Web','middleware' => ['auth:sanctum']], function () {
    Route::get('/workshop-register/{workshop:slug}',[WorkshopController::class,'workshop_register'])->name('workshop_register');
});
Route::view('login','layouts.auth.login-with-password')->name('login');


