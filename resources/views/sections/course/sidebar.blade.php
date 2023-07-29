<div class="new-workshop-left computer col-12 col-lg-4 d-flex flex-column-reverse flex-lg-column">
    <div class="new-workshop-left-sign text-right p-4 mt-3 mt-lg-0">
        <h4>
            قیمت دوره:
        </h4>
        <h5 class="desc-price text-center p-3 mt-3">
            <span x-text=" new Intl.NumberFormat('fa').format({{$course_data['price']}})">

                                    </span>
            <span>
                                        تومان
                                    </span>
        </h5>
        @guest()
        <p class="mt-5">
            لطفاً برای ثبت نام در این دوره,ابتدا <a href="{{route('login')}}">وارد سایت </a>شوید
        </p>
        @endguest
        @if($already_purchased[0])
            <a href="{{route('order-info',[ 'order' => $already_purchased[1] ] )}}" class="ex-bold-button" style="background-color: #0A6146">
                دانلود دوره
            </a>
        @else
            <a href="{{route('course_register',['course' => $course_data['slug']])}}" class="ex-bold-button">
                خرید
            </a>
        @endif
    </div>
    <div class="new-workshop-left-desc text-right p-4 mt-4">
        <h3>
            مشخصات دوره
        </h3>
        <div class="mt-4">
            <div class="new-workshop-left-desc-item d-flex flex-row-reverse">
                <img src="/images/user-tag.png" alt="icon" width="24px" height="24px">
                <p class="mr-2">
                    مدرس:خانم زیبا اسلامی
                </p>
            </div>
            <div class="new-workshop-left-desc-item d-flex flex-row-reverse">
                <img src="/images/clock.png" alt="icon" width="24px" height="24px">
                <p class="mr-2">
                    مدت زمان:{{$hour}} ساعت و {{$minute}} دقیقه
                </p>
            </div>
            <div class="new-workshop-left-desc-item d-flex flex-row-reverse">
                <img src="/images/message-question.png" alt="icon" width="24px" height="24px">
                <p class="mr-2">
                    پیش نیاز:{{$course_data['prerequisite']}}
                </p>
            </div>
            <div class="new-workshop-left-desc-item d-flex flex-row-reverse">
                <img src="/images/note-2.png" alt="icon" width="24px" height="24px">
                <p class="mr-2" style="direction: rtl">
                    تعداد سرفصل ها:{{$course_data['section_count']}}
                </p>
            </div>
            <div class="new-workshop-left-desc-item d-flex flex-row-reverse">
                <img src="/images/Headset.png" alt="icon" width="24px" height="24px">
                <p class="mr-2" style="direction: rtl">
                    روش پشتیبانی:{{$course_data['support_way']}}
                </p>
            </div>
            <div class="new-workshop-left-desc-item d-flex flex-row-reverse">
                <img src="/images/video-circle.png" alt="icon" width="24px" height="24px">
                <p class="mr-2" style="direction: rtl">
                    روش دریافت:{{$course_data['delivery_way']}}
                </p>
            </div>
            <div class="new-workshop-left-desc-item d-flex flex-row-reverse">
                <img src="/images/layer.png" alt="icon" width="24px" height="24px">
                <p class="mr-2">
                    سطح دوره:{{$course_data['level']}}
                </p>
            </div>
        </div>
    </div>
</div>
