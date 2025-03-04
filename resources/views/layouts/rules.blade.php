@extends('master')
@section('head')
    @parent
    <link rel="stylesheet" href="css/rules.css">
    <!--bootstrap cdn-->
    <!--font awesome cdn-->
    <script src="https://kit.fontawesome.com/dcc0def279.js" crossorigin="anonymous"></script>
    <!--main.js-->
    <title>Document</title>
@endsection
@section('content')
    <div class="container text-center py-5">
        <div class="rules p-4">
            <h1>
                قوانین و مقررات
            </h1>
            <p class="mt-4" style="white-space: pre-wrap">
                <span style="font-size: larger">
                    قوانین و مقررات برخورداری از امکانات سایت:
</span>
                - جهت ورود و داشتن دسترسی به صفحات مختلف سایت ثبت نام و ورود اطلاعات خواسته شده الزامی میباشد.
                - در صورت خرید محتوای آموزشی و پرداخت وجه، امکان استفاده از این محتوا صرفا در لینک دسترسی اختصاصی که
                برای شما ساخته شده است امکان پذیر است .
                - ضبط و انتشار محتوای خریداری شده به هر روش ممکن غیر قانونی بوده و علاوه بر دین شرعی، پیگرد قانونی خواهد
                داشت.
                <span style="font-size: larger">
                قوانین و مقررات شرکت در کارگاههای آموزشی (ورکشاپ)
                </span>
                - ضروریست برای شرکت در ورک شاپ های و کارگاههای آموزشی حتما از طریق همین سایت ثبت نام فرمایید.
                - به همراه داشتن بلیط شرکت در ورکشاپ به صورت پرینت یا تصویر آن در تلفن همراه شرکت کننده الزامیست.
                - رعایت زمان و نظم برای حضور در جلسات آموزشی الزامیست.
                - حضور کودکان و همراهان احتمالی صرفاً در جلسات آموزشی ممنوع است.
                - فیلم برداری یا ضبط صوت از برگزاری ورک شاپها غیر قانونی می باشد.
                - رعایت حجاب و دیگر شئونات اسلامی برای کلیه شرکت کمنندکان الزامیست.
                - آموزش های در نظر گرفته شده در کارگاههای آموزشی پروژه محور بوده و مواد مصرفی به میزان لازم توسط برگزار
                کننده تامین میشود. در صورت نیاز به مواد مصرفی یا تجهیزات و ابزار میتوانید از طریق سایت فروشگاهی همین
                سایت تهیه بفرمایید.
                - ما تلاش کرده ایم چالش های شرکت کنندگان را به حداقل برسانیم لذا در ورک شاپهایی که در مرکز تربیت مربی
                کرج برگزار میشود برای شرکت کنندگان غیر بومی اسکان رایگان فراهم نماییم.
                - شرکت کننده متعهد است ضمن رعایت کلیه قوانین، به تصمیمات ضمنی برگزار کننده کارگاه آموزشی متناسب با شرایط
                زمانی و مکانی و ... احترام بگزارد.
                - وجوه پرداختی اعم از تسویه کامل، پیش پرداخت و استفاده از کیف پول تخفیفات در صورت عدم حضور در جلسات
                آموزشی قابل ذعودت نیست لذا در تطبیق شرایط خود با زمان ، مکان و محتوای دوره های دقت بفرمایید.
                - صدور گواهینامه برای شرکت کنندگان به دو صورت گواهی حضور در دوره و یا گواهینامه فنی و حرفه ای خواهد بود.
                در صورت انتخاب گواهینامه فنی و حرفه ای باید در آزمونهای دو مرحله ای ( کتبی و عملی ) اسن سازمان شرکت
                نمایید. در غیر اینصورت فقط گواهی شرکت در دوره دریافت خواهید نمود.
            </p>
            <a href="{{url()->previous()}}" class="sign-in-button mt-5">
                میپذیرم
            </a>
        </div>
    </div>

@endsection

