@extends('layouts.admin.layout')

@section('content')
<div class="flex flex-row justify-between">
    <div class="w-1/2">
        <h1 class="admin-h1">Cities</h1>
    </div>
    <div class="relative flex items-center w-1/2 justify-end">
        <a href="{{ url('/admin/city/create') }}"
            class="no-underline text-white px-4 mx-1 flex items-center custom-green py-1 justify-center rounded">
            <span class="mx-1 text-sm font-semibold">Add City</span>
            <img src="{{ url('uploads/icons/plus.svg') }}" class="w-3 h-3">
        </a>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ url('/admin/cities') }}" class="my-3 flex flex-wrap items-center gap-3">
    <select name="country_id" id="filter_country"
        class="border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:ring focus:ring-indigo-200">
        <option value="">All Countries</option>
        @foreach($countries as $c)
        <option value="{{ $c->id }}" {{ request('country_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
        @endforeach
    </select>
    <select name="state_id" id="filter_state"
        class="border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:ring focus:ring-indigo-200">
        <option value="">All States</option>
        @foreach($states as $s)
        <option value="{{ $s->id }}" {{ request('state_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
        @endforeach
    </select>
    <button type="submit"
        class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-1.5 rounded">Filter</button>
    @if(request('country_id') || request('state_id'))
    <a href="{{ url('/admin/cities') }}" class="text-sm text-gray-500 hover:underline">Clear</a>
    @endif
</form>

<form method="POST" action="{{ url('/admin/cities/bulk') }}" id="bulkForm">
    @csrf
    {{-- Pass active filters through to controller for "select all pages" --}}
    <input type="hidden" name="filter_country_id" value="{{ request('country_id') }}">
    <input type="hidden" name="filter_state_id"   value="{{ request('state_id') }}">
    <input type="hidden" name="select_all" id="selectAllInput" value="0">

    {{-- Mass action bar --}}
    <div class="flex items-center gap-3 mb-2">
        <select name="action"
            class="border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:ring focus:ring-indigo-200">
            <option value="">— Bulk Action —</option>
            <option value="activate">Set Active</option>
            <option value="deactivate">Set Inactive</option>
            <option value="delete">Delete</option>
        </select>
        <button type="button" onclick="applyBulk()"
            class="bg-gray-700 hover:bg-gray-800 text-white text-sm px-4 py-1.5 rounded">Apply</button>
        <span id="selectedCount" class="text-xs text-gray-500"></span>
    </div>

    <div class="bg-white my-1 shadow">
        @include('partials.message')

        {{-- "Select all pages" banner — shown only when this page is fully checked and more pages exist --}}
        @if($cities->hasPages())
        <div id="selectAllBanner" class="hidden bg-indigo-50 border-b border-indigo-100 px-4 py-2 text-sm text-indigo-700 flex items-center gap-3">
            <span id="bannerText">All <strong>{{ $cities->count() }}</strong> cities on this page are selected.</span>
            <button type="button" id="selectAllPagesBtn"
                class="underline font-medium hover:text-indigo-900">
                Select all {{ $cities->total() }} cities matching current filters
            </button>
            <button type="button" id="clearSelectAllBtn"
                class="ml-4 text-gray-500 hover:text-gray-700 underline">Clear selection</button>
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
                    <th class="px-4 py-3">State</th>
                    <th class="px-4 py-3">Country</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($cities as $city)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <input type="checkbox" name="ids[]" value="{{ $city->id }}" class="row-check cursor-pointer">
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $cities->firstItem() + $loop->index }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $city->name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $city->state->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $city->country->name ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $city->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                            {{ $city->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center space-x-2">
                            <a href="{{ url('/admin/city/edit/' . $city->id) }}"
                                class="inline-flex items-center gap-1 px-3 py-1 rounded text-xs font-medium bg-indigo-50 text-indigo-700 hover:bg-indigo-100">
                                <img src="{{ url('uploads/icons/edit.svg') }}" class="w-3 h-3"> Edit
                            </a>
                            <button type="button"
                                onclick="deleteCity('{{ url('/admin/city/delete/' . $city->id) }}')"
                                class="inline-flex items-center gap-1 px-3 py-1 rounded text-xs font-medium bg-red-50 text-red-600 hover:bg-red-100">
                                <img src="{{ url('uploads/icons/delete.svg') }}" class="w-3 h-3"> Delete
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-6 text-center text-gray-400">No cities found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3">{{ $cities->links() }}</div>
    </div>
</form>

{{-- Shared single-row delete form (outside bulk form to avoid nested form issues) --}}
<form id="singleDeleteForm" method="POST" action="" style="display:none">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const pageCount        = {{ $cities->count() }};
    const totalCount       = {{ $cities->total() }};
    const hasPages         = {{ $cities->hasPages() ? 'true' : 'false' }};
    let   allPagesSelected = false;

    const selectAllCb       = document.getElementById('selectAll');
    const selectAllInput    = document.getElementById('selectAllInput');
    const selectedCountEl   = document.getElementById('selectedCount');
    const banner            = document.getElementById('selectAllBanner');
    const bannerText        = document.getElementById('bannerText');
    const selectAllPagesBtn = document.getElementById('selectAllPagesBtn');
    const clearSelectAllBtn = document.getElementById('clearSelectAllBtn');

    // Guard: nothing to do if the header checkbox isn't on the page
    if (!selectAllCb) return;

    // Header checkbox: select/deselect this page
    selectAllCb.addEventListener('change', function () {
        allPagesSelected = false;
        if (selectAllInput) selectAllInput.value = '0';
        document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
        updateCount();
        if (hasPages && this.checked) {
            showBanner(false);
        } else {
            hideBanner();
        }
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
            if (hasPages && checked.length === all.length) {
                showBanner(false);
            } else {
                hideBanner();
            }
        });
    });

    function showBanner(allPages) {
        if (!banner) return;
        allPagesSelected = allPages;
        if (allPages) {
            if (bannerText) bannerText.innerHTML = 'All <strong>' + totalCount + '</strong> cities matching current filters are selected.';
            if (selectAllPagesBtn) selectAllPagesBtn.classList.add('hidden');
        } else {
            if (bannerText) bannerText.innerHTML = 'All <strong>' + pageCount + '</strong> cities on this page are selected.';
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

    function updateCount() {
        if (!selectedCountEl) return;
        const n = allPagesSelected
            ? totalCount
            : document.querySelectorAll('.row-check:checked').length;
        selectedCountEl.textContent = n ? n + ' selected' : '';
    }

    // Bulk apply
    window.applyBulk = function () {
        const actionEl = document.querySelector('[name="action"]');
        if (!actionEl || !actionEl.value) { alert('Please select a bulk action.'); return; }
        const n = allPagesSelected
            ? totalCount
            : document.querySelectorAll('.row-check:checked').length;
        if (!n) { alert('Please select at least one row.'); return; }
        if (actionEl.value === 'delete' && !confirm('Delete ' + n + ' cities?')) return;
        document.getElementById('bulkForm').submit();
    };

    // Single row delete
    window.deleteCity = function (url) {
        if (!confirm('Delete this city?')) return;
        const f = document.getElementById('singleDeleteForm');
        f.action = url;
        f.submit();
    };

    // Dynamic state filter
    const filterCountry = document.getElementById('filter_country');
    if (filterCountry) {
        filterCountry.addEventListener('change', function () {
            const countryId  = this.value;
            const stateSelect = document.getElementById('filter_state');
            if (!stateSelect) return;
            stateSelect.innerHTML = '<option value="">All States</option>';
            if (!countryId) return;
            fetch('{{ url("/admin/ajax/states") }}?country_id=' + countryId)
                .then(r => r.json())
                .then(function (data) {
                    data.forEach(function (s) {
                        const opt = document.createElement('option');
                        opt.value = s.id;
                        opt.textContent = s.name;
                        stateSelect.appendChild(opt);
                    });
                });
        });
    }
});
</script>
@endpush
