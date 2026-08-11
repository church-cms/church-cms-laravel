@extends('layouts.admin.layout')

@section('content')
    <div class="w-full lg:w-11/12">
        <h1 class="admin-h1 mb-5 flex items-center">
            <a href="{{ url('/admin/preacher/show/' . $userprofile->user->name) }}" title="Back"
                class="rounded-full bg-gray-300 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">New Message</span>
        </h1>
        <div class="my-3 flex text-xs justify-end">
            <a href="{{ url('/admin/preacher/messages') }}" class="rounded-full bg-gray-300 p-2">
                <span class="mx-3">View Sent Messages</span>
            </a>
        </div>
        <div class="bg-white shadow border border-grey p-5">
            @include('partials.message')
            <form method="POST" action="{{ url('/admin/preacher/sendMessage/' . $userprofile->user->name) }}"
                enctype="multipart/form-data">
                @csrf
                <div class="my-5">
                    <div class="">
                        <div class="w-1/4">
                            <label for="subject" class="tw-form-label">Subject</label>
                        </div>
                        <div class="my-2 w-2/5">
                            <input type="text" name="subject" class="tw-form-control w-full">
                            <span class="text-red-500 text-xs font-semibold">{{ $errors->first('subject') }}</span>
                        </div>
                    </div>
                </div>

                <div class="my-5">
                    <div class="">
                        <div class="w-1/4">
                            <label for="message" class="tw-form-label">Message</label>
                        </div>
                        <div class="w-2/5 my-2">
                            <textarea type="text" name="message" class="tw-form-control w-full" rows="10"></textarea>
                            <span class="text-red-500 text-xs font-semibold">{{ $errors->first('message') }}</span>
                        </div>
                    </div>
                </div>

                <div class="my-5">
                    <div class="">
                        <div class="w-1/4">
                            <label for="attachments" class="tw-form-label">Attachments</label>
                        </div>
                        <div class="w-2/5 my-2">
                            <input type="file" name="attachments" id="attachments" class="tw-form-control w-full">
                            <span class="text-red-500 text-xs font-semibold">{{ $errors->first('attachments') }}</span>
                        </div>
                    </div>
                </div>

                <div class="my-6">
                    <input type="submit" value="Send" name="submit" class="btn btn-primary submit-btn cursor-pointer">
                </div>
            </form>
        </div>
    </div>
@endsection
