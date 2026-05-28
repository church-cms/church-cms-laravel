{{-- AJAX partial: Rejected prayers --}}
<div class="p-4">
    @forelse($prayers as $prayer)
    <div class="bg-white border-l-4 border-red-500 rounded-lg shadow mb-4 overflow-hidden">
        <div class="bg-red-50 px-6 py-4 border-b border-red-200 flex justify-between items-start gap-3">
            <div>
                <div class="font-bold text-gray-900">{{ $prayer->submitter_name }}</div>
                <div class="text-xs text-gray-500">
                    Rejected {{ $prayer->rejected_at ? $prayer->rejected_at->format('M j, Y') : '—' }}
                    @if($prayer->rejector) · by {{ $prayer->rejector->name }} @endif
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if($prayer->category)
                <span class="px-2 py-0.5 rounded-full text-xs font-semibold"
                    style="background-color: {{ $prayer->category->gradient_start }}; color: {{ $prayer->category->display_color }}">
                    {{ $prayer->category->emoji }} {{ $prayer->category->name }}
                </span>
                @endif
                <span class="px-3 py-1 bg-red-200 text-red-900 rounded-full text-xs font-bold">✗ REJECTED</span>
            </div>
        </div>

        <div class="px-6 py-4">
            @if($prayer->rejection_reason)
            <div class="mb-3 p-3 bg-red-100 border border-red-300 rounded-lg">
                <p class="text-sm text-red-900 font-semibold">❌ {{ $prayer->rejection_reason }}</p>
            </div>
            @endif

            <p class="text-sm text-gray-500 line-through mb-3">{{ Str::limit($prayer->text, 120) }}</p>

            @if($prayer->should_delete_at)
            @php
            $daysLeft = max(0, now()->diffInDays($prayer->should_delete_at, false));
            @endphp
            <div class="p-3 bg-gradient-to-r from-red-100 to-orange-100 rounded-lg">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-semibold text-gray-800">⏳ Auto-delete in:</span>
                    <span class="text-xl font-bold {{ $daysLeft <= 1 ? 'text-red-700' : 'text-red-600' }}">
                        {{ $daysLeft }} {{ $daysLeft === 1 ? 'day' : 'days' }}
                    </span>
                </div>
            </div>
            <p class="text-xs text-gray-400 text-center mt-2">🔒 Auto-purges after 7 days</p>
            @endif
        </div>
    </div>
    @empty
    <div class="text-center py-16 text-gray-400">
        <div class="text-4xl mb-3">✓</div>
        <p class="font-semibold">No rejected prayers</p>
    </div>
    @endforelse

    @if($prayers->hasPages())
    <div class="mt-4">{{ $prayers->links() }}</div>
    @endif
</div>