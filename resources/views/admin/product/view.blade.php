<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="mb-0 fw-bold" style="color: #1c1917;">Product List</h6>
    <span class="text-muted small">
        Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} entries
    </span>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle mb-0" style="min-width:600px;">
        <thead style="background:#FFF7EE;">
            <tr>
                <th class="text-uppercase fw-bold text-center" style="font-size:12px; color:#92400e; white-space:nowrap; padding: 15px 20px; width:60px;">#</th>
                <th class="text-uppercase fw-bold" style="font-size:12px; color:#92400e; white-space:nowrap; padding: 15px 20px;">Image</th>
                <th class="text-uppercase fw-bold" style="font-size:12px; color:#92400e; white-space:nowrap; padding: 15px 20px;">Product Name</th>
                <th class="text-uppercase fw-bold text-center" style="font-size:12px; color:#92400e; white-space:nowrap; padding: 15px 20px;">Base Price</th>
                <th class="text-uppercase fw-bold text-center" style="font-size:12px; color:#92400e; white-space:nowrap; padding: 15px 20px;">Created Date</th>
                <th class="text-uppercase fw-bold text-center" style="font-size:12px; color:#92400e; white-space:nowrap; padding: 15px 20px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @if ($products->isEmpty())
                <tr>
                    <td colspan="6" class="text-center py-5" style="color:#a8a29e; font-size:0.9rem;">
                        <div class="mb-2"><i class="fa fa-search" style="font-size: 2rem; opacity: 0.5;"></i></div>
                        {{ @trans('messages.no_product') }}
                    </td>
                </tr>
            @else
                @foreach ($products as $index => $product)
                    <tr>
                        <td class="text-center" style="font-size:13px; color:#78716c; font-weight:600; padding: 15px 20px;">
                            {{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}
                        </td>
                        <td style="padding: 15px 20px;">
                            @if ($product->product_image && file_exists(public_path('storage/' . $product->product_image)))
                                <img src="{{ asset('storage/' . $product->product_image) }}" alt="img"
                                    class="rounded clickable-image border shadow-sm" width="45" height="45" style="object-fit:cover;"
                                    data-image="{{ asset('storage/' . $product->product_image) }}">
                            @else
                                <div class="rounded d-flex align-items-center justify-content-center shadow-sm" 
                                     style="width:45px; height:45px; background: linear-gradient(135deg, #FF9933, #d35400); color:white; font-weight:800; font-size:14px;">FH</div>
                            @endif
                        </td>
                        <td style="padding: 15px 20px;">
                            <div style="font-size:13px; font-weight:600; color:#1c1917;">{{ ucfirst($product->product_name ?? '-') }}</div>
                            <div style="font-size:11px; color:#78716c;">SKU: {{ $product->sku ?? 'N/A' }}</div>
                        </td>
                        <td class="text-center" style="padding: 15px 20px;">
                            <div style="font-size:14px; font-weight:700; color:#1c1917;">
                                <i class="fa fa-rupee text-muted small me-1"></i>{{ $product->effective_price ?? $product->product_base_price }}
                                <span class="text-muted fw-normal" style="font-size:11px;">/ {{ $product->unit ?? 'kg' }}</span>
                            </div>
                            @if(isset($product->specific_price) && $product->specific_price)
                                <span class="badge" style="background:#fff7ed; color:#c2410c; font-size:10px; border:1px solid #ffedd5;">Specific</span>
                            @elseif(request()->has('customer_id') && request()->customer_id)
                                <span class="badge" style="background:#f3f4f6; color:#4b5563; font-size:10px;">Base</span>
                            @endif
                        </td>
                        <td class="text-center" style="font-size:12px; color:#78716c; white-space:nowrap; padding: 15px 20px;">
                            {{ $product->created_at ? date('d-m-Y', strtotime($product->created_at)) : '-' }}
                        </td>
                        <td class="text-center" style="padding: 15px 20px;">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.product.edit', $product->id) }}"
                                   class="btn btn-sm" style="background:#FFF7EE; color:#d97706; border:1px solid #fde68a; padding:4px 10px;">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm user-delete-btn" style="background:#fff0f0; color:#ef4444; border:1px solid #fecaca; padding:4px 10px;"
                                        data-bs-toggle="modal" data-bs-target="#user-delete" data-product-id="{{ $product->id }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $products->links('admin.parts.pagination') }}
</div>
