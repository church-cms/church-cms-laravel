@php
    $pageTitle    = $pageTitle    ?? '';
    $pageSubtitle = $pageSubtitle ?? null;
    $addUrl       = $addUrl       ?? null;
    $addLabel     = $addLabel     ?? 'Add';
@endphp

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="admin-h1">{{ $pageTitle }}</h1>
        @if($pageSubtitle)
            <p class="text-sm text-gray-500 mt-0.5">{{ $pageSubtitle }}</p>
        @endif
    </div>

    @if($addUrl)
    <a href="{{ $addUrl }}"
       class="no-underline inline-flex items-center gap-1.5 text-white text-sm font-semibold px-4 py-2 custom-green rounded shadow-sm hover:opacity-90 transition">
        &nbsp;&#43;&nbsp; {{ $addLabel }}

    </a>
    @endif
</div>
