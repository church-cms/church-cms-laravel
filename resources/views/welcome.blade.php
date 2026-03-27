@extends('layouts.welcome')
{{-- @extends('layouts.minimal') --}}

@section('meta-contents')
    <title>ChurchCMS</title>
    <meta name="description"
        content="Easy-to-use web-based Church Management Software with companion Church Mobile App and Cloud Ready Live Streaming tools. 60 Day Free Trial. No Setup Fee.">
    <meta property="og:url" content="https://churchcms.app" />
    <meta property="og:title" content="Web Based Church Management Software with a Mobile App" />
    <meta property="og:description"
        content="Take your church to the People. Easy-to-use web-based Church Management Software with companion Church Mobile App and Cloud Ready Live Streaming tools. 60 Day Free Trial. No Setup Fee." />
    <meta property="og:image" content="https://churchcms.app/images/churchcms_twitter_card.jpg" />
@endsection

@section('content')
    @include('welcome.homebanner')
    <div class="bg-white">
       
    </div>

@endsection
{{-- @section('content')
    <div class=" min-h-screen">
        @include('layouts.partials.home_navigation')
        <div class="container mx-auto mt-16 p-8 bg-white shadow-md">
            <h1 class="text-4xl font-bold text-center my-16">ChurchCMS - Development Branch as of <br />March 5, 2021 10:19
                PM</h1>

        </div>
    </div>
@endsection --}}
