@extends('theme::layout')

@section('title', 'Sermons')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-800 mb-10">Sermons</h1>

    @forelse($grouped as $userId => $sermons)
    @php $preacher = $sermons->first()->user; @endphp
    <section class="mb-12">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg">
                {{ strtoupper(substr($preacher->name ?? '?', 0, 1)) }}
            </div>
            <h2 class="text-xl font-semibold text-gray-700">{{ $preacher->name ?? 'Unknown Preacher' }}</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($sermons as $sermon)
            <a href="{{ route('web.sermon', $sermon->id) }}" class="block bg-white rounded-lg shadow hover:shadow-md overflow-hidden transition">
                @if($sermon->cover_image)
                    <img src="{{ $sermon->cover_image_path }}" alt="{{ $sermon->title }}" class="w-full h-40 object-cover">
                @else
                    <div class="w-full h-40 bg-indigo-50 flex items-center justify-center text-indigo-200 text-5xl">&#127908;</div>
                @endif
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800 text-sm leading-snug">{{ $sermon->title }}</h3>
                    <p class="text-xs text-gray-400 mt-1">{{ $sermon->created_at->format('d M Y') }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @empty
    <p class="text-gray-500">No sermons available.</p>
    @endforelse

</div>
@endsection
