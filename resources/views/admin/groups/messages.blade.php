@extends('layouts.admin.layout')

@section('content')
    <div class="relative">
        <div class="flex flex-wrap lg:flex-row justify-between">
            <div class="">
                <h1 class="admin-h1 mb-5 flex items-center">
                    <a href="{{ url('/admin/group/show/' . $id) }}" title="Back" class="rounded-full bg-gray-100 p-2">
                        <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
                    </a>
                    <span class="mx-3">Message Lists</span>
                </h1>
            </div>
            @include('partials.message')
            <form action="{{ url('/admin/group/messages/' . $id) }}" enctype="multipart form-data">
                <div class=" flex flex-wrap items-center">
                    <select class="tw-form-control text-xs" name="mode">
                        <option value="">Filter By Mode</option>
                        <option value="mail" {{ \request()->query('mode') == 'mail' ? 'selected' : '' }}>Mail</option>
                        <option value="notification" {{ \request()->query('mode') == 'notification' ? 'selected' : '' }}>
                            Notification</option>
                        <option value="sms" {{ \request()->query('mode') == 'sms' ? 'selected' : '' }}>Sms</option>
                    </select>
                    <button value="Submit" type="submit"
                        class="blue-bg text-sm text-white px-2 py-1 rounded mx-1">Submit</button>
                </div>
            </form>
        </div>

        <div class="custom-table bg-white">
            <table class="w-full bordered">
                <thead class="bg-grey-light">
                    <tr class="border-t-2 border-b-2">
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">To</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Mode</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Sent On</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Status</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Action</th>
                    </tr>
                </thead>
                @if (count($messages) == '')
                    <tr class="border-t-2 border-b-2">
                        <td colspan="5">
                            <p class="font-semibold text-s" style="text-align: center">No records found</p>
                        </td>
                    </tr>
                @else
                    <tbody class="bg-grey-light">
                        @foreach ($messages as $message)
                            <tr>
                                <td class="py-3 px-2">
                                    <a
                                        href="{{ url('/admin/member/show/' . $message->user->name) }}">{{ $message->user->FullName }}</a>
                                </td>
                                <td class="py-3 px-2">{{ ucwords($message->mode) }}</td>
                                <td class="py-3 px-2">{{ date('d-m-Y H:i:s', strtotime($message->executed_at)) }}</td>
                                <td class="py-3 px-2">{{ ucwords($message->status) }}</td>
                                <td class="py-3 px-2">
                                    <a href="{{ url('/admin/message/show/' . $message->id) }}"
                                        class="btn btn-primary submit-btn cursor-pointer" target="_blank">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                @endif
            </table>
            {{ $messages->links() }}
        </div>
    </div>
@endsection
