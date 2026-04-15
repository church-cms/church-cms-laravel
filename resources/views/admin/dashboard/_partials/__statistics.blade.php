<div class="w-full">
    <div>
        <h1 class="text-sm uppercase text-gray-800 font-semibold mb-2">Statistics</h1>
    </div>
    <div class="bg-white shadow rounded px-3 py-3 my-2 dashboard-content">
        <div class="flex justify-between flex-col py-2">
            <div class="leading-relaxed flex justify-between">
                <p class="text-xs text-black font-semibold">
                    <a href="{{ url('/admin/members') }}" target="_blank">Members</a>
                </p>
               
            </div>
            <div class="progress w-1/2 my-1">
                <div class="bar" style="width:{{ $dashboard['memberCount'] }}%">
                    <p class="percent"></p>
                </div>
            </div>
        </div>
        <div class="flex justify-between flex-col py-2">
            <div class="leading-relaxed flex justify-between">
                <p class="text-xs text-black font-semibold">
                    <a href="{{ url('/admin/events') }}" target="_blank">Events</a>
                </p>
                
            </div>
            <div class="progress w-1/2 my-1">
                <div class="bar" style="width:{{ $dashboard['eventCount'] }}%">
                    <p class="percent"></p>
                </div>
            </div>
        </div>
        <div class="flex justify-between flex-col py-2">
            <div class="leading-relaxed flex justify-between">
               
            </div>
            <div class="progress w-1/2 my-1">
                
            </div>
        </div>
        <div class="flex justify-between flex-col py-2">
            <div class="leading-relaxed flex justify-between">
                <p class="text-xs text-black font-semibold">
                    <a href="{{ url('/admin/groups') }}" target="_blank">Groups</a>
                </p>
                
            </div>
            <div class="progress w-1/2 my-1">
                
            </div>
        </div>
    </div>
</div>
