<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">

        <div class="flex-shrink-0">
            @include('theme::_logo')
        </div>

        <div class="hidden md:flex items-center space-x-6 text-sm font-medium text-gray-600">
            <a href="{{ route('web.home') }}"    class="hover:text-indigo-600">Home</a>
            <a href="{{ route('web.pages') }}"   class="hover:text-indigo-600">About</a>
            <a href="{{ route('web.posts') }}"   class="hover:text-indigo-600">Blog</a>
            <a href="{{ route('web.events') }}"  class="hover:text-indigo-600">Events</a>
            <a href="{{ route('web.gallery') }}" class="hover:text-indigo-600">Gallery</a>
            <a href="{{ route('web.sermons') }}" class="hover:text-indigo-600">Sermons</a>
            <a href="{{ route('web.faq') }}"     class="hover:text-indigo-600">FAQ</a>
            <a href="{{ route('web.prayer') }}"  class="hover:text-indigo-600">Prayer</a>
            <a href="{{ route('web.help') }}"    class="hover:text-indigo-600">Help</a>
            <a href="{{ route('web.contact') }}" class="hover:text-indigo-600">Contact</a>
        </div>

        <div>
            <a href="{{ route('login') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                Member Login
            </a>
        </div>
    </div>
</nav>
