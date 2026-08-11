@extends('layouts.admin.layout')

@section('content')
    <div class="w-1/2 lg:mr-8 md:mr-8">
        <div>
            <h1 class="admin-h1 flex items-center">
                <a href="{{ url('/admin/helps') }}" title="Back" class="rounded-full bg-gray-100 p-2">
                    <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
                </a>
                <span class="mx-3">Help Details</span>
            </h1>
        </div>
    </div>
    <div class="bg-white my-3">
        <table class="w-full">
            <tr class="">
                <td class="py-3 px-2" style="width: 20%;">User Name : </td>
                <td class="py-3 px-2">
                    <a href="{{ url('/admin/member/show/' . $help->user->name) }}">{{ $help->user->FullName }}</a>
                </td>
            </tr>
            <tr class="">
                <td class="py-3 px-2">Title : </td>
                <td class="py-3 px-2">{{ $help->title }}</td>
            </tr>
            <tr class="">
                <td class="py-3 px-2">Description : </td>
                <td class="py-3 px-2">{{ $help->description }}</td>
            </tr>
            <tr class="">
                <td class="py-3 px-2">Contact details : </td>
                <td class="py-3 px-2">{{ $help->contact_details }}</td>
            </tr>
            <tr class="">
                <td class="py-3 px-2">Status : </td>
                <td class="py-3 px-2">
                    @if ($help->status == 'pending')
                        Pending
                    @elseif($help->status == 'approve')
                        Approved
                    @elseif($help->status == 'reject')
                        Rejected
                    @else
                        Closed
                    @endif
                </td>
            </tr>
            @if ($help->status == 'reject')
                <tr class="">
                    <td class="py-3 px-2">Comments : </td>
                    <td class="py-3 px-2">{{ $help->comments }}</td>
                </tr>
            @endif
        </table>
    </div>
@endsection
