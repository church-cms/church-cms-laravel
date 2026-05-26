@extends('layouts.admin.layout')

@section('content')
<div class="w-full">

    {{-- Back --}}
    <div class="mb-4">
        <a href="{{ url('/admin/groups') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left text-xs"></i> Back to Groups
        </a>
    </div>

    @include('partials.message')

    {{-- ── Header Card ── --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="flex flex-col lg:flex-row gap-0">

            {{-- Cover image --}}
            <div class="lg:w-56 flex-shrink-0">
                <img src="{{ $group->CoverImagePath }}"
                    alt="{{ $group->name }}"
                    class="w-full h-48 lg:h-full object-cover">
            </div>

            {{-- Details --}}
            <div class="flex-1 p-6">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">

                    {{-- Name + badges --}}
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 leading-tight">{{ $group->name }}</h1>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                <i class="fas fa-tag text-xs"></i> {{ $group->groupCategory->name }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                <i class="fas fa-layer-group text-xs"></i> {{ ucwords(str_replace('_',' ',$group->group_type)) }}
                            </span>
                        </div>
                        @if($group->description)
                        <p class="text-sm text-gray-500 mt-3 max-w-xl">{{ $group->description }}</p>
                        @endif
                    </div>

                    {{-- Action buttons --}}
                    <div class="flex flex-wrap gap-2 flex-shrink-0">
                        <a href="{{ url('/admin/group/addMember/'.$group->id) }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition">
                            <i class="fas fa-user-plus"></i> Add Member
                        </a>
                        @if($memberCount)
                        <button id="btn-send-msg"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-lg transition">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                        @endif
                        <a href="{{ url('/admin/group/edit/'.$group->id) }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-medium rounded-lg transition">
                            <i class="fas fa-pen"></i> Edit
                        </a>

                        <a href="{{ url('/mygroup/'.$group->id.'?user_id=5') }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-medium rounded-lg transition" target="_blank">
                            Post View
                        </a>
                        <button id="btn-delete-group"
                            data-url="{{ url('/admin/group/delete/'.$group->id) }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 border border-red-200 hover:bg-red-100 text-red-600 text-xs font-medium rounded-lg transition">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="flex gap-6 mt-5 pt-4 border-t border-gray-100">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-indigo-600">{{ $memberCount }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Members</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-blue-600">{{ $messages->total() }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Messages Sent</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Send message panel (hidden by default) --}}
    <div id="send-msg-panel" class="hidden bg-white border border-indigo-200 rounded-xl shadow-sm p-5 mb-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-gray-700">Send Message to Group</h3>
            <button id="btn-close-msg" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <send-message url="{{ url('/') }}" name="{{ $group->id }}" tab="1" type="group"></send-message>
    </div>

    {{-- ── Tabs ── --}}
    <div>

        {{-- Tab nav --}}
        <div class="flex gap-1 border-b border-gray-200 mb-6">
            <button data-tab="info"
                class="tab-btn px-4 py-2.5 text-sm text-gray-500 hover:text-gray-700 transition -mb-px">
                <i class="fas fa-info-circle mr-1.5"></i>Info
            </button>
            <button data-tab="members"
                class="tab-btn px-4 py-2.5 text-sm text-gray-500 hover:text-gray-700 transition -mb-px">
                <i class="fas fa-users mr-1.5"></i>Members
                <span class="ml-1 px-1.5 py-0.5 rounded-full text-xs bg-gray-100 text-gray-600">{{ $memberCount }}</span>
            </button>
            <button data-tab="messages"
                class="tab-btn px-4 py-2.5 text-sm text-gray-500 hover:text-gray-700 transition -mb-px">
                <i class="fas fa-envelope mr-1.5"></i>Sent Messages
                <span class="ml-1 px-1.5 py-0.5 rounded-full text-xs bg-gray-100 text-gray-600">{{ $messages->total() }}</span>
            </button>
        </div>

        {{-- ── Info Tab ── --}}
        <div id="tab-info" class="tab-panel hidden">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 max-w-lg">
                <dl class="divide-y divide-gray-100">
                    <div class="py-3 flex">
                        <dt class="w-40 flex-shrink-0 text-xs font-semibold text-gray-500 uppercase tracking-wide pt-0.5">Category</dt>
                        <dd class="text-sm text-gray-700">{{ $group->groupCategory->name }}</dd>
                    </div>
                    <div class="py-3 flex">
                        <dt class="w-40 flex-shrink-0 text-xs font-semibold text-gray-500 uppercase tracking-wide pt-0.5">Group Type</dt>
                        <dd class="text-sm text-gray-700">{{ ucwords(str_replace('_',' ',$group->group_type)) }}</dd>
                    </div>
                    <div class="py-3 flex">
                        <dt class="w-40 flex-shrink-0 text-xs font-semibold text-gray-500 uppercase tracking-wide pt-0.5">Members</dt>
                        <dd class="text-sm text-gray-700">{{ $memberCount }}</dd>
                    </div>
                    <div class="py-3 flex">
                        <dt class="w-40 flex-shrink-0 text-xs font-semibold text-gray-500 uppercase tracking-wide pt-0.5">Description</dt>
                        <dd class="text-sm text-gray-700">{{ $group->description ?: '—' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- ── Members Tab ── --}}
        <div id="tab-members" class="tab-panel hidden">
            @if($grouplinks->isEmpty())
            <div class="bg-white border border-gray-200 rounded-xl p-12 text-center text-gray-400 text-sm">
                No members yet.
                <a href="{{ url('/admin/group/addMember/'.$group->id) }}" class="text-indigo-600 hover:underline ml-1">Add the first one.</a>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($grouplinks as $gl)
                @php
                $user = $gl->user;
                $profile = $user->userprofile;
                $name = $user->FullName;
                $avatar = $profile->AvatarPath;
                $roleColors = [
                'group_admin' => 'bg-indigo-100 text-indigo-700',
                'member' => 'bg-green-100 text-green-700',
                'guest' => 'bg-gray-100 text-gray-600',
                ];
                $roleLabels = [
                'group_admin' => 'Group Admin',
                'member' => 'Member',
                'guest' => 'Guest',
                ];
                $roleColor = $roleColors[$gl->role] ?? 'bg-gray-100 text-gray-600';
                $roleLabel = $roleLabels[$gl->role] ?? ucfirst($gl->role);
                @endphp
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 flex items-start gap-3">
                    <img src="{{ $avatar }}" alt="{{ $name }}"
                        class="w-11 h-11 rounded-full object-cover flex-shrink-0">
                    <div class="flex-1 min-w-0">
                        <a href="{{ url('/admin/member/show/'.$user->name) }}"
                            class="text-sm font-semibold text-gray-800 hover:text-indigo-600 truncate block">{{ $name }}</a>
                        <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                        <span class="inline-block mt-1.5 px-2 py-0.5 rounded-full text-xs font-medium {{ $roleColor }}">
                            {{ $roleLabel }}
                        </span>
                    </div>
                    <div class="flex gap-1 flex-shrink-0">
                        <a href="{{ url('/admin/group/editMember/'.$gl->id) }}"
                            title="Edit role"
                            class="w-7 h-7 flex items-center justify-center rounded-lg bg-gray-50 border border-gray-200 hover:bg-gray-100 text-gray-500 transition">
                            <i class="fas fa-pen text-xs"></i>
                        </a>
                        <button class="delete-member w-7 h-7 flex items-center justify-center rounded-lg bg-red-50 border border-red-100 hover:bg-red-100 text-red-500 transition"
                            data-url="{{ url('/admin/group/removeMember/'.$gl->id) }}"
                            title="Remove from group">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4">{{ $grouplinks->links() }}</div>
            @endif
        </div>

        {{-- ── Messages Tab ── --}}
        <div id="tab-messages" class="tab-panel hidden">
            @if($messages->isEmpty())
            <div class="bg-white border border-gray-200 rounded-xl p-12 text-center text-gray-400 text-sm">
                No messages sent to this group yet.
            </div>
            @else
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                            <th class="px-5 py-3 text-left">To</th>
                            <th class="px-5 py-3 text-left">Mode</th>
                            <th class="px-5 py-3 text-left">Sent On</th>
                            <th class="px-5 py-3 text-left">Status</th>
                            <th class="px-5 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($messages as $msg)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3 font-medium text-gray-800">
                                <a href="{{ url('/admin/member/show/'.$msg->user->name) }}" class="hover:text-indigo-600">
                                    {{ $msg->user->FullName }}
                                </a>
                            </td>
                            <td class="px-5 py-3">
                                @php
                                $modeColors = ['mail'=>'bg-blue-100 text-blue-700','sms'=>'bg-green-100 text-green-700','notification'=>'bg-purple-100 text-purple-700'];
                                $mc = $modeColors[$msg->mode] ?? 'bg-gray-100 text-gray-600';
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $mc }}">{{ ucwords($msg->mode) }}</span>
                            </td>
                            <td class="px-5 py-3 text-gray-600 text-xs">
                                {{ \Carbon\Carbon::parse($msg->executed_at)->format('d M Y, h:i A') }}
                            </td>
                            <td class="px-5 py-3">
                                @php $sc = $msg->status === 'sent' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'; @endphp
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $sc }}">{{ ucwords($msg->status) }}</span>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ url('/admin/message/show/'.$msg->id) }}" target="_blank"
                                    class="px-3 py-1 text-xs font-medium text-indigo-600 bg-indigo-50 border border-indigo-100 rounded hover:bg-indigo-100 transition">
                                    View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-5 py-3 border-t border-gray-100">{{ $messages->links() }}</div>
            </div>
            @endif
        </div>

    </div>{{-- /tabs --}}

</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $(function() {

        // ── Tab switching ──
        function activateTab(name) {
            $('.tab-panel').addClass('hidden');
            $('#tab-' + name).removeClass('hidden');
            $('.tab-btn')
                .removeClass('border-b-2 border-indigo-600 text-indigo-600 font-semibold')
                .addClass('text-gray-500');
            $('.tab-btn[data-tab="' + name + '"]')
                .removeClass('text-gray-500')
                .addClass('border-b-2 border-indigo-600 text-indigo-600 font-semibold');
            location.hash = name;
        }

        $('.tab-btn').on('click', function() {
            activateTab($(this).data('tab'));
        });

        // Restore tab from URL hash or default to members
        var hash = location.hash.replace('#', '');
        activateTab(['info', 'members', 'messages'].includes(hash) ? hash : 'members');

        // Send message panel toggle
        $('#btn-send-msg').on('click', function() {
            $('#send-msg-panel').removeClass('hidden');
            $('html,body').animate({
                scrollTop: $('#send-msg-panel').offset().top - 20
            }, 300);
        });
        $('#btn-close-msg').on('click', function() {
            $('#send-msg-panel').addClass('hidden');
        });

        // Delete group
        $('#btn-delete-group').on('click', function() {
            var url = $(this).data('url');
            swal({
                icon: 'warning',
                title: 'Delete Group?',
                text: 'This will permanently remove the group and all its members.',
                buttons: {
                    cancel: true,
                    confirm: {
                        text: 'Delete',
                        className: 'swal-button--danger'
                    }
                },
                dangerMode: true,
            }).then(function(confirm) {
                if (!confirm) return;
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        swal({
                            icon: 'success',
                            text: 'Group deleted.'
                        }).then(function() {
                            window.location.href = '/admin/groups';
                        });
                    }
                });
            });
        });

        // Remove member
        $(document).on('click', '.delete-member', function() {
            var url = $(this).data('url');
            swal({
                icon: 'warning',
                text: 'Remove this member from the group?',
                buttons: {
                    cancel: true,
                    confirm: true
                },
            }).then(function(confirm) {
                if (!confirm) return;
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        swal({
                            icon: 'success',
                            text: 'Member removed.'
                        }).then(function() {
                            window.location.reload();
                        });
                    }
                });
            });
        });

    });
</script>
@endpush