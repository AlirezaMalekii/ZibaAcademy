@extends('master')

@section('head')
    @parent
    <link rel="stylesheet" href="/css/ticket.css">

    <title>Document</title>

@endsection

@section('content')
    <article class="ticket-page py-5" style="overflow: hidden">


        <!-- start progress-bar section -->
        <section class="container prog-bar mb-5">
            <div class="prog-wrapper pt-4 pb-2 row d-flex justify-content-between">
                <div class="prog-item col-3 active">
                    <div class="prog-item-wrapper text-center">
                        <h3 class="p-3 prog-item-number d-flex align-items-center justify-content-center text-center">
                            4
                        </h3>
                        <p class="prog-item-desc mt-2">
                            دریافت بلیط
                        </p>
                    </div>
                </div>
                <div class="prog-item col-3">
                    <div class="prog-item-wrapper text-center">
                        <h3 class="p-3 prog-item-number d-flex align-items-center justify-content-center text-center">
                            <img src="/images/check.png" alt="icon">
                        </h3>
                        <p class="prog-item-desc mt-2">
                            رزرو و پرداخت
                        </p>
                    </div>
                </div>
                <div class="prog-item col-3">
                    <div class="prog-item-wrapper text-center">
                        <h3 class="p-3 prog-item-number d-flex align-items-center justify-content-center text-center">
                            <img src="/images/check.png" alt="icon">
                        </h3>
                        <p class="prog-item-desc mt-2">
                            ثبت نام
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
        </section>
        <!-- end progress-bar section -->

        <div class="container">
            <div class="col-12 ticket-page-wrapper p-4 text-right">
                <h2>
                    بلیط شما جهت شرکت در ورکشاپ
                </h2>
                <!-- start ticket item-->
                @foreach($tickets as $ticket)
                    <div class="user-ticket-wrapper d-flex flex-column flex-lg-row-reverse row">
                        <div class="user-ticket-card d-flex flex-column col-12 col-lg-9 p-3">
                            <div
                                class="user-ticket-card-desc d-flex flex-column flex-lg-row-reverse align-items-center">
                                <div class="user-ticket-card-image">
                                    <img src="/images/user-ticket-image.png" alt="image">
                                </div>
                                <div class="user-ticket-card-about text-center text-lg-right pr-0 pr-lg-2 mt-3 mt-lg-0">
                                    <h3 class="text-right">
                                        {{$ticket->workshop->title}}
                                    </h3>
                                    <div class="user-ticket-card-about-desc">
                                        <div
                                            class="user-ticket-card-about-desc-item d-flex flex-row justify-content-center justify-content-lg-end">
                                            <p>
                                                محل برگزاری :
                                                <span>
                                                        {{\App\Models\City::find($ticket->workshop->city_id)->name}}
                                            </span>
                                            </p>
                                            <img src="/images/ticket-place.png" alt="icon" width="24px" height="24px">
                                        </div>
                                        <div
                                            class="user-ticket-card-about-desc-item d-flex flex-row justify-content-center justify-content-lg-end">
                                            <p>
                                                زمان برگزاری:
                                                <span>
                                                {{jdate($ticket->workshop->event_time)->format('Y/m/d')}}
                                            </span>
                                            </p>
                                            <img src="/images/ticket-date.png" alt="icon" width="24px" height="24px">
                                        </div>
                                        <div
                                            class="user-ticket-card-about-desc-item d-flex flex-row justify-content-center justify-content-lg-end">
                                            <p>
                                                ساعت برگزاری:
                                                <span>
                                                {{jdate($ticket->workshop->event_time)->format('H:i')}}
                                                    </span>
                                            </p>
                                            <img src="/images/ticket-clock.png" alt="icon" width="24px" height="24px">
                                        </div>
                                        <div
                                            class="user-ticket-card-about-desc-item d-flex flex-row justify-content-center justify-content-lg-end">
                                            <div style="display: flex" dir="rtl">
                                                <p>نام و نام خانوادگی:</p>
                                                <p>{{$ticket->user->name}} {{$ticket->user->lastname}} </p>
                                            </div>
                                            <img src="/images/ticket-user.png" alt="icon" width="24px" height="24px">
                                        </div>
                                        <div
                                            class="user-ticket-card-about-desc-item d-flex flex-row justify-content-center justify-content-lg-end">
                                            <p>
                                                شماره تلفن:
                                                <span>
                                                        {{$ticket->user->phone}}
                                                    </span>
                                            </p>
                                            <img src="/images/ticket-call.png" alt="icon" width="24px" height="24px">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="ticket-card-price d-flex flex-row-reverse justify-content-between px-2 pt-2 mt-2">
                                <h4 class="text-right">
                                    قیمت بلیط:
                                    <span>
                                            {{$ticket->workshop->price}} تومان
                                </span>
                                </h4>
                            </div>
                        </div>
                        <div class="user-ticket-left align-items-center d-flex flex-column col-12 col-lg-3 py-3">
                            <h3 class="text-right">
                                ورکشاپ تخصصی تمبور دوزی
                            </h3>
                            <div class="user-ticket-left-desc">
                                <div style="display: flex; direction: rtl; height: 1.5rem">
                                    <p class="text-right mb-1">
                                        مدرس:
                                    </p>
                                    <p>
                                        خانم زیبا اسلامی
                                    </p>
                                </div>
                                <div style="display: flex; direction: rtl; height: 1.5rem">
                                    <p class="text-right mb-1">
                                        شماره بلیط:
                                    </p>
                                    <p>
                                        {{$ticket->id}}
                                    </p>
                                </div>
                                <div style="display: flex;direction: rtl; height: 1.5rem">
                                    <p class="text-right mb-1">
                                        نام شرکت کننده:
                                    </p>
                                    <p>
                                        {{$ticket->user->name}}
                                    </p>
                                </div>
                                <div style="display: flex;direction: rtl; height: 1.5rem">
                                    <p class="text-right mb-1">
                                        نام خانوادگی:
                                    </p>
                                    <p>
                                        {{$ticket->user->lastname}}
                                    </p>
                                </div>
                            </div>
                            <div class="user-ticket-qr">
                                <img style="width: 150px" src="/storage/{{$ticket->files()->first()->file['path']}}" alt="qr-code">
                            </div>
                        </div>
                    </div>
                    {{--                </br>--}}
                    @if(!$loop->last)
                        <hr style="border-top: 1px dotted black">
                    @endif
                @endforeach
                <!-- end ticket item-->
            </div>
        </div>
    </article>
@endsection

