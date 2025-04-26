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
                    <span class="d-inline-block">Update Landing Page </span>
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            <div class="page-title-subheading opacity-10">
                <nav class="" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i aria-hidden="true"
                                    class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('landing-page') }}">Landing Page</a></li>
                        <li class="active breadcrumb-item" aria-current="page">Update Landing Page</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- CONTENT START -->
<div class="main-card mb-3 card">
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data" id="savepage" onsubmit="saveData()" method="POST"
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
                        <label for="image" class="">Image</label>
                        <input name="image" id="image" type="file" class="form-control item-img" accept="image/*">
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
                <div class="col-md-6">
                    <label for="route_length" class="">Route Length</label>
                    <input type="text" class="form-control" id="route_length" name="route_length"
                        value="{{$data['route_length']}}" placeholer="Route Length">
                    <div class="validation-div" id="val-length"></div>
                    @error('route_length')
                    <div class="alert alert-daner">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="route_time" class="">Route Time</label>
                    <input type="number" class="form-control" id="route_time" name="route_time"
                        value="{{$data['route_time']}}" placeholer="Route Time">
                    <div class="validation-div" id="val-time"></div>
                    @error('route_time')
                    <div class="alert alert-daner">{{$message}}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row mt-lg-3">
                <div class="col-md-6">
                    <label for="stops" class="">Number Of Stops</label>
                    <input type="number" class="form-control" id="number_of_stops" name="number_of_stops"
                        value="{{$data['number_of_stops']}}" placeholer="Number Of Stops">
                    <div class="validation-div" id="val-stops"></div>
                    @error('number_of_stops')
                    <div class="alert alert-daner">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-md-6">
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
              
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="description" class="">Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="form-control">{{$data['description']}}</textarea>
                        <div class="validation-div" id="val-description"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status">Priority</label>
                        <input type="number" name="priority" id="priority" class="form-control" value="{{$data->priority}}">
                    </div>
                </div>
                <div class="col-md-3 mt-3 mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_code_dependecy" id="is_code_dependecy_checkbox" @if(isset($data) && $data['is_code_dependecy'] == 'yes') {{'checked'}} @endif>
                        <label class="form-check-label" for="is_code_dependecy_checkbox">
                            Code Dependency
                        </label>
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
    $("#image").change(function() {
        readURL2(this);
    });
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
    var isCodeDependency = $('#is_code_dependecy_checkbox').is(':checked') ? 'yes' : 'no';
    data.append('id', id);
    data.append('title', $('#title').val());
    data.append('status', $('#status').val());
    data.append('description', $('#description').val());
    data.append('priority', $('#priority').val());
    data.append('is_code_dependecy', isCodeDependency);
    data.append('route_time', $('#route_time').val());
    data.append('route_length', $('#route_length').val());
    data.append('number_of_stops', $('#number_of_stops').val());
    data.append('image', $('#image')[0].files[0]);
    var response = adminAjax('{{route("update-page")}}', data);

    if (response.status == '200') {
        swal.fire({
            type: 'success',
            title: response.message,
            showConfirmButton: false,
            timer: 3000
        });
        window.location.href = "{{route('landing-page')}}";
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