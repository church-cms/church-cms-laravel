@extends('layouts.admin.layout')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="admin-h1 flex items-center">
            <a href="{{ $prev_url }}" title="Back" id="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Email Details</span>
        </h1>
        <test-mail mode="admin" email_id="{{ $email->id }}"></test-mail>
    </div>
    <div class="bg-white shadow my-3">
        <div class="px-4 py-4">
            <div class="w-full lg:mr-8 md:mr-8 custom-table">
                <table class="w-full">
                    <tr class="border-t border-b">
                        <td class="py-3 px-2 font-semibold">Subject</td>
                        <td class="py-3 px-2">{{ $email->subject }}</td>
                    </tr>
                    <tr class="border-t border-b">
                        <td class="py-3 px-2 font-semibold">From Email</td>
                        <td class="py-3 px-2">{{ $email->from_email }} </td>
                    </tr>
                    <tr class="border-t border-b">
                        <td class="py-3 px-2 font-semibold">From Name</td>
                        <td class="py-3 px-2">{{ $email->from_name }} </td>
                    </tr>
                    <tr class="border-t border-b">
                        <td class="py-3 px-2 font-semibold">Reply To Email</td>
                        <td class="py-3 px-2">{{ $email->reply_to_email }} </td>
                    </tr>
                    <tr class="border-t border-b">
                        <td class="py-3 px-2 font-semibold">Content</td>
                        <td class="py-3 px-2">{!! $email->content !!}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection
