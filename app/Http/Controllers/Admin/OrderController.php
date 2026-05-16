<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        try {
            $search = $request->has('search') && !empty($request->search) ? $request->search : null;
            $limit  = $request->has('limit') ? (int) $request->limit : 10;

            $query = Order::with([
                'product' => function($q) { $q->withTrashed(); },
                'customer' => function($q) { $q->withTrashed(); }
            ])
                ->where('user_id', auth()->id());

            if ($request->order_date) {
                $query->whereDate('orders.order_date', $request->order_date);
            } else {
                if ($request->start_date) {
                    $query->whereDate('orders.order_date', '>=', $request->start_date);
                }
                if ($request->end_date) {
                    $query->whereDate('orders.order_date', '<=', $request->end_date);
                }
            }
            if ($request->customer_id) {
                $query->where('orders.customer_id', $request->customer_id);
            }
            if ($request->product_id) {
                $query->where('orders.product_id', $request->product_id);
            }
            if ($request->filled('order_type')) {
                $orderTypes = (array) $request->order_type;
                $orderTypes = array_filter($orderTypes);
                if (!empty($orderTypes)) {
                    $query->whereIn('orders.order_type', $orderTypes);
                }
            }
            if ($request->filled('payment_type')) {
                $paymentTypes = (array) $request->payment_type;
                $paymentTypes = array_filter($paymentTypes);
                if (!empty($paymentTypes)) {
                    $query->whereIn('orders.payment_type', $paymentTypes);
                }
            }
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('product', function($pq) use ($search) {
                        $pq->where('product_name->en', 'like', "%{$search}%")
                           ->orWhere('product_name->gu', 'like', "%{$search}%");
                    })
                    ->orWhereHas('customer', function($cq) use ($search) {
                        $cq->where('customer_name->en', 'like', "%{$search}%")
                           ->orWhere('customer_name->gu', 'like', "%{$search}%")
                           ->orWhere('shop_name->en', 'like', "%{$search}%")
                           ->orWhere('shop_name->gu', 'like', "%{$search}%");
                    })
                    ->orWhere('order_quantity', 'like', "%{$search}%");
                });
            }

            $orders = $query->orderBy('orders.order_date', 'desc')->orderBy('orders.created_at', 'desc')->paginate($limit);

            $customers = Customer::select('id', 'customer_name', 'shop_name')
                ->where('user_id', auth()->id())
                ->orderBy('customer_name')
                ->get();

            $products = Product::select('id', 'product_name', 'unit')
                ->where('user_id', auth()->id())
                ->orderBy('product_name')
                ->get();

            if ($request->ajax()) {
                return view('admin.order.view', ['orders' => $orders]);
            }
            return view('admin.order.index', compact('orders', 'limit', 'search', 'customers', 'products'));
        } catch (\Exception $e) {
            Log::error('OrderController@index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while fetching orders.');
        }
    }

    public function create()
    {
        $customers = Customer::select('shop_name', 'customer_name', 'id')->where('user_id', auth()->id())->where('status', 'Active')->get();
        return view('admin.order.create', compact('customers'));
    }

    public function getProductsByCustomer(Request $request)
    {
        $customerId = $request->customer_id;
        $products = Product::where('user_id', auth()->id())
            ->where('status', 'Active')
            ->get()
            ->map(function($product) use ($customerId) {
                return [
                    'id' => $product->id,
                    'product_name' => $product->product_name, // Translatable
                    'unit' => $product->unit,
                    'product_base_price' => $this->getEffectivePrice($product->id, $customerId)
                ];
            });
        
        return response()->json($products);
    }

    public function checkDuplicateOrder(Request $request)
    {
        try {
            $customerId = $request->customer_id;
            $productId = $request->product_id;
            $orderDate = $request->order_date;

            $exists = Order::where('customer_id', $customerId)
                ->where('product_id', $productId)
                ->whereDate('order_date', $orderDate)
                ->where('user_id', auth()->id())
                ->exists();

            return response()->json(['exists' => $exists]);
        } catch (\Exception $e) {
            Log::error('OrderController@checkDuplicateOrder error: ' . $e->getMessage() . ' Line: ' . $e->getLine() . ' File: ' . $e->getFile());
            return response()->json(['exists' => false, 'error' => $e->getMessage()], 500);
        }
    }
    
    private function getEffectivePrice($productId, $customerId)
    {
        $specialPrice = ProductPrice::where('product_id', $productId)
            ->where('customer_id', $customerId)
            ->first();
            
        if ($specialPrice) {
            return $specialPrice->price;
        }
        
        $product = Product::findOrFail($productId);
        return $product->product_base_price;
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'customer'       => 'required|integer',
                'product'        => 'required|integer',
                'order_quantity' => 'required|numeric|min:0.01',
                'order_date'     => 'required|date',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $customer = Customer::where('id', $request->customer)->where('user_id', auth()->id())->firstOrFail();
            $product  = Product::where('id', $request->product)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            Order::create([
                'user_id'        => auth()->id(),
                'customer_id'    => $customer->id,
                'product_id'     => $product->id,
                'order_quantity' => $request->order_quantity,
                'order_price'    => $this->getEffectivePrice($product->id, $customer->id),
                'order_date'     => $request->order_date,
                'order_type'     => $request->order_type ?? 'sell',
                'payment_type'   => $request->payment_type ?? 'remaining',
            ]);

            return redirect()->route('admin.order.index')->with('success', __('portal.order_created'));
        } catch (\Exception $e) {
            Log::error('order creation error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Something went wrong');
        }
    }

    public function edit(Request $request, Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);
        $page = $request->page;
        $products = Product::where('products.user_id', auth()->id())
            ->leftJoin('product_prices', function($join) use ($order) {
                $join->on('products.id', '=', 'product_prices.product_id')
                     ->where('product_prices.customer_id', '=', $order->customer_id);
            })
            ->select(
                'products.id',
                'products.product_name',
                'products.unit',
                DB::raw('COALESCE(product_prices.price, products.product_base_price) as effective_price')
            )
            ->get();
        $customers = Customer::select('shop_name', 'customer_name', 'id')->where('user_id', auth()->id())->get();
        return view('admin.order.edit', compact('order', 'products', 'customers', 'page'));
    }

    public function update(Request $request, Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);
        try {
            $validator = Validator::make($request->all(), [
                'product'        => 'required|integer',
                'order_quantity' => 'required|numeric|min:0.01',
                'order_date'     => 'nullable|date',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $product = Product::where('id', $request->product)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $order->update([
                'product_id'     => $product->id,
                'order_quantity' => $request->order_quantity,
                'order_price'    => $this->getEffectivePrice($product->id, $order->customer_id),
                'order_date'     => $request->order_date ?: $order->order_date,
                'order_type'     => $request->order_type ?? $order->order_type,
                'payment_type'   => $request->payment_type ?? $order->payment_type,
            ]);

            return redirect()->route('admin.order.index', ['page' => $request->page])->with('success', __('portal.order_updated'));
        } catch (\Throwable $th) {
            Log::error('OrderController@update Error: ' . $th->getMessage());
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $orderId = $request->input('order_id');
            $order = Order::where('id', $orderId)->where('user_id', auth()->id())->firstOrFail();
            $order->delete();
            return redirect()->route('admin.order.index')->with('success', __('portal.order_deleted'));
        } catch (\Throwable $th) {
            Log::error('OrderController@destroy Error: ' . $th->getMessage());
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
