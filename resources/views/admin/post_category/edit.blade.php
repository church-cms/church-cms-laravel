@extends('layouts.admin.layout')

@section('content')
    <div class="">
        <h1 class="admin-h1 mb-5 flex items-center">
            <a href="{{ url('/admin/post-categories') }}" title="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Edit Post Category</span>
        </h1>
        @include('partials.message')
        <edit-post-category url="{{ url('/') }}" id="{{ $category->id }}"></edit-post-category>
    </div>
@endsection
