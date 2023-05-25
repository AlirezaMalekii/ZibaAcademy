<?php

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


//Route::group(['prefix' => 'v1', 'middleware' => ['cors'] , 'namespace' => '\App\Http\Controllers\Api\v1'], function () {
Route::group(['prefix' => 'v1', 'namespace' => '\App\Http\Controllers\Api\v1'], function () {
    Route::post('check-admin', [\App\Http\Controllers\Api\v1\AdminController::class, 'check_admin']);

    Route::group(['middleware' => ['auth:sanctum']], function () {
        //        Route::group(['prefix' => 'super-admin', 'as'=>'super.admin.' ,'middleware' => ['ability:level-super_admin'], 'namespace' => '\App\Http\Controllers\Api\v1\Backend\SuperAdmin'], function () {
        Route::group(['prefix' => 'super-admin', 'as' => 'super.admin.', 'namespace' => '\App\Http\Controllers\Api\v1\SuperAdmin'], function () {
            Route::apiResource('categories', 'CategoryController');
            Route::apiResource('users', 'UserController');
            Route::apiResource('blogs', 'BlogController');
            Route::apiResource('workshops', 'WorkshopController');
            Route::apiResource('kavenegar-templates', 'KavehnegarController');
            Route::apiResource('announcements', 'AnnouncementController');
            Route::apiResource('notifications', 'NotificationController');
            Route::apiResource('galleries', 'GalleryController');
            Route::apiResource('discounts', 'DiscountController');
            Route::post('/galleries/delete-files/{gallery_id}', 'GalleryController@delete_files');
            Route::get('/workshops-without-gallery', 'GalleryController@workshops_without_gallery');
            Route::get('/superadmin-info', 'SettingController@info');
            Route::put('/superadmin-update', 'SettingController@update');
            Route::get('/orders', 'OrderController@index');
            Route::get('/discount/use/users', 'DiscountController@discount_user');
            Route::get('/orders/{id}', 'OrderController@show');
            Route::get('/search/user','UserController@search');
           // Route::get('/search/blog','BlogController@search');
//            Route::get('/categories/all/blogs','CategoryController@all_category_blog')->name('all-category-blog');
//            Route::get('/categories/all/workshops','CategoryController@all_category_workshop')->name('all-category-workshop');
            Route::post('/upload-image', 'FileController@store');
            Route::post('/upload-video', 'FileController@store_video');
            Route::post('/confirm-comment/{id}', 'CommentController@confirm');
            Route::post('/cancellation-of-approval/{id}', 'CommentController@cancellation_approval');
            Route::get('/order/{id}/payments', 'OrderController@payments');
            Route::get('/comments', 'CommentController@index');
            Route::get('/comments/{id}', 'CommentController@show');
            Route::get('/unverified-comments', 'CommentController@unverified_comments');
            Route::post('/reply-comment/{id}', 'CommentController@reply_comment');
            Route::get('/provinces', 'ProvinceAndCityController@province');
            Route::get('/cities/{id}', 'ProvinceAndCityController@cities');
            Route::get('/dashboard', 'DashboardController@index');
//            Route::get('user-info/{id}', [UserController::class, 'user_info'])->name('get.user');
//            Route::get('courses/index' , [CourseController::class , 'index'])->name('get.courses');
//            Route::apiResource('organizations', 'OrganizationController');

            Route::get('get-user-notifications/{user_id}/{latest?}', [\App\Http\Controllers\Api\v1\SuperAdmin\NotificationController::class, 'user_notifications'])->name('get.user.notifications');
            Route::get('notification-mark-as-read/{id}', [\App\Http\Controllers\Api\v1\SuperAdmin\NotificationController::class, 'mark_as_read'])->name('notification.mark.as.read');
            Route::get('get-all-user-unread-notifications/{user_id}/{latest?}', [\App\Http\Controllers\Api\v1\SuperAdmin\NotificationController::class, 'get_all_user_unread_notifications'])->name('get.all.user.unread.notification');
            Route::get('mark-all-of-user-unread-notifications-as-read/{user_id}', [\App\Http\Controllers\Api\v1\SuperAdmin\NotificationController::class, 'mark_all_of_user_unread_notifications_as_read'])->name('mark.all.user.notifications.as.read');
            Route::get('delete-all-user-notifications/{user_id}', [\App\Http\Controllers\Api\v1\SuperAdmin\NotificationController::class, 'delete_all_user_notifications'])->name('delete.all.user.notifications');
        });

    });
});
