{{-- AJAX partial: Ended/expired prayers --}}
<div class="p-4">
    @forelse($prayers as $prayer)
    <div class="bg-white border-l-4 border-gray-400 rounded-lg shadow mb-4 overflow-hidden">
        <div class="bg-gray-100 px-6 py-4 border-b border-gray-300 flex justify-between items-start gap-3">
            <div>
                <div class="font-bold text-gray-700">{{ $prayer->submitter_name }}</div>
                <div class="text-xs text-gray-500">
                    Posted {{ $prayer->created_at->format('M j, Y') }}
                    @if($prayer->expires_at)
                     · Expired {{ $prayer->expires_at->format('M j, Y') }}
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if($prayer->category)
                <span class="px-2 py-0.5 rounded-full text-xs font-semibold"
                    style="background-color: {{ $prayer->category->gradient_start }}; color: {{ $prayer->category->display_color }}">
                    {{ $prayer->category->emoji }} {{ $prayer->category->name }}
                </span>
                @endif
                <span class="px-3 py-1 bg-gray-300 text-gray-700 rounded-full text-xs font-bold">⏰ ENDED</span>
            </div>
        </div>

        <div class="px-6 py-4">
            <p class="text-gray-600 text-sm italic mb-3">{{ $prayer->text }}</p>
            <div class="grid grid-cols-3 gap-4 p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-xs text-gray-500 font-semibold">Duration</p>
                    <p class="text-lg font-bold text-gray-700">{{ $prayer->expiry_days ?? '—' }} days</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold">Total Prayers</p>
                    <p class="text-lg font-bold text-gray-700">{{ $prayer->total_participant_count }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold">Status</p>
                    <p class="text-lg font-bold text-gray-600">Archived</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 px-6 py-2 border-t border-gray-100 flex justify-end">
            <a href="{{ url('/admin/prayerboard/' . $prayer->id) }}" class="text-xs text-indigo-600 hover:underline">View detail →</a>
        </div>
    </div>
    @empty
    <div class="text-center py-16 text-gray-400">
        <div class="text-4xl mb-3">📚</div>
        <p class="font-semibold">No ended prayers</p>
    </div>
    @endforelse

    @if($prayers->hasPages())
    <div class="mt-4">{{ $prayers->links() }}</div>
    @endif
</div>
