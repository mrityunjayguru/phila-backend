@extends('layouts.backend.master')

@section('popup')
	<div class="modal fade" id="app-modalBox" tabindex="-1" role="dialog" aria-labelledby="app-modalBoxLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="app-modalBoxLabel">Edit</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div id="app-modalBody" class="modal-body">
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" onclick="saveModalBox()">Save changes</button>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('content')
	<div class="app-page-title app-page-title-simple">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div>
					<div class="page-title-head center-elem">
						<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
						<span class="d-inline-block">{{ trans('slider.edit') }}</span>
					</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="page-title-subheading opacity-10">
					<nav class="" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
							<li class="breadcrumb-item"> <a  href="javascript:void(0);">{{ trans('slider.singular') }}</a></li>
							<li class="active breadcrumb-item" aria-current="page">{{ trans('slider.edit') }}</li>
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
				<!--<div class="form-row">
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="title" class="">{{ trans('slider.title') }}</label>
							<input type="text" id="title" value="{{ $data->title }}" placeholder="{{ trans('slider.placeholder.title') }}" class="form-control">
							<div class="validation-div" id="val-title"></div>
						</div>
					</div>
					<div class="col-md-2">
						<label for="device" class="">{{ trans('slider.device') }}</label>
						<select class="form-control" id="device">
							<option value=""> Select Device...</option>
							<option value="Mobile" @if($data->device == 'Mobile') selected @endif>Mobile</option>
							<option value="Web" @if($data->device == 'Web') selected @endif>Web</option>
						</select>
						<div class="validation-div" id="val-device"></div>
					</div>
				</div>

				<div class="form-row">
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="slug">{{ trans('slider.slug') }}</label>
							<input type="text" id="slug" value="{{ $data->slug }}" placeholder="{{ trans('slider.placeholder.slug') }}" class="form-control">
							<div class="validation-div" id="val-slug"></div>
						</div>
					</div>
				</div>
				
				<div class="form-row">
					<div class="col-md-2">
						<div class="form-group">
							<select class="form-control" id="status">
							  <option value="active" @if($data->status == 'active') selected @endif>{{trans('common.active')}}</option>
							  <option value="inactive" @if($data->status == 'inactive') selected @endif>{{trans('common.inactive')}}</option>
							</select>
						</div>
					</div>
				</div>
				<br>
				<br>-->
				
				<div class="form-row">
					<div class="col-md-12">
						<h5>{{ trans('slider.slides') }}@if(Settings::UserPermission('slider-create')) <a class="btn-wide btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm pull-right" onclick="openPopUpBox()">Add New</a>@endif</h5>
						<input type="hidden" id="app-modal-btn" data-toggle="modal"data-target="#app-modalBox" value="Open Model"/>
						<hr>
					</div>
					
					@if(count($slides) > 0)
					@foreach($slides as $list)
					<div class="col-md-3">
						<div class="card-shadow-primary card-border mb-3 card">
							<div class="dropdown-menu-header">
								<div class="dropdown-menu-header-inner bg-focus">
									<div class="menu-header-image" style="background-image: url({{asset($list->image)}});"></div>
									<div class="menu-header-content">
										<div>
											<h5 class="menu-header-title">{{ $list->title }}</h5>
											<br>
											<br>
											<br>
											<br>
										</div>
									</div>
								</div>
							</div>
							<div class="text-center d-block card-footer">
								@if(Settings::UserPermission('slider-edit'))
								<a class="btn-shadow-primary btn btn-primary btn-sm text-white" onclick="openPopUpBox({{ $list->id }})"><i class="fa fa-edit"></i> Edit</a>
								@endif
								@if(Settings::UserPermission('slider-delete'))
								<a class="mr-2 text-danger btn btn-link btn-sm" onclick="deleteSlide({{ $list->id }})">Delete</a>
								@endif
							</div>
						</div>
					</div>
					@endforeach
					@endif
				</div>
				 
				<div class="form-row">
					@if(Settings::UserPermission('slider-edit'))
					<!--<button class="mt-2 btn btn-primary">{{ trans('common.submit') }}</button>-->
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
		data.append('item_id', '{{$data->id}}');
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
	
	// Open Model
	function openPopUpBox(item_id = ''){
		var data = new FormData();
		data.append('item_id', item_id);
		var response = adminAjax('{{route("ajax.slider.open.slide.box")}}', data);
		if(response.status == '200'){
			if(response.data){
				$('#app-modalBody').html(response.data);
				$( "#app-modal-btn" ).trigger( "click" );
				return false;
			}
		}
		swal.fire({title: response.message,type: 'error'});
	}
	
	// Save Model Data
	function saveModalBox(){
		var data = new FormData();
		data.append('item_id', $('#app-modalBody #item_id').val());
		data.append('slider_id', '{{$data->id}}');
		data.append('title', $('#app-modalBody #title').val());
		data.append('priority', $('#app-modalBody #priority').val());
		data.append('description', $('#app-modalBody #description').val());
		data.append('image', $('#image')[0].files[0]);
		data.append('is_clickable', $('#app-modalBody #is_clickable').val());
		data.append('redirect_to', $('#app-modalBody #redirect_to').val());
		//data.append('button_text', $('#button_text').val());
		var response = adminAjax('{{route("ajax.slider.store.slide")}}', data);
		if(response.status == '200'){
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
			setTimeout(function(){ location.reload(); }, 2000)
			
		}else if(response.status == '422'){
			$('.validation-div').text('');
			$.each(response.error, function( index, value ) {
				$('#app-modalBody #val-'+index).text(value);
			});
			
		} else if(response.status == '201'){
			swal.fire({title: response.message,type: 'error'});
		}
	}
	
	// Save Model Data
	function deleteSlide(item_id = ''){
		if(confirm("{{trans('common.delete_confirm')}}")){
			var data = new FormData();
			data.append('item_id', item_id);
			var response = adminAjax('{{route("ajax.delete.slide")}}', data);
			if(response.status == '200'){
				swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
				setTimeout(function(){ location.reload(); }, 2000)
				
			}else if(response.status == '201'){
				swal.fire({title: response.message,type: 'error'});
			}
		}
	}
</script>
@endsection