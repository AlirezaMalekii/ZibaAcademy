<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'v1', 'middleware' => ['cors'] , 'namespace' => '\App\Http\Controllers\Api\v1'], function () {

    Route::group(['middleware' => ['auth:sanctum']], function () {


        Route::group(['prefix' => 'super-admin', 'as'=>'super.admin.' ,'middleware' => ['ability:level-super_admin'], 'namespace' => '\App\Http\Controllers\Api\v1\Backend\SuperAdmin'], function () {
//            Route::get('user-info/{id}', [UserController::class, 'user_info'])->name('get.user');
//            Route::get('courses/index' , [CourseController::class , 'index'])->name('get.courses');
//            Route::apiResource('organizations', 'OrganizationController');
        });

    });
});
