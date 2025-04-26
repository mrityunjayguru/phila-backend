@extends('layouts.theme.master')

@section('css')
	<style>#nnmymap{width: 100%; height: 720px;}</style>
	<style>html, body, .ps-page, #mymap {width: 100%; height: 100%; margin: 0px;}</style>
@endsection

@section('content')
	<div id="mymap"></div>
	<div id="info"></div>
@endsection

@section('js')
	<!-- jQuery -->
	<script src="{{asset('adminAssets/scripts/jquery-3.5.0.min.js')}}"></script>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{Settings::get('google_map_api_key')}}&callback=map_init&v=3.exp&sensor=false&libraries=geometry"></script>
	
	<script>
		$(document).ready(function(e) {
			
		});
		
		var markerStore = {};
		
		// Initialize Google Map
		window.onload = InitializeMap;
		
		var myOptions = {
			zoom: 14,
			center: new google.maps.LatLng(39.95774, -75.17245),
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var map = new google.maps.Map(document.getElementById("mymap"), myOptions);
		
		
		function InitializeMap() {
			// drow line
			var toorRoutes = [];
			var data = new FormData();
			data.append('type', 'tour');
			var roots = runAjax(SITE_URL +'/roots', data);
			if(roots.status == '200'){
				if(roots.data.length > 0){
					$.each(roots.data, function( index, value ) {
						toorRoutes.push(new google.maps.LatLng(value.latitude, value.longitude));
					});
				}
			}
			
			var polylineOptions = {path: toorRoutes, strokeColor:"#ff0000", strokeWeight:5};
			var polyline = new google.maps.Polyline(polylineOptions);
			polyline.setMap(map);
			
			// drow line for Fairmount Park Loop
			var fairmountParkLoop = [];
			var data = new FormData();
			var roots = runAjax(SITE_URL +'/roots', data);
			if(roots.status == '200'){
				if(roots.data.length > 0){
					$.each(roots.data, function( index, value ) {
						fairmountParkLoop.push(new google.maps.LatLng(value.latitude, value.longitude));
					});
				}
			}
			
			var polylineOptions = {path: fairmountParkLoop, strokeColor:"#3f6ad8", strokeWeight:5};
			var polyline = new google.maps.Polyline(polylineOptions);
			polyline.setMap(map);
			
			
			// Create Stops.
			var data = new FormData();
			var response = runAjax(SITE_URL +'/stps', data);
			if(response.status == '200'){
				if(response.data.length > 0){
					$.each(response.data, function( index, value ) {
						const marker = new google.maps.Marker({
						  position: new google.maps.LatLng(value.latitude,value.longitude),
						  icon: 'https://citysightseeingphila.com/default/stops/Stop-'+ value.priority +'.svg',
						  map: map,
						  title: value.title,
						});
					});
				}
			}
		}
		
		setInterval(updateMarker,3000);
		function updateMarker() {
			var data = new FormData();
			data.append('device_type', 'Bus,Trolley,Trolley-Blue-Route,Shuttle');
			var response = runAjax(SITE_URL +'/trckbs', data);
			if(response.status == '200'){
				if(response.data.length > 0){
					$.each(response.data, function( index, value ) {
						if(markerStore.hasOwnProperty(value.id)) {
							markerStore[value.id].setPosition(new google.maps.LatLng(value.latitude,value.longitude));
						}else{
							var marker = new google.maps.Marker({
								position: new google.maps.LatLng(value.latitude,value.longitude),
								title:value.title,
								map:map,
								icon: 'https://citysightseeingphila.com/default/'+ value.device_type +'-Icon.png',
							}); 
							markerStore[value.id] = marker;
							
							// Show Title
							var infowindow = new google.maps.InfoWindow({
								content: '<span>'+ value.title +'</span>'
							});
							google.maps.event.addListener(marker, 'click', function() { infowindow.open(map,marker); });
							//google.maps.event.trigger(marker, 'click');
						}
					});
				}
			}
		}
	</script>
@endsection
