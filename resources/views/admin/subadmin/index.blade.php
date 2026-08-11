@extends('layouts.admin.layout')

@php
    /*
     * Map permission sets to human-readable role labels.
     * Ordered from most specific to most generic so the first match wins.
     */
    $rolePresets = [
        'Full Access'         => '__all__',
        'Preacher'            => ['create-sermons','read-sermons','update-sermons','delete-sermons'],
        'Event Coordinator'   => ['create-events','read-events','update-events','create-gallery','read-gallery','update-gallery'],
        'Finance Officer'     => ['create-funds','read-funds','update-funds','view-funds','read-payments','create-payments','read-reports','view-reports'],
        'Content Manager'     => ['create-bulletins','read-bulletins','view-bulletins','create-quotes','read-quotes','update-quotes','create-gallery','read-gallery','update-gallery','create-files','read-files','view-files'],
        'Prayer Coordinator'    => ['read-prayers','update-prayers'],
        'Support Coordinator'   => ['read-helps','update-helps'],
        'Web Admin'             => ['read-contacts','read-feedbacks','update-feedbacks'],
        'Email Blaster Manager'    => ['manage-email-blaster'],
        'CMS Manager'              => ['manage-cms'],
        'Attendance Coordinator'   => ['read-attendance','create-attendance','update-attendance'],
    ];

    $allPermissionNames = App\Models\Permission::pluck('name')->sort()->values()->toArray();

    function detectRole(array $userPerms, array $presets, array $allPerms): string {
        if (empty($userPerms)) return '';
        sort($userPerms);
        $sortedAll = $allPerms;
        sort($sortedAll);
        if ($userPerms === $sortedAll) return 'Full Access';
        foreach ($presets as $label => $required) {
            if ($required === '__all__') continue;
            sort($required);
            if ($userPerms === $required) return $label;
        }
        return 'Custom';
    }
@endphp

@section('content')
<div class="w-full">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="admin-h1">Sub Admins <span class="text-gray-400 font-normal text-base">({{ $subadmins->count() }})</span></h1>
        <a href="{{ url('/admin/subadmin/add') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-700 hover:bg-indigo-800 text-white text-sm font-medium rounded-lg transition">
            <img src="{{ url('uploads/icons/plus.svg') }}" class="w-3 h-3 brightness-0 invert">
            Add Sub Admin
        </a>
    </div>

    @include('partials.message')

    {{-- Search --}}
    <form method="GET" action="{{ url('/admin/subadmins') }}" class="mb-5">
        <div class="flex items-center gap-3 max-w-md">
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Search by name, email or mobile…"
                   class="tw-form-control w-full">
            <button type="submit"
                    class="px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white text-sm font-medium rounded-lg transition whitespace-nowrap">
                Search
            </button>
            @if($search)
                <a href="{{ url('/admin/subadmins') }}"
                   class="px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-600 text-sm font-medium rounded-lg transition">
                    Clear
                </a>
            @endif
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
        <table class="w-full" style="table-layout:fixed">
            <colgroup>
                <col style="width:28%">  {{-- Sub Admin --}}
                <col style="width:22%">  {{-- Contact --}}
                <col style="width:13%">  {{-- Last Login --}}
                <col style="width:14%">  {{-- Role --}}
                <col style="width:12%">  {{-- Permissions --}}
                <col style="width:11%">  {{-- Actions --}}
            </colgroup>
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Sub Admin</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Contact</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Last Login</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Role</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Permissions</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($subadmins as $subadmin)
                    @php
                        $profile   = $subadmin->userprofile;
                        $fullname  = $profile ? trim($profile->firstname . ' ' . $profile->lastname) : $subadmin->name;
                        $avatar    = $profile?->AvatarPath;
                        $userPerms = $subadmin->permissions->pluck('name')->sort()->values()->toArray();
                        $roleLabel = detectRole($userPerms, $rolePresets, $allPermissionNames);
                    @endphp
                    <tr class="hover:bg-gray-50 transition">

                        {{-- Avatar + Name --}}
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                @if($avatar)
                                    <img src="{{ $avatar }}" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-indigo-600 text-xs font-bold">
                                            {{ strtoupper(substr($fullname, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $fullname }}</p>
                                    <p class="text-xs text-gray-400 truncate mt-0.5">{{ $subadmin->name }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Contact --}}
                        <td class="px-4 py-3.5">
                            @if($subadmin->email)
                                <p class="text-xs font-medium text-gray-600 truncate">{{ $subadmin->email }}</p>
                            @endif
                            @if($subadmin->mobile_no)
                                <p class="text-xs text-gray-400 mt-0.5">{{ $subadmin->mobile_no }}</p>
                            @endif
                            @if(!$subadmin->email && !$subadmin->mobile_no)
                                <span class="text-gray-300 text-sm">—</span>
                            @endif
                        </td>

                        {{-- Last Login --}}
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            @if($subadmin->last_login_at)
                                @php $loginAt = \Carbon\Carbon::parse($subadmin->last_login_at); @endphp
                                <p class="text-xs font-medium text-gray-600">{{ $loginAt->format('d M Y') }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $loginAt->format('g:i A') }}</p>
                            @else
                                <span class="text-xs text-gray-300">Never</span>
                            @endif
                        </td>

                        {{-- Role label --}}
                        <td class="px-4 py-3.5">
                            @if($roleLabel === 'Full Access')
                                <span class="inline-block px-2.5 py-1 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-700">
                                    Full Access
                                </span>
                            @elseif($roleLabel === 'Custom')
                                <span class="inline-block px-2.5 py-1 rounded-md text-xs font-semibold bg-amber-50 text-amber-700">
                                    Custom
                                </span>
                            @elseif($roleLabel)
                                <span class="inline-block px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700">
                                    {{ $roleLabel }}
                                </span>
                            @else
                                <span class="text-xs text-gray-300">—</span>
                            @endif
                        </td>

                        {{-- Permissions --}}
                        <td class="px-4 py-3.5">
                            @if(count($userPerms))
                                <button type="button"
                                        data-action="perms-popover"
                                        data-name="{{ e($fullname) }}"
                                        data-role="{{ e($roleLabel) }}"
                                        data-perms="{{ e(json_encode($userPerms)) }}"
                                        class="group inline-flex items-center gap-1 text-xs font-medium text-indigo-600 hover:text-indigo-800 transition">
                                    View
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-100 group-hover:bg-indigo-200 text-indigo-600 font-semibold text-xs transition">
                                        {{ count($userPerms) }}
                                    </span>
                                </button>
                            @else
                                <span class="text-xs text-gray-300">—</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-4 py-3.5 text-right">
                            <div class="inline-flex items-center gap-1.5">
                                <a href="{{ url('/admin/subadmin/edit/' . $subadmin->name) }}"
                                   class="px-2.5 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-200 rounded-md hover:bg-gray-50 hover:border-gray-300 transition">
                                    Edit
                                </a>
                                <a href="{{ url('/admin/subadmin/show/' . $subadmin->name) }}"
                                   class="px-2.5 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-md hover:bg-indigo-100 transition">
                                    Permissions
                                </a>
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-14 text-center">
                            <p class="text-sm text-gray-400">
                                @if($search)
                                    No sub admins found matching <strong class="text-gray-600">"{{ $search }}"</strong>.
                                @else
                                    No sub admins yet.
                                    <a href="{{ url('/admin/subadmin/add') }}" class="text-indigo-600 hover:underline ml-1">Add the first one.</a>
                                @endif
                            </p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection

@push('scripts')
<script>
(function () {
    var popover   = null;
    var activeBtn = null;

    /* ── Build popover DOM once ─────────────────────────────────────── */
    function getPopover() {
        if (popover) return popover;

        var el = document.createElement('div');
        el.style.cssText = 'display:none;position:fixed;z-index:99999;width:300px;' +
            'background:#fff;border:1px solid #e5e7eb;border-radius:12px;' +
            'box-shadow:0 8px 30px rgba(0,0,0,.18);overflow:hidden';

        var arrow = document.createElement('div');
        arrow.setAttribute('data-pp', 'arrow');
        arrow.style.cssText = 'position:absolute;width:12px;height:12px;background:#fff;' +
            'border-left:1px solid #e5e7eb;border-top:1px solid #e5e7eb;' +
            'left:20px;top:-7px;transform:rotate(45deg)';

        var header = document.createElement('div');
        header.style.cssText = 'padding:12px 14px 10px;border-bottom:1px solid #f3f4f6;' +
            'display:flex;align-items:flex-start;justify-content:space-between;gap:8px';

        var nameWrap = document.createElement('div');
        var nameEl   = document.createElement('p');
        nameEl.setAttribute('data-pp', 'name');
        nameEl.style.cssText = 'margin:0;font-size:13px;font-weight:600;color:#111827;line-height:1.3';
        var roleEl = document.createElement('div');
        roleEl.setAttribute('data-pp', 'role');
        roleEl.style.marginTop = '4px';
        nameWrap.appendChild(nameEl);
        nameWrap.appendChild(roleEl);

        var closeBtn = document.createElement('button');
        closeBtn.type = 'button';
        closeBtn.style.cssText = 'background:none;border:none;cursor:pointer;color:#9ca3af;' +
            'font-size:22px;line-height:1;padding:0;flex-shrink:0;margin-top:-1px';
        closeBtn.textContent = '×';

        header.appendChild(nameWrap);
        header.appendChild(closeBtn);

        var body = document.createElement('div');
        body.style.cssText = 'padding:12px 14px;max-height:230px;overflow-y:auto';
        var permsEl = document.createElement('div');
        permsEl.setAttribute('data-pp', 'perms');
        permsEl.style.cssText = 'display:flex;flex-wrap:wrap;gap:5px';
        body.appendChild(permsEl);

        el.appendChild(arrow);
        el.appendChild(header);
        el.appendChild(body);
        document.body.appendChild(el);

        closeBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            close();
        });

        popover = el;
        return el;
    }

    function find(attr) { return popover.querySelector('[data-pp="' + attr + '"]'); }

    /* ── Open ───────────────────────────────────────────────────────── */
    function open(btn) {
        var pp = getPopover();

        if (activeBtn === btn && pp.style.display !== 'none') { close(); return; }
        activeBtn = btn;

        find('name').textContent = btn.getAttribute('data-name');

        var role   = btn.getAttribute('data-role');
        var roleEl = find('role');
        if (role) {
            var bg = role === 'Full Access' ? '#e0e7ff' : role === 'Custom' ? '#fef3c7' : '#dcfce7';
            var fg = role === 'Full Access' ? '#4338ca' : role === 'Custom' ? '#92400e' : '#166534';
            roleEl.innerHTML = '';
            var badge = document.createElement('span');
            badge.style.cssText = 'display:inline-block;padding:1px 7px;border-radius:4px;' +
                'font-size:11px;font-weight:600;background:' + bg + ';color:' + fg;
            badge.textContent = role;
            roleEl.appendChild(badge);
        } else {
            roleEl.innerHTML = '';
        }

        var perms    = JSON.parse(btn.getAttribute('data-perms'));
        var permsEl  = find('perms');
        permsEl.innerHTML = '';
        perms.forEach(function (p) {
            var span = document.createElement('span');
            span.style.cssText = 'display:inline-block;padding:2px 7px;border-radius:4px;' +
                'background:#f3f4f6;color:#374151;font-size:11px;font-family:monospace';
            span.textContent = p;
            permsEl.appendChild(span);
        });

        pp.style.display = 'block';

        var r  = btn.getBoundingClientRect();
        var pw = 300;
        var ph = pp.offsetHeight;
        var vw = window.innerWidth;
        var vh = window.innerHeight;

        var top  = r.bottom + 8;
        var left = r.left;
        var flip = top + ph > vh - 8;
        if (flip)          top  = r.top - ph - 8;
        if (left + pw > vw - 8) left = vw - pw - 8;
        if (left < 8)      left = 8;

        pp.style.top  = top  + 'px';
        pp.style.left = left + 'px';

        var arrow = popover.querySelector('[data-pp="arrow"]');
        arrow.style.left = Math.min(Math.max(r.left + r.width / 2 - left - 6, 8), pw - 20) + 'px';
        if (flip) {
            arrow.style.top = 'auto'; arrow.style.bottom = '-7px';
            arrow.style.transform = 'rotate(225deg)';
        } else {
            arrow.style.bottom = 'auto'; arrow.style.top = '-7px';
            arrow.style.transform = 'rotate(45deg)';
        }
    }

    function close() {
        if (popover) popover.style.display = 'none';
        activeBtn = null;
    }

    /* ── Event delegation — no global functions needed ──────────────── */
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-action="perms-popover"]');
        if (btn) { open(btn); return; }
        if (popover && popover.style.display !== 'none' && !popover.contains(e.target)) {
            close();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') close();
    });
})();
</script>
@endpush
