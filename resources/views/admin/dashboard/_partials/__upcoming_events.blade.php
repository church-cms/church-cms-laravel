<div class="w-full">
    <div class="flex justify-between">
        <h1 class="text-sm uppercase text-gray-800 font-semibold mb-2">Upcoming Events</h1>
        <a href="{{ url('/admin/events') }}" class="text-xs underline">See All</a>
    </div>

    <div class="w-full shadow bg-white rounded dashboard-content">
        <div class="container mx-auto">
            <div class="flex flex-col lg:flex-row md:flex-row">
                <div class="w-full p-2">
                    @forelse($dashboard['upcomingEvents'] as $event)
                        <ul class="list-reset mt-2">
                            <li class="mt-2 bg-light border-l-4 border-teal-400 rounded">
                                <div class="flex items-center">
                                    <div class="w-1/4 py-1 text-center leading-tight">
                                        <p class="text-gray-600 text-3xl font-bold">{{ date('d', strtotime($event->start_date)) }}</p>
                                        <p class="text-xs text-gray-600 uppercase">{{ date('M', strtotime($event->start_date)) }}</p>
                                    </div>
                                    <div class="w-3/4 ml-2 leading-relaxed">
                                        <a href="{{ url('/admin/events/show/details/' . $event->id) }}" class="font-semibold text-sm py-1">{{ $event->title }}</a>
                                        <p class="text-xs">{{ $event->category }}</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    @empty
                        <div class="py-2">
                            <p class="font-semibold text-sm" style="text-align: center;">No Records Found</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
