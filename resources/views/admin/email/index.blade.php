@extends('layouts.admin.layout')

@section('content')
    <div class="relative">
        <div class="flex flex-wrap lg:flex-row justify-between">
            <div class="">
                <h1 class="admin-h1">Email</h1>
            </div>
            <div class="relative flex items-center ">
                <!-- w-1/4 justify-end -->
                <div class="flex items-center">
                    <a href="{{ url('/admin/email/add') }}"
                        class="no-underline text-white px-4  mx-1 flex items-center custom-green py-1 justify-center rounded">
                        <span class="mx-1 text-sm font-semibold">Add</span>
                        <img src="{{ url('uploads/icons/plus.svg') }}" class="w-3 h-3">
                    </a>
                </div>
            </div>
        </div>
        @include('partials.message')
        <div class="bg-white shadow p-2 my-3">
            <email-list url="{{ url('/') }}" mode="admin"></email-list>
        </div>
    </div>
@endsection
