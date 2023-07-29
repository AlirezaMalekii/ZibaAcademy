<section class="workshops-items py-4">
    <div class="container">
        <div class="workshops-header row d-flex justify-content-end mb-4">
            <h3 class="text-right">ورکشاپ های برگزار شده</h3>
        </div>
        <div class="row d-flex justify-content-between align-items-center" style="flex-direction: row-reverse; justify-content: flex-start !important">

            <!--workshop item-->
            @foreach($held_workshops as $held_workshop)
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card workshop-item px-2 pb-2 mb-5 mb-lg-0" style="background-image: url({{$held_workshop['files'][0]['file']['thumb']}})">
                        <div class="card-body workshop-item-desc bg-white">
                            <h3 class="text-right mb-3">
                                {{$held_workshop['title']}}
                            </h3>
                            <div class="workshop-item-desc-location d-flex justify-content-end">
                                <p class="text-right">
                                    محل برگزاری: {{\App\Models\City::find($held_workshop['city_id'])->name}}
                                </p>
                                <img class="mt-1 ml-2" src="/images/location.png" alt="icon" width="14px" height="18px">
                            </div>
                            <div class="workshop-item-desc-location d-flex justify-content-end">
                                <p>
                                    زمان برگزاری: {{jdate($held_workshop['event_time'])->format('Y/m/d')}}
                                </p>
                                <img class="mt-1 ml-2" src="/images/date.png" alt="icon" width="16px" height="18px">
                            </div>
                            <div class="workshop-item-button">
                                <a href="{{route('choose-workshop',['workshop'=>$held_workshop['slug']])}}" class="py-2">مشاهده جزییات بیشتر</a>
{{--                                <a href="{{route('choose-workshop',['workshop'=>$held_workshop['slug']])}}" class="py-2">مشاهده جزییات بیشتر</a>--}}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
