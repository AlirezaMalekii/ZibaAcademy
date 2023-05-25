@extends('master')
@section('head')
    @parent
    <link rel="stylesheet" href="/css/index.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <title>164697</title>
    <meta name="enamad" content="164697"/>
@endsection

@section('content')
    <!-- start hero article -->
    @include('sections.home.banner')
    <!-- end hero article -->


    <!-- start record article-->
    @include('sections.home.table-info',compact('number_of_workshops'))
    <!-- end record article-->


    <article class="workshop-all pb-4 pb-lg-0">

        <!--index top-right gradient-->
        <div class="right-gradient">
            <img src="/images/right-gradient.png" alt="gradient">
        </div>


        <!-- start workshop article-->
        <section class="workshop py-5">
            <div class="container">
                <div class="index-center-title text-center d-flex align-items-center justify-content-center mb-5">
                    <img src="/images/title-vector-right.png">
                    <h2>
                        ورکشاپ ها
                    </h2>
                    <img src="/images/title-vector-left.png">
                </div>
                <div class="row d-flex flex-column-reverse flex-lg-row justify-content-between align-items-center">
                    <div class="workshop-desc col-12 col-lg-6 text-right align-items-end d-flex flex-column">
                        <h2 class="text-right">ورکشاپ ها لورم ایپسوم</h2>
                        <p class="text-right mt-3">
                            مدرس صدا و سیما،کارآفرین،دارای مدرک طراحی
                            از دانشگاه مدآرت فرانسه،شرکت مجستیک ترکیه
                        </p>
                        <a href="{{route('workshops')}}"
                           class="light-btn d-flex mt-3 py-2 px-4 flex-row-reverse align-items-center">
                            مشاهده ورکشاپ ها
                            <img src="/images/arrow-left.png" alt="icon" width="24px" height="17px" class="mr-2">
                        </a>
                        <div class="workshop-vector">
                            <img src="/images/index-vector.png" alt="icon">
                        </div>
                    </div>
                    <div class="workshop-video mb-4 mb-lg-0 col-12 col-lg-6 p-3">
                        @if($number_of_workshops!=0)
                            @if($stream_video)
                                {!! html_entity_decode($video_url) !!}
                            @else
                                <video controls>
                                    <source src="{{$video_url}}" type=video/mp4>
                                </video>
                            @endif
                        @else
                            <video controls>
                                <source src="/images/workshop-video.mp4" type=video/mp4>
                            </video>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        <!-- end workshop article-->


        <!-- start last-worksops section -->
        @include('sections.home.workshops-held',compact('held_workshops'))
        <!-- end last-workshops section -->


        <!-- start index-about section -->
        <section class="index-about mt-5">
            <div class="container">
                <div class="row d-flex flex-column-reverse flex-lg-row align-items-center justify-content-between">
                    <div
                        class="index-about-desc col-12 col-lg-7 text-right align-items-end d-flex flex-column mt-4 mt-lg-0">
                        <h3 class="mb-4">
                            درباره زیبا اسلامی
                        </h3>
                        <p class="mb-4">
                            لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک
                            است،
                            چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی
                            تکنولوژی
                            مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد
                        </p>
                        <a href="{{route('about-us')}}"
                           class="light-btn d-flex mt-3 py-2 px-4 flex-row-reverse align-items-center">
                            ادامه مطلب
                            <img src="/images/arrow-left.png" alt="icon" width="24px" height="17px" class="mr-2">
                        </a>
                    </div>
                    <div class="index-about-image-wrapper col-12 col-lg-5 text-right">
                        {{--                        <div class="index-about-image">--}}
                        <div class="index-about-image" data-aos="zoom-in" data-aos-duration="1500">
                            <img src="/images/index-about-image.png" alt="image">
                        </div>
                        <div class="about-vector">
                            <img src="/images/index-vector.png" alt="icon">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end index-about-section -->


        <!-- index-page bottom-left gradient-->
        <div class="left-gradient">
            <img src="/images/left-gradient.png" alt="gradient">
        </div>
    </article>


    <!-- start index-blog section -->
    @include('sections.home.blogs',compact('blogs'))
    <!-- end index-blog section -->
@endsection
@section('script')
    <script type="application/javascript">
        let value = document.querySelectorAll(".num");
        console.log(value)
        let inter = 5000;

        value.forEach((valued) => {
            let start = 0;
            let end = parseInt(valued.getAttribute("data-value"));
            console.log(end)
            let duration = Math.floor(inter / end);
            console.log(duration)
            console.log(value.innerHTML)
            let counter = setInterval(function () {
                start +=1;
                valued.textContent = start;
                if (start == end){
                    clearInterval(counter);
                }
            },duration)
        })


        AOS.init();
    </script>
@endsection
