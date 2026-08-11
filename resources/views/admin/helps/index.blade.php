@extends('layouts.admin.layout')

@section('content')
<div class="w-full">
    <div class="flex items-center justify-between mb-4">
        <h1 class="admin-h1">Help Requests</h1>
        <a href="{{ url('/admin/help/create') }}" class="custom-green text-white px-4 py-1 rounded text-sm flex items-center gap-1">
            <img src="{{ url('uploads/icons/plus.svg') }}" class="w-3 h-3 inline"> Add
        </a>
    </div>

    @include('partials.message')

    {{-- Status Tabs --}}
    <ul class="list-reset flex text-xs profile-tab flex-wrap mb-4">
        @foreach(['pending' => 'Pending', 'approve' => 'Approved', 'reject' => 'Rejected', 'close' => 'Closed'] as $tab => $label)
        <li class="px-2 mx-1 py-1 {{ $status === $tab ? 'active' : '' }}">
            <a href="{{ url('/admin/helps') }}?tab={{ $tab }}" class="text-gray-700 font-medium">
                {{ $label }}
                @if(($counts[$tab] ?? 0) > 0)
                    <span class="bg-gray-200 text-gray-700 rounded-full px-1.5 ml-1">{{ $counts[$tab] }}</span>
                @endif
            </a>
        </li>
        @endforeach
    </ul>

    <div class="bg-white shadow p-4">
        {{-- Search --}}
        <form method="GET" action="{{ url('/admin/helps') }}" class="flex gap-2 mb-4">
            <input type="hidden" name="tab" value="{{ $status }}">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search title, description or name..."
                class="tw-form-control flex-1">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-1 rounded text-sm">Search</button>
            <a href="{{ url('/admin/helps') }}?tab={{ $status }}" class="bg-gray-100 text-gray-700 px-4 py-1 rounded text-sm flex items-center">Reset</a>
        </form>

        @if($helps->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm table-fixed">
                <colgroup>
                    <col style="width:16%">
                    <col style="width:18%">
                    <col style="width:24%">
                    <col style="width:18%">
                    <col style="width:12%">
                    <col style="width:12%">
                </colgroup>
                <thead class="border-t-2 border-b-2 bg-gray-50">
                    <tr>
                        <th class="text-left py-2.5 px-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Submitted By</th>
                        <th class="text-left py-2.5 px-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Title</th>
                        <th class="text-left py-2.5 px-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Description</th>
                        <th class="text-left py-2.5 px-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Contact</th>
                        <th class="text-left py-2.5 px-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Submitted</th>
                        <th class="text-left py-2.5 px-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($helps as $help)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-3 align-top">
                            <span class="block font-medium text-gray-800 truncate">{{ $help->user->FullName ?? $help->user->name ?? '—' }}</span>
                        </td>
                        <td class="py-3 px-3 align-top">
                            <span class="block text-gray-700 truncate">{{ $help->title ?: '—' }}</span>
                        </td>
                        <td class="py-3 px-3 align-top">
                            <span class="block text-gray-500 leading-snug" style="overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ $help->description ?: '—' }}</span>
                        </td>
                        <td class="py-3 px-3 align-top">
                            <span class="block text-gray-600 truncate">{{ $help->contact_details ?: '—' }}</span>
                        </td>
                        <td class="py-3 px-3 align-top whitespace-nowrap text-gray-500 text-xs">
                            {{ $help->created_at->diffForHumans() }}
                        </td>
                        <td class="py-3 px-3 align-top whitespace-nowrap">
                            <div class="flex items-center gap-1.5">
                                <a href="{{ url('/admin/help/show/' . $help->id) }}"
                                    class="text-white blue-bg rounded px-2.5 py-1 text-xs">View</a>
                                @if($help->status === 'pending')
                                <a href="{{ url('/admin/help/edit/' . $help->id) }}"
                                    class="text-white custom-green rounded px-2.5 py-1 text-xs">Review</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $helps->appends(request()->query())->links() }}</div>
        @else
        <p class="text-center text-gray-500 py-8 text-sm">No {{ $status }} help requests found.</p>
        @endif
    </div>
</div>
@endsection
