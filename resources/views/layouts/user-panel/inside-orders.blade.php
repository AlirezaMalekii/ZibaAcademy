@extends('master')
@section('head')
    @parent
    <script src="https://kit.fontawesome.com/dcc0def279.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/css/orders.css">
@endsection
@section('content')
    <!-- start user-dashbord-page -->
    <article class="user-dashbord-page py-5">
        <section class="container">
            <div class="row flex-column-reverse flex-lg-row">
                <div class="col-12 col-lg-8 d-flex flex-column-reverse flex-lg-column">
                    <div class="card border-0 order-detail-card mt-3 mt-lg-0">
                        <div class="order-detail p-4">
                            <p class="order-detail-title text-right">
                                @if($order_data['status']=='paid')
                                    سفارش #{{$order_data['id']}} در تاریخ {{jdate($order_data['created_at'])->format('%d %B %Y')}} ثبت شده است و در حال حاضر در
                                    وضعیت تکمیل شده
                                    می‌باشد.
                                @elseif($order_data['status']=='pending')
                                    سفارش #{{$order_data['id']}} در تاریخ {{jdate($order_data['created_at'])->format('%d %B %Y')}} ثبت شده است و در حال حاضر در
                                    وضعیت آماده پرداخت
                                    می‌باشد.
                                @elseif($order_data['status']=='cancel')
                                    سفارش #{{$order_data['id']}} در تاریخ {{jdate($order_data['created_at'])->format('%d %B %Y')}} ثبت شده است و در حال حاضر در
                                    وضعیت لغو شده
                                    می‌باشد.
                                @endif
                            </p>
                            @if($order_data['is_paid'])
                                <div class="order-detail-desc text-right mt-4 pb-3">
                                    <p class="mb-1">
                                        {{jdate($payment_date)->format('%A, %d %B %Y, H:i')}}
{{--                                        سه شنبه ۹ شهریور ۱۴۰۰، ۱۰:۴۱ب٫ظ--}}
                                    </p>
                                    <p class="mb-1">
                                        پرداخت موفقیت آمیز بود .
                                    </p>
                                    <p class="mb-1">
                                        کد رهگیری : {{ $code }}
                                    </p>
                                </div>
                            @endif
                            @foreach($order_items as $order_item)
                                <div
                                    class="order-detail-cancel-items d-flex flex-column flex-lg-row justify-content-between text-right align-items-center mt-4">
                                    <div class="order-detail-cancel-item-image mb-3 mb-lg-0">
                                        <img src="/images/order-cancel.png" alt="image">
                                    </div>
                                    <div
                                        class="order-detail-cancel-item-price d-flex flex-row flex-lg-column justify-content-between">
                                        <h5>
                                            محصول
                                        </h5>
                                        <p>
                                            {{$order_item->itemable()->withTrashed()->get()->first()->title}}
                                        </p>
                                    </div>
                                    <div
                                        class="order-detail-cancel-item-price d-flex flex-row flex-lg-column justify-content-between">
                                        <h5>
                                            تعداد
                                        </h5>
                                        <p>
                                            {{$order_item->quantity}}
                                        </p>
                                    </div>
                                    <div
                                        class="order-detail-cancel-item-price d-flex flex-row flex-lg-column justify-content-between">
                                        <h5>
                                            قیمت واحد
                                        </h5>
                                        <p>
                                            {{number_format($order_item->itemable()->withTrashed()->get()->first()->price)}} تومان
                                        </p>
                                    </div>
                                    <div
                                        class="order-detail-cancel-item-price d-flex flex-row flex-lg-column justify-content-between">
                                        <h5>
                                            قیمت کل
                                        </h5>
                                        <p>
                                            {{number_format($order_item->itemable()->withTrashed()->get()->first()->price * $order_item->quantity)}}
                                            تومان
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @include('sections.user-panel.sidebar',compact('user'))
            </div>
        </section>
    </article>
    <!-- end user-dashbord-page -->
@endsection
