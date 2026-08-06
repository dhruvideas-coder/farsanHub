@extends('layouts.app')

<style>
    .material-row { border: 1px solid #f1f1f1; border-radius: 10px; padding: 0.75rem; margin-bottom: 0.6rem; background: #fcfcfc; }
    .row-index { font-weight: 700; color: #9a3412; }
    @media (max-width: 576px) {
        .form-control, .form-select { font-size: 0.9rem; }
    }
</style>

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('portal.recipe_items') . ' ' . __('portal.add') }}</h1>
        </div>
        <div class="ms-auto pageheader-btn d-none d-xl-flex d-lg-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.recipe-item.index') }}">{{ __('portal.home') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('portal.recipe_items') }}</li>
            </ol>
        </div>
    </div>

    {{-- Material name autocomplete suggestions --}}
    <datalist id="material-suggestions">
        @foreach($materials as $m)
            <option value="{{ $m }}"></option>
        @endforeach
    </datalist>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-lg-9 col-xl-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.recipe-item.store') }}" method="POST" id="recipe-form">
                            @csrf

                            <div class="row">
                                <div class="col-md-7 mb-4">
                                    <label for="product_id" class="form-label">{{ __('portal.product') }} <span class="text-danger">*</span></label>
                                    <select id="product_id" name="product_id" class="form-select @error('product_id') is-invalid @enderror">
                                        <option value="">-- {{ __('portal.select_product') }} --</option>
                                        @foreach($products as $p)
                                            <option value="{{ $p->id }}" data-unit="{{ $p->unit }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                                                {{ $p->product_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-5 mb-4">
                                    <label for="base_yield" class="form-label">{{ __('portal.base_yield') }} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" step="0.001" min="0.001" class="form-control @error('base_yield') is-invalid @enderror"
                                               id="base_yield" name="base_yield" value="{{ old('base_yield') }}" placeholder="{{ __('portal.eg') }} 20">
                                        <span class="input-group-text" id="base-yield-unit">{{ __('portal.unit') }}</span>
                                        @error('base_yield')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted">{{ __('portal.base_yield_hint') }}</small>
                                </div>
                            </div>

                            <h4 class="mb-1 text-danger border-bottom pb-2">{{ __('portal.raw_materials') }}</h4>
                            <small class="text-muted d-block mb-3"><i class="fa fa-info-circle"></i> {{ __('portal.qty_format_hint') }}</small>

                            <div id="material-rows">
                                @php $oldNames = old('material_name', ['']); @endphp
                                @foreach($oldNames as $i => $val)
                                    <div class="material-row">
                                        <div class="row g-2 align-items-end">
                                            <div class="col-12 col-md-5">
                                                <label class="form-label mb-1"><span class="row-index">#<span class="idx">{{ $i + 1 }}</span></span> {{ __('portal.material_name') }}</label>
                                                <input type="text" list="material-suggestions" class="form-control"
                                                       name="material_name[]" value="{{ $val }}" placeholder="{{ __('portal.material_name') }}">
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <label class="form-label mb-1">{{ __('portal.unit') }}</label>
                                                <select name="unit[]" class="form-select">
                                                    @foreach($units as $u)
                                                        <option value="{{ $u }}" {{ old('unit.'.$i, 'kg') == $u ? 'selected' : '' }}>{{ $u }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <label class="form-label mb-1">{{ __('portal.quantity') }}</label>
                                                <input type="number" step="0.001" min="0" class="form-control"
                                                       name="quantity[]" value="{{ old('quantity.'.$i) }}" placeholder="0">
                                            </div>
                                            <div class="col-12 col-md-1 d-grid">
                                                <button type="button" class="btn btn-outline-danger remove-row" title="{{ __('portal.remove') }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" id="add-row" class="btn btn-outline-secondary btn-sm mb-4">
                                <i class="fa fa-plus"></i> {{ __('portal.add_material') }}
                            </button>

                            <div class="mb-1 d-flex flex-wrap gap-2 border-top pt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> {{ __('portal.save') }}
                                </button>
                                <a href="{{ route('admin.recipe-item.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left"></i> {{ __('portal.cancel') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{-- Template for a new material row --}}
<template id="row-template">
    <div class="material-row">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-5">
                <label class="form-label mb-1"><span class="row-index">#<span class="idx"></span></span> {{ __('portal.material_name') }}</label>
                <input type="text" list="material-suggestions" class="form-control" name="material_name[]" placeholder="{{ __('portal.material_name') }}">
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label mb-1">{{ __('portal.unit') }}</label>
                <select name="unit[]" class="form-select">
                    @foreach($units as $u)
                        <option value="{{ $u }}">{{ $u }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label mb-1">{{ __('portal.quantity') }}</label>
                <input type="number" step="0.001" min="0" class="form-control" name="quantity[]" placeholder="0">
            </div>
            <div class="col-12 col-md-1 d-grid">
                <button type="button" class="btn btn-outline-danger remove-row" title="{{ __('portal.remove') }}">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
(function () {
    var productSelect = document.getElementById('product_id');
    var unitLabel     = document.getElementById('base-yield-unit');

    function updateUnit() {
        var opt  = productSelect.options[productSelect.selectedIndex];
        var unit = opt ? opt.getAttribute('data-unit') : '';
        unitLabel.textContent = unit || '{{ __('portal.unit') }}';
    }
    productSelect.addEventListener('change', updateUnit);
    updateUnit();
})();
</script>

@include('admin.recipe-item.rows-script')
@endsection
