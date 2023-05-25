<header class="bg-navbar">
    <nav class="navbar navbar-expand-lg flex-row-reverse justify-content-between d-flex py-4 container">
        <div class="navbar-logo-brand d-flex">
            <a class="navbar-brand d-flex flex-row-reverse align-items-center" href="{{route('home')}}">
                <img src="/images/header-logo.png" class="ml-2" width="40px" height="40px">
                زیبا آکادمی
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon">
                    <img src="/images/navbar-toggle.png">
                </span>
            </button>
        </div>

        <div class="collapse navbar-collapse flex-row-reverse justify-content-center" id="navbarSupportedContent">
            <ul class="navbar-nav flex-column text-right flex-lg-row-reverse">
                <li class="nav-item mr-2 active">
                    <a class="nav-link" href="{{route('home')}}">صفحه اصلی</a>
                </li>
                <li class="nav-item mr-2">
                    <a class="nav-link" href="{{route('workshops')}}">ورگشاپ ها</a>
                </li>
                <li class="nav-item mr-2">
                    <a class="nav-link" href="{{route('blogs')}}">وبلاگ</a>
                </li>
                <li class="nav-item mr-2">
                    <a class="nav-link" href="#">درباره ما</a>
                </li>
            </ul>
        </div>

        <div class="navbar-regester align-items-center d-flex flex-row-reverse">
            <!-- <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" id="navbar-search-input">
                <img class="my-2 my-sm-0" onclick="navbarsearch()" src="images/search-icon.png" width="25px" height="25px" id="navbar-search-image">
            </form> -->
            <a href="#" class="ml-2">
                <img src="/images/search-normal.png" alt="icon" width="32px" height="32px">
            </a>
            @guest
            <a href="{{route('login')}}" class="my-2 my-sm-0 py-2 px-4 bg-white text-center" style="font-family:semi-bold;font-size: 14px; border: none;border-radius: 4px;color: #1B4E43; text-decoration:none;">ورود/ثبت نام</a>
            @endguest
            @auth
                <a href="{{route('user_panel')}}" class="my-2 my-sm-0 py-2 px-4 bg-white text-center" style="font-family:semi-bold;font-size: 14px; border: none;border-radius: 4px;color: #1B4E43; text-decoration:none;">پنل کاربری</a>
            @endauth
        </div>
    </nav>
</header>
