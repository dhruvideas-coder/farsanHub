@extends('layouts.app')

<style>
    .customer-card {
        position: relative;
        border-radius: 16px;
        padding: 1rem;
        background: #fff;
        box-shadow: 0 0 0 2px rgba(255, 0, 0, 0.1);
        background-clip: padding-box;
        transition: transform 0.2s ease, box-shadow 0.3s ease;
        border: 2px solid transparent;
        background-origin: border-box;
        background-image: linear-gradient(#fff, #fff), linear-gradient(45deg, #ffcccc, #ff9999);
        background-clip: padding-box, border-box;
    }
    .customer-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(253, 13, 13, 0.4); }
    .customer-card .card-body img { object-fit: cover; border: 3px solid #dee2e6; border-radius: 8px; }
    .customer-card ul li { margin-bottom: 6px; }
    .clickable-image { cursor: pointer; }
    @media (max-width: 767.98px) {
        .filter-bar > select,
        .filter-bar > input { width: 100% !important; flex: 0 0 100% !important; }
    }
</style>

@section('content')
<div class="page-header d-flex flex-wrap justify-content-between align-items-center my-0">
    <div><h1 class="page-title">{{ @trans('portal.customers') }}</h1></div>
    <div class="ms-auto pageheader-btn d-none d-xl-flex d-lg-flex mt-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('login') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ @trans('portal.customers') }}</li>
        </ol>
    </div>
    <div class="ms-auto pageheader-btn d-flex d-md-none mt-0">
        <a href="{{ route('admin.customer.create') }}" class="btn btn-secondary me-2">
            <span class="d-none d-sm-inline">{{ @trans('portal.add') }}</span> <i class="fa fa-plus"></i>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card overflow-hidden customers">
            <div class="p-4 card-body">
                <div class="d-flex flex-wrap flex-lg-nowrap gap-2 align-items-center mb-3 filter-bar">
                    <select id="selected_data" onchange="reloadTable()" class="form-select flex-shrink-0" style="width:80px;">
                        <option value="4">4</option>
                        <option value="8" selected>8</option>
                        <option value="16">16</option>
                        <option value="24">24</option>
                        <option value="32">32</option>
                    </select>
                    <input type="text" name="search" class="form-control" id="search-val"
                           onkeyup="reloadTable()" style="flex:1 1 0; min-width:0;"
                           @if (empty($search)) placeholder="Search..."
                           @else value="{{ $search }}" @endif>
                    <a href="{{ route('admin.customer.create') }}" class="btn btn-secondary flex-shrink-0 d-none d-md-flex align-items-center gap-1">
                        <i class="fa fa-plus"></i> <span>{{ @trans('portal.add') }}</span>
                    </a>
                </div>

                <div id="customer-cards" class="mt-4">
                   @include('admin.customer.view')
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Share Modal --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<div class="modal fade" id="shareModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white py-2">
                <h6 class="modal-title mb-0"><i class="fa fa-share-alt me-2"></i>Share Customer Details</h6>
                <button type="button" class="btn btn-sm ms-auto p-0 lh-1 text-white" data-bs-dismiss="modal"
                        style="background:none; border:none; font-size:18px;" aria-label="Close">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body p-0">
                {{-- Map --}}
                <div id="share-map-wrapper">
                    <div id="share-map-loader" class="text-center p-3" style="display:none;">
                        <div class="spinner-border text-success spinner-border-sm" role="status"></div>
                        <small class="ms-2 text-muted">Loading map...</small>
                    </div>
                    <div id="share-map" style="height:220px; border-radius:0;"></div>
                    <div id="share-map-nocoord" class="text-center text-muted py-3 small d-none">
                        <i class="fa fa-map-o me-1"></i> No location set for this customer
                    </div>
                </div>
                {{-- Customer Info --}}
                <div class="p-3">
                    <h6 id="share-shop-name" class="text-danger fw-bold mb-1"></h6>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li class="mb-1"><i class="fa fa-user me-2 text-primary"></i><span id="share-owner-name"></span></li>
                        <li class="mb-1" id="share-addr-row"><i class="fa fa-map-marker me-2 text-danger"></i><span id="share-address"></span></li>
                        <li id="share-phone-row"><i class="fa fa-phone me-2 text-success"></i><span id="share-phone"></span></li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer pt-2 gap-2 flex-wrap justify-content-center">
                <a id="btn-open-maps" href="#" target="_blank" class="btn btn-danger btn-sm d-none">
                    <i class="fa fa-map-marker me-1"></i>Open Maps
                </a>
                <button class="btn btn-success btn-sm" id="btn-whatsapp-share">
                    <i class="fa fa-whatsapp me-1"></i>WhatsApp
                </button>
                <button class="btn btn-secondary btn-sm" id="btn-copy-details">
                    <i class="fa fa-copy me-1"></i>Copy
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="user-delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete customer</h5>
            </div>
            <form action="{{ route('admin.customer.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <input type="hidden" name="customer_id" id="customer_id" value="">
                    <span>Do you want to Delete this record?</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cancel</button>
                    <input type="submit" class="btn btn-primary" value="Confirm">
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Image Modal --}}
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body d-flex justify-content-center">
                <img src="" alt="Large View" id="popupImage" class="img-fluid">
                <button type="button" class="btn-close border" data-bs-dismiss="modal" aria-label="Close">
                    <span><i class="fa fa-close" style="color:red"></i></span>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if (session()->has('success'))
<script>
    Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3500 })
        .fire({ icon: 'success', title: {!! json_encode(session('success')) !!} });
</script>
@endif
@if (session()->has('error'))
<script>
    Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3500 })
        .fire({ icon: 'error', title: {!! json_encode(session('error')) !!} });
</script>
@endif

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $('.close-btn').click(function() { $('.modal').modal('hide'); });
    $('.user-delete-btn').click(function() { $('#customer_id').val($(this).data('customer-id')); });
    $(document).on('click', '.clickable-image', function() {
        $('#popupImage').attr('src', $(this).data('image'));
        $('#imageModal').modal('show');
    });

    // ─── Share modal ──────────────────────────────────────────────
    let shareMap = null;

    $(document).on('click', '.share-customer-btn', function() {
        const d = $(this).data();
        $('#share-shop-name').text(d.shop || d.name || '-');
        $('#share-owner-name').text(d.name || '-');
        $('#share-address').text([d.address, d.city].filter(Boolean).join(', ') || '-');
        $('#share-phone').text(d.phone ? '+91 ' + String(d.phone).replace(/(\d{5})(\d{5})/, '$1 $2') : '-');

        const lat = parseFloat(d.lat), lng = parseFloat(d.lng);
        const hasCoord = !isNaN(lat) && !isNaN(lng);

        $('#share-map').toggle(hasCoord);
        $('#share-map-nocoord').toggleClass('d-none', hasCoord);
        if (hasCoord) {
            $('#btn-open-maps').attr('href', 'https://maps.google.com/?q=' + lat + ',' + lng).removeClass('d-none');
        } else {
            $('#btn-open-maps').addClass('d-none');
        }

        // Store data on modal for share buttons
        $('#shareModal').data('share', d);
        $('#shareModal').modal('show');
    });

    $('#shareModal').on('shown.bs.modal', function() {
        const d = $(this).data('share');
        const lat = parseFloat(d.lat), lng = parseFloat(d.lng);
        const hasCoord = !isNaN(lat) && !isNaN(lng);

        if (hasCoord) {
            if (shareMap) { shareMap.remove(); shareMap = null; }
            shareMap = L.map('share-map').setView([lat, lng], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(shareMap);
            L.marker([lat, lng])
                .addTo(shareMap)
                .bindPopup('<b>' + (d.shop || d.name || '') + '</b><br>' + (d.address || ''))
                .openPopup();
            shareMap.invalidateSize();
        }
    });

    $('#shareModal').on('hidden.bs.modal', function() {
        if (shareMap) { shareMap.remove(); shareMap = null; }
    });

    $('#btn-whatsapp-share').on('click', function() {
        const d = $('#shareModal').data('share');
        const lat = parseFloat(d.lat), lng = parseFloat(d.lng);
        const hasCoord = !isNaN(lat) && !isNaN(lng);
        const addr = [d.address, d.city].filter(Boolean).join(', ') || '-';
        const phone = d.phone ? '+91 ' + String(d.phone).replace(/(\d{5})(\d{5})/, '$1 $2') : '-';
        let msg = '🏪 *' + (d.shop || d.name || '-') + '*\n'
                + '👤 Owner: ' + (d.name || '-') + '\n'
                + '📍 Address: ' + addr + '\n'
                + '📞 ' + phone;
        if (hasCoord) msg += '\n🗺️ Location: https://maps.google.com/?q=' + lat + ',' + lng;
        window.open('https://wa.me/?text=' + encodeURIComponent(msg), '_blank');
    });

    $('#btn-copy-details').on('click', function() {
        const d = $('#shareModal').data('share');
        const lat = parseFloat(d.lat), lng = parseFloat(d.lng);
        const hasCoord = !isNaN(lat) && !isNaN(lng);
        const addr = [d.address, d.city].filter(Boolean).join(', ') || '-';
        const phone = d.phone ? '+91 ' + String(d.phone).replace(/(\d{5})(\d{5})/, '$1 $2') : '-';
        let text = 'Shop: ' + (d.shop || d.name || '-') + '\n'
                 + 'Owner: ' + (d.name || '-') + '\n'
                 + 'Address: ' + addr + '\n'
                 + 'Phone: ' + phone;
        if (hasCoord) text += '\nLocation: https://maps.google.com/?q=' + lat + ',' + lng;

        function showCopied() {
            $('#btn-copy-details').html('<i class="fa fa-check me-1"></i>Copied!').addClass('btn-success').removeClass('btn-secondary');
            setTimeout(function() {
                $('#btn-copy-details').html('<i class="fa fa-copy me-1"></i>Copy').addClass('btn-secondary').removeClass('btn-success');
            }, 2000);
        }
        function fallbackCopy(str) {
            var ta = document.createElement('textarea');
            ta.value = str;
            ta.style.cssText = 'position:absolute;width:1px;height:1px;top:0;left:0;opacity:0;';
            var container = document.getElementById('shareModal');
            container.appendChild(ta);
            ta.focus(); ta.select();
            try { document.execCommand('copy'); showCopied(); } catch(e) {}
            container.removeChild(ta);
        }
        try {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(showCopied).catch(function() { fallbackCopy(text); });
            } else {
                fallbackCopy(text);
            }
        } catch(e) {
            fallbackCopy(text);
        }
    });
});

function reloadTable() {
    var search = $('#search-val').val();
    var limit  = $('#selected_data').val();
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    $.ajax({
        type: "GET",
        url: "{{ route('admin.customer.index') }}",
        data: { search: search, limit: limit },
        success: function(response) { $('#customer-cards').html(response); },
    });
}
</script>
@endsection
