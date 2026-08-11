@extends('layouts.admin.layout')

@section('content')
<div class="flex flex-row justify-between items-center mb-4">
    <h1 class="admin-h1">Prayer Board</h1>
    <a href="{{ url('/admin/prayercategories') }}"
        class="no-underline text-white px-4 py-1 flex items-center custom-green rounded text-sm font-semibold">
        Manage Categories
    </a>
</div>

{{-- Stats bar --}}
<div class="grid grid-cols-5 gap-3 mb-6">
    @php
    $tabs = [
    'pending' => ['label' => 'Pending', 'icon' => '⏳', 'color' => 'yellow'],
    'active' => ['label' => 'Active', 'icon' => '🟢', 'color' => 'green'],
    'answered' => ['label' => 'Answered', 'icon' => '✓', 'color' => 'blue'],
    'ended' => ['label' => 'Ended', 'icon' => '⏰', 'color' => 'gray'],
    'rejected' => ['label' => 'Rejected', 'icon' => '✗', 'color' => 'red'],
    ];
    $colorMap = [
    'yellow' => 'bg-yellow-50 border-yellow-300 text-yellow-800',
    'green' => 'bg-green-50 border-green-300 text-green-800',
    'blue' => 'bg-blue-50 border-blue-300 text-blue-800',
    'gray' => 'bg-gray-50 border-gray-300 text-gray-700',
    'red' => 'bg-red-50 border-red-300 text-red-800',
    ];
    @endphp

    @foreach($tabs as $key => $tab)
    <button onclick="switchTab('{{ $key }}')"
        id="stat-{{ $key }}"
        class="stat-btn border rounded-lg p-3 text-center cursor-pointer transition {{ $colorMap[$tab['color']] }} {{ $key === 'pending' ? 'ring-2 ring-offset-1 ring-indigo-400' : '' }}">
        <div class="text-xl">{{ $tab['icon'] }}</div>
        <div class="text-2xl font-bold">{{ $counts[$key] ?? 0 }}</div>
        <div class="text-xs font-medium">{{ $tab['label'] }}</div>
    </button>
    @endforeach
</div>

{{-- Tab content --}}
<div id="tab-content" class="bg-white shadow rounded">
    <div id="tab-loading" class="hidden text-center py-12 text-gray-400">
        <div class="text-2xl mb-2">⏳</div>
        <div class="text-sm">Loading prayers...</div>
    </div>
    <div id="tab-body"></div>
</div>

<script>
    var currentTab = 'pending';

    function switchTab(status) {
        if (currentTab === status) return;
        currentTab = status;

        // Update stat button highlights
        document.querySelectorAll('.stat-btn').forEach(function(btn) {
            btn.classList.remove('ring-2', 'ring-offset-1', 'ring-indigo-400');
        });
        var activeBtn = document.getElementById('stat-' + status);
        if (activeBtn) {
            activeBtn.classList.add('ring-2', 'ring-offset-1', 'ring-indigo-400');
        }

        loadTab(status);
    }

    function loadTab(status) {
        var loading = document.getElementById('tab-loading');
        var body = document.getElementById('tab-body');
        loading.classList.remove('hidden');
        body.innerHTML = '';

        var url = '{{ url("/admin/prayerboard/list") }}/' + status;
        fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function(res) {
                return res.text();
            })
            .then(function(html) {
                loading.classList.add('hidden');
                body.innerHTML = html;
                attachTabEvents();
            })
            .catch(function() {
                loading.classList.add('hidden');
                body.innerHTML = '<div class="p-8 text-center text-red-500">Failed to load. Please refresh the page.</div>';
            });
    }

    function attachTabEvents() {
        // Pagination links — intercept to reload within tab
        document.querySelectorAll('#tab-body .pagination a').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                var url = this.href;
                var loading = document.getElementById('tab-loading');
                var body = document.getElementById('tab-body');
                loading.classList.remove('hidden');
                body.innerHTML = '';

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(function(r) {
                        return r.text();
                    })
                    .then(function(html) {
                        loading.classList.add('hidden');
                        body.innerHTML = html;
                        attachTabEvents();
                    });
            });
        });
    }

    // Load default tab on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadTab('pending');
    });
</script>

<script>
    (function() {
        // Expiry preview update
        document.querySelectorAll('[id^="expiry-"]').forEach(function(sel) {
            sel.addEventListener('change', function() {
                var id = this.id.replace('expiry-', '');
                var days = parseInt(this.value);
                var d = new Date();
                d.setDate(d.getDate() + days);
                var preview = document.getElementById('expiry-preview-' + id);
                if (preview) {
                    preview.textContent = 'Expires: ' + d.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                }
            });
        });

        window.approvePrayer = function(id) {
            var expiryDays = document.getElementById('expiry-' + id).value;
            var text = document.getElementById('text-' + id).value;
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('{{ url("/admin/prayerboard") }}/' + id + '/approve', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        expiry_days: parseInt(expiryDays),
                        text: text
                    }),
                })
                .then(function(r) {
                    return r.json();
                })
                .then(function(data) {
                    if (data.success) {
                        var card = document.getElementById('prayer-card-' + id);
                        if (card) card.remove();
                        showToast(data.message, 'green');
                        updateStatCount('pending', -1);
                        updateStatCount('active', 1);
                    } else {
                        showToast(data.message || 'Error', 'red');
                    }
                });
        };

        window.showRejectModal = function(id) {
            document.getElementById('rejectPrayerId').value = id;
            document.getElementById('rejectReason').value = '';
            document.getElementById('rejectError').classList.add('hidden');
            document.getElementById('rejectModal').classList.remove('hidden');
        };

        window.closeRejectModal = function() {
            document.getElementById('rejectModal').classList.add('hidden');
        };

        window.submitReject = function() {
            var id = document.getElementById('rejectPrayerId').value;
            var reason = document.getElementById('rejectReason').value.trim();
            if (!reason) {
                document.getElementById('rejectError').classList.remove('hidden');
                return;
            }

            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch('{{ url("/admin/prayerboard") }}/' + id + '/reject', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        reason: reason
                    }),
                })
                .then(function(r) {
                    return r.json();
                })
                .then(function(data) {
                    closeRejectModal();
                    if (data.success) {
                        var card = document.getElementById('prayer-card-' + id);
                        if (card) card.remove();
                        showToast(data.message, 'green');
                        updateStatCount('pending', -1);
                        updateStatCount('rejected', 1);
                    } else {
                        showToast(data.message || 'Error rejecting prayer.', 'red');
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

        function applyPendingFilter() {
            var form = document.getElementById('pending-filter');
            var params = new URLSearchParams();
            var catId = form.querySelector('[name="category_id"]').value;
            var month = form.querySelector('[name="month"]').value;
            if (catId) params.set('category_id', catId);
            if (month) params.set('month', month);

            var url = '{{ url("/admin/prayerboard/list/pending") }}?' + params.toString();
            var body = document.getElementById('tab-body');
            var loading = document.getElementById('tab-loading');
            loading.classList.remove('hidden');
            body.innerHTML = '';

            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(function(r) {
                    return r.text();
                })
                .then(function(html) {
                    loading.classList.add('hidden');
                    body.innerHTML = html;
                });
        }

        window.applyPendingFilter = applyPendingFilter;
    })();
</script>
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

@endsection