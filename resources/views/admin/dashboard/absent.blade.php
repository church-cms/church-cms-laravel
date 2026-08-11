@extends('layouts.admin.layout')

@section('content')
    <div class="flex flex-row justify-between">
        <div class="w-1/2">
            <h1 class="admin-h1 mb-3 flex items-center">
                <a href="{{ url('/admin/dashboard') }}" class="rounded-full bg-gray-300 p-2" title="Back">
                    <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
                </a>
                <span class="mx-3">Inactive Members[Events]</span>
            </h1>
        </div>
    </div>
    <div class="relative custom-table bg-white">
        <div class="flex flex-row justify-between">
            <table class="w-full">
                <thead class="bg-grey-light">
                    <tr class="border-t-2 border-b-2">
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Title</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">User Name</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Date</th>
                    </tr>
                </thead>
                @if (count($absents) == 0)
                    <tr class="border-t-2 border-b-2">
                        <td colspan="3">No records found</td>
                    </tr>
                @else
                    <tbody class="bg-grey-light">
                        @foreach ($absents as $absent)
                            <tr class="border-t-2 border-b-2">
                                <td class="py-3 px-2">
                                    <a
                                        href="{{ url('/admin/events/show/details/' . $absent->entity_id) }}">{{ $absent->events->title }}</a>
                                </td>
                                <td class="py-3 px-2">
                                    <a href="{{ url('/admin/member/show/' . $absent->user->name) }}">
                                        {{ ucfirst($absent->user->userprofile->firstname) }}</a>
                                </td>
                                <td class="py-3 px-2">{{ date('d-m-Y', strtotime($absent->date)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                @endif
            </table>
        </div>
    </div>
    {{ $absents->links() }}
@endsection
