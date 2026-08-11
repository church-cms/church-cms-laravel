@extends('layouts.admin.layout')

@section('content')

    <div class="flex flex-row justify-between">
        <div class="w-full lg:w-1/2 md:w-1/2">
            <h1 class="admin-h1 mb-3 flex items-center">
                <a href="{{ url('/admin/reports') }}" class="rounded-full bg-gray-100 p-2" title="Back"><img
                        src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3"></a>
                @if ($subject == 'Event Remainder Mail')
                    <span class="mx-3">Events Reports</span>
                @elseif($subject == 'Prayer Request Remainder Mail')
                    <span class="mx-3">PrayerRequest Reports</span>
                @elseif($subject == 'Birthday Wishes')
                    <span class="mx-3">Birthday Reports</span>
                @elseif($subject == 'Anniversary Wishes')
                    <span class="mx-3">Anniversary Reports</span>
                @endif
            </h1>
        </div>
    </div>

    <div class="relative bg-white shadow p-2">
        <div class="flex flex-row justify-between">
            <table class="w-full custom-table">
                <thead class="bg-grey-light">
                    <tr class="border-t-2 border-b-2">
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Title</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">User Name</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Status</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Sent Date</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Via</th>
                    </tr>
                </thead>
                @if (count($reports) == 0)
                    <tr class="border-t-2 border-b-2">
                        <td colspan="5">No records found</td>
                    </tr>
                @else
                    <tbody class="bg-grey-light">
                        @foreach ($reports as $events)
                            <tr class="border-t-2 border-b-2">
                                @if ($events->entity_name == 'App\Models\Events')
                                    <td class="py-3 px-2"><a
                                            href="{{ url('/admin/events/show/details/' . $events->entity_id) }}">{{ $events->events->title }}</a>
                                    </td>
                                @elseif($events->entity_name == 'App\Models\User')
                                    @if ($events->via == 'mail' || $events->via == 'notification')
                                        <td class="py-3 px-2"><a
                                                href="{{ url('/admin/member/show/' . $events->user->name) }}">
                                                {{ $events->user->name }}</a></td>
                                    @else
                                        <td class="py-3 px-2"><a
                                                href="{{ url('/admin/member/show/' . $events->userSms->name) }}">
                                                {{ $events->userSms->name }}</a></td>
                                    @endif
                                @elseif($events->entity_name == 'App\Models\PrayerRequest')
                                    <td class="py-3 px-2">{{ $events->prayerRequest->title }}</td>
                                @endif
                                <td class="py-3 px-2">
                                    @if ($events->via == 'mail' || $events->via == 'notification')
                                        {{ $events->user->email }}
                                    @else
                                        {{ $events->userSms->mobile_no }}
                                    @endif
                                </td>
                                <td class="py-3 px-2">{{ $events->queue_status }}</td>
                                <td class="py-3 px-2">{{ date('d-m-Y', strtotime($events->executed_at)) }}</td>
                                <td class="py-3 px-2">{{ ucfirst($events->via) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                @endif
            </table>
        </div>
    </div>
    {{ $reports->links() }}
@endsection
