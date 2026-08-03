<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RequirementReportController extends Controller
{
    public function index(Request $request)
    {
        try {
            $date       = $request->date ?: now()->toDateString();
            $customerId = $request->customer_id;

            $result    = $this->calculate($date, $customerId);
            $customers = Customer::where('user_id', auth()->id())
                ->select('id', 'customer_name', 'shop_name')->orderBy('shop_name')->get();

            return view('admin.requirement-report.index', array_merge($result, [
                'date'       => $date,
                'customerId' => $customerId,
                'customers'  => $customers,
            ]));
        } catch (\Throwable $th) {
            Log::error('RequirementReportController@index Error: ' . $th->getMessage());
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Scale every order's recipe for the given date and aggregate raw materials.
     *
     * Returns:
     *  - totals:    aggregated material need [ ['name','unit','qty'], ... ]
     *  - breakdown: per-order material need for detail view
     *  - missing:   products in orders that have no usable recipe
     *  - orderCount
     */
    private function calculate(string $date, $customerId = null): array
    {
        $orders = Order::where('orders.user_id', auth()->id())
            ->whereDate('orders.order_date', $date)
            ->when($customerId, fn ($q) => $q->where('orders.customer_id', $customerId))
            ->with([
                'product' => function ($q) {
                    $q->withTrashed()->with('recipeItems');
                },
                'customer' => fn ($q) => $q->withTrashed(),
            ])
            ->get();

        $totals    = [];   // key: lower(name)|unit
        $breakdown = [];
        $missing   = [];

        foreach ($orders as $order) {
            $product = $order->product;
            if (!$product) {
                continue;
            }

            $items = $product->recipeItems;
            $base  = optional($items->first())->base_yield_quantity;

            if ($items->isEmpty() || !$base || $base <= 0) {
                $missing[$product->id] = $product->product_name;
                continue;
            }

            $scale = $order->order_quantity / $base;

            $line = [
                'product'   => $product->product_name,
                'customer'  => optional($order->customer)->shop_name ?: optional($order->customer)->customer_name,
                'order_qty' => $order->order_quantity,
                'unit'      => $product->unit,
                'materials' => [],
            ];

            foreach ($items as $item) {
                $needed = (float) $item->quantity * $scale;
                $key    = mb_strtolower($item->material_name) . '|' . $item->unit;

                if (!isset($totals[$key])) {
                    $totals[$key] = ['name' => $item->material_name, 'unit' => $item->unit, 'qty' => 0];
                }
                $totals[$key]['qty'] += $needed;

                $line['materials'][] = ['name' => $item->material_name, 'unit' => $item->unit, 'qty' => $needed];
            }

            $breakdown[] = $line;
        }

        // Sort aggregated totals by material name.
        usort($totals, fn ($a, $b) => strcmp($a['name'], $b['name']));

        return [
            'totals'     => $totals,
            'breakdown'  => $breakdown,
            'missing'    => $missing,
            'orderCount' => $orders->count(),
        ];
    }
}
