@extends('theme::layout')

@section('title', 'Gallery')

@section('content')
@include('theme::_hero_banner', [
    'heroTitle'    => 'Gallery',
    'heroSubtitle' => 'A glimpse into our community life and celebrations',
    'breadcrumbs'  => [
        ['label' => 'Home', 'url' => route('web.home')],
        ['label' => 'Gallery'],
    ],
])
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($galleries as $album)
        <a href="{{ route('web.gallery.show', $album->id) }}" class="block bg-white rounded-lg shadow hover:shadow-md overflow-hidden transition">
            <div class="w-full h-44 bg-gray-100 flex items-center justify-center text-gray-300 text-5xl">
                &#128247;
            </div>
            <div class="p-4">
                <h2 class="font-semibold text-gray-800">{{ $album->name }}</h2>
                @if($album->description)
                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $album->description }}</p>
                @endif
                <p class="text-xs text-indigo-500 mt-2">{{ $album->photos_count }} {{ Str::plural('photo', $album->photos_count) }}</p>
            </div>
        </a>
        @empty
        <p class="text-gray-500 col-span-3">No albums found.</p>
        @endforelse
    </div>

    <div class="mt-10">
        {{ $galleries->links() }}
    </div>
</div>
@endsection
