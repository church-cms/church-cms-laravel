@extends('theme::layout')

@section('title', $bulletin->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Back link --}}
    <div class="mb-6">
        <a href="{{ route('web.bulletins') }}" class="text-indigo-600 hover:underline text-sm">&larr; Back to Bulletins</a>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">

        {{-- Cover image --}}
        @if($bulletin->cover_image)
        <div class="w-full h-56 bg-gray-100 overflow-hidden">
            <img src="{{ $bulletin->coverImagePath }}"
                 alt="{{ $bulletin->name }}"
                 class="w-full h-full object-cover">
        </div>
        @else
        <div class="w-full h-32 bg-indigo-50 flex items-center justify-center text-7xl text-indigo-200">
            &#128196;
        </div>
        @endif

        <div class="p-6 sm:p-8">

            {{-- Type badge --}}
            <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full mb-4
                {{ $bulletin->type === 'week' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                {{ $bulletin->type === 'week' ? 'Weekly Bulletin' : 'Monthly Bulletin' }}
            </span>

            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">{{ $bulletin->name }}</h1>

            {{-- Period --}}
            <p class="text-gray-500 text-sm mb-6">
                @if($bulletin->type === 'week' && $bulletin->week)
                    Week {{ $bulletin->week }} &mdash; {{ $bulletin->year }}
                @elseif($bulletin->type === 'month' && $bulletin->month)
                    {{ \Carbon\Carbon::create($bulletin->year, $bulletin->month)->format('F Y') }}
                @else
                    {{ $bulletin->year }}
                @endif
            </p>

            {{-- Download / View button --}}
            @if($bulletin->path)
            <div class="flex flex-wrap gap-3">
                <a href="{{ $bulletin->filePath }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7
                                 -1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    View Bulletin
                </a>

                <a href="{{ $bulletin->filePath }}"
                   download
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1
                                 m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download
                </a>
            </div>
            @else
            <p class="text-sm text-gray-400 italic">No file attached to this bulletin.</p>
            @endif

        </div>
    </div>

    {{-- Related bulletins --}}
    @if($related->count())
    <div class="mt-12">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">More from {{ $bulletin->year }}</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($related as $item)
            <a href="{{ route('web.bulletin.show', $item->id) }}"
               class="flex items-center gap-4 bg-white rounded-lg shadow-sm hover:shadow-md p-4 transition group">

                <div class="w-12 h-12 shrink-0 rounded-lg bg-indigo-50 flex items-center justify-center text-2xl text-indigo-300 overflow-hidden">
                    @if($item->cover_image)
                        <img src="{{ $item->coverImagePath }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                    @else
                        &#128196;
                    @endif
                </div>

                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-800 line-clamp-1 group-hover:text-indigo-600 transition">
                        {{ $item->name }}
                    </p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        @if($item->type === 'week' && $item->week)
                            Week {{ $item->week }}, {{ $item->year }}
                        @elseif($item->type === 'month' && $item->month)
                            {{ \Carbon\Carbon::create($item->year, $item->month)->format('F Y') }}
                        @else
                            {{ $item->year }}
                        @endif
                    </p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
