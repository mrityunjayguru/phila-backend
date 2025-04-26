@extends('layouts.backend.master')

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

.parent_remove_btn-2 {
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
                    <span class="d-inline-block">Edit Stop</span>
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            <div class="page-title-subheading opacity-10">
                <nav class="" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i aria-hidden="true"
                                    class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"> <a href="{{ route('stops.index') }}">Stops</a></li>
                        <li class="active breadcrumb-item" aria-current="page">Edit</li>
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
                <div class="col-md-6">
                    <div class="position-relative form-group">
                        <label for="title" class="">Title</label>
                        <input type="text" id="title" placeholder="Enter Title" value="{{$data->title}}"
                            class="form-control">
                        <div class="validation-div" id="val-title"></div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="position-relative form-group">
                        <label for="priority" class="">Priority (Stop No.)</label>
                        <input type="number" id="priority" placeholder="0 - 99" value="{{$data->priority}}"
                            class="form-control">
                        <div class="validation-div" id="val-priority"></div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="position-relative form-group">
                        <label for="type" class="">Type</label>
                        <select name="type" id="type" class="form-control">
                            <option value="">Select Type</option>
                            <option value="tour" @if($data->type == 'tour') selected @endif>Tour Route</option>
                            <option value="fairmount_park_loop" @if($data->type == 'fairmount_park_loop') selected
                                @endif>Fairmount Park Loop</option>
                            <option value="mix" @if($data->type == 'mix') selected @endif>Mix ({{$for_type}})</option>
                        </select>
                        <div class="validation-div" id="val-type"></div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="time" class="">Time</label>
                                <input type="text" id="time" placeholder="Enter Time" value="{{$data->time}}"
                                    class="form-control">
                                <div class="validation-div" id="val-time"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="latitude" class="">Latitude</label>
                                <input type="text" id="latitude" placeholder="Enter Latitude"
                                    value="{{$data->latitude}}" class="form-control">
                                <div class="validation-div" id="val-latitude"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="longitude" class="">Longitude</label>
                                <input type="text" id="longitude" placeholder="Enter Longitude"
                                    value="{{$data->longitude}}" class="form-control">
                                <div class="validation-div" id="val-longitude"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-12">
                            <input id="pac-input" class="controls" type="text" placeholder="Type to search" />
                            <div id="map_canvas" style="height: 254px; width:100%;"></div>
                        </div>
                    </div>

                    <br>
                    <br>
                    <div class="position-relative form-group">
                        <label for="description" class="">Stop Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="form-control">{{$data->description}}</textarea>
                        <div class="validation-div" id="val-description"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="exampleFile" class="">Image</label>
                                <input name="file" id="image" type="file" class="form-control-file item-img"
                                    accept="image/*">
                                <div class="validation-div" id="val-image"></div>
                                <div class="image-preview">
                                    <button type="button" class="parent_remove_btn" data-id="{{$data->id}}"><i
                                            aria-hidden="true" class="fa fa-remove remove_image_btn"></i></button>
                                    <img id="image-src" src="@if($data->image) {{ asset($data->image) }} @endif"
                                        width="50%" />
                                </div>
                                <input type="hidden" id="img-blob">
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="exampleFile" class="">Stop Image</label>
                                <input name="file" id="stop_image" type="file" class="form-control-file item-img"
                                    accept="image/*">
                                <div class="validation-div" id="val-stop_image"></div>
                                <div class="image-preview">
                                    <button type="button" class="parent_remove_btn-2" data-id="{{$data->id}}"><i
                                            aria-hidden="true" class="fa fa-remove remove_image_btn-2"></i></button>
                                    <img id="image-src-2"
                                        src="@if($data->stop_image) {{ asset($data->stop_image) }} @endif"
                                        width="50%" />
                                </div>
                                <input type="hidden" id="img-blob">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="form-row">
                <div class="col-md-2">
                    <div class="form-group @error('status') ? has-error : ''  @enderror">
                        {{Form::label('status', trans('common.status'),['class' => 'content-label'])}}<br>
                        <select class="form-control" minlength="2" maxlength="255" id="status" name="status">
                            <option value="active" @if($data->status == 'active') selected @endif>
                                {{trans('common.active')}}
                            </option>
                            <option value="inactive" @if($data->status == 'inactive') selected @endif>
                                {{trans('common.inactive')}}
                            </option>
                        </select>
                        @if ($errors->has('status'))
                        <strong class="help-block">{{ $errors->first('status') }}</strong>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-row">
                @if(Settings::UserPermission('stop-edit'))
                <button class="mt-2 btn btn-primary">{{ trans('common.submit') }}</button>
                @endif
        </form>
    </div>
</div>
<!-- CONTENT OVER -->
<script
    src="https://maps.googleapis.com/maps/api/js?key={{Settings::get('google_map_api_key')}}&callback=initMap&v=3.exp&sensor=false&libraries=places"
    async></script>
@endsection

@section('js')
<script>
$(document).ready(function(e) {

    //initMap('{{$data->latitude}}', '{{$data->longitude}}', 1);
    setTimeout(function() {
        initMap('{{$data->latitude}}', '{{$data->longitude}}', 1);
    }, 2000)

    $("#image").change(function() {
        readURL2(this);
    });

    $("#stop_image").change(function() {
        readURL3(this);
    });

    if ($('#image-src').attr('src') == '') {
        $('.parent_remove_btn').hide();
    }
    if ($('#image-src-2').attr('src') == '') {
        $('.parent_remove_btn-2').hide();
    }
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
    data.append('item_id', '{{$data->id}}');
    data.append('title', $('#title').val());
    data.append('time', $('#time').val());
    data.append('priority', $('#priority').val());
    data.append('latitude', $('#latitude').val());
    data.append('longitude', $('#longitude').val());
    data.append('description', $('#description').val());
    data.append('type', $('#type').val());
    data.append('image', $('#image')[0].files[0]);
    data.append('stop_image', $('#stop_image')[0].files[0]);
    data.append('status', $('#status').val());
    var response = adminAjax('{{route("stops.store")}}', data);
    if (response.status == '200') {
        swal.fire({
            type: 'success',
            title: response.message,
            showConfirmButton: false,
            timer: 1500
        });
        setTimeout(function() {
            window.location.reload();
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

// Remove Image
$('.remove_image_btn').click(() => {
    if (confirm("Are you sure, you want to delete this image")) {
        var data = new FormData();
        data.append('id', $('.parent_remove_btn').data('id'));
        var response = adminAjax('{{route("stops.remove_image")}}', data);
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

// Remove Image
$('.remove_image_btn-2').click(() => {
    if (confirm("Are you sure, you want to delete this image")) {

        var data = new FormData();
        data.append('id', $('.parent_remove_btn-2').data('id'));
        var response = adminAjax('{{route("stop.remove_stop_image")}}', data);
        if (response.status == '200') {
            swal.fire({
                type: 'success',
                title: response.message,
                showConfirmButton: false,
                timer: 1500
            });
            $('.parent_remove_btn-2').hide();
            $("#image-src-2").attr("src", "")
        }
    }
});
</script>
@endsection