@extends('layouts.admin.layout')

@section('content')
    <div class="relative">
        @include('partials._page_header', ['pageTitle' => 'Page Categories'])
        <div class="bg-white shadow my-3 p-4">
        @include('partials.message')
        <page-category-list url="{{ url('/') }}" mode="admin"></page-category-list>
        </div>
    </div>
@endsection
