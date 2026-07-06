<footer class="bg-gray-900 text-gray-400 mt-16 pt-4">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">

            {{-- Col 1: Brand --}}
            <div class="lg:col-span-1">
                @if(!empty($_churchdetail['church_logo']))
                    <img src="{{ url($_churchdetail['church_logo']) }}"
                         alt="{{ $_church->name ?? config('app.name') }}"
                         class="h-10 w-auto mb-4 brightness-0 invert opacity-80">
                @endif
                <p class="text-white font-bold text-lg leading-snug">
                    {{ $_church->name ?? config('app.name') }}
                </p>
                @if(!empty($_churchdetail['short_summary']))
                    <p class="mt-3 text-sm leading-relaxed">{{ $_churchdetail['short_summary'] }}</p>
                @else
                    <p class="mt-3 text-sm leading-relaxed">A loving community of faith, rooted in the gospel of Jesus Christ.</p>
                @endif

                {{-- Social links --}}
                <div class="flex items-center gap-4 mt-5">
                    @if(!empty($_churchdetail['facebook']))
                    <a href="{{ $_churchdetail['facebook'] }}" target="_blank" rel="noopener"
                       class="text-gray-500 hover:text-white transition" title="Facebook">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/>
                        </svg>
                    </a>
                    @endif
                    @if(!empty($_churchdetail['twitter']))
                    <a href="{{ $_churchdetail['twitter'] }}" target="_blank" rel="noopener"
                       class="text-gray-500 hover:text-white transition" title="Twitter / X">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                    @endif
                    @if(!empty($_churchdetail['instagram']))
                    <a href="{{ $_churchdetail['instagram'] }}" target="_blank" rel="noopener"
                       class="text-gray-500 hover:text-white transition" title="Instagram">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Col 2: Explore --}}
            <div>
                <h4 class="text-white text-sm font-semibold uppercase tracking-widest mb-5">Explore</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('web.home') }}"     class="hover:text-white transition">Home</a></li>
                    <li><a href="{{ route('web.pages') }}"    class="hover:text-white transition">About Us</a></li>
                    <li><a href="{{ route('web.posts') }}"    class="hover:text-white transition">Blog &amp; News</a></li>
                    <li><a href="{{ route('web.gallery') }}"  class="hover:text-white transition">Gallery</a></li>
                    <li><a href="{{ route('web.bulletins') }}" class="hover:text-white transition">Bulletins</a></li>
                    <li><a href="{{ route('web.faq') }}"      class="hover:text-white transition">FAQ</a></li>
                </ul>
            </div>

            {{-- Col 3: Ministries & Resources --}}
            <div>
                <h4 class="text-white text-sm font-semibold uppercase tracking-widest mb-5">Ministries</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('web.sermons') }}"  class="hover:text-white transition">Sermons</a></li>
                    <li><a href="{{ route('web.events') }}"   class="hover:text-white transition">Events</a></li>
                    <li><a href="{{ route('web.prayer') }}"   class="hover:text-white transition">Prayer Requests</a></li>
                    <li><a href="{{ route('web.help') }}"     class="hover:text-white transition">Help Requests</a></li>
                    <li><a href="{{ route('web.contact') }}"  class="hover:text-white transition">Contact Us</a></li>
                </ul>
            </div>

            

            {{-- Col 4: Contact Info --}}
            <div>
                <h4 class="text-white text-sm font-semibold uppercase tracking-widest mb-5">Get in Touch</h4>
                <ul class="space-y-4 text-sm">
                    @if(!empty($_churchdetail['address']))
                    <li class="flex items-start gap-3">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="leading-relaxed">{{ $_churchdetail['address'] }}</span>
                    </li>
                    @endif
                    @if(!empty($_churchdetail['phone']))
                    <li class="flex items-center gap-3">
                        <svg class="w-4 h-4 flex-shrink-0 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <a href="tel:{{ $_churchdetail['phone'] }}" class="hover:text-white transition">{{ $_churchdetail['phone'] }}</a>
                    </li>
                    @endif
                    @if(!empty($_churchdetail['email']))
                    <li class="flex items-center gap-3">
                        <svg class="w-4 h-4 flex-shrink-0 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <a href="mailto:{{ $_churchdetail['email'] }}" class="hover:text-white transition">{{ $_churchdetail['email'] }}</a>
                    </li>
                    @endif
                    <li class="pt-2">
                        <a href="{{ route('web.contact') }}"
                           class="inline-block bg-indigo-700 hover:bg-indigo-600 text-white text-xs font-semibold px-4 py-2 rounded-lg transition">
                            Send a Message
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Col 4: Contact Info --}}
            <div>
                <h4 class="text-white text-sm font-semibold uppercase tracking-widest mb-5">Site QR Code</h4>
                <ul class="mr-20">
                  
                    <li class="pt-2">
                         @include('member.site_domain_qrcode')
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    {{-- Bottom bar --}}
    <div class="border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-600">
            <p>&copy; {{ date('Y') }} {{ $_church->name ?? config('app.name') }}. All rights reserved.</p>
            <div class="flex items-center gap-5">
                @auth
                @php $isFooterGuest = optional(auth()->user()->userprofile)->membership_type === 'guest'; @endphp
                <form method="POST" action="{{ $isFooterGuest ? route('web.guest.logout') : route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="hover:text-gray-400 transition">Logout</button>
                </form>
                @else
                <a href="{{ route('login') }}" class="hover:text-gray-400 transition">Staff Login</a>
                @endauth
                <a href="{{ route('web.contact') }}" class="hover:text-gray-400 transition">Privacy</a>
                <button onclick="window.scrollTo({top:0,behavior:'smooth'})"
                        class="hover:text-gray-400 transition flex items-center gap-1">
                  &nbsp;&#8593;&nbsp;Back to top

                </button>
            </div>
        </div>
    </div>

</footer>
