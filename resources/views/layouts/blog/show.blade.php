@extends('master')
@section('head')
    @parent
    <link rel="stylesheet" href="/css/inside-blog.css">

    <title>Document</title>
@endsection

<!-- start inside-blog article -->
@section('content')
    <article class="py-5 inside-blog">


        <!--index top-right gradient-->
        <div class="right-gradient">
            <img src="/images/right-gradient.png" alt="gradient">
        </div>


        <section class="inside-blog-1 container p-4 p-lg-5">
            <div class="row inside-blog-title d-flex flex-row-reverse align-items-center justify-content-between">
                <h3 class="text-right">
                    {{$data_blog['title']}}
                </h3>
                <p>
                    {{jdate($data_blog['created_at'])->format('%B %d، %Y')}}
                </p>
            </div>
            <div class="row inside-blog-info d-flex flex-row-reverse mt-3">
                <div class="inside-blog-info-item d-flex flex-row mr-2" style="align-items: baseline;">
                    <p>
                        دسته بندی:
                    @foreach($categories as $category)
                        {{--                        <p>--}}
                        {{$category." "}}
                        {{--                        </p>--}}
                    @endforeach
                    <p>

                </div>
                <div class="inside-blog-info-item d-flex flex-row-reverse mr-3">
                    <img src="/images/Eye.png" alt="icon">
                    <p class="mr-2">
                        {{$data_blog['viewCount']}} بازدید
                    </p>
                </div>
                <div class="inside-blog-info-item d-flex flex-row-reverse mr-3">
                    <img src="/images/coment.png" alt="icon">
                    <p class="mr-2">
                        {{count($comments)}} دیدگاه
                    </p>
                </div>
            </div>
            <div class="row inside-blog-first-desc d-flex flex-row-reverse mt-3">
                <div class="inside-blog-image col-12 col-lg-6">
                    <img src="{{$image}}" alt="image">
                </div>
                <div class="col-12 col-lg-6">
                    <p class="text-right">
                        {{$data_blog['description']}}
                    </p>
                </div>
            </div>
            <div class="row inside-blog-second-desc mt-3" style=" direction: rtl">
                {{--                <p class="text-right">--}}
                {{--                    {{$data_blog['body']}}--}}
                {{--                </p>--}}
                <div class="text-right" style="font-family: semi-bold">
                    {!! $data_blog['body'] !!}
                </div>
            </div>
        </section>


        <!-- comments section -->
        @if(!empty($comments))
            <section class="container mt-5 workshop-inside-comment p-5">
                <div class="row workshop-comments-title d-flex justify-content-end mb-4">
                    <h3>نظرات شرکت کنندگان</h3>
                </div>
                <ul class="comment-items post" id="comment-items">
                    <!-- comment item -->
                    @foreach($comments as $comment)
                        @if(empty($comment['comments']))
                            <li class="row mt-2 comment-item d-flex justify-content-between align-items-center">
                                <div class="comment-desc text-right" style="flex-basis: 80%">
                                    <div class="comment-name d-flex align-items-center">
                                        <div class="comment-name-image">
                                            <img src="/images/comment-image.png" alt="image">
                                        </div>
                                        <div class="comment-name-name">
                                            <h6>{{$comment['name']}}</h6>
                                        </div>
                                    </div>
                                    <div class="comment-desc-desc mt-1">
                                        <p>
                                            {{$comment['comment']}}
                                        </p>
                                    </div>
                                </div>
                                <div class="comment-date">
                                    <p>
                                        {{jdate($comment['created_at'])->format('%d %B %Y')}}
                                    </p>
                                </div>
                            </li>
                        @else
                            <li class="d-flex flex-column">
                                <div class="row mt-2 comment-item d-flex justify-content-between align-items-center">
                                    <div class="comment-desc text-right" style="flex-basis: 80%">
                                        <div class="comment-name d-flex align-items-center">
                                            <div class="comment-name-image">
                                                <img src="/images/comment-image.png" alt="image">
                                            </div>
                                            <div class="comment-name-name">
                                                <h6>{{$comment['name']}}</h6>
                                            </div>
                                        </div>
                                        <div class="comment-desc-desc mt-1">
                                            <p>
                                                {{$comment['comment']}}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="comment-date">
                                        <p>
                                            {{jdate($comment['created_at'])->format('%d %B %Y')}}
                                        </p>
                                    </div>
                                </div>
                                @foreach($comment['comments'] as $replyComment)
                                    <div class="row mt-2 comment-item d-flex justify-content-between align-items-center mr-4">
                                        <div class="comment-desc text-right">
                                            <div class="comment-name d-flex align-items-center">
                                                <div class="comment-name-image">
                                                    <img src="/images/comment-image.png" alt="image">
                                                </div>
                                                <div class="comment-name-name">
                                                    <h6>{{$replyComment['name']}}</h6>
                                                </div>
                                            </div>
                                            <div class="comment-desc-desc mt-1">
                                                <p>
                                                    {{$replyComment['comment']}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </li>
                        @endif
                    @endforeach

                </ul>
                <div class="row mt-4 comments-show d-flex justify-content-center align-items-center">
                    <button class="d-flex flex-row-reverse align-items-center load-more">
                        دیدن نظرات بیشتر
                        <img src="/images/To-Down.png" alt="icon">
                    </button>
                </div>
            </section>
        @endif

        <!-- write comment section-->
        <form method="post" action="{{route('blog_create_comment',[$data_blog['slug']])}}">
            @csrf
            <section class="container write-comment pr-5 py-4 mt-5 d-flex flex-column align-items-end">
                <div class="row d-flex justify-content-end">
                    <h3 class="write-comment-title">
                        ثبت دیدگاه:
                    </h3>
                </div>
                <div class="row write-comment-area mt-3">
            <textarea class="pr-4 pt-4" name="comment" rows="5" id="write-comment"
                      placeholder="دیدگاه خود را وارد کنید">
            </textarea>
                </div>
                <div class="row mt-3">
                    <button type="submit" class="record-button">
                        ثبت
                    </button>
                </div>
            </section>
        </form>

        <!-- start Similar content section-->
        @if($closeBlogs->first())
            <section class="similar-content container mt-5 py-4">
                <div class="similar-content-title">
                    <p class="text-right">
                        مطالب مشابه
                    </p>
                </div>
                <div class="blog-page-left mt-3">
                    <!--blog item-->
                    @foreach($closeBlogs as $closeBlog)
                        <div class="index-blog-item">
                            <div class="index-blog-item-image">
                                <img src="{{$closeBlog->files()->where('type','cover')->first()->file['thumb']}}" alt="image">
                            </div>
                            <div class="index-blog-title">
                                <div class="index-blog-title-title">
                                    <h6>
                                        @foreach($closeBlog->categories()->get() as $ctegory)
                                            {{--                        <p>--}}
                                            {{$ctegory->title." "}}
                                            {{--                        </p>--}}
                                        @endforeach
                                    </h6>
                                </div>
                                <div class="index-blog-title-items">
                                    <div class="index-blog-title-item">
                                        <p>
                                            {{$closeBlog->viewCount}}
                                        </p>
                                        <img src="/images/Eye.png" alt="icon" width="20px" height="20px">
                                    </div>
                                    <div class="index-blog-title-item">
                                        <p>
                                            {{count($closeBlog->comments()->where('approved', true)->where('parent_id', 0)->get())}}
                                        </p>
                                        <img src="/images/coment.png" alt="icon" width="20px" height="20px">
                                    </div>
                                </div>
                            </div>
                            <div class="index-blog-desc">
                                <h3>
                                    {{$closeBlog->title}}
                                </h3>
                                <p>
                                    {{Illuminate\Support\Str::limit($closeBlog->description, 100)}}
                                </p>
                                <a href="{{route('blog.show',['blog'=>$closeBlog->slug])}}">
                                    مشاهده
                                    <img src="/images/yellow-left-arrow.png" alt="icon">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
        <!-- end Similar content section-->


        <!-- index-page bottom-left gradient-->
        <div class="left-gradient">
            <img src="/images/left-gradient.png" alt="gradient">
        </div>
    </article>
@endsection
<!-- end inside-blog article-->
@section('script')
    <script>
        const loadmore = document.querySelector('.load-more');

        let currentItems = 3;
        loadmore.addEventListener('click', (e) => {
            const elementLlist = [...document.querySelectorAll('.post li')];

            for (let i = currentItems; i < currentItems + 3; i++) {
                console.log(elementLlist[i])
                if (elementLlist[i]) {
                    elementLlist[i].style = "display : flex !important";
                }
                console.log(elementLlist[i].style.display)
            }
            currentItems += 3;
        })
    </script>
@endsection
