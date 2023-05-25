<?php

namespace App\Http\Controllers\Api\v1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Blog\BlogCollection;
use App\Http\Resources\V1\Blog\BlogDashboardResource;
use App\Http\Resources\V1\Workshop\WorkshopCollection;
use App\Http\Resources\V1\Workshop\WorkshopDashboardResource;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;

class DashboardController extends Controller
{
    public function index()
    {
        $allUsers = count(User::all());
        $tickets = Ticket::with('order_item.order')
            ->whereHas('order_item.order', function ($query) {
                $query->where('is_paid', true);
            })
            ->get();
        $comments = count(Comment::whereNot('name', 'ادمین')->get());
        $totalPaid = Order::where('is_paid', true)->sum('total_price');
        $blogViewCount=Blog::sum('viewCount');
        $blogCount=count(Blog::all());
        $workshopCount=count(Workshop::all());
        $blog = Blog::orderBy('viewCount', 'desc')->take(4)->get();
//        return new BlogCollection($blog);
        $workshops=Workshop::orderBy('registration_number','desc')->take(4)->get();
        $totalPrices=
        DB::table('orders')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total_price) as total'))
            ->where('is_paid', true)
            ->groupBy('month')
            ->get();
//        dd($totalPrices->toArray());
//        dd($totalPrices);
//        $orderTotalByJalaliMonth = DB::table('orders')
//            ->select(DB::raw('SUM(total_price) as total_price_sum'), DB::raw('MONTH(created_at) as month'))
//            ->where('is_paid', true)
//            ->get()
//            ->groupBy(function ($order) {
//                // Convert the created_at column to Jalali date
//                $jalaliDate = Jalalian::fromDateTime($order->created_at);
//
//                // Return the Jalali month as the group key
//                return $jalaliDate->getMonth();
//            });
//        $orderTotalByJalaliMonth = DB::table('orders')
//            ->select(DB::raw('SUM(total_price) as total_price_sum'), DB::raw('MONTH(created_at) as month'))
//            ->where('is_paid', true)
//            ->groupBy(DB::raw('MONTH(created_at)'))
//            ->get();
        /*$orderTotalByJalaliMonth = DB::table('orders')
            ->select(DB::raw('SUM(total_price) as total_price_sum'), DB::raw('jalaliMonth(created_at) as month'))
            ->where('is_paid', true)
            ->groupBy(DB::raw('jalaliMonth(created_at)'))
            ->get();
        dd($orderTotalByJalaliMonth);*/
        //dd($blogCount,$workshopCount);
        return response([
            'data' => [
                'comment'=>$comments,
                'users'=>$allUsers,
                'tickets'=>count($tickets),
                'totalPaid'=>$totalPaid,
                'recordPrices'=>$totalPrices,
                'workshops'=> WorkshopDashboardResource::collection($workshops),
                'blogs'=> BlogDashboardResource::collection($blog),
                'blogCount'=>$blogCount,
               'blogViewCount'=> $blogViewCount
            ],
            'status' => 'success'
        ]);
    }
}
