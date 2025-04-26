@extends('layouts.backend.master')

@section('css')
 	<link rel='stylesheet prefetch' href='https://foliotek.github.io/Croppie/croppie.css'>
 	<style type="text/css">
 		#cropImagePop .modal-body {
 			height: 400px;
 		}
 		#cropImagePop .modal-content{
 			width: 600px;
 		}
 	</style>
@endsection

@section('popup')
	<!-- CROP MODAL -->
	<div class="modal fade" id="cropImagePop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
				  	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  	<h4 class="modal-title" id="myModalLabel"></h4>
				</div>
				<div class="modal-body">
		            <div id="upload-demo" class="center-block"></div>
		      	</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" id="cropImageBtn" class="btn btn-primary">Crop</button>
				</div>
			</div>
		</div>
	</div>
	<!-- CROP MODAL OVER -->
@endsection

@section('content')
		<div class="app-page-title app-page-title-simple">
			<div class="page-title-wrapper">
				<div class="page-title-heading">
					<div>
						<div class="page-title-head center-elem">
							<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
							<span class="d-inline-block">{{ trans('country.update') }}</span>
						</div>
					</div>
				</div>
				<div class="page-title-actions">
					<div class="page-title-subheading opacity-10">
						<nav class="" aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a><i aria-hidden="true" class="fa fa-home"></i></a></li>
								<li class="breadcrumb-item"> <a>Country</a></li>
								<li class="active breadcrumb-item" aria-current="page">edit</li>
							</ol>
						</nav>
					</div>
				</div>
			</div>
		</div>
				
	<!-- CONTENT START -->
	<div class="main-card mb-3 card">
		<div class="card-body">
			<form action="javascript:void(0);" id="update_country_form" enctype="multipart/form-data">
				<div class="form-row">
					<input type="hidden" name="edit_id" id="edit_id" value="{{$countries->id}}"> 
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="country_name" class="">Country Name</label>
							<input id="country_name" name="country_name" type="text" value="{{$countries->country_name}}" class="form-control" required>
							<div class="validation-div" id="val-price"></div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="country_code" class="">Country Code</label>
							<input id="country_code" name="country_code" type="text" value="{{$countries->country_code}}" class="form-control">
							<div class="validation-div" id="val-price"></div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="dial_code" class="">Dial Code</label>
							<input id="dial_code" name="dial_code" type="text" value="{{$countries->dial_code}}" class="form-control">
							<div class="validation-div" id="val-price"></div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="currency_code" class="">Currency code</label>
							<input id="currency_code" name="currency_code" type="text" value="{{$countries->currency_code}}" class="form-control">
							<div class="validation-div" id="val-price"></div>
						</div>
					</div>

					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="currency" class="">Currency</label>
							<input id="currency" name="currency" type="text" value="{{$countries->currency}}"  class="form-control">
							<div class="validation-div" id="val-price"></div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="currency_symbol" class="">Currency Symbol</label>
							<input id="currency_symbol" name="currency_symbol" type="text" value="{{$countries->currency_symbol}} class="form-control">
							<div class="validation-div" id="val-price"></div>
						</div>
					</div>
				</div>
	
				
				<hr>
				<div class="form-row">
					<div class="col-md-2">
						<div class="form-group">
							<select class="form-control" name="status" id="status" required>
								<option value="active" 
								@if($countries->status == 'active') selected @endif>
									{{trans('common.active')}}
								</option>
								<option value="inactive" 
								@if($countries->status == 'inactive') selected @endif>
									{{trans('common.inactive')}}
								</option>
							</select>
						</div>
					</div>
				</div>
				<button class="mt-2 btn btn-primary">Update</button>
			</form>
		</div>
	</div>
	<!-- CONTENT OVER -->
@endsection


@section('js')
<script src='https://foliotek.github.io/Croppie/croppie.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js'></script>
<script>
	$('#update_country_form').submit(function (e){
		e.preventDefault();
		let formData = new FormData(this);
		formData.append('filter_section', 'update');
		let response = adminAjax(SITE_URL +'/backend/saveCountries', formData);
		if(response.status == 200){
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
		}
	});
</script>
@endsection