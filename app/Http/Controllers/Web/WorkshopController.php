<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Discount;
use App\Models\DiscountUser;
use App\Models\File;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class WorkshopController extends AdminController
{
    public function index()
    {
        //$ongoing_workshops=Workshop::where('event_time','>',now())->select('city_id','title','event_time','id')->get()->toArray();
        // $held_workshops=Workshop::where('event_time','<',now())->select('city_id','title','event_time','id')->get()->toArray();
        /* $ongoing_workshops=Workshop::where('event_time','>',now())->select('city_id','title','event_time','id')->with(['files'=>function($query){
             $query->where('type','cover');
         }])->get()->toArray();
         $held_workshops=Workshop::where('event_time','<',now())->select('city_id','title','event_time','id')->with(['files'=>function($query){
             $query->where('type','cover');
         }])->get()->toArray();*/
        $ongoing_workshops = Workshop::filter()->where('event_time', '>', now())->select('city_id', 'title', 'event_time', 'id', 'slug')->with(['files' => function ($query) {
            $query->where('type', 'cover');
        }])->get()->toArray();
        $held_workshops = Workshop::filter()->where('event_time', '<', now())->select('city_id', 'title', 'event_time', 'id', 'slug')->with(['files' => function ($query) {
            $query->where('type', 'cover');
        }])->get()->toArray();
        return view('layouts.workshop.workshops', compact('ongoing_workshops', 'held_workshops'));
    }

    public function show(Workshop $workshop)
    {

        if ($workshop->event_time > now()) {
            $workshop_data = $workshop->only('slug', 'price', 'title', 'body');
            $image = $workshop->files()->where('type', 'banner')->select('file')->get()->toArray();
            $workshop_data['price'] = $this->convertToPersianNumber(number_format($workshop_data['price'], 0, '،', '،'));
            $workshop_data['city'] = City::find($workshop->city_id)->name;
            $workshop_data['date'] = jdate($workshop->event_time)->format('Y/m/d');
            $workshop_data['hour'] = jdate($workshop->event_time)->format('H:i');
            $video_of_workshop = $workshop->files()->whereIn('type', ['video', 'aparat'])->first();
            $stream_video = $video_of_workshop->type == 'aparat';
            if ($stream_video) {
                $video_url = $video_of_workshop->file['htmlCode'];
            } else {
                $video_url = "storage" . $video_of_workshop->file['path'];
            }
            return view('layouts.workshop.inside-new-workshops', compact('image', 'workshop_data', 'video_url', 'stream_video'));
        }
        if ($workshop->event_time <= now()) {
            $workshop_data = $workshop->only('slug', 'title', 'body', 'capacity');
            $workshop_data['city'] = City::find($workshop->city_id)->name;
            $workshop_data['date'] = jdate($workshop->event_time)->format('Y/m/d');
            $video_of_workshop = $workshop->files()->whereIn('type', ['video', 'aparat'])->first();
            $stream_video = $video_of_workshop->type == 'aparat';
            if ($stream_video) {
                $video_url = $video_of_workshop->file['htmlCode'];
            } else {
                $video_url = "storage" . $video_of_workshop->file['path'];
            }
            $galleries = $workshop->gallery->files()->select('file')->get()->toArray();
            $comments = $workshop->comments()->where('approved', true)->where('parent_id',0)->select('id','name', 'comment', 'created_at')->with('comments')->get()->toArray();
//           dd(count($comments),$comments);
//            $date = Jalalian::forge('last sunday')->format('%B %d، %Y'); // دی 02، 1391
//            $date = Jalalian::forge('today')->format('%A, %d %B %y'); // جمعه، 23 اسفند 97
//            dd($comments);
            return view('layouts.workshop.inside-workshop', compact('workshop_data', 'video_url', 'stream_video', 'galleries', 'comments'));
        }
        return back()->withErrors(['error' => 'یافت نشد']);
    }

    public function workshop_register(Workshop $workshop)
    {
        if ($workshop->event_time <= now()) {
            return redirect()->route('workshops')->withErrors([
                'workshopRegisterEnd' => 'مهلت ثبت نام در این ورکشاپ تمام شده است'
            ]);
        }
        if ($workshop->registration_number >= $workshop->capacity) {
            return redirect()->route('workshops')->withErrors([
                'workshopRegisterNumber' => 'ظرفیت این ورکشاپ پر شده است!'
            ]);
        }
        $workshop_data = $workshop->only('slug', 'price', 'title', 'capacity');
        $image = $workshop->files()->where('type', 'banner')->select('file')->get()->toArray();
        // $workshop_data['price']=$this->convertToPersianNumber(number_format($workshop_data['price'], 0, '،', '،'));
//        $workshop_data['capacity']=$this->convertToPersianNumber($workshop_data['capacity']);
//            $workshop_data['price']=number_format($workshop_data['price'], 0, '،', '،');
        $workshop_data['city'] = City::find($workshop->city_id)->name;
        $workshop_data['date'] = jdate($workshop->event_time)->format('Y/m/d');
        $workshop_data['hour'] = jdate($workshop->event_time)->format('H:i');
        $workshop_data['time'] = jdate($workshop->event_time)->format('%d %B');

//            $workshop_data['registration_number']=$this->convertToPersianNumber($workshop->registration_number);
        $workshop_data['registration_number'] = $workshop->registration_number;
        return view('layouts.workshop.workshop-register', compact('image', 'workshop_data'));
    }

    public function create_order(Request $request, Workshop $workshop)
    {

        if ($workshop->capacity - $workshop->registration_number < $request->number) {

            return back()->withErrors([
                'numberError' => 'تعداد بلیط های در خواستی از تعداد باقی مانده بلیط ها بیشتر است.'
            ]);
        }
        $data = $request->validate([
            'number' => 'required|integer',
            'discount' => [Rule::excludeIf(!isset($request->discount)), 'string', 'max:35', 'exists:discounts,code'],
        ]);
//        dd($data['discount']);
        if (isset($data['discount'])) {
            $discount = Discount::where('code', $data['discount'])->first();
            if ($discount) {
                $workshop_discount = $workshop->discount_items()->get();
                if (empty($workshop_discount)) {
                    return back()->withErrors(['error' => 'برای این ورکشاپ کد تخفیفی در نظر گرفته نشده است']);
                }
                $workshop_discount_workshop = $workshop->discount_items()->where('discount_id', $discount->id)->first();
                if (empty($workshop_discount_workshop)) {
                    return back()->withErrors(['error' => 'برای این ورکشاپ این کد تخفیف در نظر گرفته نشده است']);
                }
                if (($discount->expire_date != null) && $discount->expire_date < now()) {
                    return back()->withErrors(['error' => 'تاریخ انقضای این کد تخفیف به سر آماده است! ']);
                }
                if (!$discount->active) {
                    return back()->withErrors(['error' => 'این کد تخفیف فعال نمی باشد']);
                }
                $du = DiscountUser::where('discount_id', $discount->id)->whereNotNull('used_at')->get();
                if ($discount->use_limit != null) {
                    if (count($du) + $data['number'] >= $discount->use_limit) {
                        return back()->withErrors(['error' => 'متاسفیم! تعداد استفاده از این کد تخفیف بیش از درخواست شما است']);
                    }
                }
                if ($discount->percent == null) {
//                    $total_price = ($workshop->price - $discount->amount) * $data['number'];
//                    $discount_amount = $workshop->price * $data['number'] - $total_price;
                    $total_price = ($workshop->price * $data['number']) - $discount->amount ;
                    $discount_amount = $workshop->price * $data['number'] - $total_price;
                } else {
                    $total_price = ($workshop->price * ((100 - $discount->percent) / 100)) * $data['number'];
                    $discount_amount = $workshop->price * $data['number'] - $total_price;
                }
            }
        }

        if (!isset($data['discount'])) {
            $total_price = $workshop->price * $data['number'];
        }

        $order = Order::create([
            'creator_id' => auth()->user()->id,
            'user_id' => auth()->user()->id,
            'total_price' => $total_price,
            'discount_id' => isset($data['discount']) ? $discount->id : null,
            'discount_amount' => isset($data['discount']) ? $discount_amount : 0
        ]);


        $order_item = $workshop->order_items()->create([
            'order_id' => $order->id,
            'quantity' => $data['number'],
            'price' => $total_price,
        ]);
        return redirect()->route('workshop_reservation', [$workshop->slug, $order->id]);
    }

    public function workshop_reservation(Workshop $workshop, Order $order)
    {
//        $data=$request->validate([
//            'order'=>'required|integer|exists:orders,id'
//        ]);
        //  dd($data,$request);
        $order_item = $workshop->order_items()->where('order_id', $order->id)->first();
        if (!$order_item) {
            return redirect()->route('workshop_register', $workshop)->withErrors(['odrerError' => 'این سفارش مربوط به این ورکشاپ نمی باشد.']);
        }
        if ($order->creator_id != auth()->user()->id) {
            return redirect()->route('workshop_register', $workshop)->withErrors(['unmatchedUserId' => 'این سفارش متعلق به شما نمی باشد.']);
        }
        if ($order->ispaid) {
            return redirect()->route('workshop_register', $workshop)->withErrors(['ispaidError' => 'این سفارش قبلا پرداخت شده است.']);
        }
//        $haveTicket = $workshop->tickets()->where('user_id', auth()->user()->id)->first();
        $tickets = $workshop->tickets()->where('user_id', auth()->user()->id)->get();
        if (!empty($tickets)) {
            $haveTicket = false;
            foreach ($tickets as $ticket) {
                $haveTicket = $ticket->order_item->order->is_paid;
                if ($haveTicket) {
                    break;
                }
            }
        } else {
            $haveTicket = false;
        }
        $price = $order->total_price;
        $numberTicketWorkshop = $order_item->quantity;
        $basePrice = $workshop->price;
        $title = $workshop->title;
        $user = array();
        $loginUser = auth()->user();
        $user['email'] = $loginUser->email ?? '';
        $user['phone'] = $loginUser->phone;
        $user['name'] = $loginUser->name;
        $user['lastname'] = $loginUser->lastname;
        $workshop_slug = $workshop->slug;
        $order_id = $order->id;
//        dd($order,$order_id);
        return view('layouts.workshop.reservation', compact('haveTicket', 'price', 'numberTicketWorkshop', 'basePrice', 'title', 'user', 'workshop_slug', 'order_id'));
    }

    public function workshop_payment(Request $request, Workshop $workshop, Order $order)
    {
//        dd(111);
        $order_item = $workshop->order_items()->where('order_id', $order->id)->first();
        if (!$order_item) {
            return redirect()->route('workshop_register', $workshop)->withErrors(['odrerError' => 'این سفارش مربوط به این ورکشاپ نمی باشد.']);
        }
        if ($order->creator_id != auth()->user()->id) {

            return redirect()->route('workshop_register', $workshop)->withErrors(['unmatchedUserId' => 'این سفارش متعلق به شما نمی باشد.']);
        }
        if ($order->ispaid) {
            return redirect()->route('workshop_register', $workshop)->withErrors(['ispaidError' => 'این سفارش قبلا پرداخت شده است.']);
        }
        // status : pending, cancel, paid
        $workshop_number = $order_item->quentity;
        $loginUser = auth()->user();
        $data = $request->validate([
            'name' => 'required|array',
            'lastname' => 'required|array',
            'phone' => 'required|array',
            'email' => 'required|array',
            "name.*" => ['required', 'string', 'max:255'],
            "phone.*" => ['required', 'digits:11', 'distinct'],
            "lastname.*" => ['required', 'string', 'max:255'],
            "email.*" => ['nullable', 'email', 'max:255', 'distinct'],
        ]);
        if ((count($data['name']) == $workshop_number) && (count($data['phone']) == $workshop_number) && (count($data['lastname']) == $workshop_number) && (count($data['email']) == $workshop_number)) {
            return back()->withErrors(['notMatchError' => 'اطلاعات را کامل پر نکردید!']);
        }
        $order_item->tickets()->delete();
        $tickets = $workshop->tickets()->where('user_id', auth()->user()->id)->get();
        if (!empty($tickets)) {
            $haveLoginTicket = false;
            foreach ($tickets as $ticket) {
                $haveLoginTicket = $ticket->order_item->order->is_paid;
                if ($haveLoginTicket) {
                    break;
                }
            }
        } else {
            $haveLoginTicket = false;
        }
        if ($haveLoginTicket == true) {
            foreach ($data['phone'] as $key => $phone) {
                $user_phone = User::where('phone', $phone)->first();
                if (!empty($user_phone)) {
                    $ticketExist = $workshop->tickets()->where('user_id', $user_phone->id)->get();
                    if (!empty($ticketExist)) {
                        foreach ($ticketExist as $tick_exist) {
                            $user_have_ticket = $tick_exist->order_item->order->is_paid;
                            if ($user_have_ticket) {
                                return back()->withErrors(['phoneError' => ' بلیط ثبت شده است.' . $phone . 'برای']);
                            }
                        }
                        $user_phone->tickets()->create([
                            'order_item_id' => $order_item->id,
                            'workshop_id' => $workshop->id,
                            'creator_id' => $loginUser->id,
                            'token' => Ticket::createToken(),
                        ]);
                    } else {
                        $user_phone->tickets()->create([
                            'order_item_id' => $order_item->id,
                            'workshop_id' => $workshop->id,
                            'creator_id' => $loginUser->id,
                            'token' => Ticket::createToken(),
                        ]);
                    }
                } elseif (isset($data['email'][$key])) {
                    $user_email = User::where('email', $data['email'][$key])->first();
                    if (!empty($user_email)) {
                        return back()->withErrors(['emailError' => 'تکراری است.' . $user_email->email . 'ایمیل ']);
                    } else {
                        $userWithEmail = User::create([
                            'phone' => $phone,
                            'name' => $data['name'][$key],
                            'lastname' => $data['lastname'][$key],
                            'email' => $data['email'][$key]
                        ]);
                        $userWithEmail->tickets()->create([
                            'order_item_id' => $order_item->id,
                            'workshop_id' => $workshop->id,
                            'creator_id' => $loginUser->id,
                            'token' => Ticket::createToken(),
                        ]);
                    }
                } else {
                    $userWithlessEmail = User::create([
                        'phone' => $phone,
                        'name' => $data['name'][$key],
                        'lastname' => $data['lastname'][$key]
                    ]);
                    $userWithlessEmail->tickets()->create([
                        'order_item_id' => $order_item->id,
                        'workshop_id' => $workshop->id,
                        'creator_id' => $loginUser->id,
                        'token' => Ticket::createToken(),
                    ]);
                }
            }
        } else {
            foreach ($data['phone'] as $key => $phone) {
                if ($key == 0) {
                    $loginUser->tickets()->create([
                        'order_item_id' => $order_item->id,
                        'workshop_id' => $workshop->id,
                        'creator_id' => $loginUser->id,
                        'token' => Ticket::createToken(),
                    ]);
                    continue;
                }
                $user_phone = User::where('phone', $phone)->first();
                if (!empty($user_phone)) {
                    $ticketExist = $workshop->tickets()->where('user_id', $user_phone->id)->get();
                    if (!empty($ticketExist)) {
                        foreach ($ticketExist as $tick_exist) {
                            $user_have_ticket = $tick_exist->order_item->order->is_paid;
                            if ($user_have_ticket) {
                                return back()->withErrors(['phoneError' => ' بلیط ثبت شده است.' . $phone . 'برای']);
                            }
                        }
                        $user_phone->tickets()->create([
                            'order_item_id' => $order_item->id,
                            'workshop_id' => $workshop->id,
                            'creator_id' => $loginUser->id,
                            'token' => Ticket::createToken(),
                        ]);
                    } else {
                        $user_phone->tickets()->create([
                            'order_item_id' => $order_item->id,
                            'workshop_id' => $workshop->id,
                            'creator_id' => $loginUser->id,
                            'token' => Ticket::createToken(),
                        ]);
                    }
                } elseif (isset($data['email'][$key])) {
                    $user_email = User::where('email', $data['email'][$key])->first();
                    if (!empty($user_email)) {
                        return back()->withErrors(['emailError' => 'تکراری است.' . $user_email->email . 'ایمیل ']);
                    } else {
                        $userWithEmail = User::create([
                            'phone' => $phone,
                            'name' => $data['name'][$key],
                            'lastname' => $data['lastname'][$key],
                            'email' => $data['email'][$key]
                        ]);
                        $userWithEmail->tickets()->create([
                            'order_item_id' => $order_item->id,
                            'workshop_id' => $workshop->id,
                            'creator_id' => $loginUser->id,
                            'token' => Ticket::createToken(),
                        ]);
                    }
                } else {
                    $userWithlessEmail = User::create([
                        'phone' => $phone,
                        'name' => $data['name'][$key],
                        'lastname' => $data['lastname'][$key]
                    ]);
                    $userWithlessEmail->tickets()->create([
                        'order_item_id' => $order_item->id,
                        'workshop_id' => $workshop->id,
                        'creator_id' => $loginUser->id,
                        'token' => Ticket::createToken(),
                    ]);
                }

            }
        }
        if ($workshop->capacity - $workshop->registration_number < $order_item->quantity) {

            return back()->withErrors([
                'numberError' => 'تعداد بلیط های در خواستی از تعداد باقی مانده بلیط ها بیشتر است.'
            ]);
        }
        if ($order->discount_id != null)
        {
            $discount=$order->discount;
            if (($discount->expire_date != null) && $discount->expire_date < now()) {
                return redirect()->route('workshop_register', $workshop)->withErrors(['error' => 'تاریخ انقضای این کد تخفیف به سر آماده است! ']);
            }
            $du = DiscountUser::where('discount_id', $discount->id)->whereNotNull('used_at')->get();
            if ($discount->use_limit != null) {
                if (count($du) + $order_item->quantity >= $discount->use_limit) {
                    return redirect()->route('workshop_register', $workshop)->withErrors(['error' => 'متاسفیم! تعداد استفاده از این کد تخفیف بیش از درخواست شما است']);
                }
            }
            $all_tickets_user=$order_item->tickets()->pluck('user_id')->toArray();
            $discount_user = DiscountUser::where('discount_id', $discount->id)->whereNotNull('used_at')->pluck('user_id')->toArray();
            //dd($all_tickets_user,$discount_user,array_intersect($all_tickets_user, $discount_user));
            if (!empty(array_intersect($all_tickets_user, $discount_user))) {
                return redirect()->route('workshop_register', $workshop)->withErrors(['error' => 'این کد تخفیف برای خود یا همکارانی که قصد خرید بلیط دارید استفاده شده است']);
            }
            if ($discount->type == 'private') {
                $discount_user = DiscountUser::where('discount_id', $discount->id)->whereNull('used_at');
                $discount_user_array = $discount_user->pluck('user_id')->toArray();
               // dd(array_intersect($all_tickets_user, $discount_user_array),$all_tickets_user,$discount_user_array);
                if (!(count(array_intersect($all_tickets_user, $discount_user_array)) === count($all_tickets_user))) {
                    return redirect()->route('workshop_register', $workshop)->withErrors(['error' => 'این کد تخفیف برای خود یا همکارانی که قصد خرید بلیط دارید در نظر گرفته نشده است']);
                }
//               $update_discount_users= $discount_user->whereIn('user_id',$all_tickets_user)->get();
//                foreach ($update_discount_users as $update_discount_user){
//                    $update_discount_user->update('used_at',now());
//                }

            }
//            else{
//                foreach ($all_tickets_user as $tickets_user){
//                    DiscountUser::create([
//                        'user_id'=>$tickets_user,
//                        'discount_id'=>$discount->id,
//                        'used_at'=>now()
//                    ]);
//                }
//            }
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
//        dd($order_item);
        $tickets = $order_item->tickets()->get();
        foreach ($tickets as $key => $ticket) {
            $filename = time() . 'qrcode.svg';
            $path = 'upload/QrCode/';
            QrCode::size(150)->generate(env('APP_URL') . '/' . $ticket->token, Storage::disk('public')->path('upload/QrCode/') . $filename);
            $ticket->files()->create([
                'creator_id' => $loginUser->id,
                'file' => ['path' => $path . $filename],
                'type' => 'QR',
                'file_name' => $filename,
                'extension' => 'svg',
                'storage' => 'public',
                'accessibility' => 'permission',
            ]);
        }
        $workshop = $order_item->itemable;
        $register_number = $workshop->registration_number;
        $add_number = ($order_item->quantity) + $register_number;

        $workshop->update([
            'registration_number' => $add_number,
            'updated_at' => now()
        ]);
        $tickets = $order_item->tickets()->with(['user:id,name,lastname,phone', 'workshop:id,title,city_id,event_time,price', 'files'])->get();
        return view('layouts.workshop.show-ticket', compact('tickets'));
    }

    public function create_comment(Workshop $workshop, Request $request)
    {
        $data = $request->validate([
            'comment' => 'string|required|max:511',
        ]);
        $loginUser = auth()->user();
        $workshop->comments()->create([
            'creator_id' => $loginUser->id,
            'name' => $loginUser->name . " " . $loginUser->lastname,
            'comment' => $data['comment'],
        ]);
        return back()->with('success', 'کامنت شما ثبت شد. پس از بازبینی در سایت قرار خواهد گرفت');
    }

    public function set_discount(Request $request, Workshop $workshop)
    {
        $data = $request->validate([
            'discount' => 'string|required|max:35|exists:discounts,code',
        ]);
        $discount = Discount::where('code', $data['discount'])->first();
        $workshop_discount = $workshop->discount_items()->get();
        if (empty($workshop_discount)) {
            return back()->withErrors(['error' => 'برای این ورکشاپ کد تخفیفی در نظر گرفته نشده است']);
        }
        $workshop_discount_workshop = $workshop->discount_items()->where('discount_id', $discount->id)->first();
        if (empty($workshop_discount_workshop)) {
            return back()->withErrors(['error' => 'برای این ورکشاپ این کد تخفیف در نظر گرفته نشده است']);
        }
        if (($discount->expire_date != null) && $discount->expire_date < now()) {
            return back()->withErrors(['error' => 'تاریخ انقضای این کد تخفیف به سر آماده است! ']);
        }
        if (!$discount->active) {
            return back()->withErrors(['error' => 'این کد تخفیف فعال نمی باشد']);
        }
        $du = DiscountUser::where('discount_id', $discount->id)->where('used_at', 1)->get();
        if ($discount->use_limit != null) {
            if (count($du) >= $discount->use_limit) {
                return back()->withErrors(['error' => 'متاسفیم! تعداد استفاده از این کد تخفیف بیش از درخواست شما است']);
            }
        }
        /*if ($dicount->type == 'private') {
           $discount_user= DiscountUser::where('discount_id',$dicount->id)->where('user_id',auth()->user()->id)->first();
            if (empty($discount_user)){
                return back()->withErrors(['error' => 'این کد تخفیف برای شما در نظر گرفته نشده است.']);
            }
        }*/
        $workshop_data = $workshop->only('slug', 'price', 'title', 'capacity');
        $image = $workshop->files()->where('type', 'banner')->select('file')->get()->toArray();
        $workshop_data['city'] = City::find($workshop->city_id)->name;
        $workshop_data['date'] = jdate($workshop->event_time)->format('Y/m/d');
        $workshop_data['hour'] = jdate($workshop->event_time)->format('H:i');
        $workshop_data['time'] = jdate($workshop->event_time)->format('%d %B');

//            $workshop_data['registration_number']=$this->convertToPersianNumber($workshop->registration_number);
        $workshop_data['registration_number'] = $workshop->registration_number;
        return view('layouts.workshop.workshop-register', compact('image', 'workshop_data', 'discount'));
    }
}
