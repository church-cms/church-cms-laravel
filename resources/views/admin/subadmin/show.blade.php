@extends('layouts.admin.layout')

@section('content')
    <div class="">
        @include('partials.message')
        <div>
            <h1 class="admin-h1 mb-3 flex items-center">
                <a href="{{ url('/admin/subadmins') }}" class="rounded-full bg-gray-100 p-2" title="Back">
                    <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
                </a>
                <span class="mx-3">Sub Admin Profile</span>
            </h1>
        </div>
        <div class="flex flex-col lg:flex-row">
            <div class="w-full lg:w-1/5 py-3">
                <!-- start -->
                <div class="bg-white  rounded leading-relaxed  px-3 py-3">
                    <div class="relative">
                        <div>
                            <img src="{{ $user->userprofile->AvatarPath }}" class="lg:w-full md:w-56 w-56 h-56 mx-auto">
                        </div>
                        <div class=" mx-auto py-2">
                            <ul class="flex justify-center">
                                <li class="mx-2">
                                    <a href="{{ url('/admin/subadmin/edit/' . $user->name) }}" title="Edit Member"
                                        class="text-white text-xs flex items-center blue-bg rounded p-1" id="edit">
                                        <img src="{{ url('uploads/icons/profile-edit.svg') }}" class="w-3 h-3">
                                        <span class="mx-1">Edit</span>
                                    </a>
                                </li>

                                <!-- <li class="mx-2">
                                        <form action="{{ url('/admin/subadmin/delete', ['name' => $user->name]) }}" method="POST" class="text-white text-xs flex items-center blue-bg rounded p-1" id="delete">
                                            @csrf
                                            @method('delete')
                                            <img src="{{ url('uploads/icons/p-delete.svg') }}" class="w-3 h-3">
                                            <button type="submit" class="mx-1">Delete</button>
                                        </form>
                                    </li> -->
                            </ul>
                        </div>
                    </div>
                    <div class="my-2">
                        <p class="capitalize text-gray-600 text-sm">basic information :</p>
                        <ul class="list-reset text-xs leading-relaxed my-2">
                            <li class="flex py-1">
                                <div class="flex items-center">
                                    <img src="{{ url('uploads/icons/date.svg') }}" class="w-3 h-3">
                                    <span class="mx-2 text-gray-700 font-medium">Date Of Birth :</span>
                                </div>
                                <div class="">
                                    <p>{{ date('d-m-Y', strtotime(optional($user->userprofile)->date_of_birth)) }}</p>
                                </div>
                            </li>
                            <li class="flex py-1">
                                <div class="flex items-center">
                                    <img src="{{ url('uploads/icons/employee.svg') }}" class="w-3 h-3">
                                    <span class="mx-2 text-gray-700 font-medium">Occupation :</span>
                                </div>
                                <div class="">
                                    <p class="capitalize">
                                        @if ($user->userprofile->sub_occupation != '')
                                            {{ ucwords(str_replace('_', ' ', $user->userprofile->profession)) }} (
                                            {{ ucwords($user->userprofile->sub_occupation) }} )
                                        @else
                                            {{ ucwords(str_replace('_', ' ', $user->userprofile->profession)) }}
                                        @endif
                                    </p>
                                </div>
                            </li>
                            <li class="flex py-1">
                                <div class="flex items-center">
                                    <img src="{{ url('uploads/icons/gender.svg') }}" class="w-3 h-3">
                                    <span class="mx-2 text-gray-700 font-medium">Gender :</span>
                                </div>
                                <div class="">
                                    <p class="capitalize">{{ optional($user->userprofile)->gender }}</p>
                                </div>
                            </li>
                            <li class="flex py-1">
                                <div class="flex items-center">
                                    <img src="{{ url('uploads/icons/age.svg') }}" class="w-3 h-3">
                                    <span class="mx-2 text-gray-700 font-medium">Aadhaar Number :</span>
                                </div>
                                <div class="">
                                    <p class="capitalize">
                                        @if (optional($user->userprofile)->aadhar_number != '')
                                            {{ optional($user->userprofile)->aadhar_number }}
                                        @else
                                            --
                                        @endif
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="my-4">
                        <p class="capitalize text-gray-600 text-sm">contact information :</p>
                        <ul class="list-reset text-xs leading-loose my-2">
                            <li class="flex items-baseline py-1">
                                <img src="{{ url('uploads/icons/home-address.svg') }}" class="w-3 h-3">
                                <div class="w-full mx-2">
                                    <p class="text-gray-700 leading-normal">
                                        @if ($user->userprofile->address == null)
                                            --
                                        @else
                                            {{ optional($user->userprofile)->address }}
                                        @endif
                                    </p>
                                </div>
                            </li>
                            <li class="flex py-1 items-center">
                                <img src="{{ url('uploads/icons/telephone.svg') }}" class="w-3 h-3">
                                <div class="w-full mx-2">
                                    <a href="#" class="blue-text">{{ $user->mobile_no }}</a>
                                </div>
                            </li>
                            <li class="flex py-1 items-center">
                                <img src="{{ url('uploads/icons/email.svg') }}" class="w-3 h-3">
                                <div class="w-full mx-2">
                                    <a href="{{ url('/admin/member/sendMessage/' . $user->name) }}"
                                        class="blue-text">{{ $user->email }}</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- end -->
            </div>
            <div class="w-full lg:w-4/5 lg:mx-8">
                <div class="flex lg:items-center lg:justify-between flex-col lg:flex-row">
                    <h3 class="font-semibold text-3xl text-gray-700">{{ ucwords($user->FullName) }}</h3>
                    <div class="my-3 flex text-xs">
                        @if (optional($user->userprofile)->status == 'inactive')
                            <a href="#" rel="{{ url('/admin/member/updateStatus/' . $user->name) }}"
                                class="capitalize text-white custom-green rounded px-2 py-1 font-medium activate"
                                value="active" id="status">Activate</a>
                        @else
                            <a href="#" rel="{{ url('/admin/member/updateStatus/' . $user->name) }}"
                                class="capitalize text-white bg-red-600 rounded px-2 py-1  font-medium activate"
                                value="inactive" id="status">Deactivate</a>
                        @endif

                        <a href="#" rel="{{ url('/admin/member/resetPassword/' . $user->id) }}"
                            class="capitalize text-white blue-bg rounded px-2 py-1 ml-2 font-medium reset">reset
                            Password</a>

                        @if ($user->email_verified != 1)
                            <a href="#" rel="{{ url('/admin/member/' . $user->id . '/verificationcode') }}"
                                class="capitalize text-white blue-bg rounded px-2 py-1 ml-2 font-medium verify"
                                id="verify_mail">verify email</a>
                        @endif
                    </div>
                </div>
                <div class="leading-relaxed">
                    <p class="text-lg text-gray-700 font-semibold">ID: {{ $user->id }}</p>
                </div>
                <div class="bg-white shadow my-5">
                    <profile-tab url="{{ url('/') }}" entity_id="{{ $user->id }}"
                        church_id="{{ $user->church_id }}" name="{{ $user->name }}" mode="subadmin"></profile-tab>

                    <portal-target name="profile"></portal-target>
                </div>

                <div class="bg-white shadow my-5">
                    <div class="px-4 py-3 border-b">
                        <h3 class="text-sm font-semibold text-gray-700">Roles &amp; Permissions</h3>
                    </div>
                    <div class="px-4 py-4">
                        <subadmin-permissions url="{{ url('/') }}" name="{{ $user->name }}"></subadmin-permissions>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.activate').on('click', function() {
                var link = $(this).attr('rel');
                var status = $(this).attr('value');
                //alert(status);
                swal({
                    icon: "info",
                    text: "Do you want to change the status ?",
                    buttons: {
                        cancel: true,
                        confirm: true,
                    },
                    allowOutsideClick: false,
                }).then((willChange) => {
                    if (willChange) {
                        $.ajax({
                            url: link,
                            data: {
                                status: status
                            },
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                //alert(ans);
                                swal({
                                    icon: "success",
                                    text: "Member Status Updated Successfully",
                                }).then(function() {
                                    window.location.reload();
                                });
                            }
                        })
                    } else {
                        swal("Cancelled");
                    }
                });
            });
        });

        $(document).ready(function() {
            $('.reset').on('click', function() {
                var link = $(this).attr('rel');
                //alert(link);
                swal({
                    icon: "info",
                    text: "Do you want to reset password for this member ?",
                    buttons: {
                        cancel: true,
                        confirm: true,
                    },
                    allowOutsideClick: false,
                }).then((willChange) => {
                    if (willChange) {
                        $.ajax({
                            url: link,
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                //alert(ans);
                                swal({
                                    icon: "success",
                                    text: "Check your email to reset the password",
                                    showConfirmButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                }).then(function() {
                                    //window.location.reload();
                                });
                            }
                        })
                    } else {
                        swal("Cancelled");
                    }
                });
            });
        });

        $(document).ready(function() {
            $('.verify').on('click', function() {
                var link = $(this).attr('rel');
                //alert(link);
                swal({
                    icon: "info",
                    text: "Do you want to verify email for this member ?",
                    buttons: {
                        cancel: true,
                        confirm: true,
                    },
                    allowOutsideClick: false,
                }).then((willChange) => {
                    if (willChange) {
                        $.ajax({
                            url: link,
                            type: "GET",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                //alert(ans);
                                swal({
                                    icon: "success",
                                    text: "Verification code sent Successfully",
                                    showConfirmButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                }).then(function() {
                                    window.location.reload();
                                });
                            }
                        })
                    } else {
                        swal("Cancelled");
                    }
                });
            });
        });
    </script>
@endpush
