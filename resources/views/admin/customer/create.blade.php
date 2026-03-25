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
                                        <div id="nominatim-results" class="list-group shadow"
                                             style="display:none; position:absolute; top:100%; left:0; width:100%; z-index:1055; max-height:200px; overflow-y:auto;"></div>
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

{{-- Map Picker Modal --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    .map-picker-close { line-height:1; padding:4px 8px; }
    #map-picker { height:350px; }
    @media (max-width:575.98px) {
        #map-picker { height:220px; }
        #map-search-input { font-size:14px; }
    }
</style>
<div class="modal fade" id="mapPickerModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-2 d-flex align-items-center">
                <h6 class="modal-title mb-0"><i class="fa fa-map-marker me-2 text-danger"></i>Pick Location on Map</h6>
                <button type="button" class="btn btn-sm btn-outline-secondary map-picker-close ms-auto" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="p-3 border-bottom position-relative">
                    <div class="input-group">
                        <input type="text" id="map-search-input" class="form-control" placeholder="Search address on map...">
                        <button class="btn btn-secondary" id="btn-map-search" type="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                    <div id="map-search-results" class="list-group shadow"
                         style="display:none; position:absolute; z-index:9999; left:1rem; right:1rem; max-height:180px; overflow-y:auto;"></div>
                </div>
                <div id="map-picker-loader" class="text-center p-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted small">Getting your location...</p>
                </div>
                <div id="map-picker" style="display:none;"></div>
                <div class="px-3 py-2 bg-light border-top text-muted d-flex justify-content-between align-items-center" style="font-size:12px;">
                    <span><i class="fa fa-hand-pointer-o me-1 text-primary"></i>Tap on map to place marker &mdash; or search above</span>
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

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function() {
    let pickerMap = null, pickerMarker = null, pickerLat = null, pickerLng = null;
    let pickerAddress = '';

    // ─── placePickerMarker at IIFE scope so it's accessible everywhere ──
    function placePickerMarker(lat, lng, reverseGeocode) {
        if (!pickerMap) return;
        pickerLat = lat; pickerLng = lng;
        if (pickerMarker) pickerMap.removeLayer(pickerMarker);
        pickerMarker = L.marker([lat, lng], { draggable: true }).addTo(pickerMap);
        pickerMarker.on('dragend', function(e) {
            const pos = e.target.getLatLng();
            placePickerMarker(pos.lat, pos.lng, true);
        });
        document.getElementById('picker-coord-display').textContent = lat.toFixed(5) + ', ' + lng.toFixed(5);
        document.getElementById('btn-use-location').disabled = false;

        if (reverseGeocode) {
            fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng, {
                headers: { 'Accept-Language': 'en' }
            }).then(r => r.json()).then(data => {
                const full = data.display_name || '';
                pickerAddress = shortAddr(full);
                if (pickerMarker) pickerMarker.bindPopup(full).openPopup();
                document.getElementById('map-search-input').value = pickerAddress;
            }).catch(() => {});
        }
    }

    // ─── Nominatim live search on address input ──────────────────────
    let nominatimTimer;
    document.getElementById('shop_address').addEventListener('input', function() {
        clearTimeout(nominatimTimer);
        const val = this.value.trim();
        if (val.length < 4) { document.getElementById('nominatim-results').style.display = 'none'; return; }
        nominatimTimer = setTimeout(() => nominatimSearch(val, 'nominatim-results', applyNominatimResult), 700);
    });
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#nominatim-results') && e.target.id !== 'shop_address') {
            document.getElementById('nominatim-results').style.display = 'none';
        }
    });

    function nominatimSearch(q, resultsDivId, onSelect) {
        const url = 'https://nominatim.openstreetmap.org/search?format=json&limit=5&addressdetails=1&q=' + encodeURIComponent(q);
        fetch(url, { headers: { 'Accept-Language': 'en' } })
            .then(r => r.json())
            .then(data => {
                const div = document.getElementById(resultsDivId);
                div.innerHTML = '';
                if (!data.length) { div.style.display = 'none'; return; }
                data.forEach(item => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'list-group-item list-group-item-action small py-2';
                    btn.innerHTML = '<i class="fa fa-map-marker text-danger me-2"></i>' + item.display_name;
                    btn.addEventListener('click', () => onSelect(item));
                    div.appendChild(btn);
                });
                div.style.display = 'block';
            }).catch(() => {});
    }

    function shortAddr(displayName) {
        const parts = displayName.split(',').map(s => s.trim()).filter(Boolean);
        return parts.slice(0, 3).join(', ');
    }

    function applyNominatimResult(item) {
        document.getElementById('shop_address').value = shortAddr(item.display_name);
        document.getElementById('latitude').value  = parseFloat(item.lat).toFixed(6);
        document.getElementById('longitude').value = parseFloat(item.lon).toFixed(6);
        const a = item.address || {};
        const city = a.city || a.town || a.village || a.county || '';
        if (city) document.getElementById('city').value = city;
        document.getElementById('nominatim-results').style.display = 'none';
        showLocationBadge();
    }

    function showLocationBadge() {
        document.getElementById('location-set-badge').classList.remove('d-none');
        document.getElementById('btn-clear-location').style.display = '';
    }
    function hideLocationBadge() {
        document.getElementById('location-set-badge').classList.add('d-none');
        document.getElementById('btn-clear-location').style.display = 'none';
    }

    document.getElementById('btn-clear-location').addEventListener('click', function() {
        document.getElementById('latitude').value   = '';
        document.getElementById('longitude').value  = '';
        document.getElementById('shop_address').value = '';
        hideLocationBadge();
    });

    if (document.getElementById('latitude').value && document.getElementById('longitude').value) {
        showLocationBadge();
    }

    // ─── Map Picker Modal ────────────────────────────────────────────
    const mapPickerModal = document.getElementById('mapPickerModal');

    mapPickerModal.addEventListener('shown.bs.modal', function() {
        if (pickerMap) { pickerMap.remove(); pickerMap = null; pickerMarker = null; }

        const existingLat = parseFloat(document.getElementById('latitude').value);
        const existingLng = parseFloat(document.getElementById('longitude').value);
        const hasExisting = !isNaN(existingLat) && !isNaN(existingLng);

        document.getElementById('map-picker-loader').style.display = 'block';
        document.getElementById('map-picker').style.display = 'none';
        document.getElementById('btn-use-location').disabled = !hasExisting;
        document.getElementById('picker-coord-display').textContent = hasExisting
            ? existingLat.toFixed(5) + ', ' + existingLng.toFixed(5) : '';

        if (hasExisting) { pickerLat = existingLat; pickerLng = existingLng; }

        function initPickerMap(lat, lng) {
            pickerMap = L.map('map-picker').setView([lat, lng], hasExisting ? 16 : 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(pickerMap);

            if (hasExisting) placePickerMarker(existingLat, existingLng, false);

            pickerMap.on('click', function(e) {
                placePickerMarker(e.latlng.lat, e.latlng.lng, true);
            });

            pickerMap.whenReady(function() {
                document.getElementById('map-picker-loader').style.display = 'none';
                document.getElementById('map-picker').style.display = 'block';
                pickerMap.invalidateSize();
            });
        }

        if (hasExisting) {
            initPickerMap(existingLat, existingLng);
        } else if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                pos => initPickerMap(pos.coords.latitude, pos.coords.longitude),
                ()  => initPickerMap(23.0225, 72.5714),
                { enableHighAccuracy: true, timeout: 8000, maximumAge: 0 }
            );
        } else {
            initPickerMap(23.0225, 72.5714);
        }
    });

    mapPickerModal.addEventListener('hidden.bs.modal', function() {
        if (pickerMap) { pickerMap.remove(); pickerMap = null; pickerMarker = null; }
        pickerLat = null; pickerLng = null; pickerAddress = '';
        document.getElementById('map-search-results').style.display = 'none';
    });

    // ─── Search inside map modal ─────────────────────────────────────
    document.getElementById('btn-map-search').addEventListener('click', function() {
        const q = document.getElementById('map-search-input').value.trim();
        if (!q) return;
        nominatimSearch(q, 'map-search-results', function(item) {
            const lat = parseFloat(item.lat), lng = parseFloat(item.lon);
            pickerAddress = item.display_name;
            document.getElementById('map-search-input').value = item.display_name;
            document.getElementById('map-search-results').style.display = 'none';
            if (pickerMap) pickerMap.setView([lat, lng], 16);
            placePickerMarker(lat, lng, false); // direct call — no synthetic fire needed
        });
    });
    document.getElementById('map-search-input').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') { e.preventDefault(); document.getElementById('btn-map-search').click(); }
    });

    // ─── Use location ────────────────────────────────────────────────
    document.getElementById('btn-use-location').addEventListener('click', function() {
        if (pickerLat === null || pickerLng === null) return;
        document.getElementById('latitude').value  = pickerLat.toFixed(6);
        document.getElementById('longitude').value = pickerLng.toFixed(6);
        if (pickerAddress) document.getElementById('shop_address').value = pickerAddress;
        showLocationBadge();
        bootstrap.Modal.getInstance(mapPickerModal).hide();
    });
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
