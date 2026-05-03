@extends('layouts.app')

<style>
    .filter-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: 1px solid #f3f4f6;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    .filter-group-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        margin-bottom: 0.5rem;
        display: block;
    }
    .custom-select, .custom-input {
        border-radius: 8px;
        border: 1px solid #d1d5db;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        background-color: #f9fafb;
        transition: all 0.2s;
    }
    .custom-select:focus, .custom-input:focus {
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        background-color: #fff;
        outline: none;
    }
    .order-card {
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
    .order-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(253, 13, 13, 0.4); }
    .clickable-image { cursor: pointer; }
    
    .filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        align-items: flex-end;
    }
    .filter-item {
        flex: 1 1 auto;
        min-width: 0;
    }
    .filter-item.narrow {
        flex: 0 0 auto;
        width: 80px;
    }
    .filter-item.medium {
        flex: 0 0 auto;
        width: 130px;
    }
    .filter-item.date-wrap {
        flex: 0 0 auto;
        width: 155px;
    }
    @media (max-width: 767.98px) {
        .filter-bar { flex-direction: column; align-items: stretch; }
        .filter-bar > .filter-item, 
        .filter-bar > .filter-item.narrow, 
        .filter-bar > .filter-item.medium, 
        .filter-bar > .filter-item.date-wrap {
            width: 100% !important;
            flex: 0 0 100% !important;
        }
    }
</style>

@section('content')
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center my-0">
        <div><h1 class="page-title">{{ @trans('portal.orders') }}</h1></div>
        <div class="ms-auto pageheader-btn d-none d-xl-flex d-lg-flex mt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('login') }}">{{ __('portal.home') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ @trans('portal.orders') }}</li>
            </ol>
        </div>
        <div class="ms-auto pageheader-btn d-flex d-md-none mt-0">
            <a href="{{ route('admin.order.create') }}" class="btn btn-secondary me-2">
                <span class="d-none d-sm-inline">{{ @trans('portal.add') }}</span> <i class="fa fa-plus"></i>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="card overflow-hidden orders">
                <div class="p-4 card-body">
                    <!-- Responsive Filter Bar -->
                    <div class="filter-card">
                        <div class="filter-bar">
                            <div class="filter-item narrow">
                                <label class="filter-group-label d-md-none">{{ __('portal.show') }}</label>
                                <select id="selected_data" onchange="reloadTable()" class="form-select custom-select w-100">
                                    <option value="4">4</option>
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            <div class="filter-item medium">
                                <label class="filter-group-label d-md-none">Type</label>
                                <select id="type-filter" onchange="reloadTable()" class="form-select custom-select w-100">
                                    <option value="">{{ __('portal.all_types') }}</option>
                                    <option value="sell">{{ __('portal.sell') }}</option>
                                    <option value="purchase">{{ __('portal.purchase') }}</option>
                                    <option value="remaining">{{ __('portal.remaining') }}</option>
                                    <option value="cash">{{ __('portal.cash') }}</option>
                                </select>
                            </div>
                            <div class="filter-item" style="flex: 2 1 0;">
                                <label class="filter-group-label d-md-none">Customer</label>
                                <select id="customer-filter" onchange="reloadTable()" class="form-select custom-select w-100">
                                    <option value="">{{ __('portal.all_customers') }}</option>
                                    @foreach($customers as $c)
                                    <option value="{{ $c->id }}">{{ $c->customer_name }}@if($c->shop_name) ({{ $c->shop_name }})@endif</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filter-item" style="flex: 2 1 0;">
                                <label class="filter-group-label d-md-none">Product</label>
                                <select id="product-filter" onchange="reloadTable()" class="form-select custom-select w-100">
                                    <option value="">{{ __('portal.all_products') }}</option>
                                    @foreach($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->product_name }} ({{ __('portal.' . strtolower($p->unit ?? 'kg')) }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filter-item date-wrap">
                                <label class="filter-group-label d-md-none">Start Date</label>
                                <div class="position-relative">
                                    <input type="date" name="start_date" class="form-control custom-input w-100 pr-4" id="start-date" placeholder="{{ __('portal.start_date') }}" data-fp-onchange="checkDatesAndReload" oninput="toggleClearBtn(this, 'clear-start')" onchange="toggleClearBtn(this, 'clear-start')">
                                    <i class="fa fa-times position-absolute text-muted clear-btn" style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; display: none; z-index: 10;" id="clear-start" onclick="$('#start-date').val('').trigger('change'); toggleClearBtn(document.getElementById('start-date'), 'clear-start'); reloadTable();"></i>
                                </div>
                            </div>
                            <div class="filter-item date-wrap">
                                <label class="filter-group-label d-md-none">End Date</label>
                                <div class="position-relative">
                                    <input type="date" name="end_date" class="form-control custom-input w-100 pr-4" id="end-date" placeholder="{{ __('portal.end_date') }}" data-fp-onchange="checkDatesAndReload" oninput="toggleClearBtn(this, 'clear-end')" onchange="toggleClearBtn(this, 'clear-end')">
                                    <i class="fa fa-times position-absolute text-muted clear-btn" style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; display: none; z-index: 10;" id="clear-end" onclick="$('#end-date').val('').trigger('change'); toggleClearBtn(document.getElementById('end-date'), 'clear-end'); reloadTable();"></i>
                                </div>
                            </div>
                            <div class="filter-item" style="flex: 2 1 0;">
                                <label class="filter-group-label d-md-none">Search</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0" style="border-radius: 8px 0 0 8px;"><i class="fa fa-search text-muted"></i></span>
                                    <input type="text" name="search" class="form-control custom-input border-start-0" 
                                        id="search-val" onkeyup="reloadTable()" placeholder="{{ __('portal.search') }}"
                                        style="border-radius: 0 8px 8px 0;"
                                        @if (!empty($search)) value="{{ $search }}" @endif>
                                </div>
                            </div>
                            <div class="filter-item narrow d-none d-md-block">
                                <a href="{{ route('admin.order.create') }}" class="btn btn-secondary w-100 d-flex align-items-center justify-content-center gap-1" style="height: 38px; border-radius: 8px;">
                                    <i class="fa fa-plus"></i> <span class="d-none d-lg-inline">{{ @trans('portal.add') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div id="order-cards" class="mt-4">
                        @include('admin.order.view')
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal fade" id="order-delete" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">{{ __('portal.delete_order') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.order.destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body py-4">
                        <input type="hidden" name="order_id" id="order_id" value="">
                        <div class="text-center mb-3">
                            <i class="fa fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                        </div>
                        <p class="text-center mb-0">{{ __('portal.delete_order_confirm') }}</p>
                    </div>
                    <div class="modal-footer border-0 pt-0 justify-content-center">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 8px;">{{ __('portal.cancel') }}</button>
                        <button type="submit" class="btn btn-danger px-4" style="border-radius: 8px; background: #ef4444;">{{ __('portal.delete_permanently') }}</button>
                    </div>
                </form>
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
    $(document).ready(function() {
        $(document).on('click', '.order-delete-btn', function() { $('#order_id').val($(this).data('order-id')); });
    });

    function checkDatesAndReload() {
        var s = $('#start-date').val();
        var e = $('#end-date').val();
        if (s || e) reloadTable();
    }

    function toggleClearBtn(el, btnId) {
        if (el.value) {
            document.getElementById(btnId).style.display = 'block';
        } else {
            document.getElementById(btnId).style.display = 'none';
        }
    }

    function reloadTable() {
        var search     = $('#search-val').val();
        var limit      = $('#selected_data').val();
        var startDate  = $('#start-date').val();
        var endDate    = $('#end-date').val();
        var customerId = $('#customer-filter').val();
        var productId  = $('#product-filter').val();
        var type       = $('#type-filter').val();

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        $.ajax({
            type: "GET",
            url: "{{ route('admin.order.index') }}",
            data: { 
                search: search, 
                limit: limit, 
                start_date: startDate, 
                end_date: endDate, 
                customer_id: customerId, 
                product_id: productId,
                type: type 
            },
            beforeSend: function() {
                $('#order-cards').css('opacity', '0.5');
            },
            success: function(response) { 
                $('#order-cards').html(response).css('opacity', '1'); 
            },
            error: function() {
                $('#order-cards').css('opacity', '1');
            }
        });
    }
    </script>
@endsection
