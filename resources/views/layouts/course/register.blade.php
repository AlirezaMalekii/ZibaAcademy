@extends('master')
@section('head')
    @parent
    <link rel="stylesheet" href="/css/basket.css">
    @vite(['resources/js/app.js'])
    <title>Document</title>
@endsection
@section('content')
    <article class="reservation-page py-5">
        <!--workshops-page top-right gradient-->
        <div class="right-gradient">
            <img src="/images/right-gradient.png" alt="gradient">
        </div>


        <section class="container">
            <!-- start progress-bar section -->
            <section class="container prog-bar mb-5">
                <div class="prog-wrapper pt-4 pb-2 row d-flex justify-content-between">
                    <div class="prog-item col-4">
                        <div class="prog-item-wrapper text-center">
                            <h3 class="p-3 prog-item-number d-flex align-items-center justify-content-center text-center">
                                3
                            </h3>
                            <p class="prog-item-desc mt-2">
                                پرداخت
                            </p>
                        </div>
                    </div>
                    <div class="prog-item col-4 active">
                        <div class="prog-item-wrapper text-center">
                            <h3 class="p-3 prog-item-number d-flex align-items-center justify-content-center text-center">
                                2
                            </h3>
                            <p class="prog-item-desc mt-2">
                                سبد خرید
                            </p>
                        </div>
                    </div>
                    <div class="prog-item col-4">
                        <div class="prog-item-wrapper text-center">
                            <h3 class="p-3 prog-item-number d-flex align-items-center justify-content-center text-center">
                                <img src="/images/check.png" alt="icon">
                            </h3>
                            <p class="prog-item-desc mt-2">
                                جزییات دوره
                            </p>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end progress-bar section -->


            <!-- basket pop-->
            {{--<div class="basket-pop">
                <div class="reservation-pop">
                    <div
                        class="card order-empty order-empty-card px-4 pb-1 mt-3 mb-3 mt-lg-0 d-flex flex-row-reverse align-items-center justify-content-between">
                        <div class="order-empty-title d-flex flex-row-reverse mt-3">
                            <img src="/images/tick.png" alt="icon" width="28px" height="28px">
                            <p class="mt-1">
                                “دوره تمبور دوزی” به سبد خرید شما افزوده شد.
                            </p>
                        </div>
                        <div class="order-empty-button">
                            <a href="#">
                                ادامه خرید
                            </a>
                        </div>
                    </div>
                </div>
            </div>--}}


            <div class="row d-flex align-items-start flex-column-reverse flex-lg-row" x-data="{price: 0 ,discount:0}"
                 x-init="price = {{ $course_data['price']}}; discount= '{{isset($discount) ? $discount->code : 0}}'">
                <div class="reservation-pay col-12 col-lg-4 mt-3 mt-lg-0">
                    <div class="reservation-pay-up text-right p-4">
                        <div class="reservation-pay-up-items mt-4 pb-3">
                            <div class="reservation-pay-up-item d-flex flex-row-reverse mt-2 justify-content-between">
                                <p>
                                    مجموع
                                </p>
                                <h5 class="mt-1">
                                    <span
                                        x-text="new Intl.NumberFormat('fa').format(eval(price {{ isset($discount) ? is_null($discount->amount)?'*':'-' :'*' }} {{isset($discount) ? $discount->amount ?? ((100 -$discount->percent)/100) :1 }} )) "></span>
                                    تومان
                                </h5>
                            </div>
                        </div>
                        <form action="{{route('course_payment',['course'=>$course_data['slug']])}}" method="post">
                            @csrf
                            <input type="hidden" name="discount" value="{{isset($discount) ? $discount->code : ""}}">
                            <button type="submit" class="ex-bold-button mt-4 d-block border-0" data-toggle="modal"
                                    data-target="#exampleModal" style="width: 100%;">
                                ادامه جهت تسویه حساب
                            </button>
                        </form>
                    </div>
                </div>
                <div class="order-cansel-wrapper col-12 col-lg-8 text-right">
                    <div
                        class="order-detail-cancel-items d-flex flex-column flex-lg-row justify-content-between text-right align-items-center mb-4 p-4">
                        <div
                            class="order-detail-cancel-item-image mb-3 mb-lg-0 d-flex flex-column flex-lg-row align-items-center">
                            {{-- <img src="/images/Close.png" alt="icon" width="24px" height="24px"
                                  class="mb-2 mb-lg-0 ml-lg-2 basketcansel">--}}
                            {{--                        <img src="/images/order-cancel.png" alt="image">--}}
                            <img src="{{$image[0]['file']['thumb']}}" alt="image" style="max-height: 88px">
                        </div>
                        <div
                            class="order-detail-cancel-item-price d-flex flex-row flex-lg-column justify-content-between">
                            <h5>
                                محصول
                            </h5>
                            <p>
                                دوره تمبور دوزی(پیشرفته)
                            </p>
                        </div>
                        <div
                            class="order-detail-cancel-item-price d-flex flex-row flex-lg-column justify-content-between">
                            <h5>
                                قیمت
                            </h5>
                            <p>
                                {{$course_data['price']}} تومان
                            </p>
                        </div>
                    </div>

                    <div class="order-detail-cancel-items text-right p-4">
                        <form x-ref="disform"
                              class="d-flex flex-column flex-lg-row justify-content-between align-items-center"
                              method="post" action="{{route('set.discount.course',['course'=>$course_data['slug']])}}"
                              x-on:submit.prevent>
                            @csrf
                            <input placeholder="کد تخفیف خود را وارد کنید" type="text" name="discount"
                                   id="discount" :readonly="discount!=0" :value="discount!=0 ? discount : '' ">
                            <button class="discount-button mt-4 mt-lg-0"
                                    x-on:click="discount==0 ? $refs.disform.submit() : ''">
                                اعمال کد تخفیف
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>


        <!--workshops-page bottom-left gradient-->
        <div class="left-gradient">
            <img src="/images/left-gradient.png" alt="gradient">
        </div>
    </article>

    {{--<script>
        var btns = document.querySelectorAll('.basketcansel');
        btns.forEach(btnClicked);
        var time = 0;

        function btnClicked(btn, index) {
            btn.addEventListener('click', () => {
                document.getElementsByClassName("order-detail-cancel-items")[index].style = "display : none !important"
            });
        }
    </script>--}}
@endsection
