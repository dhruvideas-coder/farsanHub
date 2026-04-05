@extends('layouts.app')

<style>
/* ── STAT CARDS ─────────────────────────────────── */
.dash-card {
    border-radius: 14px;
    border: none;
    overflow: hidden;
    transition: transform .2s ease, box-shadow .2s ease;
    box-shadow: 0 2px 12px rgba(0,0,0,.07);
}
.dash-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }

.dash-card .icon-box {
    width: 54px; height: 54px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}
.dash-card .stat-label { font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: .6px; color: #9ca3af; }
.dash-card .stat-value { font-size: 26px; font-weight: 700; color: #1c1917; line-height: 1.1; margin-top: 4px; }
.dash-card .trend       { font-size: 11.5px; margin-top: 6px; }
.dash-card .trend.up    { color: #10b981; }
.dash-card .trend.down  { color: #ef4444; }
.dash-card .trend.neutral { color: #9ca3af; }

/* ── CHART CARDS ────────────────────────────────── */
.chart-card {
    border-radius: 14px;
    border: none;
    box-shadow: 0 2px 12px rgba(0,0,0,.07);
}
.chart-card .chart-title {
    font-size: 13px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .6px; color: #44403c;
}
.chart-card .chart-sub { font-size: 11px; color: #a8a29e; }

/* ── TABLE CARDS ────────────────────────────────── */
.table-card {
    border-radius: 14px; border: none;
    box-shadow: 0 2px 12px rgba(0,0,0,.07);
}
.table-card .section-label {
    font-size: 12px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .6px; color: #44403c; margin-bottom: 14px;
}
.dash-table { width: 100%; border-collapse: collapse; }
.dash-table th {
    font-size: 14px !important; font-weight: 700; text-transform: uppercase; letter-spacing: .5px;
    color: #a8a29e; padding: 8px 10px; border-bottom: 2px solid #f5f5f4;
}
.dash-table td { font-size: 12px; color: #292524; padding: 10px 10px; border-bottom: 1px solid #f5f5f4; vertical-align: middle; }
.dash-table tr:last-child td { border-bottom: none; }
.dash-table .main-text { font-weight: 600; color: #1c1917; }
.dash-table .sub-text  { font-size: 10.5px; color: #a8a29e; margin-top: 1px; }
.dash-table .amt       { font-weight: 700; color: #d97706; }

/* ── TOP CUSTOMER LIST ──────────────────────────── */
.cust-row {
    display: flex; align-items: center; gap: 12px;
    padding: 9px 0; border-bottom: 1px solid #f5f5f4;
}
.cust-row:last-child { border-bottom: none; }
.cust-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: #fef3c7; color: #92400e;
    font-size: 13px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.cust-name  { font-size: 12.5px; font-weight: 600; color: #1c1917; }
.cust-shop  { font-size: 10.5px; color: #a8a29e; }
.cust-badge {
    margin-left: auto; background: #fef3c7; color: #92400e;
    font-size: 10px; font-weight: 700; padding: 3px 9px;
    border-radius: 20px; white-space: nowrap;
}

/* ── PRODUCT BAR ────────────────────────────────── */
.prod-row { margin-bottom: 14px; }
.prod-row:last-child { margin-bottom: 0; }
.prod-label { display: flex; justify-content: space-between; margin-bottom: 4px; }
.prod-label span:first-child { font-size: 12px; font-weight: 600; color: #292524; }
.prod-label span:last-child  { font-size: 11px; color: #a8a29e; }
.prod-bar-wrap { height: 8px; background: #f5f5f4; border-radius: 99px; overflow: hidden; }
.prod-bar-fill { height: 100%; border-radius: 99px; background: linear-gradient(90deg, #d97706, #fbbf24); }

/* ── MONTH BADGE ────────────────────────────────── */
.month-badge {
    display: inline-block; padding: 3px 10px; border-radius: 20px;
    background: #fef3c7; color: #92400e; font-size: 10.5px; font-weight: 600;
}

/* ── AMBER ACCENT ───────────────────────────────── */
.text-amber { color: #d97706; }
.bg-amber-soft { background: #fef3c7; }
.border-amber  { border-color: #fcd34d !important; }

/* ── ORDERS PRODUCT FILTER SELECT ───────────────── */
.orders-prod-select {
    width: 100%;
    padding: 5px 28px 5px 10px;
    border-radius: 8px;
    border: 1.5px solid #fde68a;
    font-size: 11.5px;
    color: #374151;
    background: #fffbeb url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23d97706'/%3E%3C/svg%3E") no-repeat right 10px center;
    appearance: none;
    -webkit-appearance: none;
    cursor: pointer;
    transition: border-color .15s, box-shadow .15s;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.orders-prod-select:focus {
    outline: none;
    border-color: #d97706;
    box-shadow: 0 0 0 3px rgba(217,119,6,.15);
    background-color: #fff;
}
/* ── LOADING STATE ──────────────────────────────── */
.loading-active .dash-card,
.loading-active .chart-card,
.loading-active .table-card {
    position: relative;
    pointer-events: none;
}
.loading-active .dash-card::after,
.loading-active .chart-card::after,
.loading-active .table-card::after {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(255,255,255,0.6);
    backdrop-filter: blur(2px);
    z-index: 10;
    border-radius: 14px;
    animation: pulse 1.5s infinite;
}
@keyframes pulse {
    0% { opacity: 0.5; }
    50% { opacity: 0.8; }
    100% { opacity: 0.5; }
}

/* ── RESPONSIVE OVERRIDES ───────────────────────── */
@media (max-width: 991.98px) {
    .page-title { font-size: 1.5rem; }
    .stat-value { font-size: 22px; }
    .icon-box   { width: 44px; height: 44px; font-size: 18px; }
}

@media (max-width: 767.98px) {
    .page-header { margin-bottom: 1.5rem !important; }
    .dash-card { padding: 1rem !important; }
    .stat-value { font-size: 20px; }
    .chart-card, .table-card { padding: 1.25rem !important; }
    
    /* Keep 2 columns on small screens instead of 1 if possible, but stack filters */
    .period-filters { width: 100%; margin-top: 1rem; }
}

@media (max-width: 575.98px) {
    .stat-value { font-size: 18px; }
    .stat-label { font-size: 10px; }
    .dash-table th { font-size: 11px !important; }
    .dash-table td { font-size: 11px; padding: 8px 5px; }
    .chart-title { font-size: 11px; }
}
</style>

@section('content')

{{-- ── PAGE HEADER ──────────────────────────────────── --}}
<div class="page-header d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-2">
        <h1 class="page-title">{{ @trans('messages.dashboard') }}</h1>
        <div class="d-flex align-items-center gap-1 ms-2">
            <span class="badge bg-amber-soft text-amber px-2 py-1" style="font-size:10px; border-radius:4px; font-weight:700;">SYSTEM LIVE</span>
        </div>
    </div>
    <div class="d-flex align-items-center gap-3">
        <ol class="breadcrumb d-none d-md-flex mb-0" style="background:transparent; padding:0;">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color:#d97706; text-decoration:none;">Admin</a></li>
            <li class="breadcrumb-item active" aria-current="page">Overview</li>
        </ol>
    </div>
</div>

{{-- ── SECTION 1 : GLOBAL SYSTEM OVERVIEW (Static) ───── --}}
<div class="mb-5">
    <div class="d-flex align-items-center gap-2 mb-3">
        <div style="width:4px; height:20px; background:#1c1917; border-radius:2px;"></div>
        <h2 style="font-size:14px; font-weight:800; text-transform:uppercase; letter-spacing:1px; color:#1c1917; margin:0;">Global System Overview</h2>
        <span style="font-size:11px; color:#a8a29e; font-weight:500;">(All-time statistics)</span>
    </div>

    <div class="row g-3 mb-4">
        {{-- Total Customers --}}
        <div class="col-6 col-md-3">
            <div class="card dash-card p-3 border-0" style="background:#fff; height:100%;">
                <div class="stat-label">Total Customers</div>
                <div class="d-flex justify-content-between align-items-end mt-1">
                    <div class="stat-value">{{ number_format($global['totalCustomers']) }}</div>
                    <div class="icon-box" style="width:32px; height:32px; background:#eff6ff; font-size:14px; color:#3b82f6;"><i class="fa fa-users"></i></div>
                </div>
            </div>
        </div>
        {{-- Total Products --}}
        <div class="col-6 col-md-3">
            <div class="card dash-card p-3 border-0" style="background:#fff; height:100%;">
                <div class="stat-label">Total Products</div>
                <div class="d-flex justify-content-between align-items-end mt-1">
                    <div class="stat-value">{{ number_format($global['totalProducts']) }}</div>
                    <div class="icon-box" style="width:32px; height:32px; background:#ecfdf5; font-size:14px; color:#10b981;"><i class="fa fa-cube"></i></div>
                </div>
            </div>
        </div>
        {{-- Total Orders --}}
        <div class="col-6 col-md-3">
            <div class="card dash-card p-3 border-0" style="background:#fff; height:100%;">
                <div class="stat-label">All-time Orders</div>
                <div class="d-flex justify-content-between align-items-end mt-1">
                    <div class="stat-value">{{ number_format($global['allTimeOrders']) }}</div>
                    <div class="icon-box" style="width:32px; height:32px; background:#fff7ed; font-size:14px; color:#d97706;"><i class="fa fa-shopping-cart"></i></div>
                </div>
            </div>
        </div>
        {{-- Total Revenue --}}
        <div class="col-6 col-md-3">
            <div class="card dash-card p-3 border-0" style="background:#fff; height:100%;">
                <div class="stat-label">Global Revenue</div>
                <div class="d-flex justify-content-between align-items-end mt-1">
                    <div class="stat-value" style="color:#db2777;">₹{{ number_format($global['allTimeRevenue'] / 1000, 1) }}k</div>
                    <div class="icon-box" style="width:32px; height:32px; background:#fdf2f8; font-size:14px; color:#db2777;"><i class="fa fa-money"></i></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Monthly Grid (Chart) --}}
    <div class="card chart-card p-4 border-0" style="background:#fff;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <div class="chart-title" style="font-size:12px; color:#1c1917;">Monthly Growth trajectory</div>
                <div class="chart-sub">System-wide performance for the last 6 months</div>
            </div>
            <div class="d-flex gap-3">
                <div class="d-flex align-items-center gap-1" style="font-size:11px; font-weight:600; color:#44403c;">
                    <span style="width:10px; height:10px; background:#d97706; border-radius:2px;"></span> Revenue
                </div>
                <div class="d-flex align-items-center gap-1" style="font-size:11px; font-weight:600; color:#44403c;">
                    <span style="width:10px; height:10px; background:#3b82f6; border-radius:2px;"></span> Orders
                </div>
            </div>
        </div>
        <div style="height:280px;">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>
</div>

{{-- ── SECTION 2 : PERFORMANCE ANALYTICS (Dynamic AJAX) ── --}}
<div id="analyticsSection" style="margin: 0 -1.5rem; padding: 2rem 1.5rem; background: #fafaf9; border-top: 2px solid #f5f5f4;">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div class="d-flex align-items-center gap-3">
            <div style="width:4px; height:24px; background:#d97706; border-radius:2px;"></div>
            <div>
                <h2 style="font-size:16px; font-weight:800; text-transform:uppercase; letter-spacing:1px; color:#d97706; margin:0;">Period Performance</h2>
                <div style="font-size:11px; color:#78716c; font-weight:600; margin-top:2px;">ANALYTICS REPORT: <span id="filterLabelBadge" class="text-uppercase">{{ $period['filterLabel'] }}</span></div>
            </div>
        </div>
        
        <div class="d-flex flex-wrap align-items-center gap-2 period-filters">
            <div class="d-flex align-items-center gap-2">
                <div style="font-size:11px; font-weight:700; color:#a8a29e; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Timeframe:</div>
                <select id="dashFilter" class="form-select form-select-sm" style="width:130px; font-size:11px; font-weight:600; border-radius:8px; border-color:#e7e5e4; background:#fff; cursor:pointer;">
                    <option value="today"         {{ $period['filter'] === 'today'         ? 'selected' : '' }}>Today</option>
                    <option value="yesterday"     {{ $period['filter'] === 'yesterday'     ? 'selected' : '' }}>Yesterday</option>
                    <option value="current_week"  {{ $period['filter'] === 'current_week'  ? 'selected' : '' }}>Current Week</option>
                    <option value="current_month" {{ $period['filter'] === 'current_month' ? 'selected' : '' }}>Current Month</option>
                    <option value="current_year"  {{ $period['filter'] === 'current_year'  ? 'selected' : '' }}>Current Year</option>
                </select>
            </div>
            
            <div class="d-flex align-items-center gap-2">
                <div style="font-size:11px; font-weight:700; color:#a8a29e; text-transform:uppercase; letter-spacing:.5px; margin-left:10px; white-space:nowrap;">Product:</div>
                <select id="orderProductFilter" class="form-select form-select-sm" style="width:150px; font-size:11px; font-weight:600; border-radius:8px; border-color:#e7e5e4; background:#fff; cursor:pointer;">
                    <option value="">All Categories</option>
                    @foreach($global['products'] as $product)
                        <option value="{{ $product->id }}" {{ $period['productId'] == $product->id ? 'selected' : '' }}>{{ $product->product_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Period Stat Row --}}
    <div class="row g-3 mb-4">
        {{-- Period Orders --}}
        <div class="col-12 col-md-4">
            <div class="card dash-card p-3 border border-amber shadow-sm" style="background:#fff; border-left-width:4px !important;">
                <div class="d-flex justify-content-between">
                    <div class="stat-label">Orders Count</div>
                    <!-- <div class="icon-box" style="width:24px; height:24px; background:#fff7ed; font-size:10px; color:#d97706; border-radius:6px;"><i class="fa fa-shopping-basket"></i></div> -->
                </div>
                <div class="stat-value" id="ordersCountVal">{{ number_format($period['ordersCount']) }}</div>
                @php
                    $odDiff = $period['ordersCount'] - $period['prevOrdersCount'];
                    $odTrend = $odDiff > 0 ? 'up' : ($odDiff < 0 ? 'down' : 'neutral');
                @endphp
                <div id="orderTrendContainer" class="trend {{ $odTrend }} d-flex align-items-center gap-1 mt-2">
                    <i class="fa {{ $odDiff > 0 ? 'fa-arrow-up' : ($odDiff < 0 ? 'fa-arrow-down' : 'fa-minus') }}"></i>
                    <span id="orderTrendLabel">{{ $period['ordersCount'] }} orders</span>
                    <span id="orderTrendDiff" style="font-size:10px; opacity:0.8;">({{ $odDiff >= 0 ? '+' : '' }}{{ $odDiff }} vs prev)</span>
                </div>
            </div>
        </div>
        {{-- Period Revenue --}}
        <div class="col-12 col-md-4">
            <div class="card dash-card p-3 border-0 shadow-sm" style="background:#fff;">
                <div class="stat-label">Period Revenue</div>
                <div class="stat-value" id="revenueVal">₹ {{ number_format($period['revenue'], 0) }}</div>
                @php
                    $rvDiff = $period['revenue'] - $period['prevRevenue'];
                    $rvTrend = $rvDiff > 0 ? 'up' : ($rvDiff < 0 ? 'down' : 'neutral');
                    $rvPct = $period['prevRevenue'] > 0 ? round(abs($rvDiff) / $period['prevRevenue'] * 100, 1) : 0;
                @endphp
                <div id="revenueTrendContainer" class="trend {{ $rvTrend }} d-flex align-items-center gap-1 mt-2">
                    <i class="fa {{ $rvDiff > 0 ? 'fa-arrow-up' : ($rvDiff < 0 ? 'fa-arrow-down' : 'fa-minus') }}"></i>
                    <span id="revenueTrendLabel">{{ $rvPct }}% {{ $rvDiff >= 0 ? 'more' : 'less' }} than prev period</span>
                </div>
            </div>
        </div>
        {{-- Period Expenses --}}
        <div class="col-12 col-md-4">
            <div class="card dash-card p-3 border-0 shadow-sm" style="background:#fefce8; border: 1px dashed #fde047 !important;">
                <div class="stat-label" style="color: #854d0e;">Period Expenses</div>
                <div class="stat-value" id="expensesVal" style="color: #854d0e;">₹ {{ number_format($period['expenses'], 0) }}</div>
                <div class="trend neutral mt-2" style="color: #a16207;"><i class="fa fa-info-circle"></i> View details in reports</div>
            </div>
        </div>
    </div>

    {{-- Analysis Grid --}}
    <div class="row g-4">
        {{-- Activity Table --}}
        <div class="col-12 col-xl-8">
            <div class="card table-card p-4 border-0 shadow-sm" style="background:#fff;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="section-label mb-0" style="color:#1c1917;">Activity Log — <span id="ordersSectionLabel">{{ $period['filterLabel'] }}</span></div>
                    <a href="{{ route('admin.order.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-1" style="font-size:10px; font-weight:700; border-radius:20px;">View More</a>
                </div>
                <div class="table-responsive">
                    <table class="dash-table">
                        <thead>
                            <tr>
                                <th>Client Details</th>
                                <th>Product Item</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-end">Billing</th>
                            </tr>
                        </thead>
                        <tbody id="recentOrdersBody">
                            @forelse($period['recentOrders'] as $order)
                            <tr>
                                <td>
                                    <div class="main-text">{{ $order->customer_name }}</div>
                                    <div class="sub-text">{{ $order->shop_name }}</div>
                                </td>
                                <td style="font-weight:600; color:#57534e;">{{ $order->product_name }}</td>
                                <td class="text-center">{{ number_format($order->order_quantity, 0) }} {{ $order->unit }}</td>
                                <td class="text-end amt">₹ {{ number_format($order->calculated_total, 0) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-5" style="color:#a8a29e;">No active orders for this period.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Top Lists --}}
        <div class="col-12 col-xl-4">
            {{-- Top Customers --}}
            <div class="card table-card p-4 border-0 shadow-sm mb-4" style="background:#fff;">
                <div class="section-label" style="color:#1c1917;">Top Customers ({{ $period['filterLabel'] }})</div>
                <div id="topCustomersContainer">
                @forelse($period['topCustomers'] as $c)
                <div class="cust-row">
                    <div class="cust-avatar" style="background:#fef3c7; color:#92400e;">{{ strtoupper(substr($c->customer_name, 0, 1)) }}</div>
                    <div>
                        <div class="cust-name">{{ $c->customer_name }}</div>
                        <div class="cust-shop">{{ number_format($c->total_qty, 0) }} {{ $c->unit ?? 'KG' }} volume</div>
                    </div>
                    <div class="cust-badge">{{ $c->order_count }}</div>
                </div>
                @empty
                <div style="color:#a8a29e; font-size:12px; text-align:center; padding:20px 0;">N/A</div>
                @endforelse
                </div>
            </div>

            {{-- Top Products Bar --}}
            <div class="card table-card p-4 border-0 shadow-sm" style="background:#fff;">
                <div class="section-label" style="color:#1c1917;">Category focus</div>
                <div style="position:relative; height:140px; margin-bottom:15px;">
                    <canvas id="productChart"></canvas>
                </div>
                <div id="topProductsLegend">
                    @foreach($period['topProducts'] as $i => $p)
                    <div class="prod-row">
                        <div class="prod-label">
                            <span style="font-size:11px;">{{ $p->product_name }}</span>
                            <span style="font-size:10px; color:#d97706; font-weight:700;">{{ number_format($p->total_qty, 0) }} {{ $p->unit }}</span>
                        </div>
                        @php $maxQty = $period['topProducts']->max('total_qty'); $donutColors = ['#d97706','#3b82f6','#10b981','#8b5cf6','#ef4444']; @endphp
                        <div class="prod-bar-wrap" style="height:6px;">
                            <div class="prod-bar-fill" style="width:{{ $maxQty > 0 ? round($p->total_qty / $maxQty * 100) : 0 }}%; background:{{ $donutColors[$i % 5] }};"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Monthly Detail Bar (Dynamic if needed, currently static 6 months) --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card chart-card p-4 border-0 shadow-sm" style="background:#fff;">
                <div class="chart-title" style="font-size:12px; color:#1c1917;">System Load — Quantity Analytics</div>
                <div style="height:180px; margin-top:15px;">
                    <canvas id="quantityChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function() {
    let chartInstances = {};
    const donutColors = ['#d97706','#3b82f6','#10b981','#8b5cf6','#ef4444'];
    
    // Initial Data - Carefully extracted from bifurcated structure
    const globalData = {!! json_encode($global) !!};
    const periodData = {!! json_encode($period) !!};

    const initCharts = (g, p) => {
        // ── Monthly Overview ──────────
        const ctx1 = document.getElementById('monthlyChart').getContext('2d');
        const revGrad = ctx1.createLinearGradient(0, 0, 0, 260);
        revGrad.addColorStop(0, 'rgba(217,119,6,.25)');
        revGrad.addColorStop(1, 'rgba(217,119,6,0)');

        chartInstances.monthly = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: g.chartLabels,
                datasets: [
                    { label: 'Revenue (₹)', data: g.chartRevenue, borderColor: '#d97706', backgroundColor: revGrad, borderWidth: 2.5, fill: true, tension: .4, pointBackgroundColor: '#d97706', pointRadius: 4, yAxisID: 'yRev' },
                    { label: 'Orders', data: g.chartOrders, borderColor: '#3b82f6', backgroundColor: 'transparent', borderWidth: 2, fill: false, tension: .4, pointBackgroundColor: '#3b82f6', pointRadius: 4, borderDash: [5,3], yAxisID: 'yOrd' }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false }, plugins: { legend: { display: false } },
                scales: { 
                    yRev: { position: 'left', grid: { color: '#f5f5f4' }, ticks: { font: { size: 10 }, callback: v => '₹' + (v >= 1000 ? (v/1000).toFixed(0)+'k' : v) } },
                    yOrd: { position: 'right', grid: { drawOnChartArea: false }, ticks: { font: { size: 10 }, precision: 0 } },
                    x: { grid: { display: false }, ticks: { font: { size: 11 } } }
                }
            }
        });

        // ── Top Products Chart ──────────
        const ctx2 = document.getElementById('productChart').getContext('2d');
        chartInstances.products = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: p.topProducts.map(x => x.product_name),
                datasets: [{ data: p.topProducts.map(x => x.total_qty), backgroundColor: donutColors, borderWidth: 2, borderColor: '#fff' }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '75%', plugins: { legend: { display: false } } }
        });

        // ── Quantity Chart ──────────
        const ctx3 = document.getElementById('quantityChart').getContext('2d');
        chartInstances.quantity = new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: g.chartLabels,
                datasets: [{ label: 'Quantity (KG)', data: g.chartQuantity, backgroundColor: '#d97706', borderRadius: 4 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, 
                scales: { y: { grid: { color: '#f5f5f4' }, ticks: { font: { size: 10 } } }, x: { grid: { display: false } } }
            }
        });
    };

    const updateDashboard = () => {
        const filter = document.getElementById('dashFilter').value;
        const productId = document.getElementById('orderProductFilter').value;

        let url = '{{ route('admin.dashboard') }}?filter=' + filter;
        if (productId) url += '&product_id=' + productId;

        document.getElementById('analyticsSection').classList.add('loading-active');
        
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                const p = data.period;

                // Update UI Labels
                const prodName = document.getElementById('orderProductFilter').options[document.getElementById('orderProductFilter').selectedIndex].text;
                const labelPrefix = productId ? `[${prodName}] ` : '';
                
                document.getElementById('filterLabelBadge').textContent = labelPrefix + p.filterLabel;
                document.getElementById('ordersSectionLabel').textContent = labelPrefix + p.filterLabel;

                // Update Period Data
                document.getElementById('ordersCountVal').textContent = Number(p.ordersCount).toLocaleString('en-IN');
                document.getElementById('revenueVal').textContent = '₹ ' + Number(p.revenue).toLocaleString('en-IN');
                document.getElementById('expensesVal').textContent = '₹ ' + Number(p.expenses).toLocaleString('en-IN');

                // Update Trends
                const odDiff = p.ordersCount - p.prevOrdersCount;
                const odTrendCont = document.getElementById('orderTrendContainer');
                odTrendCont.className = 'trend ' + (odDiff > 0 ? 'up' : (odDiff < 0 ? 'down' : 'neutral'));
                odTrendCont.querySelector('i').className = 'fa ' + (odDiff > 0 ? 'fa-arrow-up' : (odDiff < 0 ? 'fa-arrow-down' : 'fa-minus'));
                document.getElementById('orderTrendLabel').textContent = p.ordersCount + ' orders';
                document.getElementById('orderTrendDiff').textContent = `(${odDiff >= 0 ? '+' : ''}${odDiff} vs prev)`;

                const rvDiff = p.revenue - p.prevRevenue;
                const rvTrendCont = document.getElementById('revenueTrendContainer');
                const rvPct = p.prevRevenue > 0 ? Math.round(Math.abs(rvDiff) / p.prevRevenue * 100 * 10) / 10 : 0;
                rvTrendCont.className = 'trend ' + (rvDiff > 0 ? 'up' : (rvDiff < 0 ? 'down' : 'neutral'));
                rvTrendCont.querySelector('i').className = 'fa ' + (rvDiff > 0 ? 'fa-arrow-up' : (rvDiff < 0 ? 'fa-arrow-down' : 'fa-minus'));
                document.getElementById('revenueTrendLabel').textContent = `${rvPct}% ${rvDiff >= 0 ? 'more' : 'less'} than prev period`;

                // Update Tables & Lists
                document.getElementById('recentOrdersBody').innerHTML = p.recentOrders.length ? p.recentOrders.map(o => `
                    <tr>
                        <td><div class="main-text">${o.customer_name}</div><div class="sub-text">${o.shop_name}</div></td>
                        <td style="font-weight:600; color:#57534e;">${o.product_name}</td>
                        <td class="text-center">${Number(o.order_quantity).toLocaleString('en-IN')} ${o.unit}</td>
                        <td class="text-end amt">₹ ${Number(o.calculated_total).toLocaleString('en-IN')}</td>
                    </tr>
                `).join('') : '<tr><td colspan="4" class="text-center py-5" style="color:#a8a29e;">No active orders for this period.</td></tr>';

                document.getElementById('topCustomersContainer').innerHTML = p.topCustomers.length ? p.topCustomers.map(c => `
                    <div class="cust-row">
                        <div class="cust-avatar" style="background:#fef3c7; color:#92400e;">${c.customer_name.charAt(0).toUpperCase()}</div>
                        <div><div class="cust-name">${c.customer_name}</div><div class="cust-shop">${Number(c.total_qty).toLocaleString('en-IN')} volume</div></div>
                        <div class="cust-badge">${c.order_count}</div>
                    </div>
                `).join('') : '<div style="color:#a8a29e; font-size:12px; text-align:center; padding:20px 0;">N/A</div>';

                // Product Chart & Bar fills (Only update if no specific product is selected, or let it show context)
                // If a product is selected, the "Top Products" usually becomes just 1 item or irrelevant.
                // We'll update it anyway to keep it consistent with the backend query.
                const maxQty = Math.max(...p.topProducts.map(x => x.total_qty), 1);
                document.getElementById('topProductsLegend').innerHTML = p.topProducts.map((pr, i) => `
                    <div class="prod-row">
                        <div class="prod-label"><span>${pr.product_name}</span><span style="color:#d97706; font-weight:700;">${Number(pr.total_qty).toLocaleString('en-IN')} ${pr.unit}</span></div>
                        <div class="prod-bar-wrap" style="height:6px;"><div class="prod-bar-fill" style="width:${(pr.total_qty/maxQty*100)}%; background:${donutColors[i%5]};"></div></div>
                    </div>`).join('');

                // Update Chart
                chartInstances.products.data.labels = p.topProducts.map(x => x.product_name);
                chartInstances.products.data.datasets[0].data = p.topProducts.map(x => x.total_qty);
                chartInstances.products.update();

                document.getElementById('analyticsSection').classList.remove('loading-active');
            })
            .catch(err => {
                console.error("Dashboard Update Failed:", err);
                document.getElementById('analyticsSection').classList.remove('loading-active');
            });
    };

    document.addEventListener('DOMContentLoaded', () => {
        initCharts(globalData, periodData);
        
        // Date Filter Change
        document.getElementById('dashFilter').addEventListener('change', function() {
            updateDashboard();
        });

        // Product Filter Change
        document.getElementById('orderProductFilter').addEventListener('change', function() {
            updateDashboard();
        });
    });
})();
</script>

@endsection
