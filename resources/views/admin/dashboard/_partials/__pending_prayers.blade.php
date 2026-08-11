<div class="w-full">
    <div class="flex justify-between items-center mb-2">
        <div class="flex items-center gap-2">
            <h1 class="text-sm uppercase text-gray-800 font-semibold">Prayer Requests</h1>
            @if($dashboard['pendingPrayerCount'] > 0)
                <span class="bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-0.5 rounded-full">
                    {{ $dashboard['pendingPrayerCount'] }} pending
                </span>
            @endif
        </div>
        <a href="{{ url('/admin/prayerboard') }}" class="text-xs underline">See All</a>
    </div>

    <div class="w-full shadow bg-white rounded dashboard-content">
        <div class="w-full p-2">
            @forelse($dashboard['pendingPrayers'] as $prayer)
                <div class="mt-2 border-l-4 border-yellow-400 rounded bg-yellow-50">
                    <div class="flex items-start p-2 gap-2">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($prayer->submitter_name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-1">
                                <span class="font-semibold text-sm text-gray-800 truncate">{{ $prayer->submitter_name }}</span>
                                <span class="text-xs text-gray-400 flex-shrink-0">{{ $prayer->created_at->diffForHumans() }}</span>
                            </div>
                            @if($prayer->category)
                                <span class="inline-block text-xs px-1.5 py-0.5 rounded-full font-medium mt-0.5"
                                    style="background-color: {{ $prayer->category->gradient_start }}; color: {{ $prayer->category->display_color }}">
                                    {{ $prayer->category->emoji }} {{ $prayer->category->name }}
                                </span>
                            @endif
                            <p class="text-xs text-gray-600 mt-1 line-clamp-2">{{ \Str::limit($prayer->text, 80) }}</p>
                            <a href="{{ url('/admin/prayerboard/' . $prayer->id) }}"
                                class="text-xs text-indigo-600 hover:underline mt-1 inline-block">
                                Review &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-4 text-center">
                    <p class="text-sm text-gray-500 font-semibold">No pending requests</p>
                </div>
            @endforelse

            @if($dashboard['pendingPrayerCount'] > 5)
                <div class="mt-2 text-center">
                    <a href="{{ url('/admin/prayerboard') }}"
                        class="text-xs text-indigo-600 hover:underline">
                        +{{ $dashboard['pendingPrayerCount'] - 5 }} more pending &rarr;
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
