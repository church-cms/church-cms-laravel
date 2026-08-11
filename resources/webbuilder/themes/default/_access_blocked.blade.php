{{--
    Reusable "access blocked" card.
    Props:
      $blockedTitle   — heading text
      $blockedMessage — body text
--}}
<div class="max-w-lg mx-auto">
    <div class="bg-white border-2 border-amber-200 rounded-2xl overflow-hidden shadow-sm">

        <div class="bg-gradient-to-r from-amber-400 to-orange-400 px-6 py-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-white/30 flex items-center justify-center text-2xl flex-shrink-0">
                🔒
            </div>
            <div>
                <h2 class="text-white font-bold text-lg leading-tight">{{ $blockedTitle ?? 'Currently Unavailable' }}</h2>
                <p class="text-amber-100 text-xs mt-0.5">This feature has been temporarily disabled.</p>
            </div>
        </div>

        <div class="p-6 space-y-4">
            <p class="text-gray-600 text-sm leading-relaxed">
                {{ $blockedMessage ?? 'This feature is currently unavailable. Please check back later or contact the church office for assistance.' }}
            </p>

            <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 flex items-start gap-3">
                <span class="text-amber-500 text-lg flex-shrink-0 mt-0.5">ℹ️</span>
                <p class="text-xs text-amber-800 leading-relaxed">
                    If you believe this is an error, please reach out to the church team via the
                    <a href="{{ route('web.contact') }}" class="font-semibold underline hover:text-amber-900">contact page</a>.
                </p>
            </div>

            <a href="{{ route('web.home') }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Home
            </a>
        </div>

    </div>
</div>
