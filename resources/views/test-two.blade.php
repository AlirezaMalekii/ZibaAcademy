@extends('master')

@section('head')
    @parent
    <link rel="stylesheet" href="/css/reservation.css">

    <title>Document</title>
    @vite(['resources/js/app.js'])
@endsection

@section('content')
    <article class="reservation-page py-5">
        <!--workshops-page top-right gradient-->
        <div class="right-gradient">
            <img src="/images/right-gradient.png" alt="gradient">
        </div>


        <section class="container">
            <!-- start progress-bar section -->
            <div class="container prog-bar mb-5" x-data="">
                <div class="prog-wrapper pt-4 pb-2 row d-flex justify-content-between">
                    <div class="prog-item col-3">
                        <div class="prog-item-wrapper text-center">
                            <h3 class="p-3 prog-item-number d-flex align-items-center justify-content-center text-center">
                                1
                            </h3>
                            <p class="prog-item-desc mt-2">
                                جزییات ورکشاپ
                            </p>
                        </div>
                    </div>
                    <div class="prog-item col-3 active">
                        <div class="prog-item-wrapper text-center">
                            <h3 class="p-3 prog-item-number d-flex align-items-center justify-content-center text-center">
                                1
                            </h3>
                            <p class="prog-item-desc mt-2">
                                جزییات ورکشاپ
                            </p>
                        </div>
                    </div>
                    <div class="prog-item col-3">
                        <div class="prog-item-wrapper text-center">
                            <h3 class="p-3 prog-item-number d-flex align-items-center justify-content-center text-center">
                                <img src="/images/check.png" alt="icon">
                            </h3>
                            <p class="prog-item-desc mt-2">
                                جزییات ورکشاپ
                            </p>
                        </div>
                    </div>
                    <div class="prog-item col-3">
                        <div class="prog-item-wrapper text-center">
                            <h3 class="p-3 prog-item-number d-flex align-items-center justify-content-center text-center">
                                <img src="/images/check.png" alt="icon">
                            </h3>
                            <p class="prog-item-desc mt-2">
                                جزییات ورکشاپ
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end progress-bar div -->


            <div class="row d-flex align-items-start flex-column-reverse flex-lg-row">
                <div class="reservation-pay col-12 col-lg-4 mt-3 mt-lg-0">
                    <div class="reservation-pay-up text-right p-4">
                        <h3>سفارش شما:</h3>
                        <div class="reservation-pay-up-items mt-4">
                            <div class="reservation-pay-up-item d-flex flex-row-reverse mt-2">
                                <p>
                                    عنوان بلیط:
                                </p>
                                <h5>
                                    {{$title}}
                                </h5>
                            </div>
                            <div class="reservation-pay-up-item d-flex flex-row-reverse mt-2">
                                <p>
                                    تعداد:
                                </p>
                                <h5>
                                    {{$numberTicketWorkshop}} عدد
                                </h5>
                            </div>
                            <div class="reservation-pay-up-item d-flex flex-row-reverse mt-2">
                                <p>
                                    قیمت واحد:
                                </p>
                                <h5>
                                    {{$basePrice}} تومان
                                </h5>
                            </div>
                            <div class="reservation-pay-up-item d-flex flex-row-reverse mt-2">
                                <p>
                                    قیمت کل:
                                </p>
                                <h5>
                                    {{$price}} تومان
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="reservation-pay-down text-right p-4 mt-3">
                        <h3>
                            پرداخت امن زرین پال
                        </h3>
                        <p class="zarin-desc mt-3 p-2 text-center">
                            پرداخت امن به وسیله کلیه کارت های شتاب از طریق درگاه زرین پال
                        </p>
                        <p class="rule-desc mt-3">
                            با خرید بلیط از این سایت ,<a href="#"> قوانین و مقررات سایت </a> را می پذیرم.
                        </p>
                        <button class="ex-bold-button mt-3" style="width: 100%; border: none"
                                x-on:click="$refs.form.submit">
                            تایید و پرداخت
                        </button>
                    </div>
                </div>
                <div class="reservation-form col-12 col-lg-8 text-right p-4">
                    <h2>
                        جزییات حساب
                    </h2>
                    {{--                    <form class="mt-4" id="my-form" method="post" action="{{route()}}">--}}
                    <form class="mt-4" x-ref="form" method="post" action="{{route('workshop_payment')}}" x-on:submit.prevent="submitting = true">
                        @csrf
                        @for($i = 1; $i <= $numberTicketWorkshop; $i++)
                            @if($i==1 && !$haveTicket)
                                <h6 dir="rtl" class="text-warning">اطلاعات کاربری شما در صورت تغییر، به روزرسانی می
                                    شود.</h6>
                                <div class="form-group d-flex flex-row-reverse row">
                                    <div class="form-group-input d-flex flex-column col-12 col-lg-6">
                                        <label>نام</label>
                                        <input type="text" name="name" id="name" class="p-2"
                                               value="{{$user['name']}}">
                                    </div>
                                    <div class="form-group-input d-flex flex-column col-12 col-lg-6">
                                        <label>نام خانوادگی</label>
                                        <input type="text" name="big-name" id="big-name" class="p-2"
                                               value="{{$user['lastname']}}">
                                    </div>
                                </div>
                                <div class="form-group d-flex flex-column">
                                    <label>تلفن همراه</label>
                                    <input type="number" name="telephone" id="telephone" class="p-2"
                                           value="{{$user['phone']}}">
                                </div>
                                <div class="form-group d-flex flex-column">
                                    <label>ایمیل (اختیاری)</label>
                                    <input type="email" name="email" id="email" class="p-2" value="{{$user['email']}}">
                                </div>
                                @if($i!=$numberTicketWorkshop)
                                    </br>
                                <hr style=" border-top: 1px dashed red;">
                                </br>
                            @endif
                            @continue
                            @endif

                            @if($i==1)
                                <h6 dir="rtl" class="text-warning">شما قبلا بلیط این ورکشاپ را تهیه کرده اید.
                                    اطلاعات جداول زیر مربوط به همکاران و دوستانتان می باشد.</h6>
                            @endif
                            <div class="form-group d-flex flex-row-reverse row">
                                <div class="form-group-input d-flex flex-column col-12 col-lg-6">
                                    <label>نام</label>
                                    <input type="text" name="name" id="name" class="p-2"
                                           value="{{$i==1 && !$haveTicket ? $user['name'] : ""}}">
                                </div>
                                <div class="form-group-input d-flex flex-column col-12 col-lg-6">
                                    <label>نام خانوادگی</label>
                                    <input type="text" name="big-name" id="big-name" class="p-2" value="">
                                </div>
                            </div>
                            <div class="form-group d-flex flex-column">
                                <label>تلفن همراه</label>
                                <input type="number" name="telephone" id="telephone" class="p-2" value="">
                            </div>
                            <div class="form-group d-flex flex-column">
                                <label>ایمیل (اختیاری)</label>
                                <input type="email" name="email" id="email" class="p-2" value="">
                            </div>
                            @if($i!=$numberTicketWorkshop)
                                </br>
                            <hr style=" border-top: 1px dashed red;">
                            </br>
                            @endif
                        @endfor
                    </form>
                </div>
            </div>
        </section>


        <!--workshops-page bottom-left gradient-->
        <div class="left-gradient">
            <img src="/images/left-gradient.png" alt="gradient">
        </div>
    </article>
@endsection
