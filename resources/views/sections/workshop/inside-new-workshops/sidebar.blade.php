<div class="new-workshop-left col-12 col-lg-4 d-flex flex-column-reverse flex-lg-column">
    <div class="new-workshop-left-sign text-right p-4 mt-3 mt-lg-0">
        <h4>
            قیمت ورکشاپ:
        </h4>
        <h5 class="desc-price text-center p-3 mt-3">
{{--            {{$price}} تومان--}}
            <span dir="ltr">{{$workshop_data['price']}}</span>
             تومان
        </h5>
        @guest
        <p class="mt-5">
            لطفاً برای ثبت نام در ورکشاپ,ابتدا <a href="{{route('login')}}">وارد سایت </a>شوید
        </p>
        @endguest
        <a href="{{route('workshop_register',['workshop'=>$workshop_data['slug']])}}" class="ex-bold-button">
            ثبت نام
        </a>
    </div>
    <div class="new-workshop-left-desc text-right p-4 mt-4">
        <h3>
            مشخصات ورکشاپ
        </h3>
        <div class="mt-4">
            <div class="new-workshop-left-desc-item d-flex flex-row-reverse">
                <img src="/images/user-tag.png" alt="icon" width="24px" height="24px">
                <p class="mr-2">
                    مدرس:خانم زیبا اسلامی
                </p>
            </div>
            <div class="new-workshop-left-desc-item d-flex flex-row-reverse">
                <img src="/images/Place-3.png" alt="icon" width="24px" height="24px">
                <p class="mr-2">
                    محل برگزاری :{{$workshop_data['city']}}
                </p>
            </div>
            <div class="new-workshop-left-desc-item d-flex flex-row-reverse">
                <img src="/images/Calender.png" alt="icon" width="24px" height="24px">
                <p class="mr-2">
                    زمان برگزاری:{{$workshop_data['date']}}
                </p>
            </div>
            <div class="new-workshop-left-desc-item d-flex flex-row-reverse">
                <img src="/images/clock.png" alt="icon" width="24px" height="24px">
                <p class="mr-2">
                    ساعت برگزاری:{{$workshop_data['hour']}}
                </p>
            </div>
        </div>
    </div>
</div>
