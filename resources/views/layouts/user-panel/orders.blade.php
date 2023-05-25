@extends('master')
@section('head')
    @parent
    <link rel="stylesheet" href="/css/user-panel-dashbord.css">
    <script src="https://kit.fontawesome.com/dcc0def279.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/css/orders.css">
    <title>Document</title>
@endsection

@section('content')
    <article class="user-dashbord-page py-5">
        <section class="container">
            <div class="row flex-column-reverse flex-lg-row">
                <div class="col-12 col-lg-8 d-flex flex-column-reverse flex-lg-column">
                    @if(!$orders)
                        <div
                            class="card order-empty border-0 order-empty-card px-4 pb-1 mt-3 mb-3 mt-lg-0 d-flex flex-row-reverse align-items-center justify-content-between">
                            <div class="order-empty-title d-flex flex-row-reverse mt-3">
                                <img src="/images/danger.png" alt="icon" width="28px" height="28px">
                                <p class="mt-1">
                                    هیچ سفارشی هنوز ثبت نشده است.
                                </p>
                            </div>
                            <div class="order-empty-button">
                                <a href="{{route('workshops')}}">
                                    مشاهده محصولات
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="card border-0 order-table-card p-4 mt-3 mt-lg-0">
                            <table class="order-table">
                                <tr>
                                    <th class="text-center pb-2 pb-lg-3">
                                        سفارش
                                    </th>
                                    <th class="text-center pb-2 pb-lg-3">
                                        تاریخ
                                    </th>
                                    <th class="text-center pb-2 pb-lg-3">
                                        وضعیت
                                    </th>
                                    <th class="text-center pb-2 pb-lg-3">
                                        مجموع
                                    </th>
                                    <th class="text-center pb-2 pb-lg-3">
                                        عملیات ها
                                    </th>
                                </tr>
                                @foreach($orders as $order)
                                    <tr>
                                        <td class="text-center pt-3">
                                            #{{$order['id']}}
                                        </td>
                                        <td class="text-center pt-3">
{{--                                            $date = Jalalian::forge('last sunday')->format('%B %d، %Y'); // دی 02، 1391--}}
{{--                                            $date = Jalalian::forge('today')->format('%A, %d %B %y'); // جمعه، 23 اسفند 97--}}
{{--                                            30 اردیبهشت 1402--}}
                                            {{jdate($order['created_at'])->format('%d %B %Y')}}
                                        </td>
                                        <td class="text-center pt-3">
                                            @switch($order['status'])
                                                @case('paid')
                                                    پرداخت شده
                                                    @break
                                                @case('cancel')
                                                    کنسل شده
                                                    @break
                                                @default
                                                    در انتظار پرداخت
                                            @endswitch
                                        </td>
                                        <td class="text-center pt-3">
                                            {{$order['total_price']}} تومان برای {{$order['items_count']}} مورد
                                        </td>
                                        <td class="text-center pt-3">
                                            <div class="order-item-operation">
                                                <a href="{{route('order-info',['order'=>$order['id']])}}" class="order-operation green mr-1">
                                                    نمایش
                                                </a>
                                                @if($order['status']!='paid')
                                                    <a href="{{route('continue_order',['order'=>$order['id']])}}" class="order-operation lgreen mr-1">
                                                        پرداخت
                                                    </a>
                                                    <a href="#" class="order-operation red mr-1">
                                                        لغو
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    @endif
                </div>
                @include('sections.user-panel.sidebar',compact('user'))
            </div>
        </section>
    </article>
@endsection
