@extends('master')
@section('head')
    @parent
    <link rel="stylesheet" href="/css/user-panel-edit.css">
    <!--font awesome cdn-->
    <script src="https://kit.fontawesome.com/dcc0def279.js" crossorigin="anonymous"></script>
    <!--main.js-->
    <title>Document</title>
@endsection
<!-- start user panel edit article -->
@section('content')
    <article class="user-panel-edit py-5">
        <section class="container">
            <div class="row flex-column-reverse flex-lg-row">
                <div class="col-12 col-lg-8 mt-3 mt-lg-0">
                    <div class="user-edit card text-right p-4">
                        <form method="post" action="{{route('user_panel_update')}}">
                            @csrf
                            <div class="user-edit-name">
                                <div class="mt-4">
                                    <div class="form-group d-flex flex-row-reverse row">
                                        <div class="form-group-input d-flex flex-column col-12 col-lg-6">
                                            <label>نام</label>
                                            <input type="text" name="name" id="name" class="p-2"
                                                   value="{{$loginInfo['name']}}">
                                        </div>
                                        <div class="form-group-input d-flex flex-column col-12 col-lg-6 mt-3 mt-lg-0">
                                            <label>نام خانوادگی</label>
                                            <input type="text" name="lastname" id="big-name" class="p-2"
                                                   value="{{$loginInfo['lastname']}}">
                                        </div>
                                    </div>
                                    <div class="form-group d-flex flex-column">
                                        <label>تلفن همراه</label>
                                        <input type="text" name="phone" id="telephone" class="p-2"
                                               value="{{$loginInfo['phone']}}" disabled>
                                    </div>
                                    <div class="form-group d-flex flex-column">
                                        <label>ایمیل (اختیاری)</label>
                                        <input type="email" name="email" id="email" class="p-2"
                                               value="{{$loginInfo['email']}}">
                                    </div>
                                </div>
                            </div>
                            <div class="user-edit-pass mt-5">
                                <h3>
                                    تغییر گذرواژه
                                </h3>
                                <div class="form-group d-flex flex-column">
                                    <label>رمز عبور جدید</label>
                                    <input type="password" name="password" id="new-pass" class="p-2">
                                </div>
                                <div class="form-group d-flex flex-column">
                                    <label>تکرار رمز عبور</label>
                                    <input type="password" name="password_confirmation" id="repeat-pass" class="p-2">
                                </div>
                            </div>
                            <div class="user-edit-button mt-3 mt-lg-4">
                                <button  class="black-button">
                                    ثبت تغییرات
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @include('sections.user-panel.sidebar',compact('user'))
            </div>
        </section>
    </article>
@endsection
<!-- end user panel edit article -->
