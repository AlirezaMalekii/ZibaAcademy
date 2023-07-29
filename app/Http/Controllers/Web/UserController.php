<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Notification\NotificationCollection;
use App\Models\Course;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

use function Symfony\Component\String\u;

class UserController extends Controller
{
    public function index()
    {
        $user_info = auth()->user();
        $notifications = $user_info->notifications()->latest()->paginate(50);
        $user = array('name' => $user_info->name . " " . $user_info->lastname, 'phone' => $user_info->phone);
//        $notification=$notifications->first();
//        dd($notification->data);
        return view('layouts.user-panel.dashboard', compact('user','notifications'));
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
            return redirect()->route('user-panel-order')->withErrors(['error' => 'اطلاعات سفارش صحیح نمی باشد']);
        }
        $user = array('name' => $loginUser->name . " " . $loginUser->lastname, 'phone' => $loginUser->phone, 'id' => $loginUser->id);
        $order_data = $order->only('id', 'status', 'created_at', 'is_paid', 'status');
        $order_items = $order->items()->get();
        if ($order->is_paid) {
            $payment = $order->payments()->where('payment', 1)->first();
            $code = $payment->code;
            $payment_date = $payment->created_at;
            foreach ($order_items as $order_item){
                if ($order_item->itemable instanceof Course && empty($order_item->spotplayer()->first()))
                    return redirect()->route('user-panel-order')->withErrors(['error' => 'شما این دوره را خریداری کرده اید. ولی به دلایلی لایسنس مورد نظر برای شما ساخته نشده است. لطفا با پشتیبانی تماس بگیرید']);
            }
        } else {
            $code = null;
            $payment_date = null;
        }
//       dd($order_items);
//       dd($order_items->itemable());
//       foreach ($order_items as $order_item)
//           dd($order_item);
//           dd($order_item->itemable()->get()->first()->title);
//       dd($order_items);
        return view('layouts.user-panel.inside-orders', compact('user', 'order_data', 'order_items', 'code', 'payment_date'));
    }

    public function logout(Request $request)
    {
        $login_user = auth()->user();
        if ($login_user->level == 'admin') {
            //dd($login_user->getRememberToken());
            $login_user->tokens()->delete();
            $login_user->update([
                'admin_token' => null,
            ]);
        }
        Auth::guard('web')->logout();
        $request->session()->invalidate();

        $request->session()->regenerateToken();
//        auth()->logout();
        return redirect('/');
    }

    public function tickets()
    {
        $loginUser = auth()->user();
        $tickets = Ticket::with('order_item.order')
            ->whereHas('order_item.order', function ($query) {
                $query->where('is_paid', true);
            })
            ->get();
        $user_tickets = $loginUser->tickets()->get();

        $c = $user_tickets->pluck('id')->toArray();
        $user_create_ticket = $loginUser->create_tickets()->get();
        $t = $user_create_ticket->pluck('id')->toArray();

        $data = array_merge($c, $t);
        $uniqueArray = array_unique($data);
        $choisenTicket = $tickets->only($uniqueArray);
        $choisenTicket = $choisenTicket->filter(function ($ticket) {
            return $ticket->workshop && $ticket->workshop->exists;
        });
       // dd($choisenTicket);
        $user = array('name' => $loginUser->name . " " . $loginUser->lastname, 'phone' => $loginUser->phone, 'id' => $loginUser->id);
        return view('layouts.user-panel.tickets', compact('choisenTicket', 'user'));
    }

    public function info()
    {
        $loginUser = auth()->user();
        $loginInfo['name'] = $loginUser->name;
        $loginInfo['lastname'] = $loginUser->lastname;
        $loginInfo['phone'] = $loginUser->phone;
        $loginInfo['email'] = $loginUser->email ?? '';
        $user = array('name' => $loginUser->name . " " . $loginUser->lastname, 'phone' => $loginUser->phone, 'id' => $loginUser->id);
        return view('layouts.user-panel.info', compact('loginInfo', 'user'));
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
            return  back()->withErrors(['error'=>'یافت نشد.']);
        }

    }
    public function update(Request $request)
    {
        $user = auth()->user();

        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
//            'phone' => ['required', 'digits:11', Rule::unique('users')->ignore($user->id)],
            'email' => [Rule::excludeIf(!isset($request->email)), 'string', 'Email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'level' => [Rule::excludeIf(!isset($request->level)), 'string', 'max:30'],
            'password' => [Rule::excludeIf(!isset($request->password)), 'confirmed','string', 'min:8', 'max:20', Rules\Password::defaults()],
           // 'active' => 'required|boolean'
        ]);

        if (key_exists('password', $fields)) {
            $fields = array_merge($fields, ['password' => bcrypt($fields['password'])]);
        }
            $user->update($fields);
        $user = array('name' => $user->name . " " . $user->lastname, 'phone' => $user->phone);
        return view('layouts.user-panel.dashboard', compact('user'));
    }
}
