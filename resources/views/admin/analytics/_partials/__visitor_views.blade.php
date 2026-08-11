<div>
    <div>
        <h1 class="text-sm text-gray-800 uppercase font-semibold mb-2">Total visitors and pageviews</h1>
    </div>
    <div class="bg-white shadow rounded px-4 py-1">
        @if (count($pageViews) > 0)
            <div class="flex py-2 items-center">
                <div class="w-1/4 font-semibold text-sm">
                    <b>Page Title</b>
                </div>
                <div class="mx-3 text-gray-700 w-3/4 flex justify-between items-center">
                    <p class="font-semibold text-sm">
                        <b>Visitors</b>
                    </p>
                    <p class="font-semibold text-sm">
                        <b>Page Views & Date</b>
                    </p>
                </div>
            </div>
            @foreach ($pageViews as $visitors)
                <div class="flex py-2 items-center">
                    <div class="w-1/4">
                        {{ $visitors['pageTitle'] }}
                    </div>
                    <div class="mx-3 text-gray-700 w-3/4 flex justify-between items-center">
                        <p class="font-semibold text-sm">
                            {{ $visitors['visitors'] }}
                        </p>
                        <p class="text-gray-600 text-xs">
                            {!! '<b>' . $visitors['pageViews'] . '</b> (' . $visitors['date']->toDateTimeString() . ')' !!}
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
