@extends('theme::layout')

@section('title', $sermon->title)
@section('meta_description', Str::limit(strip_tags($sermon->description), 160))

@section('content')

{{-- ─── SECTION 1: Hero ──────────────────────────────────────────────────── --}}
<section class="bg-gradient-to-r from-blue-900 to-blue-700 text-white py-16">
    <div class="container mx-auto px-4">
        <nav class="text-sm mb-4 opacity-80">
            <a href="{{ route('web.sermons') }}" class="hover:underline">Sermons</a>
            <span class="mx-2">/</span>
            <span>{{ $sermon->title }}</span>
        </nav>
        <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $sermon->title }}</h1>
        @if($sermon->description)
        <p class="text-xl opacity-90 max-w-3xl">{{ Str::limit(strip_tags($sermon->description), 200) }}</p>
        @endif
        <div class="flex flex-wrap gap-6 mt-6 text-sm opacity-90">
            @if($sermon->sermonlinks->count())
            @php
            $dates = $sermon->sermonlinks->pluck('date')->filter()->sort();
            $first = $dates->first() ? \Carbon\Carbon::parse($dates->first())->format('M Y') : null;
            $last = $dates->last() ? \Carbon\Carbon::parse($dates->last())->format('M Y') : null;
            @endphp
            @if($first)
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>{{ $first }}{{ $last && $last !== $first ? ' – ' . $last : '' }}</span>
            </div>
            @endif
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                <span>{{ $sermon->sermonlinks->count() }} {{ Str::plural('Chapter', $sermon->sermonlinks->count()) }}</span>
            </div>
            @endif
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <span>{{ $sermon->sermonlikevote }} Likes</span>
            </div>
        </div>
    </div>
</section>

{{-- ─── SECTION 2: Two-column layout ────────────────────────────────────── --}}
<section class="container mx-auto px-4 py-12">
    <div class="flex flex-col lg:flex-row gap-8">

        {{-- LEFT: Sidebar --}}
        <div class="lg:w-1/4">
            <div class="sticky top-4">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">

                    {{-- Cover Image --}}
                    @if($sermon->cover_image)
                    <img src="{{ $sermon->cover_image_path }}" alt="{{ $sermon->title }}" class="w-full h-56 object-cover">
                    @else
                    <div class="w-full h-56 bg-gradient-to-br from-blue-800 to-blue-500 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    @endif

                    <div class="p-4">
                        {{-- Author --}}
                        <div class="flex items-center gap-3 mb-4">
                            @php $avatar = $sermon->user?->userprofile?->avatar_path; @endphp
                            @if($avatar)
                            <img src="{{ $avatar }}" alt="{{ $sermon->user->name }}" class="w-12 h-12 rounded-full object-cover">
                            @else
                            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-lg">
                                {{ strtoupper(substr($sermon->user?->name ?? 'U', 0, 1)) }}
                            </div>
                            @endif
                            <div>
                                <p class="font-semibold text-gray-900">{{ $sermon->user?->name ?? 'Unknown' }}</p>
                                <p class="text-sm text-gray-500">{{ $sermon->user?->userprofile?->profession ?? 'Pastor' }}</p>
                            </div>
                        </div>

                        {{-- Like / Share --}}
                        <div class="flex gap-2 mb-4">
                            <div class="flex-1 flex items-center justify-center gap-2 px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-600">
                                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                {{ $sermon->sermonlikevote }}
                            </div>
                            <div class="flex-1 flex items-center justify-center gap-2 px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-600">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                </svg>
                                Share
                            </div>
                        </div>

                        {{-- Share Buttons --}}
                        <div class="border-t pt-3">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Share</p>
                            <div class="flex gap-2">
                                @php $shareUrl = urlencode(request()->url()); $shareTitle = urlencode($sermon->title); @endphp
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener"
                                    class="flex-1 p-2 bg-blue-600 text-white rounded text-center hover:bg-blue-700 transition">
                                    <svg class="w-4 h-4 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" />
                                    </svg>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}"
                                    target="_blank"
                                    rel="noopener"
                                    class="flex-1 p-2 text-white rounded text-center transition"
                                    style="background-color:#0ea5e9;"
                                    onmouseover="this.style.backgroundColor='#0284c7'"
                                    onmouseout="this.style.backgroundColor='#0ea5e9'">
                                    <svg class="w-4 h-4 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z" />
                                    </svg>
                                </a>
                                <a href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}" target="_blank" rel="noopener"
                                    class="flex-1 p-2 bg-green-600 text-white rounded text-center hover:bg-green-700 transition">
                                    <svg class="w-4 h-4 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                                        <path d="M11.5 2C6.253 2 2 6.253 2 11.5c0 1.89.538 3.652 1.47 5.143L2 22l5.5-1.45A9.454 9.454 0 0011.5 21C16.747 21 21 16.747 21 11.5S16.747 2 11.5 2z" />
                                    </svg>
                                </a>
                                <button onclick="navigator.clipboard.writeText(window.location.href)"
                                    class="flex-1 p-2 bg-gray-500 text-white rounded text-center hover:bg-gray-600 transition" title="Copy link">
                                    <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Quick Stats --}}
                        <div class="border-t mt-4 pt-4 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Chapters:</span>
                                <span class="font-semibold text-gray-800">{{ $sermon->sermonlinks->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Likes:</span>
                                <span class="font-semibold text-gray-800">{{ $sermon->sermonlikevote }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Added:</span>
                                <span class="font-semibold text-gray-800">{{ $sermon->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('web.sermons') }}" class="text-blue-600 hover:underline text-sm">&larr; Back to Sermons</a>
                </div>
            </div>
        </div>

        {{-- RIGHT: Expandable Chapters --}}
        <div class="lg:w-3/4">
            @if($sermon->description)
            <div class="prose max-w-none text-gray-700 mb-6 bg-white rounded-lg shadow-sm p-5">
                {!! $sermon->description !!}
            </div>
            @endif

            @if($sermon->sermonlinks->count())
            <div class="space-y-3">
                @foreach($sermon->sermonlinks as $i => $link)
                <div class="bg-white rounded-lg shadow-md overflow-hidden"
                    x-data="{ open: {{ $i === 0 ? 'true' : 'false' }} }">

                    {{-- Chapter Header --}}
                    <button @click="open = !open"
                        class="w-full p-5 flex items-center justify-between hover:bg-gray-50 transition text-left">
                        <div class="flex items-start gap-4 flex-1">
                            <span class="bg-blue-100 text-blue-700 font-bold px-3 py-1 rounded text-sm shrink-0">
                                Ch {{ $i + 1 }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base font-bold text-gray-900 mb-1">{{ $link->title }}</h3>
                                <div class="flex flex-wrap gap-4 text-xs text-gray-500">
                                    @if($link->date)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($link->date)->format('d M Y') }}
                                    </span>
                                    @endif
                                    @if($link->video_link)
                                    <span class="flex items-center gap-1 text-blue-500">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z" />
                                        </svg>
                                        Video
                                    </span>
                                    @endif
                                    @if($link->audio_link)
                                    <span class="flex items-center gap-1 text-green-500">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                        </svg>
                                        Audio
                                    </span>
                                    @endif
                                    @if($link->pdf_link)
                                    <span class="flex items-center gap-1 text-red-500">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        PDF
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 shrink-0 ml-3 transition-transform duration-200"
                            :class="{ 'rotate-180': open }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    {{-- Chapter Content --}}
                    <div x-show="open" x-collapse class="border-t">
                        <div class="p-5 space-y-4">

                            {{-- Video --}}
                            @if($link->video_link)
                            <div class="rounded-lg overflow-hidden bg-gray-50 border">
                                @php
                                $videoUrl = $link->video_link;
                                $embedUrl = null;
                                if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $videoUrl, $m)) {
                                $embedUrl = 'https://www.youtube.com/embed/' . $m[1];
                                } elseif (preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $m)) {
                                $embedUrl = 'https://player.vimeo.com/video/' . $m[1];
                                }
                                @endphp
                                @if($embedUrl)
                                <div class="aspect-video">
                                    <iframe src="{{ $embedUrl }}" class="w-full h-full"
                                        frameborder="0" allowfullscreen
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                                    </iframe>
                                </div>
                                @else
                                <div class="p-4 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z" />
                                            </svg>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-700">Watch Video</span>
                                    </div>
                                    <a href="{{ $videoUrl }}" target="_blank" rel="noopener"
                                        class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                        Open &rarr;
                                    </a>
                                </div>
                                @endif
                            </div>
                            @endif

                            {{-- Audio --}}
                            @if($link->audio_link)
                            <div class="bg-gray-50 rounded-lg p-4 border flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-700">Audio Version</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $link->audio_link }}</p>
                                    </div>
                                </div>
                                <a href="{{ $link->audio_link }}" target="_blank" rel="noopener"
                                    class="shrink-0 px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                    Listen
                                </a>
                            </div>
                            @endif

                            {{-- PDF --}}
                            @if($link->pdf_link)
                            <div class="bg-gray-50 rounded-lg p-4 border flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-red-100 rounded flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700">Sermon Notes &amp; Outline</p>
                                        <p class="text-xs text-gray-500">PDF / Document</p>
                                    </div>
                                </div>
                                <a href="{{ $link->getFilePath($link->pdf_link) }}" target="_blank" rel="noopener"
                                    class="shrink-0 px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Download
                                </a>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-white rounded-lg shadow-md p-10 text-center text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-sm">No chapters have been added to this sermon yet.</p>
            </div>
            @endif
        </div>

    </div>
</section>

{{-- ─── SECTION 3: Recommended Sermons ────────────────────────────────────── --}}
@if($recommended->count())
<section class="bg-white border-t py-12">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">More from this Church</h2>
            <a href="{{ route('web.sermons') }}" class="text-blue-600 hover:text-blue-700 text-sm font-semibold">View All &rarr;</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($recommended as $rec)
            <a href="{{ route('web.sermon', $rec->id) }}"
                class="bg-white rounded-lg overflow-hidden border hover:shadow-lg transition block group">
                @if($rec->cover_image)
                <img src="{{ $rec->cover_image_path }}" alt="{{ $rec->title }}" class="w-full h-40 object-cover group-hover:opacity-90 transition">
                @else
                <div class="w-full h-40 bg-gradient-to-br from-blue-700 to-blue-500 flex items-center justify-center">
                    <svg class="w-10 h-10 text-white opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                @endif
                <div class="p-4">
                    <h3 class="font-bold text-gray-900 mb-1 text-sm leading-snug line-clamp-2">{{ $rec->title }}</h3>
                    <p class="text-xs text-gray-500 mb-2">{{ $rec->user?->name ?? '' }}</p>
                    <div class="flex items-center justify-between text-xs text-gray-400">
                        <span>{{ $rec->created_at->format('d M Y') }}</span>
                        <span class="text-blue-600 font-semibold">Explore &rarr;</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection