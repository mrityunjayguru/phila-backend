function initMap(currentlat = 39.966, currentlng = -75.142, is_load = 0) {
	
	if(is_load == 0){ return false; }
	if(currentlat == ''){ currentlat = '39.966'} //Philadelphia
	if(currentlng == ''){ currentlng = '-75.142'} //Philadelphia
	myLatLng = { lat: parseFloat(currentlat), lng: parseFloat(currentlng) };
	// const myLatLng = { lat: $latitude, lng: $longitude }; //Philadelphia
    const map = new google.maps.Map(document.getElementById("map_canvas"), {
      zoom: 10,
      center: myLatLng,
      mapTypeId: "roadmap",
    });

    var marker = new google.maps.Marker({
      position: myLatLng,
      map,
      draggable:true,
      title: "",
    });

    google.maps.event.addListener(marker, 'dragend', function(evt){
      $('#latitude').val(evt.latLng.lat().toFixed(6));
      $('#longitude').val(evt.latLng.lng().toFixed(6));
    });

    var searchBox = new google.maps.places.SearchBox(document.getElementById('pac-input'));
	map.controls[google.maps.ControlPosition.TOP_CENTER].push(document.getElementById('pac-input'));
	google.maps.event.addListener(searchBox, 'places_changed', function() {
		searchBox.set('map_canvas', null);

		var places = searchBox.getPlaces();

		var bounds = new google.maps.LatLngBounds();
		var i, place;
		for (i = 0; place = places[i]; i++) {
		   (function(place) {
			 var marker = new google.maps.Marker({
				draggable: true,
			   position: place.geometry.location

			 });
			 marker.bindTo('map_canvas', searchBox, 'map_canvas');
			 google.maps.event.addListener(marker, 'map_changed', function(evt) {
			   if (!this.getMap()) {
				 this.unbindAll();
			   }
			   
			 });
			 google.maps.event.addListener(marker, 'dragend', function(evt){
				$('#latitude').val(evt.latLng.lat().toFixed(6));
				$('#longitude').val(evt.latLng.lng().toFixed(6));
			});
			 bounds.extend(place.geometry.location);
		   }(place));

		}
     map.fitBounds(bounds);
     $('#latitude').val(bounds.Ab.g.toFixed(6));
     $('#longitude').val(bounds.Ra.g.toFixed(6));
    
     searchBox.set('map_canvas', map);
     map.setZoom(Math.min(map.getZoom(),10));
   });

   
}


