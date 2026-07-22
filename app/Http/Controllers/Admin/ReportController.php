<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CustomerExport;
use App\Exports\ExpenseExport;
use App\Exports\OrderExport;
use App\Exports\ProductExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    // change password
    public function index()
    {
        // Set the current month in "YYYY-MM" format
        $selectedMonthYear = Carbon::now()->format('Y-m');

        // Customer list for dropdown
        $customers = Customer::select('id', 'customer_name', 'shop_name')
            // ->where('status', '1')
            ->where('user_id', auth()->id())
            ->orderBy('customer_name')
            ->get();

        // Order months for dropdown — use order_date when set, fall back to created_at
        $orderMonths = Order::selectRaw("DATE_FORMAT(COALESCE(order_date, DATE(created_at)), '%Y-%m') as value, DATE_FORMAT(COALESCE(order_date, DATE(created_at)), '%M-%Y') as label")
            ->where('user_id', auth()->id())
            ->groupByRaw("DATE_FORMAT(COALESCE(order_date, DATE(created_at)), '%Y-%m'), DATE_FORMAT(COALESCE(order_date, DATE(created_at)), '%M-%Y')")
            ->orderByRaw("DATE_FORMAT(COALESCE(order_date, DATE(created_at)), '%Y-%m') DESC")
            ->get();

        // Expense months for dropdown — use manual date when set, fall back to created_at
        $expenseMonths = Expense::selectRaw("DATE_FORMAT(COALESCE(date, DATE(created_at)), '%Y-%m') as value, DATE_FORMAT(COALESCE(date, DATE(created_at)), '%M-%Y') as label")
            ->where('user_id', auth()->id())
            ->groupByRaw("DATE_FORMAT(COALESCE(date, DATE(created_at)), '%Y-%m'), DATE_FORMAT(COALESCE(date, DATE(created_at)), '%M-%Y')")
            ->orderByRaw("DATE_FORMAT(COALESCE(date, DATE(created_at)), '%Y-%m') DESC")
            ->get();

        // Combined months for the date-wise summary report (orders + expenses)
        $summaryMonths = $orderMonths->concat($expenseMonths)
            ->unique('value')
            ->sortByDesc('value')
            ->values();

        return view('admin.monthly-report.index', compact(
            'selectedMonthYear',
            'customers',
            'orderMonths',
            'expenseMonths',
            'summaryMonths'
        ));
    }

    public function customerReport(Request $request)
    {
        try {
            $count = Customer::where('user_id', auth()->id())->count();
            if ($count === 0) {
                return redirect()->back()->with('error', 'No customers found to export.');
            }

            return Excel::download(new CustomerExport(), 'Customer-List.xlsx');

        } catch (\Throwable $th) {
            Log::error('ReportController@customerReport Error: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Could not export customers. Please try again.');
        }
    }

    public function productReport(Request $request)
    {
        try {
            $count = \App\Models\Product::where('user_id', auth()->id())->count();
            if ($count === 0) {
                return redirect()->back()->with('error', 'No products found to export.');
            }

            return Excel::download(new ProductExport(), 'Product-List.xlsx');
        } catch (\Throwable $th) {
            Log::error('ReportController@productReport Error: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Could not export products. Please try again.');
        }
    }

    public function orderReport(Request $request)
    {
        try {
            $customerId = $request->input('customer_id');
            $monthYear  = $request->input('month_year');

            if (!$customerId) {
                return redirect()->back()->with('error', __('portal.select_customer_for_export'));
            }

            if (!$monthYear) {
                return redirect()->back()->with('error', __('portal.select_month_year_error'));
            }

            // Get filtered orders using Eloquent relations
            $orders = Order::with(['product', 'customer'])
                ->where('user_id', auth()->id())
                ->when($customerId, function ($query) use ($customerId) {
                    $query->where('customer_id', $customerId);
                })
                ->when($monthYear, function ($query) use ($monthYear) {
                    $start = Carbon::parse($monthYear . '-01')->startOfMonth()->toDateString();
                    $end   = Carbon::parse($monthYear . '-01')->endOfMonth()->toDateString();
                    $query->where(function ($q) use ($start, $end) {
                        $q->whereBetween('order_date', [$start, $end])
                          ->orWhere(function ($q2) use ($start, $end) {
                              $q2->whereNull('order_date')
                                 ->whereDate('created_at', '>=', $start)
                                 ->whereDate('created_at', '<=', $end);
                          });
                    });
                })
                ->orderBy('order_date', 'asc')
                ->orderBy('created_at', 'asc')
                ->get();

            if ($orders->isEmpty()) {
                return redirect()->back()->with('error', 'No orders found for the selected filters. Please adjust your selection and try again.');
            }

            // Force English locale for report generation
            $previousLocale = app()->getLocale();
            app()->setLocale('en');

            try {
                $totalOrderAmount = 0;
                $totalOrderQuantity = 0;
                $totalKg = 0;
                $totalNang = 0;
                $monthName = null;
                $customerInfo = null;

                if ($monthYear) {
                    $monthName = Carbon::parse($monthYear . '-01')->format('F Y');
                }

                foreach ($orders as $order) {
                    $order->calculated_total = $order->order_quantity * $order->order_price;
                    $totalOrderAmount += $order->calculated_total;
                    $totalOrderQuantity += $order->order_quantity;

                    $unit = strtolower($order->product->unit ?? 'kg');
                    if ($unit === 'nang') {
                        $totalNang += $order->order_quantity;
                    } else {
                        $totalKg += $order->order_quantity;
                    }
                }

                // Fetch customer details if a specific customer is selected
                if ($customerId) {
                    $customerInfo = Customer::find($customerId);
                }

                // Dynamic Date
                $reportDate = now()->format('d M Y, h:i A');

                // $receiptNo = now()->format('Y') . '-' . now()->addYear()->format('y') . '-' . str_pad($orders->count(), 4, '0', STR_PAD_LEFT);
                $receiptNo = str_pad($orders->count(), 4, '0', STR_PAD_LEFT);

                // Dynamic Logo Path (absolute path required for DomPDF)
                $logoPath = public_path('images/logo.png');

                $exportType = $request->input('export_type', 'challan');

                $data = [
                    'orders' => $orders,
                    'monthName' => $monthName,
                    'monthYear' => $monthYear,
                    'totalOrderAmount' => $totalOrderAmount,
                    'totalOrderQuantity' => $totalOrderQuantity,
                    'totalKg' => $totalKg,
                    'totalNang' => $totalNang,
                    'reportDate' => $reportDate,
                    'logoPath' => $logoPath,
                    'customerInfo' => $customerInfo,
                    'receiptNo' => $receiptNo,
                ];

                if ($exportType === 'bill_image') {
                    app()->setLocale($previousLocale);
                    return view('admin.monthly-report.order-bill-image', $data);
                }

                $viewName = $exportType === 'bill' ? 'admin.monthly-report.order-bill' : 'admin.monthly-report.order-challan';

                $pdf = \PDF::loadView($viewName, $data);
                $pdf->setPaper('A4', 'portrait');

                $shopPart = $customerInfo
                    ? rtrim(preg_replace('/[^A-Za-z0-9]+/', '-', $customerInfo->shop_name), '-') . '-'
                    : '';
                $datePart = now()->format('d-M-Y');
                $prefix = $exportType === 'bill' ? 'Order-Bill-' : 'Order-Challan-';
                $fileName = $shopPart . $prefix . $datePart . '.pdf';

                app()->setLocale($previousLocale);
                return $pdf->download($fileName);
            } finally {
                app()->setLocale($previousLocale);
            }
        } catch (\Throwable $th) {
            Log::error('ReportController@orderReport Error: ' . $th->getMessage());
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function expenseReport(Request $request)
    {
        try {
            $monthYear   = $request->input('month_year');
            $expenseType = $request->input('expense_type'); // 'personal', 'business', or null for all

            if (!$monthYear) {
                return redirect()->back()->with('error', __('portal.select_month_year_error'));
            }

            $query = Expense::where('user_id', auth()->id())
                ->whereRaw("DATE_FORMAT(COALESCE(date, DATE(created_at)), '%Y-%m') = ?", [$monthYear]);

            if ($expenseType) {
                $query->where('type', $expenseType);
            }

            if ($query->count() === 0) {
                return redirect()->back()->with('error', 'No expenses found for the selected filters.');
            }

            $formatted  = Carbon::parse($monthYear . '-01')->format('F-Y');
            $typeSuffix = $expenseType ? '-' . ucfirst($expenseType) : '';
            return Excel::download(new ExpenseExport($monthYear, $expenseType), $formatted . $typeSuffix . '-Expense-List.xlsx');
        } catch (\Throwable $th) {
            Log::error('ReportController@expenseReport Error: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Could not export expenses. Please try again.');
        }
    }

    /**
     * Date-wise monthly summary: one row per date, with order (sell / purchase)
     * and expense (business / personal) totals side by side.
     */
    public function summaryReport(Request $request)
    {
        try {
            $monthYear = $request->input('month_year');

            if (!$monthYear) {
                return redirect()->back()->with('error', __('portal.select_month_year_error'));
            }

            $userId = auth()->id();

            // Order totals per date, split by order type — use order_date when set, fall back to created_at
            $orderTotals = Order::selectRaw("COALESCE(order_date, DATE(created_at)) as row_date, order_type, SUM(order_quantity * order_price) as total_amount")
                ->where('user_id', $userId)
                ->whereRaw("DATE_FORMAT(COALESCE(order_date, DATE(created_at)), '%Y-%m') = ?", [$monthYear])
                ->groupByRaw("COALESCE(order_date, DATE(created_at)), order_type")
                ->get();

            // Expense totals per date, split by expense type — use manual date when set, fall back to created_at
            $expenseTotals = Expense::selectRaw("COALESCE(date, DATE(created_at)) as row_date, type, SUM(amount) as total_amount")
                ->where('user_id', $userId)
                ->whereRaw("DATE_FORMAT(COALESCE(date, DATE(created_at)), '%Y-%m') = ?", [$monthYear])
                ->groupByRaw("COALESCE(date, DATE(created_at)), type")
                ->get();

            if ($orderTotals->isEmpty() && $expenseTotals->isEmpty()) {
                return redirect()->back()->with('error', __('portal.no_summary_data'));
            }

            // Merge both sides into one date-keyed grid
            $rows = [];
            $blank = ['sell' => 0, 'purchase' => 0, 'business' => 0, 'personal' => 0];

            foreach ($orderTotals as $row) {
                $date = (string) $row->row_date;
                $rows[$date] = $rows[$date] ?? $blank;
                $rows[$date][$row->order_type] = (float) $row->total_amount;
            }

            foreach ($expenseTotals as $row) {
                $date = (string) $row->row_date;
                $rows[$date] = $rows[$date] ?? $blank;
                $rows[$date][$row->type] = (float) $row->total_amount;
            }

            ksort($rows);

            $totals = $blank;
            foreach ($rows as $row) {
                foreach ($blank as $key => $ignored) {
                    $totals[$key] += $row[$key];
                }
            }

            // Force English locale for report generation (DomPDF's bundled font has no Gujarati glyphs)
            $previousLocale = app()->getLocale();
            app()->setLocale('en');

            try {
                $data = [
                    'rows'       => $rows,
                    'totals'     => $totals,
                    'monthYear'  => $monthYear,
                    'monthName'  => Carbon::parse($monthYear . '-01')->format('F Y'),
                    'reportDate' => now()->format('d M Y, h:i A'),
                    'logoPath'   => public_path('images/logo.png'),
                ];

                $pdf = \PDF::loadView('admin.monthly-report.summary', $data);
                $pdf->setPaper('A4', 'portrait');

                $fileName = 'Monthly-Summary-' . Carbon::parse($monthYear . '-01')->format('F-Y') . '.pdf';

                return $pdf->download($fileName);
            } finally {
                app()->setLocale($previousLocale);
            }
        } catch (\Throwable $th) {
            Log::error('ReportController@summaryReport Error: ' . $th->getMessage());
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Read a translatable attribute in English, whatever the current locale is.
     * Falls back to the locale-resolved value when no English translation exists.
     */
    private function englishName(?Customer $model, string $attribute): string
    {
        if (!$model) {
            return '';
        }

        return $model->getTranslation($attribute, 'en') ?: (string) ($model->{$attribute} ?? '');
    }

    /**
     * Date + customer wise order totals: purchase and sell side by side,
     * with the difference (purchase - sell) as the row total.
     */
    public function customerSummaryReport(Request $request)
    {
        try {
            $monthYear = $request->input('month_year');

            if (!$monthYear) {
                return redirect()->back()->with('error', __('portal.select_month_year_error'));
            }

            $userId = auth()->id();

            // Order totals per date + customer, split by order type
            $orderTotals = Order::selectRaw("COALESCE(order_date, DATE(created_at)) as row_date, customer_id, order_type, SUM(order_quantity * order_price) as total_amount")
                ->where('user_id', $userId)
                ->whereRaw("DATE_FORMAT(COALESCE(order_date, DATE(created_at)), '%Y-%m') = ?", [$monthYear])
                ->groupByRaw("COALESCE(order_date, DATE(created_at)), customer_id, order_type")
                ->get();

            if ($orderTotals->isEmpty()) {
                return redirect()->back()->with('error', __('portal.no_customer_summary_data'));
            }

            $customers = Customer::whereIn('id', $orderTotals->pluck('customer_id')->unique())
                ->get()
                ->keyBy('id');

            // One row per date + customer
            $grid = [];
            foreach ($orderTotals as $row) {
                $key = $row->row_date . '|' . $row->customer_id;

                if (!isset($grid[$key])) {
                    $customer = $customers->get($row->customer_id);
                    $grid[$key] = [
                        'date'          => (string) $row->row_date,
                        'shop_name'     => $this->englishName($customer, 'shop_name'),
                        'customer_name' => $this->englishName($customer, 'customer_name'),
                        'purchase'      => 0,
                        'sell'          => 0,
                    ];
                }

                $grid[$key][$row->order_type] = (float) $row->total_amount;
            }

            // Date first, then customer within the same date
            uasort($grid, function ($a, $b) {
                return [$a['date'], $a['shop_name'], $a['customer_name']]
                   <=> [$b['date'], $b['shop_name'], $b['customer_name']];
            });

            $totals = ['purchase' => 0, 'sell' => 0, 'total' => 0];
            foreach ($grid as $key => $row) {
                $grid[$key]['total'] = $row['purchase'] - $row['sell'];

                $totals['purchase'] += $row['purchase'];
                $totals['sell']     += $row['sell'];
                $totals['total']    += $grid[$key]['total'];
            }

            // Force English locale for report generation (DomPDF's bundled font has no Gujarati glyphs)
            $previousLocale = app()->getLocale();
            app()->setLocale('en');

            try {
                $data = [
                    'rows'       => $grid,
                    'totals'     => $totals,
                    'monthYear'  => $monthYear,
                    'monthName'  => Carbon::parse($monthYear . '-01')->format('F Y'),
                    'reportDate' => now()->format('d M Y, h:i A'),
                    'logoPath'   => public_path('images/logo.png'),
                ];

                $pdf = \PDF::loadView('admin.monthly-report.customer-summary', $data);
                $pdf->setPaper('A4', 'portrait');

                $fileName = 'Customer-Summary-' . Carbon::parse($monthYear . '-01')->format('F-Y') . '.pdf';

                return $pdf->download($fileName);
            } finally {
                app()->setLocale($previousLocale);
            }
        } catch (\Throwable $th) {
            Log::error('ReportController@customerSummaryReport Error: ' . $th->getMessage());
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
