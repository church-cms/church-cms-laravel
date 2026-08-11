<!-- absent member start -->
<div class="w-full ">
    <div class="flex justify-between">
        <h1 class="text-sm uppercase text-gray-800 font-semibold mb-2">Not Attended Members</h1>
        <!-- <a href="{{ url('/admin/dashboard/absent') }}" class="text-xs underline">See All</a> -->
    </div>
    <div class="bg-white shadow rounded px-3 py-1 dashboard-content">
        @if (count($dashboard['absentMembers']) > 0)
            @foreach ($dashboard['absentMembers'] as $absentMember)
                <div class="flex py-2 items-center">
                    <div class="w-1/6">
                        <img src="{{ $absentMember->user->userprofile->AvatarPath }}" class="w-8 h-8 rounded-full">
                    </div>
                    <div class="mx-2 text-gray-700 flex justify-between items-center w-5/6">
                        <p class="font-semibold text-sm w-1/3">
                            <a
                                href="{{ url('/admin/member/show/' . $absentMember->user->name) }}">{{ ucwords($absentMember->user->FullName) }}</a>
                        </p>
                        <p class="text-gray-600 text-xs w-1/3">
                            <a
                                href="{{ url('/admin/events/show/details/' . $absentMember->entity_id) }}">{{ $absentMember->events->title }}</a>
                        </p>
                        <p class="text-gray-600 text-xs w-1/3">{{ date('M-d Y', strtotime($absentMember->date)) }}</p>
                    </div>
                </div>
            @endforeach
        @else
            <div class="flex py-2 items-center">
                <p class="font-semibold text-sm w-full" style="text-align: center;">No Records Found</p>
            </div>
        @endif
    </div>
</div>
<!-- absent member end -->
