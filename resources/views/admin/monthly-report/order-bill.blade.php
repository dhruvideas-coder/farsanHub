<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Order Bill - {{ $monthYear }}</title>
    <!-- Modern Google Font for Premium Look -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        /* A4 Setup */
        @page {
            size: A4 portrait;
            margin: 0;
        }
        body {
            font-family: 'Outfit', DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #000;
            line-height: 1.5;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: 10mm auto;
            background: #fff;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            position: relative;
        }
        @media print {
            body { background: #fff; }
            .page {
                margin: 0;
                box-shadow: none;
                width: 100%;
                min-height: 100%;
                padding: 15mm;
            }
        }

        /* Top Header */
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; }
        
        .header-table td {
            padding-bottom: 15px;
        }

        .brand-title {
            text-align: center;
            font-size: 28px;
            font-weight: 800;
            color: #000;
            text-transform: uppercase;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }

        /* Business Info */
        .info-table { margin-bottom: 20px; }
        .info-table td { font-size: 14px; line-height: 1.5; color: #333; }
        .info-left { width: 60%; }
        .info-right { width: 40%; text-align: right; }
        
        .label {
            font-weight: 700;
            color: #000;
        }

        .divider { 
            border-top: 1px solid #000; 
            margin: 20px 0; 
        }

        /* Party Info */
        .party-table { margin-bottom: 25px; width: 100%; }
        .party-left { width: 50%; }
        .party-right { width: 50%; text-align: right; vertical-align: top; }
        
        .val-box {
            font-weight: 700;
            color: #000;
            font-size: 14px;
            text-align: right;
        }

        .party-label {
            font-size: 16px;
            font-weight: 800;
            color: #000;
            text-transform: uppercase;
            margin-bottom: 5px;
            display: block;
        }
        .party-title {
            font-size: 20px;
            font-weight: 800;
            color: #000;
            margin-bottom: 5px;
            display: block;
        }
        .party-detail {
            font-size: 14px;
            color: #333;
            margin-bottom: 4px;
            margin-left: 15px;
        }

        /* Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            border: 1px solid #000;
        }
        .items-table thead {
            background: #f8f9fa;
        }
        .items-table th {
            padding: 12px;
            text-align: center;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            border: 1px solid #000;
        }
        .items-table th:first-child {
            text-align: left;
        }
        .items-table td {
            padding: 10px 12px;
            font-size: 14px;
            text-align: center;
            border: 1px solid #000;
            font-weight: 600;
            color: #000;
        }
        .items-table td.col-desc { 
            text-align: left; 
            font-weight: 700;
        }
        
        .items-row-content td { 
            height: 100px;
            border-top: none;
            border-bottom: none;
        }

        /* Footer Table inside items-table */
        .items-table tfoot td {
            background: #f8f9fa;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }

        /* Bottom section */
        .bottom-table { width: 100%; margin-bottom: 40px; }
        .qr-section { width: 35%; text-align: center; padding-right: 15px; }
        .details-section { width: 65%; }

        .qr-title {
            font-size: 15px;
            font-weight: 800;
            color: #000;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .qr-box {
            width: 160px;
            height: 160px;
            margin: 0 auto;
            background: #ffffff;
            padding: 5px;
            border: 1px dashed #000;
        }
        .qr-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .bank-box {
            border: 1px solid #000;
            padding: 15px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .bank-box table { width: 100%; border-collapse: collapse; }
        .bank-box table td { padding: 4px 0; font-weight: 600; color: #000; }
        .bank-box table td:first-child { width: 30px; font-size: 14px; }
        .bank-box-title { color: #555; width: 100px; font-weight: 700; }
        
        .words-box {
            border: 1px solid #000;
            padding: 12px 15px;
            margin-bottom: 15px;
            font-size: 14px;
            color: #000;
            font-weight: 600;
        }
        .words-box em {
            font-style: normal;
            font-weight: 800;
        }

        .total-box-grand {
            border: 1px solid #000;
            background: #f8f9fa;
            color: #000;
            padding: 15px 20px;
        }
        .total-box-grand table { width: 100%; }
        .total-box-grand td { font-size: 20px; font-weight: 800; vertical-align: middle; }

        /* Signatures */
        .sign-table { width: 100%; border-collapse: collapse; margin-top: 60px; }
        .sign-table td { font-size: 14px; font-weight: 700; color: #000; padding-top: 10px; }
        .sign-left { text-align: center; border-top: 1px solid #000; width: 250px; }
        .sign-right { text-align: center; border-top: 1px solid #000; width: 250px;}

    </style>
</head>
<body>
<div class="page">
    <table class="header-table">
        <tr>
            <td style="text-align: left; font-size: 14px; font-weight: 700; text-transform: uppercase;">Jay Ma Bhramani</td>
            <td style="text-align: center; font-size: 18px; font-weight: bold;">Invoice</td>
            <td style="text-align: right; font-size: 14px; font-weight: 700; text-transform: uppercase;">Jay Shree Krishna</td>
        </tr>
    </table>

    <div class="brand-title">
        BHRAMANI KHANDVI HOUSE
    </div>

    <table class="info-table">
        <tr>
            <td class="info-left">
                <div style="margin-bottom: 4px;"><span class="label">FSSAI Lic No.:</span> 12345678901234</div>
                <div style="margin-top: 10px;">
                    <span class="label">Address:</span><br>
                    Shop No-06, Arkview Tower,<br>
                    near Hari Om Subhanpura Water Tank,<br>
                    Subhanpura, Vadodara, Gujarat – 390021
                </div>
            </td>
            <td class="info-right">
                <div style="margin-bottom: 4px;"><span class="label">Email:</span> patelhitesh0723a@gmail.com</div>
                <div style="margin-bottom: 4px;"><span class="label">Mobile:</span> 9510036176</div>
                <!-- <div style="margin-bottom: 4px;"><span class="label">Website:</span> https://khandvihouse.com</div> -->
            </td>
        </tr>
    </table>

    <div class="divider"></div>

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
                    <span class="party-title">All customers</span>
                @endif
            </td>
            <td class="party-right">
                <div style="font-size: 15px; margin-top: 5px;">
                    <span class="label" style="margin-right:5px;">Invoice No.:</span><span class="val-box" style="margin-right:20px;">{{ $receiptNo }}</span>
                    <span class="label" style="margin-right:5px;">Date:</span><span class="val-box">{{ now()->format('d / m / Y') }}</span>
                </div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 45%;">Item Description</th>
                <th style="width: 20%;">Qty / Weight</th>
                <th style="width: 15%;">Unit Price (&#8377;)</th>
                <th style="width: 20%;">Amount (&#8377;)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $groupedOrders = [];
                foreach($orders as $order) {
                    $key = $order->product_name;
                    if (!isset($groupedOrders[$key])) {
                        $groupedOrders[$key] = [
                            'product_name' => $order->product_name,
                            'total_qty' => 0,
                            'unit' => $order->unit ?? 'kg',
                            'total_amount' => 0,
                        ];
                    }
                    $groupedOrders[$key]['total_qty'] += (float) $order->order_quantity;
                    $groupedOrders[$key]['total_amount'] += ((float) $order->order_quantity * (float) $order->order_price);
                }
            @endphp
            @foreach($groupedOrders as $item)
                <tr>
                    <td class="col-desc">{{ $item['product_name'] }}</td>
                    <td>{{ rtrim(rtrim(number_format($item['total_qty'], 4), '0'), '.') }} {{ $item['unit'] }}</td>
                    <td>{{ $item['total_qty'] > 0 ? number_format($item['total_amount'] / $item['total_qty'], 2) : '0.00' }}</td>
                    <td>{{ number_format($item['total_amount'], 2) }}</td>
                </tr>
            @endforeach
            <!-- Blank spacing row for receipt feel -->
            <tr class="items-row-content">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td style="text-align: right; font-weight: 800; font-size: 15px; padding: 14px;">GRAND TOTAL:</td>
                <td style="text-align: center; font-weight: 800; font-size: 14px; padding: 14px;">
                    @if($totalKg > 0)
                        <span style="display:block; margin-bottom:4px;">{{ rtrim(rtrim(number_format($totalKg, 4), '0'), '.') }} <span style="font-size:11px; color:#555;">KG</span></span>
                    @endif
                    @if($totalNang > 0)
                        <span style="display:block;">{{ rtrim(rtrim(number_format($totalNang, 4), '0'), '.') }} <span style="font-size:11px; color:#555;">NANG</span></span>
                    @endif
                </td>
                <td style="background: #fff;"></td>
                <td style="text-align: center; font-weight: 800; font-size: 16px; padding: 14px;">
                    &#8377; {{ number_format($totalOrderAmount, 2) }}
                </td>
            </tr>
        </tfoot>
    </table>

    <table class="bottom-table">
        <tr>
            <td class="qr-section">
                <div class="qr-title">Scan to Pay</div>
                <div class="qr-box">
                    <img src="{{ public_path('images/scanner.webp') }}" alt="QR SCANNER">
                </div>
            </td>
            <td class="details-section">
                <div class="bank-box">
                    <table>
                        <tr><td>👉</td><td class="bank-box-title">BANK</td><td>:: STATE BANK OF INDIA</td></tr>
                        <tr><td>👉</td><td class="bank-box-title">A/C NAME</td><td>:: SHRI BRAHMANI KHANDVI HOUSE</td></tr>
                        <tr><td>👉</td><td class="bank-box-title">A/C NO</td><td>:: <span style="font-weight: 800;">*00000044017465451*</span></td></tr>
                        <tr><td>👉</td><td class="bank-box-title">IFSC CODE</td><td>:: <span style="font-weight: 800;">SBIN0011027</span></td></tr>
                    </table>
                </div>
                
                <div class="words-box">
                    @php
                        if (class_exists('NumberFormatter')) {
                            $f = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
                            $words = ucwords($f->format((int)$totalOrderAmount));
                        } else {
                            $words = (string) (int)$totalOrderAmount;
                        }
                    @endphp
                    Amount in Words: <em>Rupees {{ $words }} Only</em>
                </div>
                
                <div class="total-box-grand">
                    <table>
                        <tr>
                            <td>Total Amount</td>
                            <td style="text-align:right;">&#8377; {{ number_format($totalOrderAmount, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <table class="sign-table">
        <tr>
            <td class="sign-left">For BHRAMANI KHANDVI HOUSE</td>
            <td style="border:none;"></td>
            <td class="sign-right">Customer Signature</td>
        </tr>
    </table>

</div>
</body>
</html>
