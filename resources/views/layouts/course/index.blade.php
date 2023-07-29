@extends('master')
@section('head')
    @parent
    <link rel="stylesheet" href="/css/courses.css">
    <title>Document</title>
@endsection
@section('content')
    <!-- start courses-filter -->
    <div class="course-filter container py-5">
        <div class="col-12 col-lg-5 sign-in-bar-wrapper">
            <div class="card sign-in-bar p-2">
                <div class="d-flex" id="btns-container">
                    <div class="col-6 text-center sign-in-bar-item btn-i">
                        <button href="#" class="tex-center" onclick="filterSelection('start')">
                            دوره های مقدماتی
                        </button>
                    </div>
                    <div class="col-6 text-center sign-in-bar-item btn-i">
                        <button href="#" class="tex-center" onclick="filterSelection('pro')">
                            دوره های پیشرفته
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end courses-filter -->


    <!-- start courses-items -->
    <div class="courses-items container mb-5">
        @foreach($courses as $course)
        <a @class([
    'course-item',
    'pro' => $course['level']=='پیشرفته',
    'filterDiv',
    'start' => $course['level']=='مقدماتی',
]) href="{{route('choose-course',['course'=>$course['slug']])}}">
            <div class="course-item-image">
                <img src="{{$course['files'][0]['file']['thumb']}}" alt="image">
            </div>
            <div class="course-item-title mt-2">
                <p class="text-center">
                    {{$course['title']}}
                </p>
            </div>

            <div @class([
        'course-item-label',
        'pro'=>$course['level']=='پیشرفته',
        'start'=>$course['level']=='مقدماتی'
])
                class="course-item-label pro">
                @if($course['level']!=null)
                    <p class="text-center">
                        {{$course['level']}}
                    </p>
                    @else
                        </br>
                @endif
            </div>
            <div class="course-item-desc d-flex flex-row justify-content-between mt-4" style="flex-direction: row-reverse !important;">
                <div class="course-item-desc-item d-flex flex-row-reverse">
                    <img src="/images/user-octagon.png" alt="icon" width="18px" height="18px">
                    <p class="text-right">
                        مدرس دوره:زیبا اسلامی
                    </p>
                </div>
            </div>
            <div class="course-item-footer d-flex flex-row-reverse justify-content-between">
                <div class="course-item-footer-sign">
                    <button>
                        خرید
                    </button>
                </div>
                <div class="course-item-footer-price">
                    <p class="mt-2">
                        قیمت:{{$course['price']}} تومان
                    </p>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    <!-- end courses-items -->
@endsection

@section('script')
    <script>
        filterSelection("all")

        function filterSelection(c) {
            var x, i;
            x = document.getElementsByClassName("filterDiv");
            if (c == "all") c = "";
            for (i = 0; i < x.length; i++) {
                w3RemoveClass(x[i], "show");
                if (x[i].className.indexOf(c) > -1) w3AddClass(x[i], "show");
            }
        }

        function w3AddClass(element, name) {
            var i, arr1, arr2;
            arr1 = element.className.split(" ");
            arr2 = name.split(" ");
            for (i = 0; i < arr2.length; i++) {
                if (arr1.indexOf(arr2[i]) == -1) {
                    element.className += " " + arr2[i];
                }
            }
        }

        function w3RemoveClass(element, name) {
            var i, arr1, arr2;
            arr1 = element.className.split(" ");
            arr2 = name.split(" ");
            for (i = 0; i < arr2.length; i++) {
                while (arr1.indexOf(arr2[i]) > -1) {
                    arr1.splice(arr1.indexOf(arr2[i]), 1);
                }
            }
            element.className = arr1.join(" ");
        }


        var btnContainer = document.getElementById("btns-container");
        console.log(btnContainer)
        var btns = btnContainer.getElementsByClassName("btn-i");
        for (var i = 0; i < btns.length; i++) {
            btns[i].addEventListener("click", function () {
                var current = document.getElementsByClassName("active");
                current[0].className = current[0].className.replace(" active", "");
                this.className += " active";
            });
        }
    </script>
@endsection
