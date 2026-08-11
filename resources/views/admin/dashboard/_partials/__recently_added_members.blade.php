<div>
    <div>
        <h1 class="text-sm text-gray-800 uppercase font-semibold mb-2">Recently Added Members
        </h1>
    </div>
    <div class="bg-white shadow rounded px-3 py-1 dashboard-content">
        @if (count($dashboard['recentMember']) > 0)
            @foreach ($dashboard['recentMember'] as $recentMembers)
                <div class="flex py-2 items-center">
                    <div class="w-1/5">
                        <img src="{{ $recentMembers->userprofile->AvatarPath }}" class="w-8 h-8 rounded-full">
                    </div>
                    <div class="mx-2 text-gray-700 w-4/5 flex justify-between items-center">
                        <p class="font-semibold text-sm">
                            <a
                                href="{{ url('/admin/member/show/' . $recentMembers->name) }}">{{ ucwords($recentMembers->FullName) }}</a>
                        </p>
                        <p class="text-gray-600 text-xs">
                            {{ date('M-d', strtotime($recentMembers->created_at)) }}
                        </p>
                    </div>
                </div>
            @endforeach
        @else
            <div class="flex py-2 items-center">
                <p class="font-semibold text-sm w-full" style="text-align: center;">No Records
                    Found</p>
            </div>
        @endif
    </div>
</div>
