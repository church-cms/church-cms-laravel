@extends('layouts.admin.layout')

@section('content')
    <div class="">
        <h1 class="admin-h1 mb-5 flex items-center">
            <a href="{{ url('/admin/faq-categories') }}" title="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Edit FAQ Category</span>
        </h1>
        @include('partials.message')
        <edit-faq-category url="{{ url('/') }}" id="{{ $category->id }}"></edit-faq-category>
    </div>
@endsection
