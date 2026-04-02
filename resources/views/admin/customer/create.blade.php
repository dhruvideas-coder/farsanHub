@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">
                {{ @trans('portal.customers') . ' ' . @trans('portal.add') }}
            </h1>
        </div>
        <div class="ms-auto pageheader-btn d-none d-xl-flex d-lg-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.customer.index') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ @trans('portal.customers') }}</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <form action="{{ route('admin.customer.store') }}" method="POST" enctype="multipart/form-data" id="customer-form">
                            @csrf
                            @method('POST')
                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label for="customer_name" class="form-label">{{ @trans('portal.customer_name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('customer_name') is-invalid @enderror"
                                        id="customer_name" name="customer_name" value="{{ old('customer_name') }}">
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="shop_name" class="form-label">{{ @trans('portal.shop_name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('shop_name') is-invalid @enderror"
                                        id="shop_name" name="shop_name" value="{{ old('shop_name') }}">
                                    @error('shop_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Address with Leaflet map picker --}}
                                <div class="col-md-6 mb-3">
                                    <label for="shop_address" class="form-label">
                                        {{ @trans('portal.shop_address') }} <span class="text-danger">*</span>
                                    </label>
                                    {{-- Full-width input; dropdown anchors to this wrapper --}}
                                    <div class="position-relative">
                                        <input type="text" class="form-control @error('shop_address') is-invalid @enderror"
                                            id="shop_address" name="shop_address"
                                            placeholder="Type to search address..."
                                            value="{{ old('shop_address') }}" autocomplete="off">
                                    </div>
                                    {{-- Action row: [Pick on Map + badge] LEFT · [Clear] RIGHT --}}
                                    <div class="d-flex align-items-center justify-content-between mt-2 gap-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-secondary btn-sm" id="btn-pick-map"
                                                    data-bs-toggle="modal" data-bs-target="#mapPickerModal">
                                                <i class="fa fa-map-marker me-1"></i>Pick on Map
                                            </button>
                                            <span id="location-set-badge" class="badge bg-success d-none">
                                                <i class="fa fa-check me-1"></i>Location set
                                            </span>
                                        </div>
                                        <button type="button" class="text-danger border border-danger rounded"
                                                id="btn-clear-location" style="display:none;">
                                            <i class="fa fa-times"></i><span class="d-none d-sm-inline ms-1">Clear</span>
                                        </button>
                                    </div>
                                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                                    @error('shop_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="customer_number" class="form-label">{{ @trans('portal.mobile') }} <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control @error('customer_number') is-invalid @enderror"
                                        id="customer_number" name="customer_number"
                                        placeholder="XXXXXXXXXX"
                                        maxlength="10" inputmode="numeric" pattern="[0-9]{10}"
                                        value="{{ old('customer_number') }}">
                                    <small class="form-text text-muted">Enter 10-digit mobile number</small>
                                    @error('customer_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="customer_email" class="form-label">{{ @trans('portal.customer_email') }} <small class="text-muted fw-normal">(Optional)</small></label>
                                    <input type="email" class="form-control @error('customer_email') is-invalid @enderror"
                                        id="customer_email" name="customer_email" value="{{ old('customer_email') }}">
                                    @error('customer_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <input type="hidden" name="status" value="Active">

                                <div class="col-md-6 mb-3">
                                    <label for="city" class="form-label">{{ @trans('portal.city') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                        id="city" name="city" value="{{ old('city') }}">
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="customer_image" class="form-label">{{ @trans('portal.customer_image') }}</label>
                                    <input type="file" class="form-control @error('customer_image') is-invalid @enderror"
                                        id="customer_image" name="customer_image" accept="image/*"
                                        onchange="previewImage(this, 'customer_image_preview')">
                                    @error('customer_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ @trans('portal.accepted_formats') }}</small>
                                    <div class="mt-2">
                                        <img id="customer_image_preview" src="#" alt="Preview" class="img-thumbnail d-none" style="max-width:200px;">
                                    </div>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="shop_image" class="form-label">{{ @trans('portal.shop_image') }}</label>
                                    <input type="file" class="form-control @error('shop_image') is-invalid @enderror"
                                        id="shop_image" name="shop_image" accept="image/*"
                                        onchange="previewImage(this, 'shop_image_preview')">
                                    @error('shop_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ @trans('portal.accepted_formats') }}</small>
                                    <div class="mt-2">
                                        <img id="shop_image_preview" src="#" alt="Preview" class="img-thumbnail d-none" style="max-width:200px;">
                                    </div>
                                </div>

                                <div class="mb-3 d-flex flex-wrap gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save"></i> {{ @trans('portal.save') }}
                                    </button>
                                    <a href="{{ route('admin.customer.index') }}" class="btn btn-secondary">
                                        <i class="fa fa-arrow-left"></i> {{ @trans('portal.cancel') }}
                                    </a>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{-- Google Maps script with Places library --}}
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places"></script>

<style>
    .map-picker-close { line-height:1; padding:4px 8px; }
    #map-picker { height:400px; width: 100%; }
    .pac-container { z-index: 10000 !important; } {{-- Ensure autocomplete dropdown is above modal --}}
    @media (max-width:575.98px) {
        #map-picker { height:300px; }
    }
</style>

{{-- Map Picker Modal --}}
<div class="modal fade" id="mapPickerModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-2 d-flex align-items-center">
                <h6 class="modal-title mb-0"><i class="fa fa-map-marker me-2 text-danger"></i>Pick Location on Map</h6>
                <button type="button" class="btn btn-sm btn-info me-2 ms-auto d-flex align-items-center gap-1" id="btn-get-current-location">
                    <i class="fa fa-crosshairs"></i> My Location
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary map-picker-close" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="p-3 border-bottom position-relative">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fa fa-search text-muted"></i></span>
                        <input type="text" id="map-search-input" class="form-control border-start-0" placeholder="Search address on map...">
                    </div>
                </div>
                <div id="map-picker-loader" class="text-center p-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted small">Loading Map...</p>
                </div>
                <div id="map-picker" style="display:none;"></div>
                <div class="px-3 py-2 bg-light border-top text-muted d-flex justify-content-between align-items-center" style="font-size:12px;">
                    <span><i class="fa fa-hand-pointer-o me-1 text-primary"></i>Drag marker to adjust &mdash; or search above</span>
                    <span id="picker-coord-display" class="text-success fw-bold"></span>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" id="btn-use-location" disabled>
                    <i class="fa fa-check me-1"></i>Use this location
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    let map, marker, autocomplete, mapAutocomplete;
    let selectedLat = null, selectedLng = null, selectedAddress = '';

    // Initialize the Address Autocomplete for the main form input
    function initMainAutocomplete() {
        const addressInput = document.getElementById('shop_address');
        if (!addressInput) return;

        autocomplete = new google.maps.places.Autocomplete(addressInput, {
            componentRestrictions: { country: 'in' },
            fields: ['address_components', 'geometry', 'formatted_address']
        });

        autocomplete.addListener('place_changed', function() {
            const place = autocomplete.getPlace();
            if (!place.geometry) {
                return;
            }
            
            applyLocationData(place);
        });

        // Prevent form submission on enter (common issue with autocomplete)
        addressInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                const containers = document.querySelectorAll('.pac-container');
                let visible = false;
                containers.forEach(container => {
                    if (container.offsetParent !== null) visible = true;
                });
                if (visible) {
                    e.preventDefault();
                }
            }
        });
    }

    function applyLocationData(place) {
        document.getElementById('shop_address').value = place.formatted_address;
        document.getElementById('latitude').value = place.geometry.location.lat().toFixed(6);
        document.getElementById('longitude').value = place.geometry.location.lng().toFixed(6);
        
        // Extract city if possible
        let city = '';
        for (const component of place.address_components) {
            const types = component.types;
            if (types.includes('locality')) {
                city = component.long_name;
                break;
            } else if (types.includes('administrative_area_level_2')) {
                city = component.long_name;
            }
        }
        if (city) {
            document.getElementById('city').value = city;
        }

        showLocationBadge();
    }

    function showLocationBadge() {
        document.getElementById('location-set-badge').classList.remove('d-none');
        document.getElementById('btn-clear-location').style.display = 'block';
    }

    function hideLocationBadge() {
        document.getElementById('location-set-badge').classList.add('d-none');
        document.getElementById('btn-clear-location').style.display = 'none';
    }

    document.getElementById('btn-clear-location').addEventListener('click', function() {
        document.getElementById('latitude').value = '';
        document.getElementById('longitude').value = '';
        document.getElementById('shop_address').value = '';
        hideLocationBadge();
    });

    // Initialize Map Picker
    const mapPickerModal = document.getElementById('mapPickerModal');
    
    mapPickerModal.addEventListener('shown.bs.modal', function() {
        const existingLat = parseFloat(document.getElementById('latitude').value);
        const existingLng = parseFloat(document.getElementById('longitude').value);
        const hasExisting = !isNaN(existingLat) && !isNaN(existingLng);

        let center = { lat: 23.0225, lng: 72.5714 }; // Default to Ahmedabad
        if (hasExisting) {
            center = { lat: existingLat, lng: existingLng };
        }

        document.getElementById('map-picker-loader').style.display = 'none';
        document.getElementById('map-picker').style.display = 'block';

        if (!map) {
            map = new google.maps.Map(document.getElementById('map-picker'), {
                zoom: 15,
                center: center,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: false
            });

            marker = new google.maps.Marker({
                position: center,
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP
            });

            // Sync marker position with coordinates display
            const updateCoords = () => {
                const pos = marker.getPosition();
                selectedLat = pos.lat();
                selectedLng = pos.lng();
                document.getElementById('picker-coord-display').textContent = 
                    selectedLat.toFixed(5) + ', ' + selectedLng.toFixed(5);
                document.getElementById('btn-use-location').disabled = false;
            };

            marker.addListener('dragend', updateCoords);
            map.addListener('click', (e) => {
                marker.setPosition(e.latLng);
                updateCoords();
            });

            // Autocomplete for map modal
            const mapSearchInput = document.getElementById('map-search-input');
            mapAutocomplete = new google.maps.places.Autocomplete(mapSearchInput, {
                componentRestrictions: { country: 'in' },
                fields: ['geometry', 'formatted_address']
            });

            mapAutocomplete.addListener('place_changed', function() {
                const place = mapAutocomplete.getPlace();
                if (!place.geometry) return;

                map.setCenter(place.geometry.location);
                marker.setPosition(place.geometry.location);
                selectedLat = place.geometry.location.lat();
                selectedLng = place.geometry.location.lng();
                selectedAddress = place.formatted_address;
                updateCoords();
            });

            if (hasExisting) {
                updateCoords();
            } else {
                // Try to get current location automatically if no existing coordinates
                getCurrentLocation();
            }
        } else {
            if (hasExisting) {
                map.setCenter(center);
                marker.setPosition(center);
                updateCoords();
            } else {
                getCurrentLocation();
            }
        }
    });

    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    if (map && marker) {
                        map.setCenter(pos);
                        marker.setPosition(pos);
                        const posLat = pos.lat;
                        const posLng = pos.lng;
                        selectedLat = posLat;
                        selectedLng = posLng;
                        document.getElementById('picker-coord-display').textContent = 
                            selectedLat.toFixed(5) + ', ' + selectedLng.toFixed(5);
                        document.getElementById('btn-use-location').disabled = false;
                        
                        // Clear any previous selection that was from autocomplete
                        selectedAddress = '';
                    }
                },
                () => {
                    console.log("Geolocation service failed.");
                }
            );
        }
    }

    document.getElementById('btn-get-current-location').addEventListener('click', getCurrentLocation);

    document.getElementById('btn-use-location').addEventListener('click', function() {
        if (selectedLat === null || selectedLng === null) return;
        
        document.getElementById('latitude').value = selectedLat.toFixed(6);
        document.getElementById('longitude').value = selectedLng.toFixed(6);
        
        // Use geocoder to get address if it wasn't selected from autocomplete
        if (!selectedAddress || selectedAddress === '') {
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ location: { lat: selectedLat, lng: selectedLng } }, (results, status) => {
                if (status === 'OK' && results[0]) {
                    document.getElementById('shop_address').value = results[0].formatted_address;
                    // Extract city
                    for (const component of results[0].address_components) {
                        if (component.types.includes('locality')) {
                            document.getElementById('city').value = component.long_name;
                            break;
                        }
                    }
                }
            });
        } else {
            document.getElementById('shop_address').value = selectedAddress;
        }

        showLocationBadge();
        bootstrap.Modal.getInstance(mapPickerModal).hide();
    });

    // Safe initialization wrapper
    function safeInit() {
        if (typeof google === 'object' && typeof google.maps === 'object' && typeof google.maps.places === 'object') {
            initMainAutocomplete();
            if (document.getElementById('latitude').value && document.getElementById('longitude').value) {
                showLocationBadge();
            }
        } else {
            setTimeout(safeInit, 200);
        }
    }
    
    // Run after DOM is fully loaded and all scripts are processed
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', safeInit);
    } else {
        safeInit();
    }
})();
</script>


<script>
function previewImage(input, previewId) {
    var preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<script>
(function () {
    var phoneInput = document.getElementById('customer_number');

    // Strip non-digits and cap at 10 on every keystroke
    phoneInput.addEventListener('input', function () {
        var digits = this.value.replace(/\D/g, '').slice(0, 10);
        this.value = digits;
    });

    // Clean up on paste
    phoneInput.addEventListener('paste', function (e) {
        e.preventDefault();
        var pasted = (e.clipboardData || window.clipboardData).getData('text');
        this.value = pasted.replace(/\D/g, '').slice(0, 10);
    });

    // Prevent non-digit keys (allow control keys and Ctrl/Cmd combos)
    phoneInput.addEventListener('keydown', function (e) {
        if (e.ctrlKey || e.metaKey) return; // allow Ctrl+V, Ctrl+A, Ctrl+C, etc.
        var allowed = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'Home', 'End'];
        if (allowed.indexOf(e.key) === -1 && !/^[0-9]$/.test(e.key)) {
            e.preventDefault();
        }
    });
})();
</script>
@endsection
