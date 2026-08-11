@extends('theme::layout')

@section('title', 'Guest Login')

@section('content')

@include('theme::_hero_banner', [
    'heroTitle'    => 'Guest Login',
    'heroSubtitle' => 'Login to submit prayer requests, help requests, and blog comments.',
    'heroIcon'     => '🔐',
    'breadcrumbs'  => [
        ['label' => 'Home', 'url' => route('web.home')],
        ['label' => 'Guest Login'],
    ],
])

<section class="px-4 sm:px-6 lg:px-8 py-12 space-y-8">

    {{-- ── Login card: narrow centred column ──────────────────────── --}}
    <div class="max-w-lg mx-auto space-y-6">
    @if($guestLoginBlocked ?? false)
        @include('theme::_access_blocked', [
            'blockedTitle'   => 'Guest Login is Currently Disabled',
            'blockedMessage' => 'Guest logins are not available at this time. Please check back later or contact the church office for assistance.',
        ])
    @else

    @if(session('auth_notice'))
    <div class="bg-indigo-50 border border-indigo-200 text-indigo-800 text-sm rounded-lg px-4 py-3">
        {{ session('auth_notice') }}
    </div>
    @endif

    {{-- ── Guest Login Card ─────────────────────────────────────── --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-5">
            <h2 class="text-white font-bold text-lg">Welcome Back</h2>
            <p class="text-indigo-200 text-xs mt-0.5">Login with your registered email and password.</p>
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

            <form method="POST" action="{{ route('web.guest.login.store') }}" novalidate>
                @csrf

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('email') ? 'border-red-400' : 'border-gray-300' }}"
                           placeholder="your@email.com">
                </div>

                <div class="mb-5">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('password') ? 'border-red-400' : 'border-gray-300' }}"
                           placeholder="Your password">
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-400">
                        Remember me
                    </label>
                    <a href="{{ route('password.request') }}" class="text-xs text-indigo-600 hover:underline">
                        Forgot password?
                    </a>
                </div>

                <button type="submit"
                        class="w-full py-3 rounded-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 transition text-sm">
                    Login
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-5">
                Don't have an account?
                <a href="{{ route('web.guest.register') }}" class="text-indigo-600 font-semibold hover:underline">Register here</a>
            </p>

        </div>
    </div>

    @endif
    </div>{{-- end narrow login column --}}

    {{-- ── Church Member Section: 2/3 of page width ───────────────── --}}
    <div class="max-w-4xl mx-auto">
    <div class="bg-white border-2 border-indigo-100 rounded-2xl overflow-hidden">

        <div class="px-6 py-4 bg-indigo-50 border-b border-indigo-100">
            <h3 class="text-base font-bold text-indigo-900">Are You a Church Member?</h3>
            <p class="text-xs text-indigo-600 mt-0.5">This login above is for guests only. Members have a dedicated experience.</p>
        </div>

        @if($memberLoginBlocked ?? false)
        <div class="p-6">
            @include('theme::_access_blocked', [
                'blockedTitle'   => 'Member Web Login is Currently Disabled',
                'blockedMessage' => 'Web login for church members is not available at this time. Please use the mobile app to access your account.',
            ])
        </div>
        @else
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">

            {{-- App option --}}
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 flex flex-col gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-700 text-xl flex-shrink-0">
                        📱
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800">Use the Mobile App</p>
                        <p class="text-xs text-gray-500">Recommended for members</p>
                    </div>
                </div>
                <p class="text-xs text-gray-600 leading-relaxed">
                    Get the full member experience — attendance, groups, bulletins, prayer, and more — right from your phone.
                </p>
                <div class="flex flex-col gap-2 mt-auto">
                    <a href="#" class="flex items-center justify-center gap-2 bg-gray-900 text-white text-xs font-semibold px-4 py-2.5 rounded-lg hover:bg-gray-700 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                        </svg>
                        Download on App Store
                    </a>
                    <a href="#" class="flex items-center justify-center gap-2 bg-gray-900 text-white text-xs font-semibold px-4 py-2.5 rounded-lg hover:bg-gray-700 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3.18 23.76c.28.15.6.19.9.1l12.45-7.2-2.55-2.55-10.8 9.65zm15.54-9l-2.97-2.97L3.3.81c-.27.12-.45.39-.48.69l-.01.04 11.4 11.4 4.51-1.18zM22.56 10.2l-3.24-1.87-3.24 1.87-3.24-1.87-.01 3.74 3.25 1.87 3.24-1.87 3.24 1.87V10.2zM3.18.24L3.09.3c-.18.15-.27.36-.27.57v.12L14.01 12l2.55-2.55L3.18.24z"/>
                        </svg>
                        Get it on Google Play
                    </a>
                </div>
            </div>

            {{-- Web login option --}}
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 flex flex-col gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-xl flex-shrink-0">
                        🖥️
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800">Login via Web</p>
                        <p class="text-xs text-gray-500">Access from any browser</p>
                    </div>
                </div>
                <p class="text-xs text-gray-600 leading-relaxed">
                    Prefer a browser? Login to your member account directly on the web to access your profile and church activity.
                </p>
                <a href="{{ route('login') }}"
                   class="mt-auto flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-4 py-2.5 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Member Web Login
                </a>
            </div>

        </div>
        @endif
    </div>
    </div>{{-- end max-w-4xl --}}

</section>

@endsection
