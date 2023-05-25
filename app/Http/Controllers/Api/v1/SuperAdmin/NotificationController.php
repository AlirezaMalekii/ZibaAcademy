<?php

namespace App\Http\Controllers\Api\v1\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Notification\NotificationCollection;
use App\Http\Resources\v1\Notification\NotificationResource;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $all_notifications = Notification::latest()->paginate(50);
//        return response([
//            'notifications' => new NotificationCollection($all_notifications)
//        ]);
    }

    public function user_notifications($user_id , $latest = null)
    {
        $user = User::where('id' , $user_id)->first();
        if (isset($user->id)){
            if ($latest){
                $notifications = $user->notifications()->latest()->take($latest)->get();
            }else{
                $notifications = $user->notifications()->latest()->paginate(50);
            }
            return  new NotificationCollection($notifications , $user->unreadNotifications->count());

        }else{
            return response([
                'message' => "کاربر مورد نظر موجود نیست."
            ]);
        }

    }

    public function mark_as_read($id)
    {
        $notification = Notification::where('id' , $id)->first();
        $notification->markAsRead();
        return response([
            'notification' => $notification
        ]);
    }

    public function get_all_user_unread_notifications($user_id , $latest = null)
    {
        $user = User::where('id' , $user_id)->first();
        $unread_notifications = $user->unreadNotifications()->latest();
        if ($latest){
            $latest_unread_notifications = $unread_notifications->take($latest)->get();
            return new NotificationCollection($latest_unread_notifications ,  $unread_notifications->count());
        }else{
            $all_user_unread_notifications = $unread_notifications->paginate(50);
            return new NotificationCollection($all_user_unread_notifications ,  $unread_notifications->count());
        }

    }

    public function mark_all_of_user_unread_notifications_as_read($user_id)
    {
        $user = User::where('id' , $user_id)->first();
        $user->unreadNotifications->markAsRead();

        return response([
            'message' => "وضعیت همه اعلام های خوانده نشده شما به خوانده شده تغییر کرد.",
            'status' => "success"
        ]);
    }

    public function delete_all_user_notifications($user_id)
    {
        $user = User::where('id' , $user_id)->first();
        $user->notifications()->delete();
        return response([
            'message' => "تمام اعلامیه های شما حذف شد.",
            'status' => "success"
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return NotificationResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $notification = Notification::where('id' , $id)->first();
        if (isset($notification->id)){
            return new NotificationResource($notification);
        }else{
            return response([
                'message' => "اعلامیه مورد نظر پیدا نشد.",
                'status' => "not-found"
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notification = Notification::where('id' , $id)->first();
        $notification->delete();
        return response([
            'message' => " اعلامیه حذف شد.",
            'status' => "success"
        ]);
    }
}
