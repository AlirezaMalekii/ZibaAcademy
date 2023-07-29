@extends('master')
@section('head')
    @parent
    <script src="https://kit.fontawesome.com/dcc0def279.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/css/orders.css">
    @vite(['resources/js/app.js'])
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
                                    سفارش #{{$order_data['id']}} در
                                    تاریخ {{jdate($order_data['created_at'])->format('%d %B %Y')}} ثبت شده است و در حال
                                    حاضر در
                                    وضعیت تکمیل شده
                                    می‌باشد.
                                @elseif($order_data['status']=='pending')
                                    سفارش #{{$order_data['id']}} در
                                    تاریخ {{jdate($order_data['created_at'])->format('%d %B %Y')}} ثبت شده است و در حال
                                    حاضر در
                                    وضعیت آماده پرداخت
                                    می‌باشد.
                                @elseif($order_data['status']=='cancel')
                                    سفارش #{{$order_data['id']}} در
                                    تاریخ {{jdate($order_data['created_at'])->format('%d %B %Y')}} ثبت شده است و در حال
                                    حاضر در
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
                                @if($order_item->itemable()->withTrashed()->get()->first() instanceof \App\Models\Course && $order_data['status']=='paid')
                                    <div class="order-course" x-data="{ licenseKey: '{{$order_item->spotplayer->license_key}}', styleText : 'none', } ">
                                        <div class="order-course-policy px-2 pt-3 mt-3">
                                            <p class="text-center">
                                                مطالب این دوره دارای واترمارک‌های پیدا و پنهان هستند و هر گونه کپی
                                                برداری و نشر آن قابل پیگیری بوده و موجب پیگرد قانونی خواهد شد.
                                            </p>
                                        </div>
                                        <div class="mt-5">
                                            <p class="order-course-title text-right">
                                                مشاهده دوره در اپلیکیشن
                                            </p>
                                            <p class="order-course-desc text-right">
                                                برای مشاهده دوره‌ها ابتدا پلیر را با توجه به سیستم عامل خود دانلود و نصب
                                                نمایید. پس از اجرای پلیر، در صفحه ثبت دوره جدید کلید لایسنس را وارد،
                                                مکان ذخیره‌سازی را انتخاب و سپس فرم را تایید کنید.
                                            </p>
                                        </div>
                                        <div class="order-course-download">
                                            <div class="course-download-title d-flex flex-row-reverse mt-5">
                                                <img src="/images/one.png" alt="icon" width="48px" height="48px">
                                                <p class="mr-3 mt-2">
                                                    دانلود و نصب پلیر
                                                </p>
                                            </div>
                                            <div
                                                class="course-download-items d-flex flex-row justify-content-between px-4 pt-4 pb-3 align-items-center mt-4">
                                                <div class="row">
                                                    <div class="course-download-item col-6 col-lg-3">
                                                        <img src="/images/ios.png" alt="image">
                                                        <p class="text-center">
                                                            بزودی
                                                        </p>
                                                    </div>
                                                    <a class="course-download-item col-6 col-lg-3" target="_blank"
                                                       href="http://dl.spotplayer.ir/assets/bin/spotplayer/setup.exe"
                                                       style="color: black; text-decoration: none;">
                                                        <img src="/images/windwos.png" alt="windows">
                                                        <p class="text-center mt-2">
                                                            WINDWOS
                                                        </p>
                                                    </a>
                                                    <a class="course-download-item col-6 col-lg-3" target="_blank"
                                                       href="http://dl.spotplayer.ir/assets/bin/spotplayer/setup.dmg"
                                                       style="color: black; text-decoration: none;">
                                                        <img src="/images/mac.png" alt="macos">
                                                        <p class="text-center mt-2">
                                                            MAC OS
                                                        </p>
                                                    </a>
                                                    <a class="course-download-item col-6 col-lg-3" target="_blank"
                                                       href="http://dl.spotplayer.ir/assets/bin/spotplayer/setup.apk"
                                                       style="color: black; text-decoration: none;">
                                                        <img src="/images/android.png" alt="android">
                                                        <p class="text-center">
                                                            ANDROID
                                                        </p>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="course-key-copy">
                                            <div class="course-download-title d-flex flex-row-reverse mt-5">
                                                <img src="/images/two.png" alt="icon" width="48px" height="48px">
                                                <p class="mr-3 mt-2">
                                                    کپی و وارد نمودن کلید در پلیر
                                                </p>
                                            </div>
                                            <div class="course-key px-4 pt-4 pb-3 mt-4">
{{--                                                <p class="text-right" x-text="licenseKey" style="background-color: #b3d4fc">--}}
                                                <p class="text-right" x-text="licenseKey" :style="'background-color:'+ styleText">
{{--                                                    <!-- {{$order_item->spotplayer->license_key}} -->--}}
                                                </p>
                                            </div>

                                        </div>
                                        <div class="course-key-copy-button mt-4" @click.outside="styleText='none'">
                                            <button class="py-2" x-on:click="navigator.clipboard.writeText(licenseKey);styleText='#b3d4fc'">
                                                کپی کلید
                                            </button>
                                        </div>
                                    </div>
                                @endif
                                <div
                                    class="order-detail-cancel-items d-flex flex-column flex-lg-row justify-content-between text-right align-items-center mt-4">
                                    <div class="order-detail-cancel-item-image mb-3 mb-lg-0">
                                        {{--                                        <img src="/images/order-cancel.png" alt="image">--}}
                                        <img
                                            src="{{$order_item->itemable()->withTrashed()->get()->first()->files()->where('type','cover')->first()->file['thumb']}}"
                                            alt="image" style="height: 88px">
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
                                            {{number_format($order_item->itemable()->withTrashed()->get()->first()->price)}}
                                            تومان
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
