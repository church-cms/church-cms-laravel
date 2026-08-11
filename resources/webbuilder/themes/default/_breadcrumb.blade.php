@php $breadcrumbs = $breadcrumbs ?? []; @endphp

@if(count($breadcrumbs))
<nav aria-label="Breadcrumb" class="flex justify-center mb-5">
    <ol class="flex items-center gap-1.5 text-sm text-indigo-300">
        @foreach($breadcrumbs as $crumb)
            @if(!$loop->first)
                <li>
                    <svg class="w-3.5 h-3.5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </li>
            @endif
            <li>
                @if(!$loop->last && isset($crumb['url']))
                    <a href="{{ $crumb['url'] }}" class="hover:text-white transition-colors">{{ $crumb['label'] }}</a>
                @else
                   <span>&nbsp;&#47;&nbsp;</span> <span class="text-white font-medium">{{ $crumb['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
@endif
