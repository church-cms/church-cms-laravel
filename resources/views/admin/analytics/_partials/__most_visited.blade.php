<div>
    <div>
        <h1 class="text-sm text-gray-800 uppercase font-semibold mb-2">Visitors and pageviews
        </h1>
    </div>
    <div class="bg-white shadow rounded px-3 py-1">
        @if (count($mostVisitedpage) > 0)
            <div class="flex py-2 items-center">
                <div class="w-1/5 font-semibold text-sm">
                    <b>URL</b>
                </div>
                <div class="mx-2 text-gray-700 w-4/5 flex justify-between items-center">
                    <p class="font-semibold text-sm">
                        <b>Page Title</b>
                    </p>
                    <p class="font-semibold text-sm">
                        <b>Page Views</b>
                    </p>
                </div>
            </div>
            @foreach ($mostVisitedpage as $visitors)
                <div class="flex py-2 items-center">
                    <div class="w-1/5">
                        {{ $visitors['url'] }}
                    </div>
                    <div class="mx-2 text-gray-700 w-4/5 flex justify-between items-center">
                        <p class="font-semibold text-sm">
                            {{ $visitors['pageTitle'] }}
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
