@extends('layouts.backend.master')

@section('content')
	<div class="app-page-title app-page-title-simple">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div>
					<div class="page-title-head center-elem">
						<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
						<span class="d-inline-block">{{ trans('slider.add') }}</span>
					</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="page-title-subheading opacity-10">
					<nav class="" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
							<li class="breadcrumb-item"> <a  href="{{ route('sliders.index') }}">{{ trans('slider.singular') }}</a></li>
							<li class="active breadcrumb-item" aria-current="page">{{ trans('slider.add') }}</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>

	<!-- CONTENT START -->
	<div class="main-card mb-3 card">
		<div class="card-body">
			<form action="javascript:void(0);" onsubmit="saveData()"  enctype="multipart/form-data">
				<div class="form-row">
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="title" class="">{{ trans('slider.title') }}</label>
							<input type="text" id="title" placeholder="Enter Title" class="form-control">
							<div class="validation-div" id="val-title"></div>
						</div>
					</div>
					<div class="col-md-2">
						<label for="device" class="">{{ trans('slider.device') }}</label>
						<select class="form-control" id="device">
							<option value=""> Select Device...</option>
							<option value="Mobile"> Mobile </option>
							<!--<option value="Web"> Web </option>-->
						</select>
						<div class="validation-div" id="val-device"></div>
					</div>
				</div>

				<div class="form-row">
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="slug">{{ trans('slider.slug') }}</label>
							<input type="text" id="slug" placeholder="Enter slug like mobile-home" class="form-control">
							<div class="validation-div" id="val-slug"></div>
						</div>
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
					@if(Settings::UserPermission('slider-create'))
					<button class="mt-2 btn btn-primary">{{ trans('common.submit') }}</button>
					@endif
				</div>
			</form>
		</div>
	</div>
	<!-- CONTENT OVER -->
@endsection

@section('js')
<script>
	// CREATE
	function saveData(){
		var data = new FormData();
		data.append('title', $('#title').val());
		data.append('device', $('#device').val());
		data.append('slug', $('#slug').val());
		data.append('status', $('#status').val());
		var response = adminAjax('{{route("sliders.store")}}', data);
		if(response.status == '200'){
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
			setTimeout(function(){ window.location.href = "{{route('sliders.index')}}"; }, 2000)
			
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