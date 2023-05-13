@extends('master')

@section('head')
    @parent
    <!--css files-->
    <link rel="stylesheet" href="/css/sign-in.css">
    <!--font awesome cdn-->
    <script src="https://kit.fontawesome.com/dcc0def279.js" crossorigin="anonymous"></script>

    <title>Document</title>
@endsection

@section('content')
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
                    <form method="post" action="{{route('lwp')}}" class="mt-4">
                        @csrf
                        <div class="form-group d-flex flex-column">
                            <label>تلفن همراه</label>
                            <input type="number" name="phone" id="telephone" class="p-2">
                        </div>
                        <div class="form-group d-flex flex-column">
                            <label>رمزعبور</label>
                            <input type="password" name="password" id="password" class="p-2">
                        </div>

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
                        <button class="sign-in-button mt-5" style="width: 100%">
                            ورود به حساب کاربری
                        </button>
                        <p class="lets-sign text-center mt-4">
                            آیا حساب کاربری ندارید؟<a href="#">ثبت نام</a>
                        </p>
                    </form>
                </div>
            </div>
        </section>
    </article>
    <!-- end sigin-in page article-->
@endsection

