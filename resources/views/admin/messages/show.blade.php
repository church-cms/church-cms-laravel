@extends('layouts.admin.layout')

@section('content')
    <div class="">
        <h1 class="admin-h1 mb-5 flex items-center">
            <a href="{{ $prev_url }}" title="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Sent Message Details</span>
        </h1>
        <div class="w-full bg-white shadow border px-3 py-2">
            <div class="flex flex-col lg:flex-row text-sm items-baseline">
                <div class="w-full lg:w-1/5 py-3 px-2 font-semibold">Name : </div>
                <div class="w-full lg:w-4/5 py-3 px-2">
                    <a href="{{ url('/admin/member/show/' . $sent->user->name) }}"
                        class="underline-text">{{ $sent->user->FullName }}</a>
                </div>
            </div>
            <div class="flex flex-col lg:flex-row text-sm items-baseline">
                <div class="w-full lg:w-1/5 py-3 px-2 font-semibold">Email ID : </div>
                <div class="w-full lg:w-4/5 py-3 px-2">{{ $sent->to }} </div>
            </div>
            <div class="flex flex-col lg:flex-row text-sm items-baseline">
                <div class="w-full lg:w-1/5 py-3 px-2 font-semibold">Mode : </div>
                <div class="w-full lg:w-4/5 py-3 px-2">{{ ucwords($sent->mode) }}</div>
            </div>
            @if ($sent->mode == 'mail')
                <div class="flex flex-col lg:flex-row text-sm items-baseline">
                    <div class="w-full lg:w-1/5 py-3 px-2 font-semibold">Subject : </div>
                    <div class="w-full lg:w-4/5 py-3 px-2">
                        @if ($sent->subject != null)
                            {{ $sent->subject }}
                        @else
                            --
                        @endif
                    </div>
                </div>
            @endif
            <div class="flex flex-col lg:flex-row text-sm items-baseline">
                <div class="w-full lg:w-1/5 py-3 px-2 font-semibold">Message : </div>
                <div class="w-full lg:w-4/5 py-3 px-2 leading-loose">{{ $sent->message }} </div>
            </div>
            @if ($sent->mode == 'mail')
                <div class="flex flex-col lg:flex-row text-sm items-baseline">
                    <div class="w-full lg:w-1/5 py-3 px-2 font-semibold">Attachments : </div>
                    @if ($sent->attachments == null)
                        <div class="w-full lg:w-4/5 py-3 px-2">--</div>
                    @else
                        <div class="w-full lg:w-4/5 py-3 px-2">
                            <a href="{{ $sent->AttachmentPath }}" class="btn btn-primary submit-btn cursor-pointer"
                                target="_blank">View</a>
                        </div>
                    @endif
                </div>
            @endif
            <div class="flex flex-col lg:flex-row text-sm items-baseline">
                <div class="w-full lg:w-1/5 py-3 px-2 font-semibold">Status : </div>
                <div class="w-full lg:w-4/5 py-3 px-2">{{ ucwords($sent->status) }}</div>
            </div>
            <div class="flex flex-col lg:flex-row text-sm items-baseline">
                <div class="w-full lg:w-1/5 py-3 px-2 font-semibold">Sent On : </div>
                <div class="w-full lg:w-4/5 py-3 px-2">{{ date('d-m-Y H:i:s', strtotime($sent->executed_at)) }} </div>
            </div>
        </div>
    </div>
@endsection
