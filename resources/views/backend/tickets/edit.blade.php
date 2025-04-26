@extends('layouts.backend.master')

@section('content')
	<div class="app-page-title app-page-title-simple">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div>
					<div class="page-title-head center-elem">
						<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
						<span class="d-inline-block">Add New</span>
					</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="page-title-subheading opacity-10">
					<nav class="" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
							<li class="breadcrumb-item"> <a  href="{{ route('tickets.index') }}">Code</a></li>
							<li class="active breadcrumb-item" aria-current="page">Add New</li>
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
					<div class="col-md-4">
						<div class="position-relative form-group">
							<label for="ticket_number">Code Number</label>
							<input type="text" id="ticket_number" value="{{ $data->ticket_number }}" placeholder="Enter Code number" class="form-control">
							<div class="validation-div" id="val-ticket_number"></div>
						</div>
					</div>
				</div>
				
				<!--<div class="form-row">
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="customer_name">Customer Name</label>
							<input type="text" id="customer_name" value="{{ $data->customer_name }}" placeholder="Enter Customer Name" class="form-control">
							<div class="validation-div" id="val-customer_name"></div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="customer_number">Customer Number</label>
							<input type="number" id="customer_number" value="{{ $data->customer_number }}" placeholder="Enter contact number" class="form-control">
							<div class="validation-div" id="val-customer_number"></div>
						</div>
					</div>
				</div>-->
				
				<div class="form-row">
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="end_date">End Date</label>
							<input type="date" id="end_date" class="form-control" value="{{ $data->end_date }}">
							<div class="validation-div" id="val-end_date"></div>
						</div>
					</div>
				</div>
				
				<div class="form-row">
					<div class="col-md-2">
		                <div class="form-group">
		                    <label>Status</label>
							<select  class="form-control" minlength="2" maxlength="255" id="status" name="status">
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
				@if(Settings::UserPermission('ticket-edit'))
				<button class="mt-2 btn btn-primary">{{ trans('common.submit') }}</button>
				@endif
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
			data.append('ticket_number', $('#ticket_number').val());
			data.append('bus', $('#bus').val());
			data.append('end_date', $('#end_date').val());
			data.append('customer_name', $('#customer_name').val());
			data.append('customer_number', $('#customer_number').val());
			data.append('status', $('#status').val());
			var response = adminAjax('{{route("tickets.store")}}', data);
			if(response.status == '200'){
				swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
				setTimeout(function(){ location.reload(); }, 2000)
			}else if(response.status == '422'){
				$('.validation-div').text('');
				$.each(response.error, function( index, value ) {
					$('#val-'+index).text(value);
				});
			}
		}
	</script>
@endsection