<?php

namespace App\Http\Controllers\Api\v1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Order\OrderCollection;
use App\Http\Resources\V1\Order\OrederResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(){
        $orders = Order::latest()->paginate(30);
        return new OrderCollection($orders);
    }
    public function show($id){
        $order = Order::whereId($id)->first();
        if (!$order) {
            return response([
                'message' => "یافت نشد",
                'status' => 'failed'
            ], 400);
        }
        return new OrederResource($order);
    }
}
