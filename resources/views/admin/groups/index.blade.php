@extends('layouts.admin.layout')

@section('content')
    <div class="flex lg:items-center md:items-center justify-between flex-col lg:flex-row md:flex-row">
        <h1 class="admin-h1">Groups ({{ count($groups) }})</h1>
        <form method="GET" action="{{ url('/admin/groups') }}" role="search" enctype="multipart/form-data"
            class="mb-0">
            <div class="">
                <div class="flex lg:justify-end md:justify-end items-center">
                    <div class="search relative w-48">
                        <input type="text" name="search" class="tw-form-control w-full relative" placeholder="Search"
                            value="{{ \Request('search') }}">
                        <button class="no-underline text-white px-4 mx-1 py-1 absolute right-0 focus:outline-none">
                            <img src="{{ url('uploads/icons/search.svg') }}"
                                class="w-4 h-4 absolute right-0 mt-2 mx-1 top-0">
                        </button>
                    </div>
                    <div class="date-select date-select_none dashboard-reset mx-1 lg:mx-0 md:mx-0">
                        <a href="{{ url('/admin/groups') }}" id="do-reset"
                            class="text-sm border bg-gray-100 text-grey-darkest py-1 px-4">Reset</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @include('partials.message')
    <div class="flex flex-wrap">
        
        <div class="w-1/2 lg:w-1/6 md:w-1/2 px-2 my-5">
            <a href="{{ url('/admin/group/create') }}">
                <div class="border border-dashed border-gray-300 flex items-center h-40 bg-white">
                    <img src="{{ url('uploads/icons/add-gallery.svg') }}" class="w-8 h-8 mx-auto" id="add">
                </div>
            </a>
            <div>
                <a href="{{ url('/admin/group/create') }}" class="text-sm font-semibold my-2 text-gray-800">New Group</a>
            </div>
        </div>
    

        @foreach ($groups as $group)
            <div class="w-1/2 lg:w-1/5 md:w-1/2 px-2 my-5">
                <div class="shadow-md bg-white">
                    <a href="{{ url('/admin/group/show/' . $group->id) }}">
                        <img class="card-img-top h-48 lg:h-40 md:h-48 w-full" src="{{ $group->CoverImagePath }}"
                            alt="{{ $group->name }}" id="name">
                    </a>
                </div>
                <div class="leading-relaxed my-1">
                    <p class="text-sm font-semibold  text-gray-800 capitalize">
                        <a href="{{ url('/admin/group/show/' . $group->id) }}">{{ $group->name }}</a>
                    </p>
                    <p class="text-xs text-gray-700 flex items-center">
                        {{ \Str::limit($group->description, 50, '...') }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
@endsection
