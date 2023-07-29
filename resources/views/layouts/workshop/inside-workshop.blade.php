@extends('master')
@section('head')
    @parent
    <link rel="stylesheet" href="/css/inside-workshop.css">
    <!--bootstrap cdn-->
    <title>Document</title>
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
                <div class="row d-flex justify-content-between">
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
                <div class="text-right" style="direction: rtl; font-family: semi-bold">
                    {!!$workshop_data['body']!!}
                </div>
            </div>
        </section>


        <!-- comments section -->
        @if(!empty($comments))
            <section class="container mt-5 workshop-inside-comment p-5">
                <div class="row workshop-comments-title d-flex justify-content-end mb-4">
                    <h3>نظرات شرکت کنندگان</h3>
                </div>
                <ul class="comment-items post" id="comment-items">
                    <!-- comment item -->
                    @foreach($comments as $comment)
                        @if(empty($comment['comments']))
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
                        @else
                            <li class="d-flex flex-column">
                                <div class="row mt-2 comment-item d-flex justify-content-between align-items-center">
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
                                </div>
                                @foreach($comment['comments'] as $replyComment)
                                    <div class="row mt-2 comment-item d-flex justify-content-between align-items-center mr-4">
                                        <div class="comment-desc text-right">
                                            <div class="comment-name d-flex align-items-center">
                                                <div class="comment-name-image">
                                                    <img src="/images/comment-image.png" alt="image">
                                                </div>
                                                <div class="comment-name-name">
                                                    <h6>{{$replyComment['name']}}</h6>
                                                </div>
                                            </div>
                                            <div class="comment-desc-desc mt-1">
                                                <p>
                                                    {{$replyComment['comment']}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </li>
                        @endif
                    @endforeach

                </ul>
                <div class="row mt-4 comments-show d-flex justify-content-center align-items-center">
                    <button class="d-flex flex-row-reverse align-items-center load-more">
                        دیدن نظرات بیشتر
                        <img src="/images/To-Down.png" alt="icon">
                    </button>
                </div>
            </section>
        @endif

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

@section('script')
    <script>
        const loadmore = document.querySelector('.load-more');

        let currentItems = 3;
        loadmore.addEventListener('click', (e) => {
            const elementLlist = [...document.querySelectorAll('.post li')];

            for (let i = currentItems; i < currentItems + 3; i++) {
                console.log(elementLlist[i])
                if (elementLlist[i]) {
                    elementLlist[i].style = "display : flex !important";
                }
                console.log(elementLlist[i].style.display)
            }
            currentItems += 3;
        })
    </script>
@endsection




