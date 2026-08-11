@extends('layouts.admin.layout')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="admin-h1 flex items-center">
            <a href="{{ $prev_url }}" title="Back" id="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Campaign Details</span>
        </h1>
    </div>
    <div class="bg-white shadow my-5">
        <div class="px-4 py-4">
            <div class="w-full lg:mr-8 md:mr-8 custom-table">
                <!--  py-4 -->
                <table class="w-full">
                    <tr class="border-t border-">
                        <td class="py-3 px-2 font-semibold">Name</td>
                        <td class="py-3 px-2">{{ $campaign->name }}</td>
                    </tr>

                    <tr class="border-t border-b">
                        <td class="py-3 px-2 font-semibold">Description</td>
                        <td class="py-3 px-2">{{ $campaign->description }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between">
        <h1 class="admin-h1 flex items-center">
            <span class="">Attached Emails</span>
        </h1>

        <a href="{{ url('admin/campaign/attachemail/' . $campaign->id) }}" id="edit"
            class="no-underline text-white px-4 mx-1 flex items-center custom-green py-1 justify-center rounded">
            <span class="mx-1 text-sm font-semibold">Add</span>
            <img src="{{ url('uploads/icons/plus.svg') }}" class="w-3 h-3">
        </a>
    </div>
    <div class="px-3 py-3 bg-white shadow my-3">
        <table class="w-full border custom-table">
            <thead class="bg-grey-light">
                <tr class="border-t border-b">
                    <th class="text-left text-sm px-2 py-2 text-grey-darker">Email</th>
                    <th width="15%" class="text-left text-sm px-2 py-2 text-grey-darker">Action</th>
                </tr>
            </thead>
            @if (count($campaignemails) > 0)
                @foreach ($campaignemails as $campaignemail)
                    <tbody class="bg-grey-light">
                        <td class="py-3 px-2">{{ $campaignemail->email->subject }}</td>
                        <td class="py-3 px-2">
                            <div class="flex items-center">
                                <a href="{{ url('/admin/campaign/attachemail/show/' . $campaignemail->id) }}" title="View">
                                    <img src="{{ url('/uploads/icons/show.svg') }}"
                                        class="w-4 h-4 fill-current text-black-500 mx-1">
                                </a>

                                <a href="{{ url('/admin/campaign/attachemail/edit/' . $campaignemail->id) }}" title="Edit">
                                    <img src="{{ url('/uploads/icons/edit1.svg') }}"
                                        class="w-4 h-4 fill-current text-black-500 mx-1">
                                </a>

                                <a href="#" rel="{{ url('/admin/campaign/attachemail/delete/' . $campaignemail->id) }}"
                                    id="delete-campaign-email" title="Delete">
                                    <img src="{{ url('/uploads/icons/delete1.svg') }}"
                                        class="w-4 h-4 fill-current text-black-500 mx-1">
                                </a>
                            </div>
                        </td>
                    </tbody>
                @endforeach
            @else
                <tbody class="bg-grey-light">
                    <tr class="border-t-2 border-b-2">
                        <td colspan="2" style="text-align: center;">No records found</td>
                    </tr>
                </tbody>
            @endif
        </table>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#delete-campaign-email').on('click', function() {
                var link = $(this).attr('rel');
                swal({
                    icon: "info",
                    text: "Do you want to delete this Campaign Email ?",
                    buttons: {
                        cancel: true,
                        confirm: true,
                    },
                    allowOutsideClick: false,
                }).then((willChange) => {
                    if (willChange) {
                        $.ajax({
                            url: link,
                            type: "DELETE",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function() {
                                swal({
                                    icon: "success",
                                    text: "Campaign Email Deleted Successfully",
                                }).then(function() {
                                    window.location.reload();
                                });
                            }
                        });
                    } else {
                        swal("Cancelled");
                    }
                });
            });
        });
    </script>
@endpush
