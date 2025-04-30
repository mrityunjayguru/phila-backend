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
                        <span class="d-inline-block">Add Audio </span>
                    </div>
                </div>
            </div>
            <div class="page-title-actions">
                <div class="page-title-subheading opacity-10">
                    <nav class="" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i aria-hidden="true"
                                        class="fa fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('audio',$page_id) }}">Audio</a></li>
                            <li class="active breadcrumb-item" aria-current="page">Add New</li>
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
                <input type="hidden" name="page_id" id="page_id" value="{{$page_id}}">
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="title">Title</label>
                            <input type="text" id="title" name="title" placeholder="Enter Title"
                                class="form-control">
                            <div class="validation-div" id="val-title"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="sequence" class="">Sequence</label>
                            <input type="number" id="priority" placeholder="0 - 99" class="form-control" name="priority">
                            @error('sequence')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="validation-div" id="val-priority"></div>
                        </div>

                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="angle">Angle (degrees)</label>
                            <input type="number" id="angle" name="angle" placeholder="Enter Angle"
                                class="form-control">
                            <div class="validation-div" id="val-angle"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <div class="position-relative form-group">
                                <label for="tolerance">Tolerance</label>
                                <input type="number" id="tolerance" name="tolerance" placeholder="Enter Tolerance"
                                    class="form-control">
                                <div class="validation-div" id="val-tolerance"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="proximity">Proximity(In Feet)</label>
                            <input type="number" id="proximity" name="proximity" placeholder="Enter Proximity"
                                class="form-control">
                            <div class="validation-div" id="val-proximity"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="latitude">Latitude</label>
                            <input type="text" id="latitude" name="latitude" placeholder="Enter Latitude"
                                class="form-control">
                            <div class="validation-div" id="val-latitude"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="longitude">Longitude</label>
                            <input type="text" id="longitude" name="longitude" placeholder="Enter Longitude"
                                class="form-control">
                            <div class="validation-div" id="val-longitude"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="exampleFile" class="">Image</label>
                            <input name="image" id="image" type="file" class="form-control-file item-img"
                                accept="image/*">
                            <div class="validation-div" id="val-image"></div>
                            <div class="image-preview"><img id="image-src" src="" width="100%" /></div>
                            <input type="hidden" id="img-blob">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <label for="location" class="">Select Location</label>
                    <div class="col-md-12">
                        <input id="pac-input" class="controls" type="text" placeholder="Type to search" />
                        <div id="map_canvas" style="height: 254px; width:100%;"></div>
                    </div>
                </div>

                <div class="position-relative form-group mt-lg-3">
                    <label for="description" class="">Audio Description</label>
                    <textarea name="description" id="description" rows="4" class="form-control"></textarea>
                    <div class="validation-div" id="val-description"></div>
                </div>

                <!-- <div id="dynamicFieldsContainer">
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="language">Language</label>
                                    <select name="language[0]" class="form-control languages" id="language">
                                        @foreach ($getLanguage as $language)
                                            <option value="{{ $language->tag }}" id="languages">
                                                {{ $language->language }}({{ $language->tag }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="validation-div" id="val-language"></div>
                                    <div id="selectedLanguages"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="audio">Audio File</label>
                                    <input type="file" name="audio" class="form-control-file audios" id="audio">
                                    <div class="validation-div" id="val-audio"></div>
                                </div>
                            </div>
                        </div>
                    </div> -->

                <table class="col-lg-12 table-data">
                    <tr>
                        <th>Languages</th>
                        <th style="padding-left: 15px;">Audio</th>
                        <th>Audio Status</th>
                    </tr>
                    <tr>
                        <td><input type="text" value="English" class="form-control" disabled
                                name="language[english]">
                        </td>
                        <td><input type="file" name="audio[0]" class="form-control-file audios ml-lg-3"
                                id="audio">
                        </td>
                        <td>
                            <label class="toggle">
                                <input type="checkbox" id="english" class="status_english" name="status[english]"
                                    checked>
                                <span class="slider"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="text" value="French" class="form-control" name="language[Francesa]"
                                disabled>
                        </td>
                        <td><input type="file" name="audio[3]" class="form-control-file audios ml-lg-3"
                                id=" audio">
                        </td>
                        <td>
                            <label class="toggle">
                                <input type="checkbox" id="german" class="status_french" name="status[Francesa]"
                                    checked>
                                <span class="slider"></span>
                            </label>
                        </td>
                    </tr>

                    <tr>
                        <td><input type="text" value="German" class="form-control" disabled name="language[german]">
                        </td>
                        <td><input type="file" name="audio[1]" class="form-control-file audios ml-lg-3"
                                id=" audio">
                        </td>
                        <td>
                            <label class="toggle">
                                <input type="checkbox" id="german" class="status_german" name="status[Deutsch]"
                                    checked>
                                <span class="slider"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="text" value="Chinese" name="language[中国人]" class="form-control" disabled>
                        </td>
                        <td><input type="file" name="audio[9]" class="form-control-file audios ml-lg-3"
                                id=" audio"></td>
                        <td>
                            <label class="toggle">
                                <input type="checkbox" id="german" class="status_chienese" name="status[中国人]"
                                    checked>
                                <span class="slider"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="text" value="Spanish" class="form-control" name="language[Español]"
                                disabled></td>
                        <td><input type="file" name="audio[2]" class="form-control-file audios ml-lg-3"
                                id=" audio" checked>
                        </td>
                        <td>
                            <label class="toggle">
                                <input type="checkbox" id="german" class="status_spanish" name="status[Español]"
                                    checked>
                                <span class="slider"></span>
                            </label>
                        </td>
                    </tr>

                    <tr>
                        <td><input type="text" value="Japanese" name="language[日本語]" class="form-control " disabled>
                        </td>
                        <td><input type="file" name="audio[4]" class="form-control-file audios ml-lg-3"
                                id=" audio"></td>
                        <td>
                            <label class="toggle">
                                <input type="checkbox" id="german" class="status_japanese" name="status[日本語]"
                                    checked>
                                <span class="slider"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="text" value="Korean" name="language[한국인]" class="form-control" disabled>
                        </td>
                        <td><input type="file" name="audio[5]" class="form-control-file audios ml-lg-3"
                                id=" audio"></td>
                        <td>
                            <label class="toggle">
                                <input type="checkbox" id="german" class="status_korean" name="status[한국인]" checked>
                                <span class="slider"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="text" value="Portuguese" name="language[Portuguese]"
                                class="form-control mt-lg-3" disabled></td>
                        <td><input type="file" name="audio[10]" class="form-control-file audios ml-lg-3"
                                id=" audio"></td>
                        <td>
                            <label class="toggle">
                                <input type="checkbox" id="german" class="status_protugese" name="status[Portuguese]"
                                    checked>
                                <span class="slider"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="text" value="Hindi" name="language[हिंदी]" class="form-control" disabled>
                        </td>
                        <td><input type="file" name="audio[7]" class="form-control-file audios ml-lg-3"
                                id=" audio">
                        </td>
                        <td>
                            <label class="toggle">
                                <input type="checkbox" id="german" class="status_hindi" checked name="status[हिंदी]">
                                <span class="slider"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="text" value="Italian" name="language[Italian]" class="form-control"
                                disabled></td>
                        <td><input type="file" name="audio[6]" class="form-control-file audios ml-lg-3"
                                id=" audio">
                        </td>
                        <td>
                            <label class="toggle">
                                <input type="checkbox" id="german" class="status_italian" name="status[Italian]"
                                    checked>
                                <span class="slider"></span>
                            </label>
                        </td>
                    </tr>

                    <tr>
                        <td><input type="text" value="Russian" name="language[Русский]" class="form-control "
                                disabled>
                        </td>
                        <td><input type="file" name="audio[8]" class="form-control-file audios ml-lg-3"
                                id=" audio">
                        </td>
                        <td>
                            <label class="toggle">
                                <input type="checkbox" id="german" class="status_russian" name="status[Русский]"
                                    checked>
                                <span class="slider"></span>
                            </label>
                        </td>
                    </tr>


                    <tr>
                        <td> <input type="text" value="Vietnamese" name="language[Vietnamese]"
                                class="form-control mt-lg-3" disabled></td>
                        <td><input type="file" name="audio[11]" class="form-control-file audios ml-lg-3"
                                id=" audio"></td>
                        <td>
                            <label class="toggle">
                                <input type="checkbox" id="german" class="status_vietnamm" name="status[Vietnamese]"
                                    checked>
                                <span class="slider"></span>
                            </label>
                        </td>
                    </tr>
                </table>

                <!-- Add the "+" button to add new fields -->
                <!-- <div class="form-row">
                                                <div class="col-md-2">
                                                    <button type="button" id="addFields" class="btn btn-primary">+ Add More</button>
                                                </div>
                                            </div> -->

                <div class="form-row mt-lg-2">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="active">{{ trans('common.active') }}</option>
                                <option value="inactive">{{ trans('common.inactive') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Want to show icon on map?</label>
                            <select class="form-control" id="show_icon" name="show_icon">
                                <option value="yes">{{ trans('common.yes') }}</option>
                                <option value="no">{{ trans('common.no') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 mt-4 ml-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_in_queue">
                            <label class="form-check-label" for="is_in_queue">
                                Do you want to add this trigger point in queue
                            </label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="mt-2 btn btn-primary">{{ trans('common.submit') }}</button>
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
        $(document).ready(function() {
            
            $('#toggleButton').change(function() {
                if ($(this).is(':checked')) {
                    // Do something when checked
                    console.log('Checked');
                } else {
                    // Do something when unchecked
                    console.log('Unchecked');
                }
            });
            // debugger

            var selectedLanguages = [];



            let fieldIndex = 1; // Initialize an index for unique IDs



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

            // $.validator.addMethod("validImage", function(value, element) {
            //     // Allowed file extensions
            //     var validExtensions = ["jpg", "jpeg", "png"];
            //     // Get the file extension
            //     var fileExtension = value.split('.').pop().toLowerCase();
            //     // Check if the file extension is in the allowed list
            //     return $.inArray(fileExtension, validExtensions) !== -1;
            // }, "Please upload a valid image file (jpg, jpeg, png).");

            // Validate the form
            // $("#audioForm").validate({
            //     rules: {
            //         latitude: "required",
            //         longitude: "required",
            //         description: "required",
            //         // language: "required",
            //         audio: {
            //             required: true,
            //             mp3: true // Use the custom rule
            //         },
            //         image: {
            //             required: true,
            //             validImage: true,
            //         },
            //     },
            //     messages: {
            //         latitude: "The latitude field is required",
            //         longitude: "The longitude field is required",
            //         description: "The description field is required",
            //         // language: "Please select at least one language",
            //         audio: {
            //             required: "The audio field is required",
            //         },
            //         image: {
            //             required: "The image field is required",
            //         },
            //     },
            // });

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

                            // checkProximity(userLatLng, marker.getPosition());
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

            // function checkProximity(userLatLng, markerLatLng) {
            //     const distance = google.maps.geometry.spherical.computeDistanceBetween(
            //         new google.maps.LatLng(userLatLng.lat, userLatLng.lng),
            //         new google.maps.LatLng(markerLatLng.lat(), markerLatLng.lng())
            //     );

            //     if (distance <= radius) {
            //         document.getElementById('audio').play();
            //     } else {
            //         document.getElementById('audio').pause();
            //     }
            // }
            initMap();

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
            var page_id = $('#page_id').val();
            var data = new FormData();
            data.append('page_id', page_id);
            data.append('title', $('#title').val());
            data.append('latitude', $('#latitude').val());
            data.append('longitude', $('#longitude').val());
            data.append('status', $('#status').val());
            data.append('is_in_queue', $('#is_in_queue').prop('checked') ? 'yes' : 'no');
            data.append('description', $('#description').val());
            data.append('image', $('#image')[0].files[0]);
            data.append('show_icon', $('#show_icon').val());
            data.append('priority', $('#priority').val());
            data.append('proximity', $('#proximity').val());
            data.append('angle', $('#angle').val() || 0); // Default to 0 if not set
            data.append('tolerance', $('#tolerance').val() || 0); // Default to 0 if not set

            var all_data = [];

            data.append('_token', $('meta[name="csrf-token"]').attr('content'));

            $('table.table-data tr').each(function() {
                var languageInput = $(this).find('input[name^="language"]');
                var audioFileInput = $(this).find('input[name^="audio"]')[0];
                var statusInput = $(this).find('input[name^="status"]').is(':checked') ? 'active' : 'inactive';

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
            var response = adminAjax('{{ route('audio-store') }}', data);

            if (response.status == '200') {
                swal.fire({
                    type: 'success',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 3000
                });
                window.location.href = "/backend/audio/" + page_id;
            } else if (response.error) {
                $('.validation-div').text('');
                $.each(response.error, function(index, value) {
                    $('#val-' + index).text(value);
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
