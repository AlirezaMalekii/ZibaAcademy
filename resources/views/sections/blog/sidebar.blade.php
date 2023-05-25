<div class="blog-page-right col-12 col-lg-3">
    <div class="blog-page-right-search text-right p-4">
        <h3>
            جستجو
        </h3>
        <form class="blog-page-input mt-3" action="{{ route('blog.search') }}" method="GET">
            <input placeholder="جستجو" type="text" name="search" required>
            <img src="/images/blog-search.png" alt="icon">
        </form>
    </div>
    <div class="blog-page-right-list text-right mt-3 p-4">
        <h3>
            فهرست مطالب
        </h3>
        <div class="blog-page-right-items mt-3">
            @foreach($blog_title as $blog)
                <a href="{{route('blog.show',$blog->slug)}}" class="mt-2">
                    {{$blog->title}}
                </a>
            @endforeach
        </div>
    </div>
</div>
