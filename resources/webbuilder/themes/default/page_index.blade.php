@extends('theme::layout')

@section('title', $activePage ? ($activePage->meta_title ?: $activePage->page_name) : 'Pages')

@push('meta')
@if($activePage)
<meta name="ai_summary" content="{{ Str::limit(strip_tags($activePage->description), 300) }}">
@if($activePage->meta_keywords)<meta name="keywords" content="{{ $activePage->meta_keywords }}">@endif
@if($activePage->og_image)<meta property="og:image" content="{{ $activePage->og_image }}">@endif
@endif
@endpush

@section('content')
@php $layout = $activePage->layout_template ?? 'left-sidebar'; @endphp

{{-- Custom page CSS --}}
@if($activePage && $activePage->content && !empty($activePage->content['css']))
<style>.page-content { {!! $activePage->content['css'] !!} }</style>
@endif

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    @if($layout === 'no-sidebar')
    <main class="w-full">
        @include('theme::_page_content', ['page' => $activePage])
    </main>

    @elseif($layout === 'right-sidebar')
    <div class="flex flex-col lg:flex-row gap-8">
        <main class="lg:w-3/4 min-w-0">
            @include('theme::_page_content', ['page' => $activePage])
        </main>
        @include('theme::_page_menu')
    </div>

    @else
    <div class="flex flex-col lg:flex-row gap-8">
        @include('theme::_page_menu')
        <main class="lg:w-3/4 min-w-0">
            @if($activePage)
                @include('theme::_page_content', ['page' => $activePage])
            @else
                <div class="flex items-center justify-center h-64 text-gray-400">
                    <p>Select a page from the menu.</p>
                </div>
            @endif
        </main>
    </div>
    @endif

</div>
@endsection
