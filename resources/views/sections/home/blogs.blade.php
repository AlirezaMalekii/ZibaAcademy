<section class="index-blog py-5 mt-5">
    <div class="index-blog-wrapper">
        <div class="index-center-title text-center d-flex align-items-center justify-content-center mb-5">
            <img src="/images/title-vector-right.png">
            <h2>
                وبلاگ
            </h2>
            <img src="/images/title-vector-left.png">
        </div>
        <div class="index-blog-items">
            <!--index blog item-->
            @foreach($blogs as $blog)
                <div class="index-blog-item">
                    <div class="index-blog-item-image">
                        <img src="{{$blog->files()->get()->first()->file['thumb']}}" alt="image">
                    </div>
                    <div class="index-blog-title">
                        <div class="index-blog-title-title">
                            <h6>
                                @foreach($blog->categories as $category)
                                {{$category->title}}
                                @endforeach
                            </h6>
                        </div>
                        <div class="index-blog-title-items">
                            <div class="index-blog-title-item">
                                <p>
                                    {{$blog->viewCount}}
                                </p>
                                <img src="/images/Eye.png" alt="icon" width="20px" height="20px">
                            </div>
                            <div class="index-blog-title-item">
                                <p>
                                    {{$blog->comments_count}}
                                </p>
                                <img src="/images/coment.png" alt="icon" width="20px" height="20px">
                            </div>
                        </div>
                    </div>
                    <div class="index-blog-desc">
                        <h3>
                            {{$blog->title}}
                        </h3>
                        <p>
                            {{$blog->description}}
                        </p>
                        <a>
                            مشاهده
                            <img src="/images/yellow-left-arrow.png" alt="icon">
                        </a>
                    </div>
                </div>
            @endforeach
            <!--index blog item-->
{{--            <div class="index-blog-item">--}}
{{--                <div class="index-blog-item-image">--}}
{{--                    <img src="images/blog-image.png" alt="image">--}}
{{--                </div>--}}
{{--                <div class="index-blog-title">--}}
{{--                    <div class="index-blog-title-title">--}}
{{--                        <h6>--}}
{{--                            دوخت و طراحی--}}
{{--                        </h6>--}}
{{--                    </div>--}}
{{--                    <div class="index-blog-title-items">--}}
{{--                        <div class="index-blog-title-item">--}}
{{--                            <p>--}}
{{--                                122--}}
{{--                            </p>--}}
{{--                            <img src="images/Eye.png" alt="icon" width="20px" height="20px">--}}
{{--                        </div>--}}
{{--                        <div class="index-blog-title-item">--}}
{{--                            <p>--}}
{{--                                122--}}
{{--                            </p>--}}
{{--                            <img src="images/coment.png" alt="icon" width="20px" height="20px">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="index-blog-desc">--}}
{{--                    <h3>--}}
{{--                        چگونه لورم ایپسوم شویم و لورم ایپسوم بمانیم؟--}}
{{--                    </h3>--}}
{{--                    <p>--}}
{{--                        لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان...--}}
{{--                    </p>--}}
{{--                    <a>--}}
{{--                        مشاهده--}}
{{--                        <img src="images/yellow-left-arrow.png" alt="icon">--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </div>
</section>
