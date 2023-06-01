<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\AdminController;
use App\Models\Blog;
use App\Models\Discount;
use App\Models\DiscountUser;
use App\Models\Payment as Pay;
use App\Models\Ticket;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Payment\Facade\Payment;

class HomeController extends AdminController
{
    public function index()
    {
        $number_of_workshops = Workshop::all()->count();
        if ($number_of_workshops != 0) {
            $held_workshops = Workshop::where('event_time', '<', now())->with('city')->get();
            $last_video_of_workshop = Workshop::latest('id')->first()->files()->whereIn('type', ['video', 'aparat'])->first();
//        $last_video_of_workshop = Workshop::find(19)->files()->whereIn('type', ['video', 'aparat'])->first();
            $stream_video = $last_video_of_workshop->type == 'aparat';

            if ($stream_video) {
                $video_url = $last_video_of_workshop->file['htmlCode'];
            } else {
                $video_url = "storage" . $last_video_of_workshop->file['path'];
            }
        } else {
            $held_workshops = [];
            $stream_video = 'video';
            $video_url = '/images/workshop-video.mp4';
        }
        //dd($number_of_workshops);
//        $blogs = Blog::latest()->take(4)->withCount('comments')->get();
        $blogs = Blog::latest()->take(4)->select('id', 'description', 'title', 'viewCount', 'slug')->withCount(['comments' => function ($query) {
            $query->where('approved', 1)->latest();
        }])->with('categories:title')->with(['files' => function ($query) {
            $query->where('type', 'cover');
        }])->get();
//        dd($blogs);
        return view('layouts.index', compact('number_of_workshops', 'held_workshops', 'blogs', 'video_url', 'stream_video'));
    }

    public function check_order(Request $request)
    {

// You need to verify the payment to ensure the invoice has been paid successfully.
// We use transaction id to verify payments
// It is a good practice to add invoice amount as well.
        try {
//            $receipt = Payment::amount(1000)->transactionId($transaction_id)->verify();
            $transaction_id = $request->Authority;
            $payment = Pay::where('transaction_id', $transaction_id)->first();
            if (empty($payment)) {
                return redirect()->route('user-panel-order')->withErrors('اطلاعات صحیح نمی باشد');
            }
            $price = $payment->price;
            $receipt = Payment::amount($price)->transactionId($transaction_id)->verify();
//            dd($receipt->getReferenceId());
            $code = $receipt->getReferenceId();
            $payment->update([
                'payment' => true,
                'code' => $code
            ]);
            $order = $payment->order;
            $order->update([
                'is_paid' => true,
                'status' => 'paid',
            ]);
            $discount_id = $order->discount_id;
            $order_item = $order->items()->get()->first();
            $all_tickets_user = $order_item->tickets()->pluck('user_id')->toArray();
            if (!is_null($discount_id)) {
                $discount = Discount::find($discount_id);

                if ($discount->type == 'private') {
                    //dd($all_tickets_user);
                    foreach ($all_tickets_user as $user) {
                        $discount->users()->detach($user);
                        $discount->users()->attach($user, ['used_at' => now()]);
                    }
                    /* DB::transaction(function() use ($discount, $all_tickets_user) {
                         foreach ($all_tickets_user as $user) {
                             $discount->users()->where('user_id', $user)->detach();
                             $discount->users()->attach($user, ['used_at' => now()]);
                         }
                     });*/
                    //$discount->users()->sync($all_tickets_user, ['used_at' => now()]);
                } else {
                    foreach ($all_tickets_user as $tickets_user) {
                        DiscountUser::create([
                            'user_id' => $tickets_user,
                            'discount_id' => $discount->id,
                            'used_at' => now()
                        ]);
                    }
                }
            }
            return redirect()->route('workshop.payment-success', compact('order'));
            // You can show payment referenceId to the user.
//            echo $receipt->getReferenceId();

        } catch (InvalidPaymentException $exception) {
            /**
             * when payment is not verified, it will throw an exception.
             * We can catch the exception to handle invalid payments.
             * getMessage method, returns a suitable message that can be used in user interface.
             **/
            return redirect()->route('user-panel-order')->withErrors($exception->getMessage());
        }
    }

    public function show_ticket($token)
    {
        $ticket = Ticket::where('token', $token)->first();
        if (!$ticket) {
            return redirect()->route('home')->withErrors(['error' => 'توکن یافت نشد']);
        }
        $paid = $ticket->order_item->order->is_paid;
        if ($paid) {
            return view('layouts.show-ticket' , compact('ticket'));
        } else {
            return redirect()->route('home')->withErrors(['error' => 'پس از پرداخت بلیط شما فعال می شود']);
        }
    }
}

