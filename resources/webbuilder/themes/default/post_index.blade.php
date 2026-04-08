@extends('theme::layout')

@section('title', 'Blog')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Blog</h1>
        @if($activeCategoryId && ($activeCategory = $categories->firstWhere('id', $activeCategoryId)))
        <p class="mt-1 text-sm text-gray-500">Showing posts in <span class="font-medium text-indigo-600">{{ $activeCategory->name }}</span>
            <a href="{{ route('web.posts') }}" class="ml-2 text-gray-400 hover:text-gray-600 text-xs">&times; Clear</a>
        </p>
        @elseif($activeTag)
        <p class="mt-1 text-sm text-gray-500">Tagged: <span class="font-medium text-indigo-600">#{{ $activeTag }}</span>
            <a href="{{ route('web.posts') }}" class="ml-2 text-gray-400 hover:text-gray-600 text-xs">&times; Clear</a>
        </p>
        @endif
    </div>

    <div class="flex flex-col lg:flex-row gap-10">

        {{-- Sidebar --}}
        <aside class="lg:w-64 flex-shrink-0">
            <div class="bg-white rounded-lg shadow p-5 sticky top-6">
                <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">Categories</h2>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('web.posts') }}"
                           class="flex items-center justify-between px-3 py-2 rounded-md text-sm transition
                                  {{ ! $activeCategoryId ? 'bg-indigo-600 text-white font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                            <span>All Posts</span>
                            <span class="text-xs {{ ! $activeCategoryId ? 'text-indigo-200' : 'text-gray-400' }}">
                                {{ $categories->sum('posts_count') }}
                            </span>
                        </a>
                    </li>
                    @foreach($categories as $cat)
                    <li>
                        <a href="{{ route('web.posts', ['category' => $cat->id]) }}"
                           class="flex items-center justify-between px-3 py-2 rounded-md text-sm transition
                                  {{ $activeCategoryId == $cat->id ? 'bg-indigo-600 text-white font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                            <span>{{ $cat->name }}</span>
                            <span class="text-xs {{ $activeCategoryId == $cat->id ? 'text-indigo-200' : 'text-gray-400' }}">
                                {{ $cat->posts_count }}
                            </span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        {{-- Post List --}}
        <main class="flex-1 min-w-0">
            <div class="space-y-4">
                @forelse($posts as $post)
                <a href="{{ route('web.post', $post->id) }}"
                   class="block bg-white rounded-lg shadow hover:shadow-md transition group">
                    <div class="p-5">
                        {{-- Meta row --}}
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs text-gray-400">
                                {{ \Carbon\Carbon::parse($post->post_created_at)->format('d M Y') }}
                            </span>
                            @if($post->category)
                            <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-600 font-medium">
                                {{ $post->category->name }}
                            </span>
                            @endif
                        </div>

                        {{-- Title --}}
                        <h2 class="text-base font-semibold text-gray-800 group-hover:text-indigo-600 transition leading-snug mb-1.5">
                            {{ $post->title }}
                        </h2>

                        {{-- Excerpt --}}
                        <p class="text-sm text-gray-500 line-clamp-2">
                            {{ Str::limit(strip_tags($post->description), 200) }}
                        </p>

                        {{-- Tags + Read more --}}
                        <div class="flex flex-wrap items-center justify-between gap-2 mt-3">
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($post->tags as $tag)
                                <span onclick="event.preventDefault(); window.location='{{ route('web.posts', ['tag' => $tag->tag_name]) }}'"
                                   class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 cursor-pointer transition">
                                    #{{ $tag->tag_name }}
                                </span>
                                @endforeach
                            </div>
                            <span class="text-indigo-600 text-xs font-medium whitespace-nowrap">Read more &rarr;</span>
                        </div>
                    </div>
                </a>
                @empty
                <div class="bg-white rounded-lg shadow p-8 text-center text-gray-400">
                    No posts found.
                </div>
                @endforelse
            </div>

            @if($posts->hasPages())
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
            @endif
        </main>

    </div>
</div>
@endsection
