@php
    $heroTitle    = $heroTitle    ?? '';
    $heroSubtitle = $heroSubtitle ?? null;
    $heroIcon     = $heroIcon     ?? null;
    $breadcrumbs  = $breadcrumbs  ?? [];
@endphp

<section class="w-full bg-gradient-to-r from-indigo-800 via-indigo-700 to-indigo-900 text-white overflow-hidden relative">
    {{-- Decorative circles --}}
    <div class="absolute -top-16 -left-16 w-72 h-72 bg-white/5 rounded-full pointer-events-none"></div>
    <div class="absolute -bottom-16 -right-16 w-96 h-96 bg-white/5 rounded-full pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-white/[0.02] rounded-full pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">

        @include('theme::_breadcrumb', ['breadcrumbs' => $breadcrumbs])

        @if($heroIcon)
            <div class="text-5xl mb-3">{{ $heroIcon }}</div>
        @endif

        <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight tracking-tight">
            {{ $heroTitle }}
        </h1>

        @if($heroSubtitle)
            <p class="mt-4 text-indigo-200 text-lg max-w-2xl mx-auto">
                {{ $heroSubtitle }}
            </p>
        @endif

    </div>
</section>
