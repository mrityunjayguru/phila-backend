@extends('layouts.backend.master')
@section('css')
@endsection  
@section('content')
	<div class="app-page-title app-page-title-simple">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div>
					<div class="page-title-head center-elem">
						<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
						<span class="d-inline-block">{{ trans('area.add') }}</span>
					</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="page-title-subheading opacity-10">
					<nav class="" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
							<li class="breadcrumb-item"> <a  href="{{ route('areas.index') }}">{{ trans('area.singular') }}</a></li>
							<li class="active breadcrumb-item" aria-current="page">{{ trans('area.add') }}</li>
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
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="title" class="">{{ trans('area.title') }}</label>
							<input type="text" id="title" placeholder="{{ trans('area.placeholder.title') }}" class="form-control">
							<div class="validation-div" id="val-title"></div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="position-relative form-group">
							<label for="priority" class="">{{ trans('area.priority') }}</label>
							<input type="text" id="priority" placeholder="{{ trans('area.placeholder.enter_priority') }}" class="form-control">
							<div class="validation-div" id="val-priority"></div>
						</div>
					</div>
				</div>
				
				<hr>
				<div class="form-row">
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="pincode">{{ trans('area.pincode') }}</label>
							<input type="text" id="pincode" placeholder="{{ trans('area.placeholder.pincode') }}" class="form-control">
							<div class="validation-div" id="val-pincode"></div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="latitude">{{ trans('area.latitude') }}</label>
							<input type="text" id="latitude" placeholder="{{ trans('area.placeholder.latitude') }}" class="form-control">
							<div class="validation-div" id="val-latitude"></div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="longitude">{{ trans('area.longitude') }}</label>
							<input type="text" id="longitude" placeholder="{{ trans('area.placeholder.longitude') }}" class="form-control">
							<div class="validation-div" id="val-longitude"></div>
						</div>
					</div>
				</div>
				
				<div class="form-row">
					<div class="col-md-2">
		                <div class="form-group @error('status') ? has-error : ''  @enderror">
		                    {{Form::label('status', trans('common.status'),['class' => 'content-label'])}}<br>
		                    <select  class="form-control" minlength="2" maxlength="255" id="status" name="status">
		                      <option value="active">{{trans('common.active')}}</option>
		                      <option value="inactive ">{{trans('common.inactive')}}</option>
		                    </select>
		                     @if ($errors->has('status')) 
		                    <strong class="help-block">{{ $errors->first('status') }}</strong>
		                  @endif
		                </div>
      				</div>
    			</div>
				<div class="form-row">
				<button class="mt-2 btn btn-primary">{{ trans('common.submit') }}</button>
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
		data.append('priority', $('#priority').val());
		data.append('pincode', $('#pincode').val());
		data.append('latitude', $('#latitude').val());
		data.append('longitude', $('#longitude').val());
		data.append('status', $('#status').val());
		var response = adminAjax('{{route("areas.store")}}', data);
		if(response.status == '200'){
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
			setTimeout(function(){ window.location.href = "{{route('areas.index')}}"; }, 2000)
			
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