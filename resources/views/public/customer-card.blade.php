<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $customer->shop_name ?? $customer->customer_name ?? 'Customer Profile' }} — FarsanHub</title>
    <meta name="description" content="Customer profile for {{ $customer->shop_name ?? $customer->customer_name }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --orange:        #FF9933;
        --orange-dark:   #e07a1a;
        --orange-xdark:  #c4620e;
        --brown:         #5C3A21;
        --brown-light:   #7a4e2d;
        --cream:         #FFF7EE;
        --cream-2:       #FFF0DD;
        --text-dark:     #2d1a0a;
        --text-muted:    #9a8070;
        --border:        rgba(92,58,33,.08);
    }

    html { scroll-behavior: smooth; }
    body {
        font-family: 'Roboto', sans-serif;
        min-height: 100vh;
        color: var(--text-dark);
        background: var(--cream);
        background-image:
            radial-gradient(ellipse 70% 40% at 0% 0%,   rgba(255,153,51,.16) 0%, transparent 100%),
            radial-gradient(ellipse 60% 40% at 100% 100%, rgba(92,58,33,.10) 0%, transparent 100%);
    }

    /* ═══════════════════════════════════════════
       BRAND BAR
    ═══════════════════════════════════════════ */
    .brand-bar {
        position: sticky; top: 0; z-index: 100;
        background: linear-gradient(135deg, var(--brown) 0%, var(--brown-light) 100%);
        padding: 10px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        backdrop-filter: blur(12px);
        box-shadow: 0 2px 16px rgba(0,0,0,.25);
    }
    .brand-logo { display: flex; align-items: center; gap: 10px; }
    .brand-icon {
        width: 32px; height: 32px; border-radius: 9px;
        background: linear-gradient(135deg, var(--orange) 0%, var(--orange-dark) 100%);
        display: flex; align-items: center; justify-content: center;
        font-weight: 900; font-size: 14px; color: #fff; letter-spacing: -1px;
        box-shadow: 0 2px 8px rgba(255,153,51,.5);
    }
    .brand-name    { color: #fff; font-weight: 800; font-size: 16px; letter-spacing: -.3px; line-height: 1.1; }
    .brand-tagline { color: rgba(255,255,255,.45); font-size: 10px; font-weight: 400; margin-top: 1px; }
    .expiry-pill {
        display: flex; align-items: center; gap: 7px;
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,153,51,.35);
        border-radius: 20px;
        padding: 5px 13px;
        color: var(--orange); font-size: 12px; font-weight: 700;
        letter-spacing: .3px;
        transition: border-color .3s, color .3s;
    }
    .expiry-pill .live-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: var(--orange);
        box-shadow: 0 0 0 0 rgba(255,153,51,.5);
        animation: livePulse 1.8s infinite;
    }
    @keyframes livePulse {
        0%   { box-shadow: 0 0 0 0 rgba(255,153,51,.6); }
        70%  { box-shadow: 0 0 0 7px rgba(255,153,51,0); }
        100% { box-shadow: 0 0 0 0 rgba(255,153,51,0); }
    }
    .expiry-pill.danger    { border-color: rgba(220,53,69,.5); color: #f77; }
    .expiry-pill.danger .live-dot { background: #f77; animation: none; }

    /* ═══════════════════════════════════════════
       PAGE WRAPPER
    ═══════════════════════════════════════════ */
    .page-wrapper {
        max-width: 500px;
        margin: 0 auto;
        padding: 24px 14px 48px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    /* ═══════════════════════════════════════════
       PROFILE CARD
    ═══════════════════════════════════════════ */
    .profile-card {
        background: #fff;
        border-radius: 26px;
        overflow: hidden;
        box-shadow:
            0 1px 0 rgba(255,153,51,.2),
            0 8px 24px rgba(92,58,33,.1),
            0 32px 64px rgba(92,58,33,.1);
    }

    /* ── Hero ──────────────────────────────── */
    .card-hero {
        position: relative;
        padding: 28px 24px 52px;
        text-align: center;
        overflow: hidden;
        background: linear-gradient(160deg, var(--orange) 0%, var(--orange-dark) 55%, var(--orange-xdark) 100%);
    }
    /* Decorative circles */
    .card-hero::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 180px; height: 180px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
        pointer-events: none;
    }
    .card-hero .circle2 {
        position: absolute;
        bottom: 20px; left: -30px;
        width: 120px; height: 120px;
        border-radius: 50%;
        background: rgba(255,255,255,.06);
        pointer-events: none;
    }
    .card-hero .wave {
        position: absolute;
        bottom: -1px; left: 0; right: 0;
        height: 44px;
        background: #fff;
        border-radius: 50% 50% 0 0 / 44px 44px 0 0;
    }
    .status-chip {
        display: inline-flex; align-items: center; gap: 6px;
        border-radius: 20px; padding: 4px 14px;
        font-size: 11px; font-weight: 700; letter-spacing: .4px;
        margin-bottom: 18px;
        border: 1px solid;
    }
    .status-chip.active   { background: rgba(40,167,69,.22); border-color: rgba(40,167,69,.45); color: #b8ffcf; }
    .status-chip.inactive { background: rgba(220,53,69,.22); border-color: rgba(220,53,69,.45); color: #ffc0c0; }

    /* ── Avatar duo ─────────────────────────── */
    .avatar-duo {
        display: flex;
        justify-content: center;
        align-items: flex-end;
        gap: 0;
        position: relative;
    }
    .avatar-slot {
        display: flex; flex-direction: column; align-items: center; gap: 7px;
        position: relative;
    }
    .avatar-slot:first-child { transform: translateX(14px); z-index: 2; }
    .avatar-slot:last-child  { transform: translateX(-14px); z-index: 1; }
    .avatar-frame {
        width: 90px; height: 90px;
        border-radius: 50%;
        border: 4px solid rgba(255,255,255,.75);
        box-shadow: 0 6px 20px rgba(0,0,0,.3);
        overflow: hidden;
        display: flex; align-items: center; justify-content: center;
        font-weight: 900; font-size: 32px; color: #fff;
        background: linear-gradient(135deg, var(--brown) 0%, var(--brown-light) 100%);
        transition: transform .2s ease;
    }
    .avatar-frame.owner-frame { background: linear-gradient(135deg,#FF9933 0%,#c4620e 100%); }
    .avatar-frame.shop-frame  { background: linear-gradient(135deg,#5C3A21 0%,#7a4e2d 100%); border-radius: 24px; }
    .avatar-frame img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .avatar-label {
        font-size: 10px; color: rgba(255,255,255,.8);
        font-weight: 600; text-transform: uppercase; letter-spacing: .5px;
    }

    /* ── Card body ─────────────────────────── */
    .card-body { padding: 0 22px 22px; }

    .profile-name {
        text-align: center;
        font-size: 23px; font-weight: 900;
        color: var(--brown);
        line-height: 1.15;
        margin-bottom: 4px;
    }
    .profile-sub {
        text-align: center;
        font-size: 13px; color: var(--text-muted);
        margin-bottom: 20px;
    }
    .profile-sub strong { color: var(--orange-dark); font-weight: 700; }

    /* ── Info grid ─────────────────────────── */
    .info-grid { display: flex; flex-direction: column; gap: 2px; }
    .info-row {
        display: flex; align-items: center; gap: 12px;
        padding: 11px 12px;
        border-radius: 14px;
        transition: background .15s;
    }
    .info-row:hover { background: var(--cream); }
    .info-pill {
        width: 40px; height: 40px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px; flex-shrink: 0;
    }
    .info-pill.green   { background: rgba(40,167,69,.1);  color: #1e7e34; }
    .info-pill.blue    { background: rgba(13,110,253,.1); color: #0a58ca; }
    .info-pill.red     { background: rgba(220,53,69,.1);  color: #c82333; }
    .info-pill.brown   { background: rgba(92,58,33,.1);   color: var(--brown); }
    .info-pill.orange  { background: rgba(255,153,51,.14); color: var(--orange-dark); }

    .info-text { flex: 1; min-width: 0; }
    .info-text .label {
        font-size: 10.5px; font-weight: 600; text-transform: uppercase;
        letter-spacing: .5px; color: var(--text-muted); margin-bottom: 2px;
    }
    .info-text .value {
        font-size: 14px; font-weight: 700; color: var(--text-dark);
        word-break: break-word; line-height: 1.3;
    }
    .info-text .value a { color: inherit !important; text-decoration: none !important; font-weight: inherit !important; }

    /* ── Divider ───────────────────────────── */
    .hr-fade {
        height: 1px;
        background: linear-gradient(90deg, transparent 0%, rgba(255,153,51,.25) 30%, rgba(255,153,51,.25) 70%, transparent 100%);
        margin: 10px 0;
    }

    /* ── CTA buttons ───────────────────────── */
    .cta-row { display: flex; gap: 8px; margin-top: 16px; }
    .cta-btn {
        flex: 1;
        display: flex; flex-direction: column; align-items: center; gap: 5px;
        padding: 13px 6px;
        border-radius: 16px; border: none; cursor: pointer;
        font-family: 'Roboto', sans-serif; font-weight: 700; font-size: 11px;
        text-decoration: none !important; letter-spacing: .2px;
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .cta-btn:active { transform: scale(.96); }
    .cta-btn:hover  { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,.12); }
    .cta-btn i { font-size: 22px; }
    .cta-btn.call {
        background: linear-gradient(145deg,#d4edda,#c3e6cb);
        color: #155724;
        box-shadow: 0 2px 8px rgba(40,167,69,.2);
    }
    .cta-btn.whatsapp {
        background: linear-gradient(145deg,#dcf8e8,#c3f1d5);
        color: #075e54;
        box-shadow: 0 2px 8px rgba(37,211,102,.2);
    }

    /* ── Countdown bar ─────────────────────── */
    .countdown-bar {
        display: flex; align-items: center; gap: 10px;
        margin-top: 16px;
        padding: 11px 14px;
        border-radius: 14px;
        background: rgba(255,153,51,.07);
        border: 1px solid rgba(255,153,51,.2);
        transition: background .4s, border-color .4s;
    }
    .countdown-bar .c-icon {
        width: 34px; height: 34px; border-radius: 10px;
        background: rgba(255,153,51,.15);
        display: flex; align-items: center; justify-content: center;
        color: var(--orange-dark); font-size: 16px; flex-shrink: 0;
        transition: background .4s, color .4s;
    }
    .countdown-bar .c-label  { font-size: 11px; color: var(--text-muted); }
    .countdown-bar .c-time   { font-size: 15px; font-weight: 800; color: var(--orange-dark); display: block; transition: color .4s; }
    .countdown-bar.danger    { background: rgba(220,53,69,.06); border-color: rgba(220,53,69,.3); }
    .countdown-bar.danger .c-icon { background: rgba(220,53,69,.12); color: #dc3545; }
    .countdown-bar.danger .c-time { color: #dc3545; }

    /* ═══════════════════════════════════════════
       MAP CARD
    ═══════════════════════════════════════════ */
    .map-card {
        background: #fff;
        border-radius: 26px;
        overflow: hidden;
        box-shadow:
            0 1px 0 rgba(255,153,51,.15),
            0 8px 24px rgba(92,58,33,.1),
            0 24px 48px rgba(92,58,33,.08);
    }

    /* ── Map card header ───────────────────── */
    .map-card-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 20px 12px;
    }
    .map-card-header .map-title {
        display: flex; align-items: center; gap: 10px;
    }
    .map-title-icon {
        width: 38px; height: 38px; border-radius: 12px;
        background: linear-gradient(135deg, var(--orange) 0%, var(--orange-dark) 100%);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 17px;
        box-shadow: 0 3px 10px rgba(255,153,51,.4);
    }
    .map-title-text .ttl { font-size: 15px; font-weight: 800; color: var(--brown); line-height: 1.1; }
    .map-title-text .sub { font-size: 11px; color: var(--text-muted); margin-top: 1px; }
    .map-badge {
        display: flex; align-items: center; gap: 5px;
        background: rgba(40,167,69,.1); border: 1px solid rgba(40,167,69,.25);
        border-radius: 20px; padding: 4px 10px;
        font-size: 11px; font-weight: 700; color: #1e7e34;
    }
    .map-badge .bdot {
        width: 6px; height: 6px; border-radius: 50%;
        background: #28a745; animation: livePulse 2s infinite;
    }

    /* ── Map container ─────────────────────── */
    .map-outer {
        position: relative;
        margin: 0 14px;
        border-radius: 18px;
        overflow: hidden;
        box-shadow:
            0 4px 12px rgba(92,58,33,.15),
            0 0 0 1px rgba(255,153,51,.15);
    }
    #public-map {
        height: 300px;
        width: 100%;
        display: block;
    }
    /* top fade overlay */
    .map-outer::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 60px;
        background: linear-gradient(to bottom, rgba(255,255,255,.25), transparent);
        pointer-events: none;
        z-index: 1;
    }
    /* address chip overlaid on bottom of map */
    .map-addr-chip {
        position: absolute;
        bottom: 12px; left: 12px; right: 12px;
        z-index: 2;
        background: rgba(255,255,255,.92);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 14px;
        padding: 10px 14px;
        display: flex; align-items: center; gap: 10px;
        box-shadow: 0 4px 16px rgba(0,0,0,.15);
        border: 1px solid rgba(255,153,51,.2);
    }
    .map-addr-chip .pin-icon {
        width: 32px; height: 32px; border-radius: 10px; flex-shrink: 0;
        background: linear-gradient(135deg, var(--orange) 0%, var(--orange-dark) 100%);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 15px;
        box-shadow: 0 2px 8px rgba(255,153,51,.4);
    }
    .map-addr-chip .addr-text { flex: 1; min-width: 0; }
    .map-addr-chip .addr-label { font-size: 10px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: .4px; }
    .map-addr-chip .addr-value { font-size: 13px; font-weight: 700; color: var(--brown); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    /* ── Map actions ───────────────────────── */
    .map-actions {
        display: grid; grid-template-columns: 1fr 1fr;
        gap: 10px;
        padding: 14px 14px 16px;
    }
    .map-action-btn {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        padding: 13px 10px;
        border-radius: 14px; border: none; cursor: pointer;
        font-family: 'Roboto', sans-serif; font-weight: 700; font-size: 13px;
        text-decoration: none !important; letter-spacing: .1px;
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .map-action-btn:hover  { transform: translateY(-2px); }
    .map-action-btn:active { transform: scale(.97); }
    .map-action-btn i { font-size: 17px; }

    .map-action-btn.open-maps {
        background: linear-gradient(135deg, var(--orange) 0%, var(--orange-dark) 100%);
        color: #fff !important;
        box-shadow: 0 4px 14px rgba(255,153,51,.45);
    }
    .map-action-btn.open-maps:hover { box-shadow: 0 6px 20px rgba(255,153,51,.55); }

    .map-action-btn.get-dir {
        background: var(--cream-2);
        color: var(--brown) !important;
        border: 1px solid rgba(255,153,51,.3);
        box-shadow: 0 2px 8px rgba(92,58,33,.08);
    }
    .map-action-btn.get-dir:hover { background: var(--cream); }

    /* ── Coords row ────────────────────────── */
    .coords-row {
        display: flex; align-items: center; gap: 6px;
        margin: 0 14px 14px;
        padding: 8px 12px;
        background: var(--cream);
        border-radius: 10px;
        border: 1px solid rgba(255,153,51,.15);
    }
    .coords-row i { color: var(--text-muted); font-size: 12px; }
    .coords-row span { font-size: 11px; color: var(--text-muted); font-weight: 500; font-variant-numeric: tabular-nums; }

    /* ═══════════════════════════════════════════
       FOOTER
    ═══════════════════════════════════════════ */
    .page-footer {
        text-align: center; font-size: 12px;
        color: rgba(92,58,33,.4); padding: 4px 0 8px;
    }
    .page-footer strong { color: var(--orange-dark); }

    /* ═══════════════════════════════════════════
       LIGHTBOX
    ═══════════════════════════════════════════ */
    .lb-overlay {
        position: fixed; inset: 0; z-index: 1000;
        background: rgba(10,5,2,.88);
        backdrop-filter: blur(22px);
        -webkit-backdrop-filter: blur(22px);
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        padding: 20px;
        opacity: 0;
        pointer-events: none;
        transition: opacity .28s ease;
    }
    .lb-overlay.lb-open {
        opacity: 1;
        pointer-events: all;
    }

    /* Image wrapper — scale spring animation */
    .lb-frame {
        position: relative;
        max-width: min(88vw, 420px);
        max-height: 72vh;
        display: flex; align-items: center; justify-content: center;
        transform: scale(.82) translateY(16px);
        opacity: 0;
        transition: transform .32s cubic-bezier(.34,1.48,.64,1), opacity .28s ease;
        will-change: transform, opacity;
    }
    .lb-overlay.lb-open .lb-frame {
        transform: scale(1) translateY(0);
        opacity: 1;
    }

    .lb-img {
        display: block;
        max-width: 100%; max-height: 72vh;
        width: auto; height: auto;
        border-radius: 20px;
        box-shadow:
            0 0 0 3px rgba(255,153,51,.35),
            0 24px 80px rgba(0,0,0,.7),
            0 8px 24px rgba(0,0,0,.5);
        object-fit: contain;
        user-select: none;
        -webkit-user-drag: none;
    }

    /* Caption pill */
    .lb-caption {
        margin-top: 18px;
        display: flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,.1);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,.15);
        border-radius: 30px;
        padding: 7px 18px;
        transform: translateY(10px);
        opacity: 0;
        transition: transform .32s cubic-bezier(.34,1.48,.64,1) .06s, opacity .28s ease .06s;
    }
    .lb-overlay.lb-open .lb-caption {
        transform: translateY(0);
        opacity: 1;
    }
    .lb-caption .lb-cap-icon {
        width: 24px; height: 24px; border-radius: 8px;
        background: linear-gradient(135deg, var(--orange) 0%, var(--orange-dark) 100%);
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; color: #fff;
    }
    .lb-caption .lb-cap-text {
        font-size: 13px; font-weight: 700; color: rgba(255,255,255,.9);
        letter-spacing: .3px;
    }
    .lb-caption .lb-cap-sub {
        font-size: 11px; color: rgba(255,255,255,.45); margin-left: 4px;
    }

    /* Close button */
    .lb-close {
        position: fixed; top: 16px; right: 16px; z-index: 1001;
        width: 40px; height: 40px; border-radius: 50%;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.2);
        color: #fff; font-size: 18px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: background .2s, transform .2s, border-color .2s;
        transform: scale(.7) rotate(-90deg);
        opacity: 0;
        transition: transform .3s cubic-bezier(.34,1.48,.64,1) .05s,
                    opacity .25s ease .05s,
                    background .2s ease;
    }
    .lb-overlay.lb-open .lb-close {
        transform: scale(1) rotate(0deg);
        opacity: 1;
    }
    .lb-close:hover { background: var(--orange-dark); border-color: var(--orange-dark); transform: scale(1.1) !important; }
    .lb-close:active { transform: scale(.94) !important; }

    /* Swipe hint bar (mobile) */
    .lb-swipe-hint {
        position: fixed; bottom: 24px;
        left: 50%; transform: translateX(-50%);
        display: flex; align-items: center; gap: 6px;
        font-size: 11px; color: rgba(255,255,255,.35);
        pointer-events: none;
        opacity: 0;
        transition: opacity .3s ease .2s;
    }
    .lb-overlay.lb-open .lb-swipe-hint { opacity: 1; }
    .lb-swipe-hint i { font-size: 12px; }

    /* ── Zoomable avatar hover effect ───────── */
    .avatar-frame.zoomable { cursor: zoom-in; }
    .avatar-slot .zoom-wrap {
        position: relative;
        display: inline-block;
        border-radius: inherit;
    }
    .zoom-wrap .zoom-hint {
        position: absolute; inset: 0;
        display: flex; align-items: center; justify-content: center;
        border-radius: inherit;
        background: rgba(0,0,0,0);
        opacity: 0;
        transition: opacity .22s ease, background .22s ease;
        pointer-events: none;
    }
    .zoom-wrap:hover .zoom-hint {
        opacity: 1;
        background: rgba(0,0,0,.38);
    }
    .zoom-hint .zh-icon {
        width: 34px; height: 34px; border-radius: 50%;
        background: rgba(255,255,255,.95);
        display: flex; align-items: center; justify-content: center;
        color: var(--orange-dark); font-size: 15px;
        box-shadow: 0 2px 12px rgba(0,0,0,.3);
        transform: scale(.7);
        transition: transform .22s cubic-bezier(.34,1.56,.64,1);
    }
    .zoom-wrap:hover .zh-icon { transform: scale(1); }

    /* ═══════════════════════════════════════════
       RESPONSIVE
    ═══════════════════════════════════════════ */
    @media (max-width: 480px) {
        .page-wrapper  { padding: 16px 10px 40px; gap: 14px; }
        .card-hero     { padding: 22px 18px 48px; }
        .card-body     { padding: 0 16px 18px; }
        .avatar-frame  { width: 80px; height: 80px; font-size: 28px; }
        #public-map    { height: 260px; }
        .map-actions   { padding: 12px 12px 14px; }
    }
    </style>
</head>
<body>

{{-- ── Brand Bar ─────────────────────────────────────────────── --}}
<div class="brand-bar">
    <div class="brand-logo">
        <div class="brand-icon">FH</div>
        <div>
            <div class="brand-name">FarsanHub</div>
            <div class="brand-tagline">Customer Management System</div>
        </div>
    </div>
    <div class="expiry-pill" id="expiry-pill">
        <div class="live-dot" id="live-dot"></div>
        <span id="nav-countdown">10:00</span>
    </div>
</div>

<div class="page-wrapper">

    {{-- ══════════════════════════════════════
         PROFILE CARD
    ══════════════════════════════════════ --}}
    <div class="profile-card">

        {{-- Hero --}}
        <div class="card-hero">
            <div class="circle2"></div>

            @php $isActive = strtolower($customer->status ?? 'active') === 'active'; @endphp
            <div class="status-chip {{ $isActive ? 'active' : 'inactive' }}">
                <i class="fa fa-circle" style="font-size:6px;"></i>
                {{ $isActive ? 'Active Customer' : 'Inactive' }}
            </div>

            <div class="avatar-duo">
                <div class="avatar-slot">
                    @if($customer->customer_image)
                    <div class="zoom-wrap" style="border-radius:50%;"
                         onclick="openLightbox('{{ Storage::url($customer->customer_image) }}','Owner Photo','fa-user')">
                        <div class="avatar-frame owner-frame zoomable">
                            <img src="{{ Storage::url($customer->customer_image) }}" alt="Owner">
                        </div>
                        <div class="zoom-hint" style="border-radius:50%;">
                            <div class="zh-icon"><i class="fa fa-search-plus"></i></div>
                        </div>
                    </div>
                    @else
                    <div class="avatar-frame owner-frame">
                        {{ mb_strtoupper(mb_substr($customer->customer_name ?? 'C', 0, 1)) }}
                    </div>
                    @endif
                    <span class="avatar-label">Owner</span>
                </div>
                <div class="avatar-slot">
                    @if($customer->shop_image)
                    <div class="zoom-wrap" style="border-radius:24px;"
                         onclick="openLightbox('{{ Storage::url($customer->shop_image) }}','Shop Photo','fa-store')">
                        <div class="avatar-frame shop-frame zoomable">
                            <img src="{{ Storage::url($customer->shop_image) }}" alt="Shop">
                        </div>
                        <div class="zoom-hint" style="border-radius:24px;">
                            <div class="zh-icon"><i class="fa fa-search-plus"></i></div>
                        </div>
                    </div>
                    @else
                    <div class="avatar-frame shop-frame">
                        {{ mb_strtoupper(mb_substr($customer->shop_name ?? 'S', 0, 1)) }}
                    </div>
                    @endif
                    <span class="avatar-label">Shop</span>
                </div>
            </div>

            <div class="wave"></div>
        </div>

        {{-- Body --}}
        <div class="card-body">

            <h1 class="profile-name">{{ $customer->shop_name ?? $customer->customer_name ?? '-' }}</h1>
            <p class="profile-sub">Owner &mdash; <strong>{{ $customer->customer_name ?? '-' }}</strong></p>

            <div class="hr-fade"></div>

            {{-- Info rows --}}
            <div class="info-grid">
                @if($customer->customer_number)
                <div class="info-row">
                    <div class="info-pill green"><i class="fa fa-phone"></i></div>
                    <div class="info-text">
                        <div class="label">Mobile</div>
                        <div class="value">
                            <a href="tel:+91{{ $customer->customer_number }}">
                                +91 {{ substr($customer->customer_number,0,5) }} {{ substr($customer->customer_number,5) }}
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                @if($customer->customer_email)
                <div class="info-row">
                    <div class="info-pill blue"><i class="fa fa-envelope-o"></i></div>
                    <div class="info-text">
                        <div class="label">Email</div>
                        <div class="value">
                            <a href="mailto:{{ $customer->customer_email }}">{{ $customer->customer_email }}</a>
                        </div>
                    </div>
                </div>
                @endif

                @if($customer->shop_address)
                <div class="info-row">
                    <div class="info-pill red"><i class="fa fa-map-marker"></i></div>
                    <div class="info-text">
                        <div class="label">Shop Address</div>
                        <div class="value">{{ $customer->shop_address }}</div>
                    </div>
                </div>
                @endif

                @if($customer->city && $customer->city !== $customer->shop_address)
                <div class="info-row">
                    <div class="info-pill brown"><i class="fa fa-building-o"></i></div>
                    <div class="info-text">
                        <div class="label">City</div>
                        <div class="value">{{ $customer->city }}</div>
                    </div>
                </div>
                @endif
            </div>

            {{-- CTA buttons --}}
            @if($customer->customer_number)
            <div class="cta-row">
                <a href="tel:+91{{ $customer->customer_number }}" class="cta-btn call">
                    <i class="fa fa-phone"></i> Call
                </a>
                <a href="https://wa.me/91{{ $customer->customer_number }}" target="_blank" class="cta-btn whatsapp">
                    <i class="fa fa-whatsapp"></i> WhatsApp
                </a>
            </div>
            @endif

            {{-- Countdown --}}
            <div class="countdown-bar" id="countdown-bar">
                <div class="c-icon"><i class="fa fa-clock-o"></i></div>
                <div>
                    <div class="c-label">Link expires in</div>
                    <span class="c-time" id="countdown-display">10:00</span>
                </div>
            </div>

        </div>
    </div>{{-- /.profile-card --}}

    {{-- ══════════════════════════════════════
         MAP CARD  (only when coords available)
    ══════════════════════════════════════ --}}
    @if($customer->latitude && $customer->longitude)
    <div class="map-card">

        {{-- Header --}}
        <div class="map-card-header">
            <div class="map-title">
                <div class="map-title-icon"><i class="fa fa-map-o"></i></div>
                <div class="map-title-text">
                    <div class="ttl">Shop Location</div>
                    <div class="sub">Pinned on Google Maps</div>
                </div>
            </div>
            <div class="map-badge">
                <div class="bdot"></div>
                Live
            </div>
        </div>

        {{-- Map with overlaid address chip --}}
        <div class="map-outer">
            <div id="public-map"></div>
            <div class="map-addr-chip">
                <div class="pin-icon"><i class="fa fa-map-marker"></i></div>
                <div class="addr-text">
                    <div class="addr-label">Location</div>
                    <div class="addr-value">{{ $customer->shop_address ?? ($customer->city ?? 'See map') }}</div>
                </div>
            </div>
        </div>

        {{-- Coordinates row --}}
        <div class="coords-row">
            <i class="fa fa-crosshairs"></i>
            <span>{{ number_format((float)$customer->latitude, 6) }}, {{ number_format((float)$customer->longitude, 6) }}</span>
        </div>

        {{-- Action buttons --}}
        <div class="map-actions">
            <a href="https://www.google.com/maps/search/?api=1&query={{ $customer->latitude }},{{ $customer->longitude }}"
               target="_blank" class="map-action-btn open-maps">
                <i class="fa fa-map-marker"></i> Open in Maps
            </a>
            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $customer->latitude }},{{ $customer->longitude }}"
               target="_blank" class="map-action-btn get-dir">
                <i class="fa fa-location-arrow"></i> Get Directions
            </a>
        </div>

    </div>{{-- /.map-card --}}
    @endif

    {{-- Footer --}}
    <p class="page-footer">
        Powered by <strong>FarsanHub</strong> &nbsp;&middot;&nbsp; Link expires automatically
    </p>

</div>{{-- /.page-wrapper --}}

{{-- ── Lightbox ─────────────────────────────────────────────────── --}}
<div class="lb-overlay" id="lb-overlay" role="dialog" aria-modal="true" aria-label="Image viewer"
     onclick="handleOverlayClick(event)">
    <button class="lb-close" id="lb-close" aria-label="Close" onclick="closeLightbox()">
        <i class="fa fa-times"></i>
    </button>
    <div class="lb-frame" id="lb-frame">
        <img class="lb-img" id="lb-img" src="" alt="">
    </div>
    <div class="lb-caption" id="lb-caption">
        <div class="lb-cap-icon" id="lb-cap-icon"><i class="fa fa-image" id="lb-cap-fa"></i></div>
        <span class="lb-cap-text" id="lb-cap-text"></span>
        <span class="lb-cap-sub">Tap outside to close</span>
    </div>
    <div class="lb-swipe-hint">
        <i class="fa fa-arrow-down"></i> Swipe down to close
    </div>
</div>

{{-- ── Scripts ─────────────────────────────────────────────────── --}}
@if($customer->latitude && $customer->longitude)
<script>
function initPublicMap() {
    const lat = {{ $customer->latitude }};
    const lng = {{ $customer->longitude }};
    const shopName = '{{ addslashes($customer->shop_name ?? $customer->customer_name ?? 'Shop') }}';

    const warmStyle = [
        { elementType: 'geometry',          stylers: [{ color: '#f5ebe0' }] },
        { elementType: 'labels.text.fill',  stylers: [{ color: '#5c3a21' }] },
        { elementType: 'labels.text.stroke',stylers: [{ color: '#fff7ee' }] },
        { featureType: 'road',              elementType: 'geometry',       stylers: [{ color: '#fff3e0' }] },
        { featureType: 'road',              elementType: 'geometry.stroke', stylers: [{ color: '#ffd4a3' }] },
        { featureType: 'road.highway',      elementType: 'geometry',       stylers: [{ color: '#ffb347' }] },
        { featureType: 'road.highway',      elementType: 'geometry.stroke', stylers: [{ color: '#e07a1a' }] },
        { featureType: 'water',             elementType: 'geometry',       stylers: [{ color: '#c9dff4' }] },
        { featureType: 'water',             elementType: 'labels.text.fill',stylers: [{ color: '#4a90d9' }] },
        { featureType: 'park',              elementType: 'geometry',       stylers: [{ color: '#dcedc8' }] },
        { featureType: 'poi',               elementType: 'labels',         stylers: [{ visibility: 'off' }] },
        { featureType: 'transit',           elementType: 'labels',         stylers: [{ visibility: 'off' }] },
        { featureType: 'administrative',    elementType: 'geometry.stroke', stylers: [{ color: '#f4a261' }] },
    ];

    const map = new google.maps.Map(document.getElementById('public-map'), {
        center:             { lat, lng },
        zoom:               16,
        mapTypeControl:     false,
        streetViewControl:  false,
        fullscreenControl:  false,
        zoomControl:        true,
        zoomControlOptions: { position: google.maps.ControlPosition.RIGHT_TOP },
        styles:             warmStyle,
    });

    // Custom SVG marker (orange pin with initial)
    const initial = shopName.charAt(0).toUpperCase();
    const svgMarker = {
        url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
<svg width="48" height="60" viewBox="0 0 48 60" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <filter id="shadow" x="-30%" y="-20%" width="160%" height="160%">
      <feDropShadow dx="0" dy="3" stdDeviation="3" flood-color="rgba(0,0,0,0.35)"/>
    </filter>
    <linearGradient id="pinGrad" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%"   stop-color="#FF9933"/>
      <stop offset="100%" stop-color="#e07a1a"/>
    </linearGradient>
  </defs>
  <path d="M24 2C13.5 2 5 10.5 5 21C5 34 24 58 24 58C24 58 43 34 43 21C43 10.5 34.5 2 24 2Z"
        fill="url(#pinGrad)" filter="url(#shadow)"/>
  <circle cx="24" cy="21" r="12" fill="white" opacity="0.95"/>
  <text x="24" y="26" text-anchor="middle" font-family="Roboto,sans-serif" font-weight="900"
        font-size="13" fill="#e07a1a">${initial}</text>
</svg>`),
        scaledSize: new google.maps.Size(48, 60),
        anchor:     new google.maps.Point(24, 60),
    };

    const marker = new google.maps.Marker({
        position: { lat, lng },
        map,
        title:    shopName,
        icon:     svgMarker,
        animation: google.maps.Animation.DROP,
    });

    // Info window
    const infoWin = new google.maps.InfoWindow({
        content: `
<div style="font-family:Roboto,sans-serif;padding:4px 6px;min-width:140px;">
  <div style="font-weight:800;font-size:14px;color:#5C3A21;margin-bottom:3px;">${shopName}</div>
  <div style="font-size:11px;color:#9a8070;display:flex;align-items:center;gap:4px;">
    <span style="color:#FF9933;">&#9679;</span> Pinned location
  </div>
</div>`,
        pixelOffset: new google.maps.Size(0, -8),
    });
    infoWin.open(map, marker);
    marker.addListener('click', function() { infoWin.open(map, marker); });
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initPublicMap" async defer></script>
@endif

<script>
(function () {
    const expiresAt = new Date('{{ $shareToken->expires_at->toIso8601String() }}').getTime();
    const els       = [document.getElementById('countdown-display'), document.getElementById('nav-countdown')];
    const bar       = document.getElementById('countdown-bar');
    const pill      = document.getElementById('expiry-pill');

    function tick() {
        const remaining = expiresAt - Date.now();

        if (remaining <= 0) {
            els.forEach(function(el) { if (el) el.textContent = '00:00'; });
            if (bar) {
                bar.classList.add('danger');
                bar.querySelector('.c-label').textContent = '';
                bar.querySelector('.c-time').textContent  = 'Link expired — request a new one';
                bar.querySelector('.c-time').style.fontSize = '12px';
            }
            if (pill) { pill.classList.add('danger'); document.getElementById('live-dot').style.animation = 'none'; }
            return;
        }

        const mins = Math.floor(remaining / 60000);
        const secs = Math.floor((remaining % 60000) / 1000);
        const txt  = String(mins).padStart(2,'0') + ':' + String(secs).padStart(2,'0');
        els.forEach(function(el) { if (el) el.textContent = txt; });

        if (remaining < 120000) {
            if (bar)  bar.classList.add('danger');
            if (pill) pill.classList.add('danger');
        }

        setTimeout(tick, 1000);
    }
    tick();
})();
</script>

<script>
/* ── Lightbox ──────────────────────────────────────────────────── */
(function () {
    var overlay = document.getElementById('lb-overlay');
    var img     = document.getElementById('lb-img');
    var capText = document.getElementById('lb-cap-text');
    var capFa   = document.getElementById('lb-cap-fa');
    var frame   = document.getElementById('lb-frame');
    var closing = false;

    window.openLightbox = function (src, label, icon) {
        if (closing) return;
        img.src         = src;
        img.alt         = label;
        capText.textContent = label;
        capFa.className = 'fa ' + (icon || 'fa-image');
        document.body.style.overflow = 'hidden';
        overlay.classList.add('lb-open');
        overlay.focus();
    };

    window.closeLightbox = function () {
        if (closing) return;
        closing = true;
        overlay.style.opacity = '0';
        frame.style.transform = 'scale(.82) translateY(16px)';
        frame.style.opacity   = '0';
        setTimeout(function () {
            overlay.classList.remove('lb-open');
            overlay.style.opacity = '';
            frame.style.transform = '';
            frame.style.opacity   = '';
            img.src = '';
            document.body.style.overflow = '';
            closing = false;
        }, 300);
    };

    window.handleOverlayClick = function (e) {
        if (e.target === overlay) closeLightbox();
    };

    /* Keyboard: Escape */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && overlay.classList.contains('lb-open')) closeLightbox();
    });

    /* Touch: swipe-down to close */
    var touchStartY = 0;
    overlay.addEventListener('touchstart', function (e) {
        touchStartY = e.touches[0].clientY;
    }, { passive: true });
    overlay.addEventListener('touchmove', function (e) {
        var dy = e.touches[0].clientY - touchStartY;
        if (dy > 0) {
            var progress = Math.min(dy / 200, 1);
            frame.style.transform = 'scale(' + (1 - progress * .15) + ') translateY(' + dy * .4 + 'px)';
            overlay.style.opacity = 1 - progress * .6;
        }
    }, { passive: true });
    overlay.addEventListener('touchend', function (e) {
        var dy = e.changedTouches[0].clientY - touchStartY;
        if (dy > 90) {
            closeLightbox();
        } else {
            frame.style.transform = '';
            overlay.style.opacity = '';
        }
    }, { passive: true });
})();
</script>
</body>
</html>
