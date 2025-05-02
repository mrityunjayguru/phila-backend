@extends('layouts.backend.master')
<meta name="csrf-token" content="{{ csrf_token() }}">

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

        .toggle {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        /* Hide the checkbox input */
        .toggle input {
            display: none;
        }

        /* Describe slider's look and position. */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: gray;
            transition: .4s;
            border-radius: 34px;
        }

        /* Describe the white ball's location
                                                      and appearance in the slider. */
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        /* Modify the slider's background color to
                                                      green once the checkbox has been selected. */
        input:checked+.slider {
            background-color: green;
        }

        /* When the checkbox is checked, shift the
                                                      white ball towards the right within the slider. */
        input:checked+.slider:before {
            transform: translateX(26px);
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
                            <li class="breadcrumb-item"><a href="{{ route('audio', $page_id) }}">Audio</a></li>
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
                    {{-- <div id="map_canvas" ></div> --}}
                    <div id="map" style="height: 254px; width:100%;"></div>
                </div>
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <input type="hidden" id="id" name="id" value="{{ $data['id'] }}">
                            <input type="hidden" id="page_id" name="page_id" value="{{ $page_id }}">
                            <label for="title">Title</label>
                            <input type="text" id="title" name="title" placeholder="Enter Title"
                                class="form-control" value="{{ $data['title'] }}">
                            <div class="validation-div" id="val-title"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="priority" class="">Sequence</label>
                            <input type="number" id="priority" placeholder="0 - 99" class="form-control" name="priority"
                                value="{{ $data['priority'] }}" disabled>
                            <div class="validation-div" id="val-priority"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="angle">Angle (degrees)</label>
                            <input type="number" id="angle" name="angle" placeholder="Enter Angle"
                                class="form-control" value="{{ $data['angle'] }}">
                            <div class="validation-div" id="val-angle"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <div class="position-relative form-group">
                                <label for="tolerance">Tolerance</label>
                                <input type="number" id="tolerance" name="tolerance" placeholder="Enter Tolerance"
                                    class="form-control" value="{{ $data['tolerance'] }}">
                                <div class="validation-div" id="val-tolerance"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="proximity">Proximity(In Feet)</label>
                            <input type="number" id="proximity" name="proximity" placeholder="Enter Proximity"
                                class="form-control" value="{{ $data['proximity'] }}">
                            <div class="validation-div" id="val-proximity"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="latitude">Latitude</label>
                            <input type="text" id="latitude" name="latitude" placeholder="Enter Latitude"
                                class="form-control" value="{{ $data['latitude'] }}">
                            <div class="validation-div" id="val-latitude"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="longitude">Longitude</label>
                            <input type="text" id="longitude" name="longitude" placeholder="Enter Longitude"
                                class="form-control" value="{{ $data['longitude'] }}">
                            <div class="validation-div" id="val-longitude"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="exampleFile" class="">Image</label>
                            <input name="image" id="image" type="file" class="form-control-file item-img"
                                accept="image/*">
                            <div class="validation-div" id="val-image"></div>
                            <div class="image-preview">
                                <button type="button" class="parent_remove_btn" data-id="{{ $data->id }}"><i
                                        aria-hidden="true" class="fa fa-remove remove_image_btn"></i></button>
                                <img id="image-src"
                                    src="@if ($data->image) {{ asset($data->image) }} @endif"
                                    width="50%" />
                            </div>
                            <input type="hidden" id="img-blob">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6"></div>
                    <div class="col-md-6" style="margin-left: 91%"><button type="submit"
                            class="mt-2 btn btn-primary">{{ trans('common.submit') }}</button></div>
                </div>
                <div class="form-row">
                    <label for="description" class="">Select Location</label>
                    <div class="col-md-12">
                        <input id="pac-input" class="controls" type="text" placeholder="Type to search" />
                        <div id="map_canvas" style="height: 254px; width:100%;"></div>
                    </div>
                </div>

                <div class="position-relative form-group mt-lg-3">
                    <label for="description" class="">Audio Description</label>
                    <textarea name="description" id="description" rows="4" class="form-control">{{ $data['description'] }}</textarea>
                    <div class="validation-div" id="val-description"></div>
                </div>

                <table class="col-lg-12 table-data" id="audio-table">
                    <thead>
                        <tr>
                            <th>Languages</th>
                            <th style="padding-left: 15px;">Audio</th>
                            <th>Audio Status</th>
                        </tr>    
                    </thead>
                    <tbody>
                        @foreach ($audioDataMapped as $language => $audioData)
                        {{-- @dd($audioData); --}}
                        <tr>
                            <td>
                                <input type="text" value="{{ $language }}" class="form-control" disabled
                                    name="language[]">
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <input type="file" name="audio_files[]" class="form-control-file audios ml-lg-3">
                                    @if ($audioData['file_path'])
                                        <audio controls class="ml-3">
                                            <source src="{{ asset($audioData['file_path']) }}" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <label class="toggle">
                                    <input type="checkbox" name="audio_status[]" value="active"
                                        {{ $audioData['audio_status'] === 'active' ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                                <input type="hidden" name="audio_status_hidden[]" value="inactive">
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <input type="hidden" name="audio_data" value="{{ json_encode($audioDataMapped) }}">

                <div class="form-row mt-lg-3">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="active" @if ($data['status'] == 'active') selected @endif>
                                    {{ trans('common.active') }}</option>
                                <option value="inactive" @if ($data['status'] == 'inactive') selected @endif>
                                    {{ trans('common.inactive') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Want to show icon on map?</label>
                            <select class="form-control" id="show_icon" name="show_icon">
                                <option value="yes" @if ($data['show_icon'] == 'yes') selected @endif>
                                    {{ trans('common.yes') }}</option>
                                <option value="no" @if ($data['show_icon'] == 'no') selected @endif>
                                    {{ trans('common.no') }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 mt-4 ml-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_in_queue"
                                @if (isset($data) && $data['is_in_queue'] == 'yes') {{ 'checked' }} @endif>
                            <label class="form-check-label" for="is_in_queue">
                                Do you want to add this trigger point in queue
                            </label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ Settings::get('google_map_api_key') }}&callback=initMap&v=3.exp&sensor=false&libraries=places"
        async></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <!-- CONTENT OVER -->
@endsection

@section('js')
    <script>
        async function runAjax(url, formData) {
            const token = $('meta[name="csrf-token"]').attr('content');
            formData.append('_token', token);

            try {
                const response = await $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                });
                return response;
            } catch (error) {
                console.error("AJAX Error:", error);
                return null;
            }
        }


        var map;
        var markerStore = {};
        var selectedMarker = null;
        let existingLat = parseFloat(document.getElementById("latitude").value);
        let existingLong = parseFloat(document.getElementById("longitude").value);

        if (isNaN(existingLat) || isNaN(existingLong)) {
            existingLat = 0;
            existingLong = 0;
        }

        window.onload = async function InitializeMap() {
            var myOptions = {
                zoom: 18,
                // center: new google.maps.LatLng(existingLat, existingLong),
                center: {lat: existingLat, lng: existingLong },
                mapTypeId: google.maps.MapTypeId.ROADMAP,
            };

            map = new google.maps.Map(document.getElementById("map"), myOptions);

            var triggerPoints = @json($triggerPoints);

            // Define icon styles
            const defaultSymbol = {
                path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                scale: 4,
                strokeColor: '#FF0000', // gold
                strokeWeight: 2
            };

            const highlightedSymbol = {
                path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                scale: 4,
                strokeColor: '#000000', // blue
                strokeWeight: 2
            };


             // 🎯 Draw route line
            var tourRoutePoints = [];
            var routeData = new FormData();
            routeData.append('type', 'tour');

            var routeResponse = await runAjax(SITE_URL + '/roots', routeData);
            const parsedResponse = JSON.parse(routeResponse); // 👈 parse the string into a real object
            console.log('parsedResponse:', parsedResponse);
            
            if (parsedResponse.status == '200' && parsedResponse.data.length > 0) {
                $.each(parsedResponse.data, function(index, value) {
                    tourRoutePoints.push(new google.maps.LatLng(value.latitude, value.longitude));
                });

                var polylineOptions = {
                    path: tourRoutePoints,
                    strokeColor: "#ff0000",
                    strokeWeight: 5
                };
                var tourPolyline = new google.maps.Polyline(polylineOptions);
                tourPolyline.setMap(map);
            }
            
            var id = $('#id').val();
            triggerPoints.forEach(function(point) {
                var position = new google.maps.LatLng(point.latitude, point.longitude);

                var marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    title: point.title || "No description",
                    icon: {
                        path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                        scale: 4,
                        rotation: parseFloat(point.angle) || 0,
                        strokeColor: (point.id == id) ? '#000000' : '#FF0000',
                    }
                });

                if (point.id == id) {
                    selectedMarker = marker;
                    marker.customData = point; // also set customData so it's editable
                }


                var infoWindow = new google.maps.InfoWindow({
                    content: point.title || "No description",
                });

                marker.addListener("click", function() {
                    // Reset previous marker
                    if (selectedMarker && selectedMarker !== marker) {
                        const prevData = selectedMarker.customData;
                        selectedMarker.setIcon({
                            ...defaultSymbol,
                            rotation: parseFloat(prevData.angle) || 0
                        });
                    }

                    // Fetch all data from marker
                    const data = point;
                    console.log("Marker clicked:", data);

                    // Use current angle or default to 0
                    let angle = parseFloat(data.angle) || 0;

                    // Apply rotation with highlighted style
                    marker.setIcon({
                        ...highlightedSymbol,
                        rotation: angle
                    });

                    marker.customData = data; // store for later
                    selectedMarker = marker;

                    // Fill in the form fields
                    $('#id').val(data.id);
                    $('#title').val(data.title);
                    $('#priority').val(data.priority);
                    $('#angle').val(data.angle);
                    $('#tolerance').val(data.tolerance);
                    $('#proximity').val(data.proximity);
                    $('#latitude').val(data.latitude);
                    $('#longitude').val(data.longitude);

                    // Set the image preview if image exists
                    if (data.image) {
                        // $('#image-src').attr('src', data.image).show();
                        const imageUrl = data.image ? `${window.location.origin}/${data.image.replace(/^\/+/, '')}` : '';
                        $('#image-src').attr('src', imageUrl).show();
                        $('.parent_remove_btn').attr('data-id', data.id).show();
                    } else {
                        $('#image-src').attr('src', '').hide();
                        $('.parent_remove_btn').hide();
                    }
                    $('#description').val(data.description);

                    // Set dropdown selections
                    $('#status').val(data.status);
                    $('#show_icon').val(data.show_icon);

                    // Set checkbox (queue)
                    $('#is_in_queue').prop('checked', data.is_in_queue === 'yes');

                    // Update language audio table dynamically (optional: wipe existing table first)
                    const audioTable = $('.table-data');
                    audioTable.find("tr:gt(0)").remove(); // remove old rows except the header
                    // console.log(data.file_path.length > 0);
                    
                    let $tbody = $('#audio-table tbody');
                    $tbody.empty(); // Clear old rows

                    const rawFilePath = data.file_path;

                    // Active languages from the backend (e.g., Language::where('status', 'active')->pluck('language'))
                    const activeLanguages = @json($getLanguage->pluck('language'));

                    let filePathArray = [];

                    // Safely parse rawFilePath
                    if (typeof rawFilePath === 'string') {
                        try {
                            filePathArray = JSON.parse(rawFilePath);
                        } catch (error) {
                            console.error("Error parsing file_path JSON:", error);
                            filePathArray = [];
                        }
                    } else if (Array.isArray(rawFilePath)) {
                        filePathArray = rawFilePath;
                    }

                    // Create a mapping of language => audio data
                    const audioDataMapped = {};

                    filePathArray.forEach(audioData => {
                        const lang = audioData.languages;
                        if (lang) {
                            audioDataMapped[lang] = {
                                file_path: audioData.file_path || '',
                                audio_status: audioData.audio_status || 'inactive'
                            };
                        }
                    });

                    // Ensure all active languages are included, even if not in filePathArray
                    activeLanguages.forEach(lang => {
                        if (!audioDataMapped[lang]) {
                            audioDataMapped[lang] = {
                                file_path: '',
                                audio_status: 'inactive'
                            };
                        }
                    });

                    // Now render to DOM
                    Object.entries(audioDataMapped).forEach(([language, audioData]) => {
                        const isChecked = audioData.audio_status === 'active' ? 'checked' : '';
                        const audioSrc = audioData.file_path
                            ? `<audio controls class="ml-3">
                                    <source src="${window.location.origin}/${audioData.file_path.replace(/^\/+/, '')}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                            </audio>`
                            : '';

                        const newRow = `
                            <tr>
                                <td>
                                    <input type="text" value="${language}" class="form-control" disabled name="language[]">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <input type="file" name="audio_files[]" class="form-control-file audios ml-lg-3">
                                        ${audioSrc}
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle">
                                        <input type="checkbox" name="audio_status[]" value="active" ${isChecked}>
                                        <span class="slider"></span>
                                    </label>
                                    <input type="hidden" name="audio_status_hidden[]" value="inactive">
                                </td>
                            </tr>
                        `;
                        $tbody.append(newRow);
                    });

                    // Optional: Store raw file data
                    $('input[name="audio_data"]').val(JSON.stringify(filePathArray)); 

                    $('#latitude').trigger('change'); 
                });
                markerStore[point.id] = marker;
            });

            $('#angle').on('change', function () {
                if (!selectedMarker) return;

                let newAngle = parseFloat($(this).val()) || 0;
                let currentIcon = selectedMarker.getIcon();

                // Update the icon with new rotation
                selectedMarker.setIcon({
                    ...currentIcon,
                    rotation: newAngle
                });

                // Also update the internal stored data
                if (selectedMarker.customData) {
                    selectedMarker.customData.angle = newAngle;
                }
            });

            $('#latitude, #longitude').on('change', function () {
                if (!selectedMarker) return;

                let lat = parseFloat($('#latitude').val());
                let lng = parseFloat($('#longitude').val());

                if (isNaN(lat) || isNaN(lng)) return; // invalid

                let newPosition = new google.maps.LatLng(lat, lng);

                selectedMarker.setPosition(newPosition);

                if (selectedMarker.customData) {
                    selectedMarker.customData.latitude = lat;
                    selectedMarker.customData.longitude = lng;
                }

                // Optional: center the map
                // map.panTo(newPosition);
            });
        };
        
    </script>

    <script>
        $(document).ready(function() {

            // Select all audio elements
            let audioElements = document.querySelectorAll('audio');

            // Function to pause all audios except the one that's currently playing
            function pauseOtherAudios(currentAudio) {
                audioElements.forEach(audio => {
                    if (audio !== currentAudio) {
                        audio.pause();
                    }
                });
            }

            // Add event listeners to each audio element
            audioElements.forEach(audio => {
                // When an audio element starts playing, pause all others
                audio.addEventListener('play', function() {
                    pauseOtherAudios(audio);
                });
            });

            $('#toggleButton').change(function() {
                if ($(this).is(':checked')) {
                    // Do something when checked
                    console.log('Checked');
                } else {
                    // Do something when unchecked
                    console.log('Unchecked');
                }
            });

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

                },
                messages: {
                    audio: {
                        required: "The audio field is required",
                    },
                },
            });

            if ($('#image-src').attr('src') == '') {
                $('.parent_remove_btn').hide();
            }

            // initMap(39.94996, -75.14886, 1);

            let map;
            let marker;
            let userPositionMarker;

            function initMap() {
                // Default latitude and longitude
                const defaultLat = parseFloat($('#latitude').val());
                const defaultLng = parseFloat($('#longitude').val());

                console.log('defaultLat',defaultLat);
                console.log('defaultLng',defaultLng);
                

                if (isNaN(defaultLat) || isNaN(defaultLng)) {
                    console.error('Invalid coordinates:', defaultLat, defaultLng);
                    // Set default coordinates if values are invalid
                    defaultLat = 0;
                    defaultLng = 0;
                }

                // Initialize the map
                map = new google.maps.Map(document.getElementById('map_canvas'), {
                    center: {
                        lat: defaultLat,
                        lng: defaultLng
                    },
                    zoom: 18
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
                $('#latitude, #longitude').on('change', function() {
                    updateMarker();
                });

                // Update the inputs when marker is dragged
                marker.addListener('dragend', function() {
                    let position = marker.getPosition();
                    $('#latitude').val(position.lat());
                    $('#longitude').val(position.lng());
                    $('#latitude').trigger('change'); 
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

                        let location = place.geometry.location;
                        let position = {
                            lat: parseFloat(location.lat()),
                            lng: parseFloat(location.lng())
                        };

                        marker.setPosition(position);
                        map.setCenter(position);
                        map.setZoom(15);

                        $('#latitude').val(position.lat);
                        $('#longitude').val(position.lng);

                        if (place.geometry.viewport) {
                            bounds.union(place.geometry.viewport);
                        } else {
                            bounds.extend(location);
                        }
                    });
                    map.fitBounds(bounds);
                });

                // Geolocation to track user position
                if (navigator.geolocation) {
                    navigator.geolocation.watchPosition(
                        function(position) {
                            let userLatLng = {
                                lat: parseFloat(position.coords.latitude),
                                lng: parseFloat(position.coords.longitude)
                            };

                            // Validate coordinates
                            if (isNaN(userLatLng.lat) || isNaN(userLatLng.lng) || !isFinite(userLatLng.lat) || !
                                isFinite(userLatLng.lng)) {
                                console.error('Invalid coordinates:', userLatLng.lat, userLatLng.lng);
                                userLatLng.lat = 0; // default latitude
                                userLatLng.lng = 0; // default longitude
                            }

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
                console.log('lat',lat);
                console.log('lng',lng);
                
                if (!isNaN(lat) && !isNaN(lng)) {
                    let position = {
                        lat: lat,
                        lng: lng
                    };
                    marker.setPosition(position);
                    map.setCenter(position);
                }
            }

            // Initialize the map when the window loads
            google.maps.event.addDomListener(window, 'load', initMap);

            $("#image").change(function() {
                readURL2(this);
            });

            // $('#latitude').prop('readonly', true);
            // $('#longitude').prop('readonly', true);

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
            var page_id = $('#page_id').val();
            var isInQueue = $('#is_in_queue').is(':checked') ? 'yes' : 'no';
            data.append('id', id);
            data.append('page_id', page_id);
            data.append('title', $('#title').val());
            data.append('latitude', $('#latitude').val());
            data.append('longitude', $('#longitude').val());
            data.append('status', $('#status').val());
            data.append('is_in_queue', isInQueue);
            data.append('description', $('#description').val());
            data.append('image', $('#image')[0].files[0]);
            data.append('show_icon', $('#show_icon').val());
            data.append('priority', $('#priority').val());
            data.append('proximity', $('#proximity').val());
            data.append('angle', $('#angle').val());
            data.append('tolerance', $('#tolerance').val());

            var routeUrl = '{{ route('update-audio') }}';

            var all_data = [];

            $('table.table-data tr').each(function() {
                var languageInput = $(this).find('input[name^="language"]');
                var audioFileInput = $(this).find('input[name^="audio"]')[0];
                var statusInput = $(this).find('input[name^="audio_status"]').is(':checked') ? 'active' :
                    'inactive';

                if (languageInput.length && audioFileInput.files.length > 0) {
                    var language = languageInput.val();
                    var audioFile = audioFileInput.files[0];
                    data.append('audio_files[]', audioFile); // Append audio file

                    all_data.push({
                        languages: language,
                        audio: audioFile.name, // Only append the name here
                        audio_status: statusInput
                    });
                }
            });

            data.append('audio_data', JSON.stringify(all_data)); // Append the JSON string
            // Make the AJAX call
            var response = adminAjax(routeUrl, data);

            if (response.status == '200') {
                swal.fire({
                    type: 'success',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 3000
                });
                // window.location.href = "/backend/audio/" + page_id;
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

        $('.remove_image_btn').click(() => {
            if (confirm("Are you sure, you want to delete this image")) {
                var data = new FormData();
                data.append('id', $('.parent_remove_btn').data('id'));
                var response = adminAjax('{{ route('audio.remove_image') }}', data);
                if (response.status == '200') {
                    swal.fire({
                        type: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('.parent_remove_btn').hide();
                    $("#image-src").attr("src", "")
                }
            }
        });
    </script>
@endsection
