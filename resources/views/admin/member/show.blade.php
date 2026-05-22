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
                        class="inline-flex items-center justify-center gap-1.5 w-20 h-8 text-xs font-semibold text-white bg-blue-500 hover:bg-blue-600 rounded-lg transition">
                        <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                        Edit
                    </a>
                    <form action="{{ url('/admin/member/delete', ['name' => $user->name]) }}"
                        method="POST" id="delete" class="flex m-0 p-0">
                        @csrf
                        @method('delete')
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-1.5 w-24 h-8 text-xs font-semibold text-white bg-red-500 hover:bg-red-600 rounded-lg transition">
                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a1 1 0 00-1-1h-4a1 1 0 00-1 1H5" />
                            </svg>
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

        {{-- ══════════════════════════════════
             PROFILE TABS  (pure Blade)
        ══════════════════════════════════ --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm mb-6 overflow-hidden">

            {{-- Tab nav --}}
            <div class="flex gap-0 border-b border-gray-200 overflow-x-auto">
                @php
                $tabs = [
                ['id'=>'myprofile', 'label'=>'My Profile'],
                ['id'=>'timeline', 'label'=>'Timeline'],
                ['id'=>'family', 'label'=>'Family'],
                ['id'=>'groups', 'label'=>'Assigned Groups'],
                ['id'=>'messages', 'label'=>'Messages'],
                ['id'=>'notes', 'label'=>'Notes'],
                ['id'=>'idcard', 'label'=>'IDCard']
                ];
                @endphp
                @foreach($tabs as $i => $tab)
                <button
                    data-tab="{{ $tab['id'] }}"
                    onclick="switchTab(this.dataset.tab)"
                    id="tab-btn-{{ $tab['id'] }}"
                    class="tab-btn px-5 py-3 text-xs font-medium whitespace-nowrap border-b-2 transition {{ $i === 0 ? 'active-tab' : 'inactive-tab' }}">
                    {{ $tab['label'] }}
                </button>
                @endforeach
            </div>

            {{-- ── Tab 1 : My Profile ─────────────────────── --}}
            <div id="tab-myprofile" class="tab-panel p-6">
                @php $profile = $user->userprofile; @endphp
                <ul class="space-y-3 text-sm">
                    @foreach([
                    ['age.svg', 'Age', $profile->age ?? '--'],
                    ['marriage.svg', 'Marriage Status', ucfirst($profile->marriage_status ?? '--')],
                    ['date.svg', 'Marriage Date', $profile->marriage_date ? date('d M Y', strtotime($profile->marriage_date)) : '--'],
                    ['member-ship.svg','Membership Type', ucfirst($profile->membership_type ?? '--')],
                    ['date.svg', 'Membership Start Date', $profile->membership_type === 'member' && $profile->membership_start_date ? date('d M Y', strtotime($profile->membership_start_date)) : '--'],
                    ] as [$icon, $label, $value])
                    <li class="flex items-center gap-3">
                        <img src="{{ url('uploads/icons/'.$icon) }}" class="w-4 h-4 flex-shrink-0">
                        <span class="text-gray-500 font-medium w-44 flex-shrink-0">{{ $label }} :</span>
                        <span class="text-gray-800">{{ $value }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- ── Tab 2 : Timeline ───────────────────────── --}}
            <div id="tab-timeline" class="tab-panel hidden p-4">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="text-left px-3 py-2 font-semibold text-gray-500">Date & Time</th>
                                <th class="text-left px-3 py-2 font-semibold text-gray-500">Action</th>
                                <th class="text-left px-3 py-2 font-semibold text-gray-500">Description</th>
                                <th class="text-left px-3 py-2 font-semibold text-gray-500">IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activitylog as $log)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-3 py-2">{{ $log->created_at ? $log->created_at->format('d M Y H:i') : '--' }}</td>
                                <td class="px-3 py-2 font-medium">{{ $log->log_name ?? '--' }}</td>
                                <td class="px-3 py-2">{{ $log->description ?? '--' }}</td>
                                <td class="px-3 py-2 text-gray-400">
                                    @php $props = is_array($log->properties) ? $log->properties : json_decode($log->properties, true); @endphp
                                    {{ $props['ip'] ?? '--' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-3 py-6 text-center text-gray-400">No records found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($activitylog)
                <div class="px-4 py-3">{{ $activitylog->appends(request()->except('timeline_page'))->links() }}</div>
                @endif
            </div>

            {{-- ── Tab 3 : Family ─────────────────────────── --}}
            <div id="tab-family" class="tab-panel hidden p-4">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="text-left px-3 py-2 font-semibold text-gray-500">Family Member</th>
                                <th class="text-left px-3 py-2 font-semibold text-gray-500">Relation</th>
                                <th class="text-left px-3 py-2 font-semibold text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($family_members as $member)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-3 py-2">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $member->userprofile->AvatarPath }}" class="w-8 h-8 rounded-full object-cover">
                                        <a href="{{ url('/admin/member/show/'.$member->name) }}"
                                            class="text-indigo-600 hover:underline font-medium">
                                            {{ $member->FullName }}
                                        </a>
                                    </div>
                                </td>
                                <td class="px-3 py-2 capitalize">{{ $member->userprofile->relation ?? '--' }}</td>
                                <td class="px-3 py-2">
                                    <a href="{{ url('/admin/member/edit/'.$member->name) }}">
                                        <img src="{{ url('uploads/icons/pencil.svg') }}" class="w-3.5 h-3.5">
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-3 py-6 text-center text-gray-400">No family members found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ── Tab 4 : Assigned Groups ─────────────────── --}}
            <div id="tab-groups" class="tab-panel hidden p-4">

                @if($grouplinks!=null)
                <div class="space-y-3">
                    @foreach($grouplinks as $gl)
                    <div class="flex items-center gap-4 border border-gray-100 rounded-lg px-4 py-3 hover:bg-gray-50">
                        <img src="{{ $gl->group->CoverImagePath ?? url('uploads/icons/group.svg') }}"
                            class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                        <div class="flex-1">
                            <a href="{{ url('/admin/group/show/'.$gl->group_id) }}"
                                class="text-sm font-semibold text-indigo-600 hover:underline">
                                {{ $gl->group->name ?? '--' }}
                            </a>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Role: <span class="font-medium text-gray-600 capitalize">{{ str_replace('_',' ',$gl->role ?? 'member') }}</span>
                            </p>
                        </div>
                        <span class="text-xs text-gray-400">
                            Started {{ $gl->created_at ? $gl->created_at->diffForHumans() : '--' }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-center text-sm text-gray-400 py-6">No groups assigned</p>
                @endif
            </div>

            {{-- ── Tab 5 : Messages ───────────────────────── --}}
            <div id="tab-messages" class="tab-panel hidden p-4">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="text-left px-3 py-2 font-semibold text-gray-500">Mode</th>
                                <th class="text-left px-3 py-2 font-semibold text-gray-500">Subject</th>
                                <th class="text-left px-3 py-2 font-semibold text-gray-500">Message</th>
                                <th class="text-left px-3 py-2 font-semibold text-gray-500">Attachments</th>
                                <th class="text-left px-3 py-2 font-semibold text-gray-500">Sent On</th>
                                <th class="text-left px-3 py-2 font-semibold text-gray-500">Status</th>
                                <th class="text-left px-3 py-2 font-semibold text-gray-500">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($messages as $msg)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-3 py-2 capitalize">{{ $msg->mode ?? '--' }}</td>
                                <td class="px-3 py-2">{{ $msg->subject ?? '--' }}</td>
                                <td class="px-3 py-2 max-w-xs truncate">{{ $msg->message ?? '--' }}</td>
                                <td class="px-3 py-2">
                                    @if($msg->attachments)
                                    <a href="{{ $msg->attachments }}" target="_blank" class="text-indigo-600 hover:underline">Download</a>
                                    @else --
                                    @endif
                                </td>
                                <td class="px-3 py-2">{{ $msg->executed_at ? \Carbon\Carbon::parse($msg->executed_at)->format('d M Y H:i') : '--' }}</td>
                                <td class="px-3 py-2">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                        {{ $msg->status === 'sent' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ ucfirst($msg->status ?? '--') }}
                                    </span>
                                </td>
                                <td class="px-3 py-2">
                                    <a href="{{ url('/admin/message/show/'.$msg->id) }}"
                                        class="text-indigo-600 hover:underline">View</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-3 py-6 text-center text-gray-400">No messages found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($messages->hasPages())
                <div class="px-4 py-3">{{ $messages->appends(request()->except('msg_page'))->links() }}</div>
                @endif
            </div>

            {{-- ── Tab 6 : Notes  (keep Vue — complex CRUD) ── --}}
            <div id="tab-notes" class="tab-panel hidden p-4">
                <notes url="{{ url('/') }}"
                    entity_id="{{ $user->id }}"
                    entity_name="user"
                    church_id="{{ $user->church_id }}">
                </notes>
            </div>
            {{-- ── Tab 6 : Notes  (ID Card) ── --}}
            <div id="tab-idcard" class="tab-panel hidden p-4">
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
        {{-- end profile tabs --}}
        <div class="bg-white shadow my-5 hidden" id="sms_div">
            <send-message url="{{ url('/') }}" name="{{ $user->name }}" tab="1" type="member">
            </send-message>
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

<style>
    .active-tab {
        border-color: #4f46e5;
        color: #4f46e5;
        font-weight: 600;
    }

    .inactive-tab {
        border-color: transparent;
        color: #6b7280;
    }

    .inactive-tab:hover {
        color: #374151;
    }
</style>

<script>
    function switchTab(id) {
        // hide all panels
        document.querySelectorAll('.tab-panel').forEach(function(el) {
            el.classList.add('hidden');
        });
        // reset all buttons
        document.querySelectorAll('.tab-btn').forEach(function(btn) {
            btn.classList.remove('active-tab');
            btn.classList.add('inactive-tab');
        });
        // show selected panel
        var panel = document.getElementById('tab-' + id);
        if (panel) panel.classList.remove('hidden');
        // activate selected button
        var btn = document.getElementById('tab-btn-' + id);
        if (btn) {
            btn.classList.remove('inactive-tab');
            btn.classList.add('active-tab');
        }
    }
</script>
@endpush