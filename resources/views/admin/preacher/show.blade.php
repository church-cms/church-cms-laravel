@extends('layouts.admin.layout')

@section('content')
    <div class="">
        @include('partials.message')
        <div>
            <h1 class="admin-h1 mb-3 flex items-center">
                <a href="{{ url('/admin/preachers') }}" class="rounded-full bg-gray-100 p-2" title="Back">
                    <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
                </a>
                <span class="mx-3">Preacher Profile</span>
            </h1>
        </div>
        <div class="flex flex-col lg:flex-row">
            <div class="w-full lg:w-1/5 py-3">
                <!-- start -->
                <div class="bg-white rounded leading-relaxed px-3 py-3">
                    <div class="relative">
                        <div>
                            <img src="{{ $user->userprofile->AvatarPath }}" class="lg:w-full md:w-56 w-56 h-56 mx-auto">
                        </div>
                        <div class=" mx-auto py-2">
                            <ul class="flex justify-center">
                                <li class="mx-2">
                                    <a href="{{ url('/admin/preacher/edit/' . $user->name) }}" title="Edit Preacher"
                                        class="text-white text-xs flex items-center blue-bg rounded p-1">
                                        <img src="{{ url('uploads/icons/profile-edit.svg') }}" class="w-3 h-3">
                                        <span class="mx-1">Edit</span>
                                    </a>
                                </li>
                                <li class="mx-2">
                                    <a href="#" title="Delete Preacher"
                                        class=" text-white text-xs flex items-center bg-red-600 rounded p-1">
                                        <img src="{{ url('uploads/icons/p-delete.svg') }}" class="w-3 h-3">
                                        <span class="mx-1">Delete</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="my-4">
                        <p class="capitalize text-gray-600 text-sm">contact information :</p>
                        <ul class="list-reset text-xs leading-loose my-2">
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
                            <li class="flex py-1 items-center">
                                <img src="{{ url('uploads/icons/facebook.svg') }}" class="w-3 h-3">
                                <div class="w-full mx-2">
                                    <a href="#" class="blue-text">{{ $user->userprofile->facebook_id }}</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- end -->
            </div>
            <div class="w-full lg:w-4/5 lg:mx-8">
                <div class="flex flex-col lg:flex-row justify-between">
                    <h3 class="font-semibold text-3xl text-gray-700">{{ $user->FullName }}</h3>
                    <div class="my-3 flex flex-wrap text-xs">
                        @if (optional($user->userprofile)->status == 'inactive')
                            <a href="#" rel="{{ url('/admin/member/updateStatus/' . $user->name) }}"
                                class="capitalize text-white custom-green rounded px-2 py-1 font-medium activate my-1 lg:my-0 md:my-0"
                                value="active" id="status">Activate</a>
                        @else
                            <a href="#" rel="{{ url('/admin/member/updateStatus/' . $user->name) }}"
                                class="capitalize text-white bg-red-600 rounded px-2 py-1  font-medium activate my-1 lg:my-0 md:my-0"
                                value="inactive" id="status">Deactivate</a>
                        @endif

                        @if ($user->email != null)
                            @if ($user->email_verified == 1)
                                <a href="#" rel="{{ url('/admin/member/resetPassword/' . $user->id) }}"
                                    class="capitalize text-white blue-bg rounded px-2 py-1 ml-2 font-medium reset my-1 lg:my-0 md:my-0">reset
                                    Password</a>
                            @endif

                            @if ($user->email_verified != 1)
                                <a href="#" rel="{{ url('/admin/member/' . $user->id . '/verificationcode') }}"
                                    class="capitalize text-white blue-bg rounded px-2 py-1 ml-2 font-medium verify my-1 lg:my-0 md:my-0"
                                    id="verify_mail">verify email</a>
                            @endif
                        @endif

                        <a href="{{ url('/preacher/' . $user->id . '/impersonate') }}" target="_blank"
                            class="capitalize text-white blue-bg rounded px-2 py-1 ml-2 font-medium my-1 lg:my-0 md:my-0">Login
                            as Preacher</a>

                        <a href="{{ url('/admin/sermons/?q' . '=' . $user->name) }}"
                            class="capitalize text-white blue-bg rounded px-2 py-1 ml-2 font-medium my-1 lg:my-0 md:my-0">sermon</a>
                    </div>
                </div>
                <div class="leading-relaxed">
                    <p class="text-lg text-gray-700 font-semibold">ID: {{ $user->id }}</p>
                    <h3 class="font-semibold text-base text-gray-700 capitalize">{{ $user->name }}</h3>
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
                                    text: "Preacher Status Updated Successfully",
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
                    text: "Do you want to reset password for this user ?",
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
            $('.verify').on('click', function() {
                var link = $(this).attr('rel');
                //alert(link);
                swal({
                    icon: "info",
                    text: "Do you want to verify email for this user ?",
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
