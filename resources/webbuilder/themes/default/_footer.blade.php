<footer class="bg-gray-800 text-gray-300 py-10 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        @if($_church)
            <p class="text-lg font-semibold text-white">{{ $_church->name }}</p>
            @if($_church->address)
                <p class="text-sm mt-1">{{ $_church->address }}</p>
            @endif
        @endif
        <p class="text-xs mt-4">&copy; {{ date('Y') }} {{ $_church->name ?? config('app.name') }}. All rights reserved.</p>
    </div>
</footer>
