<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Church CMS')) – Member Portal</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-gray-100" style="font-family: 'Inter', sans-serif;">

    {{-- ── Header ─────────────────────────────────────────────────── --}}
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ url('/member/home') }}" class="flex items-center space-x-2 no-underline">
                    <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-gray-800 text-sm capitalize">
                        {{ optional(auth()->user()->church)->name ?? config('app.name') }}
                    </span>
                </a>

                {{-- Right: Avatar + Dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button x-on:click="open = !open"
                        class="flex items-center space-x-2 focus:outline-none group">
                        @if(optional(auth()->user()->userprofile)->avatar)
                            <img src="{{ auth()->user()->userprofile->AvatarPath }}"
                                class="w-9 h-9 rounded-full object-cover ring-2 ring-indigo-300 group-hover:ring-indigo-500 transition">
                        @else
                            <div class="w-9 h-9 rounded-full bg-indigo-100 ring-2 ring-indigo-300 group-hover:ring-indigo-500 transition flex items-center justify-center flex-shrink-0">
                                <span class="text-indigo-700 font-semibold text-sm">
                                    {{ strtoupper(substr(optional(auth()->user()->userprofile)->firstname ?? auth()->user()->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                        <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    {{-- Dropdown --}}
                    <div x-show="open" x-on:click.outside="open = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50"
                        style="display:none;">

                        {{-- User info strip --}}
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-800 truncate">
                                {{ optional(auth()->user()->userprofile)->firstname }}
                                {{ optional(auth()->user()->userprofile)->lastname }}
                            </p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>

                        <a href="{{ url('/member/change-password') }}"
                            class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 no-underline">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span>Change Password</span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center space-x-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </header>

    {{-- ── Main (Vue mounts here only) ────────────────────────────── --}}
    <div id="app">
        <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if(session('status'))
                <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('scripts')

    <style>
      body {
        font-family: Arial, sans-serif;

        height: 100vh;
        gap: 40px;
      }

      .card {
        width: 400px;
        height: 310px;
        border-radius: 15px;
        /* background: url("back.png") no-repeat center/cover; */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        overflow: hidden;
/*        padding: 6px 10px 30px 10px;*/
       padding: 15px 10px 2px 16px;
        color: #000;
          margin-left: auto;
         margin-right: auto;
      }

      .company-name {
        font-size: 20px;
        font-weight: bold;
      }

      .slogan {
        font-size: 12px;
        color: #555;
      }

      .website {
        text-align: center;
        font-size: 13px;
        color: #2196f3;
      }
    </style>
</body>
</html>
