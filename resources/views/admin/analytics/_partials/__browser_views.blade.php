<div>
    <div>
        <h1 class="text-sm text-gray-800 uppercase font-semibold mb-2">Top browsers</h1>
    </div>
    <div class="bg-white shadow rounded px-4 py-1">
        @if (count($browsers) > 0)
            <div class="flex py-2 items-center">
                <div class="mx-3 text-gray-700 w-4/5 flex justify-between items-center">
                    <p class="font-semibold text-sm">
                        <b>Browser</b>
                    </p>
                    <p class="font-semibold text-sm">
                        <b>Sessions</b>
                    </p>
                </div>
            </div>
            @foreach ($browsers as $visitors)
                <div class="flex py-2 items-center">
                    <div class="mx-3 text-gray-700 w-4/5 flex justify-between items-center">
                        <p class="font-semibold text-sm">
                            {{ $visitors['browser'] }}
                        </p>
                        <p class="text-gray-600 text-xs">
                            {{ $visitors['sessions'] }}
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
