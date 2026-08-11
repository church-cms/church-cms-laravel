@extends('layouts.admin.layout')

@section('content')
    <div>


        @if ($gallery!=null)
            <h1 class="admin-h1 flex items-center">
                <a href="{{ url('/admin/gallery') }}" title="Back" class="rounded-full bg-gray-100 p-2">
                    <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
                </a>
                <span class="mx-3">{{ $gallery->name }}</span>
            </h1>
    </div>

    <div class="w-full lg:w-1/3 px-2 my-3">
        <showgallery url="{{ url('/') }}" gallery_id="{{ $gallery->id }}" name="{{ $gallery->name }}"></showgallery>
    </div>
    @endif
@endsection
