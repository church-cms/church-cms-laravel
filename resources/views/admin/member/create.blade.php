@extends('layouts.admin.layout')
@section('content')

<div class="w-full mx-2">
    <h1 class="admin-h1 mb-3 flex items-center">
        <a href="{{ url('/admin/members') }}" class="rounded-full bg-gray-100 p-2" title="Back">
            <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
        </a>
        <span class="mx-3">Add Member</span>
    </h1>
    @include('partials.message')
    <div class="bg-white shadow pl-3 lg:pl-4 md:pl-4 pr-3 lg:pr-0 md:pr-0">
        <form method="POST" action="{{ url('/admin/member/add') }}" enctype="multipart/form-data">
            @csrf

            {{-- Relation (only when adding as family member) --}}
            @if($ref_name != '')
            <input type="hidden" name="ref_name" value="{{ $ref_name }}">
            <div class="flex">
                <div class="tw-form-group w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="relation" class="tw-form-label">Relation<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2">
                            <select class="tw-form-control w-full" id="relation" name="relation">
                                <option value="" disabled selected>Relationship</option>
                                @foreach($relationlist as $rel)
                                <option value="{{ $rel['id'] }}" {{ old('relation') == $rel['id'] ? 'selected' : '' }}>{{ $rel['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('relation')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
            @endif

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
                            <input type="text" class="tw-form-control w-full member-icon" id="firstname" name="firstname" value="{{ old('firstname') }}" placeholder="First Name">
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
                            <input type="text" class="tw-form-control w-full member-icon" id="lastname" name="lastname" value="{{ old('lastname') }}" placeholder="Last Name">
                        </div>
                        @error('lastname')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Birth First Name / Birth Last Name --}}
            <div class="flex flex-col lg:flex-row">
                <div class="tw-form-group w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="birth_firstname" class="tw-form-label">Birth First Name</label>
                        </div>
                        <div class="mb-2 relative">
                            <span class="absolute m-2">
                                <img src="{{ url('/uploads/icons/form-user.svg') }}" class="w-4 h-4">
                            </span>
                            <input type="text" class="tw-form-control w-full member-icon" id="birth_firstname" name="birth_firstname" value="{{ old('birth_firstname') }}" placeholder="Birth First Name">
                        </div>
                        @error('birth_firstname')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="tw-form-group w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="birth_lastname" class="tw-form-label">Birth Last Name</label>
                        </div>
                        <div class="mb-2 relative">
                            <span class="absolute m-2">
                                <img src="{{ url('/uploads/icons/form-user.svg') }}" class="w-4 h-4">
                            </span>
                            <input type="text" class="tw-form-control w-full member-icon" id="birth_lastname" name="birth_lastname" value="{{ old('birth_lastname') }}" placeholder="Birth Last Name">
                        </div>
                        @error('birth_lastname')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
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
                            <input type="text" class="tw-form-control w-full" id="aadhar_number" name="aadhar_number" value="{{ old('aadhar_number') }}" placeholder="Aadhaar Number">
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
                            <input type="date" class="tw-form-control w-full" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                        </div>
                        @error('date_of_birth')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Mobile / Email --}}
            <div class="flex flex-col lg:flex-row">
                <div class="tw-form-group w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="mobile_no" class="tw-form-label">Mobile Number<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 relative">
                            <span class="absolute m-2">
                                <img src="{{ url('/uploads/icons/mobile.svg') }}" class="w-4 h-4">
                            </span>
                            <input type="text" class="tw-form-control w-full member-icon" id="mobile_no" name="mobile_no" value="{{ old('mobile_no') }}" placeholder="Mobile Number">
                        </div>
                        @error('mobile_no')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="tw-form-group w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="email" class="tw-form-label">Email ID</label>
                        </div>
                        <div class="mb-2 relative">
                            <span class="absolute m-2">
                                <img src="{{ url('/uploads/icons/send.svg') }}" class="w-4 h-4">
                            </span>
                            <input type="text" class="tw-form-control w-full member-icon" id="email" name="email" value="{{ old('email') }}" placeholder="Email ID">
                        </div>
                        @error('email')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
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
                                <input type="radio" name="gender" id="gender1" value="male" {{ old('gender') == 'male' ? 'checked' : '' }}>
                                <span class="text-sm mx-1">Male</span>
                            </div>
                            <div class="w-1/2 flex items-center tw-form-control lg:mr-8">
                                <input type="radio" name="gender" id="gender2" value="female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                                <span class="text-sm mx-1">Female</span>
                            </div>
                        </div>
                        @error('gender')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="tw-form-group w-full lg:w-1/3">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="profession" class="tw-form-label">Occupation<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2">
                            <select class="tw-form-control w-full" id="profession" name="profession" onchange="toggleSubOccupation(this.value)">
                                <option value="" disabled selected>Occupation</option>
                                @foreach($occupationlist as $occ)
                                <option value="{{ $occ['id'] }}" {{ old('profession') == $occ['id'] ? 'selected' : '' }}>{{ $occ['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('profession')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
                    </div>
                </div>

                @php
                $subOccProf = ['business','doctor','engineer','government_employee','lawyer','pastor','police','professionals','self_employed','teacher','others'];
                @endphp
                <div class="tw-form-group w-full lg:w-1/3" id="sub_occupation_group"
                    style="{{ in_array(old('profession'), $subOccProf) ? '' : 'display:none' }}">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="sub_occupation" class="tw-form-label">Sub-Category<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2">
                            <input type="text" class="tw-form-control w-full" id="sub_occupation" name="sub_occupation" value="{{ old('sub_occupation') }}" placeholder="Sub Category">
                        </div>
                        @error('sub_occupation')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Address / Map --}}
            <div class="flex flex-wrap">
                <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="address" class="col-md-4 tw-form-label">Address<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full relative">
                            <input type="text" name="address" class="tw-form-control w-full" id="address" value="{{ old('address') }}" placeholder="Enter Address">
                            <span class="absolute m-2 top-0 right-0">
                                <a href="#" onclick="codeAddress(); return false;" dusk="getCords" id="getCords">
                                    <img src="{{ url('/uploads/icons/search.svg') }}" class="w-4 h-4">
                                </a>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div id="map_canvas" class="tw-form-control w-full" style="height: 250px;"></div>
                    </div>
                </div>

                <div class="form-group" hidden>
                    <label for="latitude" class="col-md-4 control-label">Latitude</label>
                    <div class="col-md-6">
                        <input id="latitude" type="text" class="tw-form-control w-1/2" name="latitude" value="{{ old('latitude') }}">
                    </div>
                </div>

                <div class="form-group" hidden>
                    <label for="longitude" class="col-md-4 control-label">Longitude</label>
                    <div class="col-md-6">
                        <input id="longitude" type="text" class="tw-form-control w-1/2" name="longitude" value="{{ old('longitude') }}">
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
                            <select class="tw-form-control w-full" id="country_id" name="country_id" onchange="loadStates(this.value)">
                                <option value="" disabled>Select Country</option>
                                @foreach($countrylist as $country)
                                <option value="{{ $country['id'] }}" {{ old('country_id', 7) == $country['id'] ? 'selected' : '' }}>{{ $country['name'] }}</option>
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
                            <select class="tw-form-control w-full" id="state_id" name="state_id" onchange="loadCities(this.value)">
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
                            <input type="text" class="tw-form-control w-full" id="pincode" name="pincode" value="{{ old('pincode') }}" placeholder="Enter Pincode">
                        </div>
                        @error('pincode')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Family / Marital Status / Marriage Date --}}
            <div class="flex flex-col lg:flex-row">
                <div class="tw-form-group w-full lg:w-1/4">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="family" class="tw-form-label">Family</label>
                        </div>
                        <div class="mb-2">
                            <input type="text" class="tw-form-control w-full" id="family" name="family" value="{{ old('family') }}" placeholder="Family Name">
                        </div>
                        @error('family')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="tw-form-group w-full lg:w-1/4">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="marriage_status" class="tw-form-label">Marital Status<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2">
                            <select class="tw-form-control w-full" id="marriage_status" name="marriage_status" onchange="toggleMarriageDate(this.value)">
                                <option value="" disabled selected>Marital Status</option>
                                @foreach($maritalstatuslist as $status)
                                <option value="{{ $status['id'] }}" {{ old('marriage_status') == $status['id'] ? 'selected' : '' }}>{{ $status['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('marriage_status')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="tw-form-group w-full lg:w-1/4" id="marriage_date_group"
                    style="{{ (old('marriage_status') && old('marriage_status') != 'single') ? '' : 'display:none' }}">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="marriage_start_date" class="tw-form-label">Marriage Date<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2">
                            <input type="date" class="tw-form-control w-full" id="marriage_start_date" name="marriage_start_date" value="{{ old('marriage_start_date') }}">
                        </div>
                        @error('marriage_start_date')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Hidden fields --}}
            <input type="hidden" name="membership_start_date" value="{{ $membership_start_date }}">
            <input type="hidden" name="membership_type" value="member">

            {{-- Avatar / Notes --}}
            <div class="flex flex-col lg:flex-row">
                <div class="tw-form-group w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="avatar" class="tw-form-label">Avatar<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2">
                            <input type="file" name="avatar" id="avatar" class="tw-form-control w-full" accept="image/*" onchange="previewAvatar(this)">
                        </div>
                        <div class="mb-2" id="avatar_preview_wrap" style="{{ $tempAvatar ? 'display:block' : 'display:none' }}">
                            <img id="avatar_preview"
                                 src="{{ $tempAvatar ? asset('storage/'.$tempAvatar) : '' }}"
                                 alt="Avatar Preview" class="w-24 h-24 rounded-full object-cover border">
                        </div>
                        @error('avatar')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="tw-form-group w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="notes" class="tw-form-label">Notes</label>
                        </div>
                        <div class="mb-2">
                            <textarea class="tw-form-control w-full" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        </div>
                        @error('notes')<span class="text-red-500 text-xs font-semibold">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Submit / Reset --}}
            <div class="mt-4 pb-5">
                <button type="submit" dusk="submit-btn" class="btn btn-primary submit-btn">Submit</button>
                <a href="{{ url('/admin/member/add') }}" class="btn btn-reset reset-btn">Reset</a>
            </div>

        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    var subOccupationProfessions = ['business', 'doctor', 'engineer', 'government_employee', 'lawyer', 'pastor', 'police', 'professionals', 'self_employed', 'teacher', 'others'];

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

    function previewAvatar(input) {
        var file = input.files[0];
        if (!file) return;
        var wrap = document.getElementById('avatar_preview_wrap');
        var img  = document.getElementById('avatar_preview');
        img.src  = URL.createObjectURL(file);
        wrap.style.display = '';
    }

    function toggleSubOccupation(value) {
        var group = document.getElementById('sub_occupation_group');
        group.style.display = subOccupationProfessions.includes(value) ? '' : 'none';
    }

    function toggleMarriageDate(value) {
        var group = document.getElementById('marriage_date_group');
        group.style.display = (value && value !== 'single') ? '' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
        var countrySelect = document.getElementById('country_id');
        if (countrySelect.value) {
            loadStates(
                countrySelect.value,
                '{{ old("state_id") }}',
                '{{ old("city_id") }}'
            );
        }
    });
</script>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key=AIzaSyBO00niIGAyv2GkZZi-W26Ii6ff3YEyu_w"></script>
<script type="text/javascript">
    var map;

    function initialize() {
        var address = document.getElementById('address');
        var autocomplete = new google.maps.places.Autocomplete(address);
        autocomplete.setTypes(['geocode']);
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var place = autocomplete.getPlace();
            if (!place.geometry) return;
        });
        longlat(9.9252007, 78.11977539999998);
    }

    function longlat(lat, lng) {
        var myLatlng = new google.maps.LatLng(lat, lng);
        var myOptions = {
            zoom: 15,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
        var marker = new google.maps.Marker({
            draggable: true,
            position: myLatlng,
            map: map,
            title: "Your location"
        });
        google.maps.event.addListener(marker, 'mouseup', function(event) {
            document.getElementById('latitude').value = event.latLng.lat();
            document.getElementById('longitude').value = event.latLng.lng();
        });
    }

    function codeAddress() {
        geocoder = new google.maps.Geocoder();
        var address = document.getElementById("address").value;
        geocoder.geocode({
            'address': address
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                document.getElementById('latitude').value = results[0].geometry.location.lat();
                document.getElementById('longitude').value = results[0].geometry.location.lng();
                longlat(results[0].geometry.location.lat(), results[0].geometry.location.lng());
            }
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize);
</script>
@endpush