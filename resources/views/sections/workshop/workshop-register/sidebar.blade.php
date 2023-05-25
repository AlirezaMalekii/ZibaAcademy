<div class="col-12 col-lg-4 register-left p-4 mt-3 mt-lg-0">
    <div class="register-left-detail text-right">
        <h4>جزییات:</h4>
        <div class="register-left-detail-items">
            <div class="register-left-detail-item d-flex flex-row-reverse">
                <img src="/images/teacher.png" alt="icon" width="24px" height="24px">
                <p class="mr-2">
                    تعداد ثبت نام ها:{{$workshop_data['registration_number']}} نفر
                </p>
            </div>
            <div class="register-left-detail-item d-flex flex-row-reverse">
                <img src="/images/Warning.png" alt="icon" width="24px" height="24px">
                <p class="mr-2">
                    وضعیت ورکشاپ:در حال ثبت نام
                </p>
            </div>
            <div class="register-left-detail-item d-flex    flex-row-reverse">
                <img src="/images/people1.png" alt="icon" width="24px" height="24px">
                <p class="mr-2">
                    ظرفیت ورکشاپ:{{$workshop_data['capacity']}} نفر
                </p>
            </div>
        </div>
    </div>
    <div class="register-left-detail-buy text-right">
        <h4>
            جمع کل:
        </h4>
        <h5 class="desc-price text-center p-3 mt-3">
            {{--            <span dir="ltr">{{$workshop_data['price']}}</span> تومان--}}
            {{--            <span dir="ltr" x-text="price">{{number_format(1000000)}}</span> تومان--}}
            {{--            <span dir="ltr" x-text="new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(price)"></span> تومان--}}
            <span dir="ltr"
                  x-text="new Intl.NumberFormat('fa').format(eval(price {{ isset($discount) ? is_null($discount->amount)?'*':'-' :'*' }} {{isset($discount) ? $discount->amount ?? ((100 -$discount->percent)/100) :1 }} )) "></span>
            تومان
            {{--            <span dir="ltr" x-text="new Intl.NumberFormat('fa').format(eval(price {{ isset($discount) ? is_null($discount->amount)?'*':'-' :'*' }} {{isset($discount) ? is_null($discount->amount) ? ((100 -$discount->percent)/100) : ($discount->amount * ) :1 }} )) "></span> تومان--}}
        </h5>
        <form action="{{route('create-order',['workshop'=>$workshop_data['slug']])}}" method="post">
            @csrf
            {{--            <input type="hidden" name="price" :value="price">--}}
            <input type="hidden" name="number" :value="number">
            <input type="hidden" name="discount" value="{{isset($discount) ? $discount->code : ""}}">
            <button style="width: 100%; border: none" class="ex-bold-button mt-4">
                ادامه جهت تکمیل اطلاعت
            </button>
        </form>
    </div>
</div>
