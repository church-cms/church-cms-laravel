@extends('theme::layout')

@section('title', $sermon->title)
@section('meta_description', Str::limit(strip_tags($sermon->description), 160))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    @if($sermon->cover_image)
        <img src="{{ $sermon->cover_image_path }}" alt="{{ $sermon->title }}" class="w-full h-64 object-cover rounded-lg mb-8">
    @endif

    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $sermon->title }}</h1>
    <p class="text-sm text-gray-400 mb-6">
        By {{ $sermon->user->name ?? 'Unknown' }} &mdash; {{ $sermon->created_at->format('d M Y') }}
    </p>

    @if($sermon->description)
    <div class="prose max-w-none text-gray-700 mb-8">
        {!! $sermon->description !!}
    </div>
    @endif

    {{-- Media Links --}}
    @if($sermon->sermonlinks->count())
    <div class="bg-gray-50 rounded-lg p-5">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Media</h2>
        <div class="flex flex-wrap gap-3">
            @foreach($sermon->sermonlinks as $link)
            <a href="{{ $link->url }}"
               target="_blank"
               rel="noopener noreferrer"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 transition">
                @switch(strtolower($link->type))
                    @case('audio') &#127911; @break
                    @case('video') &#9654; @break
                    @default &#128279; @break
                @endswitch
                {{ $link->type }}
                @if($link->date)
                    <span class="text-indigo-200 text-xs">&mdash; {{ \Carbon\Carbon::parse($link->date)->format('d M Y') }}</span>
                @endif
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <div class="mt-10">
        <a href="{{ route('web.sermons') }}" class="text-indigo-600 hover:underline text-sm">&larr; Back to Sermons</a>
    </div>
</div>
@endsection
