@extends('layouts.admin.layout')
@section('content')
<div class="bg-white my-3 p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="admin-h1">Donation Details</h1>
        <a href="{{ url('/admin/donations') }}" class="btn btn-secondary text-sm px-4 py-1 border rounded">
            &larr; Back
        </a>
    </div>

    <div class="max-w-2xl">
        <table class="w-full text-sm">
            <tbody>
                <tr class="border-b">
                    <td class="py-3 font-semibold text-gray-500 w-1/3">Donor</td>
                    <td class="py-3">
                        @if($donation->user)
                            {{ optional($donation->user->userprofile)->firstname }}
                            {{ optional($donation->user->userprofile)->lastname }}
                            <span class="text-gray-400 text-xs ml-1">({{ $donation->user->email }})</span>
                        @else
                            —
                        @endif
                    </td>
                </tr>
                <tr class="border-b">
                    <td class="py-3 font-semibold text-gray-500">Amount</td>
                    <td class="py-3 text-lg font-bold text-indigo-600">
                        {{ $donation->currency }} {{ number_format($donation->amount, 2) }}
                    </td>
                </tr>
                <tr class="border-b">
                    <td class="py-3 font-semibold text-gray-500">Category</td>
                    <td class="py-3 capitalize">{{ $donation->category ?? '—' }}</td>
                </tr>
                <tr class="border-b">
                    <td class="py-3 font-semibold text-gray-500">Method</td>
                    <td class="py-3 capitalize">{{ $donation->method }}</td>
                </tr>
                <tr class="border-b">
                    <td class="py-3 font-semibold text-gray-500">Status</td>
                    <td class="py-3">
                        @php
                            $colors = ['pending' => 'bg-yellow-100 text-yellow-700', 'completed' => 'bg-green-100 text-green-700', 'cancelled' => 'bg-red-100 text-red-700'];
                        @endphp
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $colors[$donation->status] ?? '' }}">
                            {{ ucfirst($donation->status) }}
                        </span>
                    </td>
                </tr>
                <tr class="border-b">
                    <td class="py-3 font-semibold text-gray-500">Note</td>
                    <td class="py-3">{{ $donation->note ?? '—' }}</td>
                </tr>
                <tr class="border-b">
                    <td class="py-3 font-semibold text-gray-500">Date</td>
                    <td class="py-3">
                        {{ ($donation->donated_at ?? $donation->created_at)->format('d M Y, h:i A') }}
                    </td>
                </tr>
            </tbody>
        </table>

        @if($donation->status === 'pending')
        <div class="mt-6 flex gap-3">
            <form method="POST" action="{{ url('/admin/donation/status/'.$donation->id) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="completed">
                <button type="submit" class="btn btn-primary blue-bg text-white rounded px-4 py-1.5 text-sm">
                    Mark Completed
                </button>
            </form>
            <form method="POST" action="{{ url('/admin/donation/status/'.$donation->id) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="cancelled">
                <button type="submit" class="btn btn-danger bg-red-600 text-white rounded px-4 py-1.5 text-sm">
                    Cancel
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
