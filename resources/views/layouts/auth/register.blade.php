@extends('master')
@section('head')
    @parent
    <link rel="stylesheet" href="/css/sign-in.css">
    <script src="https://kit.fontawesome.com/dcc0def279.js" crossorigin="anonymous"></script>
    <title>Document</title>
@endsection
<!-- start register page article -->
@section('content')
    <article class="sign-in-page py-5">
        <section class="container">
            <div class="col-12 col-lg-5 sign-in-bar-wrapper">
                <div class="card sign-in-bar p-2">
                    <div class="d-flex">
                        <div class="col-6 text-center sign-in-bar-item active">
                            <a href="#" class="tex-center" style="cursor: default">
                                ثبت نام
                            </a>
                        </div>
                        <div class="col-6 text-center sign-in-bar-item">
                            <a href="{{route('login')}}" class="tex-center">
                                ورود به حساب کاربری
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 sign-in-form-wrapper mt-4">
                <div class="card sign-in-form p-4 p-lg-5 text-right">
                    <h2 class="text-center">
                        ثبت نام
                    </h2>
                    <form class="mt-4" method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group d-flex flex-row-reverse row">
                            <div class="form-group-input d-flex flex-column col-12 col-lg-6">
                                <label for="name">نام</label>
                                <input type="text" name="name" id="name" class="p-2" value="{{old('name')}}">
                            </div>
                            <div class="form-group-input d-flex flex-column col-12 col-lg-6 mt-3 mt-lg-0">
                                <label for="big-name">نام خانوادگی</label>
                                <input type="text" name="lastname" id="big-name" class="p-2" value="{{old('lastname')}}">
                            </div>
                        </div>
                        <div class="form-group d-flex flex-column">
                            <label for="telephone">تلفن همراه</label>
                            <input type="text" name="phone" id="telephone" class="p-2" value="{{old('phone')}}">
                        </div>
                        <div class="form-group d-flex flex-column">
                            <label for="email">ایمیل (اختیاری)</label>
                            <input type="email" name="email" id="email" class="p-2"  value="{{old('email')}}">
                        </div>
                        <div class="form-group d-flex flex-row-reverse row">
                            <div class="form-group-input d-flex flex-column col-12 col-lg-6">
                                <label for="password">رمزعبور</label>
                                <input type="password" name="password" id="password" class="p-2" value="{{old('password')}}">
                            </div>
                            <div class="form-group-input d-flex flex-column col-12 col-lg-6 mt-3 mt-lg-0">
                                <label for="repeat-password">تکرار رمزعبور</label>
                                <input type="password" name="password_confirmation" id="repeat-password" class="p-2" value="{{old('password_confirmation')}}">
                            </div>
                        </div>
                        <button type="submit" class="sign-in-button mt-5" style="width: 100%;border: none">
                            ثبت نام
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </article>
@endsection
<!-- end register page article -->






