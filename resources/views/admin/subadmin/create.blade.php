@extends('layouts.admin.layout')
@section('content')

<div class="w-full max-w-4xl">

    <h1 class="admin-h1 mb-6 flex items-center gap-3">
        <a href="{{ url('/admin/subadmins') }}" class="rounded-full bg-gray-100 hover:bg-gray-200 p-2 transition">
            <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
        </a>
        Add Sub Admin
    </h1>

    @include('partials.message')

    <form method="POST" action="{{ url('/admin/subadmin/add') }}" enctype="multipart/form-data">
        @csrf

        {{-- ── Personal Information ─────────────────────────────────────── --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-700">Personal Information</h2>
            </div>
            <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="tw-form-label">First Name <span class="text-red-500">*</span></label>
                    <input type="text" name="firstname" value="{{ old('firstname') }}"
                           class="tw-form-control w-full @error('firstname') border-red-400 @enderror"
                           placeholder="First name">
                    @error('firstname')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="tw-form-label">Last Name</label>
                    <input type="text" name="lastname" value="{{ old('lastname') }}"
                           class="tw-form-control w-full @error('lastname') border-red-400 @enderror"
                           placeholder="Last name">
                    @error('lastname')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="tw-form-label">Birth First Name</label>
                    <input type="text" name="birth_firstname" value="{{ old('birth_firstname') }}"
                           class="tw-form-control w-full @error('birth_firstname') border-red-400 @enderror"
                           placeholder="Birth first name">
                    @error('birth_firstname')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="tw-form-label">Birth Last Name</label>
                    <input type="text" name="birth_lastname" value="{{ old('birth_lastname') }}"
                           class="tw-form-control w-full @error('birth_lastname') border-red-400 @enderror"
                           placeholder="Birth last name">
                    @error('birth_lastname')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="tw-form-label">Date of Birth <span class="text-red-500">*</span></label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                           class="tw-form-control w-full @error('date_of_birth') border-red-400 @enderror"
                           max="{{ date('Y-m-d') }}">
                    @error('date_of_birth')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="tw-form-label">Gender <span class="text-red-500">*</span></label>
                    <select name="gender" class="tw-form-control w-full @error('gender') border-red-400 @enderror">
                        <option value="">Select gender</option>
                        <option value="male"   {{ old('gender') === 'male'   ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other"  {{ old('gender') === 'other'  ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="tw-form-label">Aadhaar Number</label>
                    <input type="text" name="aadhar_number" value="{{ old('aadhar_number') }}"
                           class="tw-form-control w-full @error('aadhar_number') border-red-400 @enderror"
                           placeholder="12-digit Aadhaar number" maxlength="12">
                    @error('aadhar_number')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="tw-form-label">Profile Photo <span class="text-red-500">*</span></label>
                    <input type="file" name="avatar" accept="image/jpg,image/jpeg,image/png,image/webp"
                           class="tw-form-control w-full @error('avatar') border-red-400 @enderror">
                    @error('avatar')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

            </div>
        </div>

        {{-- ── Contact Information ───────────────────────────────────────── --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-700">Contact Information</h2>
            </div>
            <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="tw-form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="tw-form-control w-full @error('email') border-red-400 @enderror"
                           placeholder="email@example.com">
                    @error('email')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="tw-form-label">Mobile Number <span class="text-red-500">*</span></label>
                    <input type="text" name="mobile_no" value="{{ old('mobile_no') }}"
                           class="tw-form-control w-full @error('mobile_no') border-red-400 @enderror"
                           placeholder="10-digit mobile number" maxlength="10">
                    @error('mobile_no')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="tw-form-label">Address</label>
                    <input type="text" name="address" value="{{ old('address') }}"
                           class="tw-form-control w-full @error('address') border-red-400 @enderror"
                           placeholder="Street address">
                    @error('address')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="tw-form-label">Country <span class="text-red-500">*</span></label>
                    <select name="country_id" id="country_id"
                            class="tw-form-control w-full @error('country_id') border-red-400 @enderror">
                        <option value="">Select country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country['id'] }}" {{ old('country_id') == $country['id'] ? 'selected' : '' }}>
                                {{ $country['name'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('country_id')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="tw-form-label">State <span class="text-red-500">*</span></label>
                    <select name="state_id" id="state_id"
                            class="tw-form-control w-full @error('state_id') border-red-400 @enderror">
                        <option value="">Select state</option>
                    </select>
                    @error('state_id')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="tw-form-label">City <span class="text-red-500">*</span></label>
                    <select name="city_id" id="city_id"
                            class="tw-form-control w-full @error('city_id') border-red-400 @enderror">
                        <option value="">Select city</option>
                    </select>
                    @error('city_id')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="tw-form-label">Pincode <span class="text-red-500">*</span></label>
                    <input type="text" name="pincode" value="{{ old('pincode') }}"
                           class="tw-form-control w-full @error('pincode') border-red-400 @enderror"
                           placeholder="6-digit pincode" maxlength="6">
                    @error('pincode')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

            </div>
        </div>

        {{-- ── Occupation ────────────────────────────────────────────────── --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-700">Occupation</h2>
            </div>
            <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="tw-form-label">Profession <span class="text-red-500">*</span></label>
                    <select name="profession" id="profession"
                            class="tw-form-control w-full @error('profession') border-red-400 @enderror">
                        <option value="">Select profession</option>
                        @foreach($occupations as $occupation)
                            <option value="{{ $occupation['id'] }}" {{ old('profession') === $occupation['id'] ? 'selected' : '' }}>
                                {{ $occupation['name'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('profession')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

                <div id="sub_occupation_wrap" style="display:none">
                    <label class="tw-form-label">Sub Category <span class="text-red-500">*</span></label>
                    <input type="text" name="sub_occupation" value="{{ old('sub_occupation') }}"
                           class="tw-form-control w-full @error('sub_occupation') border-red-400 @enderror"
                           placeholder="e.g. Cardiologist">
                    @error('sub_occupation')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

            </div>
        </div>

        {{-- ── Notes ────────────────────────────────────────────────────── --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-700">Notes</h2>
            </div>
            <div class="px-6 py-5">
                <textarea name="notes" rows="3"
                          class="tw-form-control w-full @error('notes') border-red-400 @enderror"
                          placeholder="Optional notes about this sub admin">{{ old('notes') }}</textarea>
                @error('notes')<p class="tw-form-error">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- ── Actions ──────────────────────────────────────────────────── --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                    class="px-6 py-2 bg-indigo-700 hover:bg-indigo-800 text-white text-sm font-medium rounded-lg transition">
                Create Sub Admin
            </button>
            <a href="{{ url('/admin/subadmins') }}"
               class="px-6 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition">
                Cancel
            </a>
        </div>

    </form>
</div>

@endsection

@push('scripts')
<script>
    const AJAX_BASE = '{{ url('/admin/ajax') }}';
    const OLD_STATE = '{{ old('state_id') }}';
    const OLD_CITY  = '{{ old('city_id') }}';

    function populateSelect(select, items, selectedValue) {
        select.innerHTML = '<option value="">Select ' + select.id.replace('_id','') + '</option>';
        items.forEach(function(item) {
            var opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = item.name;
            if (String(item.id) === String(selectedValue)) opt.selected = true;
            select.appendChild(opt);
        });
    }

    document.getElementById('country_id').addEventListener('change', function () {
        var countryId = this.value;
        var stateSelect = document.getElementById('state_id');
        var citySelect  = document.getElementById('city_id');

        citySelect.innerHTML  = '<option value="">Select city</option>';
        stateSelect.innerHTML = '<option value="">Loading...</option>';

        if (!countryId) {
            stateSelect.innerHTML = '<option value="">Select state</option>';
            return;
        }

        fetch(AJAX_BASE + '/states?country_id=' + countryId)
            .then(function(r) { return r.json(); })
            .then(function(data) { populateSelect(stateSelect, data, ''); });
    });

    document.getElementById('state_id').addEventListener('change', function () {
        var stateId = this.value;
        var citySelect = document.getElementById('city_id');

        citySelect.innerHTML = '<option value="">Loading...</option>';

        if (!stateId) {
            citySelect.innerHTML = '<option value="">Select city</option>';
            return;
        }

        fetch(AJAX_BASE + '/cities?state_id=' + stateId)
            .then(function(r) { return r.json(); })
            .then(function(data) { populateSelect(citySelect, data, ''); });
    });

    // Restore old state/city after validation failure
    (function () {
        if (OLD_STATE) {
            var countrySelect = document.getElementById('country_id');
            var countryId = countrySelect.value;
            if (countryId) {
                fetch(AJAX_BASE + '/states?country_id=' + countryId)
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        populateSelect(document.getElementById('state_id'), data, OLD_STATE);
                        if (OLD_CITY) {
                            fetch(AJAX_BASE + '/cities?state_id=' + OLD_STATE)
                                .then(function(r) { return r.json(); })
                                .then(function(cities) {
                                    populateSelect(document.getElementById('city_id'), cities, OLD_CITY);
                                });
                        }
                    });
            }
        }
    })();

    // Show/hide sub_occupation based on profession
    var noSubOccupation = ['home_maker', 'self_employed', 'student', ''];
    document.getElementById('profession').addEventListener('change', function () {
        var wrap = document.getElementById('sub_occupation_wrap');
        wrap.style.display = noSubOccupation.includes(this.value) ? 'none' : 'block';
    });

    (function () {
        var prof = document.getElementById('profession').value;
        if (prof && !noSubOccupation.includes(prof)) {
            document.getElementById('sub_occupation_wrap').style.display = 'block';
        }
    })();
</script>
@endpush
