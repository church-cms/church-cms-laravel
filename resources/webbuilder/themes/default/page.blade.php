@extends('theme::layout')

@section('title', $page->meta_title ?: $page->page_name)
@section('meta_description', $page->meta_description ?: Str::limit(strip_tags($page->description), 160))

@push('meta')
<meta name="ai_summary" content="{{ Str::limit(strip_tags($page->description), 300) }}">
@if($page->meta_keywords)<meta name="keywords" content="{{ $page->meta_keywords }}">@endif
@if($page->og_image)<meta property="og:image" content="{{ $page->og_image }}">@endif
@endpush

@section('content')
@php $layout = $page->layout_template ?? 'left-sidebar'; @endphp

{{-- Custom page CSS --}}
@if($page->content && !empty($page->content['css']))
<style>.page-content { {!! $page->content['css'] !!} }</style>
@endif

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    @if($layout === 'no-sidebar')
    {{-- ── NO SIDEBAR: full width ─────────────────────────────────── --}}
    <main class="w-full">
        @include('theme::_page_content', ['page' => $page])
    </main>

    @elseif($layout === 'right-sidebar')
    {{-- ── RIGHT SIDEBAR: content 3/4, nav 1/4 ───────────────────── --}}
    <div class="flex flex-col lg:flex-row gap-8">
        <main class="lg:w-3/4 min-w-0">
            @include('theme::_page_content', ['page' => $page])
        </main>
        @include('theme::_page_menu')
    </div>

    @else
    {{-- ── LEFT SIDEBAR (default): nav 1/4, content 3/4 ─────────── --}}
    <div class="flex flex-col lg:flex-row gap-8">
        @include('theme::_page_menu')
        <main class="lg:w-3/4 min-w-0">
            @include('theme::_page_content', ['page' => $page])
        </main>
    </div>
    @endif

</div>
@endsection
