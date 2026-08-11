@extends('layouts.admin.layout')

@section('content')
<div class="flex flex-row justify-between">
    <div class="w-1/2">
        <h1 class="admin-h1">States</h1>
    </div>
    <div class="relative flex items-center w-1/2 justify-end">
        <a href="{{ url('/admin/state/create') }}"
            class="no-underline text-white px-4 mx-1 flex items-center custom-green py-1 justify-center rounded">
            <span class="mx-1 text-sm font-semibold">Add State</span>
            <img src="{{ url('uploads/icons/plus.svg') }}" class="w-3 h-3">
        </a>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ url('/admin/states') }}" class="my-3 flex flex-wrap items-center gap-3">
    <select name="country_id"
        class="border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:ring focus:ring-indigo-200">
        <option value="">All Countries</option>
        @foreach($countries as $c)
        <option value="{{ $c->id }}" {{ request('country_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
        @endforeach
    </select>
    <select name="status"
        class="border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:ring focus:ring-indigo-200">
        <option value="">All Statuses</option>
        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
    </select>
    <button type="submit"
        class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-1.5 rounded">Filter</button>
    @if(request('country_id') || request('status') !== null && request('status') !== '')
    <a href="{{ url('/admin/states') }}" class="text-sm text-gray-500 hover:underline">Clear</a>
    @endif
</form>

<form method="POST" action="{{ url('/admin/states/bulk') }}" id="bulkForm">
    @csrf
    <input type="hidden" name="filter_country_id" value="{{ request('country_id') }}">
    <input type="hidden" name="filter_status"      value="{{ request('status') }}">
    <input type="hidden" name="select_all" id="selectAllInput" value="0">

    {{-- Bulk action bar --}}
    <div class="flex items-center gap-3 mb-2">
        <select name="action"
            class="border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:ring focus:ring-indigo-200">
            <option value="">— Bulk Action —</option>
            <option value="activate">Set Active</option>
            <option value="deactivate">Set Inactive</option>
        </select>
        <button type="button" onclick="applyBulk()"
            class="bg-gray-700 hover:bg-gray-800 text-white text-sm px-4 py-1.5 rounded">Apply</button>
        <span id="selectedCount" class="text-xs text-gray-500"></span>
    </div>

    <div class="bg-white my-1 shadow">
        @include('partials.message')

        @if($states->hasPages())
        <div id="selectAllBanner" class="hidden bg-indigo-50 border-b border-indigo-100 px-4 py-2 text-sm text-indigo-700 flex items-center gap-3">
            <span id="bannerText">All <strong>{{ $states->count() }}</strong> states on this page are selected.</span>
            <button type="button" id="selectAllPagesBtn" class="underline font-medium hover:text-indigo-900">
                Select all {{ $states->total() }} states matching current filters
            </button>
            <button type="button" id="clearSelectAllBtn" class="ml-4 text-gray-500 hover:text-gray-700 underline">Clear selection</button>
        </div>
        @endif

        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-100 text-left text-xs uppercase tracking-wide text-gray-500">
                    <th class="px-4 py-3 w-px">
                        <input type="checkbox" id="selectAll" class="cursor-pointer">
                    </th>
                    <th class="px-4 py-3 w-px whitespace-nowrap">#</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Country</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($states as $state)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <input type="checkbox" name="ids[]" value="{{ $state->id }}" class="row-check cursor-pointer">
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $states->firstItem() + $loop->index }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $state->name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ optional($state->country)->name ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $state->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                            {{ $state->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center space-x-2">
                            <a href="{{ url('/admin/state/edit/' . $state->id) }}"
                                class="inline-flex items-center gap-1 px-3 py-1 rounded text-xs font-medium bg-indigo-50 text-indigo-700 hover:bg-indigo-100">
                                <img src="{{ url('uploads/icons/edit.svg') }}" class="w-3 h-3"> Edit
                            </a>
                            <button type="button"
                                onclick="deleteState('{{ url('/admin/state/delete/' . $state->id) }}')"
                                class="inline-flex items-center gap-1 px-3 py-1 rounded text-xs font-medium bg-red-50 text-red-600 hover:bg-red-100">
                                <img src="{{ url('uploads/icons/delete.svg') }}" class="w-3 h-3"> Delete
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-400">No states found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3">{{ $states->links() }}</div>
    </div>
</form>

{{-- Shared single-row delete form --}}
<form id="singleDeleteForm" method="POST" action="" style="display:none">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const pageCount        = {{ $states->count() }};
    const totalCount       = {{ $states->total() }};
    const hasPages         = {{ $states->hasPages() ? 'true' : 'false' }};
    let   allPagesSelected = false;

    const selectAllCb       = document.getElementById('selectAll');
    const selectAllInput    = document.getElementById('selectAllInput');
    const selectedCountEl   = document.getElementById('selectedCount');
    const banner            = document.getElementById('selectAllBanner');
    const bannerText        = document.getElementById('bannerText');
    const selectAllPagesBtn = document.getElementById('selectAllPagesBtn');
    const clearSelectAllBtn = document.getElementById('clearSelectAllBtn');

    if (!selectAllCb) return;

    selectAllCb.addEventListener('change', function () {
        allPagesSelected = false;
        if (selectAllInput) selectAllInput.value = '0';
        document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
        updateCount();
        if (hasPages && this.checked) showBanner(false); else hideBanner();
    });

    document.querySelectorAll('.row-check').forEach(function (cb) {
        cb.addEventListener('change', function () {
            allPagesSelected = false;
            if (selectAllInput) selectAllInput.value = '0';
            const all     = document.querySelectorAll('.row-check');
            const checked = document.querySelectorAll('.row-check:checked');
            selectAllCb.indeterminate = checked.length > 0 && checked.length < all.length;
            selectAllCb.checked       = checked.length > 0 && checked.length === all.length;
            updateCount();
            if (hasPages && checked.length === all.length) showBanner(false); else hideBanner();
        });
    });

    if (selectAllPagesBtn) {
        selectAllPagesBtn.addEventListener('click', function () {
            if (selectAllInput) selectAllInput.value = '1';
            showBanner(true);
            updateCount();
        });
    }

    if (clearSelectAllBtn) {
        clearSelectAllBtn.addEventListener('click', function () {
            selectAllCb.checked       = false;
            selectAllCb.indeterminate = false;
            document.querySelectorAll('.row-check').forEach(cb => cb.checked = false);
            hideBanner();
            updateCount();
        });
    }

    function showBanner(allPages) {
        if (!banner) return;
        allPagesSelected = allPages;
        if (allPages) {
            if (bannerText) bannerText.innerHTML = 'All <strong>' + totalCount + '</strong> states matching current filters are selected.';
            if (selectAllPagesBtn) selectAllPagesBtn.classList.add('hidden');
        } else {
            if (bannerText) bannerText.innerHTML = 'All <strong>' + pageCount + '</strong> states on this page are selected.';
            if (selectAllPagesBtn) selectAllPagesBtn.classList.remove('hidden');
        }
        banner.classList.remove('hidden');
    }

    function hideBanner() {
        if (!banner) return;
        banner.classList.add('hidden');
        allPagesSelected = false;
        if (selectAllInput) selectAllInput.value = '0';
    }

    function updateCount() {
        if (!selectedCountEl) return;
        const n = allPagesSelected
            ? totalCount
            : document.querySelectorAll('.row-check:checked').length;
        selectedCountEl.textContent = n ? n + ' selected' : '';
    }

    window.applyBulk = function () {
        const actionEl = document.querySelector('[name="action"]');
        if (!actionEl || !actionEl.value) { alert('Please select a bulk action.'); return; }
        const n = allPagesSelected
            ? totalCount
            : document.querySelectorAll('.row-check:checked').length;
        if (!n) { alert('Please select at least one row.'); return; }
        document.getElementById('bulkForm').submit();
    };

    window.deleteState = function (url) {
        if (!confirm('Delete this state?')) return;
        const f = document.getElementById('singleDeleteForm');
        f.action = url;
        f.submit();
    };
});
</script>
@endpush
