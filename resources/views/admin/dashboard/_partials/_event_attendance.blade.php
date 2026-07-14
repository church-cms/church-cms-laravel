<div class="w-full">
    <div class="flex justify-between items-center mb-2">
        <h1 class="text-sm uppercase text-gray-800 font-semibold">
            Events Attendance
        </h1>

        <a href="{{ url('/admin/events') }}" class="text-xs underline">
            See All
        </a>
    </div>

    <div class="w-full bg-white rounded shadow dashboard-content">
        <div class="p-2">

            @forelse($dashboard['EventAttendance'] as $event)

            @php
            $total_attendees = App\Models\User::where('church_id', $event->church_id)
            ->ByRole('5')
            ->whereHas('userprofile', function ($q) {
            $q->where('membership_type', 'member')
            ->orWhereNull('membership_type');
            })
            ->count();

            $present_attendees = count($event->attendees);
            $absent_attendees = $total_attendees - $present_attendees;
            @endphp

            <div class="mt-3 bg-gray-100 border-l-4 border-teal-400 rounded p-3">
                <div class="flex">

                    <!-- Left -->
                    <div class="w-32 text-center border-r border-gray-300 pr-3">
                        <p class="text-xs font-bold text-gray-600">
                            {{ $event->attendance_date }}
                        </p>

                        <div class="mt-2 text-xs leading-5">
                            <p>
                                <span class="font-semibold">Total:</span>
                                {{ $total_attendees }}
                            </p>

                            <p class="text-green-600">
                                <span class="font-semibold">Present:</span>
                                {{ $present_attendees }}
                            </p>

                            <p class="text-red-600">
                                <span class="font-semibold">Absent:</span>
                                {{ $absent_attendees }}
                            </p>
                        </div>
                    </div>

                    <!-- Right -->
                    <div class="flex-1 pl-4 flex flex-col justify-center">
                        <a href="{{ url('/admin/events/attendance/session/' . $event->id) }}"
                            class="text-base font-semibold text-gray-900 hover:text-teal-600">
                            {{ $event->event->title }}
                        </a>

                        <p class="text-sm text-gray-600 mt-1">
                            {{ $event->event->category }}
                        </p>
                    </div>

                </div>
            </div>

            @empty

            <div class="py-6 text-center">
                <p class="text-sm font-semibold text-gray-500">
                    No Records Found
                </p>
            </div>

            @endforelse

        </div>
    </div>
</div>