@extends('master')
@section('head')
    @parent
    <link rel="stylesheet" href="/css/blog.css">
    <title>Document</title>
@endsection
@section('content')
    <!-- start blog artice-->
    <article class="blog-page py-5">


        <!--index top-right gradient-->
        <div class="right-gradient">
            <img src="/images/right-gradient.png" alt="gradient">
        </div>


        <section class="container">
            <div class="blog-header text-center mb-5">
                <h1>
                    وبلاگ
                </h1>
            </div>
            <div class="row flex-row-reverse">
                @include('sections.blog.sidebar')
                <div class="blog-page-left col-12 col-lg-9 mt-3 mt-lg-0" style="direction: rtl">
                    <!--blog item-->
                    @foreach($blogs as $blog)
                        <div class="index-blog-item">
                            <div class="index-blog-item-image">
                                <img src="{{$blog->files()->get()->first()->file['thumb']}}" alt="image">
                            </div>
                            <div class="index-blog-title" style="direction: ltr">
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
                                    {{ $blog->title}}
                                </h3>
                                <p>
{{--                                    {{substr_replace($blog->body,"...",10)}}--}}
                                    {{Illuminate\Support\Str::limit($blog->body, 100)}}
                                </p>
                                <a style="direction: ltr">
                                    مشاهده
                                    <img src="/images/yellow-left-arrow.png" alt="icon">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>


        <!-- index-page bottom-left gradient-->
        <div class="left-gradient">
            <img src="/images/left-gradient.png" alt="gradient">
        </div>
    </article>
    <!-- end blog article-->
@endsection
