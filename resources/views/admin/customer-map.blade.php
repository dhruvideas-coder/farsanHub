<!DOCTYPE html>
<html>
<head>
    <title>{{ config('app.name', 'FarsanHub') }} - Customer Locations</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    
    <style>
        body, html { margin: 0; padding: 0; height: 100%; width: 100%; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        #map { height: 100vh; width: 100%; }
        .custom-info-window { padding: 10px; max-width: 200px; }
        .custom-info-window h3 { margin: 0 0 5px 0; font-size: 16px; color: #333; }
        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 1000;
            background: white;
            padding: 10px 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            text-decoration: none;
            color: #333;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        .back-btn:hover { background: #f8f9fa; transform: translateY(-2px); }
        .location-btn {
            position: absolute;
            bottom: 30px;
            right: 20px;
            z-index: 1000;
            background: #4285F4;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .location-btn:hover { background: #3367D6; transform: scale(1.1); }
        .location-btn:active { transform: scale(0.95); }
    </style>
</head>
<body>

    <a href="{{ route('admin.dashboard') }}" class="back-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Back to Dashboard
    </a>

    <div id="map"></div>
    <button class="location-btn" id="btn-my-location" title="My Location">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M12 2v3M12 19v3M2 12h3M19 12h3"></path></svg>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}"></script>
    <script>
        function initMap() {
            const locations = @json($locations);
            
            // Default center (or center on first location if exists)
            const mapCenter = locations.length > 0 
                ? { lat: locations[0].lat, lng: locations[0].lng } 
                : { lat: 23.0225, lng: 72.5714 }; // Default to Ahmedabad

            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: mapCenter,
                mapTypeControl: true,
                streetViewControl: true,
                fullscreenControl: true,
                styles: [
                    {
                        "featureType": "poi.business",
                        "stylers": [{ "visibility": "off" }]
                    },
                    {
                        "featureType": "transit",
                        "elementType": "labels.icon",
                        "stylers": [{ "visibility": "off" }]
                    }
                ]
            });

            const infoWindow = new google.maps.InfoWindow();
            const bounds = new google.maps.LatLngBounds();

            locations.forEach(location => {
                const marker = new google.maps.Marker({
                    position: { lat: location.lat, lng: location.lng },
                    map: map,
                    title: location.label,
                    animation: google.maps.Animation.DROP
                });

                bounds.extend(marker.getPosition());

                marker.addListener('click', () => {
                    infoWindow.setContent(`
                        <div class="custom-info-window">
                            <h3>${location.label}</h3>
                            <p>Customer Location</p>
                            <a href="https://www.google.com/maps/dir/?api=1&destination=${location.lat},${location.lng}" target="_blank" style="color: #4285F4; text-decoration: none; font-weight: bold;">Get Directions</a>
                        </div>
                    `);
                    infoWindow.open(map, marker);
                });
            });

            if (locations.length > 1) {
                map.fitBounds(bounds);
            } else if (locations.length === 1) {
                map.setCenter(mapCenter);
                map.setZoom(15);
            }

            // Current Location logic
            let userMarker = null;

            function showMyLocation() {
                if (!navigator.geolocation) {
                    Swal.fire({ icon: 'error', title: 'Oops...', text: 'Geolocation is not supported or requires https.' });
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const pos = { lat: position.coords.latitude, lng: position.coords.longitude };
                        if (!userMarker) {
                            userMarker = new google.maps.Marker({
                                map: map,
                                icon: {
                                    path: google.maps.SymbolPath.CIRCLE,
                                    scale: 8,
                                    fillColor: "#4285F4",
                                    fillOpacity: 1,
                                    strokeColor: "white",
                                    strokeWeight: 2,
                                },
                                title: "Your Location"
                            });
                        }
                        userMarker.setPosition(pos);
                        map.panTo(pos);
                        map.setZoom(15);
                    },
                    (error) => {
                        let msg = 'Unable to get location.';
                        if (error.code === 1) msg = 'Please enable location permission.';
                        Swal.fire({ icon: 'warning', title: 'Location Error', text: msg });
                    },
                    { enableHighAccuracy: true, timeout: 10000 }
                );
            }

            document.getElementById('btn-my-location').addEventListener('click', showMyLocation);

            // Auto-check on load (silent fail if no permission)
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const pos = { lat: position.coords.latitude, lng: position.coords.longitude };
                        userMarker = new google.maps.Marker({
                            position: pos,
                            map: map,
                            icon: {
                                path: google.maps.SymbolPath.CIRCLE,
                                scale: 8,
                                fillColor: "#4285F4",
                                fillOpacity: 1,
                                strokeColor: "white",
                                strokeWeight: 2,
                            }
                        });
                    },
                    null,
                    { enableHighAccuracy: true, timeout: 5000 }
                );
            }
        }

        google.maps.event.addDomListener(window, 'load', initMap);
    </script>
</body>
</html>
