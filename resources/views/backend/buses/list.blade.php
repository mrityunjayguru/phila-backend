@extends('layouts.backend.master')


@section('filter')
	<!-- FILTERS -->
	<div class="modal fade" id="filterBox" tabindex="-1" role="dialog" aria-labelledby="filterBoxTitle" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="filterTitle">{{trans('common.filters')}}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="input-group">
								<input id="search" placeholder="Type to search" step="1" type="text" class="form-control">
							</div>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-6">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">Page</span>
								</div>
								<input id="page" value="1" placeholder="Ex: 0 or 100" step="1" type="number" class="form-control">
							</div>
						</div>
						<div class="col-md-6">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">Count</span>
								</div>
								<input id="count" value="10" placeholder="Ex: 0 or 100" step="1" type="number" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<br>
							<h5 class="card-title">Status</h5>
							<div class="position-relative form-group">
								<div>
									<div class="custom-radio custom-control">
										<input type="radio" id="active" value="active" name="statusRadio" class="custom-control-input">
										<label class="custom-control-label" for="active">Active</label>
									</div>
									<div class="custom-radio custom-control">
										<input type="radio" id="inactive" value="inactive" name="statusRadio" class="custom-control-input">
										<label class="custom-control-label" for="inactive">Inactive</label>
									</div>
									<div class="custom-radio custom-control">
										<input type="radio" id="all" value="all" name="statusRadio" class="custom-control-input" checked>
										<label class="custom-control-label" for="all">All</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" onclick="getData();" data-dismiss="modal">Apply</button>
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
						<span class="d-inline-block">Vehicles</span>
					</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="page-title-subheading opacity-10">
					<nav class="" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a><i aria-hidden="true" class="fa fa-home"></i></a></li>
							<li class="breadcrumb-item"> <a>Vehicles</a></li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
	<!-- CONTENT START -->
	<div class="main-card mb-3 card">
		<div class="card-header-tab card-header">
			<div class="card-header-title font-size-lg text-capitalize font-weight-normal">Vehicle List</div>
			<div class="btn-actions-pane-right text-capitalize">
				<button type="button" class="btn-wide btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm" data-toggle="modal" data-target="#filterBox">{{trans('common.filters')}}</button>
				@if(Settings::UserPermission('bus-create'))
				<a class="btn-wide btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm" href="{{ route('buses.create') }}" > {{trans('common.add_new')}} </a>
				@endif
			</div>
		</div>
		<div class="card-body">
			<h5 class="card-title"></h5>
			<div class="table-responsive">
				<table class="mb-0 table table-striped">
					<thead>
						<tr>
						 <th>#</th>
						 <th>{{ trans('common.device_id') }}</th>
						 <th>IMEI Number</th>
						 <th>{{ trans('common.device_type') }}</th>
						 <th>Device status</th>
						 <th>{{ trans('common.title') }}</th>
						 <th>{{ trans('common.created_at') }}</th>
						 <!--<th style="width:115px;">Security</th>-->
						 <th>{{ trans('common.status') }}</th>
						 <th>Audio Available</th>
						 <th>{{ trans('common.action') }}</th>
						</tr>
					</thead>
					<tbody id="data-list"></tbody>
				</table>
			</div>
		</div>
		<div class="btn-actions-pane-right mb-3 text-capitalize">
			<button type="button" class="btn-wide btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm previous-btn">{{trans('common.previous')}}</button>
			<button type="button" class="btn-wide btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm next-btn">{{trans('common.next')}}</button>
		</div>
	</div>
	<!-- CONTENT OVER -->
@endsection
@section('js')
<script>
	$(document).ready(function(e) {
		var page = $('#filterBox #page').val();
		getData(page);
		
		$('.next-btn').click(function() {
			page = Number($('#filterBox #page').val()) + 1;
			getData(page);
		});
		
		$('.previous-btn').click(function() {
			page = Number($('#filterBox #page').val()) - 1;
			if(page == 0){ return false;}
			getData(page);
		});

		$(document).on('change', '.status', function() {
			var data = new FormData();
			data.append('status', $(this).val());
			data.append('id', $(this).attr('id'));
			var response = adminAjax('{{route("ajax.bus.change.status")}}', data);
			if (response.status == '200') {
				if (response.data.status == 'success') {
					swal.fire({
						type: 'success',
						title: response.message,
						showConfirmButton: false,
						timer: 1500
					});
				}
			}
		});
		
		$(document).on('change', '.audio_available', function() {
			var data = new FormData();
			data.append('audio_available', $(this).val());
			data.append('id', $(this).attr('id'));
			var response = adminAjax('{{route("ajax.audio.change.audio.status")}}', data);
			if (response.status == '200') {
				if (response.data.status == 'success') {
					swal.fire({
						type: 'success',
						title: response.message,
						showConfirmButton: false,
						timer: 1500
					});
				}
			}
		});
		
		$(document).on('change', '.security', function() {
			var data = new FormData();
			data.append('security', $(this).val());
			data.append('id', $(this).attr('id'));
			var response = adminAjax('{{route("ajax.bus.change.security")}}', data);
			if (response.status == '200') {
				if (response.data.status == 'success') {
					swal.fire({
						type: 'success',
						title: response.message,
						showConfirmButton: false,
						timer: 1500
					});
				}
			}
		});
	});
	
	// GET LIST
	function getData(page = 1){
		var data = new FormData();
		data.append('page', page);
		data.append('count', $('#filterBox #count').val());
		data.append('search', $('#filterBox #search').val());
		data.append('status', $('input[name="statusRadio"]:checked').val());
		data.append('audio_available', $('input[name="audioAvailable"]:checked').val());
		var response = adminAjax('{{route("ajax.buses.list")}}', data);
		if(response.status == '200'){
			$('#data-list').empty();
			if(response.data.length > 0){
				$('#filterBox #page').val(page);
				var uni_index = $('#filterBox #count').val() * (page - 1);
				var htmlData  = '';
				if(page > 1){ $('.previous-btn').show(); } else { $('.previous-btn').hide(); }
				$.each(response.data, function( index, value ) {
					uni_index = uni_index + 1;
					htmlData+= '<tr>'+
									'<th scope="row">'+ uni_index +'</th>'+
									'<td>'+ value.device_id +'</td>'+
									'<td>'+ value.imei_number +'</td>'+
									'<td>'+ value.device_type +'</td>'+
									'<td>'+ value.live_status +'</td>'+
									'<td>'+ value.title +'</td>'+
									'<td>'+ value.created_at +'</td>'+
									//'<td>'+ value.security +'</td>'+
									'<td>'+ value.status +'</td>'+
									'<td>'+ value.audio_available +'</td>'+
									'<td>'+ value.action +'</td>'+
								'</tr>';
				})
				$('#data-list').html(htmlData);
			}else{
				$('#data-list').html('<tr><td colspan="7" style="text-align:center;">'+response.message+'</td></tr>');
			}
		}
	}

	// DELETE
	function deleteThis(item_id = ''){
		alert('Contact developer to perform this action'); return false;
		if(confirm("{{trans('common.delete_confirm')}}")){
			var data = new FormData();
			data.append('item_id', item_id);
			var response = adminAjax('{{route("ajax.delete.bus")}}', data);
			if(response.status == '200'){
				window.location.href = "{{route('buses.index')}}";
			}
		}
	}
</script>
@endsection