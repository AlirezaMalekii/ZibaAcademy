@extends('master')
@section('head')
    @parent
    <link rel="stylesheet" href="/css/inside-new-workshop.css">
    <title>Document</title>
@endsection

@section('content')
    <article class="new-workshop-page py-5" style="overflow: hidden">


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
                            1
                        </h3>
                        <p class="prog-item-desc mt-2">
                            جزییات ورکشاپ
                        </p>
                    </div>
                </div>
                <div class="prog-item col-3">
                    <div class="prog-item-wrapper text-center">
                        <h3 class="p-3 prog-item-number d-flex align-items-center justify-content-center text-center">
                            1
                        </h3>
                        <p class="prog-item-desc mt-2">
                            جزییات ورکشاپ
                        </p>
                    </div>
                </div>
                <div class="prog-item col-3">
                    <div class="prog-item-wrapper text-center">
                        <h3 class="p-3 prog-item-number d-flex align-items-center justify-content-center text-center">
                            1
                        </h3>
                        <p class="prog-item-desc mt-2">
                            جزییات ورکشاپ
                        </p>
                    </div>
                </div>
                <div class="prog-item col-3 active">
                    <div class="prog-item-wrapper text-center">
                        <h3 class="p-3 prog-item-number d-flex align-items-center justify-content-center text-center">
                            1
                        </h3>
                        <p class="prog-item-desc mt-2">
                            جزییات ورکشاپ
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- end progress-bar section -->


        <!-- start inside-new-workshop artice -->
        <section class="container inside-n-workshop">
            <div class="row d-flex flex-column-reverse flex-lg-row">
                @include('sections.workshop.inside-new-workshops.sidebar',compact('workshop_data'))
                <div class="workshop-right col-12 col-lg-8">
                    <div class="new-workshop-right-hero text-right p-4">
                        <h1>
                            {{$workshop_data['title']}}
                        </h1>
                        <div class="new-workshop-right-hero-image mt-3">
                            <img src="{{$image[0]['file']['thumb']}}" alt="image">
                        </div>
                    </div>
                    <div class="new-workshop-right-desc text-right p-4 mt-4">
                        <h3>
                            معرفی ورکشاپ
                        </h3>
                        <div class="new-workshop-teaser mt-4">
                            @if($stream_video)
                                {!! html_entity_decode($video_url) !!}
                            @else
                                <video controls>
                                    <source src="{{$video_url}}" type=video/mp4>
                                </video>
                            @endif
                        </div>
                        <h4 class="mt-5">
                            درباره ورکشاپ
                        </h4>
                        <p class="mt-3">
                          {{$workshop_data['body']}}
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- end inside-new-workshop artice -->


        <!--workshops-page bottom-left gradient-->
        <div class="left-gradient">
            <img src="/images/left-gradient.png" alt="gradient">
        </div>
    </article>
@endsection
