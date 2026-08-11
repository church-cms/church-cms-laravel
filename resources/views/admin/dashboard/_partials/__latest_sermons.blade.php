<div class="w-full">
    <div class="flex justify-between">
        <h1 class="text-sm uppercase text-gray-800 font-semibold mb-2">Latest Sermons</h1>
        <a href="{{ url('/admin/sermons') }}" class="text-xs underline">See All</a>
    </div>
    <div class="w-full bg-white shadow rounded dashboard-content">
        <dashboard-sermon url="{{ url('/') }}"></dashboard-sermon>
    </div>
</div>
