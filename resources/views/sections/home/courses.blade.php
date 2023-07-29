<section class="index-courses py-5 mt-5 container">
    <div class="index-center-title text-center d-flex align-items-center justify-content-center mb-5">
        <img src="/images/title-vector-right.png">
        <h2>
            دوره های آموزشی
        </h2>
        <img src="/images/title-vector-left.png">
    </div>
    <div class="index-courses-header">
        <div class="row text-right d-flex flex-row justify-content-between align-items-center">
            <p>
                {{--                برای مشاهده و شرکت در دوره های آموزشی تمبور دوزی،تمبور دوزی،لورم ایپسوم  و... <br/>روی مشاهده همه کلیک کنید.--}}
                برای مشاهده و شرکت در دوره های آموزشی
                @foreach($courses as $course)
                    {{$course->title}}
               و
                    @break($loop->index==2)
                @endforeach
                ...
                <br/>روی مشاهده همه کلیک کنید.
            </p>
            <a href="{{route('courses')}}" class="light-btn d-flex mt-3 py-2 px-4 flex-row-reverse align-items-center">
                مشاهده دوره ها
                <img src="/images/arrow-left.png" alt="icon" width="24px" height="17px" class="mr-2">
            </a>
        </div>
    </div>
    <div class="index-courses-items-wrapper">
        <div class="index-courses-items mt-5 d-flex flex-row" style="overflow: auto">
            <!-- course-item -->
            @foreach($courses as $course)
                <a href="{{route('choose-course',['course'=>$course->slug])}}" style="text-decoration: none; max-width: 288px">
                    <div class="course-item">
                        <div class="course-item-image">
                            <img src="{{$course->files->first()->file['thumb']}}" alt="image">
                        </div>
                        <div class="course-item-title mt-2">
                            <p class="text-center">
                               {{$course->title}}
                            </p>
                        </div>
                        <div @class([
        "course-item-label",
        "start"=>$course->level=='مقدماتی',
        "pro"=>$course->level=='پیشرفته'
])>
                            @if($course->level!=null)
                            <p class="text-center">
                                {{$course->level}}
                            </p>
                            @else
                                </br>
                            @endif
                        </div>
                        <div class="course-item-desc d-flex flex-row justify-content-between mt-4">
                            <div class="course-item-desc-item d-flex flex-row-reverse">
                                <img src="/images/people2.png" alt="icon" width="18px" height="18px">
                                <p class="text-right">
                                    مدرس دوره:زیبا اسلامی
                                </p>
                            </div>
                        </div>
                        <div class="course-item-footer d-flex flex-row-reverse justify-content-between">
                            <div class="course-item-footer-sign">
                                <button>
                                    ثبت نام
                                </button>
                            </div>
                            <div class="course-item-footer-price">
                                <p class="mt-2">
                                    قیمت:{{$course->price}} تومان
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
