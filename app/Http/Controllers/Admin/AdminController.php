<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\ChangePasswordRequest;
use App\Models\Content;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function dashboard(Request $request)
    {
        $data = $this->getDashboardData($request);

        if ($request->ajax()) {
            return response()->json($data);
        }

        return view('admin.dashboard', $data);
    }

    private function getDashboardData(Request $request)
    {
        $uid       = auth()->id();
        $filter    = $request->get('filter', 'today');
        $productId = $request->get('product_id');

        // ── GLOBAL STATS (All-time) ──────────────────────────────────
        $totalCustomers = Customer::where('user_id', $uid)->count();
        $totalProducts  = Product::where('user_id', $uid)->count();
        $allTimeOrders  = Order::where('user_id', $uid)->count();
        $allTimeRevenue = Order::where('user_id', $uid)
            ->selectRaw('SUM(order_quantity * order_price) as total')
            ->value('total') ?? 0;
        
        $products = Product::where('user_id', $uid)->orderBy('product_name')->get(['id', 'product_name', 'unit']);

        // ── BUILD DATE RANGE FOR FILTER ──────────────────────────────
        switch ($filter) {
            case 'yesterday':
                $startDate   = Carbon::yesterday()->startOfDay();
                $endDate     = Carbon::yesterday()->endOfDay();
                $prevStart   = Carbon::yesterday()->subDay()->startOfDay();
                $prevEnd     = Carbon::yesterday()->subDay()->endOfDay();
                $filterLabel = 'Yesterday';
                break;
            case 'current_week':
                $startDate   = Carbon::now()->startOfWeek();
                $endDate     = Carbon::now()->endOfWeek();
                $prevStart   = Carbon::now()->subWeek()->startOfWeek();
                $prevEnd     = Carbon::now()->subWeek()->endOfWeek();
                $filterLabel = 'This Week';
                break;
            case 'current_month':
                $startDate   = Carbon::now()->startOfMonth();
                $endDate     = Carbon::now()->endOfMonth();
                $prevStart   = Carbon::now()->subMonth()->startOfMonth();
                $prevEnd     = Carbon::now()->subMonth()->endOfMonth();
                $filterLabel = 'This Month';
                break;
            case 'current_year':
                $startDate   = Carbon::now()->startOfYear();
                $endDate     = Carbon::now()->endOfYear();
                $prevStart   = Carbon::now()->subYear()->startOfYear();
                $prevEnd     = Carbon::now()->subYear()->endOfYear();
                $filterLabel = 'This Year';
                break;
            default:
                $filter      = 'today';
                $startDate   = Carbon::today()->startOfDay();
                $endDate     = Carbon::today()->endOfDay();
                $prevStart   = Carbon::yesterday()->startOfDay();
                $prevEnd     = Carbon::yesterday()->endOfDay();
                $filterLabel = 'Today';
                break;
        }

        $start = $startDate->toDateString();
        $end   = $endDate->toDateString();
        $pStart = $prevStart->toDateString();
        $pEnd   = $prevEnd->toDateString();

        // ── FILTERED STATS (For selected period & product) ───────────
        $periodOrdersQuery = Order::where('user_id', $uid)
            ->whereRaw('COALESCE(order_date, DATE(created_at)) BETWEEN ? AND ?', [$start, $end])
            ->when($productId, fn($q) => $q->where('product_id', $productId));

        $periodOrders = (clone $periodOrdersQuery)->count();

        $periodRevenue = (clone $periodOrdersQuery)
            ->selectRaw('SUM(order_quantity * order_price) as total')
            ->value('total') ?? 0;

        // Expenses are usually not product-linked, but we keep them filtered by date
        $periodExpenses = Expense::where('user_id', $uid)
            ->whereBetween('date', [$start, $end])
            ->sum('amount');

        // Previous period for trend calculation
        $prevPeriodQuery = Order::where('user_id', $uid)
            ->whereRaw('COALESCE(order_date, DATE(created_at)) BETWEEN ? AND ?', [$pStart, $pEnd])
            ->when($productId, fn($q) => $q->where('product_id', $productId));

        $prevPeriodOrders = (clone $prevPeriodQuery)->count();

        $prevPeriodRevenue = (clone $prevPeriodQuery)
            ->selectRaw('SUM(order_quantity * order_price) as total')
            ->value('total') ?? 0;

        // ── MONTHLY CHART DATA (last 6 months — always fixed) ────────
        $monthlyData = Order::where('user_id', $uid)
            ->selectRaw("DATE_FORMAT(COALESCE(order_date, DATE(created_at)), '%Y-%m') as month,
                         DATE_FORMAT(COALESCE(order_date, DATE(created_at)), '%b %Y') as label,
                         COUNT(*) as order_count,
                         SUM(order_quantity * order_price) as revenue,
                         SUM(order_quantity) as quantity")
            ->groupByRaw("DATE_FORMAT(COALESCE(order_date, DATE(created_at)), '%Y-%m'),
                          DATE_FORMAT(COALESCE(order_date, DATE(created_at)), '%b %Y')")
            ->orderByRaw("DATE_FORMAT(COALESCE(order_date, DATE(created_at)), '%Y-%m') DESC")
            ->limit(6)
            ->get()
            ->reverse()
            ->values();

        $chartLabels   = $monthlyData->pluck('label');
        $chartOrders   = $monthlyData->pluck('order_count');
        $chartRevenue  = $monthlyData->pluck('revenue')->map(fn($v) => round($v, 2));
        $chartQuantity = $monthlyData->pluck('quantity');

        // ── TOP 5 PRODUCTS (filtered by date) ────────────────────────
        $topProducts = Order::where('orders.user_id', $uid)
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->whereRaw('COALESCE(orders.order_date, DATE(orders.created_at)) BETWEEN ? AND ?', [$start, $end])
            ->selectRaw('products.id, products.product_name, SUM(orders.order_quantity) as total_qty, products.unit')
            ->groupBy('products.id', 'products.product_name', 'products.unit')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // ── TOP 5 CUSTOMERS (filtered by date & product) ─────────────
        $topCustomers = Order::where('orders.user_id', $uid)
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->whereRaw('COALESCE(orders.order_date, DATE(orders.created_at)) BETWEEN ? AND ?', [$start, $end])
            ->when($productId, fn($q) => $q->where('product_id', $productId))
            ->selectRaw('customers.customer_name, customers.shop_name,
                         COUNT(*) as order_count,
                         SUM(orders.order_quantity * orders.order_price) as total_amount,
                         SUM(orders.order_quantity) as total_qty')
            ->groupBy('customers.customer_name', 'customers.shop_name')
            ->orderByDesc('order_count')
            ->limit(5)
            ->get();

        // ── RECENT 8 ORDERS (filtered by date & product) ─────────────
        $recentOrders = Order::where('orders.user_id', $uid)
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->whereRaw('COALESCE(orders.order_date, DATE(orders.created_at)) BETWEEN ? AND ?', [$start, $end])
            ->when($productId, fn($q) => $q->where('product_id', $productId))
            ->select(
                'orders.id',
                'orders.order_quantity',
                'orders.order_price',
                'orders.order_date',
                'orders.created_at',
                'products.product_name',
                'products.unit',
                'customers.customer_name',
                'customers.shop_name'
            )
            ->orderBy('orders.created_at', 'desc')
            ->limit(8)
            ->get()
            ->each(function ($o) {
                $o->calculated_total = (float)($o->order_quantity * $o->order_price);
                $o->display_date = date('d M Y', strtotime($o->order_date ?: $o->created_at));
            });

        return [
            /* --- GLOBAL SECTION --- */
            'global' => [
                'totalCustomers' => $totalCustomers,
                'totalProducts'  => $totalProducts,
                'allTimeOrders'  => $allTimeOrders,
                'allTimeRevenue' => (float)$allTimeRevenue,
                'chartLabels'    => $chartLabels,
                'chartOrders'    => $chartOrders,
                'chartRevenue'   => $chartRevenue,
                'chartQuantity'  => $chartQuantity,
                'products'       => $products,
            ],
            /* --- PERIOD SECTION --- */
            'period' => [
                'filter'           => $filter,
                'filterLabel'      => $filterLabel,
                'productId'        => $productId,
                'ordersCount'      => $periodOrders,
                'revenue'          => (float)$periodRevenue,
                'expenses'         => (float)$periodExpenses,
                'prevOrdersCount'  => $prevPeriodOrders,
                'prevRevenue'      => (float)$prevPeriodRevenue,
                'recentOrders'     => $recentOrders,
                'topProducts'      => $topProducts,
                'topCustomers'     => $topCustomers,
            ]
        ];
    }

    // change password
    public function changePassword()
    {
        return view('module.change-password');
    }

    public function changePasswordPost(ChangePasswordRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();
        if (Hash::check($validated['current_password'], $user->password)) {
            $user->password = Hash::make($validated['password']);
            $user->save();

            return redirect()->back()->with('success', trans('portal.password_changed'));
        }

        return redirect()->back()->withErrors([
            'current_password' => __('portal.current_password_incorrect'),
        ])->withInput($request->only('current_password'));
    }
}
