@extends('layouts.admin.layout')

@section('content')
@php
    $locked = (bool) $session->locked_at;
@endphp

{{-- ── Header ──────────────────────────────────────────────────────────── --}}
<div class="flex items-center gap-3 mb-4">
    <a href="{{ route('admin.attendance.session', $session->id) }}"
       class="rounded-full bg-gray-100 hover:bg-gray-200 p-2 transition flex-shrink-0">
        <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
    </a>
    <div class="flex-1 min-w-0">
        <h1 class="admin-h1 truncate">{{ $session->event->title }}</h1>
        <p class="text-sm text-gray-500">
            {{ \Carbon\Carbon::parse($session->attendance_date)->format('D, d M Y') }}
        </p>
    </div>
    <div class="flex-shrink-0 text-right">
        <p id="checkin-count"
           class="text-2xl font-bold text-blue-600">{{ $count }}</p>
        <p class="text-xs text-gray-400 leading-tight">checked in</p>
    </div>
</div>

@if($locked)
<div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 flex items-center gap-2">
    <i class="fas fa-lock"></i>
    Session is locked — check-ins are disabled.
</div>
@endif

@unless($locked)
{{-- ── Mode tabs ────────────────────────────────────────────────────────── --}}
<div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-4 overflow-hidden">

    <div class="flex border-b border-gray-200">
        <button id="tab-search-btn"
                class="flex-1 py-3 text-sm font-medium flex items-center justify-center gap-2 transition border-b-2 border-blue-600 text-blue-600">
            <i class="fas fa-search text-xs"></i> Search
        </button>
        <button id="tab-scan-btn"
                class="flex-1 py-3 text-sm font-medium flex items-center justify-center gap-2 transition border-b-2 border-transparent text-gray-500 hover:text-gray-700">
            <i class="fas fa-qrcode text-xs"></i> Scan QR
        </button>
    </div>

    {{-- ── Search tab ──────────────────────────────────────────────────── --}}
    <div id="tab-search" class="p-4">
        <div class="relative mb-3">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" id="member-search"
                   placeholder="Type a name to search…"
                   autocomplete="off"
                   class="tw-form-control w-full pl-9 pr-4">
        </div>
        <div id="search-results" class="space-y-2"></div>
    </div>

    {{-- ── Scan tab ─────────────────────────────────────────────────────── --}}
    <div id="tab-scan" class="hidden p-4">
        <div class="relative rounded-lg overflow-hidden bg-black mb-3" style="aspect-ratio:4/3;max-height:55vh">
            <video id="qr-video" class="w-full h-full object-cover" playsinline muted></video>
            <canvas id="qr-canvas" class="hidden"></canvas>
            {{-- Scanning crosshair overlay --}}
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div class="w-48 h-48 border-2 border-white border-opacity-60 rounded-lg relative">
                    <span class="absolute top-0 left-0 w-5 h-5 border-t-2 border-l-2 border-white rounded-tl"></span>
                    <span class="absolute top-0 right-0 w-5 h-5 border-t-2 border-r-2 border-white rounded-tr"></span>
                    <span class="absolute bottom-0 left-0 w-5 h-5 border-b-2 border-l-2 border-white rounded-bl"></span>
                    <span class="absolute bottom-0 right-0 w-5 h-5 border-b-2 border-r-2 border-white rounded-br"></span>
                </div>
            </div>
        </div>
        <p id="scan-status" class="text-sm text-center text-gray-500">
            Point the camera at a member's QR code
        </p>
        <div id="scan-feedback" class="hidden mt-3"></div>
        <div class="mt-3 text-center">
            <button id="start-camera-btn"
                    class="text-sm px-4 py-2 rounded btn btn-primary submit-btn">
                <i class="fas fa-camera mr-1.5"></i> Start Camera
            </button>
        </div>
    </div>

</div>
@endunless

{{-- ── Checked-in list ─────────────────────────────────────────────────── --}}
<div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-700">
            Checked In
            <span id="list-count" class="ml-1 text-xs bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded-full">{{ $count }}</span>
        </h3>
    </div>

    <ul id="checkin-list" class="divide-y divide-gray-100">
        @forelse($recentAttendees as $att)
        @php
            $profile  = $att->member?->userprofile;
            $fullName = $profile ? trim($profile->firstname . ' ' . $profile->lastname) : $att->member?->name;
            $avatar   = $profile?->AvatarPath;
        @endphp
        <li class="flex items-center gap-3 px-4 py-3" data-uid="{{ $att->user_id }}">
            @if($avatar)
                <img src="{{ $avatar }}" class="w-9 h-9 rounded-full object-cover flex-shrink-0">
            @else
                <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <span class="text-blue-600 text-sm font-semibold">{{ strtoupper(substr($fullName ?? '?', 0, 1)) }}</span>
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-800 truncate">{{ $fullName }}</p>
                <p class="text-xs text-gray-400">{{ optional($att->scanned_at)->format('h:i A') }}</p>
            </div>
            @unless($locked)
            <button class="undo-btn flex-shrink-0 text-xs text-gray-400 hover:text-red-500 transition"
                    data-uid="{{ $att->user_id }}" title="Undo check-in">
                <i class="fas fa-times"></i>
            </button>
            @endunless
        </li>
        @empty
        <li id="empty-msg" class="px-4 py-8 text-center text-sm text-gray-400">
            No check-ins yet.
        </li>
        @endforelse
    </ul>
</div>

@endsection

@push('scripts')
{{-- jsQR for camera QR decoding --}}
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
<script>
(function () {
    var CSRF      = document.querySelector('meta[name="csrf-token"]').content;
    var MARK_URL  = '{{ route('admin.attendance.mark', $session->id) }}';
    var SEARCH_URL = '{{ route('admin.attendance.search', $session->id) }}';
    var REMOVE_URL = '{{ url('/admin/events/attendance/session/' . $session->id . '/attendee') }}';
    var locked    = {{ $locked ? 'true' : 'false' }};

    // ── Tab switching ────────────────────────────────────────────────────
    var tabSearchBtn = document.getElementById('tab-search-btn');
    var tabScanBtn   = document.getElementById('tab-scan-btn');
    var tabSearch    = document.getElementById('tab-search');
    var tabScan      = document.getElementById('tab-scan');

    if (tabSearchBtn) {
        tabSearchBtn.addEventListener('click', function () {
            tabSearch.classList.remove('hidden');
            tabScan.classList.add('hidden');
            tabSearchBtn.classList.add('border-blue-600', 'text-blue-600');
            tabSearchBtn.classList.remove('border-transparent', 'text-gray-500');
            tabScanBtn.classList.remove('border-blue-600', 'text-blue-600');
            tabScanBtn.classList.add('border-transparent', 'text-gray-500');
            stopCamera();
        });

        tabScanBtn.addEventListener('click', function () {
            tabScan.classList.remove('hidden');
            tabSearch.classList.add('hidden');
            tabScanBtn.classList.add('border-blue-600', 'text-blue-600');
            tabScanBtn.classList.remove('border-transparent', 'text-gray-500');
            tabSearchBtn.classList.remove('border-blue-600', 'text-blue-600');
            tabSearchBtn.classList.add('border-transparent', 'text-gray-500');
        });
    }

    // ── Search ───────────────────────────────────────────────────────────
    var searchInput   = document.getElementById('member-search');
    var searchResults = document.getElementById('search-results');
    var searchTimer;

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimer);
            var q = searchInput.value.trim();
            if (q.length < 2) { searchResults.innerHTML = ''; return; }
            searchTimer = setTimeout(function () { doSearch(q); }, 300);
        });
    }

    function doSearch(q) {
        fetch(SEARCH_URL + '?q=' + encodeURIComponent(q), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.json(); })
        .then(function (members) {
            searchResults.innerHTML = '';
            if (!members.length) {
                searchResults.innerHTML = '<p class="text-sm text-gray-400 text-center py-3">No members found.</p>';
                return;
            }
            members.forEach(function (m) {
                var el = buildMemberCard(m, 'search');
                searchResults.appendChild(el);
            });
        });
    }

    function buildMemberCard(m, source) {
        var wrap = document.createElement('div');
        wrap.className = 'flex items-center gap-3 px-3 py-2.5 rounded-lg border transition ' +
            (m.checked_in
                ? 'bg-green-50 border-green-200'
                : 'bg-white border-gray-200 hover:border-blue-300 hover:bg-blue-50 cursor-pointer');
        wrap.dataset.uid = m.id;

        var avatarHtml = m.avatar
            ? '<img src="' + m.avatar + '" class="w-9 h-9 rounded-full object-cover flex-shrink-0">'
            : '<div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0 text-sm font-semibold text-gray-500">' + (m.full_name || m.username || '?').charAt(0).toUpperCase() + '</div>';

        var badge = m.checked_in
            ? '<span class="text-xs text-green-600 font-medium flex items-center gap-1"><i class="fas fa-check-circle"></i> Checked in</span>'
            : '<span class="text-xs text-gray-400">Tap to check in</span>';

        wrap.innerHTML = avatarHtml +
            '<div class="flex-1 min-w-0">' +
                '<p class="text-sm font-medium text-gray-800 truncate">' + escHtml(m.full_name || m.username) + '</p>' +
                badge +
            '</div>';

        if (!m.checked_in) {
            wrap.addEventListener('click', function () {
                markCheckin({ user_id: m.id }, wrap);
            });
        }

        return wrap;
    }

    // ── Mark check-in ────────────────────────────────────────────────────
    function markCheckin(payload, cardEl) {
        fetch(MARK_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(payload),
        })
        .then(function (r) { return r.json().then(function (d) { return { status: r.status, data: d }; }); })
        .then(function (res) {
            if (res.status === 409) {
                showFeedback('already', res.data.member);
                return;
            }
            if (res.data.error) {
                showFeedback('error', null, res.data.error);
                return;
            }
            if (res.data.success) {
                prependCheckin(res.data.member);
                showFeedback('success', res.data.member);
                if (cardEl) markCardDone(cardEl);
            }
        })
        .catch(function () { showFeedback('error', null, 'Network error. Please try again.'); });
    }

    function markCardDone(cardEl) {
        cardEl.className = 'flex items-center gap-3 px-3 py-2.5 rounded-lg border bg-green-50 border-green-200 transition';
        var badge = cardEl.querySelector('span');
        if (badge) badge.outerHTML = '<span class="text-xs text-green-600 font-medium flex items-center gap-1"><i class="fas fa-check-circle"></i> Checked in</span>';
        cardEl.style.cursor = 'default';
        cardEl.replaceWith(cardEl.cloneNode(true)); // remove click listener
    }

    function prependCheckin(m) {
        var emptyMsg = document.getElementById('empty-msg');
        if (emptyMsg) emptyMsg.remove();

        var li = document.createElement('li');
        li.className = 'flex items-center gap-3 px-4 py-3 bg-green-50 animate-pulse-once';
        li.dataset.uid = m.id;

        var avatarHtml = m.avatar
            ? '<img src="' + m.avatar + '" class="w-9 h-9 rounded-full object-cover flex-shrink-0">'
            : '<div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0"><span class="text-blue-600 text-sm font-semibold">' + (m.full_name || '?').charAt(0).toUpperCase() + '</span></div>';

        li.innerHTML = avatarHtml +
            '<div class="flex-1 min-w-0">' +
                '<p class="text-sm font-medium text-gray-800 truncate">' + escHtml(m.full_name || m.username) + '</p>' +
                '<p class="text-xs text-gray-400">' + (m.scanned_at || '') + '</p>' +
            '</div>' +
            '<button class="undo-btn flex-shrink-0 text-xs text-gray-400 hover:text-red-500 transition" data-uid="' + m.id + '" title="Undo check-in"><i class="fas fa-times"></i></button>';

        var list = document.getElementById('checkin-list');
        list.insertBefore(li, list.firstChild);

        setTimeout(function () { li.classList.remove('bg-green-50'); }, 2000);

        // Update count
        var countEl  = document.getElementById('checkin-count');
        var listCount = document.getElementById('list-count');
        var n = parseInt(countEl.textContent) + 1;
        countEl.textContent = n;
        listCount.textContent = n;
    }

    // ── Undo / remove ────────────────────────────────────────────────────
    document.getElementById('checkin-list').addEventListener('click', function (e) {
        var btn = e.target.closest('.undo-btn');
        if (!btn || locked) return;
        var uid = btn.dataset.uid;
        if (!confirm('Remove this check-in?')) return;

        fetch(REMOVE_URL + '/' + uid, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (d.success) {
                var li = document.querySelector('#checkin-list li[data-uid="' + uid + '"]');
                if (li) li.remove();
                var countEl  = document.getElementById('checkin-count');
                var listCount = document.getElementById('list-count');
                var n = Math.max(0, parseInt(countEl.textContent) - 1);
                countEl.textContent = n;
                listCount.textContent = n;
                if (!document.querySelector('#checkin-list li')) {
                    document.getElementById('checkin-list').innerHTML =
                        '<li id="empty-msg" class="px-4 py-8 text-center text-sm text-gray-400">No check-ins yet.</li>';
                }
            }
        });
    });

    // ── Feedback toast ───────────────────────────────────────────────────
    var toastEl = (function () {
        var el = document.createElement('div');
        el.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 z-50 hidden px-5 py-3 rounded-xl shadow-lg text-sm font-medium text-white max-w-xs text-center';
        document.body.appendChild(el);
        return el;
    })();

    var scanFeedback = document.getElementById('scan-feedback');

    function showFeedback(type, member, msg) {
        var color = type === 'success' ? '#16a34a' : type === 'already' ? '#2563eb' : '#dc2626';
        var text  = type === 'success'  ? '✓ ' + (member?.full_name || '') + ' checked in!'
                  : type === 'already'  ? (member?.full_name || '') + ' already checked in at ' + (member?.scanned_at || '')
                  : (msg || 'Error.');

        // Toast
        toastEl.style.background = color;
        toastEl.textContent = text;
        toastEl.classList.remove('hidden');
        clearTimeout(toastEl._timer);
        toastEl._timer = setTimeout(function () { toastEl.classList.add('hidden'); }, 3000);

        // Inline scan feedback
        if (scanFeedback && !tabScan.classList.contains('hidden')) {
            scanFeedback.className = 'mt-3 px-4 py-3 rounded-lg text-sm text-center ' +
                (type === 'success' ? 'bg-green-100 text-green-700' : type === 'already' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700');
            scanFeedback.textContent = text;
            scanFeedback.classList.remove('hidden');
            setTimeout(function () { scanFeedback.classList.add('hidden'); }, 3000);
        }
    }

    // ── Camera / QR scan ─────────────────────────────────────────────────
    var video       = document.getElementById('qr-video');
    var canvas      = document.getElementById('qr-canvas');
    var startBtn    = document.getElementById('start-camera-btn');
    var scanStatus  = document.getElementById('scan-status');
    var stream      = null;
    var scanning    = false;
    var lastScanned = null;
    var lastTime    = 0;

    if (startBtn) {
        startBtn.addEventListener('click', startCamera);
    }

    function startCamera() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            scanStatus.textContent = 'Camera not supported on this device/browser.';
            return;
        }
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
            .then(function (s) {
                stream = s;
                video.srcObject = s;
                video.play();
                scanning = true;
                startBtn.classList.add('hidden');
                scanStatus.textContent = 'Scanning… point at a QR code.';
                requestAnimationFrame(scanFrame);
            })
            .catch(function () {
                scanStatus.textContent = 'Could not access camera. Check browser permissions.';
            });
    }

    function stopCamera() {
        scanning = false;
        if (stream) {
            stream.getTracks().forEach(function (t) { t.stop(); });
            stream = null;
        }
        if (video) video.srcObject = null;
        if (startBtn) startBtn.classList.remove('hidden');
    }

    function scanFrame() {
        if (!scanning) return;
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            canvas.width  = video.videoWidth;
            canvas.height = video.videoHeight;
            var ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            var imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            var code = jsQR(imageData.data, imageData.width, imageData.height, { inversionAttempts: 'dontInvert' });

            if (code) {
                var now = Date.now();
                // Debounce: skip same code within 4 seconds
                if (code.data === lastScanned && now - lastTime < 4000) {
                    requestAnimationFrame(scanFrame);
                    return;
                }
                lastScanned = code.data;
                lastTime    = now;

                var username = extractUsername(code.data);
                if (username) {
                    scanStatus.textContent = 'QR detected: ' + username;
                    markCheckin({ username: username }, null);
                } else {
                    scanStatus.textContent = 'Unrecognised QR code.';
                }
            }
        }
        requestAnimationFrame(scanFrame);
    }

    function extractUsername(qrValue) {
        // Member cards encode: url('/admin/attandance/{username}') or similar URL
        try {
            var url   = new URL(qrValue);
            var parts = url.pathname.replace(/\/$/, '').split('/');
            return parts[parts.length - 1] || null;
        } catch (e) {
            // Not a URL — treat whole value as username
            return qrValue.trim() || null;
        }
    }

    function escHtml(s) {
        return String(s || '').replace(/[&<>"']/g, function (c) {
            return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];
        });
    }

    // Stop camera when leaving page
    window.addEventListener('beforeunload', stopCamera);
})();
</script>
@endpush
