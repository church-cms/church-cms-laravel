@extends('theme::layout')

@section('title', $event->title)
@section('meta_description', Str::limit(strip_tags($event->description), 160))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    @if($event->image)
        <img src="{{ \Storage::disk('public')->url($event->image) }}" alt="{{ $event->title }}" class="w-full h-64 object-cover rounded-lg mb-8">
    @endif

    <div class="flex flex-wrap gap-2 mb-3">
        @if($event->category)
            <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full">{{ $event->category }}</span>
        @endif
        @if($event->allDay)
            <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full">All Day</span>
        @endif
    </div>

    <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $event->title }}</h1>

    <div class="bg-gray-50 rounded-lg p-4 mb-6 text-sm text-gray-600 space-y-2">
        <div>
            <span class="font-medium">Start:</span>
            @if($event->allDay)
                {{ \Carbon\Carbon::parse($event->start_date)->format('l, d M Y') }}
            @else
                {{ \Carbon\Carbon::parse($event->start_date)->format('l, d M Y \a\t g:i A') }}
            @endif
        </div>
        @if($event->end_date)
        <div>
            <span class="font-medium">End:</span>
            @if($event->allDay)
                {{ \Carbon\Carbon::parse($event->end_date)->format('l, d M Y') }}
            @else
                {{ \Carbon\Carbon::parse($event->end_date)->format('l, d M Y \a\t g:i A') }}
            @endif
        </div>
        @endif
        @if($event->location)
        <div><span class="font-medium">Location:</span> {{ $event->location }}</div>
        @endif
        @if($event->organised_by)
        <div><span class="font-medium">Organised by:</span> {{ $event->organised_by }}</div>
        @endif
    </div>

    @if($event->description)
    <div class="prose max-w-none text-gray-700">
        {!! $event->description !!}
    </div>
    @endif

    @if($event->gallery->isNotEmpty())
    <div class="mt-10">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Event Photos</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
            @foreach($event->gallery as $photo)
                <a href="{{ \Storage::disk('public')->url($photo->path) }}" target="_blank" class="block overflow-hidden rounded-lg">
                    <img src="{{ \Storage::disk('public')->url($photo->path) }}" alt="{{ $event->title }}" class="w-full h-36 object-cover hover:opacity-90 transition-opacity">
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <div class="mt-10">
        <a href="{{ route('web.events') }}" class="text-indigo-600 hover:underline text-sm">&larr; Back to Events</a>
    </div>
</div>
@endsection
