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
        font-size: 0.72rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 0.35rem;
        display: block;
    }
    .custom-select, .custom-input {
        border-radius: 8px;
        border: 1px solid #d1d5db;
        padding: 0.475rem 0.75rem;
        font-size: 0.875rem;
        background-color: #f9fafb;
        transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
    }
    .custom-select:focus, .custom-input:focus {
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.12);
        background-color: #fff;
        outline: none;
    }
    .month-wrap {
        display: flex;
        align-items: center;
        gap: 0;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background-color: #f9fafb;
        transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
        overflow: hidden;
    }
    .month-wrap:focus-within {
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.12);
        background-color: #fff;
    }
    .month-wrap input[type="month"] {
        flex: 1 1 auto;
        min-width: 0;
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        background: transparent !important;
        padding: 0.475rem 0.5rem 0.475rem 0.75rem;
        font-size: 0.875rem;
    }
    .month-wrap input[type="month"]:focus {
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
    }
    .month-wrap .clear-month-btn {
        flex: 0 0 auto;
        display: none;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #9ca3af;
        font-size: 11px;
        background: #e5e7eb;
        border: none;
        border-left: 1px solid #d1d5db;
        padding: 0 10px;
        height: 38px;
        line-height: 1;
        transition: color 0.15s, background 0.15s;
    }
    .month-wrap .clear-month-btn:hover {
        color: #fff;
        background: #ef4444;
        border-left-color: #ef4444;
    }
    .filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        align-items: flex-end;
    }
    .filter-item { flex: 1 1 auto; min-width: 0; }
    .filter-item.narrow  { flex: 0 0 auto; width: 80px; }
    .filter-item.medium  { flex: 0 0 auto; width: 210px; }

    @media (max-width: 991.98px) {
        .filter-item.medium { width: 190px; }
    }
    @media (max-width: 767.98px) {
        .filter-bar { flex-direction: column; align-items: stretch; }
        .filter-bar > .filter-item,
        .filter-bar > .filter-item.narrow,
        .filter-bar > .filter-item.medium {
            width: 100% !important;
            flex: 0 0 100% !important;
        }
        .month-wrap input[type="month"] { width: 100%; }
    }
</style>

@section('content')
<div class="page-header d-flex flex-wrap justify-content-between align-items-center my-0">
    <div>
        <h1 class="page-title">{{ @trans('portal.expenses') }}</h1>
    </div>
    <div class="ms-auto pageheader-btn d-none d-xl-flex d-lg-flex mt-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.expense.index') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ @trans('portal.expenses') }}</li>
        </ol>
    </div>
    <div class="ms-auto pageheader-btn d-flex d-md-none mt-0">
        <a href="{{ route('admin.expense.create') }}" class="btn btn-secondary me-2">
            <span class="d-none d-sm-inline">{{ @trans('portal.add') }}</span> <i class="fa fa-plus"></i>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card overflow-hidden">
            <div class="p-4 card-body">

                <!-- Filter Bar -->
                <div class="filter-card">
                    <div class="filter-bar">

                        {{-- Per-page --}}
                        <div class="filter-item narrow">
                            <label class="filter-group-label d-md-none">Show</label>
                            <select id="selected_data" onchange="reloadTable()" class="form-select custom-select w-100">
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="75">75</option>
                                <option value="100">100</option>
                            </select>
                        </div>

                        {{-- Month picker --}}
                        <div class="filter-item medium">
                            <label class="filter-group-label d-md-none">Month</label>
                            <div class="month-wrap">
                                <input type="month" id="month-filter"
                                       title="Filter by month"
                                       onchange="onMonthChange(this)">
                                <button type="button" class="clear-month-btn" id="clear-month-btn"
                                        onclick="clearMonthFilter()" title="Clear month filter">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Search --}}
                        <div class="filter-item">
                            <label class="filter-group-label d-md-none">Search</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"
                                      style="border-radius:8px 0 0 8px;">
                                    <i class="fa fa-search text-muted"></i>
                                </span>
                                <input type="text" name="search" id="search-val"
                                       class="form-control custom-input border-start-0"
                                       onkeyup="reloadTable()"
                                       placeholder="Search purpose, comment…"
                                       style="border-radius:0 8px 8px 0;"
                                       @if (!empty($search)) value="{{ $search }}" @endif>
                            </div>
                        </div>

                        {{-- Add button (desktop) --}}
                        <div class="filter-item narrow d-none d-md-block">
                            <a href="{{ route('admin.expense.create') }}"
                               class="btn btn-secondary w-100 d-flex align-items-center justify-content-center gap-1"
                               style="height:38px; border-radius:8px;">
                                <i class="fa fa-plus"></i>
                                <span class="d-none d-lg-inline">{{ @trans('portal.add') }}</span>
                            </a>
                        </div>

                    </div>
                </div>

                <!-- Table -->
                <div id="expense-table-wrap" class="mt-2">
                    @include('admin.expense.view')
                </div>

            </div>
        </div>
    </div>
</div>


<!-- Delete Modal -->
<div class="modal fade" id="user-delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Delete Expense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.expense.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body py-4">
                    <input type="hidden" name="expense_id" id="expense_id" value="">
                    <div class="text-center mb-3">
                        <i class="fa fa-exclamation-triangle text-warning" style="font-size:3rem;"></i>
                    </div>
                    <p class="text-center mb-0">Are you sure you want to delete this expense? This action cannot be undone.</p>
                </div>
                <div class="modal-footer border-0 pt-0 justify-content-center">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius:8px;">Cancel</button>
                    <button type="submit" class="btn btn-danger px-4" style="border-radius:8px;">Delete Permanently</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if (session()->has('success'))
<script>
    Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 })
        .fire({ icon: 'success', title: {!! json_encode(session('success')) !!} });
</script>
@endif
@if (session()->has('error'))
<script>
    Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 })
        .fire({ icon: 'error', title: {!! json_encode(session('error')) !!} });
</script>
@endif

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $(document).on('click', '.expense-delete-btn', function () {
            $('#expense_id').val($(this).data('expense-id'));
        });
        // Also support old class name just in case
        $(document).on('click', '.user-delete-btn', function () {
            $('#expense_id').val($(this).data('expense-id'));
        });
    });

    function onMonthChange(el) {
        var clearBtn = document.getElementById('clear-month-btn');
        clearBtn.style.display = el.value ? 'flex' : 'none';
        reloadTable();
    }

    function clearMonthFilter() {
        var el = document.getElementById('month-filter');
        el.value = '';
        document.getElementById('clear-month-btn').style.display = 'none';
        reloadTable();
    }

    function reloadTable() {
        var search = $('#search-val').val();
        var limit  = $('#selected_data').val();
        var month  = $('#month-filter').val();

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        $.ajax({
            type: "GET",
            url: "{{ route('admin.expense.index') }}",
            data: { search: search, limit: limit, month: month },
            beforeSend: function () {
                $('#expense-table-wrap').css('opacity', '0.45');
            },
            success: function (response) {
                $('#expense-table-wrap').html(response).css('opacity', '1');
            },
            error: function () {
                $('#expense-table-wrap').css('opacity', '1');
            }
        });
    }
</script>
@endsection
