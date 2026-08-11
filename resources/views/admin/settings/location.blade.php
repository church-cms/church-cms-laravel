@extends('layouts.admin.layout')
@section('content')
<div class="w-full flex flex-col">
<h1 class="text-xl font-semibold mb-6 text-gray-700">Settings</h1>
<div class="w-full main-content bg-white flex h-auto">
    <div class="flex flex-col lg:flex-row w-full">
        @include('layouts.admin.settingsbar')
        <div class="flex-1 px-8 py-6 min-w-0">
            @include('partials._page_header', ['pageTitle' => 'Location and Map'])
            @include('partials.message')

            <form method="POST" action="{{ url('/admin/settings/location') }}">
                @csrf
                <div class="bg-white border border-gray-200 rounded shadow-sm mb-6 max-w-3xl">
                    <table class="form-table w-full">
                        <tbody>

                            <tr>
                                <th scope="row" class="w-32 py-4 px-5 text-left align-top">
                                    <label for="address" class="text-sm font-medium text-gray-700 leading-8">Address</label>
                                </th>
                                <td class="py-4 px-6">
                                    <div class="relative w-full">
                                        <input type="text" name="address" id="address"
                                            class="tw-form-control w-full"
                                            placeholder="Enter church address"
                                            value="{{ old('address', $churchdetail['address'] ?? '') }}">
                                        <span class="absolute m-2 top-0 right-0">
                                            <a href="#" onclick="codeAddress(); return false;" title="Geocode address">
                                                <img src="{{ url('/uploads/icons/search.svg') }}" class="w-4 h-4">
                                            </a>
                                        </span>
                                    </div>
                                    <div class="mt-3 w-full">
                                        <div id="map_canvas" class="rounded border border-gray-200" style="height: 280px;"></div>
                                    </div>
                                    <div hidden>
                                        <input id="latitude" type="text" name="latitude"
                                            value="{{ old('latitude', $churchdetail['latitude'] ?? '') }}">
                                        <input id="longitude" type="text" name="longitude"
                                            value="{{ old('longitude', $churchdetail['longitude'] ?? '') }}">
                                    </div>
                                    <p class="mt-2 text-xs text-gray-400">Type an address and click the search icon, or drag the pin to set coordinates.</p>
                                    <span class="text-red-500 text-xs block mt-1">{{ $errors->first('address') }}</span>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <div class="mb-8">
                    <input type="submit" value="Save Changes" name="submit" class="btn btn-primary submit-btn cursor-pointer">
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key=AIzaSyBO00niIGAyv2GkZZi-W26Ii6ff3YEyu_w"></script>
<script>
    var map;

    function initialize() {
        var addressInput = document.getElementById('address');
        var autocomplete = new google.maps.places.Autocomplete(addressInput);
        autocomplete.setTypes(['geocode']);

        var lat = parseFloat('{{ $churchdetail['latitude']  ?? 9.9252007 }}') || 9.9252007;
        var lng = parseFloat('{{ $churchdetail['longitude'] ?? 78.11977539999998 }}') || 78.11977539999998;
        longlat(lat, lng);
    }

    function longlat(lat, lng) {
        var myLatlng = new google.maps.LatLng(lat, lng);
        var myOptions = { zoom: 15, center: myLatlng, mapTypeId: google.maps.MapTypeId.ROADMAP };
        map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);
        var marker = new google.maps.Marker({ draggable: true, position: myLatlng, map: map, title: 'Church location' });
        google.maps.event.addListener(marker, 'mouseup', function(event) {
            document.getElementById('latitude').value  = event.latLng.lat();
            document.getElementById('longitude').value = event.latLng.lng();
        });
    }

    function codeAddress() {
        var geocoder = new google.maps.Geocoder();
        var address  = document.getElementById('address').value;
        geocoder.geocode({ address: address }, function(results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                document.getElementById('latitude').value  = results[0].geometry.location.lat();
                document.getElementById('longitude').value = results[0].geometry.location.lng();
                longlat(results[0].geometry.location.lat(), results[0].geometry.location.lng());
            }
        });
    }

    google.maps.event.addDomListener(window, 'load', initialize);
</script>
@endpush
