@if($products->isEmpty())
    <div class="text-center text-muted py-5">
        <i class="fa fa-flask" style="font-size: 2.5rem; opacity: 0.3;"></i>
        <p class="mt-3 mb-1">{{ __('portal.no_recipe_items') }}</p>
        <a href="{{ route('admin.recipe-item.create') }}" class="btn btn-sm btn-secondary mt-2">
            <i class="fa fa-plus"></i> {{ __('portal.add_items') }}
        </a>
    </div>
@else
    <div class="row g-3">
        @foreach($products as $product)
            <div class="col-12 col-lg-6">
                <div class="recipe-product-card h-100">
                    <div class="recipe-product-head">
                        <span class="p-name">{{ $product->product_name }}</span>
                        <span class="material-badge">
                            {{ $product->recipeItems->count() }} {{ __('portal.materials') }}
                        </span>
                        @php $baseYield = optional($product->recipeItems->first())->base_yield_quantity; @endphp
                        @if($baseYield)
                            <span class="material-badge">
                                {{ __('portal.for') }} {{ formatQty($baseYield, $product->unit) }} {{ $product->unit }}
                            </span>
                        @endif
                        <div class="ms-auto d-flex gap-1">
                            <a href="{{ route('admin.recipe-item.edit', $product->id) }}"
                               class="btn btn-sm btn-outline-primary" title="{{ __('portal.edit') }}">
                                <i class="fa fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger recipe-delete-btn"
                                    data-bs-toggle="modal" data-bs-target="#recipe-delete"
                                    data-product-id="{{ $product->id }}" title="{{ __('portal.delete') }}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th style="width:8%">#</th>
                                    <th>{{ __('portal.material_name') }}</th>
                                    <th class="text-end" style="width:35%">{{ __('portal.quantity') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->recipeItems as $i => $item)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $item->material_name }}</td>
                                        <td class="text-end text-nowrap">
                                            {{ formatQty($item->quantity, $item->unit) }}
                                            <span class="text-muted">{{ $item->unit }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
