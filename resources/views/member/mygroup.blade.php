@extends('layouts.app')

@section('title', 'My Groups')

@section('content')

<div class="mb-6 mx-auto max-w-[640px]">

    {{-- Back link --}}
    <a href="{{ url('/member/home') }}"
        class="inline-flex items-center text-sm text-gray-500 hover:text-indigo-600 no-underline mb-4">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back to Profile
    </a>

    {{-- Page heading --}}
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-gray-800">My Groups</h2>
        <span class="text-xs bg-indigo-100 text-indigo-700 font-semibold px-3 py-1 rounded-full">{{count($group_link)}} Groups</span>
    </div>

    {{-- ─────────────────────────────────────────────
         GROUP CARD 1  —  Admin (send-message enabled)
    ───────────────────────────────────────────── --}}

    @if(count($group_link)>0)

    @foreach($group_link as $grouplist)

    @php

    $total_group_count=App\Models\GroupLink::where('group_id',$grouplist->group_id)->count();

    @endphp

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4 overflow-hidden">

        {{-- Card header --}}
        <div class="flex items-center justify-between px-6 pt-5 pb-3">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
                    @if($grouplist->group->cover_image)
                    <img src="{{url('storage/'.$grouplist->group->cover_image)}}" alt="">
                    @endif
                </div>
                <div>
                    <p class="font-semibold text-gray-800 text-sm">{{$grouplist->group->name}}</p>

                    <span class="text-xs text-indigo-500 font-medium bg-indigo-50 px-2 py-0.5 rounded-full">{{$grouplist->group->groupCategory->name}}</span>
                    <!-- <span class="text-xs text-indigo-500 font-medium bg-indigo-50 px-2 py-0.5 rounded-full">{{$grouplist->group->group_type}}</span> -->
                </div>
            </div>
            {{-- Admin badge --}}
            <span class="text-xs font-semibold bg-green-100 text-green-700 px-2.5 py-0.5 rounded-full">

                @if($grouplist->role=='group_admin')
                Group Admin
                @else
                {{ucfirst($grouplist->role)}}
                @endif
            </span>
        </div>

        {{-- Member count --}}
        <div class="px-6 pb-3 flex items-center space-x-1 text-xs text-gray-400">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197" />
            </svg>
            <span>{{$total_group_count}} members</span>
        </div>

        {{-- Admin action buttons --}}

        <div class="px-6 pb-4 flex items-center space-x-2">

            {{-- Remove Group button --}}
            <button type="button"
                data-group-id="{{ $grouplist->group_id }}"
                data-group-name="{{ $grouplist->group->name }}"
                onclick="confirmRemoveGroup(this)"
                class="inline-flex items-center text-xs font-semibold text-white bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg transition">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a1 1 0 00-1-1h-4a1 1 0 00-1 1H5" />
                </svg>
                Remove Group
            </button>

            {{-- Hidden remove form (POST + spoofed DELETE) --}}
            <form id="remove-form-{{ $grouplist->group_id }}"
                action="{{ route('member.group.remove', $grouplist->group_id) }}"
                method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>

            <!-- @if($grouplist->role == 'group_admin')
            {{-- Send Message button — opens modal --}}
            <button type="button"
                data-group-id="{{ $grouplist->group_id }}"
                data-group-name="{{ $grouplist->group->name }}"
                data-send-url="{{ route('member.group.sendmessage', $grouplist->group_id) }}"
                onclick="openSendModal(this)"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-lg transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Send Message
            </button>
            @endif -->

            <a href="{{url('/member/mygroup/'.$grouplist->group_id)}}">
                <button type="button"

                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-white bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg transition">

                    View
                </button></a>

        </div>




    </div>
    @endforeach
    @else
    <div> No records found </div>
    @endif

</div>

{{-- ═══════════════════════════════════════════════
     SEND MESSAGE MODAL  (shared, one per page)
════════════════════════════════════════════════ --}}
<div id="send-msg-modal"
    class="fixed inset-0 z-50 hidden flex items-center justify-center p-4"
    role="dialog" aria-modal="true">

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black bg-opacity-40" onclick="closeSendModal()"></div>

    {{-- Panel --}}
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md mx-auto p-6 z-10">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-base font-semibold text-gray-800">Send Message</h3>
            <button type="button" onclick="closeSendModal()"
                class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Group name badge --}}
        <p id="modal-group-name" class="text-xs text-indigo-600 font-medium bg-indigo-50 px-3 py-1.5 rounded-lg mb-4 truncate"></p>

        {{-- Form --}}
        <form id="send-msg-form" enctype="multipart/form-data">
            @csrf

            {{-- Mode: Email / Notification / SMS --}}
            <div class="flex items-center gap-5 mb-4">
                <label class="flex items-center gap-1.5 text-sm text-gray-700 cursor-pointer">
                    <input type="radio" name="mode" value="mail" checked
                        onchange="onModeChange(this.value)"
                        class="accent-indigo-600"> Email
                </label>
                <label class="flex items-center gap-1.5 text-sm text-gray-700 cursor-pointer">
                    <input type="radio" name="mode" value="notification"
                        onchange="onModeChange(this.value)"
                        class="accent-indigo-600"> Notification
                </label>
                <label class="flex items-center gap-1.5 text-sm text-gray-700 cursor-pointer">
                    <input type="radio" name="mode" value="sms"
                        onchange="onModeChange(this.value)"
                        class="accent-indigo-600"> SMS
                </label>
            </div>

            {{-- Subject (Email only) --}}
            <div id="field-subject" class="mb-3">
                <label class="block text-xs font-medium text-gray-600 mb-1">
                    Subject <span class="text-red-500">*</span>
                </label>
                <input type="text" name="subject" maxlength="30"
                    placeholder="Enter Subject"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
            </div>

            {{-- Message --}}
            <div class="mb-3">
                <label class="block text-xs font-medium text-gray-600 mb-1">
                    Message <span class="text-red-500">*</span>
                </label>
                <textarea name="message" rows="4" id="field-message" maxlength="1000"
                    placeholder="Enter Message"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none"></textarea>
            </div>

            {{-- Attachments (Email only) --}}
            <div id="field-attachments" class="mb-3">
                <label class="block text-xs font-medium text-gray-600 mb-1">Attachments</label>
                <input type="file" name="attachments"
                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.csv"
                    class="w-full text-sm text-gray-500 border border-gray-300 rounded-lg px-3 py-2 file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>

            {{-- Send Later --}}
            <div class="mb-4">
                <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                    <input type="checkbox" name="send_later" value="true" id="chk-send-later"
                        onchange="toggleSendLater(this.checked)"
                        class="accent-indigo-600 w-4 h-4">
                    Send Later
                </label>
                <div id="field-execute-at" class="hidden mt-2">
                    <input type="datetime-local" name="executed_at"
                        class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>
            </div>

            {{-- Error area --}}
            <div id="send-msg-error" class="hidden mb-3 text-xs text-red-600 bg-red-50 border border-red-200 rounded-lg px-3 py-2"></div>

            {{-- Actions --}}
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeSendModal()"
                    class="text-sm text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg border border-gray-200 bg-white transition">
                    Cancel
                </button>
                <button type="button" onclick="submitSendMessage()"
                    id="btn-send-submit"
                    class="inline-flex items-center gap-1.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 px-5 py-2 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    Send
                </button>
            </div>
        </form>

    </div>
</div>

{{-- Scripts --}}
<script>
    // ── Remove Group ──────────────────────────────────
    function confirmRemoveGroup(btn) {
        const id = btn.dataset.groupId;
        const name = btn.dataset.groupName;
        if (confirm('Are you sure you want to remove the group "' + name + '"?\nThis action cannot be undone.')) {
            document.getElementById('remove-form-' + id).submit();
        }
    }

    // ── Send Message Modal ────────────────────────────
    let _sendUrl = '';

    function openSendModal(btn) {
        _sendUrl = btn.dataset.sendUrl;
        document.getElementById('modal-group-name').textContent = btn.dataset.groupName;
        // reset form
        document.getElementById('send-msg-form').reset();
        onModeChange('mail');
        document.getElementById('field-execute-at').classList.add('hidden');
        document.getElementById('send-msg-error').classList.add('hidden');
        // show modal
        document.getElementById('send-msg-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeSendModal() {
        document.getElementById('send-msg-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function onModeChange(mode) {
        const showEmail = mode === 'mail';
        document.getElementById('field-subject').style.display = showEmail ? '' : 'none';
        document.getElementById('field-attachments').style.display = showEmail ? '' : 'none';
        // update maxlength on message
        document.getElementById('field-message').maxLength = mode === 'sms' ? 300 : 1000;
    }

    function toggleSendLater(checked) {
        document.getElementById('field-execute-at').classList.toggle('hidden', !checked);
    }

    function submitSendMessage() {
        const form = document.getElementById('send-msg-form');
        const btn = document.getElementById('btn-send-submit');
        const errBox = document.getElementById('send-msg-error');
        errBox.classList.add('hidden');

        const fd = new FormData(form);
        // normalize send_later
        if (!form.querySelector('#chk-send-later').checked) {
            fd.set('send_later', 'false');
        }

        btn.disabled = true;
        btn.textContent = 'Sending…';

        fetch(_sendUrl, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: fd
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    closeSendModal();
                    // show flash message without full reload
                    showFlash(data.success, 'success');
                } else if (data.errors) {
                    const msgs = Object.values(data.errors).flat().join(' ');
                    errBox.textContent = msgs;
                    errBox.classList.remove('hidden');
                } else {
                    errBox.textContent = 'Something went wrong. Please try again.';
                    errBox.classList.remove('hidden');
                }
            })
            .catch(() => {
                errBox.textContent = 'Network error. Please try again.';
                errBox.classList.remove('hidden');
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg> Send';
            });
    }

    // inline flash (no page reload needed)
    function showFlash(msg, type) {
        const div = document.createElement('div');
        div.className = type === 'success' ?
            'mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm flex items-center gap-2' :
            'mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm';
        div.innerHTML = (type === 'success' ?
            '<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>' :
            '') + '<span>' + msg + '</span>';
        const main = document.querySelector('main');
        main.insertBefore(div, main.firstChild);
        setTimeout(() => div.remove(), 5000);
    }
</script>

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