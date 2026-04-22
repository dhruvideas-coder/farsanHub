@extends('layouts.app')

<style>
/* ── MODERN DASHBOARD DESIGN SYSTEM ──────────────── */
:root {
    --accent: #5c67f2;
    --accent-glow: rgba(92, 103, 242, 0.1);
    --glass-bg: #ffffff;
    --glass-border: #f1f5f9;
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --success: #10b981;
    --danger: #ef4444;
    --warning: #f59e0b;
    --card-radius: 20px;
    --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 10px 30px rgba(0, 0, 0, 0.04);
}

.dashboard-wrapper {
    padding: 2rem;
    background: #f8fafc;
    min-height: calc(100vh - 100px);
    animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ── HEADER DESIGN ────────────────────────────────── */
.header-hero { margin-bottom: 2.5rem; }
.header-hero h1 {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text-primary);
    letter-spacing: -0.025em;
    margin-top: 0.5rem;
}
.live-badge {
    background: #fff;
    border: 1px solid #e2e8f0;
    color: var(--success);
    padding: 6px 16px;
    border-radius: 100px;
    font-size: 11px;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: var(--shadow-sm);
    text-transform: uppercase;
}
.live-dot {
    width: 8px; height: 8px;
    background: var(--success);
    border-radius: 50%;
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
    70% { transform: scale(1.1); box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
    100% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

/* ── KPI CARDS ─────────────────────────────────── */
.kpi-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    border: 1px solid #f1f5f9;
    height: 100%;
    position: relative;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}
.kpi-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
}
.kpi-icon-box {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: #eff2fe;
    color: #5c67f2;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    margin-bottom: 1.2rem;
}
.kpi-badge {
    position: absolute;
    top: 1.5rem;
    right: 1.5rem;
    background: #ecfdf5;
    color: #10b981;
    padding: 4px 10px;
    border-radius: 99px;
    font-size: 11px;
    font-weight: 700;
}
.kpi-badge.negative {
    background: #fef2f2;
    color: #ef4444;
}
.kpi-label {
    color: #94a3b8;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 0.4rem;
}
.kpi-value {
    color: #1e293b;
    font-size: 26px;
    font-weight: 800;
    line-height: 1.2;
}

/* ── MODERN CARDS ─────────────────────────────────── */
.premium-card {
    background: #fff;
    border: 1px solid var(--glass-border);
    border-radius: var(--card-radius);
    padding: 1.5rem;
    box-shadow: var(--shadow-md);
    transition: all 0.3s ease;
    height: 100%;
}
.premium-card:hover {
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06);
}
.card-header-main {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}
.card-title-main {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

/* ── MODERN TABLES ────────────────────────────────── */
.modern-table { 
    width: 100%; 
    border-collapse: separate; 
    border-spacing: 0; 
    min-width: 800px; /* Forces horizontal scroll on small devices */
}
.modern-table thead th {
    padding: 1rem;
    font-size: 11px;
    font-weight: 800;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    border-bottom: 1px solid #e2e8f0;
    background: #f8fafc;
    position: sticky;
    top: 0;
    z-index: 10;
}
.modern-table thead th:nth-child(2) { width: 120px; } /* Date */
.modern-table thead th:nth-child(4) { width: 110px; } /* Quantity */
.modern-table thead th:nth-child(5) { width: 130px; } /* Billing */

.modern-table td {
    padding: 0.85rem 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    transition: all 0.2s ease;
    background: #fff;
    white-space: nowrap;
}
.modern-table td:nth-child(1),
.modern-table td:nth-child(3) {
    white-space: normal; /* Allow text wrapping for long names */
}
.modern-table tr:hover td { 
    background: #fcfdfe;
}

.table-responsive {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f5f9;
    border-radius: 12px;
}
.table-responsive::-webkit-scrollbar {
    height: 6px;
}
.table-responsive::-webkit-scrollbar-track {
    background: #f1f5f9;
}
.table-responsive::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.status-pill {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.02em;
    display: inline-flex;
    align-items: center;
}
.status-pill.sell { background: #ecfdf5; color: #059669; }
.status-pill.purchase { background: #fff1f2; color: #e11d48; }

/* ── CUSTOMER LIST ────────────────────────────────── */
.customer-row {
    display: flex; align-items: center; gap: 1rem;
    padding: 0.75rem 1rem;
    border-radius: 12px;
    transition: all 0.2s ease;
}
.customer-row:hover { background: #f8fafc; }
.avatar-box {
    width: 40px; height: 40px;
    border-radius: 10px;
    background: #eff2fe;
    color: #5c67f2;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 15px;
}

/* ── FILTER BAR ───────────────────────────────────── */
.modern-filters {
    background: #fff;
    padding: 0.5rem;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    display: flex;
    gap: 0.5rem;
}
.filter-item {
    border: none;
    background: transparent;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-secondary);
    transition: all 0.2s;
    cursor: pointer;
}
.filter-item:hover { background: #f1f5f9; color: var(--text-primary); }
.filter-item.active { background: #eff2fe; color: #5c67f2; }

/* ── LOADING ──────────────────────────────────────── */
.loading-overlay {
    position: absolute; inset: 0;
    background: rgba(255,255,255,0.7);
    backdrop-filter: blur(8px);
    z-index: 1000;
    border-radius: var(--card-radius);
    display: flex; align-items: center; justify-content: center;
    opacity: 0; pointer-events: none;
    transition: opacity 0.3s ease;
}
.loading-active .loading-overlay { opacity: 1; pointer-events: all; }

/* ── RESPONSIVE ───────────────────────────────────── */
@media (max-width: 991px) {
    .dashboard-wrapper { padding: 1.2rem; }
    .header-hero h1 { font-size: 1.8rem; }
    .stat-value { font-size: 26px; }
}
</style>

@section('content')

<div class="dashboard-wrapper">
    {{-- ── HERO SECTION ─────────────────────────────────── --}}
    <div class="header-hero d-flex flex-wrap justify-content-between align-items-center gap-4">
        <div>
            <div class="live-badge">
                <div class="live-dot"></div>
                Live Analytics
            </div>
            <h1>Dashboard</h1>
            <p class="text-muted fw-medium mb-0">Welcome back, {{ explode(' ', auth()->user()->name)[0] }}. Here is your store summary.</p>
        </div>
        
        <div class="d-flex flex-wrap gap-3">
            <div class="modern-filters">
                <select id="dashFilter" class="form-select border-0 bg-transparent fw-bold text-dark" style="cursor:pointer; min-width: 140px;">
                    <option value="today"         {{ $period['filter'] === 'today'         ? 'selected' : '' }}>Today</option>
                    <option value="yesterday"     {{ $period['filter'] === 'yesterday'     ? 'selected' : '' }}>Yesterday</option>
                    <option value="current_week"  {{ $period['filter'] === 'current_week'  ? 'selected' : '' }}>This Week</option>
                    <option value="current_month" {{ $period['filter'] === 'current_month' ? 'selected' : '' }}>This Month</option>
                    <option value="current_year"  {{ $period['filter'] === 'current_year'  ? 'selected' : '' }}>This Year</option>
                </select>
            </div>
            <div class="modern-filters">
                <select id="orderProductFilter" class="form-select border-0 bg-transparent fw-bold text-dark" style="cursor:pointer; min-width: 160px;">
                    <option value="">All Products</option>
                    @foreach($global['products'] as $product)
                        <option value="{{ $product->id }}" {{ $period['productId'] == $product->id ? 'selected' : '' }}>{{ $product->product_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- ── KPI SECTION ──────────────────────────────────── --}}
    <div class="row g-4 mb-5" id="analyticsCards">
        {{-- Total Sales --}}
        <div class="col-12 col-md-6 col-xl-3">
            <div class="kpi-card">
                <div class="kpi-icon-box" style="background: #ecfdf5; color: #10b981;">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                @php
                    $sellDiff = $period['sellRevenue'] - $period['prevSellRevenue'];
                    $sellPct = $period['prevSellRevenue'] > 0 ? round(abs($sellDiff) / $period['prevSellRevenue'] * 100, 1) : 0;
                @endphp
                <div id="sellTrendBadge" class="kpi-badge {{ $sellDiff >= 0 ? '' : 'negative' }}">
                    {{ $sellDiff >= 0 ? '+' : '-' }}{{ $sellPct }}%
                </div>
                <div class="kpi-label">Sales</div>
                <div class="kpi-value" id="sellRevenueVal">₹{{ number_format($period['sellRevenue'], 0) }}</div>
            </div>
        </div>

        {{-- Total Purchases --}}
        <div class="col-12 col-md-6 col-xl-3">
            <div class="kpi-card">
                <div class="kpi-icon-box" style="background: #fff1f2; color: #ef4444;">
                    <i class="fa fa-truck"></i>
                </div>
                @php
                    $purchaseDiff = $period['purchaseRevenue'] - $period['prevPurchaseRevenue'];
                    $purchasePct = $period['prevPurchaseRevenue'] > 0 ? round(abs($purchaseDiff) / $period['prevPurchaseRevenue'] * 100, 1) : 0;
                @endphp
                <div id="purchaseTrendBadge" class="kpi-badge {{ $purchaseDiff <= 0 ? '' : 'negative' }}">
                    {{ $purchaseDiff <= 0 ? '-' : '+' }}{{ $purchasePct }}%
                </div>
                <div class="kpi-label">Purchases</div>
                <div class="kpi-value" id="purchaseRevenueVal">₹{{ number_format($period['purchaseRevenue'], 0) }}</div>
            </div>
        </div>

        {{-- Total Expenses --}}
        <div class="col-12 col-md-6 col-xl-3">
            <div class="kpi-card">
                <div class="kpi-icon-box" style="background: #fffbeb; color: #f59e0b;">
                    <i class="fa fa-money"></i>
                </div>
                @php
                    $expenseDiff = $period['expenses'] - $period['prevExpenses'];
                    $expensePct = $period['prevExpenses'] > 0 ? round(abs($expenseDiff) / $period['prevExpenses'] * 100, 1) : 0;
                @endphp
                <div id="expenseTrendBadge" class="kpi-badge {{ $expenseDiff <= 0 ? '' : 'negative' }}">
                    {{ $expenseDiff <= 0 ? '-' : '+' }}{{ $expensePct }}%
                </div>
                <div class="kpi-label">Expenses</div>
                <div class="kpi-value" id="expenseVal">₹{{ number_format($period['expenses'], 0) }}</div>
            </div>
        </div>

        {{-- Net Profit/Balance --}}
        <div class="col-12 col-md-6 col-xl-3">
            <div class="kpi-card">
                <div class="kpi-icon-box" style="background: #eff2fe; color: #5c67f2;">
                    <i class="fa fa-bank"></i>
                </div>
                @php
                    $netProfit = $period['sellRevenue'] - $period['purchaseRevenue'] - $period['expenses'];
                    $prevNetProfit = $period['prevSellRevenue'] - $period['prevPurchaseRevenue'] - $period['prevExpenses'];
                    $netDiff = $netProfit - $prevNetProfit;
                    $netPct = $prevNetProfit != 0 ? round(abs($netDiff) / abs($prevNetProfit) * 100, 1) : 0;
                @endphp
                <div id="netTrendBadge" class="kpi-badge {{ $netProfit >= 0 ? '' : 'negative' }}">
                    {{ $netProfit >= 0 ? '+' : '-' }}{{ $netPct }}%
                </div>
                <div class="kpi-label">Net Balance</div>
                <div class="kpi-value" id="netProfitVal">{{ $netProfit < 0 ? '-' : '' }}₹{{ number_format(abs($netProfit), 0) }}</div>
            </div>
        </div>
    </div>

    {{-- ── ANALYTICS GRID ───────────────────────────────── --}}
    <div class="row g-4 mb-5">
        <div class="col-12 col-xl-8">
            <div class="premium-card position-relative" id="chartCardWrapper">
                <div class="loading-overlay"><div class="spinner-border text-primary"></div></div>
                <div class="card-header-main">
                    <h3 class="card-title-main">Revenue Performance</h3>
                    <div class="d-flex gap-3">
                        <div class="d-flex align-items-center gap-2" style="font-size: 11px; font-weight: 800;">
                            <span class="rounded-circle" style="width:8px; height:8px; background:var(--success);"></span> SALES
                        </div>
                        <div class="d-flex align-items-center gap-2" style="font-size: 11px; font-weight: 800;">
                            <span class="rounded-circle" style="width:8px; height:8px; background:var(--danger);"></span> PURCHASES
                        </div>
                    </div>
                </div>
                <div style="height: 380px;">
                    <canvas id="mainRevenueChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="premium-card">
                <div class="card-header-main">
                    <h3 class="card-title-main">Top Customers</h3>
                </div>
                <div style="height: 250px; position: relative;">
                    <canvas id="customerPieChart"></canvas>
                </div>
                <div id="customerLegend" class="mt-4">
                    {{-- Populated by JS --}}
                </div>
            </div>
        </div>
    </div>

    {{-- ── ACTIVITY & TOP PERFORMERS ────────────────────── --}}
    <div class="row g-4">
        <div class="col-12 col-xl-8">
            <div class="premium-card">
                <div class="card-header-main">
                    <h3 class="card-title-main">Recent Orders</h3>
                    <a href="{{ route('admin.order.index') }}" class="btn btn-sm fw-bold text-primary px-3" style="background: #eff2fe; border-radius: 8px;">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Client / Shop</th>
                                <th>Date</th>
                                <th>Item</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-end">Billing</th>
                            </tr>
                        </thead>
                        <tbody id="recentOrdersBody">
                            @forelse($period['recentOrders'] as $order)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-box" style="width: 32px; height: 32px; font-size: 11px; flex-shrink: 0;">
                                            {{ strtoupper(substr($order->customer_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 13.5px;">{{ $order->customer_name }}</div>
                                            <div class="text-muted" style="font-size: 10.5px; font-weight: 600;">{{ $order->shop_name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark" style="font-size: 13px;">{{ $order->display_date }}</div>
                                    <div class="text-muted" style="font-size: 9px; font-weight: 800;">RECORDED</div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark" style="font-size: 13.5px;">{{ $order->product_name }}</div>
                                    <div class="mt-1">
                                        <span class="status-pill {{ $order->type === 'sell' ? 'sell' : 'purchase' }}" style="padding: 2px 8px; font-size: 9px;">
                                            {{ strtoupper($order->type) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="fw-bold text-dark" style="font-size: 13.5px;">{{ number_format($order->order_quantity, 0) }}</div>
                                    <div class="text-muted" style="font-size: 9.5px; font-weight: 800; text-transform: uppercase;">{{ $order->unit }}</div>
                                </td>
                                <td class="text-end">
                                    <div class="fw-bold text-primary" style="font-size: 14.5px;">₹{{ number_format($order->calculated_total, 0) }}</div>
                                    <div class="text-muted" style="font-size: 9.5px; font-weight: 700;">PROCESSED</div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-5 text-muted fw-bold">No transactions found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="premium-card">
                <div class="card-header-main">
                    <h3 class="card-title-main">Top Customers</h3>
                </div>
                <div id="topCustomersContainer">
                    @forelse($period['topCustomers'] as $c)
                    <div class="customer-row">
                        <div class="avatar-box">{{ strtoupper(substr($c->customer_name, 0, 1)) }}</div>
                        <div class="flex-grow-1">
                            <div class="fw-bold text-dark" style="font-size: 14px;">{{ $c->customer_name }}</div>
                            <div class="text-muted small fw-bold">{{ number_format($c->total_kg ?? 0, 0) }}kg Volume</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-primary" style="font-size: 14px;">{{ $c->order_count }}</div>
                            <div class="text-muted" style="font-size: 10px; font-weight: 800;">ORDERS</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">No partnerships data</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function() {
    let charts = {};
    const COLORS = {
        accent: '#ff9933',
        success: '#10b981',
        danger: '#ef4444',
        primary: '#3b82f6',
        secondary: '#64748b',
        border: '#f1f5f9'
    };

    const initDashboard = () => {
        const globalData = {!! json_encode($global) !!};
        const periodData = {!! json_encode($period) !!};

        // ── Main Revenue Chart ──────────────────────────
        const ctxRev = document.getElementById('mainRevenueChart').getContext('2d');
        const gradientS = ctxRev.createLinearGradient(0, 0, 0, 400);
        gradientS.addColorStop(0, 'rgba(16, 185, 129, 0.15)');
        gradientS.addColorStop(1, 'rgba(16, 185, 129, 0)');

        const gradientP = ctxRev.createLinearGradient(0, 0, 0, 400);
        gradientP.addColorStop(0, 'rgba(239, 68, 68, 0.1)');
        gradientP.addColorStop(1, 'rgba(239, 68, 68, 0)');

        charts.revenue = new Chart(ctxRev, {
            type: 'line',
            data: {
                labels: globalData.chartLabels,
                datasets: [
                    {
                        label: 'Sales',
                        data: globalData.chartSellRevenue,
                        borderColor: COLORS.success,
                        backgroundColor: gradientS,
                        fill: true,
                        tension: 0.45,
                        borderWidth: 4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointHoverBorderWidth: 4,
                        pointHoverBorderColor: '#fff',
                        pointHoverBackgroundColor: COLORS.success
                    },
                    {
                        label: 'Purchases',
                        data: globalData.chartPurchaseRevenue,
                        borderColor: COLORS.danger,
                        backgroundColor: gradientP,
                        fill: true,
                        tension: 0.45,
                        borderWidth: 4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointHoverBorderWidth: 4,
                        pointHoverBorderColor: '#fff',
                        pointHoverBackgroundColor: COLORS.danger
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { padding: 15, backgroundColor: '#1e293b', titleFont: { size: 13 }, bodyFont: { size: 14, weight: 'bold' } } },
                scales: {
                    y: { grid: { color: COLORS.border, drawBorder: false }, ticks: { font: { weight: 600 }, callback: v => '₹' + (v >= 1000 ? (v/1000).toFixed(0)+'k' : v) } },
                    x: { grid: { display: false }, ticks: { font: { weight: 600 } } }
                }
            }
        });

        // ── Customer Pie Chart ──────────────────────────
        const ctxPie = document.getElementById('customerPieChart').getContext('2d');
        charts.pie = new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: periodData.topCustomers.map(c => c.customer_name),
                datasets: [{
                    data: periodData.topCustomers.map(c => c.total_amount),
                    backgroundColor: [COLORS.accent, COLORS.primary, COLORS.success, '#8b5cf6', COLORS.danger],
                    borderWidth: 0,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: { legend: { display: false } }
            }
        });

        updateCustomerLegend(periodData.topCustomers);
    };

    const updateCustomerLegend = (customers) => {
        const colors = [COLORS.accent, COLORS.primary, COLORS.success, '#8b5cf6', COLORS.danger];
        const container = document.getElementById('customerLegend');
        container.innerHTML = customers.map((c, i) => `
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 8px; height: 8px; border-radius: 50%; background: ${colors[i % colors.length]}"></div>
                    <span class="fw-bold text-dark" style="font-size: 12px;">${c.customer_name}</span>
                </div>
                <span class="text-muted fw-bold" style="font-size: 11px;">₹${Number(c.total_amount).toLocaleString('en-IN')}</span>
            </div>
        `).join('');
    };

    const updateDashboard = () => {
        const filter = document.getElementById('dashFilter').value;
        const productId = document.getElementById('orderProductFilter').value;
        const url = `{{ route('admin.dashboard') }}?filter=${filter}&product_id=${productId}`;

        document.getElementById('chartCardWrapper').classList.add('loading-active');

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.json())
            .then(data => {
                const p = data.period;
                
                // Update Stats
                const updateVal = (id, val, prefix = '₹') => {
                    const el = document.getElementById(id);
                    if (el) {
                        const isNeg = val < 0;
                        el.textContent = (isNeg ? '-' : '') + prefix + Math.abs(Number(val)).toLocaleString('en-IN');
                    }
                };
                updateVal('sellRevenueVal', p.sellRevenue);
                updateVal('purchaseRevenueVal', p.purchaseRevenue);
                updateVal('expenseVal', p.expenses);
                
                const netProfit = p.sellRevenue - p.purchaseRevenue - p.expenses;
                updateVal('netProfitVal', netProfit);

                // Update Trends & Growth
                const updateBadge = (id, current, prev, inverse = false) => {
                    const diff = current - prev;
                    const pct = prev != 0 ? Math.round(Math.abs(diff) / Math.abs(prev) * 100 * 10) / 10 : 0;
                    const badge = document.getElementById(id);
                    if (badge) {
                        badge.textContent = (diff >= 0 ? '+' : '-') + pct + '%';
                        const isPositive = inverse ? diff <= 0 : diff >= 0;
                        badge.className = 'kpi-badge ' + (isPositive ? '' : 'negative');
                    }
                };

                updateBadge('sellTrendBadge', p.sellRevenue, p.prevSellRevenue);
                updateBadge('purchaseTrendBadge', p.purchaseRevenue, p.prevPurchaseRevenue, true);
                updateBadge('expenseTrendBadge', p.expenses, p.prevExpenses, true);
                
                const prevNetProfit = p.prevSellRevenue - p.prevPurchaseRevenue - p.prevExpenses;
                updateBadge('netTrendBadge', netProfit, prevNetProfit);

                // Update Table
                document.getElementById('recentOrdersBody').innerHTML = p.recentOrders.length ? p.recentOrders.map(o => `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-box" style="width: 32px; height: 32px; font-size: 11px; flex-shrink: 0;">
                                    ${o.customer_name.charAt(0).toUpperCase()}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark" style="font-size: 13.5px;">${o.customer_name}</div>
                                    <div class="text-muted" style="font-size: 10.5px; font-weight: 600;">${o.shop_name}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark" style="font-size: 13px;">${o.display_date}</div>
                            <div class="text-muted" style="font-size: 9px; font-weight: 800;">RECORDED</div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark" style="font-size: 13.5px;">${o.product_name}</div>
                            <div class="mt-1">
                                <span class="status-pill ${o.type === 'sell' ? 'sell' : 'purchase'}" style="padding: 2px 8px; font-size: 9px;">${o.type.toUpperCase()}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="fw-bold text-dark" style="font-size: 13.5px;">${Number(o.order_quantity).toLocaleString()}</div>
                            <div class="text-muted" style="font-size: 9.5px; font-weight: 800; text-transform: uppercase;">${o.unit}</div>
                        </td>
                        <td class="text-end">
                            <div class="fw-bold text-primary" style="font-size: 14.5px;">₹${Number(o.calculated_total).toLocaleString()}</div>
                            <div class="text-muted" style="font-size: 9.5px; font-weight: 700;">PROCESSED</div>
                        </td>
                    </tr>
                `).join('') : '<tr><td colspan="5" class="text-center py-5 text-muted fw-bold">No transactions found</td></tr>';

                // Update Top Customers
                document.getElementById('topCustomersContainer').innerHTML = p.topCustomers.length ? p.topCustomers.map(c => `
                    <div class="customer-row">
                        <div class="avatar-box">${c.customer_name.charAt(0).toUpperCase()}</div>
                        <div class="flex-grow-1">
                            <div class="fw-bold text-dark" style="font-size: 14px;">${c.customer_name}</div>
                            <div class="text-muted small fw-bold">${Number(c.total_kg || 0).toLocaleString()}kg Volume</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-primary" style="font-size: 14px;">${c.order_count}</div>
                            <div class="text-muted" style="font-size: 10px; font-weight: 800;">ORDERS</div>
                        </div>
                    </div>
                `).join('') : '<div class="text-center py-5 text-muted">No partnerships data</div>';

                // Update Revenue Chart
                charts.revenue.data.labels = data.global.chartLabels;
                charts.revenue.data.datasets[0].data = data.global.chartSellRevenue;
                charts.revenue.data.datasets[1].data = data.global.chartPurchaseRevenue;
                charts.revenue.update();

                // Update Pie Chart
                charts.pie.data.labels = p.topCustomers.map(c => c.customer_name);
                charts.pie.data.datasets[0].data = p.topCustomers.map(c => c.total_amount);
                charts.pie.update();
                updateCustomerLegend(p.topCustomers);

                document.getElementById('chartCardWrapper').classList.remove('loading-active');
            });
    };

    document.addEventListener('DOMContentLoaded', () => {
        initDashboard();
        document.getElementById('dashFilter').addEventListener('change', updateDashboard);
        document.getElementById('orderProductFilter').addEventListener('change', updateDashboard);
    });
})();
</script>

@endsection
