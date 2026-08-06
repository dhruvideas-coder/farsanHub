@extends('layouts.app')

<style>
    .filter-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -1px rgba(0,0,0,0.04);
        border: 1px solid #f3f4f6;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    .filter-group-label {
        font-size: 0.72rem; font-weight: 600; color: #6b7280;
        text-transform: uppercase; letter-spacing: 0.04em;
        margin-bottom: 0.35rem; display: block;
    }
    .custom-select, .custom-input {
        border-radius: 8px; border: 1px solid #d1d5db;
        padding: 0.475rem 0.75rem; font-size: 0.875rem;
        background-color: #f9fafb;
        transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
    }
    .custom-select:focus, .custom-input:focus {
        border-color: #f97316; box-shadow: 0 0 0 3px rgba(249,115,22,0.12);
        background-color: #fff; outline: none;
    }
    .filter-bar { display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: flex-end; }
    .filter-item { flex: 1 1 auto; min-width: 0; }
    .filter-item.medium { flex: 0 0 auto; width: 240px; }
    @media (max-width: 767.98px) {
        .filter-bar { flex-direction: column; align-items: stretch; }
        .filter-bar > .filter-item, .filter-bar > .filter-item.medium {
            width: 100% !important; flex: 0 0 100% !important;
        }
    }

    /* ── ENTRY TABLE ─── */
    .pay-table { width: 100%; border-collapse: collapse; }
    .pay-table thead th {
        background: #fff7ed; border-bottom: 1px solid #fed7aa;
        color: #9a3412; font-size: 0.72rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.04em;
        padding: 0.65rem 0.6rem; white-space: nowrap;
    }
    .pay-table tbody td { border-bottom: 1px solid #f3f4f6; padding: 0.5rem 0.6rem; vertical-align: middle; }
    .pay-table tbody tr:hover { background: #fffbf6; }
    .pay-table .cust-cell { min-width: 190px; }
    .pay-table .shop-name { font-weight: 600; color: #1c1917; }
    .pay-table .cust-name { display: block; font-size: 0.75rem; color: #78716c; }
    .pay-table .form-control { font-size: 0.85rem; }
    .row-num { color: #9ca3af; font-size: 0.78rem; }
    .amount-prefix {
        background: #fff7ed; border: 1px solid #d1d5db; border-right: none;
        color: #9a3412; font-size: 0.85rem; border-radius: 8px 0 0 8px;
    }
    .amount-input { border-radius: 0 8px 8px 0 !important; text-align: right; }
    .total-badge {
        background: #fff7ed; border: 1px solid #fed7aa; color: #9a3412;
        border-radius: 999px; padding: 0.25rem 0.85rem;
        font-size: 0.85rem; font-weight: 600; white-space: nowrap;
    }

    /* Mobile: stack each row as a card */
    @media (max-width: 767.98px) {
        .pay-table, .pay-table tbody, .pay-table tr, .pay-table td { display: block; width: 100%; }
        .pay-table thead { display: none; }
        .pay-table tbody tr {
            border: 1px solid #f1f1f1; border-radius: 10px;
            padding: 0.75rem; margin-bottom: 0.7rem; background: #fcfcfc;
        }
        .pay-table tbody td { border: none; padding: 0.3rem 0; }
        .pay-table tbody td[data-label]::before {
            content: attr(data-label);
            display: block; font-size: 0.7rem; font-weight: 600; color: #6b7280;
            text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 0.2rem;
        }
    }
</style>

@section('content')
<div class="page-header d-flex flex-wrap justify-content-between align-items-center my-0">
    <div>
        <h1 class="page-title">{{ __('portal.monthly_payments') }}</h1>
    </div>
    <div class="ms-auto pageheader-btn d-none d-xl-flex d-lg-flex mt-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('portal.home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('portal.monthly_payments') }}</li>
        </ol>
    </div>
</div>

{{-- Account autocomplete suggestions --}}
<datalist id="account-suggestions">
    @foreach($accounts as $account)
        <option value="{{ $account }}"></option>
    @endforeach
</datalist>

<div class="row">
    <div class="col-12">
        <div class="card overflow-hidden">
            <div class="p-4 card-body">

                {{-- STEP 1: pick the month --}}
                <div class="filter-card">
                    <form action="{{ route('admin.monthly-payment.index') }}" method="GET">
                        <div class="filter-bar">
                            <div class="filter-item medium">
                                <label class="filter-group-label">{{ __('portal.select_month_year') }}</label>
                                <select name="month_year" class="form-select custom-select w-100">
                                    <option value="">— {{ __('portal.select_month_year') }} —</option>
                                    @foreach($months as $month)
                                        <option value="{{ $month['value'] }}" {{ $monthYear == $month['value'] ? 'selected' : '' }}>
                                            {{ $month['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filter-item medium">
                                <button type="submit" class="btn btn-secondary w-100 d-flex align-items-center justify-content-center gap-1"
                                        style="height:38px; border-radius:8px;">
                                    <i class="fa fa-search"></i>
                                    <span>{{ __('portal.show_customers') }}</span>
                                </button>
                            </div>
                        </div>
                        <p class="text-muted mb-0 mt-3" style="font-size:0.82rem;">
                            {{ __('portal.monthly_payments_desc') }}
                        </p>
                    </form>
                </div>

                @if(!$monthYear)
                    <div class="text-center text-muted py-5">
                        <i class="fa fa-calendar-o mb-3" style="font-size:2.5rem; color:#e5e7eb;"></i>
                        <p class="mb-0">{{ __('portal.select_month_to_start') }}</p>
                    </div>
                @else
                    {{-- STEP 2: enter the payment details --}}
                    <form action="{{ route('admin.monthly-payment.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="month_year" value="{{ $monthYear }}">

                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                            <h4 class="mb-0 text-danger">
                                {{ $monthName }}
                                <span class="text-muted" style="font-size:0.85rem; font-weight:400;">
                                    ({{ count($rows) }} {{ __('portal.customers') }})
                                </span>
                            </h4>
                            <span class="total-badge ms-auto me-2">
                                {{ __('portal.total') }}: &#8377; <span id="amount-total">0.00</span>
                            </span>
                            @if($hasSavedData)
                                <a href="{{ route('admin.monthly-payment.pdf', ['month_year' => $monthYear]) }}"
                                   class="btn btn-outline-danger btn-sm pdf-download-btn">
                                    <i class="fa fa-file-pdf-o"></i> {{ __('portal.download_pdf') }}
                                </a>
                            @else
                                <span class="btn btn-outline-secondary btn-sm disabled"
                                      title="{{ __('portal.no_payment_data_to_export') }}">
                                    <i class="fa fa-file-pdf-o"></i> {{ __('portal.download_pdf') }}
                                </span>
                            @endif
                        </div>

                        <div class="table-responsive">
                            <table class="pay-table">
                                <thead>
                                    <tr>
                                        <th style="width:40px;">#</th>
                                        <th class="cust-cell">{{ __('portal.customer') }}</th>
                                        <th style="width:160px;">{{ __('portal.payment_date') }}</th>
                                        <th style="width:140px;">{{ __('portal.amount') }}</th>
                                        <th style="width:190px;">{{ __('portal.account') }}</th>
                                        <th style="width:190px;">{{ __('portal.transaction_id') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rows as $row)
                                        @php $id = $row['customer_id']; @endphp
                                        <tr>
                                            <td class="row-num d-none d-md-table-cell">{{ $loop->iteration }}</td>
                                            <td class="cust-cell">
                                                <span class="shop-name">{{ $row['shop_name'] ?: '—' }}</span>
                                                @if($row['customer_name'])
                                                    <span class="cust-name">{{ $row['customer_name'] }}</span>
                                                @endif
                                            </td>
                                            <td data-label="{{ __('portal.payment_date') }}">
                                                <input type="date" class="form-control custom-input w-100"
                                                       name="payment_date[{{ $id }}]"
                                                       value="{{ old('payment_date.' . $id, $row['payment_date']) }}">
                                            </td>
                                            <td data-label="{{ __('portal.amount') }}">
                                                <div class="input-group">
                                                    <span class="input-group-text amount-prefix">&#8377;</span>
                                                    <input type="number" step="0.01" min="0"
                                                           class="form-control custom-input amount-input"
                                                           name="payment_amount[{{ $id }}]"
                                                           value="{{ old('payment_amount.' . $id, $row['payment_amount']) }}"
                                                           placeholder="0.00">
                                                </div>
                                            </td>
                                            <td data-label="{{ __('portal.account') }}">
                                                <input type="text" list="account-suggestions"
                                                       class="form-control custom-input w-100"
                                                       name="account[{{ $id }}]"
                                                       value="{{ old('account.' . $id, $row['account']) }}"
                                                       placeholder="{{ __('portal.account') }}">
                                            </td>
                                            <td data-label="{{ __('portal.transaction_id') }}">
                                                <input type="text" class="form-control custom-input w-100"
                                                       name="transaction_id[{{ $id }}]"
                                                       value="{{ old('transaction_id.' . $id, $row['transaction_id']) }}"
                                                       placeholder="{{ __('portal.transaction_id') }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 d-flex flex-wrap gap-2 border-top pt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> {{ __('portal.save') }}
                            </button>
                            @if($hasSavedData)
                                <a href="{{ route('admin.monthly-payment.pdf', ['month_year' => $monthYear]) }}"
                                   class="btn btn-danger pdf-download-btn">
                                    <i class="fa fa-file-pdf-o"></i> {{ __('portal.download_pdf') }}
                                </a>
                            @else
                                <span class="btn btn-secondary disabled"
                                      title="{{ __('portal.no_payment_data_to_export') }}">
                                    <i class="fa fa-file-pdf-o"></i> {{ __('portal.download_pdf') }}
                                </span>
                            @endif
                        </div>
                        <small class="text-muted d-block mt-2">
                            <i class="fa fa-info-circle"></i>
                            {{ $hasSavedData ? __('portal.monthly_payments_save_hint') : __('portal.no_payment_data_to_export') }}
                        </small>
                    </form>
                @endif

            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if($monthYear)
<script>
// Running total of the entered amounts, so it can be checked against the bank statement.
(function () {
    var inputs = document.querySelectorAll('.amount-input');
    var label  = document.getElementById('amount-total');

    function recalc() {
        var total = 0;
        inputs.forEach(function (input) {
            var value = parseFloat(input.value);
            if (!isNaN(value)) { total += value; }
        });
        label.textContent = total.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    inputs.forEach(function (input) { input.addEventListener('input', recalc); });
    recalc();
})();

// Loader while the PDF is being built. The server sets a readable `pdf_ready` cookie on
// the download response, so the loader closes when the file actually starts downloading
// rather than after a guessed delay. The timeout is only a safety net.
(function () {
    var buttons = document.querySelectorAll('.pdf-download-btn');
    if (!buttons.length) { return; }

    var COOKIE = 'pdf_ready';
    var TIMEOUT = 60000;

    function cookieSet() {
        return document.cookie.split(';').some(function (part) {
            return part.trim().indexOf(COOKIE + '=') === 0;
        });
    }

    function clearCookie() {
        document.cookie = COOKIE + '=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/';
    }

    buttons.forEach(function (button) {
        button.addEventListener('click', function () {
            clearCookie();

            Swal.fire({
                title: {!! json_encode(__('portal.please_wait')) !!},
                html: {!! json_encode(__('portal.export_generating')) !!},
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: function () { Swal.showLoading(); }
            });

            var waited = 0;
            var poll = setInterval(function () {
                waited += 300;
                if (cookieSet() || waited >= TIMEOUT) {
                    clearInterval(poll);
                    clearCookie();
                    Swal.close();
                }
            }, 300);
        });
    });
})();
</script>
@endif

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
@endsection
