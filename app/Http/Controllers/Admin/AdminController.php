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
        
        $allTime = Order::where('user_id', $uid)->selectRaw("
            SUM(CASE WHEN order_type = 'sell' THEN 1 ELSE 0 END) as sell_count,
            SUM(CASE WHEN order_type = 'sell' THEN order_quantity * order_price ELSE 0 END) as sell_rev,
            SUM(CASE WHEN order_type = 'purchase' THEN 1 ELSE 0 END) as purchase_count,
            SUM(CASE WHEN order_type = 'purchase' THEN order_quantity * order_price ELSE 0 END) as purchase_rev,
            SUM(CASE WHEN payment_type = 'remaining' THEN 1 ELSE 0 END) as remaining_count,
            SUM(CASE WHEN payment_type = 'remaining' THEN order_quantity * order_price ELSE 0 END) as remaining_rev,
            SUM(CASE WHEN payment_type = 'cash' THEN 1 ELSE 0 END) as cash_count,
            SUM(CASE WHEN payment_type = 'cash' THEN order_quantity * order_price ELSE 0 END) as cash_rev
        ")->first();

        $allTimeSellOrders       = (int)($allTime->sell_count ?? 0);
        $allTimeSellRevenue      = $allTime->sell_rev ?? 0;
        $allTimePurchaseOrders   = (int)($allTime->purchase_count ?? 0);
        $allTimePurchaseRevenue  = $allTime->purchase_rev ?? 0;
        $allTimeRemainingOrders  = (int)($allTime->remaining_count ?? 0);
        $allTimeRemainingRevenue = $allTime->remaining_rev ?? 0;
        $allTimeCashOrders       = (int)($allTime->cash_count ?? 0);
        $allTimeCashRevenue      = $allTime->cash_rev ?? 0;
        
        $products = Product::where('user_id', $uid)
            ->orderBy('product_name->' . app()->getLocale())
            ->get(['id', 'product_name', 'unit'])
            ->each(function($p) {
                $p->product_name = $this->translate($p->product_name);
            });

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
            case 'last_month':
                $startDate   = Carbon::now()->subMonth()->startOfMonth();
                $endDate     = Carbon::now()->subMonth()->endOfMonth();
                $prevStart   = Carbon::now()->subMonths(2)->startOfMonth();
                $prevEnd     = Carbon::now()->subMonths(2)->endOfMonth();
                $filterLabel = 'Last Month';
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

        // Counts + revenues for every type/payment bucket in a single query
        $statSums = "
            SUM(CASE WHEN order_type = 'sell' THEN 1 ELSE 0 END) as sell_count,
            SUM(CASE WHEN order_type = 'sell' THEN order_quantity * order_price ELSE 0 END) as sell_rev,
            SUM(CASE WHEN order_type = 'purchase' THEN 1 ELSE 0 END) as purchase_count,
            SUM(CASE WHEN order_type = 'purchase' THEN order_quantity * order_price ELSE 0 END) as purchase_rev,
            SUM(CASE WHEN payment_type = 'remaining' THEN 1 ELSE 0 END) as remaining_count,
            SUM(CASE WHEN payment_type = 'remaining' THEN order_quantity * order_price ELSE 0 END) as remaining_rev,
            SUM(CASE WHEN payment_type = 'cash' THEN 1 ELSE 0 END) as cash_count,
            SUM(CASE WHEN payment_type = 'cash' THEN order_quantity * order_price ELSE 0 END) as cash_rev";

        $periodStats = (clone $periodOrdersQuery)->selectRaw($statSums)->first();
        $periodSellOrders       = (int)($periodStats->sell_count ?? 0);
        $periodSellRevenue      = $periodStats->sell_rev ?? 0;
        $periodPurchaseOrders   = (int)($periodStats->purchase_count ?? 0);
        $periodPurchaseRevenue  = $periodStats->purchase_rev ?? 0;
        $periodRemainingOrders  = (int)($periodStats->remaining_count ?? 0);
        $periodRemainingRevenue = $periodStats->remaining_rev ?? 0;
        $periodCashOrders       = (int)($periodStats->cash_count ?? 0);
        $periodCashRevenue      = $periodStats->cash_rev ?? 0;

        // Expenses are usually not product-linked, but we keep them filtered by date
        $periodExpenses = Expense::where('user_id', $uid)
            ->whereBetween('date', [$start, $end])
            ->sum('amount');

        // Previous period for trend calculation
        $prevPeriodQuery = Order::where('user_id', $uid)
            ->whereRaw('COALESCE(order_date, DATE(created_at)) BETWEEN ? AND ?', [$pStart, $pEnd])
            ->when($productId, fn($q) => $q->where('product_id', $productId));

        $prevStats = (clone $prevPeriodQuery)->selectRaw($statSums)->first();
        $prevPeriodSellOrders       = (int)($prevStats->sell_count ?? 0);
        $prevPeriodSellRevenue      = $prevStats->sell_rev ?? 0;
        $prevPeriodPurchaseOrders   = (int)($prevStats->purchase_count ?? 0);
        $prevPeriodPurchaseRevenue  = $prevStats->purchase_rev ?? 0;
        $prevPeriodRemainingOrders  = (int)($prevStats->remaining_count ?? 0);
        $prevPeriodRemainingRevenue = $prevStats->remaining_rev ?? 0;
        $prevPeriodCashOrders       = (int)($prevStats->cash_count ?? 0);
        $prevPeriodCashRevenue      = $prevStats->cash_rev ?? 0;

        $prevPeriodExpenses = Expense::where('user_id', $uid)
            ->whereBetween('date', [$pStart, $pEnd])
            ->sum('amount');

        // ── DYNAMIC CHART DATA (Based on Filter) ─────────────────────
        $chartLabels          = collect();
        $chartSellRevenue     = collect();
        $chartPurchaseRevenue = collect();
        $chartRemainingRevenue = collect();
        $chartCashRevenue     = collect();
        $chartOrders          = collect();
        $chartQuantity        = collect();
        
        // Conditional-aggregate columns shared by every chart bucket
        $bucketSums = "
            SUM(CASE WHEN order_type = 'sell' THEN order_quantity * order_price ELSE 0 END) as sell,
            SUM(CASE WHEN order_type = 'purchase' THEN order_quantity * order_price ELSE 0 END) as purchase,
            SUM(CASE WHEN payment_type = 'remaining' THEN order_quantity * order_price ELSE 0 END) as remaining,
            SUM(CASE WHEN payment_type = 'cash' THEN order_quantity * order_price ELSE 0 END) as cash";

        // Given an ordered map of [bucketKey => label] and a keyed result set,
        // push each bucket's label + revenues (0 when the bucket has no rows).
        $fillChart = function (array $buckets, $rows) use (
            &$chartLabels, &$chartSellRevenue, &$chartPurchaseRevenue, &$chartRemainingRevenue, &$chartCashRevenue
        ) {
            foreach ($buckets as $key => $label) {
                $r = $rows[$key] ?? null;
                $chartLabels->push($label);
                $chartSellRevenue->push((float)($r->sell ?? 0));
                $chartPurchaseRevenue->push((float)($r->purchase ?? 0));
                $chartRemainingRevenue->push((float)($r->remaining ?? 0));
                $chartCashRevenue->push((float)($r->cash ?? 0));
            }
        };

        switch ($filter) {
            case 'today':
            case 'yesterday':
                // Hourly data for the specific day (one grouped query)
                $targetDate = ($filter === 'today') ? Carbon::today() : Carbon::yesterday();
                $rows = Order::where('user_id', $uid)
                    ->whereRaw("DATE(COALESCE(order_date, created_at)) = ?", [$targetDate->toDateString()])
                    ->selectRaw("HOUR(created_at) as bucket, $bucketSums")
                    ->groupByRaw("HOUR(created_at)")
                    ->get()->keyBy('bucket');

                $buckets = [];
                for ($i = 0; $i < 24; $i++) {
                    $buckets[$i] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
                }
                $fillChart($buckets, $rows);
                break;

            case 'current_week':
                // Last 7 days (one grouped query)
                $rows = Order::where('user_id', $uid)
                    ->whereRaw("DATE(COALESCE(order_date, created_at)) BETWEEN ? AND ?",
                        [Carbon::now()->subDays(6)->toDateString(), Carbon::now()->toDateString()])
                    ->selectRaw("DATE(COALESCE(order_date, created_at)) as bucket, $bucketSums")
                    ->groupByRaw("DATE(COALESCE(order_date, created_at))")
                    ->get()->keyBy('bucket');

                $buckets = [];
                for ($i = 6; $i >= 0; $i--) {
                    $day = Carbon::now()->subDays($i);
                    $buckets[$day->toDateString()] = $day->format('D d M');
                }
                $fillChart($buckets, $rows);
                break;

            case 'current_month':
                // Last 30 days (one grouped query)
                $rows = Order::where('user_id', $uid)
                    ->whereRaw("DATE(COALESCE(order_date, created_at)) BETWEEN ? AND ?",
                        [Carbon::now()->subDays(29)->toDateString(), Carbon::now()->toDateString()])
                    ->selectRaw("DATE(COALESCE(order_date, created_at)) as bucket, $bucketSums")
                    ->groupByRaw("DATE(COALESCE(order_date, created_at))")
                    ->get()->keyBy('bucket');

                $buckets = [];
                for ($i = 29; $i >= 0; $i--) {
                    $day = Carbon::now()->subDays($i);
                    $buckets[$day->toDateString()] = $day->format('d M');
                }
                $fillChart($buckets, $rows);
                break;

            case 'last_month':
                // All days of last calendar month
                $lmStart = Carbon::now()->subMonth()->startOfMonth();
                $lmEnd   = Carbon::now()->subMonth()->endOfMonth();
                $rows = Order::where('user_id', $uid)
                    ->whereRaw("DATE(COALESCE(order_date, created_at)) BETWEEN ? AND ?",
                        [$lmStart->toDateString(), $lmEnd->toDateString()])
                    ->selectRaw("DATE(COALESCE(order_date, created_at)) as bucket, $bucketSums")
                    ->groupByRaw("DATE(COALESCE(order_date, created_at))")
                    ->get()->keyBy('bucket');

                $buckets = [];
                $daysInLastMonth = $lmStart->daysInMonth;
                for ($i = 0; $i < $daysInLastMonth; $i++) {
                    $day = $lmStart->copy()->addDays($i);
                    $buckets[$day->toDateString()] = $day->format('d M');
                }
                $fillChart($buckets, $rows);
                break;

            case 'current_year':
            default:
                // Last 12 months (one grouped query)
                $rows = Order::where('user_id', $uid)
                    ->whereRaw("DATE_FORMAT(COALESCE(order_date, created_at), '%Y-%m') BETWEEN ? AND ?",
                        [Carbon::now()->subMonths(11)->format('Y-m'), Carbon::now()->format('Y-m')])
                    ->selectRaw("DATE_FORMAT(COALESCE(order_date, created_at), '%Y-%m') as bucket, $bucketSums")
                    ->groupByRaw("DATE_FORMAT(COALESCE(order_date, created_at), '%Y-%m')")
                    ->get()->keyBy('bucket');

                $buckets = [];
                for ($i = 11; $i >= 0; $i--) {
                    $month = Carbon::now()->subMonths($i);
                    $buckets[$month->format('Y-m')] = $month->format('M Y');
                }
                $fillChart($buckets, $rows);
                break;
        }

        // ── TOP 5 PRODUCTS (filtered by date) ────────────────────────
        $topProducts = Order::where('orders.user_id', $uid)
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->whereRaw('COALESCE(orders.order_date, DATE(orders.created_at)) BETWEEN ? AND ?', [$start, $end])
            ->selectRaw('products.id, products.product_name, products.unit,
                         SUM(orders.order_quantity) as total_qty,
                         SUM(CASE WHEN orders.order_type = \'sell\' THEN orders.order_quantity ELSE 0 END) as total_sell_qty,
                         SUM(CASE WHEN orders.order_type = \'purchase\' THEN orders.order_quantity ELSE 0 END) as total_purchase_qty,
                         SUM(CASE WHEN orders.payment_type = \'remaining\' THEN orders.order_quantity ELSE 0 END) as total_remaining_qty,
                         SUM(CASE WHEN orders.payment_type = \'cash\' THEN orders.order_quantity ELSE 0 END) as total_cash_qty')
            ->groupBy('products.id', 'products.product_name', 'products.unit')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get()
            ->each(function($p) {
                $p->product_name = $this->translate($p->product_name);
            });

        // ── TOP 5 CUSTOMERS (filtered by date & product) ─────────────
        $topCustomers = Order::where('orders.user_id', $uid)
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->whereRaw('COALESCE(orders.order_date, DATE(orders.created_at)) BETWEEN ? AND ?', [$start, $end])
            ->when($productId, fn($q) => $q->where('product_id', $productId))
            ->selectRaw('customers.customer_name, customers.shop_name,
                         COUNT(*) as order_count,
                         SUM(orders.order_quantity * orders.order_price) as total_amount,
                         SUM(orders.order_quantity) as total_qty,
                         SUM(CASE WHEN LOWER(COALESCE(products.unit, \'kg\')) = \'kg\' THEN orders.order_quantity ELSE 0 END) as total_kg,
                         SUM(CASE WHEN LOWER(COALESCE(products.unit, \'kg\')) = \'nang\' THEN orders.order_quantity ELSE 0 END) as total_nang')
            ->groupBy('customers.customer_name', 'customers.shop_name')
            ->orderByDesc('total_amount')
            ->limit(5)
            ->get()
            ->each(function($c) {
                $c->customer_name = $this->translate($c->customer_name);
                $c->shop_name = $this->translate($c->shop_name);
            });

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
                'orders.order_type',
                'orders.payment_type',
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
                $o->product_name = $this->translate($o->product_name);
                $o->customer_name = $this->translate($o->customer_name);
                $o->shop_name = $this->translate($o->shop_name);
            });

        return [
            /* --- GLOBAL SECTION --- */
            'global' => [
                'totalCustomers'         => $totalCustomers,
                'totalProducts'          => $totalProducts,
                'allTimeSellOrders'      => $allTimeSellOrders,
                'allTimeSellRevenue'     => (float)$allTimeSellRevenue,
                'allTimePurchaseOrders'  => $allTimePurchaseOrders,
                'allTimePurchaseRevenue' => (float)$allTimePurchaseRevenue,
                'allTimeRemainingOrders'  => $allTimeRemainingOrders,
                'allTimeRemainingRevenue' => (float)$allTimeRemainingRevenue,
                'allTimeCashOrders'       => $allTimeCashOrders,
                'allTimeCashRevenue'      => (float)$allTimeCashRevenue,
                
                'chartLabels'          => $chartLabels,
                'chartOrders'          => $chartOrders,
                'chartSellRevenue'     => $chartSellRevenue,
                'chartPurchaseRevenue' => $chartPurchaseRevenue,
                'chartRemainingRevenue' => $chartRemainingRevenue,
                'chartCashRevenue'     => $chartCashRevenue,
                'chartQuantity'        => $chartQuantity,
                'products'             => $products,
            ],
            /* --- PERIOD SECTION --- */
            'period' => [
                'filter'           => $filter,
                'filterLabel'      => $filterLabel,
                'productId'        => $productId,
                
                'sellOrdersCount'  => $periodSellOrders,
                'sellRevenue'      => (float)$periodSellRevenue,
                'prevSellOrdersCount' => $prevPeriodSellOrders,
                'prevSellRevenue'  => (float)$prevPeriodSellRevenue,
                
                'purchaseOrdersCount' => $periodPurchaseOrders,
                'purchaseRevenue'  => (float)$periodPurchaseRevenue,
                'prevPurchaseOrdersCount' => $prevPeriodPurchaseOrders,
                'prevPurchaseRevenue' => (float)$prevPeriodPurchaseRevenue,

                'remainingOrdersCount' => $periodRemainingOrders,
                'remainingRevenue'  => (float)$periodRemainingRevenue,
                'prevRemainingOrdersCount' => $prevPeriodRemainingOrders,
                'prevRemainingRevenue' => (float)$prevPeriodRemainingRevenue,

                'cashOrdersCount' => $periodCashOrders,
                'cashRevenue'  => (float)$periodCashRevenue,
                'prevCashOrdersCount' => $prevPeriodCashOrders,
                'prevCashRevenue' => (float)$prevPeriodCashRevenue,
                
                'expenses'         => (float)$periodExpenses,
                'prevExpenses'     => (float)$prevPeriodExpenses,
                
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

    private function translate($json)
    {
        if (empty($json)) return '';
        if (is_array($json)) {
            return $json[app()->getLocale()] ?? $json['en'] ?? '';
        }
        $data = json_decode($json, true);
        if (!is_array($data)) return $json;
        return $data[app()->getLocale()] ?? $data['en'] ?? '';
    }
}
