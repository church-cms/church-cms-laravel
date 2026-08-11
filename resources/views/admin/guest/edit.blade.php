@extends('layouts.admin.layout')

@section('content')
<div class="w-full">
    <h1 class="admin-h1 mb-5 flex items-center">
        @if ($user->ref_id == null)
        <a href="{{ url('/admin/guest/show/' . $user->name) }}" title="Back" class="rounded-full bg-gray-100 p-2">
            <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
        </a>
        @else
        <a href="{{ url('/admin/guest/show/' . $user->refer->name) }}" title="Back"
            class="rounded-full bg-gray-100 p-2">
            <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
        </a>
        @endif
        <span class="mx-3">Edit Guest</span>
    </h1>
    @include('partials.message')
    <div class="bg-white shadow px-4 py-2">
        <form method="POST" action="{{ url('/admin/guest/edit/' . $user->name) }}" enctype="multipart/form-data">
            @csrf

            {{-- First Name / Last Name --}}
            <div class="flex flex-col lg:flex-row">
                <div class="tw-form-group w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="firstname" class="tw-form-label">First Name<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 relative">
                            <span class="absolute m-2">
                                <img src="{{ url('/uploads/icons/form-user.svg') }}" class="w-4 h-4">
                            </span>
                            <input type="text" class="tw-form-control w-full member-icon" id="firstname" name="firstname"
                                value="{{ old('firstname', $user->userprofile->firstname) }}" placeholder="First Name">
                        </div>
                        @error('firstname')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="tw-form-group w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="lastname" class="tw-form-label">Last Name</label>
                        </div>
                        <div class="mb-2 relative">
                            <span class="absolute m-2">
                                <img src="{{ url('/uploads/icons/form-user.svg') }}" class="w-4 h-4">
                            </span>
                            <input type="text" class="tw-form-control w-full member-icon" id="lastname" name="lastname"
                                value="{{ old('lastname', $user->userprofile->lastname) }}" placeholder="Last Name">
                        </div>
                        @error('lastname')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Aadhaar / Date of Birth --}}
            <div class="flex flex-col lg:flex-row">
                <div class="tw-form-group w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="aadhar_number" class="tw-form-label">Aadhaar Number</label>
                        </div>
                        <div class="mb-2">
                            <input type="text" class="tw-form-control w-full" id="aadhar_number" name="aadhar_number"
                                value="{{ old('aadhar_number', $user->userprofile->aadhar_number) }}" placeholder="Aadhaar Number">
                        </div>
                        @error('aadhar_number')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="tw-form-group w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="date_of_birth" class="tw-form-label">Date Of Birth<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2">
                            <input type="date" class="tw-form-control w-full" id="date_of_birth" name="date_of_birth"
                                value="{{ old('date_of_birth', $user->userprofile->date_of_birth ? date('Y-m-d', strtotime($user->userprofile->date_of_birth)) : '') }}">
                        </div>
                        @error('date_of_birth')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Gender / Occupation / Sub-Category --}}
            <div class="flex flex-col lg:flex-row">
                <div class="tw-form-group w-full lg:w-1/3">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label class="tw-form-label">Gender<span class="text-red-500">*</span></label>
                        </div>
                        <div class="flex">
                            <div class="w-1/2 flex items-center tw-form-control mr-2 lg:mr-8 md:mr-8">
                                <input type="radio" name="gender" id="gender1" value="male"
                                    {{ old('gender', $user->userprofile->gender) == 'male' ? 'checked' : '' }}>
                                <span class="text-sm mx-1">Male</span>
                            </div>
                            <div class="w-1/2 flex items-center tw-form-control lg:mr-8">
                                <input type="radio" name="gender" id="gender2" value="female"
                                    {{ old('gender', $user->userprofile->gender) == 'female' ? 'checked' : '' }}>
                                <span class="text-sm mx-1">Female</span>
                            </div>
                        </div>
                        @error('gender')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="tw-form-group w-full lg:w-1/3">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="profession" class="tw-form-label">Occupation</label>
                        </div>
                        <div class="mb-2">
                            <select class="tw-form-control w-full" id="profession" name="profession"
                                onchange="toggleSubOccupation(this.value)">
                                <option value="" disabled>Occupation</option>
                                @foreach($occupationlist as $occ)
                                    <option value="{{ $occ['id'] }}"
                                        {{ old('profession', $user->userprofile->profession) == $occ['id'] ? 'selected' : '' }}>
                                        {{ $occ['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('profession')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
                    </div>
                </div>

                @php
                    $subOccProfessions = ['business','doctor','engineer','government_employee','lawyer','pastor','police','professionals','self_employed','teacher','others'];
                    $currentProfession = old('profession', $user->userprofile->profession);
                @endphp
                <div class="tw-form-group w-full lg:w-1/3" id="sub_occupation_wrap"
                    style="{{ in_array($currentProfession, $subOccProfessions) ? '' : 'display:none' }}">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="sub_occupation" class="tw-form-label">Sub-Category</label>
                        </div>
                        <div class="mb-2">
                            <input type="text" class="tw-form-control w-full" id="sub_occupation" name="sub_occupation"
                                value="{{ old('sub_occupation', $user->userprofile->sub_occupation) }}" placeholder="Sub Category">
                        </div>
                        @error('sub_occupation')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Address + Map --}}
            <div class="flex flex-col lg:flex-row md:flex-row">
                <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="address" class="tw-form-label">Address</label>
                        </div>
                        <div class="mb-2 w-full relative">
                            <input type="text" name="address" class="tw-form-control w-full" id="address"
                                value="{{ old('address', $user->userprofile->address) }}">
                            <span class="absolute m-2 top-0 right-0">
                                <a href="#" onclick="codeAddress(); return false;" dusk="getCords" id="getCords">
                                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30.239px"
                                        height="30.239px" viewBox="0 0 30.239 30.239" xml:space="preserve"
                                        class="w-4 h-4 fill-current text-gray-600">
                                        <g>
                                            <path d="M20.194,3.46c-4.613-4.613-12.121-4.613-16.734,0c-4.612,4.614-4.612,12.121,0,16.735 c4.108,4.107,10.506,4.547,15.116,1.34c0.097,0.459,0.319,0.897,0.676,1.254l6.718,6.718c0.979,0.977,2.561,0.977,3.535,0 c0.978-0.978,0.978-2.56,0-3.535l-6.718-6.72c-0.355-0.354-0.794-0.577-1.253-0.674C24.743,13.967,24.303,7.57,20.194,3.46z M18.073,18.074c-3.444,3.444-9.049,3.444-12.492,0c-3.442-3.444-3.442-9.048,0-12.492c3.443-3.443,9.048-3.443,12.492,0 C21.517,9.026,21.517,14.63,18.073,18.074z">
                                            </path>
                                        </g>
                                    </svg>
                                </a>
                            </span>
                            <span class="text-red-500 text-xs font-semibold">{{ $errors->first('address') }}</span>
                        </div>
                    </div>
                </div>

                <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div id="map_canvas" class="tw-form-control" style="height: 250px;"></div>
                    </div>
                </div>

                <div class="tw-form-group w-1/2" hidden>
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="latitude" class="col-md-4 control-label">Latitude</label>
                        </div>
                        <div class="mb-2 w-full relative">
                            <input id="latitude" type="text" class="tw-form-control w-1/2" name="latitude"
                                value="{{ old('latitude') }}">
                        </div>
                    </div>
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="longitude" class="col-md-4 control-label">Longitude</label>
                        </div>
                        <div class="mb-2 w-full relative">
                            <input id="longitude" type="text" class="tw-form-control w-1/2" name="longitude"
                                value="{{ old('longitude') }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Country / State / City / Pincode --}}
            <div class="tw-form-group">
                <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-1/4 lg:mr-8 md:pr-8">
                        <div class="mb-2">
                            <label for="country_id" class="tw-form-label">Country<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2">
                            <select class="tw-form-control w-full" id="country_id" name="country_id"
                                onchange="loadStates(this.value)">
                                <option value="" disabled>Select Country</option>
                                @foreach($countrylist as $country)
                                    <option value="{{ $country['id'] }}"
                                        {{ old('country_id', $user->userprofile->country_id) == $country['id'] ? 'selected' : '' }}>
                                        {{ $country['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('country_id')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
                    </div>

                    <div class="w-full lg:w-1/4 lg:mr-8 md:pr-8">
                        <div class="mb-2">
                            <label for="state_id" class="tw-form-label">State<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2">
                            <select class="tw-form-control w-full" id="state_id" name="state_id"
                                onchange="loadCities(this.value)">
                                <option value="" disabled selected>Select State</option>
                            </select>
                        </div>
                        @error('state_id')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
                    </div>

                    <div class="w-full lg:w-1/4 lg:mr-8 md:pr-8">
                        <div class="mb-2">
                            <label for="city_id" class="tw-form-label">City<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2">
                            <select class="tw-form-control w-full" id="city_id" name="city_id">
                                <option value="" disabled selected>Select City</option>
                            </select>
                        </div>
                        @error('city_id')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
                    </div>

                    <div class="w-full lg:w-1/4 lg:mr-8 md:pr-8">
                        <div class="mb-2">
                            <label for="pincode" class="tw-form-label">Pincode<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2">
                            <input type="text" class="tw-form-control w-full" id="pincode" name="pincode"
                                value="{{ old('pincode', $user->userprofile->pincode) }}" placeholder="Enter Pincode">
                        </div>
                        @error('pincode')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div class="flex flex-col lg:flex-row">
                <div class="tw-form-group w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="notes" class="tw-form-label">Notes</label>
                        </div>
                        <div class="mb-2">
                            <textarea class="tw-form-control w-full" id="notes" name="notes" rows="3">{{ old('notes', $user->userprofile->notes) }}</textarea>
                        </div>
                        @error('notes')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="mt-4 pb-5">
                <button type="submit" class="btn btn-primary submit-btn">Submit</button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key=AIzaSyBO00niIGAyv2GkZZi-W26Ii6ff3YEyu_w"></script>
    <script>
        var map;
        var subOccupationProfessions = ['business','doctor','engineer','government_employee','lawyer','pastor','police','professionals','self_employed','teacher','others'];

        function initialize() {
            var addressInput = document.getElementById('address');
            var autocomplete = new google.maps.places.Autocomplete(addressInput);
            autocomplete.setTypes(['geocode']);
            google.maps.event.addListener(autocomplete, 'place_changed', function() {
                var place = autocomplete.getPlace();
                if (!place.geometry) return;
            });
            if (addressInput.value) {
                codeAddress();
            }
        }

        function longlat(lat, lng) {
            var myLatlng = new google.maps.LatLng(lat, lng);
            var myOptions = { zoom: 8, center: myLatlng, mapTypeId: google.maps.MapTypeId.ROADMAP };
            map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);
            var marker = new google.maps.Marker({ draggable: true, position: myLatlng, map: map, title: 'Your location' });
            google.maps.event.addListener(marker, 'mouseup', function(event) {
                document.getElementById('latitude').value = event.latLng.lat();
                document.getElementById('longitude').value = event.latLng.lng();
            });
        }

        function codeAddress() {
            var geocoder = new google.maps.Geocoder();
            var address = document.getElementById('address').value;
            geocoder.geocode({ 'address': address }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    document.getElementById('latitude').value = results[0].geometry.location.lat();
                    document.getElementById('longitude').value = results[0].geometry.location.lng();
                    longlat(results[0].geometry.location.lat(), results[0].geometry.location.lng());
                }
            });
        }

        google.maps.event.addDomListener(window, 'load', initialize);

        (function() {
            var s = document.createElement('style');
            s.textContent = '@media(max-width:1200px){#map_canvas{width:100%!important}}';
            document.head.appendChild(s);
        })();

        function loadStates(countryId, selectedStateId, selectedCityId) {
            var stateSelect = document.getElementById('state_id');
            var citySelect  = document.getElementById('city_id');
            stateSelect.innerHTML = '<option value="" disabled selected>Select State</option>';
            citySelect.innerHTML  = '<option value="" disabled selected>Select City</option>';
            if (!countryId) return;
            axios.get('/admin/ajax/states', { params: { country_id: countryId } })
                .then(function(response) {
                    response.data.forEach(function(state) {
                        var opt = document.createElement('option');
                        opt.value = state.id;
                        opt.text  = state.name;
                        if (selectedStateId && state.id == selectedStateId) opt.selected = true;
                        stateSelect.appendChild(opt);
                    });
                    if (selectedStateId) loadCities(selectedStateId, selectedCityId);
                });
        }

        function loadCities(stateId, selectedCityId) {
            var citySelect = document.getElementById('city_id');
            citySelect.innerHTML = '<option value="" disabled selected>Select City</option>';
            if (!stateId) return;
            axios.get('/admin/ajax/cities', { params: { state_id: stateId } })
                .then(function(response) {
                    response.data.forEach(function(city) {
                        var opt = document.createElement('option');
                        opt.value = city.id;
                        opt.text  = city.name;
                        if (selectedCityId && city.id == selectedCityId) opt.selected = true;
                        citySelect.appendChild(opt);
                    });
                });
        }

        function toggleSubOccupation(value) {
            var wrap = document.getElementById('sub_occupation_wrap');
            wrap.style.display = subOccupationProfessions.includes(value) ? '' : 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            var countrySelect = document.getElementById('country_id');
            if (countrySelect.value) {
                loadStates(
                    countrySelect.value,
                    '{{ old("state_id", $user->userprofile->state_id) }}',
                    '{{ old("city_id", $user->userprofile->city_id) }}'
                );
            }
        });
    </script>
@endpush

<style>
    .tw-label { color: #3492e2; }
    .tw-label input[type="file"] { display: none; }
</style>
