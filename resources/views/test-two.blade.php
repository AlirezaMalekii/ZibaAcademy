<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--css files-->
    <link rel="stylesheet" href="/css/globalstyle.css">
    <link rel="stylesheet" href="/css/reset.css">
    <link rel="stylesheet" href="/css/sign-in.css">
    <!--bootstrap cdn-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!--font awesome cdn-->
    <script src="https://kit.fontawesome.com/dcc0def279.js" crossorigin="anonymous"></script>
    <!--main.js-->
    <script src="/js/main.js"></script>
    <title>Document</title>
</head>
<body>
<!-- start navbar section -->
<header class="bg-navbar">
    <nav class="navbar navbar-expand-lg flex-row-reverse justify-content-between d-flex py-4 container">
        <div class="navbar-logo-brand d-flex">
            <a class="navbar-brand d-flex flex-row-reverse align-items-center" href="#">
                <img src="images/header-logo.png" class="ml-2" width="40px" height="40px">
                زیبا آکادمی
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon">
                    <img src="images/navbar-toggle.png">
                </span>
            </button>
        </div>

        <div class="collapse navbar-collapse flex-row-reverse justify-content-center" id="navbarSupportedContent">
            <ul class="navbar-nav flex-column text-right flex-lg-row-reverse">
                <li class="nav-item mr-2 active">
                    <a class="nav-link" href="#">صفحه اصلی</a>
                </li>
                <li class="nav-item mr-2">
                    <a class="nav-link" href="#">ورگشاپ ها</a>
                </li>
                <li class="nav-item mr-2">
                    <a class="nav-link" href="#">وبلاگ</a>
                </li>
                <li class="nav-item mr-2">
                    <a class="nav-link" href="#">درباره ما</a>
                </li>
            </ul>
        </div>

        <div class="navbar-regester align-items-center d-flex flex-row-reverse">
            <!-- <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" id="navbar-search-input">
                <img class="my-2 my-sm-0" onclick="navbarsearch()" src="images/search-icon.png" width="25px" height="25px" id="navbar-search-image">
            </form> -->
            <a href="#" class="ml-2">
                <img src="/images/search-normal.png" alt="icon" width="32px" height="32px">
            </a>
            <button class="my-2 my-sm-0 py-2 px-4 bg-white text-center">ورود/ثبت نام</button>
        </div>
    </nav>
</header>
<!-- end navbar section -->





<!-- start sigin-in page article-->
<article class="sign-in-page py-5">
    <section class="container">
        <div class="col-12 col-lg-5 sign-in-bar-wrapper">
            <div class="card sign-in-bar p-2">
                <div class="d-flex">
                    <div class="col-6 text-center sign-in-bar-item">
                        <a href="#" class="tex-center">
                            ثبت نام
                        </a>
                    </div>
                    <div class="col-6 text-center sign-in-bar-item active">
                        <a href="#" class="tex-center">
                            ورود به حساب کاربری
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6 sign-in-form-wrapper mt-4">
            <div class="card sign-in-form p-5 text-right">
                <h2 class="text-center">
                    ورود
                </h2>
                <form class="mt-4">
                    <div class="form-group d-flex flex-column">
                        <label>تلفن همراه</label>
                        <input type="number" name="telephone" id="telephone" class="p-2">
                    </div>
                    <div class="form-group d-flex flex-column">
                        <label>رمزعبور</label>
                        <input type="password" name="password" id="password" class="p-2">
                    </div>
                </form>
                <div class="d-flex flex-column flex-lg-row-reverse justify-content-between mt-3">
                    <div class="remember-me">
                        <label>
                            مرا به خاطر بسپار
                        </label>
                        <input type="checkbox" name="remember-me" id="remember-me">
                    </div>
                    <div class="forget-password">
                        <a href="#">
                            رمز عبور خود را فراموش کرده اید؟
                        </a>
                    </div>
                </div>
                <a href="#" class="sign-in-button mt-5">
                    ورود به حساب کاربری
                </a>
                <p class="lets-sign text-center mt-4">
                    آیا حساب کاربری ندارید؟<a href="#">ثبت نام</a>
                </p>
            </div>
        </div>
    </section>
</article>
<!-- end sigin-in page article-->



<!-- start footer -->
<footer class="py-5">
    <div class="container">
        <div class="row justify-content-center flex-row-reverse align-items-center mb-5">
            <img src="/images/footer-logo.png" alt="logo" class="footer-title-image">
            <h2 class="mr-3 footer-title">
                زیبا آکادمی
            </h2>
        </div>
        <div class="row d-flex flex-row-reverse justify-content-between align-items-start">
            <div class="footer-pages col-6 col-lg-4 text-right">
                <h3 class="mb-3 footer-item-title">
                    بخش های سایت
                </h3>
                <div class="footer-pages-items d-flex flex-column">
                    <a href="#" class="mt-2">
                        صفحه اصلی
                    </a>
                    <a href="#" class="mt-2">
                        ورکشاپ ها
                    </a>
                    <a href="#" class="mt-2">
                        حساب کاربری
                    </a>
                    <a href="#" class="mt-2">
                        درباره ما
                    </a>
                    <a href="#" class="mt-2">
                        قوانین و مقررات
                    </a>
                </div>
            </div>
            <div class="footer-contact col-6 col-lg-4 text-right">
                <h3 class="mb-3 footer-item-title">
                    ارتباط با ما
                </h3>
                <div class="footer-contact-items mt-2">
                    <p>
                        نشانی:قزوین،بلوار اتحاد،پلاک23
                    </p>
                    <p>
                        شماره تماس:02855667788
                    </p>
                    <div class="footer-social d-flex flex-column flex-lg-row-reverse">
                        <p class="mt-2">
                            شبکه های اجتماعی:
                        </p>
                        <div class="footer-social-items d-flex">
                            <a href="#" class="d-flex justify-content-center align-items-center pl-2">
                                <img src="/images/instagram.png" alt="icon" class="mr-2">
                            </a>
                            <a href="#" class="d-flex justify-content-center align-items-center pl-2">
                                <img src="/images/telegram.png" alt="icon" class="mr-2">
                            </a>
                            <a href="#" class="d-flex justify-content-center align-items-center pl-2">
                                <img src="/images/Whatsapp.png" alt="icon" class="mr-2">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-enamad col-12 col-lg-4 text-right mt-4 mt-lg-0">
                <h3 class="mb-4 footer-item-title">
                    مجوز های دریافت شده
                </h3>
                <div class="footer-enamad-image">
                    <img src="/images/enamad.png" alt="image">
                </div>
            </div>
        </div>
        <div class="row under-footer mt-5 justify-content-center align-items-center">
            <p class="mt-2 text-center p-4 p-lg-0">
                تمامی حقوق این سایت متعلق به زیبا اسلامی می باشد.طراحی و پیاده سازی توسط <a href="#">شرکت نوآوران نگاه اندیش</a>
            </p>
        </div>
    </div>
</footer>
<!-- end footer -->
</body>
</html>
