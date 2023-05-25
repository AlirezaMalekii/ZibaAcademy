<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function Symfony\Component\String\u;

class UserController extends Controller
{
    public function index()
    {
        $user_info = auth()->user();
//       auth()->logout();
//       auth()
        $user = array('name' => $user_info->name . " " . $user_info->lastname, 'phone' => $user_info->phone);
        return view('layouts.user-panel.dashboard', compact('user'));
    }

    public function orders()
    {
        $user_info = auth()->user();
        $orders = Order::where('user_id', $user_info->id)->select('id', 'created_at', 'status', 'total_price')->withCount('items')->latest()->get()->toArray();
//       dd($orders);
        $user = array('name' => $user_info->name . " " . $user_info->lastname, 'phone' => $user_info->phone, 'id' => $user_info->id);
        return view('layouts.user-panel.orders', compact('user', 'orders'));
    }

    public function orders_info(Order $order)
    {
        $loginUser = auth()->user();
        if ($order->user_id != $loginUser->id) {
            return redirect()->route('user-panel-order')->withErrors(['error'=>'اطلاعات سفارش صحیح نمی باشد']);
        }
        $user = array('name' => $loginUser->name . " " . $loginUser->lastname, 'phone' => $loginUser->phone, 'id' => $loginUser->id);
       $order_data= $order->only('id','status','created_at','is_paid','status');
       $order_items=$order->items()->get();
       if ($order->is_paid){
           $payment=$order->payments()->where('payment',1)->first();
          $code= $payment->code;
          $payment_date=$payment->created_at;
       }else{
           $code=null;
           $payment_date=null;
       }
//       dd($order_items);
//       dd($order_items->itemable());
//       foreach ($order_items as $order_item)
//           dd($order_item);
//           dd($order_item->itemable()->get()->first()->title);
//       dd($order_items);
        return view('layouts.user-panel.inside-orders',compact('user','order_data','order_items','code','payment_date'));
    }
    public function logout(Request $request){
        $login_user=auth()->user();
        if ($login_user->level == 'admin')
        {
            $login_user->tokens()->delete();
            $login_user->update([
               'admin_token'=>null,
            ]);
        }
        Auth::guard('web')->logout();
        $request->session()->invalidate();

        $request->session()->regenerateToken();
//        auth()->logout();
        return redirect('/');
    }
}
