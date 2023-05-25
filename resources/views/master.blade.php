<!DOCTYPE html>
<html lang="en">
<head>
    @section('head')
        @include('sections.head')
    @show
</head>
<body>
<!-- start navbar section -->
@include('sections.header')
<!-- end navbar section -->
@include('sweetalert::alert')
{{--<div style="overflow-y: hidden">--}}
@yield('content')
{{--</div>--}}
<!-- start footer -->
@include('sections.footer')
<!-- end footer -->
@yield('script')
</body>
</html>
