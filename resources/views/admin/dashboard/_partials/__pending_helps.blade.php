<div class="w-full">
    <div class="flex justify-between items-center mb-2">
        <div class="flex items-center gap-2">
            <h1 class="text-sm uppercase text-gray-800 font-semibold">Help Requests</h1>
            @if($dashboard['pendingHelpCount'] > 0)
                <span class="bg-red-100 text-red-700 text-xs font-bold px-2 py-0.5 rounded-full">
                    {{ $dashboard['pendingHelpCount'] }} pending
                </span>
            @endif
        </div>
        <a href="{{ url('/admin/helps') }}" class="text-xs underline">See All</a>
    </div>

    <div class="w-full shadow bg-white rounded dashboard-content">
        <div class="w-full p-2">
            @forelse($dashboard['pendingHelps'] as $help)
                <div class="mt-2 border-l-4 border-red-400 rounded bg-red-50">
                    <div class="flex items-start p-2 gap-2">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($help->user->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-1">
                                <span class="font-semibold text-sm text-gray-800 truncate">
                                    {{ $help->user->FullName ?? $help->user->name ?? 'Anonymous' }}
                                </span>
                                <span class="text-xs text-gray-400 flex-shrink-0">{{ $help->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs font-medium text-gray-700 mt-0.5 truncate">{{ $help->title }}</p>
                            <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ \Str::limit($help->description, 80) }}</p>
                            <a href="{{ url('/admin/help/edit/' . $help->id) }}"
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

            @if($dashboard['pendingHelpCount'] > 5)
                <div class="mt-2 text-center">
                    <a href="{{ url('/admin/helps') }}" class="text-xs text-indigo-600 hover:underline">
                        +{{ $dashboard['pendingHelpCount'] - 5 }} more pending &rarr;
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
