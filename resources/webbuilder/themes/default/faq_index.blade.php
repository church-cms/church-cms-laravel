@extends('theme::layout')

@section('title', 'FAQ')

@section('content')
@php
    $activeId = request('category', optional($categories->first())->id);
    $active   = $categories->firstWhere('id', $activeId) ?? $categories->first();
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <h1 class="text-3xl font-bold text-gray-800 mb-8">Frequently Asked Questions</h1>

    @if($categories->isEmpty())
        <p class="text-gray-500">No FAQs available.</p>
    @else
    <div class="flex gap-6 items-start">

        {{-- LEFT: Category nav (1/4) --}}
        <aside class="w-1/4 flex-shrink-0 sticky top-6">
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Categories</span>
                </div>
                <nav class="divide-y divide-gray-100">
                    @foreach($categories as $cat)
                    <a href="{{ route('web.faq') }}?category={{ $cat->id }}"
                       class="flex items-center justify-between px-4 py-3 text-sm transition
                              {{ $active && $active->id == $cat->id
                                  ? 'bg-indigo-600 text-white font-semibold'
                                  : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                        <span>{{ $cat->name }}</span>
                        <span class="text-xs rounded-full px-2 py-0.5 font-medium
                                     {{ $active && $active->id == $cat->id
                                         ? 'bg-indigo-500 text-white'
                                         : 'bg-gray-100 text-gray-500' }}">
                            {{ $cat->faq->count() }}
                        </span>
                    </a>
                    @endforeach
                </nav>
            </div>
        </aside>

        {{-- RIGHT: Questions panel (3/4) --}}
        <div class="w-3/4 min-w-0">
            @if($active)
            <div class="flex items-center gap-3 mb-5">
                <h2 class="text-xl font-bold text-gray-800">{{ $active->name }}</h2>
                <span class="text-xs bg-indigo-100 text-indigo-700 rounded-full px-2 py-0.5 font-medium">
                    {{ $active->faq->count() }} {{ Str::plural('question', $active->faq->count()) }}
                </span>
            </div>

            @if($active->faq->isEmpty())
                <p class="text-gray-400 text-sm">No questions in this category yet.</p>
            @else
                <div class="space-y-2">
                    @foreach($active->faq as $item)
                    <details class="bg-white border border-gray-200 rounded-xl group open:border-indigo-300 open:shadow-sm transition-all">
                        <summary class="flex justify-between items-center px-5 py-4 cursor-pointer font-medium text-gray-800 list-none hover:bg-gray-50 rounded-xl group-open:rounded-b-none group-open:border-b group-open:border-indigo-100">
                            <span>{{ $item->question }}</span>
                            <span class="text-indigo-400 flex-shrink-0 ml-3 transition-transform group-open:rotate-180">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </span>
                        </summary>
                        <div class="px-5 py-4 text-gray-600 text-sm leading-relaxed prose prose-sm max-w-none">
                            {!! $item->answer !!}
                        </div>
                    </details>
                    @endforeach
                </div>
            @endif
            @endif
        </div>

    </div>
    @endif

</div>
@endsection
