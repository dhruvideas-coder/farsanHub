<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>{{ trans('portal.customer_summary') }} - {{ $monthYear }}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1c1917;
            line-height: 1.5;
            background: #fff;
        }

        .page { padding: 30px 36px; }

        .accent-bar { background:#d97706; height:5px; width:100%; margin-bottom:20px; }
        .bottom-bar { background:#d97706; height:4px; width:100%; margin-top:18px; }

        /* ── HEADER ─── */
        .header-table { width:100%; border-collapse:collapse; margin-bottom:16px; }
        .brand-logo   { vertical-align:middle; width:78px; }
        .brand-logo img { width:70px; height:auto; }
        .brand-info   { vertical-align:middle; padding-left:10px; }
        .brand-name   { font-size:15px; font-weight:bold; text-transform:uppercase; letter-spacing:1px; }
        .brand-tagline{ font-size:8.5px; color:#78716c; margin-top:2px; }
        .report-info  { vertical-align:middle; text-align:right; }
        .report-badge { display:inline-block; background:#fef3c7; color:#92400e; font-size:8.5px; font-weight:bold;
                        text-transform:uppercase; letter-spacing:1px; padding:3px 10px; border-radius:20px; border:1px solid #fcd34d; }
        .report-title { font-size:17px; font-weight:bold; margin-top:7px; text-transform:uppercase; }
        .report-meta  { margin-top:6px; font-size:9px; color:#57534e; line-height:1.7; }
        .report-meta strong { color:#1c1917; }

        .divider { border:none; border-top:2px solid #d97706; margin:0 0 18px 0; }

        /* ── SUMMARY TABLE ─── */
        .sum-table { width:100%; border-collapse:collapse; }

        .sum-table thead tr { background:#fef3c7; }
        .sum-table thead th {
            padding:8px 6px;
            font-size:8.5px;
            font-weight:bold;
            text-transform:uppercase;
            letter-spacing:0.6px;
            color:#92400e;
            border:1px solid #fcd34d;
            text-align:center;
            white-space:nowrap;
        }
        .sum-table thead th.th-left { text-align:left; }

        .sum-table tbody tr.row-even { background:#fafaf9; }
        .sum-table tbody tr.row-odd  { background:#ffffff; }
        .sum-table tbody td {
            padding:6px 7px;
            border:1px solid #f0efee;
            font-size:9.5px;
            vertical-align:middle;
        }
        .sum-table tbody td.td-date { text-align:center; color:#57534e; white-space:nowrap; }
        .sum-table tbody td.td-shop { font-weight:600; color:#1c1917; }
        .sum-table tbody td.td-shop .cust-name { display:block; font-size:8.5px; font-weight:normal; color:#78716c; }
        .sum-table tbody td.td-amt  { text-align:right; color:#292524; white-space:nowrap; }
        .sum-table tbody td.td-zero { text-align:right; color:#d6d3d1; }
        .sum-table tbody td.td-total { text-align:right; font-weight:bold; color:#92400e; white-space:nowrap; }
        .sum-table tbody td.td-total.is-negative { color:#b91c1c; }

        /* Grand total row */
        .sum-table tfoot tr { background:#fef3c7; }
        .sum-table tfoot td {
            padding:9px 7px;
            border:1px solid #fcd34d;
            font-size:10px;
            font-weight:bold;
            color:#92400e;
        }
        .sum-table tfoot td.ft-label { text-align:left; font-size:8.5px; text-transform:uppercase; letter-spacing:0.5px; }
        .sum-table tfoot td.ft-amt   { text-align:right; background:#fde68a; white-space:nowrap; }
        .sum-table tfoot td.ft-amt.is-negative { color:#b91c1c; }

        /* ── FOOTER ─── */
        .footer-table { width:100%; border-collapse:collapse; margin-top:10px; }
        .footer-left  { font-size:8.5px; color:#a8a29e; vertical-align:middle; }
        .footer-right { text-align:right; font-size:8.5px; color:#a8a29e; vertical-align:middle; }
        .footer-right strong { color:#78716c; }
    </style>
</head>
<body>
<div class="page">

    <div class="accent-bar"></div>

    {{-- HEADER --}}
    <table class="header-table">
        <tr>
            <td class="brand-logo"><img src="{{ $logoPath }}" alt="Logo"></td>
            <td class="brand-info">
                <div class="brand-name">Brahmani Khandvi &amp; Farsan</div>
                <div class="brand-tagline">Authentic Gujarati Snacks &amp; Farsan</div>
            </td>
            <td class="report-info">
                <div class="report-badge">{{ trans('portal.customer_summary') }}</div>
                <div class="report-title">{{ $monthName }}</div>
                <div class="report-meta">
                    <strong>{{ trans('portal.generated_on') }}:</strong> {{ $reportDate }}
                </div>
            </td>
        </tr>
    </table>

    <hr class="divider">

    {{-- DATE + CUSTOMER WISE TABLE --}}
    <table class="sum-table">
        <thead>
            <tr>
                <th class="th-left" style="width:44%;">{{ trans('portal.customer') }}</th>
                <th style="width:18%;">{{ trans('portal.purchase') }}</th>
                <th style="width:18%;">{{ trans('portal.sell') }}</th>
                <th style="width:20%;">{{ trans('portal.total') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
            <tr class="{{ $loop->even ? 'row-even' : 'row-odd' }}">
                <td class="td-shop">
                    {{ $row['shop_name'] ?: '—' }}
                    @if($row['customer_name'])
                        <span class="cust-name">{{ $row['customer_name'] }}</span>
                    @endif
                </td>
                @if($row['purchase'] > 0)
                    <td class="td-amt">&#8377; {{ number_format($row['purchase'], 2) }}</td>
                @else
                    <td class="td-zero">&mdash;</td>
                @endif
                @if($row['sell'] > 0)
                    <td class="td-amt">&#8377; {{ number_format($row['sell'], 2) }}</td>
                @else
                    <td class="td-zero">&mdash;</td>
                @endif
                <td class="td-total {{ $row['total'] < 0 ? 'is-negative' : '' }}">
                    &#8377; {{ number_format($row['total'], 2) }}
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td class="ft-label">{{ trans('portal.grand_total') }}</td>
                <td class="ft-amt">&#8377; {{ number_format($totals['purchase'], 2) }}</td>
                <td class="ft-amt">&#8377; {{ number_format($totals['sell'], 2) }}</td>
                <td class="ft-amt {{ $totals['total'] < 0 ? 'is-negative' : '' }}">
                    &#8377; {{ number_format($totals['total'], 2) }}
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="bottom-bar"></div>

    <table class="footer-table">
        <tr>
            <td class="footer-left">{{ trans('portal.generated_on') }}: {{ $reportDate }}</td>
            <td class="footer-right"><strong>Brahmani Khandvi &amp; Farsan</strong></td>
        </tr>
    </table>

</div>
</body>
</html>
