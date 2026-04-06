@extends('layouts.member')

@section('title', 'My Profile')

@section('content')

@php
    $user    = auth()->user();
    $profile = optional($user->userprofile);
@endphp

{{-- ── Page Header ────────────────────────────────────────────────── --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">My Profile</h1>
    <p class="text-sm text-gray-500 mt-1">Your personal information and membership details</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- ── Col 1: Avatar + Church ───────────────────────────────────── --}}
    <div class="lg:col-span-1 space-y-5">

        {{-- Avatar Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col items-center text-center">
            @if($profile->avatar)
                <img src="{{ $profile->AvatarPath }}"
                    class="w-28 h-28 rounded-full object-cover ring-4 ring-indigo-100">
            @else
                <div class="w-28 h-28 rounded-full bg-indigo-100 ring-4 ring-indigo-200 flex items-center justify-center">
                    <span class="text-5xl font-bold text-indigo-600">
                        {{ strtoupper(substr($profile->firstname ?? $user->name, 0, 1)) }}
                    </span>
                </div>
            @endif

            <h2 class="mt-4 text-lg font-semibold text-gray-800">
                {{ $profile->firstname }} {{ $profile->lastname }}
            </h2>
            <p class="text-sm text-gray-500">{{ $user->email }}</p>

            <span class="mt-3 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                {{ $profile->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                <span class="w-1.5 h-1.5 rounded-full mr-1.5
                    {{ $profile->status === 'active' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                {{ ucfirst($profile->status ?? 'active') }}
            </span>

            @if($profile->membership_type)
            <span class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                {{ ucfirst($profile->membership_type) }}
            </span>
            @endif
        </div>

        {{-- Church Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Church</h3>
            <div class="flex items-start space-x-3">
                <div class="mt-0.5 w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-800 capitalize">{{ optional($user->church)->name }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{!! optional($user->church)->fulladdress() !!}</p>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Col 2: Personal / Address / Membership ───────────────────── --}}
    <div class="lg:col-span-1 space-y-5">

        {{-- Personal Details --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-5">Personal Details</h3>
            <dl class="space-y-0 divide-y divide-gray-50">
                @php
                    $fields = [
                        ['label' => 'First Name',      'value' => $profile->firstname],
                        ['label' => 'Last Name',       'value' => $profile->lastname],
                        ['label' => 'Gender',          'value' => ucfirst($profile->gender ?? '')],
                        ['label' => 'Date of Birth',   'value' => $profile->date_of_birth ? date('d M Y', strtotime($profile->date_of_birth)) : null],
                        ['label' => 'Mobile',          'value' => $user->mobile_no],
                        ['label' => 'Email',           'value' => $user->email],
                        ['label' => 'Profession',      'value' => ucwords(str_replace('_', ' ', $profile->profession ?? ''))],
                        ['label' => 'Marriage Status', 'value' => ucwords(str_replace('_', ' ', $profile->marriage_status ?? ''))],
                    ];
                @endphp
                @foreach($fields as $field)
                <div class="flex items-center justify-between py-3">
                    <dt class="text-xs font-medium text-gray-400 w-1/2">{{ $field['label'] }}</dt>
                    <dd class="text-sm font-semibold text-gray-800 text-right w-1/2 truncate">{{ $field['value'] ?: '—' }}</dd>
                </div>
                @endforeach
            </dl>
        </div>

        {{-- Address --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-5">Address</h3>
            <dl class="space-y-0 divide-y divide-gray-50">
                @php
                    $address = [
                        ['label' => 'Street',  'value' => $profile->address],
                        ['label' => 'City',    'value' => optional($profile->city)->name],
                        ['label' => 'State',   'value' => optional($profile->state)->name],
                        ['label' => 'Country', 'value' => optional($profile->country)->name],
                        ['label' => 'Pincode', 'value' => $profile->pincode],
                    ];
                @endphp
                @foreach($address as $row)
                <div class="flex items-center justify-between py-3">
                    <dt class="text-xs font-medium text-gray-400 w-1/2">{{ $row['label'] }}</dt>
                    <dd class="text-sm font-semibold text-gray-800 text-right w-1/2 truncate">{{ $row['value'] ?: '—' }}</dd>
                </div>
                @endforeach
            </dl>
        </div>

        {{-- Membership --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-5">Membership</h3>
            <dl class="space-y-0 divide-y divide-gray-50">
                @php
                    $membership = [
                        ['label' => 'Type',         'value' => ucfirst($profile->membership_type ?? '')],
                        ['label' => 'Member Since',  'value' => $profile->membership_start_date ? date('d M Y', strtotime($profile->membership_start_date)) : null],
                        ['label' => 'Was Baptized',  'value' => ucfirst($profile->was_baptized ?? '')],
                    ];
                    if ($profile->was_baptized === 'yes') {
                        $membership[] = ['label' => 'Baptism Date', 'value' => $profile->baptism_date ? date('d M Y', strtotime($profile->baptism_date)) : null];
                    }
                @endphp
                @foreach($membership as $row)
                <div class="flex items-center justify-between py-3">
                    <dt class="text-xs font-medium text-gray-400 w-1/2">{{ $row['label'] }}</dt>
                    <dd class="text-sm font-semibold text-gray-800 text-right w-1/2">{{ $row['value'] ?: '—' }}</dd>
                </div>
                @endforeach
            </dl>
        </div>

    </div>

    {{-- ── Col 3: Family Tree ────────────────────────────────────────── --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 h-full">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-5">Family Tree</h3>
            <member-family-tree name="{{ $user->name }}"></member-family-tree>
        </div>
    </div>

</div>

@endsection

