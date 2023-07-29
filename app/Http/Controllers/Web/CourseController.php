<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Discount;
use App\Models\DiscountUser;
use App\Models\Order;
use App\Models\SpotPlayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Validation\Rule;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;

class CourseController extends AdminController
{
    public function check_buy_course(Course $course)
    {
        $loginUser = auth()->user();
        $already_have_this_course = array();
        $courses = $course->order_items()->whereHas('order', function ($query) use ($loginUser) {
            $query->where('is_paid', 1)->where('user_id', $loginUser->id);
        });
//        dd($courses->get());
        //dd($courses->latest()->first()->id);
        if (count($courses->get()) > 0) {
            $already_have_this_course = [true, $courses->latest()->first()->order_id];
//            dd($already_have_this_course[1],$courses->get());
        } else {
            $already_have_this_course = [false, null];
        }
        return $already_have_this_course;
    }

    public function index()
    {
        $courses = Course::filter()->where('status', 'active')->select('level', 'title', 'price', 'id', 'slug')->with(['files' => function ($query) {
            $query->where('type', 'cover');
        }])->get()->toArray();
        return view('layouts.course.index', compact('courses'));
    }

    public function show(Course $course)
    {
        $course->increment('viewCount');
        $course_data = $course->only('slug', 'price', 'title', 'body', 'id');
//        $array_time=explode(":",$course->time);
//        $course_data['hour']=Str::startsWith($array_time[0],"0")?explode('0',$array_time[0]):$array_time[0];
//        $course_data['minutes']=Str::startsWith($array_time[1],"0");
//        $time = '06:30:00';
//        $time_parts = explode(':', $time);
//        $hour = ltrim($time_parts[0], '0');
//        $minute = ltrim($time_parts[1], '0');
        $time = $course->time;
        //$carbon = \Carbon\Carbon::parse($time);
        $carbon = explode(':', $time);
        // dd($carbon);
        $hour = Str::startsWith($carbon[0], '0') ? Str::after($carbon[0], '0') : $carbon[0];
        $minute = Str::startsWith($carbon[1], '0') ? Str::after($carbon[1], '0') : $carbon[1];
        $image = $course->files()->where('type', 'banner')->select('file')->get()->toArray();
        //$course_data['price'] = $this->convertToPersianNumber(number_format($course_data['price'], 0, '،', '،'));
        $course_data['prerequisite'] = $course->prerequisite ?? 'ندارد';
        $course_data['section_count'] = $course->section_count == 0 ? '' : $course->section_count;
        $course_data['support_way'] = $course->support_way ?? '';
        $course_data['delivery_way'] = $course->delivery_way ?? '';
        $course_data['level'] = $course->level ?? '';
        $video_of_course = $course->files()->whereIn('type', ['video', 'aparat'])->first();
        $stream_video = $video_of_course->type == 'aparat';
        if ($stream_video) {
            $video_url = $video_of_course->file['htmlCode'];
        } else {
            $video_url = $video_of_course->file['path'];
        }
        SEOMeta::setTitle($course_data['title']);
        SEOMeta::setDescription($course->description);
        SEOMeta::addMeta('workshop:published_time', $course->created_at->toW3CString(), 'property');
        SEOMeta::addKeyword([$course['title']]);

        JsonLd::setTitle($course_data['title']);
        JsonLd::setDescription($course->description);
        JsonLd::setType('Course');
        JsonLd::addImage(url($image[0]['file']['thumb']));
        $comments = $course->comments()->where('approved', true)->where('parent_id', 0)->select('id', 'name', 'comment', 'created_at')->with('comments')->get()->toArray();
        $already_purchased = [false, null];
        if (auth()->check() && $this->check_buy_course($course)[0]) {
            $already_purchased = $this->check_buy_course($course);
        }
        // dd($already_purchased);
        return view('layouts.course.show', compact('course_data', 'stream_video', 'video_url', 'image', 'minute', 'hour', 'comments', 'already_purchased'));
    }

    public function create_comment(Course $course, Request $request)
    {
        $data = $request->validate([
            'comment' => 'string|required|max:511',
        ]);
        $loginUser = auth()->user();
        $course->comments()->create([
            'creator_id' => $loginUser->id,
            'name' => $loginUser->name . " " . $loginUser->lastname,
            'comment' => $data['comment'],
        ]);
        return back()->with('success', 'کامنت شما ثبت شد. پس از بازبینی در سایت قرار خواهد گرفت');
    }

    public function course_register(Course $course)
    {
        $already_buy_this_course = $this->check_buy_course($course);
        if ($already_buy_this_course[0]) {
            return redirect()->route('order-info', ['order' => $already_buy_this_course[1]])->withErrors(['error' => 'برای شما قبلا برای این دوره لایسنس ساخته شده است.']);
        }
        $course_data = $course->only('slug', 'price');
        $image = $course->files()->where('type', 'cover')->select('file')->get()->toArray();
        return view('layouts.course.register', compact('course_data', 'image'));
    }

    public function set_discount(Request $request, Course $course)
    {
        $loginUser = auth()->user();
        $data = $request->validate([
            'discount' => 'string|required|max:35|exists:discounts,code',
        ]);
        $discount = Discount::where('code', $data['discount'])->first();
        $course_discount = $course->discount_items()->get();
        if (empty($course_discount)) {
            return back()->withErrors(['error' => 'برای این دوره کد تخفیفی در نظر گرفته نشده است']);
        }
        $course_discount_course = $course->discount_items()->where('discount_id', $discount->id)->first();
        if (empty($course_discount_course)) {
            return back()->withErrors(['error' => 'برای این دوره این کد تخفیف در نظر گرفته نشده است']);
        }
        if (($discount->expire_date != null) && $discount->expire_date < now()) {
            return back()->withErrors(['error' => 'تاریخ انقضای این کد تخفیف به سر آماده است! ']);
        }
        if (!$discount->active) {
            return back()->withErrors(['error' => 'این کد تخفیف فعال نمی باشد']);
        }
        $du = DiscountUser::where('discount_id', $discount->id)->whereNotNull('used_at')->get();
        if ($discount->use_limit != null) {
            if (count($du) >= $discount->use_limit) {
                return back()->withErrors(['error' => 'متاسفیم! تعداد استفاده از این کد تخفیف بیش از درخواست شما است']);
            }
        }
//        $loginUser->discounts()->where('discount_id', $discount->id)->whereNotNull('used_at')->get();
        $user_used_discount = $du->contains('user_id', $loginUser->id);
        if ($user_used_discount) {
            return back()->withErrors(['error' => 'این کد تخفیف قبلا استفاده شده است']);
        }
        if ($discount->type == 'private') {
            $du_doesnot_use = DiscountUser::where('discount_id', $discount->id)->whereNull('used_at')->get();
            if (!$du_doesnot_use->contains('user_id', $loginUser->id)) {
                return back()->withErrors(['error' => 'این کد تخفیف برای شما در نظر گرفته نشده است']);
            }
        }
        $course_data = $course->only('slug', 'price');
        $image = $course->files()->where('type', 'cover')->select('file')->get()->toArray();
        return view('layouts.course.register', compact('course_data', 'image', 'discount'));
    }

    public function course_payment(Request $request, Course $course)
    {
        $already_buy_this_course = $this->check_buy_course($course);
        if ($already_buy_this_course[0]) {
            return redirect()->route('order-info', ['order' => $already_buy_this_course[1]])->withErrors(['error' => 'برای شما قبلا برای این دوره لایسنس ساخته شده است.']);
        }
        $data = $request->validate([
            'discount' => [Rule::excludeIf(!isset($request->discount)), 'string', 'max:35', 'exists:discounts,code'],
        ]);
        $loginUser = auth()->user();
        $loginUserId = $loginUser->id;
        if (isset($data['discount'])) {
            $discount = Discount::where('code', $data['discount'])->first();
            if ($discount) {
                $workshop_discount = $course->discount_items()->get();
                if (empty($workshop_discount)) {
                    return redirect()->route('course_register', ['course' => $course->slug])->withErrors(['error' => 'برای این دوره کد تخفیفی در نظر گرفته نشده است']);
                }
                $workshop_discount_workshop = $course->discount_items()->where('discount_id', $discount->id)->first();
                if (empty($workshop_discount_workshop)) {
                    return redirect()->route('course_register', ['course' => $course->slug])->withErrors(['error' => 'برای این دوره این کد تخفیف در نظر گرفته نشده است']);
                }
                if (($discount->expire_date != null) && $discount->expire_date < now()) {
                    return redirect()->route('course_register', ['course' => $course->slug])->withErrors(['error' => 'تاریخ انقضای این کد تخفیف به سر آماده است! ']);
                }
                if (!$discount->active) {
                    return redirect()->route('course_register', ['course' => $course->slug])->withErrors(['error' => 'این کد تخفیف فعال نمی باشد']);
                }

                $du = DiscountUser::where('discount_id', $discount->id)->whereNotNull('used_at')->get();
                if ($discount->use_limit !== null) {
                    if (count($du) >= $discount->use_limit) {
                        return redirect()->route('course_register', ['course' => $course->slug])->withErrors(['error' => 'متاسفیم! تعداد استفاده از این کد تخفیف بیش از درخواست شما است']);
                    }
                }
                $user_used_discount = $du->contains('user_id', $loginUserId);
                if ($user_used_discount) {
                    return redirect()->route('course_register', ['course' => $course->slug])->withErrors(['error' => 'این کد تخفیف قبلا استفاده شده است']);
                }
                if ($discount->type == 'private') {
                    $du_doesnot_use = DiscountUser::where('discount_id', $discount->id)->whereNull('used_at')->get();
                    if (!$du_doesnot_use->contains('user_id', $loginUserId)) {
                        return redirect()->route('course_register', ['course' => $course->slug])->withErrors(['error' => 'این کد تخفیف برای شما در نظر گرفته نشده است']);
                    }
                }

                if ($discount->percent == null) {
                    $total_price = ($course->price) - $discount->amount;
                    $discount_amount = $course->price - $total_price;
                } else {
                    $total_price = ($course->price * ((100 - $discount->percent) / 100));
                    $discount_amount = $course->price - $total_price;
                }
            }
        }
        if (!isset($data['discount'])) {
            $total_price = $course->price;
        }
        $order = Order::create([
            'creator_id' => $loginUserId,
            'user_id' => $loginUserId,
            'total_price' => $total_price,
            'discount_id' => isset($data['discount']) ? $discount->id : null,
            'discount_amount' => isset($data['discount']) ? $discount_amount : 0
        ]);
        $order_item = $course->order_items()->create([
            'order_id' => $order->id,
            'quantity' => 1,
            'price' => $total_price,
        ]);
        $invoice = (new Invoice)->amount($order->total_price);
//        $invoice->detail(['order_item' => $order_item->id]);
        return Payment::purchase($invoice, function ($driver, $transactionId) use ($order, $loginUserId) {
            $order->payments()->create([
                'creator_id' => $loginUserId,
                'transaction_id' => $transactionId,
                'price' => $order->total_price,
            ]);
        })->pay()->render();
    }

    public function payment_success(Order $order)
    {
        $loginUser = auth()->user();
        if ($order->creator->id != $loginUser->id) {
            return redirect()->route('user-panel-order')->withErrors('این سفارش متعلق به شما نمی باشد');
        }
        if ($order->is_paid == false) {
            return redirect()->route('user-panel-order')->withErrors('این سفارش پرداخت نشده است.');
        }
        $order_item = $order->items()->first();
        $course = $order_item->itemable;
        //    $already_buy_this_course = $this->check_buy_course($course);
//        if ($already_buy_this_course[0]) {
//            return redirect()->route('order-info',['order'=>$already_buy_this_course[1]])->withErrors(['error' => 'برای شما قبلا برای این دوره لایسنس ساخته شده است.']);
//        }
        $user = $order->user;
        /*$exists = $user->orders()
            ->whereHas('orderItems', function ($query) use ($course) {
                $query->where('orderable_type', Course::class)
                    ->where('orderable_id', $course->id)
                    ->whereHas('spotPlayer');
            })->exists();*/
        if ($order_item->spotplayer) {
            return redirect()->route('order-info', ['order' => $order->id])->withErrors(['error' => 'برای شما قبلا برای این دوره لایسنس ساخته شده است.']);
        }
        $user_phone = $user->phone;
        $name_and_lastname = $user->name . "-" . $user->lastname;
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                '$API' => 'ZIr3mHK9isbVYzqOmY/R61Pp3lY=',
                '$LEVEL' => -1
            ])->post('https://panel.spotplayer.ir/license/edit/',
                [
                    'course' => [$course->spotplayer_course_id],
                    "name" => $name_and_lastname,// name of customer
                    "awm" => [
                        'text' => Str::after($user_phone, '0')
                    ],
                    "offline" => 15,
                    //"test" => true,
                    "watermark" =>
                        [
                            "position" => 495,
                            "reposition" => 30,
                            "margin" => 48,
                            "texts" =>
                                [
                                    [
                                        "text" => $user_phone,
                                        "repeat" => 11,
                                        "font" => 1,
                                        "weight" => 1,
                                        "size" => 40,
                                        "color" => 2164195328,
                                        "stroke" => [
                                            "size" => 2,
                                            "color" => 2148335871
                                        ]
                                    ],
                                    [
                                        "text" => $user_phone,
                                        "repeat" => 1,
                                        "font" => 1,
                                        "weight" => 0,
                                        "size" => 200,
                                        "color" => 2164195328,
                                        "stroke" => [
                                            "size" => 1,
                                            "color" => 2148335871
                                        ]
                                    ]

                                ]
                        ],
                    "device" => [
                        "p0" => 1,//all device
                        "p1" => 1,//windows
                        "p2" => 1,//macos
                        "p3" => 0,//
                        "p4" => 1,//android
                        "p5" => 1,//ios
                        "p6" => 0//webapp
                    ]
                ]);
            if ($response->failed()) {
                return redirect()->route('user-panel-order')->withErrors($response->body());
            }
            $data_response = json_decode($response->body());
            $order_item->spotplayer()->create([
                'license_id' => $data_response->_id,
                'license_key' => $data_response->key,
                'url' => $data_response->url
            ]);
            $this->send_sms_lookup([
                'receptor' => $user_phone,
                'template' => "course",
                'token' => $course->title,
                'token2' => url()->route('order-info', ['order' => $order->id])
            ]);
            return redirect()->route('order-info', ['order' => $order->id])->with('success', 'لایسنس دوره مورد نظر با موفقیت برای شما ساخته شد');
        } catch (\Exception $e) {
            return redirect()->route('user-panel-order')->withErrors($e->getMessage());
        }

    }
}
