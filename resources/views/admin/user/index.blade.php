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
    .filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        align-items: flex-end;
    }
    .filter-item { flex: 1 1 auto; min-width: 0; }
    .filter-item.narrow  { flex: 0 0 auto; width: 80px; }
    .filter-item.medium  { flex: 0 0 auto; width: 210px; }
    
    @media (max-width: 767.98px) {
        .filter-bar { flex-direction: column; align-items: stretch; }
        .filter-bar > .filter-item,
        .filter-bar > .filter-item.narrow,
        .filter-bar > .filter-item.medium {
            width: 100% !important;
            flex: 0 0 100% !important;
        }
    }
</style>

@section('content')
<div class="page-header d-flex flex-wrap justify-content-between align-items-center my-0">
    <div>
        <h1 class="page-title">{{ @trans('portal.users') }}</h1>
    </div>
    <div class="ms-auto pageheader-btn d-none d-xl-flex d-lg-flex mt-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">{{ @trans('portal.home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ @trans('portal.users') }}</li>
        </ol>
    </div>
    <div class="ms-auto pageheader-btn d-flex d-md-none mt-0">
        <a href="{{ route('admin.user.create') }}" class="btn btn-secondary me-2">
             <i class="fa fa-plus"></i>
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
                        <label class="filter-group-label d-md-none">{{ @trans('portal.show') }}</label>
                            <select id="selected_data" onchange="reloadTable()" class="form-select custom-select w-100">
                                <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('limit') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>

                        {{-- Search --}}
                        <div class="filter-item">
                            <label class="filter-group-label d-md-none">{{ @trans('portal.search') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="border-radius:8px 0 0 8px;">
                                    <i class="fa fa-search text-muted"></i>
                                </span>
                                <input type="text" id="search-val" class="form-control custom-input border-start-0" 
                                       placeholder="Search name or email..." 
                                       style="border-radius:0 8px 8px 0;"
                                       onkeyup="reloadTable()"
                                       value="{{ request('search') }}">
                            </div>
                        </div>

                        {{-- Add button (desktop) --}}
                        <div class="filter-item narrow d-none d-md-block">
                            <a href="{{ route('admin.user.create') }}" 
                               class="btn btn-secondary w-100 d-flex align-items-center justify-content-center gap-1"
                               style="height:38px; border-radius:8px;">
                                <i class="fa fa-plus"></i>
                                <span class="d-none d-lg-inline">{{ @trans('portal.add') }}</span>
                            </a>
                        </div>

                    </div>
                </div>

                <div id="user-table-wrap" class="mt-2">
                    @include('admin.user.view')
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
    function reloadTable() {
        var search = $('#search-val').val();
        var limit  = $('#selected_data').val();

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        $.ajax({
            type: "GET",
            url: "{{ route('admin.user.index') }}",
            data: { search: search, limit: limit },
            beforeSend: function () {
                $('#user-table-wrap').css('opacity', '0.45');
            },
            success: function (response) {
                $('#user-table-wrap').html(response).css('opacity', '1');
            },
            error: function () {
                $('#user-table-wrap').css('opacity', '1');
            }
        });
    }

    // Handle pagination clicks via AJAX
    $(document).on('click', '#user-table-wrap .pagination a', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var search = $('#search-val').val();
        var limit = $('#selected_data').val();

        $.ajax({
            url: url,
            data: { search: search, limit: limit },
            success: function(response) {
                $('#user-table-wrap').html(response);
                window.scrollTo(0, 0);
            }
        });
    });
</script>

@if(session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3500 })
            .fire({ icon: 'success', title: "{{ session('success') }}" });
    });
</script>
@endif

@if(session('error'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3500 })
            .fire({ icon: 'error', title: "{{ session('error') }}" });
    });
</script>
@endif
@endsection
