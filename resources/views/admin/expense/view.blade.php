<div class="table-responsive mb-2">
    <table class="table table-hover align-middle mb-0" id="logs-table" style="min-width:550px;">
        <thead style="background:#FFF7EE;">
            <tr>
                <th class="text-uppercase fw-bold" style="font-size:12px; color:#92400e; white-space:nowrap;">#</th>
                <th class="text-uppercase fw-bold" style="font-size:12px; color:#92400e; white-space:nowrap;">{{ @trans('portal.purpose') }}</th>
                <th class="text-uppercase fw-bold text-end" style="font-size:12px; color:#92400e; white-space:nowrap;">{{ @trans('portal.amount') }}</th>
                <th class="text-uppercase fw-bold" style="font-size:12px; color:#92400e; white-space:nowrap;">{{ @trans('portal.comment') }}</th>
                <th class="text-uppercase fw-bold text-center" style="font-size:12px; color:#92400e; white-space:nowrap;">{{ @trans('portal.date') }}</th>
                <th class="text-uppercase fw-bold text-center" style="font-size:12px; color:#92400e; white-space:nowrap;">{{ @trans('portal.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @if ($expenses->isEmpty())
                <tr>
                    <td colspan="6" class="text-center py-4" style="color:#a8a29e; font-size:0.9rem;">
                        <i class="fa fa-inbox me-1"></i> {{ @trans('messages.no_expense') }}
                    </td>
                </tr>
            @else
                @foreach($expenses as $index => $expense)
                    <tr>
                        <td style="font-size:13px; color:#78716c; font-weight:600;">{{ $expenses->firstItem() + $index }}</td>
                        <td>
                            <span style="font-size:13px; font-weight:600; color:#1c1917;">
                                {{ $expense->purpose ? trans('portal.' . $expense->purpose) : '-' }}
                            </span>
                        </td>
                        <td class="text-end" style="font-size:13px; font-weight:700; color:#d97706; white-space:nowrap;">
                            ₹{{ $expense->amount ? number_format($expense->amount, 2) : '-' }}
                        </td>
                        <td style="font-size:13px; color:#292524;">{{ $expense->comment ?: '-' }}</td>
                        <td class="text-center" style="font-size:12px; color:#78716c; white-space:nowrap;">
                            {{ $expense->date ? date('d-m-Y', strtotime($expense->date)) : '-' }}
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.expense.edit', $expense->id) }}"
                                   class="btn btn-sm" style="background:#FFF7EE; color:#d97706; border:1px solid #fde68a; padding:4px 10px;">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a class="btn btn-sm expense-delete-btn"
                                   style="background:#fff0f0; color:#ef4444; border:1px solid #fecaca; padding:4px 10px; cursor:pointer;"
                                   data-bs-toggle="modal" data-bs-target="#user-delete"
                                   data-expense-id="{{ $expense->id }}">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach

                {{-- Total row — matches orders module style --}}
                <tr style="background:#FFF7EE; font-weight:700;">
                    <td colspan="2" class="text-end" style="font-size:13px; color:#92400e;">
                        {{ @trans('portal.total') }}:
                    </td>
                    <td class="text-end" style="font-size:14px; color:#FF9933; white-space:nowrap;">
                        ₹{{ number_format($totalAmount, 2) }}
                    </td>
                    <td colspan="3"></td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center mt-2">
    {{ $expenses->links('admin.parts.pagination') }}
</div>
