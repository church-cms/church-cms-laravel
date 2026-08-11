@extends('theme::layout')

@section('title', 'Bulletins')

@section('content')
@include('theme::_hero_banner', [
    'heroTitle'    => 'Bulletins',
    'heroSubtitle' => 'Weekly and monthly parish bulletins and newsletters',
    'breadcrumbs'  => [
        ['label' => 'Home', 'url' => route('web.home')],
        ['label' => 'Bulletins'],
    ],
])
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    @php
        $grouped = $bulletins->groupBy('year');
    @endphp

    @forelse($grouped as $year => $items)
    <div class="mb-10">
        <h2 class="text-xl font-semibold text-indigo-700 mb-4 border-b border-indigo-100 pb-2">{{ $year }}</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($items as $bulletin)
            <a href="{{ route('web.bulletin.show', $bulletin->id) }}"
               class="flex bg-white rounded-lg shadow hover:shadow-md overflow-hidden transition group">

                {{-- Cover image --}}
                <div class="w-24 shrink-0 bg-indigo-50 flex items-center justify-center text-4xl text-indigo-300">
                    @if($bulletin->cover_image)
                        <img src="{{ $bulletin->coverImagePath }}"
                             alt="{{ $bulletin->name }}"
                             loading="lazy"
                             class="w-24 h-full object-cover">
                    @else
                        &#128196;
                    @endif
                </div>

                <div class="p-4 flex flex-col justify-center min-w-0">
                    <span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full mb-2 w-fit
                        {{ $bulletin->type === 'week' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $bulletin->type === 'week' ? 'Weekly' : 'Monthly' }}
                    </span>

                    <h3 class="font-semibold text-gray-800 text-sm leading-snug line-clamp-2 group-hover:text-indigo-600 transition">
                        {{ $bulletin->name }}
                    </h3>

                    <p class="text-xs text-gray-400 mt-1">
                        @if($bulletin->type === 'week' && $bulletin->week)
                            Week {{ $bulletin->week }}, {{ $bulletin->year }}
                        @elseif($bulletin->type === 'month' && $bulletin->month)
                            {{ \Carbon\Carbon::create($bulletin->year, $bulletin->month)->format('F Y') }}
                        @else
                            {{ $bulletin->year }}
                        @endif
                    </p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @empty
    <div class="text-center py-24 text-gray-400">
        <div class="text-6xl mb-4">&#128196;</div>
        <p class="text-lg">No bulletins available yet.</p>
    </div>
    @endforelse

    <div class="mt-8">
        {{ $bulletins->links() }}
    </div>

</div>
@endsection
