<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Order Bill - {{ $monthYear }}</title>

<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

@php
    /* ── Group orders by product ── */
    $groupedOrders = [];
    foreach ($orders as $order) {
        $key = $order->product_id ?? $order->product_name;

        if (!isset($groupedOrders[$key])) {
            $groupedOrders[$key] = [
                'product_name' => $order->product_name,
                'total_qty'    => 0,
                'unit'         => $order->unit ?? 'kg',
                'total_amount' => 0,
            ];
        }
        $groupedOrders[$key]['total_qty']    += (float) $order->order_quantity;
        $groupedOrders[$key]['total_amount'] += (float) $order->order_quantity * (float) $order->order_price;
    }

    /* ── Amount in words ── */
    if (class_exists('NumberFormatter')) {
        $f     = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
        $words = ucwords($f->format((int) $totalOrderAmount));
    } else {
        $words = (string)(int) $totalOrderAmount;
    }
@endphp

<style>
:root {
    --ink:      #3a2a1a;
    --accent:   #FF9933;
    --accent2:  #e07a1a;
    --dark:     #5C3A21;
    --muted:    #7a5c3a;
    --border:   #e0d0c0;
    --light-bg: #FFF7EE;
    --cream:    #fff9f2;
    --green:    #15803d;
    --green-bg: #dcfce7;
    --white:    #ffffff;
}

*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: 'Outfit', sans-serif;
    background: linear-gradient(135deg, #ffe4b5 0%, #fff3e0 50%, #fff9f2 100%);
    min-height: 100vh;
    padding: 30px 16px 100px;
    color: var(--ink);
}

/* ── Card ── */
.page {
    max-width: 820px;
    margin: 0 auto;
    background: var(--white);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 25px 60px rgba(15,23,42,.15), 0 4px 12px rgba(15,23,42,.08);
}

/* ── Top accent strip ── */
.page::before {
    content: '';
    display: block;
    height: 5px;
    background: linear-gradient(90deg, #5C3A21 0%, #FF9933 50%, #e07a1a 100%);
}

/* ── Header ── */
.header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 30px 40px 24px;
    border-bottom: 1px solid var(--border);
    background: linear-gradient(135deg, #5C3A21 0%, #8B5E3C 100%);
    color: #fff;
}

.brand-left {
    display: flex;
    align-items: center;
    gap: 14px;
}

.logo {
    width: 54px;
    height: 54px;
    border-radius: 12px;
    background: rgba(255,255,255,.15);
    padding: 5px;
    object-fit: contain;
}

.brand-name {
    font-size: 20px;
    font-weight: 900;
    letter-spacing: .5px;
    line-height: 1.2;
}

.brand-sub {
    font-size: 12px;
    font-weight: 400;
    opacity: .75;
    margin-top: 2px;
}

.header-right {
    text-align: right;
}

.invoice-label {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
    opacity: .7;
    margin-bottom: 4px;
}

.invoice-number {
    font-size: 22px;
    font-weight: 800;
}

.badge-paid {
    display: inline-block;
    margin-top: 6px;
    background: var(--green-bg);
    color: #15803d;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    padding: 4px 12px;
    border-radius: 20px;
}

/* ── Meta row ── */
.meta-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 24px 40px;
    border-bottom: 1px solid var(--border);
    background: var(--cream);
    gap: 20px;
}

.meta-block { flex: 1; }

.meta-label {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 6px;
}

.meta-value {
    font-size: 14px;
    font-weight: 700;
    color: var(--ink);
}

.meta-sub {
    font-size: 12px;
    font-weight: 500;
    color: var(--muted);
    margin-top: 2px;
}

.meta-block.right { text-align: right; }

/* ── Section padding ── */
.section {
    padding: 28px 40px;
}

.section + .section {
    border-top: 1px solid var(--border);
}

/* ── Business info strip ── */
.biz-strip {
    padding: 14px 40px;
    background: #fff3e0;
    border-bottom: 1px solid var(--border);
    display: flex;
    gap: 28px;
    font-size: 12px;
    color: var(--muted);
    font-weight: 500;
    flex-wrap: wrap;
}

.biz-strip span { display: flex; align-items: center; gap: 5px; }
.biz-strip b { color: var(--ink); }

/* ── Items table ── */
.items-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid var(--border);
}

.items-table thead tr {
    background: linear-gradient(90deg, #5C3A21 0%, #8B5E3C 100%);
}

.items-table th {
    padding: 13px 16px;
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    text-align: center;
}

.items-table th:first-child { text-align: left; }

.items-table tbody tr:nth-child(even) { background: #fff9f2; }
.items-table tbody tr:last-child td { border-bottom: none; }

.items-table td {
    padding: 13px 16px;
    font-size: 13px;
    font-weight: 600;
    text-align: center;
    border-bottom: 1px solid var(--border);
    color: var(--ink);
}

.items-table td:first-child {
    text-align: left;
    font-weight: 700;
}

.amount-cell {
    color: var(--green);
    font-weight: 800;
}

/* ── Bottom grid ── */
.bottom-grid {
    display: flex;
    gap: 24px;
    padding: 28px 40px;
    border-top: 1px solid var(--border);
    align-items: flex-start;
}

/* QR */
.qr-wrap { flex: 0 0 auto; text-align: center; }

.qr-title {
    font-size: 8px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 8px;
}

.qr-frame {
    width: 120px;
    height: 120px;
    border: 2px dashed #e0a060;
    border-radius: 10px;
    padding: 6px;
    background: #fff9f2;
}

.qr-frame img { width: 100%; height: 100%; object-fit: contain; }

/* Right col */
.right-col { flex: 1; display: flex; flex-direction: column; gap: 12px; }

.bank-card {
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 14px 16px;
    font-size: 12px;
    background: var(--cream);
}

.bank-card-title {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 10px;
}

.bank-row {
    display: flex;
    align-items: center;
    margin-bottom: 5px;
    gap: 8px;
}

.bank-key { width: 70px; color: var(--muted); font-weight: 600; flex-shrink: 0; }
.bank-val { font-weight: 700; color: var(--ink); }

.words-card {
    border: 1px solid #f5d9a8;
    border-radius: 10px;
    padding: 12px 16px;
    font-size: 12px;
    background: #fff8ed;
    color: #7a4a10;
    font-weight: 500;
    line-height: 1.5;
}

.words-card strong { font-weight: 800; }

/* Grand total */
.grand-total {
    background: linear-gradient(135deg, #5C3A21 0%, #FF9933 100%);
    color: #fff;
    border-radius: 12px;
    padding: 18px 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.grand-total-label {
    font-size: 13px;
    font-weight: 600;
    opacity: .75;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.grand-total-amount {
    font-size: 26px;
    font-weight: 900;
}

/* ── Signatures ── */
.sign-section {
    display: flex;
    justify-content: space-between;
    padding: 20px 40px 30px;
    border-top: 1px solid var(--border);
}

.sign-box {
    width: 200px;
    text-align: center;
}

.sign-line {
    border-top: 1.5px solid var(--ink);
    padding-top: 8px;
    font-size: 12px;
    font-weight: 700;
    color: var(--ink);
    letter-spacing: .3px;
}

.sign-sub {
    font-size: 10px;
    color: var(--muted);
    margin-top: 3px;
}

/* ── Footer strip ── */
.footer-strip {
    background: #fff3e0;
    border-top: 1px solid var(--border);
    padding: 12px 40px;
    font-size: 10px;
    color: var(--muted);
    text-align: center;
    font-weight: 500;
}

/* ── Download bar ── */
.download-bar {
    position: fixed;
    bottom: 0;
    width: 100%;
    background: rgba(255,255,255,.95);
    backdrop-filter: blur(10px);
    text-align: center;
    padding: 14px 20px;
    border-top: 1px solid var(--border);
    z-index: 100;
    display: flex;
    justify-content: center;
    gap: 12px;
}

.btn {
    padding: 12px 28px;
    border: none;
    border-radius: 8px;
    font-family: 'Outfit', sans-serif;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: opacity .2s;
}

.btn:hover { opacity: .85; }

.btn-primary {
    background: linear-gradient(135deg, #5C3A21 0%, #FF9933 100%);
    color: #fff;
}

.btn-secondary {
    background: var(--light-bg);
    color: var(--ink);
    border: 1px solid var(--border);
}

/* ── Responsive ── */
@media (max-width: 640px) {
    .header, .meta-row, .section, .bottom-grid, .sign-section, .biz-strip { padding-left: 20px; padding-right: 20px; }
    .meta-row, .bottom-grid { flex-direction: column; }
    .grand-total-amount { font-size: 20px; }
    .items-table th, .items-table td { font-size: 11px; padding: 9px 10px; }
}
</style>
</head>

<body>
<div class="page" id="bill-page">

    {{-- ── HEADER ── --}}
    <div class="header">
        <div class="brand-left">
            <div class="logo" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #FF9933, #e07a1a); color: white; font-weight: 900; font-size: 24px; letter-spacing: -1px;">
                 FH
            </div>
            <div>
                <div class="brand-name">BHRAMANI KHANDVI HOUSE</div>
                <div class="brand-sub">Premium Snacks &amp; Catering &nbsp;·&nbsp; Vadodara, Gujarat</div>
            </div>
        </div>
        <div class="header-right">
            <div class="invoice-label">Invoice</div>
            <div class="invoice-number">#{{ $receiptNo }}</div>
            <div class="badge-paid">&#10003; Paid</div>
        </div>
    </div>

    {{-- ── BUSINESS INFO STRIP ── --}}
    <div class="biz-strip">
        <span><b>FSSAI:</b> 20725032000942</span>
        <span><b>Mobile:</b> 9574659456</span>
        <span><b>Email:</b> patelhitesh0723a@gmail.com</span>
        <span>Shop No-06, Arkview Tower, Subhanpura, Vadodara – 390021</span>
    </div>

    {{-- ── META ROW ── --}}
    <div class="meta-row">
        <div class="meta-block">
            <div class="meta-label">Bill To</div>
            @if($customerInfo)
                <div class="meta-value">{{ $customerInfo->customer_name }}</div>
                <div class="meta-sub">{{ $customerInfo->shop_name }}</div>
                @if($customerInfo->shop_address)
                    <div class="meta-sub">{{ $customerInfo->shop_address }}{{ $customerInfo->city ? ', '.$customerInfo->city : '' }}</div>
                @endif
                @if($customerInfo->customer_number)
                    <div class="meta-sub">{{ $customerInfo->customer_number }}</div>
                @endif
            @else
                <div class="meta-value">All Customers</div>
            @endif
        </div>

        <div class="meta-block">
            <div class="meta-label">Period</div>
            <div class="meta-value">{{ $monthName ?? $monthYear }}</div>
        </div>

        <div class="meta-block right">
            <div class="meta-label">Date</div>
            <div class="meta-value">{{ now()->format('d M Y') }}</div>
            <div class="meta-sub">{{ now()->format('h:i A') }}</div>
        </div>
    </div>

    {{-- ── ITEMS TABLE ── --}}
    <div class="section" style="padding-bottom: 0;">
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width:42%;">Item Description</th>
                    <th style="width:18%;">Qty / Weight</th>
                    <th style="width:18%;">Unit Price</th>
                    <th style="width:22%;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groupedOrders as $item)
                <tr>
                    <td>{{ $item['product_name'] }}</td>
                    <td>{{ rtrim(rtrim(number_format($item['total_qty'], 4), '0'), '.') }} {{ __('portal.' . strtolower($item['unit'])) }}</td>
                    <td>{{ $item['total_qty'] > 0 ? number_format($item['total_amount'] / $item['total_qty'], 2) : '0.00' }}</td>
                    <td class="amount-cell">&#8377; {{ number_format($item['total_amount'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ── BOTTOM: QR + BANK + TOTAL ── --}}
    <div class="bottom-grid">

        <div class="qr-wrap">
            <div class="qr-title">Scan with UPI App to Pay</div>
            <div class="qr-frame">
                <img src="{{ asset('images/scanner.webp') }}" alt="QR Code">
            </div>
        </div>

        <div class="right-col">

            <div class="bank-card">
                <div class="bank-card-title">Bank Details</div>
                <div class="bank-row">
                    <span class="bank-key">Bank</span>
                    <span class="bank-val">State Bank of India</span>
                </div>
                <div class="bank-row">
                    <span class="bank-key">A/C Name</span>
                    <span class="bank-val">Shri Brahmani Khandvi House</span>
                </div>
                <div class="bank-row">
                    <span class="bank-key">A/C No.</span>
                    <span class="bank-val">00000044017465451</span>
                </div>
                <div class="bank-row">
                    <span class="bank-key">IFSC</span>
                    <span class="bank-val">SBIN0011027</span>
                </div>
            </div>

            <div class="words-card">
                Amount in Words: <strong>Rupees {{ $words }} Only</strong>
            </div>

            <div class="grand-total">
                <div>
                    <div class="grand-total-label">Grand Total</div>
                    @if($totalKg > 0)
                        <div style="font-size:11px; opacity:.65; margin-top:3px;">
                            {{ rtrim(rtrim(number_format($totalKg, 4), '0'), '.') }} {{ __('portal.kg') }}
                            @if($totalNang > 0) &nbsp;+&nbsp; {{ rtrim(rtrim(number_format($totalNang, 4), '0'), '.') }} {{ __('portal.nang') }} @endif
                        </div>
                    @endif
                </div>
                <div class="grand-total-amount">&#8377; {{ number_format($totalOrderAmount, 2) }}</div>
            </div>

        </div>
    </div>

    {{-- ── SIGNATURES ── --}}
    <div class="sign-section">
        <div class="sign-box">
            <div style="height:64px; display:flex; align-items:flex-end; justify-content:center; margin-bottom:6px; background:#fff;">
                <img src="{{ asset('images/hitesh_sign.webp') }}" alt="Authorized Sign"
                     style="height:60px; max-width:180px; object-fit:contain; display:block;
                            mix-blend-mode:multiply;">
            </div>
            <div class="sign-line">For BHRAMANI KHANDVI HOUSE</div>
            <div class="sign-sub">Authorized Signatory</div>
        </div>
        <div class="sign-box" style="margin-top: 66px;">
            <div class="sign-line">Customer Signature</div>
            <div class="sign-sub">Receiver</div>
        </div>
    </div>

    {{-- ── FOOTER ── --}}
    {{-- <div class="footer-strip">
        * Computer-generated invoice. Goods once sold will not be taken back. Subject to Vadodara jurisdiction.
    </div> --}}

</div>

{{-- ── DOWNLOAD BAR ── --}}
<div class="download-bar">
    <button class="btn btn-secondary" onclick="window.print()">&#128438; Print</button>
    <button class="btn btn-primary" onclick="downloadImage()">&#8659; Download Image</button>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
function downloadImage() {
    const btn = document.querySelectorAll('.btn');
    btn.forEach(b => b.disabled = true);

    const page = document.getElementById('bill-page');

    html2canvas(page, {
        scale: 2,
        useCORS: true,
        allowTaint: true,
        backgroundColor: '#ffffff',
        logging: false,
    }).then(canvas => {
        const link = document.createElement('a');
        link.download = 'Invoice-{{ $receiptNo }}.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
        btn.forEach(b => b.disabled = false);
    }).catch(() => {
        btn.forEach(b => b.disabled = false);
        alert('Download failed. Try using Print instead.');
    });
}
</script>

</body>
</html>
