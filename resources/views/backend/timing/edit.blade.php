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
						<span class="d-inline-block">Timing</span>
					</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="page-title-subheading opacity-10">
					<nav class="" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
							<li class="active breadcrumb-item" aria-current="page">Timing</li>
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
							<label for="first_bus">Tour Route First Bus</label>
							<input type="text" id="first_bus" placeholder="" value="{{$data->first_bus}}" class="form-control">
							<div class="validation-div" id="val-first_bus"></div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="last_bus">Tour Route Last Bus</label>
							<input type="text" id="last_bus" placeholder="" value="{{$data->last_bus}}"class="form-control">
							<div class="validation-div" id="val-last_bus"></div>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-2">
						<div class="position-relative form-group">
							<label for="frequency">Tour Route Frequency</label>
							<input type="text" id="frequency" placeholder="" value="{{$data->frequency}}" class="form-control">
							<div class="validation-div" id="val-frequency"></div>
						</div>
					</div>
				</div>
				
				
				<hr>
				<div class="form-row">
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="fairmount_first_bus">Fairmount Park First Bus</label>
							<input type="text" id="fairmount_first_bus" placeholder="" value="{{$data->fairmount_first_bus}}" class="form-control">
							<div class="validation-div" id="val-fairmount_first_bus"></div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="fairmount_last_bus">Fairmount Park Last Bus</label>
							<input type="text" id="fairmount_last_bus" placeholder="" value="{{$data->fairmount_last_bus}}"class="form-control">
							<div class="validation-div" id="val-fairmount_last_bus"></div>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-2">
						<div class="position-relative form-group">
							<label for="fairmount_frequency">Fairmount Park Frequency</label>
							<input type="text" id="fairmount_frequency" placeholder="" value="{{$data->fairmount_frequency}}" class="form-control">
							<div class="validation-div" id="val-fairmount_frequency"></div>
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
	// CREATE
	function saveData(){
		var data = new FormData();
		data.append('first_bus', $('#first_bus').val());
		data.append('last_bus', $('#last_bus').val());
		data.append('frequency', $('#frequency').val());
		
		data.append('fairmount_first_bus', $('#fairmount_first_bus').val());
		data.append('fairmount_last_bus', $('#fairmount_last_bus').val());
		data.append('fairmount_frequency', $('#fairmount_frequency').val());
		var response = adminAjax('{{route("timings.store")}}', data);
		if(response.status == '200'){
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
			setTimeout(function(){ window.location.href = "{{route('timings.index')}}"; }, 2000)
			
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