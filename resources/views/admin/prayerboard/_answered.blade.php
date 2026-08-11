{{-- AJAX partial: Answered prayers --}}
<div class="p-4">
    @forelse($prayers as $prayer)
    <div class="bg-white border-l-4 border-blue-500 rounded-lg shadow mb-4 overflow-hidden">
        <div class="bg-blue-50 px-6 py-4 border-b border-blue-200 flex justify-between items-start gap-3">
            <div>
                <div class="font-bold text-gray-900">{{ $prayer->submitter_name }}</div>
                <div class="text-xs text-gray-500">
                    Posted {{ $prayer->created_at->format('M j, Y') }}
                    @if($prayer->answered_at)
                     · Answered {{ $prayer->answered_at->format('M j, Y') }}
                    @endif
                </div>
                @if($prayer->approver)
                <div class="text-xs text-gray-400">✓ Approved by {{ $prayer->approver->name }}</div>
                @endif
            </div>
            <div class="flex items-center gap-2">
                @if($prayer->category)
                <span class="px-2 py-0.5 rounded-full text-xs font-semibold"
                    style="background-color: {{ $prayer->category->gradient_start }}; color: {{ $prayer->category->display_color }}">
                    {{ $prayer->category->emoji }} {{ $prayer->category->name }}
                </span>
                @endif
                <span class="px-3 py-1 bg-blue-200 text-blue-900 rounded-full text-xs font-bold">✓ ANSWERED</span>
            </div>
        </div>

        <div class="px-6 py-4">
            <p class="text-gray-700 text-sm mb-3">{{ $prayer->text }}</p>

            @if($prayer->answer_testimony)
            <div class="mb-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-xs font-bold text-green-700 mb-1">📖 Testimony</p>
                <p class="text-sm text-green-900 italic">"{{ $prayer->answer_testimony }}"</p>
            </div>
            @endif

            <div class="grid grid-cols-3 gap-4 p-3 bg-blue-50 rounded-lg">
                <div>
                    <p class="text-xs text-gray-600 font-semibold">Days Active</p>
                    <p class="text-lg font-bold text-blue-700">
                        @if($prayer->approved_at && $prayer->answered_at)
                            {{ $prayer->approved_at->diffInDays($prayer->answered_at) }}
                        @else
                            —
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 font-semibold">Total Prayers</p>
                    <p class="text-lg font-bold text-blue-700">{{ $prayer->total_participant_count }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 font-semibold">Status</p>
                    <p class="text-lg font-bold text-blue-700">Testified</p>
                </div>
            </div>
            <p class="text-xs text-gray-400 text-center mt-2">📖 Testimony Archive — read-only</p>
        </div>

        <div class="bg-gray-50 px-6 py-2 border-t border-gray-100 flex justify-end">
            <a href="{{ url('/admin/prayerboard/' . $prayer->id) }}" class="text-xs text-indigo-600 hover:underline">View detail →</a>
        </div>
    </div>
    @empty
    <div class="text-center py-16 text-gray-400">
        <div class="text-4xl mb-3">🎉</div>
        <p class="font-semibold">No answered prayers yet</p>
        <p class="text-sm">Mark active prayers as answered when testimonies come in.</p>
    </div>
    @endforelse

    @if($prayers->hasPages())
    <div class="mt-4">{{ $prayers->links() }}</div>
    @endif
</div>
