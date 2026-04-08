@extends('theme::layout')

@section('title', $page->meta_title ?: $page->page_name)
@section('meta_description', $page->meta_description ?: Str::limit(strip_tags($page->description), 160))

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex flex-col lg:flex-row gap-8">

        {{-- ── LEFT NAV 1/4 ──────────────────────────────────────────── --}}
        @include('theme::_page_menu')

        {{-- ── RIGHT CONTENT 3/4 ─────────────────────────────────────── --}}
        <main class="lg:w-3/4 min-w-0">

            @if($page->cover_image)
            <img src="{{ \Storage::url($page->cover_image) }}"
                 alt="{{ $page->page_name }}"
                 class="w-full h-56 object-cover rounded-lg mb-6">
            @endif

            <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ $page->page_name }}</h1>

            <div class="prose max-w-none text-gray-700 leading-relaxed">
                {!! $page->description !!}
            </div>

        </main>

    </div>
</div>
@endsection
