@extends('master')
@section('head')
    @parent
    <!--css files-->
    <link rel="stylesheet" href="/css/sign-in.css">
    <!--font awesome cdn-->
    <script src="https://kit.fontawesome.com/dcc0def279.js" crossorigin="anonymous"></script>
    @vite(['resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <title>Document</title>
@endsection
<!-- start sigin-in page article-->
@section('content')
    <article class="sign-in-page py-5">
        <section class="container">
            <div class="col-12 col-lg-5 sign-in-bar-wrapper">
                <div class="card sign-in-bar p-2">
                    <div class="d-flex">
                        <div class="col-6 text-center sign-in-bar-item">
                            <a href="{{route('register')}}" class="tex-center">
                                ثبت نام
                            </a>
                        </div>
                        <div class="col-6 text-center sign-in-bar-item active">
                            <a href="#" class="tex-center" style="cursor:default">
                                ورود به حساب کاربری
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 sign-in-form-wrapper mt-4"
                 x-cloak  x-data="{ phone: false}" x-init="phone= {{session()->get('phone') !== null}}">
                <div class="card sign-in-form p-5 text-right">
                    <h2 class="text-center">
                        ورود
                    </h2>
                    <form method="POST" action="{{session()->get('phone') ? route('lwo') : route('make-otp')}}">
                        @csrf
                        <div x-show="phone==false">
                            <div class="form-group d-flex flex-column" style="margin-bottom: 80px">
                                <label for="phone">تلفن همراه</label>
                                <input type="text" name="phone" id="phone" class="p-2"
                                       value="{{session()->get('phone')??''}}" :readonly="phone">
                            </div>
                            <button class="sign-in-button mt-3"
                                    style="border: none; width: 100%; margin-top: 2rem !important;"
                                    :type="!phone ? 'submit' : 'button'"
                                    @click="!phone ? $refs.form.submit() : null">
                                ورود با کد پیامکی
                            </button>
                        </div>
                        <div x-show="phone==true">
                            <div class="code-input mt-4" style="margin-bottom: 80px">
                                <input type="text" name="code" class="p-2" placeholder="کد ارسال شده را وارد کنید"
                                       :disabled="!phone">
                            </div>
                            <button class="sign-in-button mt-5"
                                    style="border: none; width: 100%; margin-top: 2rem !important;" type="button"
                                    @submit.prevent="!phone"
                                    :type="phone ? 'submit' : 'button'"
                                    @click="phone ? $refs.form.submit() : null">
                                ثبت کد پیامکی
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </article>
@endsection
