@extends('master')
@section('head')
    @parent
    <link rel="stylesheet" href="/css/inside-workshop.css">
    <title>Document</title>
{{--    @vite(['resources/js/app.js'])--}}
@endsection
@section('content')
    <article class="inside-workshop-page py-5">
        <!--workshops-page top-right gradient-->
        <div class="right-gradient">
            <img src="/images/right-gradient.png" alt="gradient">
        </div>


        <section class="container inside-workshop-desc p-5">
            <div class="inside-workshop-title row d-flex justify-content-end pb-2">
                <h1>{{$workshop_data['title']}}</h1>
            </div>
            <div class="workshop-desc-desc mt-3">
                <div class="row d-flex flex-row-reverse">
                    <div class="d-flex mr-3">
                        <p class="mr-2">محل برگزاری: {{$workshop_data['city']}}</p>
                        <img src="/images/location.png" width="20px" height="20px">
                    </div>
                    <div class="d-flex mr-3">
                        <p class="mr-2">زمان برگزاری: {{$workshop_data['date']}}</p>
                        <img src="/images/date.png" width="20px" height="20px">
                    </div>
                    <div class="d-flex mr-3">
                        <p class="mr-2">تعداد شرکت کنندگان:{{$workshop_data['capacity']}} نفر</p>
                        <img src="/images/people.png" width="20px" height="20px">
                    </div>
                </div>
            </div>
            <div class="workshop-desc-images mt-2">
                <div class="row d-flex" style="flex-direction: row-reverse">
                    <!--workshop-desc image item -->
                    @foreach($galleries as $gallery)
                        <div class="col-6 col-lg-3 mb-4">
                            <div class="card border-0">
                                <div class="card-body workshop-desc-image p-0">
                                    <img src="{{$gallery['file']['thumb']}}" alt="image">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="workshop-teaser-header row d-flex justify-content-end mt-5">
                <h3>
                    تیزر ورکشاپ
                </h3>
            </div>
            {{--            <div class="new-workshop-teaser mt-4">--}}
            {{--            <div class="row workshop-teaser d-flex ml-auto mt-3">--}}
            <div class="workshop-teaser ml-auto mt-3">
                @if($stream_video)
                    {!! html_entity_decode($video_url) !!}
                @else
                    <video controls>
                        <source src="{{$video_url}}" type=video/mp4>
                    </video>
                @endif
            </div>
            <div class="row about-workshop d-flex justify-content-end mt-5">
                <h3>درباره ورکشاپ</h3>
                <p class="text-right">
                    {{$workshop_data['body']}}
                </p>
            </div>
        </section>
        <!-- comments section -->
        <section class="container mt-5 workshop-inside-comment p-5">
            <div class="row workshop-comments-title d-flex justify-content-end mb-4">
                <h3>نظرات شرکت کنندگان</h3>
            </div>
            <ul class="comment-items" id="comment-items">
                @foreach($comments as $comment)
                <li class="row mt-2 comment-item d-flex justify-content-between align-items-center">
                    <div class="comment-desc text-right" style="flex-basis: 80%">
                        <div class="comment-name d-flex align-items-center">
                            <div class="comment-name-image">
                                <img src="/images/comment-image.png" alt="image">
                            </div>
                            <div class="comment-name-name">
                                <h6>{{$comment['name']}}</h6>
                            </div>
                        </div>
                        <div class="comment-desc-desc mt-1">
                            <p>
                                {{$comment['comment']}}
                            </p>
                        </div>
                    </div>
                    <div class="comment-date">
                        <p>
                            {{jdate($comment['created_at'])->format('%d %B %Y')}}
                        </p>
                    </div>
                </li>
                @endforeach
                    <div class="row mt-2 comment-item d-flex justify-content-between align-items-center">
                        <div class="comment-desc text-right">
                            <div class="comment-name d-flex align-items-center">
                                <div class="comment-name-image">
                                    <img src="/images/comment-image.png" alt="image">
                                </div>
                                <div class="comment-name-name">
                                    <h6>محمد صادق زمانی</h6>
                                </div>
                            </div>
                            <div class="comment-desc-desc mt-1">
                                <p>
                                    لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است
                                </p>
                            </div>
                        </div>
                        <div class="comment-date">
                            <p>
                                25 فروردین 1401
                            </p>
                        </div>
                    </div>
                    <!-- comment item  -->
                    <div class="row mt-2 comment-item d-flex justify-content-between align-items-center">
                        <div class="comment-desc text-right">
                            <div class="comment-name d-flex align-items-center">
                                <div class="comment-name-image">
                                    <img src="/images/comment-image.png" alt="image">
                                </div>
                                <div class="comment-name-name">
                                    <h6>محمد صادق زمانی</h6>
                                </div>
                            </div>
                            <div class="comment-desc-desc mt-1">
                                <p>
                                    لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است
                                </p>
                            </div>
                        </div>
                        <div class="comment-date">
                            <p>
                                25 فروردین 1401
                            </p>
                        </div>
                    </div>
                    <!-- comment item  -->
                    <div class="row mt-2 comment-item d-flex justify-content-between align-items-center">
                        <div class="comment-desc text-right">
                            <div class="comment-name d-flex align-items-center">
                                <div class="comment-name-image">
                                    <img src="/images/comment-image.png" alt="image">
                                </div>
                                <div class="comment-name-name">
                                    <h6>محمد صادق زمانی</h6>
                                </div>
                            </div>
                            <div class="comment-desc-desc mt-1">
                                <p>
                                    لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است
                                </p>
                            </div>
                        </div>
                        <div class="comment-date">
                            <p>
                                25 فروردین 1401
                            </p>
                        </div>
                    </div>
            </ul>
            <div class="row mt-4 comments-show d-flex justify-content-center align-items-center">
                <button class="d-flex flex-row-reverse align-items-center" onclick="showcomments()">
                    دیدن نظرات بیشتر
                    <img src="/images/To-Down.png" alt="icon">
                </button>
            </div>
        </section>


        <!-- write comment section-->
        <form method="post" action="{{route('workshop_create_comment',[$workshop_data['slug']])}}">
            @csrf
            <section class="container write-comment pr-5 py-4 mt-5 d-flex flex-column align-items-end">

                <div class="row d-flex justify-content-end">
                    <h3 class="write-comment-title">
                        ثبت دیدگاه:
                    </h3>
                </div>
                <div class="row write-comment-area mt-3">
            <textarea class="pr-4 pt-4" name="comment" rows="5" id="write-comment"
                      placeholder="دیدگاه خود را وارد کنید"></textarea>
                </div>
                <div class="row mt-3">
                    <button type="submit" class="record-button">
                        ثبت
                    </button>
                </div>
            </section>
        </form>

        <!--workshops-page bottom-left gradient-->
        <div class="left-gradient">
            <img src="/images/left-gradient.png" alt="gradient">
        </div>
    </article>
@endsection
