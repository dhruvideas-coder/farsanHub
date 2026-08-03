<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>{{ trans('portal.customer_payment_sheet') }} - {{ $monthYear }}</title>
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

        /* ── SHEET TABLE ─── */
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
            padding:9px 5px;
            border:1px solid #e7e5e4;
            font-size:8.5px;
            vertical-align:middle;
        }
        .sum-table tbody td.td-shop { font-weight:600; color:#1c1917; }
        .sum-table tbody td.td-shop .cust-name { display:block; font-size:7.5px; font-weight:normal; color:#78716c; }
        /* Left blank on purpose — filled in by hand on the printed copy */
        .sum-table tbody td.td-blank { }

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
                <div class="report-badge">{{ trans('portal.customer_payment_sheet') }}</div>
                <div class="report-title">{{ $monthName }}</div>
                <div class="report-meta">
                    <strong>{{ trans('portal.generated_on') }}:</strong> {{ $reportDate }}
                </div>
            </td>
        </tr>
    </table>

    <hr class="divider">

    {{-- CUSTOMER WISE BLANK PAYMENT SHEET --}}
    <table class="sum-table">
        <thead>
            <tr>
                <th class="th-left" style="width:37%;">{{ trans('portal.customer') }}</th>
                <th style="width:18%;">{{ trans('portal.payment_date') }}</th>
                <th style="width:23%;">{{ trans('portal.account') }}</th>
                <th style="width:22%;">{{ trans('portal.transaction_id') }}</th>
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
                <td class="td-blank">&nbsp;</td>
                <td class="td-blank">&nbsp;</td>
                <td class="td-blank">&nbsp;</td>
            </tr>
            @endforeach
        </tbody>
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
