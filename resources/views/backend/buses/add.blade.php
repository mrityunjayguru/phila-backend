@extends('layouts.backend.master')
@section('css')
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

#pac-input {
    position: absolute;
    top: 10px !important;
    right: 60px !important;
    height: 28px;
    z-index: 9999;
}
</style>

<script src="{{asset('adminAssets/scripts/custom/google_map_api.js')}}"></script>
<div class="app-page-title app-page-title-simple">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div>
                <div class="page-title-head center-elem">
                    <span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
                    <span class="d-inline-block">Add New</span>
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            <div class="page-title-subheading opacity-10">
                <nav class="" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i aria-hidden="true"
                                    class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"> <a href="{{ route('buses.index') }}">Vehicles</a></li>
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
        <form class="" action="javascript:void(0);" onsubmit="saveData()" enctype="multipart/form-data">
            <div class="form-row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Device Type</label>
                        <select id="device_type" class="form-control" minlength="2" maxlength="255">
                            <option value="">Select Type</option>
                            <option value="Bus">Bus</option>
                            <option value="Trolley">Trolley</option>
                            <option value="Trolley-Blue-Route">Trolley-Blue-Route</option>
                            <option value="Shuttle">Shuttle</option>
                        </select>
                        <div class="validation-div" id="val-device_type"></div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="title" class="">Title</label>
                        <input type="text" id="title" placeholder="Enter Title" class="form-control">
                        <div class="validation-div" id="val-title"></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="position-relative form-group">
                        <label for="device_id" class="">Device ID</label>
                        <input type="number" id="device_id" placeholder="Device ID" class="form-control">
                        <div class="validation-div" id="val-device_id"></div>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="device_id" class="">IMEI Number</label>
                        <input type="number" id="imei_number" placeholder="Enter IMEI Number" class="form-control">
                        <div class="validation-div" id="val-imei_number"></div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="audio_available" class="">Audio Available</label>
                        <select class="form-control" id="audio_available" name="audio_available">
                            <option value="yes">Yes</option>
                            <option value="no ">No</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-6">
                    <!--<div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="latitude" class="">Latitude</label>
                                <input type="text" id="latitude" value="39.966" placeholder="Enter Latitude" class="form-control" readonly>
                                <div class="validation-div" id="val-latitude"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="longitude" class="">Longitude</label>
                                <input type="text" id="longitude" value="-75.142" placeholder="Enter Longitude" class="form-control" readonly>
                                <div class="validation-div" id="val-longitude"></div>
                            </div>
                        </div>
                    </div>-->
                    <hr>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-2">
                    <div class="form-group">
                        <select class="form-control" id="status">
                            <option value="active">{{trans('common.active')}}</option>
                            <option value="inactive ">{{trans('common.inactive')}}</option>
                        </select>
                    </div>
                </div>
                
            </div>
            <div class="form-row">
                @if(Settings::UserPermission('bus-create'))
                <button class="mt-2 btn btn-primary">{{ trans('common.submit') }}</button>
                @endif
        </form>
    </div>
</div>
</div>
<!-- CONTENT OVER -->
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCGkz3XsDJbMPCL7T-I8WM6QVEn_Gij8yc&callback=initMap&v=3.exp&sensor=false&libraries=places"
    async></script>
@endsection

@section('js')
<script>
$(document).ready(function(e) {

    initMap('39.966', '-75.142', 1);

    $("#image").change(function() {
        readURL2(this);
    });

    $("#stop_image").change(function() {
        readURL3(this);
    });

    if ($('#image-src').attr('src') == '') {
        $('.parent_remove_btn').hide();
    }

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
    data.append('title', $('#title').val());
    data.append('imei_number', $('#imei_number').val());
    data.append('device_id', $('#device_id').val());
    data.append('device_type', $('#device_type').val());
    data.append('latitude', $('#latitude').val());
    data.append('longitude', $('#longitude').val());
    data.append('status', $('#status').val());
    data.append('audio_available', $('#audio_available').val());
    var response = adminAjax('{{route("buses.store")}}', data);
    if (response.status == '200') {
        swal.fire({
            type: 'success',
            title: response.message,
            showConfirmButton: false,
            timer: 1500
        });
        setTimeout(function() {
            window.location.href = "{{route('buses.index')}}";
        }, 2000)

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