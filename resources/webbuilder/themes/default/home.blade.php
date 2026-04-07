@extends('theme::layout')

@section('title', $_church->name ?? config('app.name'))

@section('content')

    {{-- Hero --}}
    <section class="bg-indigo-700 text-white py-24 px-4 text-center">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-4xl font-bold leading-tight">{{ $_church->name ?? config('app.name') }}</h1>
            @if($_church && $_church->quotes)
                <p class="mt-4 text-lg text-indigo-200">{{ $_church->quotes }}</p>
            @endif
        </div>
    </section>

    {{-- Recent Posts --}}
    @if($recentPosts->count())
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h2 class="text-2xl font-bold text-gray-800 mb-8">Latest Posts</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($recentPosts as $post)
            <a href="{{ route('web.post', $post->id) }}" class="block bg-white rounded-lg shadow hover:shadow-md transition overflow-hidden">
                <div class="p-5">
                    <p class="text-xs text-gray-400 mb-1">{{ \Carbon\Carbon::parse($post->post_created_at)->format('d M Y') }}</p>
                    <h3 class="font-semibold text-gray-800 text-base leading-snug">{{ $post->title }}</h3>
                    <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ Str::limit(strip_tags($post->description), 100) }}</p>
                </div>
            </a>
            @endforeach
        </div>
        <div class="mt-8 text-center">
            <a href="{{ route('web.posts') }}" class="inline-block px-6 py-2 border border-indigo-600 text-indigo-600 rounded-md text-sm hover:bg-indigo-50">View all posts</a>
        </div>
    </section>
    @endif

    {{-- Featured Pages --}}
    @if($featuredPages->count())
    <section class="bg-gray-100 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-8">Pages</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach($featuredPages as $page)
                <a href="{{ route('web.page', [optional($page->pageCategory)->slug ?? 'general', $page->slug]) }}" class="block bg-white rounded-lg shadow hover:shadow-md overflow-hidden transition">
                    @if($page->cover_image)
                        <img src="{{ \Storage::url($page->cover_image) }}" alt="{{ $page->page_name }}" class="w-full h-40 object-cover">
                    @endif
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800">{{ $page->page_name }}</h3>
                        <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ Str::limit(strip_tags($page->description), 80) }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

@endsection
