@extends('theme::layout')

@section('title', $gallery->name)

@section('content')

@include('theme::_hero_banner', [
    'heroTitle'   => $gallery->name,
    'heroSubtitle' => $gallery->description ?? null,
    'breadcrumbs' => [
        ['label' => 'Home',    'url' => route('web.home')],
        ['label' => 'Gallery', 'url' => route('web.gallery')],
        ['label' => $gallery->name],
    ],
])

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    @if($gallery->photos->count())

    @php
        $photos = $gallery->photos->map(fn($p) => $p->FullPath)->values()->toArray();
    @endphp

    <div
        x-data="{
            photos: {{ json_encode($photos) }},
            active: 0,
            prev() { this.active = (this.active - 1 + this.photos.length) % this.photos.length; this.scrollThumb(); },
            next() { this.active = (this.active + 1) % this.photos.length; this.scrollThumb(); },
            scrollThumb() {
                this.$nextTick(() => {
                    const el = document.getElementById('thumb-' + this.active);
                    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                });
            }
        }"
        @keydown.left.window="prev()"
        @keydown.right.window="next()"
    >

        {{-- Main viewer --}}
        <div class="relative bg-gray-900 rounded-2xl overflow-hidden shadow-xl" style="aspect-ratio:16/9">

            <template x-for="(photo, i) in photos" :key="i">
                <img
                    :src="photo"
                    x-show="active === i"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="absolute inset-0 w-full h-full object-contain"
                    :alt="'Photo ' + (i + 1)">
            </template>

            {{-- Prev button --}}
            <button @click="prev()"
                class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-black bg-opacity-50 hover:bg-opacity-75 text-white rounded-full flex items-center justify-center transition focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            {{-- Next button --}}
            <button @click="next()"
                class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-black bg-opacity-50 hover:bg-opacity-75 text-white rounded-full flex items-center justify-center transition focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            {{-- Counter badge --}}
            <div class="absolute bottom-3 right-3 bg-black bg-opacity-60 text-white text-xs font-semibold px-3 py-1 rounded-full">
                <span x-text="active + 1"></span> / {{ $gallery->photos->count() }}
            </div>

        </div>

        {{-- Thumbnail strip --}}
        <div class="flex gap-2 overflow-x-auto mt-4 pb-2 scroll-smooth" style="scrollbar-width:thin">
            @foreach($gallery->photos as $i => $photo)
            <button
                id="thumb-{{ $i }}"
                @click="active = {{ $i }}; scrollThumb()"
                :class="active === {{ $i }} ? 'ring-2 ring-indigo-500 ring-offset-2 opacity-100' : 'opacity-60 hover:opacity-90'"
                class="flex-shrink-0 w-20 h-14 rounded-lg overflow-hidden transition focus:outline-none">
                <img
                    src="{{ $photo->FullPath }}"
                    alt="Thumbnail {{ $i + 1 }}"
                    loading="lazy"
                    class="w-full h-full object-cover">
            </button>
            @endforeach
        </div>

    </div>

    @else
        <p class="text-gray-500 text-center py-20">No photos in this album yet.</p>
    @endif

</div>

@endsection
