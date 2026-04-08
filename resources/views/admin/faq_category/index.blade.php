@extends('layouts.admin.layout')

@section('content')
    <div class="relative">
        <div class="flex flex-wrap lg:flex-row justify-between">
            <div class="">
                <h1 class="admin-h1">FAQ Categories</h1>
            </div>
        </div>
        @include('partials.message')
        <div class="bg-white shadow my-3 p-4">
            <faq-category-list url="{{ url('/') }}"></faq-category-list>
        </div>
    </div>
@endsection
