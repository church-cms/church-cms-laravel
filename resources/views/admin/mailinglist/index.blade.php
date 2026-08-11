@extends('layouts.admin.layout')

@section('content')
    <div class="relative">
        <div class="flex flex-wrap lg:flex-row justify-between">
            <div class="">
                <h1 class="admin-h1">Mailing List</h1>
            </div>
            <div class="relative flex items-center">
                <!-- w-1/4 justify-end -->
                <div class="flex items-center">
                    <a href="{{ url('/admin/mailinglist/add') }}"
                        class="no-underline text-white px-4 mx-1 flex items-center custom-green py-1 justify-center rounded">
                        <span class="mx-1 text-sm font-semibold">Add</span>
                        <img src="{{ url('/uploads/icons/plus.svg') }}" class="w-3 h-3 fill-current text-white">
                    </a>
                </div>
            </div>
        </div>
        @include('partials.message')
        <list-mailinglist url="{{ url('/') }}" mode="admin"></list-mailinglist>
    </div>
@endsection
