@extends('master')
@section('head')
    @parent
    <link rel="stylesheet" href="/css/workshops.css">
    <title>Document</title>
@endsection

@section('content')
    <article class="workshops-page" style="overflow: hidden">

        <!--workshops-page top-right gradient-->
        <div class="right-gradient">
            <img src="/images/right-gradient.png" alt="gradient">
        </div>

        <!-- start workshop items section-->
        @include('sections.workshop.workshops.ongoing-workshops',compact('ongoing_workshops'))
        <!-- end workshop items section-->


        <!-- start workshop items section-->
        @include('sections.workshop.workshops.held-workshops',compact('held_workshops'))
        <!-- end workshop items section-->


        <!--workshops-page bottom-left gradient-->
        <div class="left-gradient">
            <img src="/images/left-gradient.png" alt="gradient">
        </div>
    </article>
@endsection
