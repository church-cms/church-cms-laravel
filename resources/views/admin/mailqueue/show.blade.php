@extends('layouts.admin.layout')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="admin-h1 flex items-center">
            <a href="{{ url('/admin/mailqueues') }}" title="Back" id="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Mail Queue Details</span>
        </h1>
    </div>
    <div class="bg-white shadow my-5">
        <div class="px-4 py-4">
            <div class="w-full lg:mr-8 md:mr-8 custom-table">
                <table class="w-full">
                    <tr class="border-t border-b">
                        <td class="py-3 px-2 font-semibold">Email</td>
                        <td class="py-3 px-2">
                            <a
                                href="{{ url('/admin/email/show/' . $mailqueue->email_id) }}">{{ $mailqueue->email->subject }}</a>
                        </td>
                    </tr>
                    <tr class="border-t border-b">
                        <td class="py-3 px-2 font-semibold">Subscriber</td>
                        <td class="py-3 px-2">
                            <a
                                href="{{ url('/admin/subscriber/show/' . $mailqueue->subscriber_id) }}">{{ $mailqueue->subscriber->email }}</a>
                        </td>
                    </tr>
                    <tr class="border-t border-b">
                        <td class="py-3 px-2 font-semibold">Mailing list</td>
                        <td class="py-3 px-2">
                            <a
                                href="{{ url('/admin/mailinglist/show/' . $mailqueue->mailinglist_id) }}">{{ $mailqueue->mailinglist->name }}</a>
                        </td>
                    </tr>
                    <tr class="border-t border-b">
                        <td class="py-3 px-2 font-semibold">Campaign</td>
                        <td class="py-3 px-2">
                            <a
                                href="{{ url('/admin/campaign/show/' . $mailqueue->campaign_id) }}">{{ $mailqueue->campaign->name }}</a>
                        </td>
                    </tr>
                    <tr class="border-t-2 border-b-2">
                        <td class="py-3 px-2 font-semibold">Subject</td>
                        <td class="py-3 px-2">{{ $mailqueue->subject }} </td>
                    </tr>
                    <tr class="border-t-2 border-b-2">
                        <td class="py-3 px-2 font-semibold">From Email</td>
                        <td class="py-3 px-2">{{ $mailqueue->from_email }} </td>
                    </tr>
                    <tr class="border-t-2 border-b-2">
                        <td class="py-3 px-2 font-semibold">From Name</td>
                        <td class="py-3 px-2">{{ $mailqueue->from_name }} </td>
                    </tr>
                    <tr class="border-t-2 border-b-2">
                        <td class="py-3 px-2 font-semibold">Reply To Email</td>
                        <td class="py-3 px-2">{{ $mailqueue->reply_to_email }} </td>
                    </tr>
                    <tr class="border-t-2 border-b-2">
                        <td class="py-3 px-2 font-semibold">To Mail</td>
                        <td class="py-3 px-2">{{ $mailqueue->to_mail }} </td>
                    </tr>
                    <tr class="border-t-2 border-b-2">
                        <td class="py-3 px-2 font-semibold">Content</td>
                        <td class="py-3 px-2">{{ $mailqueue->content }} </td>
                    </tr>
                    <tr class="border-t-2 border-b-2">
                        <td class="py-3 px-2 font-semibold">Fired At</td>
                        <td class="py-3 px-2">{{ $mailqueue->fired_at }} </td>
                    </tr>
                    <tr class="border-t-2 border-b-2">
                        <td class="py-3 px-2 font-semibold">Failed At</td>
                        <td class="py-3 px-2">{{ $mailqueue->failed_at }} </td>
                    </tr>
                    <tr class="border-t-2 border-b-2">
                        <td class="py-3 px-2 font-semibold">Exception</td>
                        <td class="py-3 px-2">{{ $mailqueue->exception }} </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection
