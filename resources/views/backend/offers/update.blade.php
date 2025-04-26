@extends('layouts.backend.master')

@section('content')
	<div class="app-page-title app-page-title-simple">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div>
					<div class="page-title-head center-elem">
						<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
						<span class="d-inline-block">Edit</span>
					</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="page-title-subheading opacity-10">
					<nav class="" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
							<li class="breadcrumb-item"> <a  href="{{ route('offers.index') }}">Offers</a></li>
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
			<form class="" action="javascript:void(0);" onsubmit="saveCoupon()"  enctype="multipart/form-data">
				<div class="form-row">
					<div class="col-md-6">
						<div class="form-row">
							<div class="col-md-3">
								<div class="position-relative form-group">
									<label for="place_id" class="">Place</label>
									<select class="form-control" id="place_id">
										@if(empty($data->place_id))
										<option value=''>Select Place</option>
										@endif
										@foreach ($places as $list)
										<option value="{{$list->id}}" @if($data->place_id == $list->id) selected @endif>{{$list->title}}</option>
										@endforeach
									</select>
									<div class="validation-div" id="val-place_id"></div>
								</div>
							</div>
							@if(empty($data->place_id))
							<div class="col-md-8">
								<div class="title-div position-relative form-group">
									<label for="title" class="">Title</label>
									<input type="text" id="title" placeholder="Enter title" value="{{$data->title}}" class="form-control">
									<div class="validation-div" id="val-title"></div>
								</div>
							</div>
							@endif
						</div>
						
						@if(empty($data->place_id))
						<div class="form-row">
							<div class="col-md-3">
								<div class="stop-div position-relative form-group">
									<label for="nearest_stop" class="">Nearest Stop</label>
									<select class="form-control" id="nearest_stop">
										<option value=''>Select Stop</option>
										@foreach ($stops as $list)
										<option value="{{$list->id}}" @if($data->nearest_stop == $list->id) selected @endif>{{$list->title}}</option>
										@endforeach
									</select>
									<div class="validation-div" id="val-nearest_stop"></div>
								</div>
							</div>
							
							<div class="col-md-9">
								<div class="website-div position-relative form-group">
									<label for="website" class="">Website</label>
									<input type="text" id="website" placeholder="Enter URL" value="{{$data->website}}" class="form-control">
									<div class="validation-div" id="val-website"></div>
								</div>
							</div>
						</div>
						@endif
						
						<div class="form-row">
							<div class="col-md-6">
								<div class="position-relative form-group">
									<label for="start_date" class="">Start Date</label>
									<input type="text" id="start_date" placeholder="Enter Start Date" class="form-control" value="{{date('m/d/Y',strtotime($data->start_date))}}" autocomplete="off" data-toggle="datepicker">
									<div class="validation-div" id="val-start_date"></div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="position-relative form-group">
									<label for="end_date" class="">End Date</label>
									<input type="text" id="end_date" placeholder="Enter End Date" class="form-control" value="{{date('m/d/Y',strtotime($data->end_date))}}" autocomplete="off" data-toggle="datepicker">
									<div class="validation-div" id="val-end_date"></div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="position-relative form-group">
									<label for="description" class="">Description</label>
									<textarea name="description" id="description"  rows="4" class="form-control">{{$data->description}}</textarea>
									<div class="validation-div" id="val-description"></div>
								</div>
							</div>
						</div>
					</div>
					
					@if(empty($data->place_id))
					<div class="col-md-6">
						<div class="form-row">
							<div class="col-md-12">
								<div class="image-div position-relative form-group">
									<label for="exampleFile" class="">Image</label>
									<input name="file" id="image" type="file" class="form-control-file item-img" accept="image/*">
									<div class="validation-div" id="val-image"></div>
									<div class="image-preview"><img id="image-src" src="@if($data->image) {{ asset($data->image) }} @endif" width="100%"/></div>
									<input type="hidden" id="img-blob">
								</div>
							</div>
						</div>
					</div>
					@endif
					
				</div>
				<div class="form-row">
					<div class="col-md-2">
		                <div class="form-group">
							<select class="form-control" id="status">
							  <option value="active" 
		                        @if($data->status == 'active') selected @endif>
		                        {{trans('common.active')}}
		                      	</option>
		                      	<option value="inactive" 
		                        @if($data->status == 'inactive') selected @endif>
		                        {{trans('common.inactive')}}
		                      	</option>
							</select>
						</div>
      				</div>
    			</div>
				<div class="form-row">
					@if(Settings::UserPermission('offer-edit'))
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
	$(document).ready(function(e) {
		$("#image").change(function () {
			readURL(this);
		});
		
		$('#place_id').on('change', function () {
			if($(this).val() == ''){
				$('.image-div').show();
				$('.title-div').show();
				$('.stop-div').show();
				$('.website-div').show();
			}else{
				$('.title-div').hide();
				$('.image-div').hide();
				$('.stop-div').hide();
				$('.website-div').hide();
			}
		});
	});
	
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				jQuery('#image-src').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	
	// CREATE
	function saveCoupon(){
		var data = new FormData();
		data.append('item_id', '{{$data->id}}');
		data.append('description', $('#description').val());
		data.append('start_date', $('#start_date').val());
		data.append('end_date', $('#end_date').val());
		data.append('status', $('#status').val());
		if('{{$data->place_id}}'){
			data.append('place_id', $('#place_id').val());
		}else{
			data.append('title', $('#title').val());
			data.append('nearest_stop', $('#nearest_stop').val());
			data.append('website', $('#website').val());
			data.append('image', $('#image')[0].files[0]);
		}
		
		var response = adminAjax('{{route("offers.store")}}', data);
		if(response.status == '200'){
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
			setTimeout(function(){ window.location.reload(); }, 2000)
			
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