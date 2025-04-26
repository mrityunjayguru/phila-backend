@extends('layouts.backend.master')

@section('css')
<!-- Additional CSS if needed -->
@endsection

@section('content')
<style>
#map_canvas {
    width: 980px;
    height: 500px;
}

#current {
    padding-top: 25px;
}

.image-preview {}

.parent_remove_btn {
    cursor: pointer;
}

.error {
    color: red;
}

#pac-input {
    position: absolute;
    top: 10px !important;
    right: 60px !important;
    height: 28px;
    z-index: 9999;
}
</style>

<script src="{{ asset('adminAssets/scripts/custom/google_map_api.js') }}"></script>

<div class="app-page-title app-page-title-simple">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div>
                <div class="page-title-head center-elem">
                    <span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
                    <span class="d-inline-block">Edit Audio </span>
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            <div class="page-title-subheading opacity-10">
                <nav class="" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i aria-hidden="true"
                                    class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('audio') }}">Audio</a></li>
                        <li class="active breadcrumb-item" aria-current="page">Edit Audio</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- CONTENT START -->
<div class="main-card mb-3 card">
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data" id="audioForm" onsubmit="saveData()" method="POST"
            action="javascript:void(0);">
            @csrf
            <div class="form-row">
                <div class="col-md-6">
                    <div class="position-relative form-group">
                        <input type="hidden" id="id" name="id" value="{{$data['id']}}">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" placeholder="Enter Title" class="form-control"
                            value="{{$data['title']}}">
                        <div class="validation-div" id="val-title"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="position-relative form-group">
                        <label for="priority" class="">Priority</label>
                        <input type="number" id="priority" placeholder="0 - 99" class="form-control" name="priority"
                            value="{{$data['priority']}}">
                        <div class="validation-div" id="val-priority"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="position-relative form-group">
                        <label for="language">Language</label>
                        <select name="language[]" id="language"
                            class="form-control multiselect js-example-basic-multiple" multiple="multiple">
                            @foreach($getLanguage as $languages)
                            <option
                                value="{{$languages->tag}}  @if(in_array($languages->tag, $selectedLanguages)) selected @endif">
                                {{ $languages->language }} ({{ $languages->tag }})
                            </option>
                            @endforeach
                        </select>
                        <div class="validation-div" id="val-language"></div>
                        <div id="selectedLanguages"></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="position-relative form-group">
                        <label for="latitude">Latitude</label>
                        <input type="text" id="latitude" name="latitude" placeholder="Enter Latitude"
                            class="form-control" value="{{$data['latitude']}}">
                        <div class="validation-div" id="val-latitude"></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="position-relative form-group">
                        <label for="longitude">Longitude</label>
                        <input type="text" id="longitude" name="longitude" placeholder="Enter Longitude"
                            class="form-control" value="{{$data['longitude']}}">
                        <div class="validation-div" id="val-longitude"></div>
                    </div>
                </div>

            </div>
            <div class="form-row">
                <div class="col-md-6">
                    <div class="position-relative form-group">
                        <label for="audio">Audio File</label>
                        @if(!empty($data['file_path']))
                        <p>Current File: <audio controls>
                                <source src="{{ asset($data['file_path']) }}" type="audio/mpeg">
                                Your browser does not support the audio element.
                        </p>
                        @endif
                        <input type="file" id="audio" class="form-control-file" name="audio">
                        <div class="validation-div" id="val-audio"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="position-relative form-group">
                        <label for="exampleFile" class="">Image</label>
                        <input name="image" id="image" type="file" class="form-control-file item-img" accept="image/*">
                        <div class="validation-div" id="val-image"></div>
                        <div class="image-preview">
                            <button type="button" class="parent_remove_btn" data-id="{{$data->id}}"><i
                                    aria-hidden="true" class="fa fa-remove remove_image_btn"></i></button>
                            <img id="image-src" src="@if($data->image) {{ asset($data->image) }} @endif" width="50%" />
                        </div>
                        <input type="hidden" id="img-blob">
                    </div>
                </div>
            </div>
            <div class="form-row">
                <label for="description" class="">Select Location</label>
                <div class="col-md-12">
                    <input id="pac-input" class="controls" type="text" placeholder="Type to search" />
                    <div id="map_canvas" style="height: 254px; width:100%;"></div>
                </div>
            </div>
            <audio id="audio" src="path_to_your_audio_file.mp3"></audio>

            <div class="position-relative form-group mt-lg-3">
                <label for="description" class="">Audio Description</label>
                <textarea name="description" id="description" rows="4"
                    class="form-control">{{$data['description']}}</textarea>
                <div class="validation-div" id="val-description"></div>
            </div>
            <div class="form-row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="active" @if($data['status']=='active' ) selected @endif>
                                {{ trans('common.active') }}</option>
                            <option value="inactive" @if($data['status']=='inactive' ) selected @endif>
                                {{ trans('common.inactive') }}</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="status">Want to show icon on map?</label>
                        <select class="form-control" id="show_icon" name="show_icon">
                            <option value="yes" @if($data['show_icon']=='yes' ) selected @endif>
                                {{ trans('common.yes') }}</option>
                            <option value="no" @if($data['show_icon']=='no' ) selected @endif>{{ trans('common.no') }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="mt-2 btn btn-primary">{{ trans('common.submit') }}</button>
        </form>
    </div>
</div>

<script
    src="https://maps.googleapis.com/maps/api/js?key={{Settings::get('google_map_api_key')}}&callback=initMap&v=3.exp&sensor=false&libraries=places"
    async></script>

<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<!-- CONTENT OVER -->

@endsection

@section('js')
<script>
$(document).ready(function() {

    //Select multiple language
    $('.js-example-basic-multiple').select2();

    // Custom validation rule for file extension
    $.validator.addMethod("mp3", function(value, element) {
        // Check if the element has a file
        if (element.files && element.files.length > 0) {
            // Get the file extension
            var fileExtension = value.split('.').pop().toLowerCase();
            // Check if it is an mp3 file
            return fileExtension === "mp3";
        }
        return true; // If no file is selected, do not validate
    }, "Please upload a valid MP3 file.");

    $.validator.addMethod("validImage", function(value, element) {
        // Allowed file extensions
        var validExtensions = ["jpg", "jpeg", "png"];
        // Get the file extension
        var fileExtension = value.split('.').pop().toLowerCase();
        // Check if the file extension is in the allowed list
        return $.inArray(fileExtension, validExtensions) !== -1;
    }, "Please upload a valid image file (jpg, jpeg, png).");

    // Validate the form
    $("#audioForm").validate({
        rules: {
            audio: {
                required: true,
                mp3: true // Use the custom rule
            },
            image: {
                required: true,
                validImage: true,
            },
        },
        messages: {
            audio: {
                required: "The audio field is required",
            },
            image: {
                required: "The image field is required",
            },
        },
    });

    // initMap(39.94996, -75.14886, 1);

    let map;
    let marker;
    let userPositionMarker;


    function initMap() {
        // Default latitude and longitude
        const defaultLat = 39.94996;
        const defaultLng = -75.1652215;

        // Initialize the map
        map = new google.maps.Map(document.getElementById('map_canvas'), {
            center: {
                lat: defaultLat,
                lng: defaultLng
            },
            zoom: 8
        });

        // Create the marker
        marker = new google.maps.Marker({
            map: map,
            position: {
                lat: defaultLat,
                lng: defaultLng
            },
            draggable: true
        });

        // Set the default values to the input fields
        $('#latitude').val(defaultLat);
        $('#longitude').val(defaultLng);

        // Set the marker based on the input values
        $('#latitude, #longitude').on('input', function() {
            updateMarker();
        });

        // Update the inputs when marker is dragged
        marker.addListener('dragend', function() {
            let position = marker.getPosition();
            $('#latitude').val(position.lat());
            $('#longitude').val(position.lng());
        });

        // Initialize the search box
        let input = document.getElementById('pac-input');
        let searchBox = new google.maps.places.SearchBox(input);

        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        map.addListener('bounds_changed', function() {
            searchBox.setBounds(map.getBounds());
        });

        searchBox.addListener('places_changed', function() {
            let places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }

            // For each place, get the location and update the marker
            let bounds = new google.maps.LatLngBounds();
            places.forEach(function(place) {
                if (!place.geometry || !place.geometry.location) {
                    console.log("Returned place contains no geometry");
                    return;
                }

                marker.setPosition(place.geometry.location);
                map.setCenter(place.geometry.location);
                map.setZoom(15);

                $('#latitude').val(place.geometry.location.lat());
                $('#longitude').val(place.geometry.location.lng());

                if (place.geometry.viewport) {
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
        });

        // Geolocation to track user position
        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(
                function(position) {
                    let userLatLng = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    if (!userPositionMarker) {
                        userPositionMarker = new google.maps.Marker({
                            position: userLatLng,
                            map: map,
                            icon: {
                                path: google.maps.SymbolPath.CIRCLE,
                                scale: 5,
                                fillColor: '#00F',
                                fillOpacity: 1,
                                strokeWeight: 0
                            }
                        });
                    } else {
                        userPositionMarker.setPosition(userLatLng);
                    }

                    checkProximity(userLatLng, marker.getPosition());
                },
                function(error) {
                    console.log("Geolocation error: " + error.message);
                }, {
                    enableHighAccuracy: true,
                    maximumAge: 0,
                    timeout: 5000
                }
            );
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function updateMarker() {
        let lat = parseFloat($('#latitude').val());
        let lng = parseFloat($('#longitude').val());

        if (!isNaN(lat) && !isNaN(lng)) {
            let position = {
                lat: lat,
                lng: lng
            };
            marker.setPosition(position);
            map.setCenter(position);
        }
    }

    function checkProximity(userLatLng, markerLatLng) {
        const distance = google.maps.geometry.spherical.computeDistanceBetween(
            new google.maps.LatLng(userLatLng.lat, userLatLng.lng),
            new google.maps.LatLng(markerLatLng.lat(), markerLatLng.lng())
        );

        if (distance <= radius) {
            document.getElementById('audio').play();
        } else {
            document.getElementById('audio').pause();
        }
    }
    initMap();

    $("#image").change(function() {
        readURL2(this);
    });

    $('#latitude').prop('readonly', true);
    $('#longitude').prop('readonly', true);
});


function readURL2(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            jQuery('#image-src').attr('src', e.target.result);
        }
        $('.parent_remove_btn').show();
        reader.readAsDataURL(input.files[0]);
    }
}

function readURL3(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            jQuery('#image-src-2').attr('src', e.target.result);
        }
        $('.parent_remove_btn-2').show();
        reader.readAsDataURL(input.files[0]);
    }
}

// CREATE
function saveData() {
    var data = new FormData();
    var id = $('#id').val();
    data.append('id', id);
    data.append('title', $('#title').val());
    data.append('title', $('#title').val());
    data.append('latitude', $('#latitude').val());
    data.append('longitude', $('#longitude').val());
    data.append('status', $('#status').val());
    data.append('language', $('#language').val());
    data.append('audio', $('#audio')[0].files[0]);
    data.append('description', $('#description').val());
    data.append('image', $('#image')[0].files[0]);
    data.append('show_icon', $('#show_icon').val());
    data.append('priority', $('#priority').val());
    var routeUrl = '{{ route("update-audio") }}';

    // Make the AJAX call
    var response = adminAjax(routeUrl, data);
    // console.log(data);
    // return false;
    if (response.status == '200') {
        swal.fire({
            type: 'success',
            title: response.message,
            showConfirmButton: false,
            timer: 3000
        });
        window.location.href = "{{route('audio')}}";
    } else if (response.error) {
        $('.validation-div').text('');
        $.each(response.error, function(index, value) {
            $('#val-' + index).text(value[0]);
        });

    } else if (response.status == '201') {
        swal.fire({
            title: response.message,
            type: 'error'
        });
    }
}
</script>
@endsection