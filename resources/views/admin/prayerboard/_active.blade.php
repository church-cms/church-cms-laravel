{{-- AJAX partial: Active prayers --}}
<div class="p-4">
    @include('partials.message')

    @forelse($prayers as $prayer)
    <div class="bg-white border-l-4 border-green-500 rounded-lg shadow mb-4 overflow-hidden" id="prayer-card-{{ $prayer->id }}">
        {{-- Header --}}
        <div class="bg-green-50 px-6 py-4 border-b border-green-200 flex justify-between items-start gap-3">
            <div class="flex items-start gap-3 flex-1">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                    {{ strtoupper(substr($prayer->submitter_name, 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-gray-900">{{ $prayer->submitter_name }}</span>
                        @if($prayer->is_pinned)
                        <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full font-semibold">📌 Pinned</span>
                        @endif
                    </div>
                    <div class="text-xs text-gray-500">Posted {{ $prayer->created_at->format('M j, Y') }}</div>
                    @if($prayer->approver)
                    <div class="text-xs text-gray-400">✓ Approved by {{ $prayer->approver->name }}</div>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                @if($prayer->category)
                <span class="px-2 py-0.5 rounded-full text-xs font-semibold"
                    style="background-color: {{ $prayer->category->gradient_start }}; color: {{ $prayer->category->display_color }}">
                    {{ $prayer->category->emoji }} {{ $prayer->category->name }}
                </span>
                @endif
                <span class="px-3 py-1 bg-green-200 text-green-900 rounded-full text-xs font-bold">🟢 ACTIVE</span>
            </div>
        </div>

        {{-- Body --}}
        <div class="px-6 py-4">
            <p class="text-gray-700 text-sm mb-4">{{ $prayer->text }}</p>

            {{-- Expiry bar --}}
            @if($prayer->expires_at)
            <div class="mb-4 p-3 bg-orange-50 rounded-lg border border-orange-200">
                <div class="flex justify-between items-center mb-1">
                    <span class="text-sm font-semibold text-gray-700">⏰ Expires in:</span>
                    <span class="font-bold {{ $prayer->days_remaining <= 7 ? 'text-red-600' : 'text-orange-600' }}">
                        {{ $prayer->days_remaining }} days
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="h-2 rounded-full bg-gradient-to-r from-orange-500 to-red-500"
                        style="width: {{ $prayer->expiry_percentage }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">Expires {{ $prayer->expires_at->format('M j, Y') }}</p>
            </div>
            @endif

            {{-- Participant counts --}}
            <div class="grid grid-cols-3 gap-3 mb-4">
                <div class="bg-indigo-50 p-2 rounded border border-indigo-200 text-center">
                    <p class="text-xs text-gray-600 font-bold">Members</p>
                    <p class="text-xl font-bold text-indigo-600">{{ $prayer->member_count }}</p>
                </div>
                <div class="bg-purple-50 p-2 rounded border border-purple-200 text-center">
                    <p class="text-xs text-gray-600 font-bold">Guests</p>
                    <p class="text-xl font-bold text-purple-600">{{ $prayer->guest_count }}</p>
                </div>
                <div class="bg-gray-50 p-2 rounded border border-gray-200 text-center">
                    <p class="text-xs text-gray-600 font-bold">Anon</p>
                    <p class="text-xl font-bold text-gray-600">{{ $prayer->anonymous_count }}</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="grid grid-cols-2 gap-2">
                @if($prayer->is_pinned)
                <button onclick="prayerAction({{ $prayer->id }}, 'unpin')"
                    class="px-3 py-2 bg-yellow-500 text-white text-xs font-bold rounded hover:bg-yellow-600">📌 Unpin</button>
                @else
                <button onclick="prayerAction({{ $prayer->id }}, 'pin')"
                    class="px-3 py-2 bg-blue-600 text-white text-xs font-bold rounded hover:bg-blue-700">📌 Pin to Top</button>
                @endif
                <button onclick="showAnsweredModal({{ $prayer->id }})"
                    class="px-3 py-2 bg-green-600 text-white text-xs font-bold rounded hover:bg-green-700">✓ Answered</button>
                <button onclick="showExtendModal({{ $prayer->id }})"
                    class="px-3 py-2 bg-indigo-600 text-white text-xs font-bold rounded hover:bg-indigo-700">⏰ Extend</button>
                <button onclick="prayerAction({{ $prayer->id }}, 'unpublish')"
                    class="px-3 py-2 bg-gray-600 text-white text-xs font-bold rounded hover:bg-gray-700">⊘ Unpublish</button>
            </div>
        </div>

        <div class="bg-gray-50 px-6 py-2 border-t border-gray-100 flex justify-end">
            <a href="{{ url('/admin/prayerboard/' . $prayer->id) }}" class="text-xs text-indigo-600 hover:underline">View detail &amp; audit log →</a>
        </div>
    </div>
    @empty
    <div class="text-center py-16 text-gray-400">
        <div class="text-4xl mb-3">🙏</div>
        <p class="font-semibold">No active prayers</p>
    </div>
    @endforelse

    @if($prayers->hasPages())
    <div class="mt-4">{{ $prayers->links() }}</div>
    @endif
</div>

{{-- Mark Answered modal --}}
<div id="answeredModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-3">✓ Mark Prayer as Answered</h3>
        <input type="hidden" id="answeredPrayerId" value="">
        <label class="block text-sm font-semibold text-gray-700 mb-1">Testimony (optional)</label>
        <textarea id="answeredTestimony" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" rows="3"
            placeholder="Share how God answered this prayer..."></textarea>
        <div class="flex gap-3 mt-4">
            <button onclick="submitAnswered()"
                class="flex-1 px-4 py-2 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700">Confirm</button>
            <button onclick="document.getElementById('answeredModal').classList.add('hidden')"
                class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300">Cancel</button>
        </div>
    </div>
</div>

{{-- Extend modal --}}
<div id="extendModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-3">⏰ Extend Prayer Expiry</h3>
        <input type="hidden" id="extendPrayerId" value="">
        <label class="block text-sm font-semibold text-gray-700 mb-1">Extend by</label>
        <select id="extendDays" class="w-full border-2 border-gray-300 rounded-lg px-3 py-2 text-sm font-medium">
            <option value="7">7 Days</option>
            <option value="14">14 Days</option>
            <option value="30">30 Days</option>
            <option value="60">60 Days</option>
        </select>
        <div class="flex gap-3 mt-4">
            <button onclick="submitExtend()"
                class="flex-1 px-4 py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700">Extend</button>
            <button onclick="document.getElementById('extendModal').classList.add('hidden')"
                class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300">Cancel</button>
        </div>
    </div>
</div>

<script>
    (function() {
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var baseUrl = '{{ url("/admin/prayerboard") }}';

        window.prayerAction = function(id, action) {
            fetch(baseUrl + '/' + id + '/' + action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                })
                .then(function(r) {
                    return r.json();
                })
                .then(function(data) {
                    if (data.success) {
                        showToast(data.message, 'green');
                        if (action === 'unpublish') {
                            var card = document.getElementById('prayer-card-' + id);
                            if (card) card.remove();
                        } else {
                            // Reload tab to reflect pin change
                            window.switchTab && switchTab('active');
                        }
                    } else {
                        showToast(data.message || 'Error', 'red');
                    }
                });
        };

        window.showAnsweredModal = function(id) {
            document.getElementById('answeredPrayerId').value = id;
            document.getElementById('answeredTestimony').value = '';
            document.getElementById('answeredModal').classList.remove('hidden');
        };

        window.submitAnswered = function() {
            var id = document.getElementById('answeredPrayerId').value;
            var testimony = document.getElementById('answeredTestimony').value.trim();
            fetch(baseUrl + '/' + id + '/mark-answered', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        testimony: testimony
                    }),
                })
                .then(function(r) {
                    return r.json();
                })
                .then(function(data) {
                    document.getElementById('answeredModal').classList.add('hidden');
                    if (data.success) {
                        var card = document.getElementById('prayer-card-' + id);
                        if (card) card.remove();
                        showToast(data.message, 'green');
                        updateStatCount('active', -1);
                        updateStatCount('answered', 1);
                    } else {
                        showToast(data.message || 'Error', 'red');
                    }
                });
        };

        window.showExtendModal = function(id) {
            document.getElementById('extendPrayerId').value = id;
            document.getElementById('extendModal').classList.remove('hidden');
        };

        window.submitExtend = function() {
            var id = document.getElementById('extendPrayerId').value;
            var days = document.getElementById('extendDays').value;
            fetch(baseUrl + '/' + id + '/extend', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        additional_days: parseInt(days)
                    }),
                })
                .then(function(r) {
                    return r.json();
                })
                .then(function(data) {
                    document.getElementById('extendModal').classList.add('hidden');
                    if (data.success) {
                        showToast(data.message, 'green');
                        switchTab && switchTab('active');
                    } else {
                        showToast(data.message || 'Error', 'red');
                    }
                });
        };

        function showToast(msg, color) {
            var toast = document.createElement('div');
            toast.className = 'fixed bottom-6 right-6 px-6 py-3 rounded-lg shadow-xl text-white font-semibold text-sm z-50 ' +
                (color === 'green' ? 'bg-green-600' : 'bg-red-600');
            toast.textContent = msg;
            document.body.appendChild(toast);
            setTimeout(function() {
                toast.remove();
            }, 3500);
        }

        function updateStatCount(tab, delta) {
            var btn = document.getElementById('stat-' + tab);
            if (!btn) return;
            var countEl = btn.querySelector('.text-2xl');
            if (countEl) {
                var current = parseInt(countEl.textContent) || 0;
                countEl.textContent = Math.max(0, current + delta);
            }
        }
    })();
</script>