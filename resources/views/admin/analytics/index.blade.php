@extends('layouts.admin.layout')
@section('content')
    @include('partials._page_header', ['pageTitle' => 'Google Analytics'])
    <!-- start -->
    <div class="flex flex-col lg:flex-row my-4">
        <div class="w-full">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @include('admin.analytics._partials.__most_visited')
                @include('admin.analytics._partials.__visitor_views')
                @include('admin.analytics._partials.__referrer_views')
                @include('admin.analytics._partials.__user_type_views')
                @include('admin.analytics._partials.__browser_views')
            </div>
        </div>
    </div>
    <!-- end -->
@endsection
