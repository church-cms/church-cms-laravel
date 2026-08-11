<nav class="bg-white shadow-sm sticky top-0 z-50" x-data="{ open: false, profileOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">

        <div class="flex-shrink-0">
            @include('theme::_logo')
        </div>

        <div class="hidden md:flex items-center space-x-6 text-sm font-medium text-gray-600">
            <a href="{{ route('web.home') }}" class="hover:text-indigo-600">Home</a>
            <a href="{{ route('web.pages') }}" class="hover:text-indigo-600">About</a>
            <a href="{{ route('web.posts') }}" class="hover:text-indigo-600">Blog</a>
            <a href="{{ route('web.events') }}" class="hover:text-indigo-600">Events</a>
            <a href="{{ route('web.gallery') }}" class="hover:text-indigo-600">Gallery</a>
            <a href="{{ route('web.sermons') }}" class="hover:text-indigo-600">Sermons</a>
            <a href="{{ route('web.bulletins') }}" class="hover:text-indigo-600">Bulletins</a>
            <a href="{{ route('web.faq') }}" class="hover:text-indigo-600">FAQ</a>
            <a href="{{ route('web.prayer') }}" class="hover:text-indigo-600">Prayer</a>
            <a href="{{ route('web.help') }}" class="hover:text-indigo-600">Help</a>
            <a href="{{ route('member.donate') }}" class="hover:text-indigo-600">Donation</a>
            <a href="{{ route('web.contact') }}" class="hover:text-indigo-600">Contact</a>
        </div>

        <div class="flex items-center gap-3">

            @auth
            @php $isGuest = optional(auth()->user()->userprofile)->membership_type === 'guest'; @endphp
            {{-- Avatar + dropdown --}}
            <div class="relative hidden md:block">
                <button @click="profileOpen = !profileOpen" class="flex items-center gap-2 focus:outline-none group">
                    @if(optional(auth()->user()->userprofile)->avatar)
                    <img src="{{ auth()->user()->userprofile->AvatarPath }}"
                        class="w-9 h-9 rounded-full object-cover ring-2 ring-indigo-300 group-hover:ring-indigo-500 transition">
                    @else
                    <div class="w-9 h-9 rounded-full bg-indigo-100 ring-2 ring-indigo-300 group-hover:ring-indigo-500 transition flex items-center justify-center">
                        <span class="text-indigo-700 font-semibold text-sm">
                            {{ strtoupper(substr(optional(auth()->user()->userprofile)->firstname ?? auth()->user()->name, 0, 1)) }}
                        </span>
                    </div>
                    @endif
                    <svg class="w-4 h-4 text-gray-400 transition-transform" :class="profileOpen ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="profileOpen" @click.outside="profileOpen = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50"
                    style="display:none;">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-semibold text-gray-800 truncate">
                            {{ optional(auth()->user()->userprofile)->firstname }}
                            {{ optional(auth()->user()->userprofile)->lastname }}
                        </p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        @if($isGuest)
                        <span class="inline-block mt-1 text-xs px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-600 font-medium">Guest</span>
                        @endif
                    </div>
                    @if(!$isGuest)
                    <a href="{{ url('/member/home') }}"
                        class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 no-underline">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        My Profile
                    </a>
                    <a href="{{ url('/member/mygrouplist') }}"
                        class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 no-underline">

                        <svg class="w-4 h-4 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 20h5V18a4 4 0 00-5-3.87M17 20H7m10 0v-2c0-.653-.126-1.276-.356-1.848M7 20H2v-2a4 4 0 015-3.87m0 0a5.002 5.002 0 019.712 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>

                        My Group List
                    </a>
                    <a href="{{ route('member.donate') }}"
                        class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 no-underline">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        Donate
                    </a>
                    <a href="{{ url('/member/change-password') }}"
                        class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 no-underline">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Change Password
                    </a>
                    @endif
                    <form method="POST" action="{{ $isGuest ? route('web.guest.logout') : route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
            @else
            {{-- Unauthenticated: Register + Login --}}
            <div class="hidden md:flex items-center gap-2">
                <a href="{{ route('web.guest.register') }}"
                    class="px-4 py-2 border border-indigo-300 text-indigo-700 text-sm font-medium rounded-md hover:bg-indigo-50">
                    Register
                </a>
                <a href="{{ route('web.guest.login') }}"
                    class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                    Login
                </a>
            </div>
            @endauth

            {{-- Mobile hamburger --}}
            <button @click="open = !open"
                class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-indigo-600 hover:bg-gray-100 focus:outline-none"
                :aria-expanded="open">
                <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

        </div>
    </div>

    {{-- Mobile drawer --}}
    <div x-show="open" x-collapse class="md:hidden border-t border-gray-100 bg-white">
        <div class="px-4 py-3 space-y-1 text-sm font-medium text-gray-600">
            <a href="{{ route('web.home') }}" class="block px-3 py-2 rounded-md hover:bg-indigo-50 hover:text-indigo-600">Home</a>
            <a href="{{ route('web.pages') }}" class="block px-3 py-2 rounded-md hover:bg-indigo-50 hover:text-indigo-600">About</a>
            <a href="{{ route('web.posts') }}" class="block px-3 py-2 rounded-md hover:bg-indigo-50 hover:text-indigo-600">Blog</a>
            <a href="{{ route('web.events') }}" class="block px-3 py-2 rounded-md hover:bg-indigo-50 hover:text-indigo-600">Events</a>
            <a href="{{ route('web.gallery') }}" class="block px-3 py-2 rounded-md hover:bg-indigo-50 hover:text-indigo-600">Gallery</a>
            <a href="{{ route('web.sermons') }}" class="block px-3 py-2 rounded-md hover:bg-indigo-50 hover:text-indigo-600">Sermons</a>
            <a href="{{ route('web.bulletins') }}" class="block px-3 py-2 rounded-md hover:bg-indigo-50 hover:text-indigo-600">Bulletins</a>
            <a href="{{ route('web.faq') }}" class="block px-3 py-2 rounded-md hover:bg-indigo-50 hover:text-indigo-600">FAQ</a>
            <a href="{{ route('web.prayer') }}" class="block px-3 py-2 rounded-md hover:bg-indigo-50 hover:text-indigo-600">Prayer</a>
            <a href="{{ route('web.help') }}" class="block px-3 py-2 rounded-md hover:bg-indigo-50 hover:text-indigo-600">Help</a>
            <a href="{{ route('web.contact') }}" class="block px-3 py-2 rounded-md hover:bg-indigo-50 hover:text-indigo-600">Contact</a>

            @auth
            @php $isGuestMobile = optional(auth()->user()->userprofile)->membership_type === 'guest'; @endphp
            <div class="border-t border-gray-100 pt-2 mt-2 space-y-1">
                <div class="px-3 py-2">
                    <p class="text-xs font-semibold text-gray-800">
                        {{ optional(auth()->user()->userprofile)->firstname }}
                        {{ optional(auth()->user()->userprofile)->lastname }}
                    </p>
                    <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
                    @if($isGuestMobile)
                    <span class="inline-block mt-1 text-xs px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-600 font-medium">Guest</span>
                    @endif
                </div>
                @if(!$isGuestMobile)
                <a href="{{ url('/member/home') }}" class="block px-3 py-2 rounded-md hover:bg-indigo-50 hover:text-indigo-600">My Profile</a>
                <a href="{{ url('/member/change-password') }}" class="block px-3 py-2 rounded-md hover:bg-indigo-50 hover:text-indigo-600">Change Password</a>
                @endif
                <form method="POST" action="{{ $isGuestMobile ? route('web.guest.logout') : route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-red-600 hover:bg-red-50">Logout</button>
                </form>
            </div>
            @else
            <div class="border-t border-gray-100 pt-2 mt-2 space-y-1">
                <a href="{{ route('web.guest.register') }}" class="block px-3 py-2 rounded-md border border-indigo-300 text-indigo-700 text-center hover:bg-indigo-50">Register</a>
                <a href="{{ route('web.guest.login') }}" class="block px-3 py-2 rounded-md bg-indigo-600 text-white text-center hover:bg-indigo-700">Login</a>
            </div>
            @endauth
        </div>
    </div>
</nav>