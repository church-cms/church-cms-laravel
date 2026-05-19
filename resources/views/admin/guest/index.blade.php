@extends('layouts.admin.layout')

@section('content')
@php
$hasFilters = $firstname || $lastname || $gender || $minAge || $maxAge || $dob || $profession || $mobile || $email || $location;
$exportUrl = url('/admin/exportGuests') . '?' . http_build_query(array_merge(request()->all(), ['usergroup_id' => 5]));
$isAdmin = auth()->user()->usergroup_id == 3;
@endphp

{{-- ── Header ─────────────────────────────────────────────────────────── --}}
<div class="flex items-center justify-between my-3">
    <h1 class="admin-h1">Guests <span class="text-gray-400 text-base font-normal">({{ $count }})</span></h1>
    <div class="flex items-center gap-2">

        {{-- @if($isAdmin || Auth::user()->hasPermission('create-members'))
        <a href="{{ url('/admin/guest/add') }}"
        class="text-sm rounded px-3 py-1.5 flex items-center gap-2 btn btn-primary submit-btn">
        <i class="fas fa-user-plus text-xs"></i>
        Add Guest
        </a>
        @endif --}}
        <a href="{{ $exportUrl }}"
            class="text-sm rounded px-3 py-1.5 flex items-center gap-2 border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
            <i class="fas fa-download text-xs"></i>
            Export
        </a>
    </div>
</div>

@include('partials.message')

<div class="bg-white shadow px-4 py-5">

    {{-- ── Alphabet pills ──────────────────────────────────────────────── --}}
    <div class="flex flex-wrap gap-1 mb-4">
        <a href="{{ url('/admin/guests') }}"
            class="px-2.5 py-1 text-xs rounded border font-medium transition
                  {{ !$alphabet ? 'border-blue-600 text-blue-600 bg-blue-50' : 'border-gray-200 text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
            All
        </a>
        @foreach(range('A', 'Z') as $letter)
        <a href="{{ url('/admin/guests') . '?alphabet=' . $letter }}"
            class="px-2.5 py-1 text-xs rounded border font-medium transition
                  {{ $alphabet === $letter ? 'border-blue-600 text-blue-600 bg-blue-50' : 'border-gray-200 text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
            {{ $letter }}
        </a>
        @endforeach
    </div>

    {{-- ── Filter toggle + form ────────────────────────────────────────── --}}
    <div class="mb-5">
        <button type="button" id="guest-filter-toggle"
            class="text-sm flex items-center gap-1.5 px-3 py-1.5 rounded border transition
                       {{ $hasFilters ? 'border-blue-400 text-blue-600 bg-blue-50' : 'border-gray-200 text-gray-600 hover:bg-gray-50' }}">
            <i class="fas fa-filter text-xs"></i>
            Filters
            @if($hasFilters)
            <span class="ml-1 text-xs bg-blue-600 text-white rounded-full px-1.5 py-0.5 leading-none">on</span>
            @endif
        </button>

        <div id="guest-filter-panel" class="{{ $hasFilters ? '' : 'hidden' }} mt-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <form method="GET" action="{{ url('/admin/guests') }}">
                <input type="hidden" name="alphabet" value="{{ $alphabet }}">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">First Name</label>
                        <input type="text" name="firstname" value="{{ $firstname }}"
                            class="tw-form-control text-sm py-1.5">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Last Name</label>
                        <input type="text" name="lastname" value="{{ $lastname }}"
                            class="tw-form-control text-sm py-1.5">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Gender</label>
                        <select name="gender" class="tw-form-control text-sm py-1.5">
                            <option value="">Any</option>
                            <option value="M" {{ $gender === 'M' ? 'selected' : '' }}>Male</option>
                            <option value="F" {{ $gender === 'F' ? 'selected' : '' }}>Female</option>
                            <option value="O" {{ $gender === 'O' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Occupation</label>
                        <select name="profession" class="tw-form-control text-sm py-1.5">
                            <option value="">Any</option>
                            @foreach($occupations as $occ)
                            <option value="{{ $occ }}" {{ $profession === $occ ? 'selected' : '' }}>{{ $occ }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Birth Month</label>
                        <select name="date_of_birth" class="tw-form-control text-sm py-1.5">
                            <option value="">Any</option>
                            @foreach(['01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December'] as $mv => $ml)
                            <option value="{{ $mv }}" {{ $dob === $mv ? 'selected' : '' }}>{{ $ml }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Min Age</label>
                        <input type="number" name="min_age" value="{{ $minAge }}" min="0" max="120"
                            class="tw-form-control text-sm py-1.5">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Max Age</label>
                        <input type="number" name="max_age" value="{{ $maxAge }}" min="0" max="120"
                            class="tw-form-control text-sm py-1.5">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Mobile</label>
                        <input type="text" name="mobile_no" value="{{ $mobile }}"
                            class="tw-form-control text-sm py-1.5">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Email</label>
                        <input type="text" name="email" value="{{ $email }}"
                            class="tw-form-control text-sm py-1.5">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Location</label>
                        <input type="text" name="location" value="{{ $location }}"
                            class="tw-form-control text-sm py-1.5">
                    </div>
                </div>
                <div class="mt-3 flex gap-2">
                    <button type="submit"
                        class="text-sm px-4 py-1.5 rounded btn btn-primary submit-btn">
                        Search
                    </button>
                    <a href="{{ url('/admin/guests') }}"
                        class="text-sm px-4 py-1.5 rounded border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Guest table ──────────────────────────────────────────────────── --}}
    @if($guests->isEmpty())
    <div class="text-center py-16 text-gray-400">
        <i class="fas fa-users text-4xl mb-3 block"></i>
        <p class="text-sm">No guests found.</p>
        @if($isAdmin || Auth::user()->hasPermission('create-members'))
        <a href="{{ url('/admin/guest/add') }}" class="text-sm text-blue-500 hover:underline mt-1 block">
            Add your first guest
        </a>
        @endif
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wide">

                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Mobile</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Profession</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($guests as $guest)
                @php
                $profile = $guest->userprofile;
                $fullName = $profile ? trim($profile->firstname . ' ' . $profile->lastname) : $guest->name;
                $initials = $profile
                ? strtoupper(substr($profile->firstname ?? '', 0, 1) . substr($profile->lastname ?? '', 0, 1))
                : strtoupper(substr($guest->name, 0, 2));
                $status = $profile->status ?? 'active';
                @endphp
                <tr class="hover:bg-gray-50 transition">

                    <td class="px-4 py-3 font-medium text-gray-800">
                        <div class="flex items-center gap-2">
                            @if($profile && $profile->AvatarPath)
                            <img src="{{ $profile->AvatarPath }}" alt="{{ $fullName }}"
                                class="w-9 h-9 rounded-full object-cover">
                            @else
                            <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-semibold">
                                {{ $initials ?: '?' }}
                            </div>
                            @endif
                            <a href="{{ url('/admin/guest/show/' . $guest->name) }}"
                                class="hover:text-blue-600 transition">{{ $fullName }}</a>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $guest->mobile_no ?: '—' }}</td>
                    <td class="px-4 py-3 text-gray-500 truncate max-w-xs">{{ $guest->email ?: '—' }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $profile->profession ?? '—' }}</td>
                    <td class="px-4 py-3">
                        @if($status === 'active')
                        <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700 font-medium">Active</span>
                        @elseif($status === 'inactive')
                        <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 font-medium">Inactive</span>
                        @else
                        <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700 font-medium">{{ ucfirst($status) }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ url('/admin/guest/show/' . $guest->name) }}"
                                class="text-xs px-2.5 py-1.5 rounded border border-gray-200 text-gray-600 hover:bg-gray-100 transition">
                                View
                            </a>
                            @if($isAdmin || Auth::user()->hasPermission('create-members'))
                            <a href="{{ url('/admin/guest/edit/' . $guest->name) }}"
                                class="text-xs px-2.5 py-1.5 rounded border border-gray-200 text-gray-600 hover:bg-gray-100 transition">
                                Edit
                            </a>
                            <form action="{{ url('/admin/guest/delete/' . $guest->name) }}" method="POST"
                                onsubmit="return confirm('Delete this guest?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="text-xs px-2.5 py-1.5 rounded border border-red-200 text-red-500 hover:bg-red-50 transition">
                                    Delete
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- ── Pagination ───────────────────────────────────────────────────── --}}
    @if($guests->hasPages())
    <div class="mt-5">{{ $guests->links() }}</div>
    @endif

</div>

@endsection

@push('scripts')
<script>
    (function() {
        var toggle = document.getElementById('guest-filter-toggle');
        var panel = document.getElementById('guest-filter-panel');
        if (toggle && panel) {
            toggle.addEventListener('click', function() {
                panel.classList.toggle('hidden');
            });
        }
    })();
</script>
@endpush