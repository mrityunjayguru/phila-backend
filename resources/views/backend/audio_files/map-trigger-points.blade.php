<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trigger Points Map</title>
    <!-- Add Leaflet CSS and JS -->
    {{-- <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script> --}}
    <script src="{{ asset('adminAssets/scripts/jquery-3.5.0.min.js') }}"></script>
    <script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key={{ Settings::get('google_map_api_key') }}&callback=map_init&v=3.exp&sensor=false&libraries=geometry">
    </script>

    <style>
        #map {
            height: 100vh;
        }
    </style>
</head>

<body>
    <div id="map"></div>
    {{-- <script>
        // Initialize the map
        var map = L.map('map').setView([0, 0], 2);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        // Add trigger points to the map
        var triggerPoints = @json($triggerPoints);

        triggerPoints.forEach(function(point) {
            L.marker([point.latitude, point.longitude])
                .addTo(map)
                .bindPopup(point.title || 'No description');
        });
    </script> --}}
    <script>
        // Initialize the Google Map
        var map;
        var markerStore = {}; // Optional: Store markers if needed later
        window.onload = function InitializeMap() {
            // Map options
            var myOptions = {
                zoom: 13,
                center: new google.maps.LatLng(39.95774, -75.17245), // Default center
                mapTypeId: google.maps.MapTypeId.ROADMAP,
            };


            // Create the map instance
            map = new google.maps.Map(document.getElementById("map"), myOptions);


            // Trigger points
            var triggerPoints = @json($triggerPoints); // Replace with your server-side data


            // Add markers to the map
            triggerPoints.forEach(function(point) {
                var position = new google.maps.LatLng(point.latitude, point.longitude);
                var customIcon = {
                    // url: "http://127.0.0.1:8000/uploads/Audio.png",
                    url: "https://citysightseeingphila.com/uploads/Audio.png",
                    scaledSize: new google.maps.Size(30, 30),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(15, 30),
                }

                var marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    title: point.title || "No description",
                    icon: customIcon
                });


                // Add an info window for the marker
                var infoWindow = new google.maps.InfoWindow({
                    content: point.title || "No description",
                });


                marker.addListener("click", function() {
                    infoWindow.open(map, marker);
                });


                // Store marker if needed later
                markerStore[point.id] = marker;
            });
        };
    </script>

</body>

</html>
