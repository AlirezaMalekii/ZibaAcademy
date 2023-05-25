<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Workshop;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class PaymentController extends Controller
{
    public function re_payment(Order $order)
    {
        if ($order->is_paid) {
            return back()->withErrors(['OrderPaid' => 'این سفارش پرداخت شده است']);
        }
        if ($order->status == 'cancel') {
            return back()->withErrors(['OrderPaid' => 'این سفارش کنسل شده است']);
        }
//        $workshops=Workshop::order_items()->where('order_id',$order->id)->get();
        $order_id=$order->id;
        $workshops = Workshop::whereHas('order_items', function ($query) use ($order_id) {
            $query->where('order_id', $order_id);
        })->get();
//        dd($workshops);
        foreach ($workshops as $workshop){
           $workshop_quantity= $workshop->order_items()->where('order_id',$order_id)->first()->quantity;
           if ($workshop_quantity + $workshop->registration_number >$workshop->capacity){
               return back()->withErrors(['registegerError'=>'ظرفیت ورکشاپ از تقاضای شما بیشتر است']);
           }
        }
        $payments = $order->payments();
        $get_payments = $payments->get();
        if ($get_payments->first()) {
            $success_payment = $payments->where('payment', 1)->get();
            if ($success_payment->first()){
                $order->update([
                   'is_paid'=>true,
                    'status'=>'paid'
                ]);
                return redirect()->route('workshop.payment-success',compact('order'));
            }
        } else {
            dd(123);

        }
    }
}
