	<!--<hr>
	<div class="form-row">
		<div class="col-md-6">
			<div class="position-relative form-group">
				<label for="nickname">{{ trans('common.nickname') }}</label>
				<input id="nickname" type="text" class="form-control">
				<div class="validation-div" id="val-nickname"></div>
			</div>
		</div>
	</div>-->
	
	<!-- Categories -->
	<div class="main-card mb-3 card">
		<div class="card-body">
			<div class="card-title">Permissions</div>
			<div class="position-relative form-group">
				<div>
					@if(count($permissions) > 0)
					@foreach($permissions as $list)
					<div class="custom-checkbox custom-control">
						<input type="checkbox" name="permission" value="{{$list->id}}" id="permission-{{$list->id}}" class="custom-control-input category">
						<label class="custom-control-label" for="permission-{{$list->id}}">{{$list->name}}</label>
					</div>
					@endforeach
					@endif
				</div>
			</div>
		</div>
	</div>