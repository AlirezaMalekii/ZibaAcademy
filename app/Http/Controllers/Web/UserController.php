<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Ticket;
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
            return redirect()->route('user-panel-order')->withErrors(['error' => 'اطلاعات سفارش صحیح نمی باشد']);
        }
        $user = array('name' => $loginUser->name . " " . $loginUser->lastname, 'phone' => $loginUser->phone, 'id' => $loginUser->id);
        $order_data = $order->only('id', 'status', 'created_at', 'is_paid', 'status');
        $order_items = $order->items()->get();
        if ($order->is_paid) {
            $payment = $order->payments()->where('payment', 1)->first();
            $code = $payment->code;
            $payment_date = $payment->created_at;
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
/*$loginUser = auth()->user();
$tickets = Ticket::with('order_item.order')
->whereHas('order_item.order', function ($query)
{
    $query->where('is_paid', true);
})
->get();
$user_tickets = $loginUser->tickets()->get();
if (!empty($user_tickets))
{
$c = $user_tickets->pluck('id')->toArray();
$user_create_ticket = $loginUser->create_tickets()->get();
if (!empty($user_create_ticket))
{
$t = $user_create_ticket->pluck('id')->toArray();
}

else {
    $t = [];
}
$data = array_merge($c, $t);
$uniqueArray = array_unique($data);
//dd($tickets,$uniqueArray);
$choisenTicket = $tickets->only([$uniqueArray]);
dd($choisenTicket);
} else {

}*/
//You can achieve this by adding a couple of methods to your User model. First, you need to define a relationship between the User and Order models:
//class User extends Model
//{
//    // ...
//
//    public function orders()
//    {
//        return $this->hasMany(Order::class, 'user_id');
//    }
//}
//This will allow you to retrieve all the orders of a user. Then, you can define another method that filters these orders based on the is_paid column and retrieves the tickets through the OrderItem model:
//class User extends Model
//{
//    // ...
//
//    public function paidTickets()
//    {
//        return $this->hasManyThrough(
//            Ticket::class,
//            OrderItem::class,
//            'order_id',
//            'order_item_id',
//            'id',
//            'id'
//        )->whereHas('order', function ($query) {
//            $query->where('is_paid', true);
//        });
//    }
//}
//In this method, we're using the hasManyThrough relationship to get the tickets that belong to the user's orders. We're also calling the whereHas method to filter the orders based on the is_paid column. This will ensure that we only get the tickets from paid orders.
//Note that you need to define the appropriate relationships in your models as well as the foreign keys. In this example, I assumed that you have a user_id column in the Ticket table, an order_item_id column in the Ticket table, and an order_id column in the OrderItem table.
