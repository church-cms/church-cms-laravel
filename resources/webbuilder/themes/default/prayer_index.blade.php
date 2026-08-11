@extends('theme::layout')

@section('title', 'Prayer Board')

@section('content')

{{-- ═══════════════════════════════════════════════════════
     HERO SECTION
═══════════════════════════════════════════════════════ --}}
@include('theme::_hero_banner', [
    'heroTitle'    => 'Prayer Board',
    'heroSubtitle' => 'You are not alone. Share your heart, and let the community stand with you in prayer.',
    'heroIcon'     => '🙏',
    'breadcrumbs'  => [
        ['label' => 'Home', 'url' => route('web.home')],
        ['label' => 'Prayer Board'],
    ],
])

{{-- ═══════════════════════════════════════════════════════
     TWO-COLUMN: INFO + PRAYER CARDS
═══════════════════════════════════════════════════════ --}}
<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        {{-- LEFT — Process info block --}}
        <aside class="lg:col-span-1 self-start bg-gray-100 rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="sticky top-6 space-y-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-1">How It Works</h2>
                    <p class="text-sm text-gray-500">We keep the prayer board safe, sincere and spirit-filled.</p>
                </div>

                <ol class="space-y-5">
                    <li class="flex gap-4">
                        <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm flex-shrink-0">1</div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Submit Your Request</p>
                            <p class="text-xs text-gray-500 mt-0.5">Write a brief prayer request (10–500 characters). You can submit anonymously or as a member.</p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <div class="w-9 h-9 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 font-bold text-sm flex-shrink-0">2</div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Admin Review</p>
                            <p class="text-xs text-gray-500 mt-0.5">Our team reviews all submissions to keep the board edifying and appropriate for the community.</p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-sm flex-shrink-0">3</div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Published to the Board</p>
                            <p class="text-xs text-gray-500 mt-0.5">Once approved, your request appears here so the whole congregation can lift it in prayer.</p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <div class="w-9 h-9 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-700 font-bold text-sm flex-shrink-0">4</div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Testimony</p>
                            <p class="text-xs text-gray-500 mt-0.5">When God answers, let us know! Answered prayers are archived as a testimony of His faithfulness.</p>
                        </div>
                    </li>
                </ol>

                <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4">
                    <p class="text-xs font-bold text-indigo-800 uppercase mb-1">A word of encouragement</p>
                    <p class="text-sm text-indigo-900 italic leading-relaxed">
                        "Do not be anxious about anything, but in every situation, by prayer and petition, with thanksgiving, present your requests to God."
                    </p>
                    <p class="text-xs text-indigo-600 mt-2 font-semibold">— Philippians 4:6</p>
                </div>

                <a href="#submit-prayer"
                   class="block w-full text-center py-3 rounded-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 transition text-sm">
                    🙏 Submit a Prayer Request
                </a>
            </div>
        </aside>

        {{-- RIGHT — Prayer cards --}}
        <main class="lg:col-span-2">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Community Prayer Requests</h2>



            @forelse($requests as $item)
                @include('theme::_card_prayer_item', ['item' => $item])
            @empty
            <div class="text-center py-20 text-gray-400 border-2 border-dashed border-gray-200 rounded-xl">
                <p class="text-5xl mb-3">🙏</p>
                <p class="font-semibold text-gray-600">No prayer requests yet.</p>
                <p class="text-sm mt-1">Be the first — use the form below.</p>
            </div>
            @endforelse

            @if($requests->hasPages())
            <div class="mt-8">{{ $requests->links() }}</div>
            @endif
        </main>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     FULL-WIDTH SUBMIT SECTION
═══════════════════════════════════════════════════════ --}}
<section id="submit-prayer" class="bg-gradient-to-br from-indigo-50 to-purple-100 border-t border-indigo-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

            {{-- LEFT — Invitation copy --}}
            <div class="flex flex-col justify-center">
                <p class="text-indigo-600 text-sm font-bold uppercase tracking-wider mb-2">Share Your Heart</p>
                <h2 class="text-3xl font-extrabold text-gray-900 leading-tight mb-4">
                    Let Us Pray<br>With You
                </h2>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    Whatever you are facing — illness, grief, uncertainty, or simply gratitude — our community is here. Submit your prayer request and let hundreds of believers stand alongside you before the throne of grace.
                </p>

                <ul class="space-y-3 text-sm text-gray-700">
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 bg-green-100 text-green-700 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">✓</span>
                        <span>All submissions reviewed before publishing — your dignity is protected.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 bg-green-100 text-green-700 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">✓</span>
                        <span>Prayers remain active on the board for up to 60 days.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 bg-green-100 text-green-700 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">✓</span>
                        <span>When God answers, share your testimony and encourage the community.</span>
                    </li>
                </ul>
            </div>

            {{-- RIGHT — Form --}}
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-indigo-100">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-5">
                    <h3 class="text-white font-bold text-lg">Submit a Prayer Request</h3>
                    <p class="text-indigo-200 text-xs mt-0.5">Reviewed &amp; published within 24 hours.</p>
                </div>

                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6 text-sm flex items-start gap-2">
                        <span class="text-lg">✅</span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <div class="p-6">
                    @auth
                    @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-5 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('web.prayer.store') }}" method="POST">
                        @csrf
                        <div class="mb-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Your Prayer Request <span class="text-red-500">*</span>
                            </label>
                            <textarea name="text" rows="5" required minlength="10" maxlength="500"
                                      placeholder="Share what you'd like the community to pray for..."
                                      class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 resize-none">{{ old('text') }}</textarea>
                            <p class="text-xs text-gray-400 mt-1">Between 10 and 500 characters.</p>
                        </div>

                        @if(isset($categories) && $categories->count())
                        <div class="mb-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Category <span class="text-gray-400 font-normal">(optional)</span>
                            </label>
                            <select name="category_id"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-white">
                                <option value="">— Select a category —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->display_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <button type="submit"
                                class="w-full py-3 rounded-xl font-bold transition flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 text-sm">
                            <span class="text-base">🙏</span>
                            <span>Submit Prayer Request</span>
                        </button>
                    </form>
                    @else
                    <div class="text-center py-6 space-y-4">
                        <p class="text-gray-600 text-sm">You need to be logged in to submit a prayer request.</p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="{{ route('web.guest.register') }}"
                               class="px-5 py-2.5 rounded-xl font-semibold bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 text-sm text-center">
                                Register Free
                            </a>
                            <a href="{{ route('web.guest.login') }}"
                               class="px-5 py-2.5 rounded-xl font-semibold border border-indigo-300 text-indigo-700 hover:bg-indigo-50 text-sm text-center">
                                Login
                            </a>
                        </div>
                    </div>
                    @endauth
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

