<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\DiscountUser;
use App\Models\Order;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Shetabit\Payment\Facade\Payment;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use App\Models\Payment as Pay;

class HomeController extends AdminController
{
    public function index()
    {
        $number_of_workshops = Workshop::all()->count();
        $held_workshops = Workshop::where('event_time', '<', now())->with('city')->get();
        $last_video_of_workshop = Workshop::latest('id')->first()->files()->whereIn('type', ['video', 'aparat'])->first();
//        $last_video_of_workshop = Workshop::find(19)->files()->whereIn('type', ['video', 'aparat'])->first();
        $stream_video = $last_video_of_workshop->type == 'aparat';

        if ($stream_video) {
            $video_url = $last_video_of_workshop->file['htmlCode'];
        } else {
            $video_url = "storage" . $last_video_of_workshop->file['path'];
        }
//        $blogs = Blog::latest()->take(4)->withCount('comments')->get();
        $blogs = Blog::latest()->take(4)->select('id', 'description', 'title', 'viewCount')->withCount(['comments' => function ($query) {
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
            $code=$receipt->getReferenceId();
            $payment->update([
                'payment' => true,
                'code' => $code
            ]);
            $order = $payment->order;
            $order->update([
                'is_paid' => true,
                'status' => 'paid',
            ]);

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
}

