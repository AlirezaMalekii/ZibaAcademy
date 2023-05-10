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

@yield('content')

<!-- start footer -->
@include('sections.footer')
<!-- end footer -->
</body>
</html>
