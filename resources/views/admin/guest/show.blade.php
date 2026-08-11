@extends('layouts.admin.layout')

@section('content')
    <div class="">
        @include('partials.message')
        <div>
            <h1 class="admin-h1 mb-3 flex items-center">
                <a href="{{ $prev_url }}" class="rounded-full bg-gray-100 p-2" title="Back">
                    <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
                </a>
                <span class="mx-3">Person Profile</span>
            </h1>
        </div>
        <div class="flex flex-col lg:flex-row">
            <div class="w-full lg:w-1/5 py-3">
                <div class="bg-white rounded leading-relaxed px-3 py-3">
                    <div class="relative">
                        <div>
                            <img src="{{ $user->userprofile->AvatarPath }}" class="lg:w-full md:w-56 w-56 h-56 mx-auto">
                        </div>
                        <div class=" mx-auto py-2">
                            <ul class="flex justify-center">
                                <li class="mx-2">
                                    <a href="{{ url('/admin/guest/edit/' . $user->name) }}" title="Edit Guest"
                                        class="text-white text-xs flex items-center blue-bg rounded p-1" id="edit">
                                        <img src="{{ url('uploads/icons/profile-edit.svg') }}" class="w-3 h-3">
                                        <span class="mx-1">Edit</span>
                                    </a>
                                </li>

                                <li class="mx-2">
                                    <!-- <a href="#" title="Delete Guest" class=" text-white text-xs flex items-center blue-bg rounded p-1"><img src="{{ url('uploads/icons/p-delete.svg') }}" class="w-3 h-3"><span class="mx-1">Delete</span></a> -->
                                    <form action="{{ url('/admin/guest/delete', ['name' => $user->name]) }}" method="POST"
                                        class="text-white text-xs flex items-center bg-red-600 rounded p-1" id="delete">
                                        @csrf
                                        @method('delete')
                                        <img src="{{ url('uploads/icons/p-delete.svg') }}" class="w-3 h-3">
                                        <button type="submit" class="mx-1">Delete</button>
                                    </form>
                                </li>
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
                                        @if (optional($user->userprofile)->sub_occupation != '')
                                            {{ optional($user->userprofile)->profession }} (
                                            {{ optional($user->userprofile)->sub_occupation }} )
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
                                    <a href="{{ url('/admin/guest/sendMessage/' . $user->name) }}"
                                        class="blue-text">{{ $user->email }}</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-4/5 lg:mx-8">
                <div class="flex lg:items-center lg:justify-between flex-col lg:flex-row relative">
                    <h3 class="font-semibold text-3xl text-gray-700">{{ ucfirst($user->FullName) }}</h3>

                    <div onclick="showsidebar('member-profile-menu')"
                        class="bg-white rounded-full w-6 h-6 ml-auto flex items-center justify-center cursor-pointer">
                        <svg id="Capa_1" enable-background="new 0 0 515.555 515.555" height="512"
                            viewBox="0 0 515.555 515.555" width="512" xmlns="http://www.w3.org/2000/svg"
                            class="w-3 h-3 fill-current text-gray-600">
                            <path
                                d="m303.347 18.875c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0">
                            </path>
                            <path
                                d="m303.347 212.209c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0">
                            </path>
                            <path
                                d="m303.347 405.541c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0">
                            </path>
                        </svg>
                    </div>
                    <div id="member-profile-menu" class="hidden absolute top-0 right-0 bg-white shadow mt-10 rounded">
                        <div class="flex flex-col text-xs w-40 my-1">
                            @if (optional($user->userprofile)->status == 'inactive')
                                <a href="#" rel="{{ url('/admin/guest/updateStatus/' . $user->name) }}"
                                    class="capitalize text-teal-500 rounded px-4 py-1 font-medium activate my-1 lg:my-0 md:my-0 mr-2"
                                    value="active" id="status">Activate</a>
                            @else
                                <a href="#" rel="{{ url('/admin/guest/updateStatus/' . $user->name) }}"
                                    class="capitalize text-red-600 rounded px-4 py-1 font-medium activate my-1 lg:my-0 md:my-0 mr-2"
                                    value="inactive" id="status">Deactivate</a>
                            @endif

                            <!-- <a href="#" rel="{{ url('/admin/guest/resetPassword/' . $user->id) }}" class="capitalize text-gray-700 rounded px-4 py-1 mr-2 font-medium reset my-1 lg:my-0 md:my-0">reset Password</a> -->

                            @if ($user->email_verified != 1)
                                <a href="#" rel="{{ url('/admin/guest/' . $user->id . '/verificationcode') }}"
                                    class="capitalize text-gray-700 rounded px-4 py-1 mr-2 font-medium verify"
                                    id="verify_mail">verify email</a>
                            @endif

                            @if ($status == 0)
                                <a href="#" rel="{{ url('/admin/guest/subscribe/' . $user->name) }}"
                                    class="capitalize text-teal-500 rounded px-2 py-1 ml-2 font-medium subscribe my-1 lg:my-0 md:my-1"
                                    value="1" id="subscribe">Subscribe NewsLetter</a>
                            @else
                                <a href="#" rel="{{ url('/admin/guest/subscribe/' . $user->name) }}"
                                    class="capitalize text-red-600 rounded px-2 py-1 ml-2 font-medium subscribe my-1 lg:my-0 md:my-1"
                                    value="0" id="subscribe">Unsubscribe NewsLetter</a>
                            @endif

                            <!-- <a href="{{ url('/admin/guest/sendMessage/' . $user->name) }}" class="capitalize text-white blue-bg rounded px-2 py-1 ml-2 font-medium">send message</a> -->

                            <a href="#"
                                class="capitalize text-gray-700 rounded px-2 py-1 ml-2 font-medium send_sms my-1 lg:my-0 md:my-1">messaging</a>

                            <!-- <a href="{{ url('/admin/guest/add?ref_name=' . $user->name) }}" class="capitalize text-gray-700 rounded px-2 py-1 ml-2 font-medium my-1 lg:my-0 md:my-1">add family member</a> -->

                            <a href="#" rel="{{ url('/admin/guest/exit/' . $user->name) }}"
                                class="capitalize text-gray-700 rounded px-2 py-1 ml-2 font-medium exit-member my-1 lg:my-0 md:my-1">exit
                                guest</a>
                        </div>
                    </div>

                    <div class="my-3 flex text-xs flex-wrap">
                    </div>
                </div>
                <div class="leading-relaxed">
                    <p class="text-lg text-gray-700 font-semibold">ID: {{ $user->id }}</p>
                </div>
                <div class="bg-white shadow my-5">
                    <guest-profile-tab url="{{ url('/') }}" entity_id="{{ $user->id }}"
                        church_id="{{ $user->church_id }}" name="{{ $user->name }}" mode="guest"
                        type="{{ $user->userprofile->membership_type }}"></guest-profile-tab>

                    <portal-target name="guest_profile"></portal-target>
                </div>
                <!-- <div class="bg-white shadow my-5">
         <family-tree url="{{ url('/') }}" entity_id="{{ $user->id }}" church_id="{{ $user->church_id }}" name="{{ $user->name }}" mode="member" type="{{ $user->userprofile->membership_type }}"></family-tree>
        </div> -->
                <div class="bg-white shadow my-5 hidden" id="sms_div">
                    <send-message url="{{ url('/') }}" name="{{ $user->name }}" tab="1" type="guest">
                    </send-message>
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
                                    text: "Guest Status Updated Successfully",
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
                    text: "Do you want to reset password for this guest ?",
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
                    text: "Do you want to verify email for this guest ?",
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


        $(document).ready(function() {
            $('.subscribe').on('click', function() {
                var link = $(this).attr('rel');
                var status = $(this).attr('value');
                //alert(link);
                swal({
                    icon: "info",
                    text: "Do you want to change newsletter status for this guest ?",
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
                                    text: "NewsLetter Status Updated Successfully",
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
            $('.exit-member').on('click', function() {
                var link = $(this).attr('rel');
                var name = {!! json_encode($user->name) !!};
                //alert(link);
                swal({
                    icon: "info",
                    text: "Do you want to exit this guest ?",
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
                                //alert(name);
                                window.location.href = "/admin/guest/exit/" + name;
                            }
                        })
                    } else {
                        swal("Cancelled");
                    }
                });
            });
        });

        $(document).ready(function() {
            $('.send_sms').on('click', function() {
                if ($('#sms_div').hasClass('hidden')) {
                    $('#sms_div').addClass('block').removeClass('hidden');
                }
            });
        });
    </script>
@endpush
