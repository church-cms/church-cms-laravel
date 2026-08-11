@extends('layouts.admin.layout')

@section('content')
    <div class="relative">
        <div class="flex flex-wrap lg:flex-row justify-between">
            <div class="">
                <h1 class="admin-h1">Subscribers</h1>
            </div>
            <div class="flex items-center">
                <div class="relative flex items-center">
                    <div class="flex items-center">
                        <a href="{{ url('/admin/subscriber/add') }}"
                            class="no-underline text-white px-4 mx-1 flex items-center custom-green py-1 justify-center rounded">
                            <span class="mx-1 text-xs lg:text-sm md:text-sm font-semibold whitespace-no-wrap">Add</span>
                            <img src="{{ url('uploads/icons/plus.svg') }}" class="w-3 h-3">
                        </a>
                    </div>
                </div>

                <div class="relative flex items-center">
                    <div class="flex items-center">
                        <a href="{{ url('/admin/subscriber') }}"
                            class="no-underline text-white px-4 mx-1 flex items-center custom-green py-1 rounded">
                            <span class="mx-1 text-xs lg:text-sm md:text-sm font-semibold whitespace-no-wrap">Import</span>
                        </a>
                    </div>
                </div>

                <div class="relative flex items-center">
                    <div class="flex items-center">
                        <a href="{{ url('/admin/exportSubscribers/?' . $query) }}"
                            class="no-underline text-white  px-4 mx-1 flex items-center custom-green py-1 rounded">
                            <span class="mx-1 text-xs lg:text-sm md:text-sm font-semibold whitespace-no-wrap">Export</span>

                        </a>
                    </div>
                </div>
            </div>
        </div>
        @include('partials.message')
        <subscriber-list url="{{ url('/') }}" mode="admin"></subscriber-list>
    </div>
@endsection
