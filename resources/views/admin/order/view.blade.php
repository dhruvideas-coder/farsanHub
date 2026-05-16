<div class="table-responsive mb-2">
<table class="table table-hover align-middle mb-0" id="logs-table" style="min-width:600px;">
    <thead style="background:#FFF7EE;">
        <tr>
            <th class="text-uppercase fw-bold" style="font-size:12px; color:#92400e; white-space:nowrap;">#</th>
            <th class="text-uppercase fw-bold text-center" style="font-size:12px; color:#92400e; white-space:nowrap;">{{ @trans('portal.type') }}</th>
            <th class="text-uppercase fw-bold" style="font-size:12px; color:#92400e; white-space:nowrap;">{{ @trans('portal.shop_name') }}</th>
            <th class="text-uppercase fw-bold" style="font-size:12px; color:#92400e; white-space:nowrap;">{{ @trans('portal.product_name') }}</th>
            <th class="text-uppercase fw-bold text-center" style="font-size:12px; color:#92400e; white-space:nowrap;">{{ @trans('portal.order_quantity') }}</th>
            <th class="text-uppercase fw-bold text-end" style="font-size:12px; color:#92400e; white-space:nowrap;">{{ @trans('portal.amount') }}</th>
            <th class="text-uppercase fw-bold text-center" style="font-size:12px; color:#92400e; white-space:nowrap;">{{ @trans('portal.date') }}</th>
            <th class="text-uppercase fw-bold text-center" style="font-size:12px; color:#92400e; white-space:nowrap;">{{ @trans('portal.action') }}</th>
        </tr>
    </thead>
    <tbody>
        @if ($orders->isEmpty())
            <tr>
                <td colspan="8" class="text-center py-4" style="color:#a8a29e; font-size:0.9rem;">
                    <i class="fa fa-inbox me-1"></i> {{ @trans('messages.no_content') }}
                </td>
            </tr>
        @else
            @foreach($orders as $index => $order)
                <tr>
                    <td style="font-size:13px; color:#78716c; font-weight:600;">{{ $orders->firstItem() + $index }}</td>
                    <td class="text-center">
                        <div class="d-flex flex-column align-items-center gap-1">
                            @if(($order->order_type ?? 'sell') === 'purchase')
                                <span class="badge" style="background:#dbeafe; color:#1e40af; font-size:11px;">{{ __('portal.purchase') }}</span>
                            @else
                                <span class="badge" style="background:#d1fae5; color:#065f46; font-size:11px;">{{ __('portal.sell') }}</span>
                            @endif
                            @if(($order->payment_type ?? 'remaining') === 'cash')
                                <span class="badge" style="background:#fef3c7; color:#92400e; font-size:11px;">{{ __('portal.cash') }}</span>
                            @else
                                <span class="badge" style="background:#fee2e2; color:#991b1b; font-size:11px;">{{ __('portal.remaining') }}</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span style="font-size:13px; font-weight:600; color:#1c1917;">
                            @if($order->customer)
                                {{ $order->customer->shop_name ?: $order->customer->customer_name }}
                            @else
                                N/A
                            @endif
                        </span>
                    </td>
                    <td style="font-size:13px; color:#292524;">{{ optional($order->product)->product_name ?? 'N/A' }}</td>
                    <td class="text-center">
                        <span class="badge rounded-pill" style="background:#FFF7EE; color:#d97706; font-size:12px; font-weight:600; border:1px solid #fde68a;">
                            {{ rtrim(rtrim(number_format($order->order_quantity, 4), '0'), '.') }} {{ __('portal.' . strtolower(optional($order->product)->unit ?? 'kg')) }}
                        </span>
                    </td>   
                    <td class="text-end" style="font-size:13px; font-weight:700; color:#d97706; white-space:nowrap;">
                        ₹{{ number_format($order->order_quantity * $order->order_price, 2) }}
                    </td>
                    <td class="text-center" style="font-size:12px; color:#78716c; white-space:nowrap;">
                        {{ date('d-m-Y', strtotime($order->order_date ?: $order->created_at)) }}
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('admin.order.edit', [$order->id, 'page' => $orders->currentPage()]) }}"
                               class="btn btn-sm" style="background:#FFF7EE; color:#d97706; border:1px solid #fde68a; padding:4px 10px;">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a class="btn btn-sm order-delete-btn" style="background:#fff0f0; color:#ef4444; border:1px solid #fecaca; padding:4px 10px; cursor:pointer;"
                               data-bs-toggle="modal" data-bs-target="#order-delete"
                               data-order-id="{{ $order->id }}">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
            @php
                $totalKg = $orders->filter(fn($o) => strtolower(optional($o->product)->unit ?? 'kg') === 'kg')->sum('order_quantity');
                $totalNang = $orders->filter(fn($o) => strtolower(optional($o->product)->unit ?? '') === 'nang')->sum('order_quantity');
            @endphp
            <tr style="background:#FFF7EE; font-weight:700;">
                <td colspan="4" class="text-end" style="font-size:13px; color:#92400e;">{{ @trans('portal.total') }}:</td>
                <td class="text-center">
                    <div class="d-inline-flex flex-column align-items-center gap-1">
                        <span class="badge rounded-pill" style="background:#FF9933; color:#fff; font-size:12px; font-weight:700;">
                            {{ rtrim(rtrim(number_format($totalKg, 4), '0'), '.') }} {{ __('portal.kg') }}
                        </span>
                        <span class="badge rounded-pill" style="background:#FF9933; color:#fff; font-size:12px; font-weight:700;">
                            {{ rtrim(rtrim(number_format($totalNang, 4), '0'), '.') }} {{ __('portal.nang') }}
                        </span>
                    </div>
                </td>
                <td class="text-end" style="font-size:14px; color:#FF9933; white-space:nowrap;">
                    ₹{{ number_format($orders->sum(function ($order) { return $order->order_quantity * $order->order_price; }), 2) }}
                </td>
                <td colspan="2"></td>
            </tr>
        @endif
    </tbody>
</table>
</div>

<div class="d-flex justify-content-center mt-2">
    {{ $orders->links('admin.parts.pagination') }}
</div>
