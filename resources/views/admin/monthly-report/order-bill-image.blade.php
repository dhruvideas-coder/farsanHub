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

        body {
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: #000;
            line-height: 1.5;
            background: #f1f5f9;
        }
        .page {
            margin: 20px auto 100px;
            max-width: 900px;
            background: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid #ccc;
        }

        /* Top Header */
        .top-chant-container {
            display: flex;
            justify-content: space-between;
            color: #000;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .brand-title {
            text-align: center;
            font-size: 28px;
            font-weight: 800;
            color: #000;
            text-transform: uppercase;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }

        /* Business Info */
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .info-left {
            width: 60%;
            font-size: 14px;
        }
        .info-right {
            width: 40%;
            text-align: right;
            font-size: 14px;
        }
        .label {
            font-weight: 700;
            color: #000;
        }
        .info-text {
            color: #333;
            margin-bottom: 4px;
            font-weight: 500;
        }

        .divider { 
            border-top: 1px solid #000; 
            margin: 20px 0; 
        }

        /* Party Info */
        .party-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding-bottom: 10px;
        }
        .party-left { 
            width: 60%; 
        }
        .party-right { 
            width: 40%; 
            display: flex;
            justify-content: flex-end;
            align-items: flex-start;
        }
        
        .val-box {
            display: inline-block;
            font-weight: 700;
            color: #000;
            min-width: 100px;
            text-align: right;
            font-size: 14px;
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
            font-weight: 500;
            margin-left: 20px;
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
            color: #000;
        }
        .items-table th:first-child {
            text-align: left;
        }
        .items-table td {
            padding: 12px 14px;
            font-size: 14px;
            text-align: center;
            border: 1px solid #000;
            font-weight: 600;
            color: #000;
        }
        .items-table td.col-desc { 
            text-align: left; 
            font-weight: 700;
            color: #000;
        }
        
        .items-row-content td { 
            height: auto;
            min-height: 100px;
            padding: 40px 14px; 
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
        .bottom-section { 
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .qr-section { 
            width: 32%; 
            text-align: center; 
        }
        .details-section { 
            width: 65%; 
        }

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
        .bank-box table td { 
            padding: 4px 0; 
            font-weight: 600;
            color: #000;
        }
        .bank-box table td:first-child { width: 30px; font-size: 16px; }
        .bank-box table td:nth-child(2) { width: 100px; font-weight: 700; }
        
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 20px;
            font-weight: 800;
        }

        /* Signatures */
        .sign-section { 
            display: flex;
            justify-content: space-between;
            margin-top: 60px; 
        }
        .sign-box { 
            width: 250px; 
            border-top: 1px solid #000; 
            padding-top: 10px; 
            text-align: center;
            font-size: 14px;
            font-weight: 700;
            color: #000;
        }
        .sign-box.left { text-align: center; }

        /* UI Download Bar */
        .download-bar {
            text-align: center;
            padding: 15px 20px;
            background: #ffffff;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 1px solid #ccc;
            z-index: 1000;
            box-shadow: 0 -4px 15px rgba(0,0,0,0.05);
        }
        .download-bar button {
            background: #000;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 800;
            font-family: inherit;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .download-bar button:hover {
            opacity: 0.8;
        }
        
        #generated-image-container {
            display: none;
            text-align: center;
            margin-bottom: 120px;
            padding: 20px;
        }
        #generated-image-container img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
<div class="page">
    <div class="top-chant-container">
        <span>Jay Ma Bhramani</span>
        <span style="font-size: 18px; font-weight: bold;">Invoice</span>
        <span>Jay Shree Krishna</span>
    </div>
    <div class="brand-title">
        &#8764; BHRAMANI KHANDVI HOUSE &#8764;
    </div>

    <div class="info-section">
        <div class="info-left">
            <div class="info-text"><span class="label">FSSAI Lic No.:</span> 12345678901234</div>
            <div class="info-text" style="margin-top: 10px;">
                <span class="label">Address:</span><br>
                Shop No-06, Arkview Tower,<br>
                near Hari Om Subhanpura Water Tank,<br>
                Subhanpura, Vadodara, Gujarat – 390021
            </div>
        </div>
        <div class="info-right">
            <div class="info-text"><span class="label">Email:</span> patelhitesh0723a@gmail.com</div>
            <div class="info-text"><span class="label">Mobile:</span> 9510036176</div>
            <!-- <div class="info-text"><span class="label">Website:</span> https://khandvihouse.com</div> -->
        </div>
    </div>

    <div class="divider"></div>

    <div class="party-section">
        <div class="party-left">
            <span class="party-label">Bill To:</span>
            @if($customerInfo)
                <span class="party-title">{{ $customerInfo->customer_name }}</span>
                <div class="party-detail">{{ $customerInfo->shop_name }}</div>
                <div class="party-detail"><span class="label">Address:</span> {{ $customerInfo->shop_address }}{{ $customerInfo->city ? ', '.$customerInfo->city : '' }}</div>
                <div class="party-detail"><span class="label">Phone:</span> {{ $customerInfo->customer_number }}</div>
            @else
                <span class="party-title">All customers</span>
            @endif
        </div>
        <div class="party-right">
            <table style="text-align: right; border-collapse: separate; border-spacing: 0 12px;">
                <tr>
                    <td class="label">Date:</td>
                    <td><div class="val-box">{{ now()->format('d / m / Y') }}</div></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px;" class="label">Invoice No.:</td>
                    <td><div class="val-box">{{ $receiptNo }}</div></td>
                </tr>
            </table>
        </div>
    </div>

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
                <td style="border-bottom:none; border-top:none;"></td>
                <td style="border-bottom:none; border-top:none;"></td>
                <td style="border-bottom:none; border-top:none;"></td>
                <td style="border-bottom:none; border-top:none;"></td>
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

    <div class="bottom-section">
        <div class="qr-section">
            <div class="qr-title">Scan to Pay</div>
            <div class="qr-box">
                <img src="{{ asset('images/scanner.webp') }}" alt="QR SCANNER" crossorigin="anonymous">
            </div>
        </div>
        <div class="details-section">
            <div class="bank-box">
                <table>
                    <tr><td>👉</td><td>BANK</td><td>:: STATE BANK OF INDIA</td></tr>
                    <tr><td>👉</td><td>A/C NAME</td><td>:: SHRI BRAHMANI KHANDVI HOUSE</td></tr>
                    <tr><td>👉</td><td>A/C NO</td><td>:: <span style="font-weight: 800;">*00000044017465451*</span></td></tr>
                    <tr><td>👉</td><td>IFSC CODE</td><td>:: <span style="font-weight: 800;">SBIN0011027</span></td></tr>
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
                <span>Total Amount</span>
                <span>&#8377; {{ number_format($totalOrderAmount, 2) }}</span>
            </div>
        </div>
    </div>

    <div class="sign-section">
        <div class="sign-box left">For BHRAMANI KHANDVI HOUSE</div>
        <div class="sign-box right">Customer Signature</div>
    </div>

</div>

<!-- UI Download Button -->
<div class="download-bar">
    <button onclick="downloadImage()" id="downloadBtn">Download as Image</button>
    <div id="mobile-hint" style="display:none; margin-top:10px; font-size:12px; color:#64748b;">If download fails on mobile, please capture a screenshot or long-press the generated image below.</div>
</div>

<div id="generated-image-container">
    <p style="color:#d97706; font-weight:bold; margin-bottom:10px; font-size:16px;">Your image is ready! Long-press the image below to save.</p>
    <img id="output-img" src="" alt="Generated Bill">
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    function downloadImage() {
        const btn = document.getElementById('downloadBtn');
        btn.innerText = 'Generating...';
        btn.disabled = true;

        const page = document.querySelector('.page');
        
        const origMargin = page.style.margin;
        const origShadow = page.style.boxShadow;
        page.style.margin = "0";
        page.style.boxShadow = "none";

        // Lowering scale to 1.5 to prevent memory crash on mobile devices
        html2canvas(page, { scale: 1.5, useCORS: true }).then(canvas => {
            const dataUrl = canvas.toDataURL('image/png');
            
            // Append and click technique for better mobile support
            const link = document.createElement('a');
            link.download = `Order-Bill-{{ $receiptNo ?? 'Export' }}.png`;
            link.href = dataUrl;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Also show the image incase the forced download is blocked by Safari/Mobile
            document.getElementById('generated-image-container').style.display = 'block';
            document.getElementById('output-img').src = dataUrl;
            document.getElementById('mobile-hint').style.display = 'block';
            
            page.style.display = 'none'; // hide the HTML version to avoid confusion
            
            btn.innerText = 'Download Again';
            btn.disabled = false;
        }).catch(err => {
            alert("Could not generate image. Please try again.");
            btn.innerText = 'Download as Image';
            btn.disabled = false;
            page.style.margin = origMargin;
            page.style.boxShadow = origShadow;
        });
    }
</script>
</body>
</html>
