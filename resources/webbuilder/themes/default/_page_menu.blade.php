<aside class="lg:w-1/4 flex-shrink-0">
    <div class="bg-white rounded-lg shadow overflow-hidden sticky top-6">

        <div class="bg-indigo-700 px-5 py-3">
            <h2 class="text-white font-semibold text-base tracking-wide">Pages</h2>
        </div>

        @forelse($grouped as $categoryName => $pages)
        <div class="border-b border-gray-100 last:border-0">
            <div class="px-5 py-2 bg-gray-50 text-xs font-bold uppercase tracking-widest text-gray-500">
                {{ $categoryName }}
            </div>
            <ul>
                @foreach($pages as $p)
                <li>
                    <a href="{{ route('web.page', [optional($p->pageCategory)->slug ?? 'general', $p->slug]) }}"
                       class="flex items-center px-5 py-2 text-sm transition-colors
                              {{ $activePage && $activePage->id == $p->id
                                 ? 'bg-indigo-50 text-indigo-700 font-semibold border-l-4 border-indigo-600'
                                 : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600 border-l-4 border-transparent' }}">
                        {{ $p->menu_text ?: $p->page_name }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @empty
        <p class="px-5 py-4 text-sm text-gray-500">No pages available.</p>
        @endforelse

    </div>
</aside>
