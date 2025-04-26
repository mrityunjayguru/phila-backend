@extends('layouts.backend.master')
  
@section('content')
<style>
#map_canvas {width: 980px;height: 500px;}
#current {padding-top: 25px;}
.image-preview{}
.parent_remove_btn{cursor: pointer;}
#pac-input{position: absolute;top: 10px!important; right: 60px!important; height: 28px; z-index: 9999;}
.tr-10{height: 50px;}

/*  Toggle Switch  */
.toggleSwitch span span {
  display: none;
}

@media only screen {
  .toggleSwitch {
    display: inline-block;
    height: 18px;
    position: relative;
    overflow: visible;
    padding: 0;
    margin-left: 10px;
    cursor: pointer;
    width: 40px;
	top: 11px;
  }
  .toggleSwitch * {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
  }
  .toggleSwitch label,
  .toggleSwitch > span {
    line-height: 20px;
    height: 20px;
    vertical-align: middle;
  }
  .toggleSwitch input:focus ~ a,
  .toggleSwitch input:focus + label {
    outline: none;
  }
  .toggleSwitch label {
    position: relative;
    z-index: 3;
    display: block;
    width: 100%;
  }
  .toggleSwitch input {
    position: absolute;
    opacity: 0;
    z-index: 5;
  }
  .toggleSwitch > span {
    position: absolute;
    left: -50px;
    width: 100%;
    margin: 0;
    padding-right: 50px;
    text-align: left;
    white-space: nowrap;
  }
  .toggleSwitch > span span {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 5;
    display: block;
    width: 50%;
    margin-left: 50px;
    text-align: left;
    font-size: 0.9em;
    width: 100%;
    left: 15%;
    top: -1px;
    opacity: 0;
  }
  .toggleSwitch a {
    position: absolute;
    right: 50%;
    z-index: 4;
    display: block;
    height: 100%;
    padding: 0;
    left: 2px;
    width: 18px;
    background-color: #fff;
    border: 1px solid #CCC;
    border-radius: 100%;
    -webkit-transition: all 0.2s ease-out;
    -moz-transition: all 0.2s ease-out;
    transition: all 0.2s ease-out;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  }
  .toggleSwitch > span span:first-of-type {
    color: #ccc;
    opacity: 1;
    left: 45%;
  }
  .toggleSwitch > span:before {
    content: '';
    display: block;
    width: 100%;
    height: 100%;
    position: absolute;
    left: 50px;
    top: -2px;
    background-color: #fafafa;
    border: 1px solid #ccc;
    border-radius: 30px;
    -webkit-transition: all 0.2s ease-out;
    -moz-transition: all 0.2s ease-out;
    transition: all 0.2s ease-out;
  }
  .toggleSwitch input:checked ~ a {
    border-color: #fff;
    left: 100%;
    margin-left: -8px;
  }
  .toggleSwitch input:checked ~ span:before {
    border-color: #0097D1;
    box-shadow: inset 0 0 0 30px #0097D1;
  }
  .toggleSwitch input:checked ~ span span:first-of-type {
    opacity: 0;
  }
  .toggleSwitch input:checked ~ span span:last-of-type {
    opacity: 1;
    color: #fff;
  }
  /* Switch Sizes */
  .toggleSwitch.large {
    width: 60px;
    height: 27px;
  }
  .toggleSwitch.large a {
    width: 27px;
  }
  .toggleSwitch.large > span {
    height: 29px;
    line-height: 28px;
  }
  .toggleSwitch.large input:checked ~ a {
    left: 41px;
  }
  .toggleSwitch.large > span span {
    font-size: 1.1em;
  }
  .toggleSwitch.large > span span:first-of-type {
    left: 50%;
  }
  .toggleSwitch.xlarge {
    width: 80px;
    height: 36px;
  }
  .toggleSwitch.xlarge a {
    width: 36px;
  }
  .toggleSwitch.xlarge > span {
    height: 38px;
    line-height: 37px;
  }
  .toggleSwitch.xlarge input:checked ~ a {
    left: 52px;
  }
  .toggleSwitch.xlarge > span span {
    font-size: 1.4em;
  }
  .toggleSwitch.xlarge > span span:first-of-type {
    left: 50%;
  }
}
</style>
<script src="{{asset('adminAssets/scripts/custom/google_map_api.js')}}"></script>
<input type="hidden" id="edit_status" value="1">

	<div class="app-page-title app-page-title-simple">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div>
					<div class="page-title-head center-elem">
						<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
						<span class="d-inline-block">{{ trans($type.'.edit') }}</span>
					</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="page-title-subheading opacity-10">
					<nav class="" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
							<li class="breadcrumb-item"> <a  href="{{ route('page.places',[$type]) }}">{{ trans($type.'.singular') }}</a></li>
							<li class="active breadcrumb-item" aria-current="page">{{ trans($type.'.edit') }}</li>
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
						<div class="form-row">
							<div class="col-md-8">
								<div class="position-relative form-group">
									<label for="title" class="">Title</label>
									<input type="text" id="title" value="{{$data->title}}" placeholder="Enter title" class="form-control">
									<div class="validation-div" id="val-title"></div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="position-relative form-group">
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
						</div>
						<div class="form-row">
							<div class="col-md-10">
								<div class="position-relative form-group">
									<label for="address" class="">Address</label>
									<input type="text" id="address" value="{{$data->address}}" placeholder="Enter Address" class="form-control">
									<div class="validation-div" id="val-address"></div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="position-relative form-group">
									<label for="latitude" class="">Latitude</label>
									<input type="text" id="latitude" value="{{$data->latitude}}" placeholder="Enter Latitude" class="form-control">
									<div class="validation-div" id="val-latitude"></div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="position-relative form-group">
									<label for="longitude" class="">Longitude</label>
									<input type="text" id="longitude" value="{{$data->longitude}}" placeholder="Enter Longitude" class="form-control">
									<div class="validation-div" id="val-longitude"></div>
								</div>
							</div>
							<div class="col-md-10">
								<div class="position-relative form-group">
									<label for="google_business_url" class="">Google Business URL (For Google Map)</label>
									<input type="text" id="google_business_url" value="{{$data->google_business_url}}" placeholder="Enter Google Business URL" class="form-control">
									<div class="validation-div" id="val-google_business_url"></div>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-5">
								<div class="position-relative form-group">
									<label for="website" class="">Website</label>
									<input type="text" id="website" value="{{$data->website}}" placeholder="Enter URL" class="form-control">
									<div class="validation-div" id="val-website"></div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="position-relative form-group">
									<label for="contact_number" class="">Contact Number</label>
									<input type="number" id="contact_number" value="{{$data->phone_number}}" placeholder="Enter Contact Number" class="form-control">
									<label for="website" class="">(Add URL with http OR https)</label>
									<div class="validation-div" id="val-contact_number"></div>
								</div>
							</div>
						</div>
						
						<div class="form-row">
							<div class="col-md-12">
								<b>Opening Hours</b>
								<label class="dasdads toggleSwitch" onclick="">
									<input name="hours" type="checkbox" @if($data->is_hours == '1') checked @endif>
									<span><span>OFF</span><span>ON</span></span>
									<a></a>
								</label>
							</div>
							<div class="hours-box col-md-12" @if($data->is_hours != '1') style="display:none;" @endif>
								<table>
									@foreach($days as $day)
									<tr class="tr-10">
										<td width="83px">{{$day['title']}}</td>
										<td width="83px">
										<label class="dasdads toggleSwitch" onclick="">
											<input name="{{$day['title']}}" type="checkbox"checked>
											<span><span>OFF</span><span>ON</span></span>
											<a></a>
										</label>
										</td>
										<td width="283px">
											<div class="row">
												<div class="col-md-6">
													<select class="form-control" id="{{$day['id']}}-1">
														<option value="">Open at</option>
														<option value="07:00 AM">07:00 AM</option>
														<option value="07:30 AM">07:30 AM</option>
														<option value="08:00 AM">08:00 AM</option>
														<option value="08:30 AM">08:30 AM</option>
														<option value="09:00 AM">09:00 AM</option>
														<option value="09:30 AM">09:30 AM</option>
														<option value="10:00 AM">10:00 AM</option>
														<option value="10:30 AM">10:30 AM</option>
														<option value="11:00 AM">11:00 AM</option>
														<option value="11:30 AM">11:30 AM</option>
														<option value="12:00 PM">12:00 PM</option>
														<option value="12:30 PM">12:30 PM</option>
														<option value="01:00 PM">01:00 PM</option>
														<option value="01:30 PM">01:30 PM</option>
														<option value="02:00 PM">02:00 PM</option>
														<option value="02:30 PM">02:30 PM</option>
														<option value="03:00 PM">03:00 PM</option>
														<option value="03:30 PM">03:30 PM</option>
														<option value="04:00 PM">04:00 PM</option>
														<option value="04:30 PM">04:30 PM</option>
														<option value="05:00 PM">05:00 PM</option>
														<option value="05:30 PM">05:30 PM</option>
														<option value="06:00 PM">06:00 PM</option>
														<option value="06:30 PM">06:30 PM</option>
														<option value="07:00 PM">07:00 PM</option>
														<option value="07:30 PM">07:30 PM</option>
														<option value="08:00 PM">08:00 PM</option>
														<option value="08:30 PM">08:30 PM</option>
														<option value="09:00 PM">09:00 PM</option>
														<option value="09:30 PM">09:30 PM</option>
														<option value="10:00 PM">10:00 PM</option>
														<option value="10:30 PM">10:30 PM</option>
														<option value="11:00 PM">11:00 PM</option>
														<option value="11:30 PM">11:30 PM</option>
														<option value="12:00 AM">12:00 PM</option>
													</select>
												</div>
												<div class="col-md-6">
													<select class="form-control" id="{{$day['id']}}-2">
														<option value="">Close at</option>
														<option value="07:30 AM">07:30 AM</option>
														<option value="08:00 AM">08:00 AM</option>
														<option value="08:30 AM">08:30 AM</option>
														<option value="09:00 AM">09:00 AM</option>
														<option value="09:30 AM">09:30 AM</option>
														<option value="10:00 AM">10:00 AM</option>
														<option value="10:30 AM">10:30 AM</option>
														<option value="11:00 AM">11:00 AM</option>
														<option value="11:30 AM">11:30 AM</option>
														<option value="12:00 PM">12:00 PM</option>
														<option value="12:30 PM">12:30 PM</option>
														<option value="01:00 PM">01:00 PM</option>
														<option value="01:30 PM">01:30 PM</option>
														<option value="02:00 PM">02:00 PM</option>
														<option value="02:30 PM">02:30 PM</option>
														<option value="03:00 PM">03:00 PM</option>
														<option value="03:30 PM">03:30 PM</option>
														<option value="04:00 PM">04:00 PM</option>
														<option value="04:30 PM">04:30 PM</option>
														<option value="05:00 PM">05:00 PM</option>
														<option value="05:30 PM">05:30 PM</option>
														<option value="06:00 PM">06:00 PM</option>
														<option value="06:30 PM">06:30 PM</option>
														<option value="07:00 PM">07:00 PM</option>
														<option value="07:30 PM">07:30 PM</option>
														<option value="08:00 PM">08:00 PM</option>
														<option value="08:30 PM">08:30 PM</option>
														<option value="09:00 PM">09:00 PM</option>
														<option value="09:30 PM">09:30 PM</option>
														<option value="10:00 PM">10:00 PM</option>
														<option value="10:30 PM">10:30 PM</option>
														<option value="11:00 PM">11:00 PM</option>
														<option value="11:30 PM">11:30 PM</option>
														<option value="12:00 AM">12:00 PM</option>
													</select>
												</div>
											</div>
										</td>
									</tr>
									@endforeach
								</table>
							</div>
						</div>
						<br>
						
						@if($type == 'landmark' || $type == 'attraction')
						
						<div class="form-row">
							<div class="col-md-12">
								<b>Entry Charges</b>
								<label class="dasdads toggleSwitch" onclick="">
									<input name="charges" type="checkbox" @if($data->is_charges == '1') checked @endif>
									<span><span>OFF</span><span>ON</span></span>
									<a></a>
								</label>
							</div>
							<div class="col-md-8">
								<hr>
								<div class="charges-box position-relative form-group" @if($data->is_charges != '1') style="display:none;" @endif>
									<textarea id="charges" rows="4" class="form-control">{{$data->charges}}</textarea>
									<div class="validation-div" id="val-charges"></div>
								</div>
							</div>
							<div class="col-md-8">
								<div class="position-relative form-group">
									<label for="ticket_booking_url" class="">Ticket Booking Url</label>
									<input type="text" id="ticket_booking_url" value="{{$data->ticket_booking_url}}" placeholder="Enter URL" class="form-control">
									<div class="validation-div" id="val-ticket_booking_url"></div>
								</div>
							</div>
						</div>
						@endif
						
						<div class="form-row">
							<div class="col-md-12">
								<div class="position-relative form-group">
									<label for="description" class="">Description</label>
									<textarea name="description" id="description"  rows="4" class="form-control">{{$data->description}}</textarea>
									<div class="validation-div" id="val-description"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-row">
							<div class="col-md-12">
								<div class="position-relative form-group">
									<label for="exampleFile" class="">Image</label>
									<input name="file" id="image" type="file" class="form-control-file item-img" accept="image/*">
									<div class="validation-div" id="val-image"></div>
									
									<div class="image-preview">
										<button type="button" class="parent_remove_btn" data-id="{{$data->id}}" ><i aria-hidden="true" class="fa fa-remove remove_image_btn"></i></button>	
										<img id="image-src" src="@if($data->image) {{ asset($data->image) }} @endif" width="50%"/>
									</div>
									<input type="hidden" id="img-blob">
								</div>
							</div>
						</div>
						<div class="form-row">
                        <label for="description" class="">Select Location</label>
                        <div class="col-md-12">
                            <input id="pac-input" class="controls" type="text" placeholder="Type to search" />
                            <div id="map_canvas" style="height: 254px; width:100%;"></div>
                        </div>
                    </div>

                    <br>
                    <br>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-2">
		                <div class="form-group @error('status') ? has-error : ''  @enderror">
		                    {{Form::label('status', trans('common.status'),['class' => 'content-label'])}}<br>
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
		                     @if ($errors->has('status')) 
		                    <strong class="help-block">{{ $errors->first('status') }}</strong>
		                  @endif
		                </div>
      				</div>
    			</div>
				<div class="form-row">
					@if(Settings::UserPermission('place-create'))
					<button class="mt-2 btn btn-primary">{{ trans('common.submit') }}</button>
					@endif
				</div>
			</form>
		</div>
	</div>
	<!-- CONTENT OVER -->
@endsection

@section('js')
<!-- Map Js -->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>-->
<script async src="https://maps.googleapis.com/maps/api/js?key={{Settings::get('google_map_api_key')}}&callback=initMap&v=3.exp&sensor=false&libraries=places"></script>
<script>
	$(document).ready(function(e) {
		
		//initMap('{{$data->latitude}}', '{{$data->longitude}}', 1);
		setTimeout(function(){
			initMap('{{$data->latitude}}', '{{$data->longitude}}', 1);
		}, 2000)
		
		
		$("#image").change(function () {
			readURL(this);
		});

		//$('#latitude').prop('readonly',true);
		//$('#longitude').prop('readonly',true);

		// Charges Toggle
		$('input[name="charges"]').click(function(){
			if($(this).prop("checked") == true){
				$('.charges-box').show()
			} else if($(this).prop("checked") == false){
				$('.charges-box').hide()
			}
		})
		
		// Hours Toggle
		$('input[name="hours"]').click(function(){
			if($(this).prop("checked") == true){
				$('.hours-box').show()
			} else if($(this).prop("checked") == false){
				$('.hours-box').hide()
			}
		})
		
		if($('#image-src').attr('src') == ''){
			$('.parent_remove_btn').hide();
		}
		
		val_monday = '{{$data->monday}}';
		if(val_monday == 'Closed'){
			$('input[name="Monday"]').prop("checked", false);
			$('#monday-1').hide();
			$('#monday-2').hide();
		}else{
			var arr = val_monday.split('-');
			$('#monday-1 option[value="'+ $.trim(arr[0]) +'"]').attr("selected", true);
			$('#monday-2 option[value="'+ $.trim(arr[1]) +'"]').attr("selected", true);
		}
		
		val_tuesday = '{{$data->tuesday}}';
		if(val_tuesday == 'Closed'){
			$('input[name="Tuesday"]').prop("checked", false);
			$('#tuesday-1').hide();
			$('#tuesday-2').hide();
		}else{
			var arr = val_tuesday.split('-');
			$('#tuesday-1 option[value="'+ $.trim(arr[0]) +'"]').attr("selected", true);
			$('#tuesday-2 option[value="'+ $.trim(arr[1]) +'"]').attr("selected", true);
		}
		
		val_wednesday = '{{$data->wednesday}}';
		if(val_wednesday == 'Closed'){
			$('input[name="Wednesday"]').prop("checked", false);
			$('#wednesday-1').hide();
			$('#wednesday-2').hide();
		}else{
			var arr = val_wednesday.split('-');
			$('#wednesday-1 option[value="'+ $.trim(arr[0]) +'"]').attr("selected", true);
			$('#wednesday-2 option[value="'+ $.trim(arr[1]) +'"]').attr("selected", true);
		}
		
		val_thursday = '{{$data->thursday}}';
		if(val_thursday == 'Closed'){
			$('input[name="Thursday"]').prop("checked", false);
			$('#thursday-1').hide();
			$('#thursday-2').hide();
		}else{
			var arr = val_thursday.split('-');
			$('#thursday-1 option[value="'+ $.trim(arr[0]) +'"]').attr("selected", true);
			$('#thursday-2 option[value="'+ $.trim(arr[1]) +'"]').attr("selected", true);
		}
		
		val_friday = '{{$data->friday}}';
		if(val_friday == 'Closed'){
			$('input[name="Friday"]').prop("checked", false);
			$('#friday-1').hide();
			$('#friday-2').hide();
		}else{
			var arr = val_friday.split('-');
			$('#friday-1 option[value="'+ $.trim(arr[0]) +'"]').attr("selected", true);
			$('#friday-2 option[value="'+ $.trim(arr[1]) +'"]').attr("selected", true);
		}
		
		val_saturday = '{{$data->saturday}}';
		if(val_saturday == 'Closed'){
			$('input[name="Saturday"]').prop("checked", false);
			$('#saturday-1').hide();
			$('#saturday-2').hide();
		}else{
			var arr = val_saturday.split('-');
			$('#saturday-1 option[value="'+ $.trim(arr[0]) +'"]').attr("selected", true);
			$('#saturday-2 option[value="'+ $.trim(arr[1]) +'"]').attr("selected", true);
		}
		
		val_sunday = '{{$data->sunday}}';
		if(val_sunday == 'Closed'){
			$('input[name="Sunday"]').prop("checked", false);
			$('#sunday-1').hide();
			$('#sunday-2').hide();
		}else{
			var arr = val_sunday.split('-');
			$('#sunday-1 option[value="'+ $.trim(arr[0]) +'"]').attr("selected", true);
			$('#sunday-2 option[value="'+ $.trim(arr[1]) +'"]').attr("selected", true);
		}
		
		$('input[name="Monday"]').click(function(){
			if($(this).prop("checked") == true){
				$('#monday-1').show();
				$('#monday-2').show();
			} else if($(this).prop("checked") == false){
				$('#monday-1').hide();
				$('#monday-2').hide();
			}
		});
		$('input[name="Tuesday"]').click(function(){
			if($(this).prop("checked") == true){
				$('#tuesday-1').show();
				$('#tuesday-2').show();
			} else if($(this).prop("checked") == false){
				$('#tuesday-1').hide();
				$('#tuesday-2').hide();
			}
		});
		$('input[name="Wednesday"]').click(function(){
			if($(this).prop("checked") == true){
				$('#wednesday-1').show();
				$('#wednesday-2').show();
			} else if($(this).prop("checked") == false){
				$('#wednesday-1').hide();
				$('#wednesday-2').hide();
			}
		});
		$('input[name="Thursday"]').click(function(){
			if($(this).prop("checked") == true){
				$('#thursday-1').show();
				$('#thursday-2').show();
			} else if($(this).prop("checked") == false){
				$('#thursday-1').hide();
				$('#thursday-2').hide();
			}
		});
		$('input[name="Friday"]').click(function(){
			if($(this).prop("checked") == true){
				$('#friday-1').show();
				$('#friday-2').show();
			} else if($(this).prop("checked") == false){
				$('#friday-1').hide();
				$('#friday-2').hide();
			}
		});
		$('input[name="Saturday"]').click(function(){
			if($(this).prop("checked") == true){
				$('#saturday-1').show();
				$('#saturday-2').show();
			} else if($(this).prop("checked") == false){
				$('#saturday-1').hide();
				$('#saturday-2').hide();
			}
		});
		$('input[name="Sunday"]').click(function(){
			if($(this).prop("checked") == true){
				$('#sunday-1').show();
				$('#sunday-2').show();
			} else if($(this).prop("checked") == false){
				$('#sunday-1').hide();
				$('#sunday-2').hide();
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
	function saveData(){
		var data = new FormData();
		data.append('item_id', '{{$data->id}}');
		data.append('type', '{{$type}}');
		data.append('title', $('#title').val());
		data.append('nearest_stop', $('#nearest_stop').val());
		data.append('address', $('#address').val());
		data.append('website', $('#website').val());
		data.append('contact_number', $('#contact_number').val());
		
		var monday = 'Closed';
		var tuesday = 'Closed';
		var wednesday = 'Closed';
		var thursday = 'Closed';
		var friday = 'Closed';
		var saturday = 'Closed';
		var sunday = 'Closed';
		
		if($('input[name="Monday"]').prop("checked") == true){
			var monday = $('#monday-1').val() +' - '+ $('#monday-2').val();
		}
		if($('input[name="Tuesday"]').prop("checked") == true){
			var tuesday = $('#tuesday-1').val() +' - '+ $('#tuesday-2').val();
		}
		if($('input[name="Wednesday"]').prop("checked") == true){
			var wednesday = $('#wednesday-1').val() +' - '+ $('#wednesday-2').val();
		}
		if($('input[name="Thursday"]').prop("checked") == true){
			var thursday = $('#thursday-1').val() +' - '+ $('#thursday-2').val();
		}
		if($('input[name="Friday"]').prop("checked") == true){
			var friday = $('#friday-1').val() +' - '+ $('#friday-2').val();
		}
		if($('input[name="Saturday"]').prop("checked") == true){
			var saturday = $('#saturday-1').val() +' - '+ $('#saturday-2').val();
		}
		if($('input[name="Sunday"]').prop("checked") == true){
			var sunday = $('#sunday-1').val() +' - '+ $('#sunday-2').val();
		}
		data.append('monday', monday);
		data.append('tuesday', tuesday);
		data.append('wednesday', wednesday);
		data.append('thursday', thursday);
		data.append('friday', friday);
		data.append('saturday', saturday);
		data.append('sunday', sunday);
		
		
		if($('input[name="charges"]').prop("checked") == true){
			data.append('is_charges', 1);
			data.append('charges', $('#charges').val());
		}else{
			data.append('is_charges', 0);
		}
		
		data.append('is_hours', 0);
		if($('input[name="hours"]').prop("checked") == true){
			data.append('is_hours', 1);
		}
	
		data.append('description', $('#description').val());
		data.append('image', $('#image')[0].files[0]);
		data.append('ticket_booking_url', $('#ticket_booking_url').val());
		data.append('latitude', $('#latitude').val());
		data.append('longitude', $('#longitude').val());
		data.append('google_business_url', $('#google_business_url').val());
		data.append('status', $('#status').val());
		var response = adminAjax('{{route("ajax.place.store")}}', data);
		if(response.status == '200'){
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
			//setTimeout(function(){ window.location.reload(); }, 2000)
			
		}else if(response.status == '422'){
			$('.validation-div').text('');
			$.each(response.error, function( index, value ) {
				$('#val-'+index).text(value);
			});
			
		} else if(response.status == '201'){
			swal.fire({title: response.message,type: 'error'});
		}
	}

	// Remove Image
	$('.remove_image_btn').click( () => {
		if(confirm("Are you sure, you want to delete this image")){
			var data = new FormData();
			data.append('id', $('.parent_remove_btn').data('id'));
			var response = adminAjax('{{route("place.remove_image")}}', data);
			if (response.status == '200') {
				swal.fire({type: 'success',title: response.message,showConfirmButton: false,timer: 1500});
				$('.parent_remove_btn').hide();
				$("#image-src").attr("src","")
			}
		}
	});
</script>
@endsection