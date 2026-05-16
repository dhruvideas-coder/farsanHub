<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Order Bill - {{ $monthYear }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        @page {
            size: A4 portrait;
            margin: 10mm 12mm 10mm 12mm;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #000;
            line-height: 1.4;
            background: #fff;
            margin: 18mm 20mm 15mm 20mm;
            padding: 0;
        }

        /* ── Page wrapper ── */
        .page {
            width: 100%;
            background: #fff;
        }

        /* ── Header ── */
        .header-row {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .header-row td {
            vertical-align: middle;
        }

        .brand-title {
            text-align: center;
            font-size: 22px;
            font-weight: 800;
            color: #000;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        /* ── Info block ── */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .info-table td {
            font-size: 11px;
            line-height: 1.5;
            vertical-align: top;
        }
        .info-left  { width: 60%; }
        .info-right { width: 40%; text-align: right; }

        .label { font-weight: 700; }

        .divider {
            border: none;
            border-top: 1.5px solid #000;
            margin: 8px 0;
        }

        /* ── Party info ── */
        .party-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .party-left  { width: 55%; vertical-align: top; }
        .party-right { width: 45%; vertical-align: top; text-align: right; }

        .party-label {
            font-size: 11px;
            font-weight: 700;
            color: #000;
            text-transform: uppercase;
            display: block;
            margin-bottom: 3px;
        }
        .party-title {
            font-size: 16px;
            font-weight: 800;
            color: #000;
            display: block;
            margin-bottom: 3px;
        }
        .party-detail {
            font-size: 11px;
            color: #333;
            margin-bottom: 2px;
        }

        .inv-meta {
            font-size: 12px;
            margin-bottom: 3px;
        }

        /* ── Items table ── */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            border: 1px solid #000;
            page-break-inside: auto;
        }
        .items-table thead {
            background: #f0f0f0;
            display: table-header-group;
        }
        .items-table th {
            padding: 8px 10px;
            text-align: center;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            border: 1px solid #000;
        }
        .items-table th:first-child { text-align: left; }
        .items-table td {
            padding: 7px 10px;
            font-size: 12px;
            text-align: center;
            border: 1px solid #000;
            font-weight: 600;
        }
        .items-table td.col-desc {
            text-align: left;
            font-weight: 700;
        }
        .items-table tbody tr { page-break-inside: avoid; }

        /* tfoot */
        .items-table tfoot td {
            background: #f0f0f0;
            border-top: 1.5px solid #000;
        }

        /* ── Bottom section ── */
        .bottom-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .qr-section {
            width: 32%;
            text-align: center;
            vertical-align: top;
            padding-right: 12px;
        }
        .details-section {
            width: 68%;
            vertical-align: top;
        }

        .qr-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .qr-box {
            width: 130px;
            height: 130px;
            margin: 0 auto;
            border: 1px dashed #000;
            padding: 4px;
        }
        .qr-box img {
            width: 100%;
            height: 100%;
        }

        .bank-box {
            border: 1px solid #000;
            padding: 10px 12px;
            margin-bottom: 10px;
        }
        .bank-box table { width: 100%; border-collapse: collapse; }
        .bank-box table td {
            padding: 3px 0;
            font-size: 11px;
            font-weight: 600;
        }
        .bank-box-title { width: 80px; font-weight: 700; color: #333; }

        .words-box {
            border: 1px solid #000;
            padding: 9px 12px;
            margin-bottom: 10px;
            font-size: 11px;
            font-weight: 600;
        }
        .words-box strong { font-weight: 800; }

        .total-box-grand {
            border: 1px solid #000;
            background: #f0f0f0;
            padding: 10px 14px;
        }
        .total-box-grand table { width: 100%; border-collapse: collapse; }
        .total-box-grand td {
            font-size: 17px;
            font-weight: 800;
            vertical-align: middle;
        }

        /* ── Signatures ── */
        .sign-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .sign-cell {
            text-align: center;
            font-size: 11px;
            font-weight: 700;
            width: 200px;
            padding-top: 6px;
            border-top: 1px solid #000;
            vertical-align: bottom;
        }
        .sign-img {
            display: block;
            margin: 0 auto 4px;
            height: 55px;
            max-width: 160px;
        }
        .sign-gap { border: none; }

        /* ── Terms ── */
        .terms {
            font-size: 10px;
            color: #555;
            margin-top: 12px;
            border-top: 1px solid #ccc;
            padding-top: 6px;
        }
    </style>
</head>
<body>
<div class="page">

    {{-- ── TOP HEADER ── --}}
    <table class="header-row">
        <tr>
            <td style="text-align:left; font-size:11px; font-weight:700; text-transform:uppercase;">Jay Ma Bhramani</td>
            <td style="text-align:center; font-size:16px; font-weight:800; text-transform:uppercase; letter-spacing:1px;">Invoice</td>
            <td style="text-align:right; font-size:11px; font-weight:700; text-transform:uppercase;">Jay Shree Krishna</td>
        </tr>
    </table>

    <div class="brand-title">BHRAMANI KHANDVI HOUSE</div>

    {{-- ── BUSINESS INFO ── --}}
    <table class="info-table">
        <tr>
            <td class="info-left">
                <div><span class="label">FSSAI Lic No.:</span> 20725032000942</div>
                <div style="margin-top:4px;">
                    <span class="label">Address:</span>
                    Shop No-06, Arkview Tower, near Hari Om Subhanpura Water Tank,
                    Subhanpura, Vadodara, Gujarat – 390021
                </div>
            </td>
            <td class="info-right">
                <div><span class="label">Email:</span> patelhitesh0723a@gmail.com</div>
                <div><span class="label">Mobile:</span> 9574659456</div>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    {{-- ── PARTY INFO ── --}}
    <table class="party-table">
        <tr>
            <td class="party-left">
                <span class="party-label">Bill To:</span>
                @if($customerInfo)
                    <span class="party-title">{{ $customerInfo->customer_name }}</span>
                    <div class="party-detail">{{ $customerInfo->shop_name }}</div>
                    <div class="party-detail"><span class="label">Address:</span> {{ $customerInfo->shop_address }}{{ $customerInfo->city ? ', '.$customerInfo->city : '' }}</div>
                    <div class="party-detail"><span class="label">Phone:</span> {{ $customerInfo->customer_number }}</div>
                @else
                    <span class="party-title">All Customers</span>
                @endif
            </td>
            <td class="party-right">
                <div class="inv-meta"><span class="label">Invoice No.:</span> {{ $receiptNo }}</div>
                <div class="inv-meta"><span class="label">Date:</span> {{ now()->format('d / m / Y') }}</div>
                <div class="inv-meta"><span class="label">Period:</span> {{ $monthYear }}</div>
            </td>
        </tr>
    </table>

    {{-- ── ITEMS TABLE ── --}}
    @php
        $groupedOrders = [];
        foreach($orders as $order) {
            $key = $order->product->id;

            if (!isset($groupedOrders[$key])) {
                $groupedOrders[$key] = [
                    'product_name' => $order->product->product_name,
                    'total_qty'    => 0,
                    'unit'         => $order->product->unit ?? 'kg',
                    'total_amount' => 0,
                ];
            }
            $groupedOrders[$key]['total_qty']    += (float) $order->order_quantity;
            $groupedOrders[$key]['total_amount'] += (float) $order->order_quantity * (float) $order->order_price;
        }
    @endphp

    <table class="items-table">
        <thead>
            <tr>
                <th style="width:42%;">Item Description</th>
                <th style="width:20%;">Qty / Weight</th>
                <th style="width:17%;">Unit Price</th>
                <th style="width:21%;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groupedOrders as $item)
                <tr>
                    <td class="col-desc">{{ $item['product_name'] }}</td>
                    <td>{{ rtrim(rtrim(number_format($item['total_qty'], 4), '0'), '.') }} {{ ucfirst($item['unit']) }}</td>
                    <td>{{ $item['total_qty'] > 0 ? number_format($item['total_amount'] / $item['total_qty'], 2) : '0.00' }}</td>
                    <td>{{ number_format($item['total_amount'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align:right; font-weight:800; font-size:13px; padding:10px;">
                    GRAND TOTAL:
                    &nbsp;&nbsp;
                    @if($totalKg > 0)
                        {{ rtrim(rtrim(number_format($totalKg, 4), '0'), '.') }} kg
                    @endif
                    @if($totalNang > 0)
                        &nbsp; {{ rtrim(rtrim(number_format($totalNang, 4), '0'), '.') }} Nang
                    @endif
                </td>
                <td style="background:#fff; border:1px solid #000;"></td>
                <td style="text-align:center; font-weight:800; font-size:15px; padding:10px;">
                    {{ number_format($totalOrderAmount, 2) }}
                </td>
            </tr>
        </tfoot>
    </table>

    {{-- ── BOTTOM: QR + BANK + TOTAL ── --}}
    <table class="bottom-table">
        <tr>
            <td class="qr-section">
                <div class="qr-title">Scan to Pay</div>
                <div class="qr-box">
                    <img src="{{ public_path('images/scanner.webp') }}" alt="QR">
                </div>
            </td>
            <td class="details-section">
                <div class="bank-box">
                    <table>
                        <tr>
                            <td class="bank-box-title">Bank</td>
                            <td>: STATE BANK OF INDIA</td>
                        </tr>
                        <tr>
                            <td class="bank-box-title">A/C Name</td>
                            <td>: SHRI BRAHMANI KHANDVI HOUSE</td>
                        </tr>
                        <tr>
                            <td class="bank-box-title">A/C No</td>
                            <td>: <strong>*00000044017465451*</strong></td>
                        </tr>
                        <tr>
                            <td class="bank-box-title">IFSC Code</td>
                            <td>: <strong>SBIN0011027</strong></td>
                        </tr>
                    </table>
                </div>

                <div class="words-box">
                    @php
                        if (class_exists('NumberFormatter')) {
                            $f = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
                            $words = ucwords($f->format((int) $totalOrderAmount));
                        } else {
                            $words = (string)(int) $totalOrderAmount;
                        }
                    @endphp
                    Amount in Words: <strong>Rupees {{ $words }} Only</strong>
                </div>

                <div class="total-box-grand">
                    <table>
                        <tr>
                            <td>Total Amount</td>
                            <td style="text-align:right;"> {{ number_format($totalOrderAmount, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    {{-- ── SIGNATURES ── --}}
    <table class="sign-table">
        <tr>
            <td style="width:200px; text-align:center; vertical-align:bottom; padding-bottom:0;">
                <img src="{{ public_path('images/hitesh_sign.webp') }}" class="sign-img" alt="Sign">
            </td>
            <td class="sign-gap"></td>
            <td style="width:200px; vertical-align:bottom; padding-bottom:0;"></td>
        </tr>
        <tr>
            <td class="sign-cell">For BHRAMANI KHANDVI HOUSE</td>
            <td class="sign-gap"></td>
            <td class="sign-cell">Customer Signature</td>
        </tr>
    </table>

    <div class="terms">
        * This is a computer-generated invoice. Goods once sold will not be taken back. Subject to Vadodara jurisdiction.
    </div>

</div>
</body>
</html>
