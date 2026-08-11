@extends('layouts.admin.layout')

@section('content')
<div class="flex items-center justify-between">
    <h1 class="admin-h1 flex items-center">
        <a href="{{ url('/admin/contacts') }}" title="Back" id="Back" class="rounded-full bg-gray-300 p-2">
            <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
        </a>
        <span class="mx-3">Contact Details</span>
    </h1>
</div>
@php

$properties=json_decode($contact->properties);
@endphp
<div class="bg-white p-4 w-full lg:w-full lg:mr-8 md:mr-8 custom-table py-4">
    <table class="w-full">
        <tr class="border-t-2 border-b-2">
            <td class="py-3 px-2">Name</td>
            <td class="py-3 px-2">{{ $contact->fullname }}</td>
        </tr>

        <tr class="border-t-2 border-b-2">
            <td class="py-3 px-2">Mobile Number</td>
            <td class="py-3 px-2">{{ $contact->mobile }} </td>
        </tr>

        <tr class="border-t-2 border-b-2">
            <td class="py-3 px-2">Email</td>
            <td class="py-3 px-2">{{ $contact->email }}</a></td>
        </tr>

        <tr class="border-t-2 border-b-2">
            <td class="py-3 px-2">Query</td>
            <td class="py-3 px-2">{{ $contact->query }}</td>
        </tr>

        <tr class="border-t-2 border-b-2">
            <td class="py-3 px-2">Submitted On</td>
            <td class="py-3 px-2">{{ date('d-m-Y H:i:s', strtotime($contact->date_of_submission)) }}</td>
        </tr>

        <tr class="border-t-2 border-b-2">
            <td class="py-3 px-2">Browser Details</td>
            <td class="py-3 px-2">
                <p class="my-2">{{ $properties->ip }}</p>
                <p>{{ $properties->details }}</p>
            </td>
        </tr>
    </table>
</div>
@endsection