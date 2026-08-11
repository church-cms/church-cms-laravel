@extends('layouts.admin.layout')

@section('content')
    <div class="relative">
        @include('partials._page_header', ['pageTitle' => 'FAQ Categories'])
        @include('partials.message')
        <div class="bg-white shadow my-3 p-4">
            <faq-category-list url="{{ url('/') }}"></faq-category-list>
        </div>
    </div>
@endsection
