@extends('layouts.admin.layout')

@section('content')
    <div class="w-full">
        <h1 class="admin-h1 mb-5 flex items-center">
            @if ($user->ref_id == null)
                <a href="{{ url('/admin/member/show/' . $user->name) }}" title="Back" class="rounded-full bg-gray-100 p-2">
                    <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
                </a>
            @else
                <a href="{{ url('/admin/member/show/' . $user->refer->name) }}" title="Back"
                    class="rounded-full bg-gray-100 p-2">
                    <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
                </a>
            @endif
            <span class="mx-3">Edit Member</span>
        </h1>
        @include('partials.message')
        <div class="bg-white shadow px-4 py-2">
            <form method="POST" action="{{ url('/admin/member/edit/' . $user->name) }}" enctype="multipart/form-data">
                @csrf
                <edit-member url="{{ url('/') }}" name="{{ $user->name }}"></edit-member>
                <portal to="edit_address">
                    <div class="flex flex-col lg:flex-row md:flex-row">
                        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
                            <div class="lg:mr-8 md:mr-8">
                                <div class="mb-2">
                                    <label for="address" class="tw-form-label">Address<span
                                            class="text-red-500">*</span></label>
                                </div>
                                <div class="mb-2 w-full relative">
                                    <input type="text" name="address" class="tw-form-control w-full" id="address"
                                        value="{{ $user->userprofile->address }}" required>
                                    <span class="absolute m-2 top-0 right-0">
                                        <a href="#" onclick="codeAddress(); return false;" dusk="getCords" id="getCords">
                                            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30.239px"
                                                height="30.239px" viewBox="0 0 30.239 30.239" xml:space="preserve"
                                                class="w-4 h-4 fill-current text-gray-600">
                                                <g>
                                                    <path
                                                        d="M20.194,3.46c-4.613-4.613-12.121-4.613-16.734,0c-4.612,4.614-4.612,12.121,0,16.735 c4.108,4.107,10.506,4.547,15.116,1.34c0.097,0.459,0.319,0.897,0.676,1.254l6.718,6.718c0.979,0.977,2.561,0.977,3.535,0 c0.978-0.978,0.978-2.56,0-3.535l-6.718-6.72c-0.355-0.354-0.794-0.577-1.253-0.674C24.743,13.967,24.303,7.57,20.194,3.46z M18.073,18.074c-3.444,3.444-9.049,3.444-12.492,0c-3.442-3.444-3.442-9.048,0-12.492c3.443-3.443,9.048-3.443,12.492,0 C21.517,9.026,21.517,14.63,18.073,18.074z">
                                                    </path>
                                                </g>
                                            </svg>
                                        </a>
                                    </span>
                                    <span
                                        class="text-red-500 text-xs font-semibold">{{ $errors->first('address') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
                            <div class="lg:mr-8 md:mr-8">
                                <div id="map_canvas" class="tw-form-control" style="height: 250px;">
                                </div>
                            </div>
                        </div>

                        <div class="tw-form-group w-1/2" hidden>
                            <div class="lg:mr-8 md:mr-8">
                                <div class="mb-2">
                                    <label for="password" class="col-md-4 control-label">Latitude</label>
                                </div>
                                <div class="mb-2 w-full relative">
                                    <input id="latitude" type="text" class="tw-form-control w-1/2" name="latitude"
                                        value="{{ old('latitude') }}">
                                    @if ($errors->has('latitude'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('latitude') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="lg:mr-8 md:mr-8">
                                <div class="mb-2">
                                    <label for="password-confirm" class="col-md-4 control-label">Longitude</label>
                                </div>
                                <div class="mb-2 w-full relative">
                                    <input id="longitude" type="text" class="tw-form-control w-1/2" name="longitude"
                                        value="{{ old('longitude') }}">
                                    @if ($errors->has('longitude'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('longitude') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </portal>
            </form>
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
                codeAddress("address");
            }

            function longlat(lat, lng) {
                //Map
                var myLatlng = new google.maps.LatLng(lat, lng);

                var myOptions = {
                    zoom: 8,
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

    <style>
        .tw-label {
            color: #3492e2;
        }

        .tw-label input[type="file"] {
            display: none;
        }

        @media(max-width:1200px) {
            #map_canvas {
                width: 100% !important;
            }
        }

    </style>
