@extends('layouts.backend.master')
@section('css')
@endsection  
@section('content')
	<style>
	#map_canvas {width: 980px;height: 500px;}
	#current {padding-top: 25px;}
	.image-preview{}
	.parent_remove_btn{cursor: pointer;}
	.parent_remove_btn-2{cursor: pointer;}
	#pac-input{position: absolute;top: 10px!important; right: 60px!important; height: 28px; z-index: 9999;}
	</style>
	<div class="app-page-title app-page-title-simple">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div>
					<div class="page-title-head center-elem">
						<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
						<span class="d-inline-block">Custom Map</span>
					</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="page-title-subheading opacity-10">
					<nav class="" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
							<li class="active breadcrumb-item" aria-current="page">Custom Map</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>

	<!-- CONTENT START -->
	<div class="main-card mb-3 card">
		<div class="card-body">
			<form class="" action="javascript:void(0);" onsubmit="saveData()"  enctype="multipart/form-data">
				<div class="form-row">
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="exampleFile" class="">Image</label>
							<input name="file" id="image" type="file" class="form-control-file item-img" accept="image/*">
							<div class="validation-div" id="val-image"></div>
							<div class="image-preview">
								<button type="button" class="parent_remove_btn" data-id="{{$data->id}}" ><i aria-hidden="true" class="fa fa-remove remove_image_btn"></i></button>
								<img id="image-src" src="@if($data->image) {{ asset($data->image) }} @endif" width="50%" />
							</div>
						</div>
					</div>
				</div>
				<div class="form-row">
					<button class="mt-2 btn btn-primary">{{ trans('common.submit') }}</button>
				</div>
			</form>
		</div>
	</div>
	<!-- CONTENT OVER -->
@endsection

@section('js')
<script>
	
	$("#image").change(function() {
        readURL2(this);
    });
	
	$("#stop_image").change(function() {
        readURL3(this);
    });
	
	if($('#image-src').attr('src') == ''){
		$('.parent_remove_btn').hide();
	}
	if($('#image-src-2').attr('src') == ''){
		$('.parent_remove_btn-2').hide();
	}
	
	// Remove Image
	$('.remove_image_btn').click( () => {
		if(confirm("Are you sure, you want to delete this image")){
			var data = new FormData();
			data.append('id', $('.parent_remove_btn').data('id'));
			var response = adminAjax('{{route("ajax.custom-map.remove-image")}}', data);
			if (response.status == '200') {
				swal.fire({type: 'success',title: response.message,showConfirmButton: false,timer: 1500});
				$('.parent_remove_btn').hide();
				$("#image-src").attr("src","")
			}
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

	// CREATE
	function saveData(){
		var data = new FormData();
		data.append('image', $('#image')[0].files[0]);
		var response = adminAjax('{{route("custom-map.store")}}', data);
		if(response.status == '200'){
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
			setTimeout(function(){ window.location.reload(); }, 2000);
			
		}else if(response.status == '422'){
			$('.validation-div').text('');
			$.each(response.error, function( index, value ) {
				$('#val-'+index).text(value);
			});
			
		} else if(response.status == '201'){
			swal.fire({title: response.message,type: 'error'});
		}
	}
</script>
@endsection