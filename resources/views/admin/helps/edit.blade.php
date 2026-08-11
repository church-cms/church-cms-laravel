@extends('layouts.admin.layout')

@section('content')
<div class="w-full lg:w-2/3">
    <h1 class="admin-h1 flex items-center">
        <a href="{{ url('/admin/helps') }}" title="Back" class="rounded-full bg-gray-100 p-2">
            <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
        </a>
        <span class="mx-3">Review Help Request</span>
    </h1>

    @include('partials.message')

    <div class="bg-white shadow px-6 py-4 my-3">
        {{-- Request details --}}
        <table class="w-full text-sm mb-6">
            <tr class="border-b">
                <td class="py-2 pr-4 font-semibold text-gray-600 w-1/4">Submitted By</td>
                <td class="py-2">{{ $help->user->FullName ?? $help->user->name ?? '—' }}</td>
            </tr>
            <tr class="border-b">
                <td class="py-2 pr-4 font-semibold text-gray-600">Contact Details</td>
                <td class="py-2">{{ $help->contact_details ?? '—' }}</td>
            </tr>
            <tr class="border-b">
                <td class="py-2 pr-4 font-semibold text-gray-600">Title</td>
                <td class="py-2">{{ $help->title }}</td>
            </tr>
            <tr class="border-b">
                <td class="py-2 pr-4 font-semibold text-gray-600 align-top">Description</td>
                <td class="py-2 whitespace-pre-wrap">{{ $help->description }}</td>
            </tr>
            <tr class="border-b">
                <td class="py-2 pr-4 font-semibold text-gray-600">Submitted</td>
                <td class="py-2">{{ $help->created_at->diffForHumans() }} &mdash; {{ $help->created_at->format('d M Y, g:i a') }}</td>
            </tr>
            <tr>
                <td class="py-2 pr-4 font-semibold text-gray-600">Current Status</td>
                <td class="py-2 capitalize">{{ $help->status }}</td>
            </tr>
        </table>

        @if($help->status === 'pending')
        {{-- Review form --}}
        <form method="POST" action="{{ url('/admin/help/update/' . $help->id) }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Action <span class="text-red-500">*</span></label>
                <select name="status" id="status-select" class="tw-form-control w-1/2"
                    onchange="toggleFields(this.value)">
                    <option value="" disabled selected>Select action</option>
                    <option value="approve" {{ old('status') === 'approve' ? 'selected' : '' }}>Approve</option>
                    <option value="reject" {{ old('status') === 'reject' ? 'selected' : '' }}>Reject</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="field-expired-at" class="{{ old('status') === 'approve' ? '' : 'hidden' }} mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Expire Within <span class="text-red-500">*</span></label>
                <select name="expired_at" class="tw-form-control w-1/2">
                    <option value="" disabled selected>Select days</option>
                    <option value="1" {{ old('expired_at') == 1 ? 'selected' : '' }}>1 Day</option>
                    <option value="3" {{ old('expired_at') == 3 ? 'selected' : '' }}>3 Days</option>
                    <option value="5" {{ old('expired_at') == 5 ? 'selected' : '' }}>5 Days</option>
                    <option value="7" {{ old('expired_at') == 7 ? 'selected' : '' }}>7 Days</option>
                </select>
                @error('expired_at')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="field-comments" class="{{ old('status') === 'reject' ? '' : 'hidden' }} mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Reason for Rejection <span class="text-red-500">*</span></label>
                <input type="text" name="comments" value="{{ old('comments') }}"
                    class="tw-form-control w-full" placeholder="Enter reason...">
                @error('comments')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded font-semibold text-sm">
                Submit
            </button>
            <a href="{{ url('/admin/helps') }}" class="ml-3 text-sm text-gray-500 hover:underline">Cancel</a>
        </form>
        @else
        <p class="text-sm text-gray-500 italic">This request has already been {{ $help->status === 'approve' ? 'approved' : $help->status }}.</p>
        @endif
    </div>
</div>

<script>
function toggleFields(status) {
    document.getElementById('field-expired-at').classList.toggle('hidden', status !== 'approve');
    document.getElementById('field-comments').classList.toggle('hidden', status !== 'reject');
}
</script>
@endsection
