@php
    $prayerId = $item->id;
    $catName  = strtolower(optional($item->category)->name ?? '');

    // Spec-defined category → button gradient mapping
    $gradMap = [
        'health'     => ['#EF4444', '#EC4899'],
        'family'     => ['#EC4899', '#A855F7'],
        'employment' => ['#3B82F6', '#06B6D4'],
        'work'       => ['#3B82F6', '#06B6D4'],
        'financial'  => ['#FBBF24', '#F97316'],
        'finance'    => ['#FBBF24', '#F97316'],
        'personal'   => ['#A855F7', '#6366F1'],
    ];

    $grad     = $gradMap[$catName] ?? ['#6366F1', '#7C3AED'];
    $gradFrom = $grad[0];
    $gradTo   = $grad[1];
    $liftUrl  = route('web.prayer.lift', $prayerId);
    $catLabel = $item->category ? $item->category->name : 'prayer';
@endphp

{{-- Inject CSRF meta once across all cards --}}
@once
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
.lift-prayer-btn {
    transition: transform 200ms ease-out, box-shadow 200ms ease-out, opacity 300ms ease-out;
    will-change: transform, box-shadow;
}
.lift-prayer-btn:not([disabled]):hover {
    transform: translateY(-2px) scale(1.03);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.18);
    filter: brightness(0.88);
}
.lift-prayer-btn:focus-visible {
    outline: 2px solid #6366F1;
    outline-offset: 3px;
}
@keyframes liftSpin {
    to { transform: rotate(360deg); }
}
.lift-spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255,255,255,0.4);
    border-top-color: #fff;
    border-radius: 50%;
    animation: liftSpin 0.8s linear infinite;
    flex-shrink: 0;
}
</style>
@endonce

<div id="lift-area-{{ $prayerId }}">
    {{-- Toast for error / duplicate messages --}}
    <div id="lift-toast-{{ $prayerId }}"
         class="hidden mb-2 text-xs text-center font-semibold px-3 py-2 rounded-lg"
         role="alert"
         aria-live="polite"></div>

    <button
        id="lift-prayer-btn-{{ $prayerId }}"
        class="lift-prayer-btn w-full py-3 px-6 rounded-full font-semibold text-white text-sm flex items-center justify-center gap-2 cursor-pointer"
        style="background: linear-gradient(to right, {{ $gradFrom }}, {{ $gradTo }});"
        data-prayer-id="{{ $prayerId }}"
        data-grad-from="{{ $gradFrom }}"
        data-grad-to="{{ $gradTo }}"
        data-lift-url="{{ $liftUrl }}"
        data-is-submitted="false"
        aria-label="Lift prayer for {{ $catLabel }}"
        role="button"
        onclick="liftPrayer('{{ $prayerId }}', this)">
        <span class="btn-icon">🙏</span>
        <span class="btn-label">Lift Prayer</span>
    </button>
</div>

@once
<script>
function liftPrayer(prayerId, btn) {
    if (btn.disabled || btn.dataset.isSubmitted === 'true') return;

    var csrfToken = document.querySelector('meta[name="csrf-token"]');
    csrfToken = csrfToken ? csrfToken.getAttribute('content') : '';

    setLiftBtnState(btn, 'submitting');
    hideLiftToast(prayerId);

    fetch(btn.dataset.liftUrl, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN':  csrfToken,
            'Accept':        'application/json',
            'Content-Type':  'application/json',
        },
    })
    .then(function (res) {
        return res.json().then(function (data) {
            return { status: res.status, data: data };
        });
    })
    .then(function (result) {
        var status = result.status;
        var data   = result.data;

        if (status === 200 || status === 201 || data.success) {
            // 3A — Success
            setLiftBtnState(btn, 'success');
            btn.dataset.isSubmitted = 'true';

            // Update participant counts in the card without page reload
            if (data.participant_breakdown) {
                var bd = data.participant_breakdown;
                updateCount(prayerId, 'member',    bd.members);
                updateCount(prayerId, 'guest',     bd.guests);
                updateCount(prayerId, 'anonymous', bd.anonymous);
                updateCount(prayerId, 'total',     bd.total);
            }

            // Fade out after 2 s, stay hidden until next interaction
            setTimeout(function () {
                btn.style.transition = 'opacity 1s ease-out';
                btn.style.opacity    = '0';
            }, 2000);

        } else if (status === 403 || data.code === 'DUPLICATE_PARTICIPATION') {
            // Already prayed
            setLiftBtnState(btn, 'already');
            btn.dataset.isSubmitted = 'true';
            showLiftToast(prayerId, 'You\'ve already lifted this prayer', 'gray');

        } else if (status === 422) {
            // Prayer no longer active
            setLiftBtnState(btn, 'disabled');
            showLiftToast(prayerId, 'This prayer is no longer active', 'red');

        } else if (status === 401) {
            // Not authenticated
            setLiftBtnState(btn, 'auth');

        } else if (status === 404) {
            // Prayer gone — hide area entirely
            var area = document.getElementById('lift-area-' + prayerId);
            if (area) area.style.display = 'none';

        } else {
            // Generic server error
            setLiftBtnState(btn, 'error');
            showLiftToast(prayerId, 'Could not record. Please try again.', 'red');
        }
    })
    .catch(function () {
        // Network error — allow retry
        setLiftBtnState(btn, 'error');
        showLiftToast(prayerId, 'Network error. Try again?', 'red');
    });
}

function setLiftBtnState(btn, state) {
    var gradFrom = btn.dataset.gradFrom;
    var gradTo   = btn.dataset.gradTo;
    var spinner  = '<span class="lift-spinner" aria-hidden="true"></span>';

    btn.style.transition = 'transform 200ms ease-out, box-shadow 200ms ease-out, opacity 300ms ease-out';
    btn.style.opacity    = '1';
    btn.disabled         = false;
    btn.setAttribute('aria-busy',     'false');
    btn.setAttribute('aria-disabled', 'false');

    switch (state) {
        case 'submitting':
            btn.innerHTML = spinner + '<span>Submitting\u2026</span>';
            btn.style.background = 'linear-gradient(to right, ' + gradFrom + ', ' + gradTo + ')';
            btn.style.opacity    = '0.75';
            btn.disabled         = true;
            btn.setAttribute('aria-busy',     'true');
            btn.setAttribute('aria-disabled', 'true');
            break;

        case 'success':
            btn.innerHTML = '<span>\u2713</span><span>Thank you for Praying</span>';
            btn.style.background = 'linear-gradient(to right, #10B981, #059669)';
            btn.disabled         = true;
            btn.setAttribute('aria-disabled', 'true');
            break;

        case 'error':
            btn.innerHTML = '<span>\u274C</span><span>Could not record (try again)</span>';
            btn.style.background = 'linear-gradient(to right, #EF4444, #DC2626)';
            break;

        case 'already':
            btn.innerHTML = '<span>\u2713</span><span>Already Praying</span>';
            btn.style.background = 'linear-gradient(to right, #9CA3AF, #6B7280)';
            btn.disabled         = true;
            btn.setAttribute('aria-disabled', 'true');
            break;

        case 'disabled':
            btn.innerHTML = '<span>\uD83D\uDEAB</span><span>Prayer Ended</span>';
            btn.style.background = 'linear-gradient(to right, #9CA3AF, #6B7280)';
            btn.disabled         = true;
            btn.setAttribute('aria-disabled', 'true');
            break;

        case 'auth':
            btn.innerHTML = '<span>\uD83D\uDD12</span><span>Please sign in to pray</span>';
            btn.style.background = 'linear-gradient(to right, #F59E0B, #D97706)';
            break;

        default: // reset
            btn.innerHTML = '<span>\uD83D\uDE4F</span><span>Lift Prayer</span>';
            btn.style.background = 'linear-gradient(to right, ' + gradFrom + ', ' + gradTo + ')';
    }
}

function showLiftToast(prayerId, msg, color) {
    var toast = document.getElementById('lift-toast-' + prayerId);
    if (!toast) return;
    toast.textContent = msg;
    toast.className = 'mb-2 text-xs text-center font-semibold px-3 py-2 rounded-lg ' +
        (color === 'red'
            ? 'bg-red-50 text-red-700 border border-red-200'
            : 'bg-gray-100 text-gray-600 border border-gray-200');
}

function hideLiftToast(prayerId) {
    var toast = document.getElementById('lift-toast-' + prayerId);
    if (toast) toast.className = 'hidden';
}

function updateCount(prayerId, type, value) {
    if (value === undefined || value === null) return;
    var el = document.querySelector('[data-prayer-' + type + '="' + prayerId + '"]');
    if (el) el.textContent = value;
}
</script>
@endonce
