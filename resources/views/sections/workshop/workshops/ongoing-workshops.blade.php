<section class="workshops-items py-5">
    <div class="container">
        <div class="workshops-header row d-flex justify-content-end mb-4">
            <h3 class="text-right">ورکشاپ های درحال برگزاری</h3>
        </div>
        <div class="row d-flex justify-content-between align-items-center">
            <!--workshop item-->
            @foreach($ongoing_workshops as $ongoing_workshop)
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card workshop-item px-2 pb-2 mb-5 mb-lg-0">
                    <div class="card-body workshop-item-desc bg-white">
                        <h3 class="text-right mb-3">
                            {{$ongoing_workshop['title']}}
                        </h3>
                        <div class="workshop-item-desc-location d-flex justify-content-end">
                            <p class="text-right">
                                محل برگزاری: {{\App\Models\City::find($ongoing_workshop['city_id'])->name}}
                            </p>
                            <img class="mt-1 ml-2" src="/images/location.png" alt="icon" width="14px" height="18px">
                        </div>
                        <div class="workshop-item-desc-location d-flex justify-content-end">
                            <p>
                                زمان برگزاری: {{jdate($ongoing_workshop['event_time'])->format('Y/m/d')}}
                            </p>
                            <img class="mt-1 ml-2" src="/images/date.png" alt="icon" width="16px" height="18px">
                        </div>
                        <div class="workshop-item-button">
                            <a href="{{route('choose-workshop',['workshop'=>$ongoing_workshop['slug']])}}" class="py-2">مشاهده جزییات بیشتر</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
