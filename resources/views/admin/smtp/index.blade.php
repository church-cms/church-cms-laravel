@extends('layouts.admin.layout')

@section('content')
    <div class="relative">
        <div class="flex flex-wrap lg:flex-row justify-between">
            <div class="">
                <h1 class="admin-h1">Smtp</h1>
            </div>
            <div class="relative flex items-center">
                <!-- w-1/4 justify-end -->
                <div class="flex items-center">
                    <a href="{{ url('/admin/smtp/add') }}"
                        class="no-underline text-white px-4 mx-1 flex items-center custom-green py-1 justify-center rounded">
                        <span class="mx-1 text-sm font-semibold">Add</span>
                        <img src="{{ url('/uploads/icons/plus.svg') }}" class="w-3 h-3">
                    </a>
                </div>
            </div>
        </div>
        @include('partials.message')
        <smtp-list url="{{ url('/') }}" mode="admin"></smtp-list>
    </div>
@endsection
