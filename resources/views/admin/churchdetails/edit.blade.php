@extends('layouts.admin.layout')

@section('content')
    <div class="w-full mx-2">
        <h1 class="admin-h1 mb-3 flex items-center">
            <span class="mx-3">Church Details</span>
        </h1>
        @include('partials.message')
        <div class="bg-white shadow my-5">
            <div class="px-6 py-6">
                <form method="POST" action="{{ url('/admin/churchdetails/edit/' . \Auth::user()->church_id) }}"
                    enctype="multipart/form-data">
                    @csrf

                    <table class="form-table w-full">
                        <tbody>

                            {{-- Site Title --}}
                            <tr class="border-b border-gray-100">
                                <th scope="row" class="w-1/4 py-4 pr-6 text-right align-top">
                                    <label for="site_title" class="text-sm font-medium text-gray-700 leading-8">Site Title</label>
                                </th>
                                <td class="py-4">
                                    <input type="text" name="site_title" id="site_title"
                                        class="tw-form-control w-full lg:w-2/3"
                                        placeholder="Site Title"
                                        value="{{ old('site_title', $churchdetail['site_title']) }}">
                                    <span class="text-red-500 text-xs font-semibold block mt-1">{{ $errors->first('site_title') }}</span>
                                </td>
                            </tr>

                            {{-- Site Description --}}
                            <tr class="border-b border-gray-100">
                                <th scope="row" class="w-1/4 py-4 pr-6 text-right align-top">
                                    <label for="site_description" class="text-sm font-medium text-gray-700 leading-8">Site Description</label>
                                </th>
                                <td class="py-4">
                                    <textarea name="site_description" id="site_description"
                                        class="tw-form-control w-full lg:w-2/3"
                                        placeholder="Site Description">{{ old('site_description', $churchdetail['site_description']) }}</textarea>
                                    <span class="text-red-500 text-xs font-semibold block mt-1">{{ $errors->first('site_description') }}</span>
                                </td>
                            </tr>

                            {{-- Site Keyword --}}
                            <tr class="border-b border-gray-100">
                                <th scope="row" class="w-1/4 py-4 pr-6 text-right align-top">
                                    <label for="site_keyword" class="text-sm font-medium text-gray-700 leading-8">Site Keyword</label>
                                </th>
                                <td class="py-4">
                                    <input type="text" name="site_keyword" id="site_keyword"
                                        class="tw-form-control w-full lg:w-2/3"
                                        placeholder="Keyword"
                                        value="{{ old('site_keyword', $churchdetail['site_keyword']) }}">
                                    <span class="text-red-500 text-xs font-semibold block mt-1">{{ $errors->first('site_keyword') }}</span>
                                </td>
                            </tr>

                            {{-- Church Logo --}}
                            <tr class="border-b border-gray-100">
                                <th scope="row" class="w-1/4 py-4 pr-6 text-right align-top">
                                    <label for="church_logo" class="text-sm font-medium text-gray-700 leading-8">Church Logo</label>
                                </th>
                                <td class="py-4">
                                    <div class="flex items-start gap-5">
                                        {{-- Current preview --}}
                                        <div class="flex-shrink-0">
                                            @if($churchdetail['church_logo'])
                                                <img src="{{ $churchdetail['church_logo'] }}"
                                                    id="church_logo_preview"
                                                    class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 shadow-sm">
                                            @else
                                                <div id="church_logo_preview"
                                                    class="w-20 h-20 rounded-full bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center text-gray-400 text-xs text-center leading-tight">
                                                    No logo
                                                </div>
                                            @endif
                                        </div>
                                        {{-- Upload control + help --}}
                                        <div>
                                            <label for="church_logo"
                                                class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded text-sm text-gray-700 cursor-pointer hover:bg-gray-50 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M12 12V4m0 0L8 8m4-4l4 4"/>
                                                </svg>
                                                Choose file&hellip;
                                            </label>
                                            <input type="file" name="church_logo" id="church_logo"
                                                accept="image/png,image/jpeg,image/gif,image/svg+xml"
                                                class="sr-only"
                                                onchange="previewImage(this, 'church_logo_preview')">
                                            <p class="mt-2 text-xs text-gray-500 leading-relaxed">
                                                Appears on your website header and emails.<br>
                                                Recommended: <strong>512 &times; 512 px</strong>, PNG or SVG with transparent background.<br>
                                                Max file size: <strong>2 MB</strong>.
                                            </p>
                                            <p id="church_logo_filename" class="mt-1 text-xs text-indigo-600 hidden"></p>
                                        </div>
                                    </div>
                                    <span class="text-red-500 text-xs font-semibold block mt-2">{{ $errors->first('church_logo') }}</span>
                                </td>
                            </tr>

                            {{-- Favicon --}}
                            <tr class="border-b border-gray-100">
                                <th scope="row" class="w-1/4 py-4 pr-6 text-right align-top">
                                    <label for="favicon" class="text-sm font-medium text-gray-700 leading-8">Favicon</label>
                                </th>
                                <td class="py-4">
                                    <div class="flex items-start gap-5">
                                        {{-- Current preview --}}
                                        <div class="flex-shrink-0">
                                            @if($churchdetail['favicon'])
                                                <img src="{{ $churchdetail['favicon'] }}"
                                                    id="favicon_preview"
                                                    class="w-10 h-10 object-contain border border-gray-200 rounded shadow-sm bg-gray-50 p-1">
                                            @else
                                                <div id="favicon_preview"
                                                    class="w-10 h-10 bg-gray-100 border-2 border-dashed border-gray-300 rounded flex items-center justify-center text-gray-400 text-xs text-center leading-tight">
                                                    <span>ICO</span>
                                                </div>
                                            @endif
                                        </div>
                                        {{-- Upload control + help --}}
                                        <div>
                                            <label for="favicon"
                                                class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded text-sm text-gray-700 cursor-pointer hover:bg-gray-50 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M12 12V4m0 0L8 8m4-4l4 4"/>
                                                </svg>
                                                Choose file&hellip;
                                            </label>
                                            <input type="file" name="favicon" id="favicon"
                                                accept="image/x-icon,image/png,image/gif"
                                                class="sr-only"
                                                onchange="previewImage(this, 'favicon_preview')">
                                            <p class="mt-2 text-xs text-gray-500 leading-relaxed">
                                                The small icon shown in browser tabs and bookmarks.<br>
                                                Recommended: <strong>32 &times; 32 px</strong> or <strong>16 &times; 16 px</strong>, ICO or PNG format.<br>
                                                Max file size: <strong>512 KB</strong>.
                                            </p>
                                            <p id="favicon_filename" class="mt-1 text-xs text-indigo-600 hidden"></p>
                                        </div>
                                    </div>
                                    <span class="text-red-500 text-xs font-semibold block mt-2">{{ $errors->first('favicon') }}</span>
                                </td>
                            </tr>

                            {{-- Short Summary --}}
                            <tr class="border-b border-gray-100">
                                <th scope="row" class="w-1/4 py-4 pr-6 text-right align-top">
                                    <label for="short_summary" class="text-sm font-medium text-gray-700 leading-8">Short Summary</label>
                                </th>
                                <td class="py-4">
                                    <textarea name="short_summary" id="short_summary"
                                        class="tw-form-control w-full lg:w-2/3"
                                        placeholder="Short Summary">{{ old('short_summary', $churchdetail['short_summary']) }}</textarea>
                                    <span class="text-red-500 text-xs font-semibold block mt-1">{{ $errors->first('short_summary') }}</span>
                                </td>
                            </tr>

                            {{-- Long Summary --}}
                            <tr class="border-b border-gray-100">
                                <th scope="row" class="w-1/4 py-4 pr-6 text-right align-top">
                                    <label for="long_summary" class="text-sm font-medium text-gray-700 leading-8">Long Summary</label>
                                </th>
                                <td class="py-4">
                                    <textarea name="long_summary" id="long_summary"
                                        class="tw-form-control w-full lg:w-2/3"
                                        rows="3"
                                        placeholder="Long Summary">{{ old('long_summary', $churchdetail['long_summary']) }}</textarea>
                                    <span class="text-red-500 text-xs font-semibold block mt-1">{{ $errors->first('long_summary') }}</span>
                                </td>
                            </tr>

                            {{-- Quotes --}}
                            <tr class="border-b border-gray-100">
                                <th scope="row" class="w-1/4 py-4 pr-6 text-right align-top">
                                    <label for="quotes" class="text-sm font-medium text-gray-700 leading-8">Quotes</label>
                                </th>
                                <td class="py-4">
                                    <textarea name="quotes" id="quotes"
                                        class="tw-form-control w-full lg:w-2/3"
                                        rows="3"
                                        placeholder="Quotes">{{ old('quotes', $churchdetail['quotes']) }}</textarea>
                                    <span class="text-red-500 text-xs font-semibold block mt-1">{{ $errors->first('quotes') }}</span>
                                </td>
                            </tr>

                            {{-- Phone --}}
                            <tr class="border-b border-gray-100">
                                <th scope="row" class="w-1/4 py-4 pr-6 text-right align-top">
                                    <label for="phone" class="text-sm font-medium text-gray-700 leading-8">Phone</label>
                                </th>
                                <td class="py-4">
                                    <input type="text" name="phone" id="phone"
                                        class="tw-form-control w-full lg:w-2/3"
                                        placeholder="Phone"
                                        value="{{ old('phone', $churchdetail['phone']) }}">
                                    <span class="text-red-500 text-xs font-semibold block mt-1">{{ $errors->first('phone') }}</span>
                                </td>
                            </tr>

                            {{-- Email --}}
                            <tr class="border-b border-gray-100">
                                <th scope="row" class="w-1/4 py-4 pr-6 text-right align-top">
                                    <label for="email" class="text-sm font-medium text-gray-700 leading-8">Email</label>
                                </th>
                                <td class="py-4">
                                    <input type="text" name="email" id="email"
                                        class="tw-form-control w-full lg:w-2/3"
                                        placeholder="Email"
                                        value="{{ old('email', $churchdetail['email']) }}">
                                    <span class="text-red-500 text-xs font-semibold block mt-1">{{ $errors->first('email') }}</span>
                                </td>
                            </tr>

                            {{-- Address --}}
                            <tr class="border-b border-gray-100">
                                <th scope="row" class="w-1/4 py-4 pr-6 text-right align-top">
                                    <label for="address" class="text-sm font-medium text-gray-700 leading-8">Address</label>
                                </th>
                                <td class="py-4">
                                    <div class="relative w-full lg:w-2/3">
                                        <input type="text" name="address" id="address"
                                            class="tw-form-control w-full"
                                            placeholder="Enter Address"
                                            value="{{ old('address', $churchdetail['address']) }}"
                                            required>
                                        <span class="absolute m-2 top-0 right-0">
                                            <a href="#" onclick="codeAddress(); return false;" dusk="getCords" id="getCords">
                                                <img src="{{ url('/uploads/icons/search.svg') }}" class="w-4 h-4">
                                            </a>
                                        </span>
                                    </div>
                                    <div class="mt-3 w-full lg:w-2/3">
                                        <div id="map_canvas" class="tw-form-control w-full rounded" style="height: 250px;"></div>
                                    </div>
                                    <div hidden>
                                        <input id="latitude" type="text" class="tw-form-control" name="latitude"
                                            value="{{ old('latitude', $churchdetail['latitude']) }}">
                                        <input id="longitude" type="text" class="tw-form-control" name="longitude"
                                            value="{{ old('longitude', $churchdetail['longitude']) }}">
                                    </div>
                                    <span class="text-red-500 text-xs font-semibold block mt-1">{{ $errors->first('address') }}</span>
                                </td>
                            </tr>

                            {{-- Website --}}
                            <tr class="border-b border-gray-100">
                                <th scope="row" class="w-1/4 py-4 pr-6 text-right align-top">
                                    <label for="website" class="text-sm font-medium text-gray-700 leading-8">Website</label>
                                </th>
                                <td class="py-4">
                                    <input type="text" name="website" id="website"
                                        class="tw-form-control w-full lg:w-2/3"
                                        placeholder="Website"
                                        value="{{ old('website', $churchdetail['website']) }}">
                                    <span class="text-red-500 text-xs font-semibold block mt-1">{{ $errors->first('website') }}</span>
                                </td>
                            </tr>

                            {{-- Facebook --}}
                            <tr class="border-b border-gray-100">
                                <th scope="row" class="w-1/4 py-4 pr-6 text-right align-top">
                                    <label for="facebook" class="text-sm font-medium text-gray-700 leading-8">Facebook</label>
                                </th>
                                <td class="py-4">
                                    <input type="text" name="facebook" id="facebook"
                                        class="tw-form-control w-full lg:w-2/3"
                                        placeholder="Facebook"
                                        value="{{ old('facebook', $churchdetail['facebook']) }}">
                                    <span class="text-red-500 text-xs font-semibold block mt-1">{{ $errors->first('facebook') }}</span>
                                </td>
                            </tr>

                            {{-- Twitter --}}
                            <tr class="border-b border-gray-100">
                                <th scope="row" class="w-1/4 py-4 pr-6 text-right align-top">
                                    <label for="twitter" class="text-sm font-medium text-gray-700 leading-8">Twitter</label>
                                </th>
                                <td class="py-4">
                                    <input type="text" name="twitter" id="twitter"
                                        class="tw-form-control w-full lg:w-2/3"
                                        placeholder="Twitter"
                                        value="{{ old('twitter', $churchdetail['twitter']) }}">
                                    <span class="text-red-500 text-xs font-semibold block mt-1">{{ $errors->first('twitter') }}</span>
                                </td>
                            </tr>

                            {{-- Instagram --}}
                            <tr class="border-b border-gray-100">
                                <th scope="row" class="w-1/4 py-4 pr-6 text-right align-top">
                                    <label for="instagram" class="text-sm font-medium text-gray-700 leading-8">Instagram</label>
                                </th>
                                <td class="py-4">
                                    <input type="text" name="instagram" id="instagram"
                                        class="tw-form-control w-full lg:w-2/3"
                                        placeholder="Instagram"
                                        value="{{ old('instagram', $churchdetail['instagram']) }}">
                                    <span class="text-red-500 text-xs font-semibold block mt-1">{{ $errors->first('instagram') }}</span>
                                </td>
                            </tr>

                            {{-- Submit --}}
                            <tr>
                                <th scope="row" class="w-1/4 py-4 pr-6"></th>
                                <td class="py-4">
                                    <input type="submit" id="submit-btn" value="Save Changes" name="submit"
                                        class="btn btn-primary submit-btn cursor-pointer">
                                </td>
                            </tr>

                        </tbody>
                    </table>

                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <style>
        .form-table th {
            vertical-align: top;
            padding-top: 1rem;
        }
        .form-table td {
            vertical-align: top;
        }
    </style>
    <script>
        function previewImage(input, previewId) {
            var file = input.files[0];
            if (!file) return;

            var filenameEl = document.getElementById(input.id + '_filename');
            if (filenameEl) {
                filenameEl.textContent = '\u2713 ' + file.name;
                filenameEl.classList.remove('hidden');
            }

            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById(previewId);
                if (preview && preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else if (preview) {
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.id = previewId;
                    img.className = preview.className;
                    preview.parentNode.replaceChild(img, preview);
                }
            };
            reader.readAsDataURL(file);
        }
    </script>

    <script
        src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key=AIzaSyBO00niIGAyv2GkZZi-W26Ii6ff3YEyu_w">
    </script>
    <script type="text/javascript">
        var map;

        function initialize() {
            var address = (document.getElementById('address'));
            var autocomplete = new google.maps.places.Autocomplete(address);
            autocomplete.setTypes(['geocode']);
            google.maps.event.addListener(autocomplete, 'place_changed', function() {
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    return;
                }

                var address = '';
                if (place.address_components) {
                    address = [
                        (place.address_components[0] && place.address_components[0].short_name || ''),
                        (place.address_components[1] && place.address_components[1].short_name || ''),
                        (place.address_components[2] && place.address_components[2].short_name || '')
                    ].join(' ');
                }
            });
            longlat(9.9252007, 78.11977539999998);
        }

        function longlat(lat, lng) {
            var myLatlng = new google.maps.LatLng(lat, lng);

            var myOptions = {
                zoom: 15,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }

            map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

            var marker = new google.maps.Marker({
                draggable: true,
                position: myLatlng,
                map: map,
                title: "Your location"
            });
            google.maps.event.addListener(marker, 'mouseup', function(event) {
                document.getElementById('latitude').value = event.latLng.lat()
                document.getElementById('longitude').value = event.latLng.lng()
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
