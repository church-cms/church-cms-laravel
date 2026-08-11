@extends('layouts.admin.layout')
@section('content')
    <div class="relative">
        <div class="flex lg:items-center md:items-center justify-between flex-col lg:flex-row md:flex-row">
            <div class="">
                <h1 class="admin-h1">Contacts</h1>
            </div>
            <form method="GET" action="{{ url('/admin/contacts') }}" role="search" enctype="multipart/form-data"
                class="mb-0">
                <div class="">
                    <div class="flex lg:justify-end md:justify-end items-center">
                        <div class="search relative w-48">
                            <input type="text" name="search" class="tw-form-control w-full relative" placeholder="Search"
                                value="{{ \Request('search') }}">
                            <button class="no-underline text-white px-4 mx-1 py-1 absolute right-0 focus:outline-none">
                                <img src="{{ url('uploads/icons/search.svg') }}"
                                    class="w-4 h-4 absolute right-0 mt-2 mx-1 top-0">
                            </button>
                        </div>
                        <div class="date-select date-select_none dashboard-reset mx-1 lg:mx-0 md:mx-0">
                            <a href="{{ url('/admin/contacts') }}" id="do-reset"
                                class="text-sm border bg-gray-100 text-grey-darkest py-1 px-4">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @include('partials.message')
        <div class="custom-table bg-white p-4 my-3">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-grey-light">
                        <tr class="border-t-2 border-b-2">
                            <th class="text-left text-sm px-2 py-2 text-grey-darker">Name</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker">Mobile</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker">Email</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker">Query</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker">Action</th>
                        </tr>
                    </thead>
                    @if (count($contacts) == '')
                        <tr class="border-t-2 border-b-2">
                            <td colspan="5" style="text-align: center;">No records found</td>
                        </tr>
                    @else
                        @foreach ($contacts as $contact)
                            <tbody class="bg-grey-light">
                                <td class="py-3 px-2">{{ $contact->fullname }}</td>
                                <td class="py-3 px-2">{{ $contact->mobile }}</td>
                                <td class="py-3 px-2">{{ $contact->email }}</td>
                                <td class="py-3 px-2">{{ \Str::limit($contact->query, 50, '...') }}</td>
                                <td class="py-3 px-2">
                                    <a href="{{ url('/admin/contact/show/' . $contact->id) }}"
                                        class="btn btn-primary submit-btn cursor-pointer" title="View">View</a>
                                </td>
                            </tbody>
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
        {{ $contacts->links() }}
    </div>
@endsection
