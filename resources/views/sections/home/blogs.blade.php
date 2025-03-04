<section class="index-blog py-5 mt-5">
    <div class="index-blog-wrapper">
        <div class="index-center-title text-center d-flex align-items-center justify-content-center mb-5">
            <img src="/images/title-vector-right.png">
            <h2>
                وبلاگ
            </h2>
            <img src="/images/title-vector-left.png">
        </div>
        <div class="index-blog-items" style="flex-direction: row-reverse">
            <!--index blog item-->
            @foreach($blogs as $blog)
                {{--                <div class="index-blog-item" style="max-width: 250px">--}}
                <div class="index-blog-item d-flex flex-column justify-content-between" style="max-width: 250px">
                    <div>
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
                    </div>
                    <div class="index-blog-desc">
                        <h3>
                            {{$blog->title}}
                        </h3>
                        <p>
                            {{Illuminate\Support\Str::limit($blog->description, 100)}}
                        </p>
                        <a href="{{route('blog.show',['blog'=>$blog->slug])}}">
                            مشاهده
                            <img src="/images/yellow-left-arrow.png" alt="icon">
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
