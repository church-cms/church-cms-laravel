@extends('layouts.admin.layout')

@section('content')
<div class="flex flex-row justify-between">
    <div class="w-1/2">
        <h1 class="admin-h1">Countries</h1>
    </div>
    <div class="relative flex items-center w-1/2 justify-end">
        <a href="{{ url('/admin/country/create') }}"
            class="no-underline text-white px-4 mx-1 flex items-center custom-green py-1 justify-center rounded">
            <span class="mx-1 text-sm font-semibold">Add Country</span>
            <img src="{{ url('uploads/icons/plus.svg') }}" class="w-3 h-3">
        </a>
    </div>
</div>

<div class="bg-white my-3 shadow">
    @include('partials.message')
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-100 text-left text-xs uppercase tracking-wide text-gray-500">
                <th class="px-4 py-3 w-px whitespace-nowrap">#</th>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Short Name</th>
                <th class="px-4 py-3">ISO Code</th>
                <th class="px-4 py-3">Tel Prefix</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($countries as $country)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-500">{{ $loop->iteration }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $country->name }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $country->short_name ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $country->iso_code ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $country->tel_prefix ?? '—' }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $country->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                        {{ $country->status ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center space-x-2">
                        <a href="{{ url('/admin/country/edit/' . $country->id) }}"
                            class="inline-flex items-center gap-1 px-3 py-1 rounded text-xs font-medium bg-indigo-50 text-indigo-700 hover:bg-indigo-100">
                            <img src="{{ url('uploads/icons/edit.svg') }}" class="w-3 h-3"> Edit
                        </a>
                        <form method="POST" action="{{ url('/admin/country/delete/' . $country->id) }}"
                            onsubmit="return confirm('Delete this country?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center gap-1 px-3 py-1 rounded text-xs font-medium bg-red-50 text-red-600 hover:bg-red-100">
                                <img src="{{ url('uploads/icons/delete.svg') }}" class="w-3 h-3"> Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-6 text-center text-gray-400">No countries found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3">{{ $countries->links() }}</div>
</div>
@endsection
