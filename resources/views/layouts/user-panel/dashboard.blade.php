@extends('master')
@section('head')
    @parent
    <link rel="stylesheet" href="/css/user-panel-dashbord.css">
    <script src="https://kit.fontawesome.com/dcc0def279.js" crossorigin="anonymous"></script>
    <title>Document</title>
@endsection

@section('content')
    <article class="user-dashbord-page py-5">
        <section class="container">
            <div class="row flex-column-reverse flex-lg-row">
                <div class="col-12 col-lg-8 d-flex flex-column-reverse flex-lg-column">
                    <div class="user-dashbord-topic d-flex p-4 mt-3 mt-lg-0">
                        <div class="row user-dashbord-items-box">
                            <div class="col-12 col-lg-6">
                                <div class="user-dashbord-topic-item card text-center">
                                    <a href="{{route('user_panel_ticket')}}" class="text-center ">
                                        بلیط های من
                                    </a>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 mt-3 mt-lg-0">
                                <div class="user-dashbord-topic-item card text-center">
                                    <a href="{{route('workshops')}}" class="text-center ">
                                        ورکشاپ های درحال برگزاری
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(!isset($notifications->first()->id))
                    <div class="user-dashbord-news d-flex flex-row-reverse align-items-center p-4 mt-3">
                        <div class="user-dashbord-news-image">
                            <img src="/images/user-dashbord-news.png" alt="image">
                        </div>
                        <div class="user-dashbord-news-desc text-right">
                            <h3>
                                اطلاعیه ها
                            </h3>
                            <p>
                                هیچ اطلاعیه جدیدی ندارید.
                            </p>
                        </div>
                    </div>
                    @else
                    <div class="user-dashbord-news p-4 mt-3">
                        <div class="d-flex flex-row-reverse align-items-center">
                            <div class="user-dashbord-news-image">
                                <img src="/images/user-dashbord-news.png" alt="image">
                            </div>
                            <div class="user-dashbord-news-desc text-right">
                                <h3>
                                    اطلاعیه ها
                                </h3>
                            </div>
                        </div>
                        @foreach($notifications as $notification)
                        <div class="user-dashbord-news-desc-item">
                            <p class="title text-right mb-0" style="font-family: semi-bold;font-size: 20px;color: #2D302F;">
                                {{$notification->data['title']}}
                            </p>
                            <p class="desc text-right" style="font-family: semi-bold;font-size: 20px;color: rgb(45, 48, 47 , 70%);direction: rtl;">
                                {{$notification->data['message']}}
                            </p>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @include('sections.user-panel.sidebar',compact('user'))
            </div>
        </section>
    </article>
@endsection
