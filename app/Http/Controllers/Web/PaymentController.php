<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\DiscountUser;
use App\Models\Order;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;
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
        $order_id = $order->id;
        if ($order->discount_id != null) {
            $discount = $order->discount;
            if (($discount->expire_date != null) && $discount->expire_date < now()) {
                return back()->withErrors(['error' => 'تاریخ انقضای این کد تخفیف به سر آماده است! ']);
            }
            if (!$discount->active) {
                return back()->withErrors(['error' => 'این کد تخفیف فعال نمی باشد']);
            }
            $discountItems = $discount->discount_items()->distinct()->get(['discountable_id', 'discountable_type']);
            $orderItems = $order->items()->distinct()->get(['itemable_id', 'itemable_type']);

// Check if all $orderItems exist in $discountItems
            $allExist = $orderItems->every(function ($orderItem) use ($discountItems) {
                return $discountItems->contains(function ($discountItem) use ($orderItem) {
                    return $discountItem->discountable_id === $orderItem->itemable_id
                        && $discountItem->discountable_type === $orderItem->itemable_type;
                });
            });
            if (!$allExist) {
                return back()->withErrors(['OrderPaid' => 'این کد تخفیف برای همه موارد در خواستی در نظر گرفته نشده است.']);
            }
        }

        $workshops = Workshop::withTrashed()->whereHas('order_items', function ($query) use ($order_id) {
            $query->where('order_id', $order_id);
        })->get();

        //dd($workshops);
        foreach ($workshops as $workshop) {
            if ((!$workshop->exists()) || $workshop->trashed()) {
                return back()->withErrors(['registegerError' => 'ورکشاپ مورد نظر حذف شده است']);
            }
            $workshop_order_item = $workshop->order_items()->where('order_id', $order_id)->first();
            $workshop_quantity = $workshop_order_item->quantity;
            if ($workshop_quantity + $workshop->registration_number > $workshop->capacity) {
                return back()->withErrors(['registegerError' => 'ظرفیت ورکشاپ از تقاضای شما بیشتر است']);
            }
            if ($workshop->event_time < now()) {
                return back()->withErrors(['registegerError' => 'ورکشاپ برگذار شده است']);
            }
            $count_ticket = count($workshop_order_item->tickets()->get());
            if ($count_ticket != $workshop_quantity) {
                if ($count_ticket > $workshop_quantity) {
                    return redirect()->route('home')->withErrors(['unHappendError' => 'ارور ناشناخته ای اتفاق افتاده است']);
                }
                if ($count_ticket > 0) {
                    $workshop_order_item->tickets()->delete();
                }
                return redirect()->route('workshop_reservation', [$workshop->slug, $order->id]);
            }
            if ($order->discount_id != null) {
                $discount = $order->discount;
                $du = DiscountUser::where('discount_id', $discount->id)->whereNotNull('used_at')->get();
                if ($discount->use_limit != null) {
                    if (count($du) + $workshop_order_item->quantity > $discount->use_limit) {
                        return redirect()->route('workshop_register', $workshop)->withErrors(['error' => 'متاسفیم! تعداد استفاده از این کد تخفیف بیش از درخواست شما است']);
                    }
                }
                $all_tickets_user = $workshop_order_item->tickets()->pluck('user_id')->toArray();
                $discount_user = DiscountUser::where('discount_id', $discount->id)->whereNotNull('used_at')->pluck('user_id')->toArray();
                //dd($all_tickets_user,$discount_user,array_intersect($all_tickets_user, $discount_user));
                if (!empty(array_intersect($all_tickets_user, $discount_user))) {
                    return redirect()->route('workshop_register', $workshop)->withErrors(['error' => 'این کد تخفیف برای خود یا همکارانی که قصد خرید بلیط دارید استفاده شده است']);
                }
                if ($discount->type == 'private') {
                    $discount_user = DiscountUser::where('discount_id', $discount->id)->whereNull('used_at');
                    $discount_user_array = $discount_user->pluck('user_id')->toArray();
                    if (!(count(array_intersect($all_tickets_user, $discount_user_array)) === count($all_tickets_user))) {
                        return redirect()->route('workshop_register', $workshop)->withErrors(['error' => 'این کد تخفیف برای خود یا همکارانی که قصد خرید بلیط دارید در نظر گرفته نشده است']);
                    }
                }
            }
        }
        $courses = Course::withTrashed()->whereHas('order_items', function ($query) use ($order_id) {
            $query->where('order_id', $order_id);
        })->get();
        //dd($courses);
        $loginUser = auth()->user();

        foreach ($courses as $course) {
            if ((!$course->exists()) || $course->trashed()) {
                return back()->withErrors(['registegerError' => 'دوره مورد نظر حذف شده است']);
            }
            if ($course->status != 'active') {
                return redirect()->route('courses')->withErrors(['activeCourseError' => 'دوره مورد نظر فعال نمی باشد']);
            }
            $courses = $course->order_items()->whereHas('order', function ($query) use ($loginUser) {
                $query->where('is_paid', 1)->where('user_id', $loginUser->id);
            });
            //dd($courses->latest()->first()->id);
            if (count($courses->get()) > 0) {
                return redirect()->route('order-info',['order'=>$courses->latest()->first()->order_id])->withErrors(['error' => 'برای شما قبلا برای این دوره لایسنس ساخته شده است.']);
            }
            //dd(2);
            $course_order_item = $course->order_items()->where('order_id', $order_id)->first();
//           dd($course_order_item);
            if ($order->discount_id != null) {
                $discount = $order->discount;
                $du = DiscountUser::where('discount_id', $discount->id)->whereNotNull('used_at')->get();
                /*
                $user_used_discount = $du->contains('user_id', $loginUserId);
                if ($user_used_discount) {
                    return redirect()->route('course_register', ['course' => $course->slug])->withErrors(['error' => 'این کد تخفیف قبلا استفاده شده است']);
                }
                if ($discount->type == 'private') {
                    $du_doesnot_use = DiscountUser::where('discount_id', $discount->id)->whereNull('used_at')->get();
                    if (!$du_doesnot_use->contains('user_id', $loginUserId)) {
                        return redirect()->route('course_register', ['course' => $course->slug])->withErrors(['error' => 'این کد تخفیف برای شما در نظر گرفته نشده است']);
                    }
                }*/
                if ($discount->use_limit !== null) {
                    if (count($du) + 1 > $discount->use_limit) {
                        return redirect()->route('course_register', $course->slug)->withErrors(['error' => 'متاسفیم! تعداد استفاده از این کد تخفیف بیش از درخواست شما است']);
                    }
                }

//                $discount_user = DiscountUser::where('discount_id', $discount->id)->whereNotNull('used_at')->pluck('user_id')->toArray();
//                if (!empty(array_intersect($loginUser->id, $discount_user))) {
//                    return redirect()->route('course_register', $course)->withErrors(['error' => 'کد تخفیف استفاده شده است']);
//                }
                $user_used_discount = $du->contains('user_id', $loginUser->id);
                if ($user_used_discount) {
                    return redirect()->route('course_register', ['course' => $course->slug])->withErrors(['error' => 'این کد تخفیف قبلا استفاده شده است']);
                }

                if ($discount->type == 'private') {
                    $du_doesnot_use = DiscountUser::where('discount_id', $discount->id)->whereNull('used_at')->get();
                    if (!$du_doesnot_use->contains('user_id', $loginUser->id)) {
                        return redirect()->route('course_register', ['course' => $course->slug])->withErrors(['error' => 'این کد تخفیف برای شما در نظر گرفته نشده است']);
                    }
//                    $discount_user = DiscountUser::where('discount_id', $discount->id)->whereNull('used_at');
//                    $discount_user_array = $discount_user->pluck('user_id')->toArray();
//                    if (!(count(array_intersect($loginUser->id, $discount_user_array)) === 1)) {
//                        return redirect()->route('course_register', $course)->withErrors(['error' => 'این کد تخفیف برای شما در نظر گرفته نشده است']);
//                    }
                }
            }
        }
        $payments = $order->payments();
        $get_payments = $payments->get();

        if ($get_payments->first()) {
            $success_payment = $payments->where('payment', 1)->get();
            if ($success_payment->first()) {
                $order->update([
                    'is_paid' => true,
                    'status' => 'paid'
                ]);
                return redirect()->route('workshop.payment-success', compact('order'));
            }
        }
        $invoice = (new Invoice)->amount($order->total_price);
//        $invoice->detail(['order_item' => $order_item->id]);
//        $driver='zarinpal';
        return Payment::purchase($invoice, function ($driver, $transactionId) use ($order, $loginUser) {
            // Store transactionId in database as we need it to verify payment in the future.
            $order->payments()->create([
                'creator_id' => $loginUser->id,
                'transaction_id' => $transactionId,
                'price' => $order->total_price,
            ]);
        })->pay()->render();
    }

    public function cancel_payment(Order $order)
    {
        if ($order->is_paid) {
            return back()->withErrors(['OrderPaid' => 'این سفارش پرداخت شده است']);
        }
        if ($order->status == 'cancel') {
            return back()->withErrors(['OrderPaid' => 'این سفارش کنسل شده است']);
        }
        if ($order->status == 'paid') {
            return back()->withErrors(['OrderPaid' => 'این سفارش پرداخت شده است']);
        }
        $order_id = $order->id;
        $workshops = Workshop::whereHas('order_items', function ($query) use ($order_id) {
            $query->where('order_id', $order_id);
        })->get();
        foreach ($workshops as $workshop) {
            $workshop_order_item = $workshop->order_items()->where('order_id', $order_id)->first();
            $tickets = $workshop_order_item->tickets()->get();
            foreach ($tickets as $ticket) {
                $ticket->delete();
            }
        }
        $order->update([
            'status' => 'cancel'
        ]);
        $user_info = auth()->user();
        $orders = Order::where('user_id', $user_info->id)->select('id', 'created_at', 'status', 'total_price')->withCount('items')->latest()->get()->toArray();
//       dd($orders);
        $user = array('name' => $user_info->name . " " . $user_info->lastname, 'phone' => $user_info->phone, 'id' => $user_info->id);
        return view('layouts.user-panel.orders', compact('user', 'orders'));
    }
}
