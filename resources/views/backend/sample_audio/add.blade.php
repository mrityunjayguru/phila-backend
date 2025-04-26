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
                    <span class="d-inline-block">Add Sample Audio </span>
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            <div class="page-title-subheading opacity-10">
                <nav class="" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i aria-hidden="true"
                                    class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('sample-audio') }}">Sample Audio</a></li>
                        <li class="active breadcrumb-item" aria-current="page">Add Sample</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- CONTENT START -->
<div class="main-card mb-3 card">
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data" id="sampleaudio" onsubmit="saveData()" method="POST"
            action="javascript:void(0);">
            @csrf
            <div class="form-row">
                <div class="col-md-6">
                    <div class="position-relative form-group">
                        <label for="language">Language</label>
                        <select name="language" class="form-control languages" id="language">
                            @foreach($getLanguage as $language)
                            <option value="{{ $language->tag }}" id="languages">{{ $language->language }}
                                ({{ $language->tag }})
                            </option>
                            @endforeach
                        </select>
                        <div class="validation-div" id="val-language"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="position-relative form-group">
                        <label for="audio">Audio</label>
                        <input type="file" id="audio" class="form-control-file" name="audio">
                        <!-- <div class="validation-div" id="val-audio"></div> -->
                    </div>
                </div>
            </div>

            <div class="form-row mt-lg-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="active">{{ trans('common.active') }}</option>
                            <option value="inactive">{{ trans('common.inactive') }}</option>
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

    // Validate the form
    $("#sampleaudio").validate({
        rules: {
            audio: {
                required: true,
                mp3: true // Use the custom rule
            },
        },
        messages: {
            audio: {
                required: true,
                mp3: true // Use the custom rule
            },
        },
    });
});

// CREATE
function saveData() {
    var data = new FormData();
    data.append('language', $('#language').val());
    data.append('status', $('#status').val());
    data.append('audio', $('#audio')[0].files[0]);
    var response = adminAjax('{{route("sample-audio-store")}}', data);

    if (response.status == '200') {
        swal.fire({
            type: 'success',
            title: response.message,
            showConfirmButton: false,
            timer: 3000
        });
        window.location.href = "{{route('sample-audio')}}";
    } else if (response.status == '422') {
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