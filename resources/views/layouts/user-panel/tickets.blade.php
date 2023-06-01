@extends('master')
@section('head')
    @parent
    <link rel="stylesheet" href="/css/user-panel-ticket.css">

        <script src="https://kit.fontawesome.com/dcc0def279.js" crossorigin="anonymous"></script>

    <title>Document</title>
    <style>
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                -moz-print-color-adjust: exact;
                -ms-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
@endsection

@section('content')
    <article class="user-dashbord-ticket-page py-5">
        <section class="container">
            <div class="row d-flex flex-column-reverse flex-lg-row">
                <div class="col-12 col-lg-8 mt-3 mt-lg-0">
                    @if($choisenTicket->first())
                    <div class="card user-ticket p-4">
                        <!-- start ticket item-->
                        <div id="print">
                            @foreach($choisenTicket as $ticket)
                                <div class="user-ticket-wrapper d-flex flex-column flex-md-row-reverse row" style="margin-bottom: 30px; margin-top: 30px">
                                    <div class="user-ticket-card d-flex flex-column col-12 col-md-9 p-3">
                                        <div
                                            class="user-ticket-card-desc d-flex flex-column flex-md-row-reverse align-items-center">
                                            <div class="user-ticket-card-image">
                                                <img src="/images/user-ticket-image.png" alt="image">
                                            </div>
                                            <div
                                                class="user-ticket-card-about text-center text-md-right pr-0 pr-md-2 mt-3 mt-md-0">
                                                <h3 class="text-right">
                                                    {{$ticket->workshop->title}}
                                                </h3>
                                                <div class="user-ticket-card-about-desc">
                                                    <div
                                                        class="user-ticket-card-about-desc-item d-flex flex-row justify-content-center justify-content-md-end">
                                                        <p>
                                                            محل برگزاری :
                                                            <span>
                                                            {{\App\Models\City::find($ticket->workshop->city_id)->name}}
                                                    </span>
                                                        </p>
                                                        <img src="/images/ticket-place.png" alt="icon" width="24px"
                                                             height="24px">
                                                    </div>
                                                    <div
                                                        class="user-ticket-card-about-desc-item d-flex flex-row justify-content-center justify-content-md-end">
                                                        <p>
                                                            زمان برگزاری:
                                                            <span>
                                                                                                        {{jdate($ticket->workshop->event_time)->format('Y/m/d')}}
                                                    </span>
                                                        </p>
                                                        <img src="/images/ticket-date.png" alt="icon" width="24px"
                                                             height="24px">
                                                    </div>
                                                    <div
                                                        class="user-ticket-card-about-desc-item d-flex flex-row justify-content-center justify-content-md-end">
                                                        <p>
                                                            ساعت برگزاری:
                                                            <span>
                                                                                                                                                                {{jdate($ticket->workshop->event_time)->format('H:i')}}
                                                    </span>
                                                        </p>
                                                        <img src="/images/ticket-clock.png" alt="icon" width="24px"
                                                             height="24px">
                                                    </div>
                                                    <div
                                                        class="user-ticket-card-about-desc-item d-flex flex-row justify-content-center justify-content-md-end">
                                                        <p style="direction: rtl">
                                                            نام و نام خانوادگی:
                                                            <span>
                                                        {{$ticket->user->name}} {{$ticket->user->lastname}}
                                                    </span>
                                                        </p>
                                                        <img src="/images/ticket-user.png" alt="icon" width="24px"
                                                             height="24px">
                                                    </div>
                                                    <div
                                                        class="user-ticket-card-about-desc-item d-flex flex-row justify-content-center justify-content-md-end">
                                                        <p>
                                                            شماره تلفن:
                                                            <span>
                                                        {{$ticket->user->phone}}
                                                    </span>
                                                        </p>
                                                        <img src="/images/ticket-call.png" alt="icon" width="24px"
                                                             height="24px">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="ticket-card-price d-flex flex-row-reverse justify-content-between px-2 pt-2 mt-2">
                                            <h4 class="text-right">
                                                قیمت بلیت:
                                                <span>
                                           {{$ticket->workshop->price}} تومان
                                        </span>
                                            </h4>
                                        </div>
                                    </div>
                                    <div
                                        class="user-ticket-left align-items-center d-flex flex-column col-12 col-md-3 py-3">
                                        <h3 class="text-right">
                                            ورکشاپ تخصصی تمبور دوزی
                                        </h3>
                                        <div class="user-ticket-left-desc">
                                            <p class="text-right mb-1">
                                                مدرس:
                                                <span>
                                            خانم زیبا اسلامی
                                        </span>
                                            </p>
                                            <p class="text-right mb-1">
                                                شماره بلیط:
                                                <span>
                                             {{$ticket->id}}
                                        </span>
                                            </p>
                                            <p class="text-right mb-1" style="direction: rtl">
                                                نام شرکت کننده:
                                                <sapn>
                                                    {{$ticket->user->name}}
                                                </sapn>
                                            </p>
                                            <p class="text-right mb-1" style="direction: rtl">
                                                نام خانوادگی:
                                                <span>
                                           {{$ticket->user->lastname}}
                                        </span>
                                            </p>
                                        </div>
                                        <div class="user-ticket-qr">
                                            <img src="/store/{{$ticket->files()->first()->file['path']}}" alt="qr-code">
                                        </div>
                                    </div>
                                </div>
                                @if(!$loop->last)
                                    <hr style="border-top: 1px dotted black">
                                @endif
                            @endforeach
                        </div>
                        <!-- end ticket item-->
                        @if($choisenTicket->first())
                            <div class="user-ticket-button text-right mt-4">
                                <button onclick="printDiv()" class="black-button">دانلود</button>
                            </div>
                        @endif
                    </div>
                        @endif
                </div>
                @include('sections.user-panel.sidebar',compact('user'))
            </div>
        </section>
    </article>
@endsection
@section('script')
    <script type="text/javascript">
        function printDiv() {
            var printContent = document.getElementById("print").innerHTML;
            var originalContent = document.body.innerHTML;
            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
        }
    </script>
@endsection
