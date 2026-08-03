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
    .filter-item.medium { flex: 0 0 auto; width: 210px; }
    @media (max-width: 767.98px) {
        .filter-bar { flex-direction: column; align-items: stretch; }
        .filter-bar > .filter-item, .filter-bar > .filter-item.medium {
            width: 100% !important; flex: 0 0 100% !important;
        }
    }
    .recipe-product-card { border: 1px solid #f1f1f1; border-radius: 12px; overflow: hidden; }
    .recipe-product-head {
        background: #fff7ed; border-bottom: 1px solid #fed7aa;
        padding: 0.75rem 1rem; display: flex; align-items: center; gap: 0.75rem;
    }
    .recipe-product-head .p-name { font-weight: 700; color: #7c2d12; }
    .material-badge {
        font-size: 0.72rem; background: #fff; border: 1px solid #fed7aa;
        color: #9a3412; border-radius: 999px; padding: 0.1rem 0.55rem;
    }
</style>

@section('content')
<div class="page-header d-flex flex-wrap justify-content-between align-items-center my-0">
    <div>
        <h1 class="page-title">{{ __('portal.recipe_items') }}</h1>
    </div>
    <div class="ms-auto pageheader-btn d-none d-xl-flex d-lg-flex mt-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('portal.home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('portal.recipe_items') }}</li>
        </ol>
    </div>
    <div class="ms-auto pageheader-btn d-flex d-md-none mt-0">
        <a href="{{ route('admin.recipe-item.create') }}" class="btn btn-secondary me-2">
            <i class="fa fa-plus"></i>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card overflow-hidden">
            <div class="p-4 card-body">

                <div class="filter-card">
                    <div class="filter-bar">

                        {{-- Product Filter --}}
                        <div class="filter-item medium">
                            <label class="filter-group-label">{{ __('portal.product') }}</label>
                            <select id="filter-product" onchange="reloadTable()" class="form-select custom-select w-100">
                                <option value="">{{ __('portal.all_products') }}</option>
                                @foreach($allProducts as $p)
                                    <option value="{{ $p->id }}" {{ (isset($productId) && $productId == $p->id) ? 'selected' : '' }}>
                                        {{ $p->product_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Search --}}
                        <div class="filter-item">
                            <label class="filter-group-label">{{ __('portal.search') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="border-radius:8px 0 0 8px;">
                                    <i class="fa fa-search text-muted"></i>
                                </span>
                                <input type="text" id="search-val" class="form-control custom-input border-start-0"
                                       placeholder="{{ __('portal.search_material_or_product') }}"
                                       style="border-radius:0 8px 8px 0;"
                                       onkeyup="reloadTable()" value="{{ $search ?? '' }}">
                            </div>
                        </div>

                        {{-- Add button (desktop) --}}
                        <div class="filter-item medium d-none d-md-block">
                            <a href="{{ route('admin.recipe-item.create') }}"
                               class="btn btn-secondary w-100 d-flex align-items-center justify-content-center gap-1"
                               style="height:38px; border-radius:8px;">
                                <i class="fa fa-plus"></i>
                                <span>{{ __('portal.add_items') }}</span>
                            </a>
                        </div>

                    </div>
                </div>

                <div id="recipe-list" class="mt-2">
                    @include('admin.recipe-item.view')
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="recipe-delete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-bottom-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.recipe-item.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body text-center pt-0">
                    <div class="mb-3">
                        <i class="fa fa-exclamation-circle text-danger" style="font-size: 3.5rem;"></i>
                    </div>
                    <h4 class="fw-bold mb-2" style="color: #1c1917;">{{ __('portal.delete_recipe') }}</h4>
                    <p class="text-muted px-3">{{ __('portal.delete_recipe_confirm') }}</p>
                    <input type="hidden" name="product_id" id="delete_product_id" value="">
                </div>
                <div class="modal-footer border-top-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-light px-4 fw-bold" data-bs-dismiss="modal" style="border-radius: 8px;">{{ __('portal.cancel') }}</button>
                    <button type="submit" class="btn btn-danger px-4 fw-bold" style="border-radius: 8px;">{{ __('portal.delete_now') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {
    $(document).on('click', '.recipe-delete-btn', function () {
        $('#delete_product_id').val($(this).data('product-id'));
    });
});

function reloadTable() {
    var search    = $('#search-val').val();
    var productId = $('#filter-product').val();

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    $.ajax({
        type: "GET",
        url: "{{ route('admin.recipe-item.index') }}",
        data: { search: search, product_id: productId },
        beforeSend: function () { $('#recipe-list').css('opacity', '0.45'); },
        success: function (response) { $('#recipe-list').html(response).css('opacity', '1'); },
        error: function () { $('#recipe-list').css('opacity', '1'); }
    });
}
</script>

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
