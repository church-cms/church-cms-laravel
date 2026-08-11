@extends('layouts.admin.layout')

@section('content')
    <div class="relative">
        @include('partials._page_header', ['pageTitle' => 'Post Categories'])
        <div class="bg-white shadow my-3 p-4">
            @include('partials.message')
            <post-category-list url="{{ url('/') }}"></post-category-list>
        </div>
    </div>
@endsection
