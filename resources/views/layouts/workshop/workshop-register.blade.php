@extends('master')
@section('head')
    @parent
    <!--css files-->
    <link rel="stylesheet" href="/css/register.css">
    <!--bootstrap cdn-->
    <title>Document</title>
    @vite(['resources/js/app.js'])
@endsection

@section('content')
    <!-- start register page article -->

    {{--    <article class="register-page py-5" x-data="{--}}
    {{--        number:1,--}}
    {{--    price: (this.number * {{ $workshop_data['price']}})--}}
    {{--    }">--}}
    <article class="register-page py-5" style="overflow: hidden" x-data="{ number: 1, price: 0 ,discount:0}"
{{--             x-init="price = number * {{ $workshop_data['price']}}; discount= '{{isset($discount) ? $discount->code : 0}}'; if ( {{isset($discount)}} ) {{is_null($discount->percent) ? operator='-' : operator = '*'}}">--}}
             x-init="price = number * {{ $workshop_data['price']}}; discount= '{{isset($discount) ? $discount->code : 0}}'">
        <!--workshops-page top-right gradient-->
        <div class="right-gradient">
            <img src="/images/right-gradient.png" alt="gradient">
        </div>


        <!-- start progress-bar section -->
        <section class="container prog-bar mb-5">
            <div class="prog-wrapper pt-4 pb-2 row d-flex justify-content-between">
                <div class="prog-item col-3">
                    <div class="prog-item-wrapper text-center">
                        <h3 class="p-3 prog-item-number d-flex align-items-center justify-content-center text-center">
                            4
                        </h3>
                        <p class="prog-item-desc mt-2">
                            دریافت بلیط
                        </p>
                    </div>
                </div>
                <div class="prog-item col-3">
                    <div class="prog-item-wrapper text-center">
                        <h3 class="p-3 prog-item-number d-flex align-items-center justify-content-center text-center">
                            3
                        </h3>
                        <p class="prog-item-desc mt-2">
                            رزرو و پرداخت
                        </p>
                    </div>
                </div>
                <div class="prog-item col-3 active">
                    <div class="prog-item-wrapper text-center">
                        <h3 class="p-3 prog-item-number d-flex align-items-center justify-content-center text-center">
                            2
                        </h3>
                        <p class="prog-item-desc mt-2">
                            ثبت نام
                        </p>
                    </div>
                </div>
                <div class="prog-item col-3">
                    <div class="prog-item-wrapper text-center">
                        <h3 class="p-3 prog-item-number d-flex align-items-center justify-content-center text-center">
                            <img src="/images/check.png" alt="icon">
                        </h3>
                        <p class="prog-item-desc mt-2">
                            جزییات ورکشاپ
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- end progress-bar section -->
        <section class="container">
            <div class="row d-flex flex-column-reverse flex-lg-row align-items-start">
                @include('sections.workshop.workshop-register.sidebar',compact('workshop_data'))
                <div class="col-12 col-lg-8 register-right">
                    <div class="register-right-desc p-4">
                        <div class="row d-flex flex-row-reverse">
                            <div class="register-right-desc-image col-12 col-lg-6">
                                <img src="{{$image[0]['file']['thumb']}}" alt="image">
                            </div>
                            <div class="register-right-desc-desc col-12 col-lg-6 text-right mt-3 mt-lg-0">
                                <h3>
                                    {{$workshop_data['title']}}
                                </h3>
                                <div class="register-right-desc-desc-items">
                                    <div class="register-right-desc-desc-item d-flex flex-row-reverse">
                                        <img src="/images/location.png" alt="icon" width="24px" height="24px">
                                        <p class="mr-2">
                                            محل برگزاری :{{$workshop_data['city']}}
                                        </p>
                                    </div>
                                    <div class="register-right-desc-desc-item d-flex flex-row-reverse">
                                        <img src="/images/Calender.png" alt="icon" width="24px" height="24px">
                                        <p class="mr-2">
                                            زمان برگزاری:{{$workshop_data['date']}}
                                        </p>
                                    </div>
                                    <div class="register-right-desc-desc-item d-flex flex-row-reverse">
                                        <img src="/images/clock.png" alt="icon" width="24px" height="24px">
                                        <p class="mr-2">
                                            ساعت برگزاری:{{$workshop_data['hour']}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="register-table p-4 mt-3">
                        <div class="d-flex flex-column">
                            <table class="text-center">
                                <tr>
                                    <th>
                                        مهلت ثبت نام
                                    </th>
                                    <th>
                                        قیمت
                                    </th>
                                    <th>
                                        موضوع ورکشاپ
                                    </th>
                                </tr>
                                <tr>
                                    <td class="register-table-td">
                                        {{$workshop_data['time']}}
                                    </td>
                                    <td class="register-table-td table-price">
                                        <span dir="ltr">{{$workshop_data['price']}}</span> تومان
                                    </td>
                                    <td class="register-table-td">
                                        {{$workshop_data['title']}}
                                    </td>
                                </tr>
                            </table>
                            <div
                                class="row register-number-wrapper d-flex justify-content-between align-items-center flex-row-reverse p-4">
                                <h3 class="col-8 col-lg-10 text-right mt-2">
                                    تعداد
                                </h3>
                                <div
                                    class="col-4 col-lg-2 d-flex register-number justify-content-between px-1 align-items-center align-content-center">
                                    <button
                                        @click="number < {{$workshop_data['capacity']-$workshop_data['registration_number']}}? number++ :'';price=number * {{$workshop_data['price']}}"
                                        style="font-size: 28px;
    margin-bottom: .5rem;
    color: #6C6E6D;
    cursor: pointer;
    background-color: white;
    border: none;">
                                        +
                                    </button>
                                    <h6 x-text="number">1</h6>
                                    <button @click="number>1 ? number--:'';price=number * {{$workshop_data['price']}}"
                                            style="font-size: 28px;
                                            margin-bottom: .5rem;
                                            color: #6C6E6D;
                                            cursor: pointer;
                                            background-color: white;
                                            border: none;">
                                        -
                                    </button>
                                    {{--                                    <input style="width: inherit; border: none" type="number" min="0" max="255" x-model="number">--}}
                                    {{--                                    <span x-text="number"></span>--}}
                                    {{--                                    <h5 @click="number-1">-</h5>--}}
                                </div>
                            </div>
                            <div class="register-table-update pt-3">
                                <div
                                    class="d-flex flex-column flex-lg-row-reverse justify-content-between align-items-center">
                                    <form x-ref="disform" class="d-flex flex-row-reverse mt-3" method="post" action="{{route('set.discount.workshop',['workshop'=>$workshop_data['slug']])}}"  x-on:submit.prevent>
                                        @csrf
                                        <div class="form-group discount-input ml-2">
                                            <input type="text" placeholder="کد تخفیف خود را وارد کنید"
                                                   name="discount"
                                                   id="discount" :readonly="discount!=0" :value="discount!=0 ? discount : '' ">
                                        </div>
                                        <div class="discount-button" type="button" x-on:click="discount==0 ? $refs.disform.submit() : ''">
                                            <button class="black-button">
                                                اعمال
                                            </button>
                                        </div>
                                    </form>
{{--                                    <a href="{{route('workshop_register',['workshop'=>$workshop_data['slug']])}}">--}}
{{--                                        <button class="black-button" type=""--}}
{{--                                                @click="alert($event.target.getAttribute('message'))">--}}
{{--                                            بروزرسانی--}}
{{--                                        </button>--}}
{{--                                    </a>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!--workshops-page bottom-left gradient-->
        <div class="left-gradient">
            <img src="/images/left-gradient.png" alt="gradient">
        </div>
    </article>
    <!-- end register page article -->
@endsection
{{--@section('script')--}}
{{--@endsection--}}

