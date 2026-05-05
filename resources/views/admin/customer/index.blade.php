@extends('layouts.app')

<style>
    /* ── Customer listing cards ─────────────────────────────────── */
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
    .customer-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(253,13,13,.4); }
    .customer-card .card-body img { object-fit: cover; border: 3px solid #dee2e6; border-radius: 8px; }
    .customer-card ul li { margin-bottom: 6px; }
    .clickable-image { cursor: pointer; }
    @media (max-width: 767.98px) {
        .filter-bar > select,
        .filter-bar > input { width: 100% !important; flex: 0 0 100% !important; }
    }

    /* ── Share Modal ────────────────────────────────────────────── */
    .share-modal-wrap {
        border-radius: 22px !important;
        overflow: hidden;
        border: 0;
        box-shadow: 0 24px 64px rgba(92,58,33,.22), 0 0 0 1px rgba(255,153,51,.15) !important;
    }

    /* Header */
    .sm-header {
        position: relative;
        background: linear-gradient(135deg, #FF9933 0%, #e07a1a 60%, #c4620e 100%);
        padding: 22px 20px 52px;
        text-align: center;
        overflow: hidden;
    }
    .sm-header::before {
        content: '';
        position: absolute; top: -50px; right: -50px;
        width: 160px; height: 160px; border-radius: 50%;
        background: rgba(255,255,255,.08); pointer-events: none;
    }
    .sm-header::after {
        content: '';
        position: absolute; bottom: -1px; left: 0; right: 0;
        height: 36px; background: #fff;
        border-radius: 50% 50% 0 0 / 36px 36px 0 0;
    }
    .sm-header .sm-close {
        position: absolute; top: 12px; right: 14px;
        width: 30px; height: 30px; border-radius: 50%;
        background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.3);
        color: #fff; font-size: 14px; display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: background .2s, transform .2s;
        line-height: 1;
    }
    .sm-header .sm-close:hover { background: rgba(0,0,0,.25); transform: rotate(90deg); }
    .sm-header .sm-icon-ring {
        display: inline-flex; align-items: center; justify-content: center;
        width: 52px; height: 52px; border-radius: 50%;
        background: rgba(255,255,255,.22);
        border: 2px solid rgba(255,255,255,.4);
        font-size: 22px; color: #fff;
        margin-bottom: 10px;
        box-shadow: 0 4px 16px rgba(0,0,0,.15);
    }
    .sm-header h6 { color: #fff; font-weight: 800; font-size: 16px; margin: 0 0 3px; }
    .sm-header small { color: rgba(255,255,255,.8); font-size: 11px; font-weight: 500; }

    /* Body */
    .sm-body { padding: 0 20px 20px; }

    /* Loader */
    .sm-loader {
        text-align: center; padding: 24px 0 16px;
    }
    .sm-loader .loader-rings {
        display: inline-block; position: relative;
        width: 52px; height: 52px; margin-bottom: 14px;
    }
    .sm-loader .loader-rings span {
        display: block; position: absolute; inset: 0;
        border-radius: 50%;
        border: 3px solid transparent;
        border-top-color: #FF9933;
        animation: smSpin 1s linear infinite;
    }
    .sm-loader .loader-rings span:nth-child(2) {
        inset: 8px; border-top-color: #e07a1a; animation-duration: .75s; animation-direction: reverse;
    }
    .sm-loader .loader-rings span:nth-child(3) {
        inset: 16px; border-top-color: #c4620e; animation-duration: .55s;
    }
    @keyframes smSpin { to { transform: rotate(360deg); } }
    .sm-loader p { font-size: 13px; color: #9a8070; margin: 0; font-weight: 500; }
    .sm-loader .dot-anim::after {
        content: ''; animation: dotDot 1.4s infinite;
    }
    @keyframes dotDot {
        0%   { content: ''; }
        33%  { content: '.'; }
        66%  { content: '..'; }
        100% { content: '...'; }
    }

    /* Countdown strip */
    .sm-countdown-strip {
        display: flex; align-items: center; gap: 10px;
        background: rgba(255,153,51,.07);
        border: 1px solid rgba(255,153,51,.2);
        border-radius: 14px;
        padding: 10px 14px;
        margin-bottom: 14px;
        transition: background .4s, border-color .4s;
    }
    .sm-countdown-strip .cd-icon {
        width: 36px; height: 36px; border-radius: 11px; flex-shrink: 0;
        background: linear-gradient(135deg,#FF9933 0%,#e07a1a 100%);
        display: flex; align-items: center; justify-content: center;
        font-size: 17px; color: #fff;
        box-shadow: 0 3px 10px rgba(255,153,51,.4);
    }
    .sm-countdown-strip .cd-text { flex: 1; }
    .sm-countdown-strip .cd-label { font-size: 10px; color: #9a8070; font-weight: 600; text-transform: uppercase; letter-spacing: .4px; }
    .sm-countdown-strip .cd-time  { font-size: 20px; font-weight: 900; color: #e07a1a; line-height: 1.1; letter-spacing: -.5px; }
    .sm-countdown-strip .cd-time sup { font-size: 11px; font-weight: 600; letter-spacing: 0; }
    .sm-countdown-strip .cd-bar-wrap {
        width: 100%; height: 4px; border-radius: 99px;
        background: rgba(255,153,51,.15); margin-top: 5px; overflow: hidden;
    }
    .sm-countdown-strip .cd-bar {
        height: 100%; border-radius: 99px;
        background: linear-gradient(90deg,#FF9933,#e07a1a);
        transition: width 1s linear, background .5s;
        width: 100%;
    }
    .sm-countdown-strip.sm-cd-danger { background: rgba(220,53,69,.06); border-color: rgba(220,53,69,.3); }
    .sm-countdown-strip.sm-cd-danger .cd-icon { background: linear-gradient(135deg,#dc3545,#b02a37); box-shadow: 0 3px 10px rgba(220,53,69,.35); }
    .sm-countdown-strip.sm-cd-danger .cd-time  { color: #dc3545; }
    .sm-countdown-strip.sm-cd-danger .cd-bar   { background: linear-gradient(90deg,#dc3545,#b02a37); }

    /* URL field */
    .sm-url-row {
        display: flex; gap: 8px; align-items: center;
        background: #FFF7EE;
        border: 1.5px solid #FFD199;
        border-radius: 13px;
        padding: 6px 6px 6px 12px;
        margin-bottom: 14px;
    }
    .sm-url-row .url-icon { color: #e07a1a; font-size: 13px; flex-shrink: 0; }
    .sm-url-row input {
        flex: 1; border: none; background: transparent;
        font-size: 12px; color: #5C3A21; font-weight: 600;
        outline: none; min-width: 0;
        font-variant-numeric: tabular-nums;
    }
    .sm-url-row input::selection { background: rgba(255,153,51,.25); }
    .sm-copy-btn {
        flex-shrink: 0;
        display: flex; align-items: center; gap: 6px;
        padding: 7px 14px; border-radius: 9px;
        background: linear-gradient(135deg,#FF9933 0%,#e07a1a 100%);
        color: #fff; border: none; cursor: pointer;
        font-size: 12px; font-weight: 700; letter-spacing: .2px;
        transition: opacity .2s, transform .15s;
        box-shadow: 0 2px 8px rgba(255,153,51,.4);
    }
    .sm-copy-btn:hover { opacity: .9; }
    .sm-copy-btn:active { transform: scale(.95); }

    /* Action buttons grid */
    .sm-actions {
        display: grid; grid-template-columns: 1fr 1fr;
        gap: 8px; margin-bottom: 12px;
    }
    .sm-action-btn {
        display: flex; align-items: center; justify-content: center; gap: 7px;
        padding: 11px 10px; border-radius: 13px; border: none; cursor: pointer;
        font-size: 13px; font-weight: 700; letter-spacing: .1px;
        text-decoration: none !important;
        transition: transform .15s ease, box-shadow .15s ease, opacity .2s;
    }
    .sm-action-btn:hover  { transform: translateY(-2px); }
    .sm-action-btn:active { transform: scale(.96); }
    .sm-action-btn i { font-size: 16px; }

    /* Open Card — orange gradient (theme primary) */
    .sm-action-btn.sm-open {
        background: linear-gradient(135deg, #FF9933 0%, #e07a1a 100%);
        color: #fff !important;
        box-shadow: 0 4px 14px rgba(255,153,51,.45);
    }
    .sm-action-btn.sm-open:hover { box-shadow: 0 6px 20px rgba(255,153,51,.55); }

    /* WhatsApp */
    .sm-action-btn.sm-whatsapp {
        background: linear-gradient(135deg, #25D366 0%, #1da851 100%);
        color: #fff !important;
        box-shadow: 0 4px 14px rgba(37,211,102,.35);
    }
    .sm-action-btn.sm-whatsapp:hover { box-shadow: 0 6px 20px rgba(37,211,102,.45); }

    /* Security note */
    .sm-security {
        display: flex; align-items: center; gap: 7px;
        background: rgba(92,58,33,.04);
        border: 1px solid rgba(92,58,33,.08);
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 11px; color: #9a8070; font-weight: 500;
    }
    .sm-security i { color: #e07a1a; font-size: 12px; flex-shrink: 0; }

    /* Error state */
    .sm-error {
        text-align: center; padding: 20px 0 8px;
    }
    .sm-error .err-icon {
        width: 52px; height: 52px; border-radius: 50%;
        background: rgba(220,53,69,.1);
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 24px; color: #dc3545; margin-bottom: 12px;
    }
    .sm-error p { font-size: 13px; color: #dc3545; margin: 0; font-weight: 500; }
</style>

@section('content')
<div class="page-header d-flex flex-wrap justify-content-between align-items-center my-0">
    <div><h1 class="page-title">{{ @trans('portal.customers') }}</h1></div>
    <div class="ms-auto pageheader-btn d-none d-xl-flex d-lg-flex mt-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('login') }}">{{ __('portal.home') }}</a></li>
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
                           @if (empty($search)) placeholder="{{ __('portal.search') }}"
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

{{-- Share Link Modal --}}
<div class="modal fade" id="shareModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content share-modal-wrap">

            {{-- ── Header ──────────────────────────────── --}}
            <div class="sm-header">
                <button type="button" class="sm-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </button>
                <div class="sm-icon-ring">
                    <i class="fa fa-share-alt"></i>
                </div>
                <h6>Share Customer Profile</h6>
                <small>Public link &middot; expires in 10 minutes</small>
            </div>

            {{-- ── Body ────────────────────────────────── --}}
            <div class="sm-body">

                {{-- Generating loader --}}
                <div id="share-generating" class="sm-loader">
                    <div class="loader-rings">
                        <span></span><span></span><span></span>
                    </div>
                    <p>Generating secure link<span class="dot-anim"></span></p>
                </div>

                {{-- Link box (hidden until ready) --}}
                <div id="share-link-box" style="display:none;">

                    {{-- Countdown strip --}}
                    <div class="sm-countdown-strip" id="share-countdown-badge">
                        <div class="cd-icon"><i class="fa fa-clock-o"></i></div>
                        <div class="cd-text">
                            <div class="cd-label">Time remaining</div>
                            <div class="cd-time"><span id="share-countdown-text">10:00</span> <sup>min</sup></div>
                            <div class="cd-bar-wrap"><div class="cd-bar" id="sm-cd-bar"></div></div>
                        </div>
                    </div>

                    {{-- URL row --}}
                    <div class="sm-url-row">
                        <i class="fa fa-link url-icon"></i>
                        <input type="text" id="share-link-input" readonly placeholder="Link will appear here…">
                        <button id="btn-copy-link" class="sm-copy-btn" onclick="copyShareLink()">
                            <i class="fa fa-copy"></i> Copy
                        </button>
                    </div>

                    {{-- Action buttons --}}
                    <div class="sm-actions">
                        <a id="btn-open-link" href="#" target="_blank" class="sm-action-btn sm-open">
                            <i class="fa fa-external-link"></i> Open Card
                        </a>
                        <button id="btn-whatsapp-share" class="sm-action-btn sm-whatsapp" onclick="whatsappShareLink()">
                            <i class="fa fa-whatsapp"></i> WhatsApp
                        </button>
                    </div>

                    {{-- Security note --}}
                    <div class="sm-security">
                        <i class="fa fa-lock"></i>
                        Private link — auto-expires &amp; cannot be reused. Do not share publicly.
                    </div>

                </div>

                {{-- Error state --}}
                <div id="share-error" style="display:none;" class="sm-error">
                    <div class="err-icon"><i class="fa fa-exclamation-circle"></i></div>
                    <p id="share-error-msg">Could not generate link. Please try again.</p>
                </div>

            </div>{{-- /.sm-body --}}
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="user-delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('portal.delete_customer') }}</h5>
            </div>
            <form action="{{ route('admin.customer.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <input type="hidden" name="customer_id" id="customer_id" value="">
                    <span>{{ __('portal.delete_confirm') }}</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">{{ __('portal.cancel') }}</button>
                    <input type="submit" class="btn btn-primary" value="{{ __('portal.confirm') }}">
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
// ── Countdown timer state ────────────────────────────────────────
let _countdownInterval = null;
let _shareLink = '';

function startCountdown(expiresAt) {
    clearInterval(_countdownInterval);
    const expiry    = new Date(expiresAt).getTime();
    const totalMs   = 10 * 60 * 1000;

    function tick() {
        const remaining = expiry - Date.now();

        if (remaining <= 0) {
            clearInterval(_countdownInterval);
            $('#share-countdown-text').text('00:00');
            $('#sm-cd-bar').css('width', '0%');
            $('#share-countdown-badge').addClass('sm-cd-danger');
            $('#btn-copy-link, #btn-open-link, #btn-whatsapp-share').prop('disabled', true);
            return;
        }

        const mins = Math.floor(remaining / 60000);
        const secs = Math.floor((remaining % 60000) / 1000);
        $('#share-countdown-text').text(
            String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0')
        );

        // Animate progress bar
        const pct = Math.min(100, (remaining / totalMs) * 100);
        $('#sm-cd-bar').css('width', pct + '%');

        // Switch to danger state under 2 min
        if (remaining < 120000) {
            $('#share-countdown-badge').addClass('sm-cd-danger');
        }
    }
    tick();
    _countdownInterval = setInterval(tick, 1000);
}

function copyShareLink() {
    const $btn = $('#btn-copy-link');
    function showCopied() {
        $btn.html('<i class="fa fa-check"></i> Copied!').css('background', 'linear-gradient(135deg,#28a745,#1e7e34)');
        setTimeout(function() {
            $btn.html('<i class="fa fa-copy"></i> Copy').css('background', 'linear-gradient(135deg,#FF9933 0%,#e07a1a 100%)');
        }, 2000);
    }
    if (navigator.clipboard) {
        navigator.clipboard.writeText(_shareLink).then(showCopied).catch(function() { fallbackCopy(_shareLink, showCopied); });
    } else {
        fallbackCopy(_shareLink, showCopied);
    }
}

function fallbackCopy(str, cb) {
    var ta = document.createElement('textarea');
    ta.value = str;
    ta.style.cssText = 'position:absolute;width:1px;height:1px;top:0;left:0;opacity:0;';
    document.body.appendChild(ta);
    ta.focus(); ta.select();
    try { document.execCommand('copy'); if (cb) cb(); } catch(e) {}
    document.body.removeChild(ta);
}

function whatsappShareLink() {
    if (!_shareLink) return;
    const msg = '👤 Customer Profile\n' + _shareLink + '\n_(Link valid for 10 minutes)_';
    window.open('https://wa.me/?text=' + encodeURIComponent(msg), '_blank');
}

$(document).ready(function() {
    $(document).on('click', '.close-btn', function() { $('.modal').modal('hide'); });
    $(document).on('click', '.user-delete-btn', function() { $('#customer_id').val($(this).data('customer-id')); });
    $(document).on('click', '.clickable-image', function() {
        $('#popupImage').attr('src', $(this).data('image'));
        $('#imageModal').modal('show');
    });

    // ─── Share button: generate link via AJAX ─────────────────────
    $(document).on('click', '.share-customer-btn', function() {
        const shareUrl = $(this).data('share-url');

        // Reset modal state
        clearInterval(_countdownInterval);
        _shareLink = '';
        $('#share-generating').show();
        $('#share-link-box').hide();
        $('#share-error').hide();
        $('#btn-copy-link, #btn-open-link, #btn-whatsapp-share').prop('disabled', false);
        $('#share-countdown-badge').removeClass('sm-cd-danger');
        $('#share-countdown-text').text('10:00');
        $('#sm-cd-bar').css('width', '100%');
        $('#btn-copy-link').html('<i class="fa fa-copy"></i> Copy').css('background', 'linear-gradient(135deg,#FF9933 0%,#e07a1a 100%)');

        $('#shareModal').modal('show');

        $.ajax({
            type: 'POST',
            url: shareUrl,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res) {
                _shareLink = res.url;
                $('#share-link-input').val(_shareLink);
                $('#btn-open-link').attr('href', _shareLink);
                startCountdown(res.expires_at);
                $('#share-generating').hide();
                $('#share-link-box').show();
            },
            error: function(xhr) {
                $('#share-generating').hide();
                $('#share-error-msg').text(xhr.responseJSON?.message || 'Could not generate link. Please try again.');
                $('#share-error').show();
            }
        });
    });

    $('#shareModal').on('hidden.bs.modal', function() {
        clearInterval(_countdownInterval);
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
