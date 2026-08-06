<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RecipeItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RecipeItemController extends Controller
{
    /** Units offered in the material dropdown. Weight lives in kg (grams as decimals),
     *  volume in litre (ml as decimals), plus piece for counted items. */
    private array $units = ['kg', 'litre', 'piece'];

    public function index(Request $request)
    {
        try {
            $search    = $request->has('search') && !empty($request->search) ? $request->search : null;
            $productId = $request->product_id;

            // Products (of this user) that have at least one recipe item, with the items eager loaded.
            $query = Product::where('products.user_id', auth()->id())
                ->whereHas('recipeItems')
                ->with(['recipeItems' => function ($q) {
                    $q->orderBy('material_name');
                }]);

            if ($productId) {
                $query->where('products.id', $productId);
            }

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('products.product_name->en', 'like', "%{$search}%")
                        ->orWhere('products.product_name->gu', 'like', "%{$search}%")
                        ->orWhereHas('recipeItems', function ($sub) use ($search) {
                            $sub->where('material_name', 'like', "%{$search}%");
                        });
                });
            }

            $products = $query->orderBy('products.product_name->en')->get();

            // For the product filter dropdown.
            $allProducts = Product::where('user_id', auth()->id())
                ->select('id', 'product_name')->orderBy('product_name->en')->get();

            if ($request->ajax()) {
                return view('admin.recipe-item.view', compact('products'));
            }

            return view('admin.recipe-item.index', compact('products', 'allProducts', 'search', 'productId'));
        } catch (\Throwable $th) {
            Log::error('RecipeItemController@index Error: ' . $th->getMessage());
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function create()
    {
        $products  = Product::where('user_id', auth()->id())->orderBy('product_name->en')->get();
        $materials = $this->materialSuggestions();
        return view('admin.recipe-item.create', [
            'products'  => $products,
            'materials' => $materials,
            'units'     => $this->units,
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_id'      => 'required|exists:products,id',
                'base_yield'      => 'required|numeric|min:0.001',
                'material_name'   => 'required|array|min:1',
                'material_name.*' => 'nullable|string|max:255',
                'unit'            => 'required|array',
                'unit.*'          => 'required|in:' . implode(',', $this->units),
                'quantity'        => 'required|array',
                'quantity.*'      => 'nullable|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $product = Product::where('id', $request->product_id)
                ->where('user_id', auth()->id())->firstOrFail();

            $saved = $this->syncItems($product, $request);

            if ($saved === 0) {
                return redirect()->back()->withInput()
                    ->with('error', __('portal.recipe_no_valid_rows'));
            }

            return redirect()->route('admin.recipe-item.index')
                ->with('success', __('portal.recipe_item_created'));
        } catch (\Throwable $th) {
            Log::error('RecipeItemController@store Error: ' . $th->getMessage());
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function edit(Product $product)
    {
        abort_if($product->user_id !== auth()->id(), 403);

        $items     = $product->recipeItems()->orderBy('material_name')->get();
        $materials = $this->materialSuggestions();

        return view('admin.recipe-item.edit', [
            'product'   => $product,
            'items'     => $items,
            'materials' => $materials,
            'units'     => $this->units,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        abort_if($product->user_id !== auth()->id(), 403);

        try {
            $validator = Validator::make($request->all(), [
                'base_yield'      => 'required|numeric|min:0.001',
                'material_name'   => 'required|array|min:1',
                'material_name.*' => 'nullable|string|max:255',
                'unit'            => 'required|array',
                'unit.*'          => 'required|in:' . implode(',', $this->units),
                'quantity'        => 'required|array',
                'quantity.*'      => 'nullable|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $this->syncItems($product, $request);

            return redirect()->route('admin.recipe-item.index')
                ->with('success', __('portal.recipe_item_updated'));
        } catch (\Throwable $th) {
            Log::error('RecipeItemController@update Error: ' . $th->getMessage());
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $product = Product::where('id', $request->input('product_id'))
                ->where('user_id', auth()->id())->firstOrFail();

            $product->recipeItems()->delete();

            return redirect()->route('admin.recipe-item.index')
                ->with('success', __('portal.recipe_item_deleted'));
        } catch (\Throwable $th) {
            Log::error('RecipeItemController@destroy Error: ' . $th->getMessage());
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Replace a product's recipe items with the submitted rows.
     * Empty rows (no material name or zero/blank quantity) are ignored.
     * Returns the number of items saved.
     */
    private function syncItems(Product $product, Request $request): int
    {
        $names      = $request->input('material_name', []);
        $units      = $request->input('unit', []);
        $quantities = $request->input('quantity', []);
        $baseYield  = $request->input('base_yield');

        return DB::transaction(function () use ($product, $names, $units, $quantities, $baseYield) {
            // Remove old items (force delete so we don't accumulate soft-deleted rows).
            $product->recipeItems()->forceDelete();

            $saved = 0;
            foreach ($names as $i => $name) {
                $name = trim((string) $name);
                $qty  = $quantities[$i] ?? null;

                if ($name === '' || $qty === null || $qty === '') {
                    continue;
                }

                RecipeItem::create([
                    'user_id'             => auth()->id(),
                    'product_id'          => $product->id,
                    'material_name'       => $name,
                    'unit'                => $units[$i] ?? 'kg',
                    'quantity'            => $qty,
                    'base_yield_quantity' => $baseYield,
                ]);
                $saved++;
            }

            return $saved;
        });
    }

    /** Distinct material names this user has already used, for autocomplete. */
    private function materialSuggestions()
    {
        return RecipeItem::where('user_id', auth()->id())
            ->select('material_name')->distinct()
            ->orderBy('material_name')->pluck('material_name');
    }
}
