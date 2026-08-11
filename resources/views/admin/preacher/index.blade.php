@extends('layouts.admin.layout')

@section('content')
    <div class="relative">
        <div class="flex flex-wrap lg:flex-row justify-between">
            <portal-target name="preacher_count"></portal-target>
            <!-- <div class="">
                    <h1 class="admin-h1">Preachers ( {{ $count }} )</h1>
                </div> -->
            <div class="w-full lg:w-2/4">
                <portal-target name="search"></portal-target>
                <portal-target name="memberfilter"></portal-target>
            </div>
            <div class="relative flex items-center w-1/4 lg:justify-end">
                <div class="flex items-center">
                    <a href="{{ url('/admin/preacher/add/') }}"
                        class="no-underline text-white  px-4 mx-1 flex items-center custom-green py-1 justify-center rounded">
                        <span class="mx-1 text-sm font-semibold" dusk="add-button">Add</span>
                        <img src="{{ url('uploads/icons/plus.svg') }}" class="w-3 h-3">
                    </a>
                </div>
            </div>
        </div>
        @include('partials.message')
        <preacher-list url="{{ url('/') }}" searchquery="{{ $query }}" letter="{{ $alphabet }}">
        </preacher-list>
        <search-preacher url="{{ url('/') }}" searchquery="{{ $query }}"></search-preacher>
    </div>
@endsection
