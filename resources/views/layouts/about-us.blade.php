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
                    زیبا اسلامی هستم ، متولد ۱۳۶۵، از دوره راهنمایی به صورت تخصصی کار هنری انجام میدادم ، تحصیلات دانشگاهیم  رو در رشته علوم تربیتی شروع کردم و بعد از اون در رشته حقوق ، اما هیچ کدام از این رشته ها نیاز روحی من رو تامین نمیکرد ، تا این که رو به هنر آوردم و تحصیلاتم در این رشته هارو رها کردم و به فراگیری تخصصی هنر های مختلف پرداختم . شروع یاد گیری هنر از رشته خیاطی شروع شد، بعد از مدتی احساس  کردم روی تمام این لباسها که دوخته میشود باید به تناسب لباس، کار هنری دیگه ای انجام شود ، در نتیجه به فراگیری هنر های مختلف از جمله جواهر دوزی ، و تزیینات تخصصی لباس  و.... پرداختم و برای ارتقا مهارتم ازمحضر  اساتید ژاپنی و روسی استفاده کردم . دارای لایسنس دیزاین لباس از دانشگاه مد آرت و شرکت مجستیک ترکیه، دارای کد استادی از مرکز تربیت مربی و پژوهش های فنی و حرفه ای، برگزار کننده ورکشاپ های سراسری در سطح کشور ،دارای مزون تخصصی تزیینات لباس
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
