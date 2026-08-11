@extends('theme::layout')

@section('title', 'Guest Registration')

@section('content')

@include('theme::_hero_banner', [
'heroTitle' => 'Guest Registration',
'heroSubtitle' => 'Create a free account to connect with our community.',
'heroIcon' => '✋',
'breadcrumbs' => [
['label' => 'Home', 'url' => route('web.home')],
['label' => 'Guest Registration'],
],
])

<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    @if(session('auth_notice'))
    <div class="mb-6 bg-indigo-50 border border-indigo-200 text-indigo-800 text-sm rounded-lg px-4 py-3">
        {{ session('auth_notice') }}
    </div>
    @endif

    @if($blocked ?? false)
    @include('theme::_access_blocked', [
    'blockedTitle' => 'Guest Registration is Currently Closed',
    'blockedMessage' => 'New guest registrations are not being accepted at this time. Please check back later or contact the church office to be added manually.',
    ])
    @else
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">

        {{-- ── LEFT: Why Register ───────────────────────────────────── --}}
        <div class="lg:sticky lg:top-24 space-y-8">

            <div>
                <p class="text-indigo-600 text-sm font-bold uppercase tracking-wider mb-2">Free &amp; Takes 1 Minute</p>
                <h2 class="text-3xl font-extrabold text-gray-900 leading-tight mb-3">
                    Join Our Community<br>as a Guest
                </h2>
                <p class="text-gray-600 leading-relaxed">
                    You don't need to be a church member to participate. Register as a guest and start engaging with our community right away — no fees, no commitments.
                </p>
            </div>

            {{-- What you can do --}}
            <div>
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-4">What You Can Do After Registering</h3>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <span class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-base flex-shrink-0 mt-0.5">🙏</span>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Submit Prayer Requests</p>
                            <p class="text-xs text-gray-500 mt-0.5">Share your heart with the congregation and let the community pray alongside you.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-700 text-base flex-shrink-0 mt-0.5">🤝</span>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Post Help Requests</p>
                            <p class="text-xs text-gray-500 mt-0.5">Let the church know when you need support — practical, emotional, or spiritual.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-700 text-base flex-shrink-0 mt-0.5">💬</span>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Comment on Blog Posts</p>
                            <p class="text-xs text-gray-500 mt-0.5">Share your thoughts, ask questions, and engage with sermons and articles.</p>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- Why we ask --}}
            <div>
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-4">Why We Ask for Your Details</h3>
                <ul class="space-y-2.5 text-sm text-gray-600">
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-green-100 text-green-700 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">✓</span>
                        <span><strong class="text-gray-700">Name &amp; Gender</strong> — Helps us address you personally in communications.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-green-100 text-green-700 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">✓</span>
                        <span><strong class="text-gray-700">Date of Birth</strong> — Allows the church to send birthday blessings.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-green-100 text-green-700 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">✓</span>
                        <span><strong class="text-gray-700">Mobile &amp; Email</strong> — For account recovery and important church announcements only.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-green-100 text-green-700 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">✓</span>
                        <span><strong class="text-gray-700">Privacy Guaranteed</strong> — Your information is never shared with third parties.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-green-100 text-green-700 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">✓</span>
                        <span><strong class="text-gray-700">No Spam</strong> — We only contact you for matters directly relevant to the church.</span>
                    </li>
                </ul>
            </div>

            {{-- Scripture --}}
            <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4">
                <p class="text-xs font-bold text-indigo-800 uppercase mb-1">A Word of Welcome</p>
                <p class="text-sm text-indigo-900 italic leading-relaxed">
                    "Therefore welcome one another as Christ has welcomed you, for the glory of God."
                </p>
                <p class="text-xs text-indigo-600 mt-2 font-semibold">— Romans 15:7</p>
            </div>

        </div>

        {{-- ── RIGHT: Registration Form ─────────────────────────────── --}}
        <div>
            <div class="bg-white border border-gray-200 rounded-2xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-5">
                    <h2 class="text-white font-bold text-lg">Create Your Guest Account</h2>
                    <p class="text-indigo-200 text-xs mt-0.5">Free registration — no membership required.</p>
                </div>

                <div class="p-6">

                    @if($errors->any())
                    <div class="mb-5 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form id="guest-register-form" method="POST" action="{{ route('web.guest.register.store') }}"
                        data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}" novalidate>
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="firstname" value="{{ old('firstname') }}" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('firstname') ? 'border-red-400' : '' }}"
                                    placeholder="John">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Last Name</label>
                                <input type="text" name="lastname" value="{{ old('lastname') }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('lastname') ? 'border-red-400' : '' }}"
                                    placeholder="Doe">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Gender <span class="text-red-500">*</span></label>
                                <select name="gender" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('gender') ? 'border-red-400' : '' }}">
                                    <option value="">— Select —</option>
                                    <option value="male" {{ old('gender') === 'male'   ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') === 'other'  ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Date of Birth <span class="text-red-500">*</span></label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('date_of_birth') ? 'border-red-400' : '' }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Mobile Number <span class="text-red-500">*</span></label>
                            <div class="flex gap-2">
                                <select name="mobile_country_code"
                                    class="w-28 flex-shrink-0 border border-gray-300 rounded-lg px-2 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                    @forelse($countries as $country)
                                    <option value="{{ $country->tel_prefix }}"
                                        {{ old('mobile_country_code', '91') === $country->tel_prefix ? 'selected' : '' }}>
                                        {{ $country->short_name ?? $country->name }} +{{ $country->tel_prefix }}
                                    </option>
                                    @empty
                                    <option value="+91" selected>India +91</option>
                                    @endforelse
                                </select>
                                <input type="tel" name="mobile_no" value="{{ old('mobile_no') }}" required maxlength="10"
                                    class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('mobile_no') ? 'border-red-400' : '' }}"
                                    placeholder="10-digit mobile number">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('email') ? 'border-red-400' : '' }}"
                                placeholder="your@email.com">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Password <span class="text-red-500">*</span></label>
                                <input type="password" name="password" required minlength="8"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('password') ? 'border-red-400' : '' }}"
                                    placeholder="Min. 8 characters">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                                <input type="password" name="password_confirmation" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                    placeholder="Repeat password">
                            </div>
                        </div>
                        @if(config('settings.guest_register_captcha_status')=="1")
                        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                        @error('g-recaptcha-response')
                        <p class="text-red-500 text-xs mb-3">{{ $message }}</p>
                        @enderror
                        @endif

                        <button type="submit"
                            class="w-full py-3 rounded-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 transition text-sm">
                            Create Account
                        </button>
                    </form>

                    <p class="text-center text-sm text-gray-500 mt-5">
                        Already have an account?
                        <a href="{{ route('web.guest.login') }}" class="text-indigo-600 font-semibold hover:underline">Login here</a>
                    </p>

                </div>
            </div>
        </div>

    </div>
    @endif
</section>

@endsection

@push('scripts')
@php
$recaptchaKey = env('GOOGLE_RECAPTCHA_KEY', ''); @endphp
@if($recaptchaKey && config('settings.guest_register_captcha_status')=="1")
<script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaKey }}"></script>
<script>
    grecaptcha.ready(function() {
        var form = document.getElementById('guest-register-form');
        var siteKey = form.dataset.sitekey;
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            grecaptcha.execute(siteKey, {
                action: 'guest_register'
            }).then(function(token) {
                document.getElementById('g-recaptcha-response').value = token;
                form.submit();
            });
        });
    });
</script>
@endif
@endpush