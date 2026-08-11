<div>
    <div>
        <h1 class="text-sm text-gray-800 uppercase font-semibold mb-2">Top referrers
        </h1>
    </div>
    <div class="bg-white shadow rounded px-4 py-1">
        @if (count($referrer) > 0)
            <div class="flex py-2 items-center">
                <div class="mx-3 text-gray-700 w-4/5 flex justify-between items-center">
                    <p class="font-semibold text-sm">
                        <b>URL</b>
                    </p>
                    <p class="font-semibold text-sm">
                        <b>Page Views</b>
                    </p>
                </div>
            </div>
            @foreach ($referrer as $visitors)
                <div class="flex py-2 items-center">
                    <div class="mx-3 text-gray-700 w-4/5 flex justify-between items-center">
                        <p class="font-semibold text-sm">
                            {{ $visitors['url'] }}
                        </p>
                        <p class="text-gray-600 text-xs">
                            {{ $visitors['pageViews'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        @else
            <div class="flex py-2 items-center">
                <p class="font-semibold text-sm w-full" style="text-align: center;">No Records
                    Found</p>
            </div>
        @endif
    </div>
</div>
