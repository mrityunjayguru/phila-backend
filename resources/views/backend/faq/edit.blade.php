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
							<li class="breadcrumb-item"> <a  href="{{ route('faqs.index') }}">FAQs</a></li>
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
			<form class="" action="javascript:void(0);" onsubmit="saveData()" enctype="multipart/form-data">
				<div class="form-row">
					<div class="col-md-4">
						<div class="position-relative form-group">
							<label for="title">Title</label>
							<input type="text" id="title" placeholder="Enter title" value="{{ $data->title }}" class="form-control">
							<div class="validation-div" id="val-title"></div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="position-relative form-group">
							<label for="priority">Priority</label>
							<input type="number" id="priority" placeholder="Ex: 0 - 100" value="{{ $data->priority }}" class="form-control">
							<div class="validation-div" id="val-priority"></div>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-3">
						<div class="form-group">
		                    <label>Topic</label>
							<select  class="form-control" minlength="2" maxlength="255" id="topic">
		                      <option value="">Select Topic</option>
		                      @foreach($topics as $list)
							  <option value="{{$list->id}}"@if($data->topic == $list->id) selected @endif>{{$list->title}}</option>
							  @endforeach
		                    </select>
		                </div>
					</div>
				</div>
				
				<div class="form-row">
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="description">Description</label>
							<textarea  name="description" id="description" rows="5" class="form-control">{{ $data->description }}</textarea>
							<div class="validation-div" id="val-description"></div>
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
				@if(Settings::UserPermission('faq-edit'))
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
			data.append('title', $('#title').val());
			data.append('topic', $('#topic').val());
			data.append('description', $('#description').val());
			data.append('priority', $('#priority').val());
			data.append('status', $('#status').val());
			var response = adminAjax('{{route("faqs.store")}}', data);
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