@extends('layouts.backend.master')

@section('content')
	<div class="app-page-title app-page-title-simple">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div>
					<div class="page-title-head center-elem">
						<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
						<span class="d-inline-block">Send Notification</span>
					</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="page-title-subheading opacity-10">
					<nav class="" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
							<li class="active breadcrumb-item" aria-current="page">Notification</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>

	<!-- CONTENT START -->
	<div class="main-card mb-3 card">
		<div class="card-body">
			<div id="loader"  style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
				<img src="{{ asset('uploads/output-onlinegiftools.gif') }}" alt="Loading..." />
			</div>
			<form class="" action="javascript:void(0);" onsubmit="saveData()"  enctype="multipart/form-data">
				<div class="form-row">
					<div class="col-md-6">
						<div class="form-row">
							<div class="col-md-9">
								<div class="position-relative form-group">
									<label for="title">Title</label>
									<input type="text" id="title" placeholder="Enter Title" class="form-control">
									<div class="validation-div" id="val-title"></div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="position-relative form-group">
									<label for="place">Select Place</label>
									<select class="form-control" id="place">
									<option value=""> Select Place</option>
									@foreach($data as $list)
									<option value="{{$list->id}}"> {{$list->title}}</option>
									@endforeach
								</select>
									<div class="validation-div" id="val-place"></div>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="position-relative form-group">
									<label for="description">Description</label>
									<textarea  name="description" id="description"  rows="5" class="form-control"></textarea>
									<div class="validation-div" id="val-description"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-3 d-none">
						<div class="position-relative form-group">
							<label for="image" class="">Image</label>
							<input name="file" id="image" type="file" class="form-control-file item-img" accept="image/*">
							<div class="validation-div" id="val-image"></div>
							<div class="image-preview"><img id="image-src" src="" width="100%"/></div>
						</div>
					</div>
				</div>
				
				<div class="form-row">
					<button class="mt-2 btn btn-primary subminBtn">{{ trans('common.submit') }}</button>
				</div>
			</form>
		</div>
	</div>
	<!-- CONTENT OVER -->
	
	<!-- CONTENT START -->
	<!--<div class="main-card mb-3 card">
		<div class="card-header-tab card-header">
			<div class="card-header-title font-size-lg text-capitalize font-weight-normal">History</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="mb-0 table table-striped">
					<thead>
						<tr>
						 <th>#</th>
						 <th>Title</th>
						 <th>Date</th>
						 <th>Status</th>
						</tr>
					</thead>
					<tbody id="data-list">
						<tr>
						 <th>1</th>
						 <th>Notification 1</th>
						 <th>21-03-22</th>
						 <th>Sent</th>
						</tr>
						<tr>
						 <th>2</th>
						 <th>Important Notification</th>
						 <th>20-03-22</th>
						 <th>Sent</th>
						</tr>
						<tr>
						 <th>3</th>
						 <th>Cafe Ole</th>
						 <th>11-03-22</th>
						 <th>Sent</th>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>-->
@endsection

@section('js')
<script>
	$(document).ready(function(e) {
		$("#image").change(function () {
			readURL(this);
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
	function saveData(){
		var data = new FormData();
		data.append('title', $('#title').val());
		data.append('description', $('#description').val());
		data.append('place', $('#place').val());
		data.append('image', $('#image')[0].files[0]);

		$('#loader').css('display','block');
		$('.subminBtn').prop('disabled', true);
		
		var response = adminAjax('{{route("ajax.notification.fire")}}', data);

		if(response.status == '200'){
			$('#loader').css('display', 'none');
			$('.subminBtn').prop('disabled', false);
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
			setTimeout(function(){ window.location.reload(); }, 2000)
			
		}else if(response.status == '422'){
			$('#loader').css('display', 'none');
			$('.subminBtn').prop('disabled', false);
			$('.validation-div').text('');
			$.each(response.error, function( index, value ) {
				$('#val-'+index).text(value);
			});
			
		} else if(response.status == '201'){
			$('#loader').css('display', 'none');
			$('.subminBtn').prop('disabled', false);
			swal.fire({title: response.message,type: 'error'});
		}
	}
</script>
@endsection