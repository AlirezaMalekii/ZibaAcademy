<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Web\BlogController;
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

//testing
Route::get('state_of_login', function () {
    if (auth()->check())
        return 'yes';
    return 'no';
});
Route::get('logout', function () {
    if (auth()->check()) {
        if (auth()->user()->level === 'admin')
        {
            auth()->user()->tokens()->delete();
        }
        auth()->logout();
    }
    auth()->loginUsingId(2);
    return 'no';
});
Route::group(['namespace' => '\App\Http\Controllers\Web'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('workshops', [WorkshopController::class, 'index'])->name('workshops');
    Route::get('workshops/{workshop:slug}', [WorkshopController::class, 'show'])->name('choose-workshop');
    Route::get('blogs', [BlogController::class, 'index'])->name('blogs');
    Route::get('/blogs/search', [BlogController::class, 'search'])->name('blog.search');
});
Route::group(['namespace' => '\App\Http\Controllers\Web', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/workshop-register/{workshop:slug}', [WorkshopController::class, 'workshop_register'])->name('workshop_register');
    Route::get('/workshop-reservation/workshop/{workshop:slug}/order/{order}', [WorkshopController::class, 'workshop_reservation'])->name('workshop_reservation');
    Route::post('/create-order/{workshop:slug}', [WorkshopController::class, 'create_order'])->name('create-order');
    Route::post('/payment/workshop/{workshop:slug}/order/{order}', [WorkshopController::class, 'workshop_payment'])->name('workshop_payment');
    Route::get('/payment/check', [HomeController::class, 'check_order'])->name('check_order');
    Route::get('/workshop/payment-success/{order}',[WorkshopController::class, 'payment_success'])->name('workshop.payment-success');
    Route::post('/workshop/{workshop:slug}/create-new-comment', [WorkshopController::class, 'create_comment'])->name('workshop_create_comment');
    Route::post('/workshop/{workshop:slug}/set-discount', [WorkshopController::class, 'set_discount'])->name('set.discount.workshop');
});
Route::middleware('guest')->namespace('App\Http\Controllers')->group(function () {
    Route::get('login/password', 'AuthController@login')->name('login');
    Route::get('login/otp', 'AuthController@forget_password')->name('otp');
    Route::get('register', 'AuthController@register')->name('register');
    Route::post('register', [AuthController::class, 'register_user'])->name('register-user');
    Route::post('login-with-otp', [AuthController::class, 'login_with_otp'])->name('lwo');
    Route::post('login-with-password', [AuthController::class, 'login_with_password'])->name('lwp');
    Route::post('make-otp', [AuthController::class, 'create_otp'])->name('make-otp');
});
Route::middleware('auth:sanctum')->prefix('/user-panel')->namespace('App\Http\Controllers\Web')->group(function () {
    Route::get('/dashboard', 'UserController@index')->name('user_panel');
    Route::get('/orders', 'UserController@orders')->name('user-panel-order');
    Route::get('/order-info/{order}', 'UserController@orders_info')->name('order-info');
    Route::get('/order/{order}/continue', 'PaymentController@re_payment')->name('continue_order');
    Route::post('logout','UserController@logout')->name('logout');
});



