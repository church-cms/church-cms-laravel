<div class="w-full">
    <h1 class="text-sm uppercase text-gray-800 font-semibold mb-2">Statistics</h1>
    <div class="bg-white shadow rounded px-4 py-3">

        <div class="grid grid-cols-2 gap-3">

            <a href="{{ url('/admin/members') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition group">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-users text-blue-500 text-sm"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xl font-bold text-gray-800 leading-none">{{ $dashboard['memberCount'] }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Members</p>
                </div>
            </a>

            <a href="{{ url('/admin/guest') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition group">
                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user-clock text-purple-500 text-sm"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xl font-bold text-gray-800 leading-none">{{ $dashboard['guestCount'] }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Guests</p>
                </div>
            </a>

            <a href="{{ url('/admin/events') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition group">
                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-calendar-alt text-green-500 text-sm"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xl font-bold text-gray-800 leading-none">{{ $dashboard['eventCount'] }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Events</p>
                </div>
            </a>

            <a href="{{ url('/admin/groups') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition group">
                <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-layer-group text-yellow-500 text-sm"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xl font-bold text-gray-800 leading-none">{{ $dashboard['groupCount'] }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Groups</p>
                </div>
            </a>

            <a href="{{ url('/admin/bulletins') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition group">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-newspaper text-red-500 text-sm"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xl font-bold text-gray-800 leading-none">{{ $dashboard['bulletinCount'] }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Bulletins</p>
                </div>
            </a>

            <a href="{{ url('/admin/gallery') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition group">
                <div class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-images text-pink-500 text-sm"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xl font-bold text-gray-800 leading-none">{{ $dashboard['galleryCount'] }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Galleries</p>
                </div>
            </a>

            <a href="{{ url('/admin/media') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition group">
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-file-alt text-indigo-500 text-sm"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xl font-bold text-gray-800 leading-none">{{ $dashboard['fileCount'] }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Files</p>
                </div>
            </a>

            <a href="{{ url('/admin/funds') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition group">
                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-hand-holding-usd text-emerald-500 text-sm"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xl font-bold text-gray-800 leading-none">{{ number_format($dashboard['total_fund'], 0) }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Total Funds</p>
                </div>
            </a>

        </div>
    </div>
</div>
