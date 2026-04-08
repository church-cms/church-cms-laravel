@extends('theme::layout')

@section('title', $gallery->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="mb-6">
        <a href="{{ route('web.gallery') }}" class="text-indigo-600 hover:underline text-sm">&larr; Back to Gallery</a>
    </div>

    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $gallery->name }}</h1>
    @if($gallery->description)
        <p class="text-gray-500 mb-8">{{ $gallery->description }}</p>
    @endif

    @if($gallery->photos->count())
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($gallery->photos as $photo)
        <div class="overflow-hidden rounded-lg bg-gray-100">
            <img
                src="{{ $photo->FullPath }}"
                data-src="{{ $photo->FullPath }}"
                alt="{{ $gallery->name }}"
                loading="lazy"
                class="w-full h-48 object-cover hover:scale-105 transition-transform cursor-pointer">
        </div>
        @endforeach
    </div>
    @else
        <p class="text-gray-500">No photos in this album.</p>
    @endif

</div>
@endsection
