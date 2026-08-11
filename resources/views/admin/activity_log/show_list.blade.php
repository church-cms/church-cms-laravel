<div>
    <h1 class="admin-h1">Activity Log</h1>
</div>

<div class="bg-white px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3 custom-table my-3">
    @include('partials.message')
    <table class="table table-hover w-full border">
        <thead class="border-t-2 border-b-2">
            <tr>
                <th>Name</th>
                <th>Title</th>
                <th>Description</th>
                <th>Date and Time</th>
                <th>Ip</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($activitylog as $activity)
                <tr>
                    <td> <a href="{{ url('admin/activity_log/' . optional($activity->user)->name) }}">{{ optional($activity->user)->FullName }}
                    </td>
                    <td>{{ $activity->log_name }}</td>
                    <td>{{ $activity->description }}</td>
                    <td>{{ $activity->created_at->format(' d-m-Y  H:i:s') }}</td>
                    <td>
                        @if (asset($activity->properity['ip']) != '')
                            {{ $activity->properties['ip'] }}
                        @else
                            --
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>

        @if (count($activitylog) == 0)
            <tr class="border-b">
                <td colspan="6">No records found</td>
            </tr>
        @endif
    </table>
    {{ $activitylog->links() }}
</div>
