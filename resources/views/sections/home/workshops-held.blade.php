<section class="last-workshops mt-5">
    <div class="container">
        <div class="last-workshops-title mb-4">
            <h2 class="text-right">
                ورکشاپ های برگزار شده
            </h2>
        </div>
        <div class="row d-flex" style="flex-direction: row-reverse">
            <!-- last-workshop-item -->
            @foreach($held_workshops as $held_workshop)

                <div class="col-6 col-lg-4 last-card-container">
                    <a href="{{route('choose-workshop',$held_workshop->slug)}}">
                        <div class="card p-2 p-lg-3 mb-4">
                            <div class="card-header bg-transparent">
                                <h3 class="text-center">
                                    {{$held_workshop->city['name']}}
                                </h3>
                            </div>
                            <div class="card-body d-flex justify-content-between">
                                <div
                                    class="last-workshop-desc-time col-6 d-flex flex-column justify-content-center align-items-center">
                                    <h6 class="text-center px-2 pb-1">
                                        {{$held_workshop->period}} ساعت
                                    </h6>
                                    <p class="text-center">
                                        مدت زمان ورکشاپ
                                    </p>
                                </div>
                                <div
                                    class="last-workshop-desc-number col-6 d-flex flex-column justify-content-center align-items-center">
                                    <h6 class="text-center px-2 pb-1">
                                        {{$held_workshop->capacity}}
                                    </h6>
                                    <p class="text-center">
                                        شرکت کننده
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

            @endforeach
        </div>
    </div>
</section>
