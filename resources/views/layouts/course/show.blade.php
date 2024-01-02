@extends('master')
@section('head')
    @parent
    <link rel="stylesheet" href="/css/inside-course.css">
    @vite('resources/js/app.js')
    {{--    <title>Document</title>--}}
@endsection

@section('content')
    <article class="new-workshop-page py-5" x-data>


        <!--workshops-page top-right gradient-->
        <div class="right-gradient">
            <img src="/images/right-gradient.png" alt="gradient">
        </div>


        <!-- start progress-bar section -->
        @if(!$already_purchased[0])
        <section class="container prog-bar mb-5">
            <div class="prog-wrapper pt-4 pb-2 row d-flex justify-content-between">
                <div class="prog-item col-4">
                    <div class="prog-item-wrapper text-center">
                        <h3 class="p-3 prog-item-number d-flex align-items-center justify-content-center text-center">
                            3
                        </h3>
                        <p class="prog-item-desc mt-2">
                            پرداخت
                        </p>
                    </div>
                </div>
                <div class="prog-item col-4">
                    <div class="prog-item-wrapper text-center">
                        <h3 class="p-3 prog-item-number d-flex align-items-center justify-content-center text-center">
                            2
                        </h3>
                        <p class="prog-item-desc mt-2">
                            سبد خرید
                        </p>
                    </div>
                </div>
                <div class="prog-item col-4 active">
                    <div class="prog-item-wrapper text-center">
                        <h3 class="p-3 prog-item-number d-flex align-items-center justify-content-center text-center">
                            1
                        </h3>
                        <p class="prog-item-desc mt-2">
                            جزییات دوره
                        </p>
                    </div>
                </div>
            </div>
        </section>
        @endif
        <!-- end progress-bar section -->


        <!-- start inside-new-workshop artice -->
        <section class="container inside-n-workshop">
            <div class="row d-flex flex-column-reverse flex-lg-row">
                @include('sections.course.sidebar')
                <div class="workshop-right col-12 col-lg-8">
                    <div class="new-workshop-right-hero text-right p-4">
                        <div class="d-flex flex-row-reverse justify-content-between align-items-center">
                            <h1>
                                {{ $course_data['title'] }}
                            </h1>
                            <div class="course-item-label start">
                                <p class="text-center">
                                    {{ $course_data['level'] }}
                                </p>
                            </div>
                        </div>
                        <div class="new-workshop-right-hero-image mt-3">
                            <img src="{{$image[0]['file']['thumb']}}" alt="image">
                        </div>
                    </div>
                    <div class="new-workshop-right-desc text-right p-4 mt-4">
                        <h3>
                            معرفی دوره
                        </h3>
                        <div class="new-workshop-teaser mt-4">
                            @if($stream_video)
                                {!! html_entity_decode($video_url) !!}
                            @else
                                <video controls>
                                    <source src="{{$video_url}}" type=video/mp4>
                                </video>
                            @endif
                        </div>
                        <h4 class="mt-5">
                            توضیحات دوره
                        </h4>
                        <div class="mt-3" style="direction:rtl; font-family: semi-bold">
                            {!! $course_data['body'] !!}
                        </div>
                        {{--<p class="mt-3">
                            لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است،
                            چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی
                            مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی در شصت و سه
                            درصد گذشته حال و آینده، شناخت فراوان جامعه و متخصصان را می طلبد،
                        </p>
                        <div class="course-topics" id="list">
                            <div class="course-topics-header">
                                <p>
                                    سرفصل های دوره
                                </p>
                            </div>
                            <div class="course-topic mt-3">
                                <div class="d-flex flex-row-reverse course-topic-title">
                                    <p>
                                        فصل اول لورم ایپسوم
                                    </p>
                                    <img class="mr-3 deactive arrow-up" src="/images/arrow-up.png" alt="icon" width="17px"
                                         height="8px">
                                    <img class="mr-3 arrow-down" src="/images/arrow-down.png" alt="icon" width="17px"
                                         height="8px">
                                </div>
                                <div class="course-topic-items deactive">
                                    <div class="course-topic-item">
                                        <p>
                                            لورم ایپسوم
                                        </p>
                                    </div>
                                    <div class="course-topic-item">
                                        <p>
                                            لورم ایپسوم
                                        </p>
                                    </div>
                                    <div class="course-topic-item">
                                        <p>
                                            لورم ایپسوم
                                        </p>
                                    </div>
                                    <div class="course-topic-item">
                                        <p>
                                            لورم ایپسوم
                                        </p>
                                    </div>
                                    <div class="course-topic-item">
                                        <p>
                                            لورم ایپسوم
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="course-topic mt-3">
                                <div class="d-flex flex-row-reverse course-topic-title">
                                    <p>
                                        فصل اول لورم ایپسوم
                                    </p>
                                    <img class="mr-3 deactive arrow-up" src="/images/arrow-up.png" alt="icon" width="17px"
                                         height="8px">
                                    <img class="mr-3 arrow-down" src="/images/arrow-down.png" alt="icon" width="17px"
                                         height="8px">
                                </div>
                                <div class="course-topic-items deactive">
                                    <div class="course-topic-item">
                                        <p>
                                            لورم ایپسوم
                                        </p>
                                    </div>
                                    <div class="course-topic-item">
                                        <p>
                                            لورم ایپسوم
                                        </p>
                                    </div>
                                    <div class="course-topic-item">
                                        <p>
                                            لورم ایپسوم
                                        </p>
                                    </div>
                                    <div class="course-topic-item">
                                        <p>
                                            لورم ایپسوم
                                        </p>
                                    </div>
                                    <div class="course-topic-item">
                                        <p>
                                            لورم ایپسوم
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>--}}
                    </div>
                    <div class="new-workshop-left mobile col-12 col-lg-4 d-flex flex-column-reverse flex-lg-column">
                        <div class="new-workshop-left-sign text-right p-4 mt-3 mt-lg-0">
                            <h4>
                                قیمت دوره:
                            </h4>
                            <h5 class="desc-price text-center p-3 mt-3">
                            <span x-text=" new Intl.NumberFormat('fa').format({{$course_data['price']}})">

                                    </span>
                                <span>
                                        تومان
                                    </span>
                            </h5>
                            @guest()
                                <p class="mt-5">
                                    لطفاً برای ثبت نام در دوره,ابتدا <a href="{{route('login')}}">وارد سایت </a>شوید
                                </p>
                            @endguest
                            @if($already_purchased[0])
{{--                                <a href="{{route('order-info',['order'=>\App\Models\Course::find($course_data['id'])->order_items()->whereHas('order', function ($query)  {--}}
{{--            $query->where('is_paid', 1)->where('user_id',auth()->user()->id);--}}
{{--        })->get()->first()->id] }}" class="ex-bold-button">--}}
                                 <a href="{{route('order-info',['order'=>$already_purchased[1]] )}}" class="ex-bold-button" style="background-color: #0A6146">
                                    دانلود دوره
                                </a>
                            @else
                                <a href="{{route('course_register',['course'=>$course_data['slug']])}}"
                                   class="ex-bold-button">
                                    خرید
                                </a>
                            @endif
                        </div>
                        <div class="new-workshop-left-desc text-right p-4 mt-4">
                            <h3>
                                مشخصات دوره
                            </h3>
                            <div class="mt-4">
                                <div class="new-workshop-left-desc-item d-flex flex-row-reverse">
                                    <img src="/images/user-tag.png" alt="icon" width="24px" height="24px">
                                    <p class="mr-2">
                                        مدرس:خانم زیبا اسلامی
                                    </p>
                                </div>
                                <div class="new-workshop-left-desc-item d-flex flex-row-reverse">
                                    <img src="/images/clock.png" alt="icon" width="24px" height="24px">
                                    <p class="mr-2">
                                        مدت زمان:{{$hour}} ساعت و {{$minute}} دقیقه
                                    </p>
                                </div>
                                <div class="new-workshop-left-desc-item d-flex flex-row-reverse">
                                    <img src="/images/message-question.png" alt="icon" width="24px" height="24px">
                                    <p class="mr-2">
                                        پیش نیاز:{{$course_data['prerequisite']}}
                                    </p>
                                </div>
                                <div class="new-workshop-left-desc-item d-flex flex-row-reverse">
                                    <img src="/images/note-2.png" alt="icon" width="24px" height="24px">
                                    <p class="mr-2" style="direction: rtl">
                                        تعداد سرفصل ها:{{$course_data['section_count']}}
                                    </p>
                                </div>
                                <div class="new-workshop-left-desc-item d-flex flex-row-reverse">
                                    <img src="/images/Headset.png" alt="icon" width="24px" height="24px">
                                    <p class="mr-2" style="direction: rtl">
                                        روش پشتیبانی:{{$course_data['support_way']}}
                                    </p>
                                </div>
                                <div class="new-workshop-left-desc-item d-flex flex-row-reverse">
                                    <img src="/images/video-circle.png" alt="icon" width="24px" height="24px">
                                    <p class="mr-2" style="direction: rtl">
                                        روش دریافت:{{$course_data['delivery_way']}}
                                    </p>
                                </div>
                                <div class="new-workshop-left-desc-item d-flex flex-row-reverse">
                                    <img src="/images/layer.png" alt="icon" width="24px" height="24px">
                                    <p class="mr-2">
                                        سطح دوره:{{$course_data['level']}}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                            <div
                                                class="row mt-2 comment-item d-flex justify-content-between align-items-center">
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
                                                <div
                                                    class="row mt-2 comment-item d-flex justify-content-between align-items-center mr-4">
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
                    <form method="post" action="{{route('course_create_comment',[$course_data['slug']])}}">
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
                </div>
            </div>
        </section>
        <!-- end inside-new-workshop artice -->


        <!--workshops-page bottom-left gradient-->
        <div class="left-gradient">
            <img src="/images/left-gradient.png" alt="gradient">
        </div>
    </article>
@endsection
@section('script')
    <script>

        // comment show function
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
