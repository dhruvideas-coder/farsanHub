<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Order Bill - {{ $monthYear }}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111;
            line-height: 1.4;
            background: #fbfbf6; /* Slight yellowish tint */
        }
        .page {
            padding: 30px;
            background: #fefef9;
        }

        /* Top Header */
        .top-chant {
            text-align: center;
            color: #b08d55;
            font-size: 12px;
            margin-bottom: 5px;
        }
        .brand-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #4a5c43;
            text-transform: uppercase;
            font-style: italic;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }

        /* Business Info */
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        .info-table td { font-size: 10px; color: #222; vertical-align: top; }
        .info-left { width: 50%; }
        .info-right { width: 50%; }

        .divider { border-top: 2px solid #b08d55; margin: 10px 0; }

        /* Party Info */
        .party-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .party-table td { vertical-align: top; font-size: 11px; }
        .party-left { width: 60%; padding-right: 10px; }
        .party-right { width: 40%; }
        
        .val-box {
            display: inline-block;
            border: 1px solid #b08d55;
            border-radius: 5px;
            padding: 3px 6px;
            background: #f9faec;
            margin-left: 5px;
            min-width: 100px;
        }

        .party-title {
            font-size: 14px;
            font-style: italic;
            font-weight: bold;
            color: #111;
        }

        /* Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 1px solid #7c9372;
            border-radius: 5px; /* Dompdf doesn't heavily support table rounded borders well but helps */
        }
        .items-table th {
            background: #cedebb;
            color: #111;
            padding: 8px;
            text-align: center;
            font-size: 11px;
            border: 1px solid #7c9372;
        }
        .items-table td {
            padding: 8px;
            border: 1px solid #7c9372;
            font-size: 11px;
            text-align: center;
        }
        .items-table td.col-desc { text-align: left; }
        
        /* Fixed height for items min view */
        .items-row-content { 
            height: 200px;
            vertical-align: top;
        }

        /* Bottom section */
        .bottom-table { width: 100%; border-collapse: collapse; margin-bottom: 50px; }
        .bottom-table td { vertical-align: top; }
        .qr-section { width: 35%; text-align: center; }
        .details-section { width: 65%; padding-left: 20px; }

        .qr-title {
            font-size: 12px;
            margin-bottom: 5px;
            color: #333;
        }
        .qr-box {
            width: 120px;
            height: 120px;
            border: 2px dashed #b08d55;
            margin: 0 auto;
            text-align: center;
            line-height: 120px;
            color: #999;
            font-size: 10px;
        }

        .subtotal-cell {
            background: #cedebb;
            font-weight: bold;
            text-align: right;
            padding: 8px;
        }

        .bank-box, .words-box, .total-box {
            border: 1px solid #b08d55;
            border-radius: 5px;
            background: #fdfcee;
            padding: 6px 10px;
            margin-bottom: 10px;
            font-size: 10px;
        }
        .bank-box p { margin: 2px 0; }
        .bank-icon { color: #d69e2e; }

        .total-box {
            background: #cedebb;
            font-weight: bold;
            font-size: 12px;
            display: flex; /* ignored by dompdf mostly */
        }
        
        .total-box-table { width:100%; border-collapse: collapse; }
        .total-box-table td { font-size:12px; font-weight:bold; }
        
        /* Signatures */
        .sign-table { width: 100%; border-collapse: collapse; margin-top: 40px; }
        .sign-table td { width: 50%; padding-top: 40px; border-top: 1px solid #111; font-size: 11px; }
        .sign-left { text-align: left; }
        .sign-right { text-align: right; }
    </style>
</head>
<body>
<div class="page">
    <div class="top-chant">Om Shree Swami Shreeji Om</div>
    <div class="brand-title">
        &#8764; BHRAMANI KHANDVI HOUSE &#8764;
    </div>

    <table class="info-table">
        <tr>
            <td class="info-left">
                FSSAI Lic No.: 12345678901234<br>
                Mobile: 9510036176<br><br>
                Address: Shop No-06, Arkview Tower,<br>
                near Hari Om Subhanpura Water Tank,<br>
                Subhanpura, Vadodara, Gujarat – 390021
            </td>
            <td class="info-right" style="vertical-align: bottom;">
                Email: patelhitesh0723a@gmail.com<br>
                Website: https://khandvihouse.com
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="party-table">
        <tr>
            <td class="party-left">
                <span style="font-size:12px; font-weight:bold;">Bill To:</span>
                @if($customerInfo)
                    <span class="party-title">{{ $customerInfo->customer_name }}</span><br>
                    <span style="margin-left: 45px;">{{ $customerInfo->shop_name }}</span><br>
                    <span style="margin-left: 45px;">Customer Address: {{ $customerInfo->shop_address }}{{ $customerInfo->city ? ', '.$customerInfo->city : '' }}</span><br>
                    <span style="margin-left: 45px;">Customer Phone: {{ $customerInfo->customer_number }}</span>
                @else
                    <span class="party-title">All customers</span>
                @endif
            </td>
            <td class="party-right">
                <table style="width:100%; border-collapse:separate; border-spacing:0 8px;">
                    <tr>
                        <td style="width:70px; font-weight:bold;">Date:</td>
                        <td><div class="val-box">{{ now()->format('d / m / Y') }}</div></td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;">Invoice No.:</td>
                        <td><div class="val-box">{{ $receiptNo }}</div></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 45%; text-align: left;">Item Description</th>
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
                <td style="border-bottom:none; border-top:none;"></td>
                <td style="border-bottom:none; border-top:none;"></td>
                <td style="border-bottom:none; border-top:none;"></td>
                <td style="border-bottom:none; border-top:none;"></td>
            </tr>
        </tbody>
        <tfoot>
            <tr style="background: #eef3e6; border-top: 2px solid #7c9372;">
                <td style="text-align: right; font-weight: bold; font-size: 12px; padding: 10px; color: #4a5c43; text-transform: uppercase;">Grand Total:</td>
                <td style="text-align: center; font-weight: bold; font-size: 11px; padding: 10px; color: #111;">
                    @if($totalKg > 0)
                        <span style="display:block; margin-bottom:2px;">{{ rtrim(rtrim(number_format($totalKg, 4), '0'), '.') }} <span style="font-size:9px; color:#666;">KG</span></span>
                    @endif
                    @if($totalNang > 0)
                        <span style="display:block;">{{ rtrim(rtrim(number_format($totalNang, 4), '0'), '.') }} <span style="font-size:9px; color:#666;">NANG</span></span>
                    @endif
                </td>
                <td style="background: #fbfbf6; border-bottom: none; border-top: 2px solid #7c9372;"></td>
                <td style="text-align: center; font-weight: bold; font-size: 13px; padding: 10px; color: #4a5c43; border-top: 2px solid #7c9372; background: #cedebb;">
                    &#8377; {{ number_format($totalOrderAmount, 2) }}
                </td>
            </tr>
        </tfoot>
    </table>

    <table class="bottom-table">
        <tr>
            <td class="qr-section">
                <div class="qr-title">Scan to Pay</div>
                <div class="qr-box border-0" style="border: none;">
                    <img src="{{ public_path('images/scanner.webp') }}" style="max-width: 100%; max-height: 100px; display: block; margin: 0 auto;" alt="QR SCANNER">
                </div>
            </td>
            <td class="details-section">
                <div class="bank-box">
                    <table style="width:100%;">
                        <tr><td style="width:20px;">👉</td><td style="width:70px;">BANK</td><td>:: STATE BANK OF INDIA</td></tr>
                        <tr><td>👉</td><td>A/C NAME</td><td>:: SHRI BRAHMANI KHANDVI HOUSE</td></tr>
                        <tr><td>👉</td><td>A/C NO</td><td>:: *00000044017465451*</td></tr>
                        <tr><td>👉</td><td>IFSC CODE</td><td>:: SBIN0011027</td></tr>
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
                
                <div class="bank-box" style="background: #cedebb; padding: 2px;">
                    <table class="total-box-table">
                        <tr>
                            <td style="padding-left:10px;">Total Amount</td>
                            <td style="text-align:right; padding-right:10px;">&#8377; {{ number_format($totalOrderAmount, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <table class="sign-table">
        <tr>
            <td class="sign-left" style="width:250px;">For BHRAMANI KHANDVI HOUSE</td>
            <td style="border:none;"></td>
            <td class="sign-right" style="width:200px;">Customer Signature</td>
        </tr>
    </table>

</div>
</body>
</html>
