@extends('master')
@section('head')
    @parent
    <link rel="stylesheet" href="/css/about-us.css">

    <title>Document</title>
@endsection

<!-- start about-us section-->
@section('content')
<section class="about-us">
    <!--workshops-page top-right gradient-->
    <div class="right-gradient">
        <img src="/images/right-gradient.png" alt="gradient">
    </div>

    <div class="container py-5">
        <div class="row d-flex flex-column-reverse flex-lg-row align-items-center justify-content-between">
            <div
                class="index-about-desc col-12 col-lg-7 text-right align-items-end d-flex flex-column mt-5 mt-lg-4 mt-lg-0">
                <div class="first-about-vector">
                    <img src="/images/index-vector.png" alt="icon">
                </div>
                <h3 class="mb-4">
                    درباره زیبا اسلامی
                </h3>
                <p class="mb-4">
                    لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است،
                    چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد
                    نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد
                </p>
            </div>
            <div class="index-about-image-wrapper col-12 col-lg-5 text-right">
                <div class="index-about-image">
                    <img src="/images/index-about-image.png" alt="image">
                </div>
                <div class="second-about-vector">
                    <img src="/images/index-vector.png" alt="icon">
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
<!-- end about-us section-->
