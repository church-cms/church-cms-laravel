@extends('layouts.admin.layout')
@section('content')

    <div class="w-full mx-2">
        <h1 class="admin-h1 mb-3 flex items-center">
            <a href="{{ url('/admin/guests') }}" class="rounded-full bg-gray-100 p-2" title="Back">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Add Guest</span>
        </h1>
        @include('partials.message')
        <div class="bg-white shadow pl-3 lg:pl-4 md:pl-4 pr-3 lg:pr-0 md:pr-0">
            <form method="POST" action="" enctype="multipart/form-data">
                @csrf
                @if (count($errors) > 0)
                    <span class="text-red-500 text-xs font-semibold">{{ $errors }}</span>
                    <!--This is temporary.Have to change -->
                @endif
                <create-guest url="{{ url('/') }}" ref_name="{{ $ref_name }}"></create-guest>

                <portal to="address">
                    <div class="flex flex-wrap">
                        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
                            <div class="lg:mr-8 md:mr-8">
                                <div class="mb-2">
                                    <label for="address" class="col-md-4 tw-form-label">Address</label>
                                </div>
                                <div class="mb-2 w-full relative">
                                    <input type="text" name="address" class="tw-form-control w-full" id="address"
                                        value="{{ old('address') }}" placeholder="Enter Address">
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
                            <label for="latitude" class="col-md-4 control-label">Latitude
                            </label>
                            <div class="col-md-6">
                                <input id="latitude" type="text" class="tw-form-control w-1/2" name="latitude"
                                    value="{{ old('latitude') }}">
                            </div>
                        </div>

                        <div class="form-group" hidden>
                            <label for="longitude" class="col-md-4 control-label">Longitude
                            </label>
                            <div class="col-md-6">
                                <input id="longitude" type="text" class="tw-form-control w-1/2" name="longitude"
                                    value="{{ old('longitude') }}">
                            </div>
                        </div>
                    </div>
                </portal>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
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
            //Map
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

            //map
        }

        function codeAddress() {
            geocoder = new google.maps.Geocoder();
            var address = document.getElementById("address").value;
            geocoder.geocode({
                'address': address
            }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    //alert("Latitude: "+results[0].geometry.location.lat());
                    // alert("Longitude: "+results[0].geometry.location.lng());
                    document.getElementById('latitude').value = results[0].geometry.location.lat();
                    document.getElementById('longitude').value = results[0].geometry.location.lng();
                    longlat(results[0].geometry.location.lat(), results[0].geometry.location.lng());
                } else {
                    //alert("Geocode was not successful for the following reason: " + status);
                }
            });
        }
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
@endpush
