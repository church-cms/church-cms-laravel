@extends('layouts.admin.layout')

@section('content')
<div class="">
    @include('partials.message')

    {{-- Back + Page title --}}
    <div class="mb-4">
        <h1 class="admin-h1 flex items-center">
            <a href="{{ $prev_url }}" class="rounded-full bg-gray-100 p-2 mr-3" title="Back">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            Person Profile
        </h1>
    </div>

    {{-- ══════════════════════════════════════════════
         FULL-WIDTH PROFILE HEADER CARD
    ══════════════════════════════════════════════ --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="flex flex-col md:flex-row gap-0">

            {{-- Avatar column --}}
            <div class="md:w-48 flex-shrink-0 flex flex-col items-center justify-start bg-gray-50 border-b md:border-b-0 md:border-r border-gray-100 p-6 gap-4">
                <img src="{{ $user->userprofile->AvatarPath }}"
                    alt="{{ $user->FullName }}"
                    class="w-32 h-32 rounded-full object-cover ring-4 ring-white shadow">

                {{-- Edit / Delete --}}
                <div class="flex gap-2">
                    <a href="{{ url('/admin/member/edit/' . $user->name) }}"
                        class="inline-flex items-center gap-1 text-xs font-medium text-white blue-bg rounded-lg px-3 py-1.5">
                        <img src="{{ url('uploads/icons/profile-edit.svg') }}" class="w-3 h-3">
                        Edit
                    </a>
                    <form action="{{ url('/admin/member/delete', ['name' => $user->name]) }}" method="POST"
                        class="inline-flex" id="delete">
                        @csrf
                        @method('delete')
                        <button type="submit"
                            class="inline-flex items-center gap-1 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg px-3 py-1.5">
                            <img src="{{ url('uploads/icons/p-delete.svg') }}" class="w-3 h-3">
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            {{-- Details column --}}
            <div class="flex-1 p-6">

                {{-- Name + ID + kebab menu --}}
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 leading-tight">{{ ucfirst($user->FullName) }}</h2>
                        <p class="text-sm text-gray-400 mt-0.5">ID: {{ $user->id }}</p>
                    </div>

                    {{-- ⋮ Kebab menu --}}
                    <div class="relative flex-shrink-0">
                        <button onclick="showsidebar('member-profile-menu')"
                            class="bg-gray-100 hover:bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center transition">
                            <svg viewBox="0 0 515.555 515.555" class="w-3 h-3 fill-current text-gray-600">
                                <path d="m303.347 18.875c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0" />
                                <path d="m303.347 212.209c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0" />
                                <path d="m303.347 405.541c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0" />
                            </svg>
                        </button>
                        <div id="member-profile-menu" class="hidden absolute top-10 right-0 bg-white shadow-lg rounded-xl border border-gray-100 z-20">
                            <div class="flex flex-col text-xs w-48 py-1">
                                @if (optional($user->userprofile)->status == 'inactive')
                                <a href="#" rel="{{ url('/admin/member/updateStatus/' . $user->name) }}"
                                    class="capitalize text-teal-600 px-4 py-2 font-medium activate hover:bg-gray-50"
                                    value="active" id="status">Activate</a>
                                @else
                                <a href="#" rel="{{ url('/admin/member/updateStatus/' . $user->name) }}"
                                    class="capitalize text-red-600 px-4 py-2 font-medium activate hover:bg-gray-50"
                                    value="inactive" id="status">Deactivate</a>
                                @endif

                                @if ($user->email != null)
                                @if ($user->email_verified == 1)
                                <a href="#" rel="{{ url('/admin/member/resetPassword/' . $user->id) }}"
                                    class="capitalize text-gray-700 px-4 py-2 font-medium reset hover:bg-gray-50">Reset Password</a>
                                @endif
                                @if ($user->email_verified != 1)
                                <a href="#" rel="{{ url('/admin/member/' . $user->id . '/verificationcode') }}"
                                    class="capitalize text-gray-700 px-4 py-2 font-medium verify hover:bg-gray-50" id="verify_mail">Verify Email</a>
                                @endif
                                @endif

                                @if ($status == 0)
                                <a href="#" rel="{{ url('/admin/member/subscribe/' . $user->name) }}"
                                    class="capitalize text-teal-600 px-4 py-2 font-medium subscribe hover:bg-gray-50"
                                    value="1" id="subscribe">Subscribe NewsLetter</a>
                                @else
                                <a href="#" rel="{{ url('/admin/member/subscribe/' . $user->name) }}"
                                    class="capitalize text-red-600 px-4 py-2 font-medium subscribe hover:bg-gray-50"
                                    value="0" id="subscribe">Unsubscribe NewsLetter</a>
                                @endif

                                <a href="#"
                                    class="capitalize text-gray-700 px-4 py-2 font-medium send_sms hover:bg-gray-50">Messaging</a>
                                <a href="{{ url('/admin/member/view/'.$user->name) }}"
                                    class="capitalize text-gray-700 px-4 py-2 font-medium hover:bg-gray-50">Generate Membership Card</a>
                                <a href="{{ url('/admin/member/add?ref_name=' . $user->name) }}"
                                    class="capitalize text-gray-700 px-4 py-2 font-medium hover:bg-gray-50">Add Family Member</a>
                                <a href="#"
                                    rel="{{ url('/admin/member/exit/' . $user->name) }}"
                                    data-name="{{ $user->name }}"
                                    class="capitalize text-gray-700 px-4 py-2 font-medium exit-member hover:bg-gray-50">Exit Member</a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Info grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-0 border-t border-gray-100 pt-4">

                    {{-- Basic Information --}}
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Basic Information</p>
                        <ul class="space-y-2 text-xs">
                            <li class="flex items-center gap-2">
                                <img src="{{ url('uploads/icons/family.svg') }}" class="w-3.5 h-3.5 flex-shrink-0">
                                <span class="text-gray-500 font-medium w-24 flex-shrink-0">Family</span>
                                <span class="text-blue-600 capitalize">
                                    {{ optional($user->userprofile)->family ?: '--' }}
                                </span>
                            </li>
                            <li class="flex items-center gap-2">
                                <img src="{{ url('uploads/icons/date.svg') }}" class="w-3.5 h-3.5 flex-shrink-0">
                                <span class="text-gray-500 font-medium w-24 flex-shrink-0">Date of Birth</span>
                                <span class="text-gray-800">
                                    {{ optional($user->userprofile)->date_of_birth ? date('d M Y', strtotime($user->userprofile->date_of_birth)) : '--' }}
                                </span>
                            </li>
                            <li class="flex items-center gap-2">
                                <img src="{{ url('uploads/icons/employee.svg') }}" class="w-3.5 h-3.5 flex-shrink-0">
                                <span class="text-gray-500 font-medium w-24 flex-shrink-0">Occupation</span>
                                <span class="text-gray-800 capitalize">
                                    @if(optional($user->userprofile)->sub_occupation)
                                    {{ optional($user->userprofile)->profession }} ({{ optional($user->userprofile)->sub_occupation }})
                                    @else
                                    {{ ucwords(str_replace('_',' ', optional($user->userprofile)->profession ?? '--')) }}
                                    @endif
                                </span>
                            </li>
                            <li class="flex items-center gap-2">
                                <img src="{{ url('uploads/icons/gender.svg') }}" class="w-3.5 h-3.5 flex-shrink-0">
                                <span class="text-gray-500 font-medium w-24 flex-shrink-0">Gender</span>
                                <span class="text-gray-800 capitalize">{{ optional($user->userprofile)->gender ?: '--' }}</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <img src="{{ url('uploads/icons/age.svg') }}" class="w-3.5 h-3.5 flex-shrink-0">
                                <span class="text-gray-500 font-medium w-24 flex-shrink-0">Aadhaar No.</span>
                                <span class="text-gray-800">{{ optional($user->userprofile)->aadhar_number ?: '--' }}</span>
                            </li>
                        </ul>
                    </div>

                    {{-- Contact Information --}}
                    <div class="mt-4 sm:mt-0">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Contact Information</p>
                        <ul class="space-y-2 text-xs">
                            <li class="flex items-start gap-2">
                                <img src="{{ url('uploads/icons/home-address.svg') }}" class="w-3.5 h-3.5 flex-shrink-0 mt-0.5">
                                <span class="text-gray-800 leading-relaxed">
                                    {{ optional($user->userprofile)->address ?: '--' }}
                                </span>
                            </li>
                            <li class="flex items-center gap-2">
                                <img src="{{ url('uploads/icons/telephone.svg') }}" class="w-3.5 h-3.5 flex-shrink-0">
                                <a href="tel:{{ $user->mobile_no }}" class="blue-text">{{ $user->mobile_no ?: '--' }}</a>
                            </li>
                            <li class="flex items-center gap-2">
                                <img src="{{ url('uploads/icons/email.svg') }}" class="w-3.5 h-3.5 flex-shrink-0">
                                <a href="{{ url('/admin/member/sendMessage/' . $user->name) }}"
                                    class="blue-text truncate">{{ $user->email ?: '--' }}</a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- ══════════════════════════════════════════════ --}}

    <div class="w-full">
        <div class="bg-white shadow my-5">
            <profile-tab url="{{ url('/') }}" entity_id="{{ $user->id }}"
                church_id="{{ $user->church_id }}" name="{{ $user->name }}" mode="member"
                type="{{ $user->userprofile->membership_type }}"></profile-tab>




            <portal-target name="profile"></portal-target>
        </div>
        <!-- <div class="bg-white shadow my-5">
         <family-tree url="{{ url('/') }}" entity_id="{{ $user->id }}" church_id="{{ $user->church_id }}" name="{{ $user->name }}" mode="member" type="{{ $user->userprofile->membership_type }}"></family-tree>
        </div> -->
        <div class="bg-white shadow my-5 hidden" id="sms_div">
            <send-message url="{{ url('/') }}" name="{{ $user->name }}" tab="1" type="member">
            </send-message>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide ">Membership Id Card</h3>&nbsp; <a href="{{ url('/admin/member/print/'.$user->name) }}"
                    class="text-xs font-semibold text-white bg-blue-500 px-3 py-1 rounded cursor-pointer">
                    Print
                </a>
            </div>
            @include('member.idcard.idcard')
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


    $(document).ready(function() {
        $('.subscribe').on('click', function() {
            var link = $(this).attr('rel');
            var status = $(this).attr('value');
            //alert(link);
            swal({
                icon: "info",
                text: "Do you want to change newsletter status for this member ?",
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
            var name = $(this).data('name');
            //alert(link);
            swal({
                icon: "info",
                text: "Do you want to exit this member ?",
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
                            window.location.href = "/admin/member/exit/" + name;
                        }
                    })
                } else {
                    swal("Cancelled");
                }
            });
        });
    });

    $(document).ready(function() {
        $('.send_sms').on('click', function(e) {
            e.preventDefault();

            // Close the kebab dropdown
            $('#member-profile-menu').addClass('hidden');

            // Show the messaging panel
            var $div = $('#sms_div');
            $div.removeClass('hidden').addClass('block');

            // Smooth scroll into view
            $('html, body').animate({
                scrollTop: $div.offset().top - 80
            }, 400);
        });
    });
</script>
@endpush