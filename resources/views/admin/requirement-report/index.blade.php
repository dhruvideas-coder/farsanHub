@extends('layouts.app')

<style>
    .filter-card {
        background: #ffffff; border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -1px rgba(0,0,0,0.04);
        border: 1px solid #f3f4f6; padding: 1.25rem; margin-bottom: 1.5rem;
    }
    .filter-group-label {
        font-size: 0.72rem; font-weight: 600; color: #6b7280;
        text-transform: uppercase; letter-spacing: 0.04em;
        margin-bottom: 0.35rem; display: block;
    }
    .custom-select, .custom-input {
        border-radius: 8px; border: 1px solid #d1d5db;
        padding: 0.475rem 0.75rem; font-size: 0.875rem; background-color: #f9fafb;
    }
    .filter-bar { display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: flex-end; }
    .filter-item { flex: 1 1 auto; min-width: 0; }
    .filter-item.medium { flex: 0 0 auto; width: 220px; }
    @media (max-width: 767.98px) {
        .filter-bar { flex-direction: column; align-items: stretch; }
        .filter-bar > .filter-item, .filter-bar > .filter-item.medium { width:100% !important; flex:0 0 100% !important; }
    }
    .summary-card { border: 1px solid #fed7aa; border-radius: 12px; overflow: hidden; }
    .summary-head { background: #fff7ed; padding: 0.85rem 1rem; font-weight: 700; color: #7c2d12; }
    .qty-strong { font-weight: 700; color: #9a3412; }
</style>

@section('content')
<div class="page-header d-flex flex-wrap justify-content-between align-items-center my-0">
    <div>
        <h1 class="page-title">{{ __('portal.requirement_report') }}</h1>
    </div>
    <div class="ms-auto pageheader-btn d-none d-xl-flex d-lg-flex mt-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('portal.home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('portal.requirement_report') }}</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card overflow-hidden">
            <div class="p-4 card-body">

                {{-- Filter Bar --}}
                <form method="GET" action="{{ route('admin.requirement-report.index') }}">
                    <div class="filter-card">
                        <div class="filter-bar">
                            <div class="filter-item medium">
                                <label class="filter-group-label">{{ __('portal.order_date') }}</label>
                                <input type="date" name="date" value="{{ $date }}" class="form-control custom-input w-100">
                            </div>
                            <div class="filter-item medium">
                                <label class="filter-group-label">{{ __('portal.customer') }}</label>
                                <select name="customer_id" class="form-select custom-select w-100">
                                    <option value="">{{ __('portal.all_customers') }}</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}" {{ (string)$customerId === (string)$c->id ? 'selected' : '' }}>
                                            {{ $c->shop_name ?: $c->customer_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filter-item">
                                <label class="filter-group-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-secondary w-100" style="height:38px;border-radius:8px;">
                                        <i class="fa fa-search"></i> {{ __('portal.show') }}
                                    </button>
                                    <button type="button" id="share-btn" onclick="shareRequirement()"
                                            class="btn btn-success w-100 {{ empty($totals) ? 'disabled' : '' }}" style="height:38px;border-radius:8px;">
                                        <i class="fa fa-share-alt"></i> {{ __('portal.share') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="row justify-content-center">
                    <div class="col-12 col-lg-9 col-xl-8">

                        <div class="text-muted mb-3">
                            <i class="fa fa-calendar"></i> {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                            &nbsp;·&nbsp; {{ $orderCount }} {{ __('portal.orders') }}
                        </div>

                        @if(empty($totals))
                            <div class="text-center text-muted py-5">
                                <i class="fa fa-inbox" style="font-size:2.5rem;opacity:0.3;"></i>
                                <p class="mt-3">{{ __('portal.no_requirement_data') }}</p>
                            </div>
                        @else
                            {{-- Aggregated totals --}}
                            <div class="summary-card mb-4">
                                <div class="summary-head">
                                    <i class="fa fa-list-check"></i> {{ __('portal.total_required') }}
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0 align-middle">
                                        <thead>
                                            <tr>
                                                <th style="width:8%">#</th>
                                                <th>{{ __('portal.material_name') }}</th>
                                                <th class="text-end" style="width:30%">{{ __('portal.total_required') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($totals as $i => $t)
                                                <tr>
                                                    <td>{{ $i + 1 }}</td>
                                                    <td>{{ $t['name'] }}</td>
                                                    <td class="text-end text-nowrap">
                                                        <span class="qty-strong">{{ rtrim(rtrim(number_format($t['qty'], 3), '0'), '.') }}</span>
                                                        <span class="text-muted">{{ $t['unit'] }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Per-order breakdown --}}
                            <h5 class="mb-3">{{ __('portal.breakdown') }}</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead>
                                        <tr>
                                            <th>{{ __('portal.product') }}</th>
                                            <th>{{ __('portal.customer') }}</th>
                                            <th class="text-end">{{ __('portal.order') }}</th>
                                            <th>{{ __('portal.raw_materials') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($breakdown as $row)
                                            <tr>
                                                <td class="fw-bold">{{ $row['product'] }}</td>
                                                <td>{{ $row['customer'] ?: '-' }}</td>
                                                <td class="text-end text-nowrap">{{ rtrim(rtrim(number_format($row['order_qty'], 3), '0'), '.') }} {{ $row['unit'] }}</td>
                                                <td>
                                                    @foreach($row['materials'] as $m)
                                                        <span class="badge bg-light text-dark border me-1 mb-1">
                                                            {{ $m['name'] }}: {{ rtrim(rtrim(number_format($m['qty'], 3), '0'), '.') }} {{ $m['unit'] }}
                                                        </span>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@php
    // Build a WhatsApp-friendly plain-text summary of the requirement.
    $shareLines = [];
    $shareLines[] = '*' . __('portal.requirement_report') . '*';
    $shareLines[] = '📅 ' . \Carbon\Carbon::parse($date)->format('d M Y') . ' · ' . $orderCount . ' ' . __('portal.orders');
    if (!empty($totals)) {
        $shareLines[] = '';
        $shareLines[] = '*' . __('portal.total_required') . ':*';
        foreach ($totals as $i => $t) {
            $shareLines[] = ($i + 1) . '. ' . $t['name'] . ' - '
                . rtrim(rtrim(number_format($t['qty'], 3), '0'), '.') . ' ' . $t['unit'];
        }
    }
    $shareLines[] = '';
    $shareLines[] = config('app.name', 'Farsan Hub');
    $shareText = implode("\n", $shareLines);
@endphp

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var REQUIREMENT_SHARE_TEXT = @json($shareText);

    function shareRequirement() {
        var text = REQUIREMENT_SHARE_TEXT;
        if (navigator.share) {
            // Native share sheet (WhatsApp, Telegram, email, etc.) — mobile & supported desktops.
            navigator.share({ title: {!! json_encode(__('portal.requirement_report')) !!}, text: text })
                .catch(function () {});
        } else {
            // Fallback: open WhatsApp with the text prefilled.
            window.open('https://wa.me/?text=' + encodeURIComponent(text), '_blank');
        }
    }
</script>

@if (session()->has('error'))
<script>
    Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3500 })
        .fire({ icon: 'error', title: {!! json_encode(session('error')) !!} });
</script>
@endif
@endsection
