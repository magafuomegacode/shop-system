<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Case-insensitive category config lookup.
     */
    private function resolveCategoryConfig(?string $categoryName): array
    {
        if (!$categoryName) {
            return [];
        }

        $allConfigs = config('category_specs', []);
        $needle = strtolower(trim($categoryName));

        foreach ($allConfigs as $key => $value) {
            if (strtolower(trim($key)) === $needle) {
                return is_array($value) ? $value : [];
            }
        }

        return [];
    }

    /**
     * Rudi list ya units halali kwa category.
     * Inaunga mkono 'units' (array) na 'unit' (single).
     */
    private function resolveUnitsFromConfig(?string $categoryName): array
    {
        $cfg = $this->resolveCategoryConfig($categoryName);

        if (!empty($cfg['units']) && is_array($cfg['units'])) {
            return array_values($cfg['units']);
        }

        if (!empty($cfg['unit']) && is_string($cfg['unit'])) {
            return [$cfg['unit']];
        }

        return [];
    }

    /**
     * Unit ya kwanza kama default.
     */
    private function resolveDefaultUnitFromConfig(?string $categoryName): ?string
    {
        $units = $this->resolveUnitsFromConfig($categoryName);
        return $units[0] ?? null;
    }

    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $shopId = $user->shop_id;

        $query = Product::query()
            ->with([
                'store:id,name',
                'category:id,name',
                'creator:id,full_name,role',
                'stocks',
            ]);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($storeId = $request->input('store')) {
            $query->where('store_id', $storeId);
        }

        if ($categoryId = $request->input('category')) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->orderByDesc('created_at')->paginate(15);

        $stores = Store::where('shop_id', $shopId)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $categories = Category::select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('products.index', compact('products', 'stores', 'categories'));
    }

    /**
     * Step 1: Show the category chooser for a specific store.
     */
    public function chooseCategory(Request $request)
    {
        $user = Auth::user();

        $storeId = $request->query('store_id');

        $store = Store::where('shop_id', $user->shop_id)
            ->where('id', $storeId)
            ->firstOrFail();

        $categories = Category::where('store_id', $store->id)
            ->orderBy('name')
            ->get();

        return view('products.choose-category', compact('store', 'categories'));
    }

    /**
     * Step 2: Show the form for creating a new product.
     *
     * Multi-unit support:
     *   - $categoryUnits = list ya units zote
     *   - $categoryUnit  = default (ya kwanza kwenye list)
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        $stores = Store::where('shop_id', $user->shop_id)
            ->where('is_active', true)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $selectedStoreId    = $request->query('store_id');
        $selectedCategoryId = $request->query('category_id');

        $categories = $selectedStoreId
            ? Category::where('store_id', $selectedStoreId)
                ->select('id', 'name')
                ->orderBy('name')
                ->get()
            : collect();

        $selectedCategoryName = $selectedCategoryId
            ? $categories->firstWhere('id', (int) $selectedCategoryId)?->name
            : null;

        // ✅ Case-insensitive config lookup
        $categoryConfig = $this->resolveCategoryConfig($selectedCategoryName);

        $categorySpecs = [];
        $categoryUnits = [];
        $categoryUnit  = null;

        if (!empty($categoryConfig)) {
            $categorySpecs = $categoryConfig['fields'] ?? [];
            $categoryUnits = $this->resolveUnitsFromConfig($selectedCategoryName);
            $categoryUnit  = $categoryUnits[0] ?? null;
        }

        $categorySpecs = collect($categorySpecs)
            ->filter(fn($s) => is_array($s))
            ->values()
            ->toArray();

        return view('products.create', compact(
            'stores',
            'categories',
            'selectedStoreId',
            'selectedCategoryId',
            'selectedCategoryName',
            'categorySpecs',
            'categoryUnits',
            'categoryUnit'
        ));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'store_id'      => ['required', 'exists:stores,id'],
            'category_id'   => ['required', 'exists:categories,id'],
            'name'          => ['required', 'string', 'max:150'],
            'sku'           => ['nullable', 'string', 'max:50', 'unique:products,sku'],
            'unit'          => ['nullable', 'string', 'max:20'],
            'size'          => ['nullable', 'numeric', 'min:0'],
            'cost_price'    => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'quantity'      => ['required', 'integer', 'min:0'],
            'min_quantity'  => ['nullable', 'integer', 'min:0'],
            'is_active'     => ['boolean'],
            'specs'         => ['nullable', 'array'],
        ]);

        $store = Store::where('shop_id', $user->shop_id)
            ->where('id', $validated['store_id'])
            ->firstOrFail();

        $category = Category::where('store_id', $store->id)
            ->where('id', $validated['category_id'])
            ->firstOrFail();

        $specs = collect($request->input('specs', []))
            ->filter(fn($v) => $v !== null && $v !== '')
            ->toArray();

        // ✅ Unit — tumia iliyotumwa, la sivyo default ya kwanza
        $unit = $validated['unit'] ?? null;
        if (!$unit) {
            $unit = $this->resolveDefaultUnitFromConfig($category->name);
        }

        // Thibitisha unit ni halali kwa category
        $allowedUnits = $this->resolveUnitsFromConfig($category->name);
        if ($unit && !empty($allowedUnits) && !in_array($unit, $allowedUnits, true)) {
            $unit = $allowedUnits[0] ?? null;
        }

        DB::beginTransaction();

        try {
            $product = Product::create([
                'store_id'      => $store->id,
                'category_id'   => $category->id,
                'name'          => $validated['name'],
                'sku'           => $validated['sku'] ?? null,
                'unit'          => $unit,
                'size'          => $validated['size'] ?? null,
                'specs'         => $specs ?: null,
                'cost_price'    => $validated['cost_price'] ?? null,
                'selling_price' => $validated['selling_price'],
                'is_active'     => $request->boolean('is_active', true),
                'created_by'    => $user->id,
            ]);

            Stock::create([
                'store_id'     => $store->id,
                'product_id'   => $product->id,
                'quantity'     => $validated['quantity'],
                'min_quantity' => $validated['min_quantity'] ?? 5,
            ]);

            if ($validated['quantity'] > 0) {
                StockMovement::create([
                    'store_id'      => $store->id,
                    'product_id'    => $product->id,
                    'user_id'       => $user->id,
                    'movement_type' => 'in',
                    'quantity'      => $validated['quantity'],
                    'reference'     => 'initial-stock',
                    'note'          => 'Initial stock on product creation',
                ]);
            }

            ActivityLog::log(
                $user->id, $user->shop_id, $store->id,
                'CREATE_PRODUCT', 'products',
                "Created product '{$product->name}' in '{$store->name}' ({$category->name}) with {$validated['quantity']} stock",
                $product->id, 'products',
                null,
                [
                    'name'     => $product->name,
                    'store'    => $store->name,
                    'category' => $category->name,
                    'quantity' => $validated['quantity'],
                    'specs'    => $specs,
                    'unit'     => $unit,
                ]
            );

            Notification::send(
                $user->shop_id,
                'product_added',
                'New Product Added',
                "{$product->name} was added to {$store->name} ({$category->name}) with {$validated['quantity']} " . ($unit ?? 'units') . " stock",
                route('products.show', $product),
                'plus-circle'
            );

            if ($validated['quantity'] <= ($validated['min_quantity'] ?? 5)) {
                Notification::send(
                    $user->shop_id,
                    'low_stock',
                    'Low Stock Alert',
                    "{$product->name} has low stock ({$validated['quantity']} " . ($unit ?? 'units') . ") in {$store->name}",
                    route('products.show', $product),
                    'alert-triangle'
                );
            }

            DB::commit();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success'  => true,
                    'message'  => "Product '{$product->name}' created with {$validated['quantity']} " . ($unit ?? 'units') . " stock in {$store->name}!",
                    'product'  => $product,
                    'redirect' => route('products.index'),
                ]);
            }

            return redirect()
                ->route('products.index')
                ->with('success', "Product '{$product->name}' created successfully!");

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create product: ' . $e->getMessage(),
                ], 422);
            }

            return back()
                ->withErrors(['error' => 'Failed to create product: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $this->authorizeProduct($product);

        $product->load([
            'store:id,name,location',
            'category:id,name',
            'creator:id,full_name,role',
            'stocks',
        ]);

        $stock = Stock::where('product_id', $product->id)
            ->where('store_id', $product->store_id)
            ->first();

        return view('products.show', compact('product', 'stock'));
    }

    /**
     * Show the form for editing a product.
     */
    public function edit(Product $product)
    {
        $this->authorizeProduct($product);

        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isOwner() && !$user->isCashier()) {
            abort(403, 'Only Admin, Owner, or Cashier can edit products.');
        }

        $stores = Store::where('shop_id', $user->shop_id)
            ->where('is_active', true)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $categories = Category::where('store_id', $product->store_id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $stock = Stock::where('product_id', $product->id)
            ->where('store_id', $product->store_id)
            ->first();

        $selectedCategoryName = $product->category->name ?? null;

        // ✅ Case-insensitive config lookup
        $categoryConfig = $this->resolveCategoryConfig($selectedCategoryName);

        $categorySpecs = [];
        $categoryUnits = [];
        $categoryUnit  = null;

        if (!empty($categoryConfig)) {
            $categorySpecs = $categoryConfig['fields'] ?? [];
            $categoryUnits = $this->resolveUnitsFromConfig($selectedCategoryName);
            $categoryUnit  = $product->unit
                ?: ($categoryUnits[0] ?? null);
        }

        $categorySpecs = collect($categorySpecs)
            ->filter(fn($s) => is_array($s))
            ->values()
            ->toArray();

        return view('products.edit', compact(
            'product',
            'stores',
            'categories',
            'stock',
            'selectedCategoryName',
            'categorySpecs',
            'categoryUnits',
            'categoryUnit'
        ));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        $this->authorizeProduct($product);

        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isOwner() && !$user->isCashier()) {
            abort(403, 'Only Admin, Owner, or Cashier can update products.');
        }

        $validated = $request->validate([
            'store_id'      => ['required', 'exists:stores,id'],
            'category_id'   => ['required', 'exists:categories,id'],
            'name'          => ['required', 'string', 'max:150'],
            'sku'           => ['nullable', 'string', 'max:50', Rule::unique('products')->ignore($product->id)],
            'unit'          => ['nullable', 'string', 'max:20'],
            'size'          => ['nullable', 'numeric', 'min:0'],
            'cost_price'    => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'quantity'      => ['required', 'integer', 'min:0'],
            'min_quantity'  => ['nullable', 'integer', 'min:0'],
            'is_active'     => ['boolean'],
            'specs'         => ['nullable', 'array'],
        ]);

        $store = Store::where('shop_id', $user->shop_id)
            ->where('id', $validated['store_id'])
            ->firstOrFail();

        $category = Category::where('store_id', $store->id)
            ->where('id', $validated['category_id'])
            ->firstOrFail();

        $specs = collect($request->input('specs', []))
            ->filter(fn($v) => $v !== null && $v !== '')
            ->toArray();

        // ✅ Unit — tumia iliyotumwa, la sivyo default
        $unit = $validated['unit'] ?? null;
        if (!$unit) {
            $unit = $this->resolveDefaultUnitFromConfig($category->name);
        }

        // Thibitisha unit ni halali
        $allowedUnits = $this->resolveUnitsFromConfig($category->name);
        if ($unit && !empty($allowedUnits) && !in_array($unit, $allowedUnits, true)) {
            $unit = $allowedUnits[0] ?? null;
        }

        DB::beginTransaction();

        try {
            $oldValues = $product->only([
                'name', 'selling_price', 'cost_price',
                'store_id', 'category_id', 'is_active', 'specs', 'unit',
            ]);

            $product->update([
                'store_id'      => $store->id,
                'category_id'   => $category->id,
                'name'          => $validated['name'],
                'sku'           => $validated['sku'] ?? null,
                'unit'          => $unit,
                'size'          => $validated['size'] ?? null,
                'specs'         => $specs ?: null,
                'cost_price'    => $validated['cost_price'] ?? null,
                'selling_price' => $validated['selling_price'],
                'is_active'     => $request->boolean('is_active', true),
            ]);

            $stock = Stock::firstOrCreate(
                ['store_id' => $store->id, 'product_id' => $product->id],
                ['quantity' => 0, 'min_quantity' => 5]
            );

            $oldQuantity = (int) $stock->quantity;
            $newQuantity = (int) $validated['quantity'];

            $stock->update([
                'quantity'     => $newQuantity,
                'min_quantity' => $validated['min_quantity'] ?? 5,
            ]);

            if ($oldQuantity !== $newQuantity) {
                StockMovement::create([
                    'store_id'      => $store->id,
                    'product_id'    => $product->id,
                    'user_id'       => $user->id,
                    'movement_type' => 'adjustment',
                    'quantity'      => $newQuantity - $oldQuantity,
                    'reference'     => 'manual-edit',
                    'note'          => "Stock adjusted from {$oldQuantity} to {$newQuantity}",
                ]);
            }

            ActivityLog::log(
                $user->id, $user->shop_id, $store->id,
                'UPDATE_PRODUCT', 'products',
                "Updated product '{$product->name}' — stock: {$newQuantity}",
                $product->id, 'products',
                $oldValues,
                $product->only([
                    'name', 'selling_price', 'cost_price',
                    'store_id', 'category_id', 'is_active', 'specs', 'unit',
                ])
            );

            if ($newQuantity <= ($validated['min_quantity'] ?? 5)) {
                Notification::send(
                    $user->shop_id,
                    'low_stock',
                    'Low Stock Alert',
                    "{$product->name} has low stock ({$newQuantity} " . ($unit ?? 'units') . ") in {$store->name}",
                    route('products.show', $product),
                    'alert-triangle'
                );
            }

            DB::commit();

            return redirect()
                ->route('products.index')
                ->with('success', "Product '{$product->name}' updated successfully!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Failed to update: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {
        $this->authorizeProduct($product);

        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isOwner()) {
            abort(403, 'Only Admin or Owner can delete products.');
        }

        $name = $product->name;
        $product->delete();

        ActivityLog::log(
            $user->id, $user->shop_id, null,
            'DELETE_PRODUCT', 'products',
            "Deleted product: {$name}",
            null, 'products'
        );

        return redirect()
            ->route('products.index')
            ->with('success', "Product '{$name}' deleted successfully!");
    }

    /**
     * Download products as CSV.
     */
    public function downloadCsv(Request $request)
    {
        $query = Product::with([
            'store:id,name',
            'category:id,name',
            'creator:id,full_name,role',
        ]);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($storeId = $request->input('store')) {
            $query->where('store_id', $storeId);
        }

        if ($categoryId = $request->input('category')) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->orderBy('name')->get();

        $productIds = $products->pluck('id');
        $stocks = Stock::whereIn('product_id', $productIds)
            ->get()
            ->keyBy(function ($s) {
                return $s->store_id . '-' . $s->product_id;
            });

        $filename = 'products-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($products, $stocks) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'No.', 'Product Name', 'Store', 'Category', 'Unit',
                'Specs', 'Stock', 'Min Stock', 'Cost Price', 'Selling Price', 'Profit',
                'Status', 'Added By', 'Role', 'Date Added',
            ]);

            foreach ($products as $i => $p) {
                $profit = ($p->selling_price ?? 0) - ($p->cost_price ?? 0);
                $stockKey = $p->store_id . '-' . $p->id;
                $stock = $stocks->get($stockKey);

                $specsDisplay = '-';
                if (!empty($p->specs) && is_array($p->specs)) {
                    $pairs = [];
                    foreach ($p->specs as $k => $v) {
                        $pairs[] = str_replace('_', ' ', $k) . ': ' . $v;
                    }
                    $specsDisplay = implode(' | ', $pairs);
                }

                // ✅ Unit fallback
                $unit = $p->unit;
                if (!$unit && $p->category) {
                    $unit = $this->resolveDefaultUnitFromConfig($p->category->name);
                }

                fputcsv($file, [
                    $i + 1,
                    $p->name,
                    $p->store->name ?? '-',
                    $p->category->name ?? '-',
                    $unit ? strtoupper($unit) : '-',
                    $specsDisplay,
                    $stock->quantity ?? 0,
                    $stock->min_quantity ?? '-',
                    number_format($p->cost_price ?? 0, 2, '.', ''),
                    number_format($p->selling_price ?? 0, 2, '.', ''),
                    number_format($profit, 2, '.', ''),
                    $p->is_active ? 'Active' : 'Inactive',
                    $p->creator->full_name ?? 'Unknown',
                    $p->creator->role ?? '-',
                    $p->created_at ? $p->created_at->format('Y-m-d H:i') : '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Download products as a printable report.
     */
    public function downloadReport(Request $request)
    {
        $query = Product::with([
            'store:id,name',
            'category:id,name',
            'creator:id,full_name,role',
        ]);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($storeId = $request->input('store')) {
            $query->where('store_id', $storeId);
        }

        if ($categoryId = $request->input('category')) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->orderBy('name')->get();

        $productIds = $products->pluck('id');
        $stocks = Stock::whereIn('product_id', $productIds)
            ->get()
            ->keyBy(function ($s) {
                return $s->store_id . '-' . $s->product_id;
            });

        $grouped = $products->groupBy(function ($p) {
            return $p->store->name ?? 'Unassigned';
        })->sortKeys();

        $totals = [
            'count'         => $products->count(),
            'total_cost'    => $products->sum('cost_price'),
            'total_selling' => $products->sum('selling_price'),
            'total_profit'  => $products->sum(fn($p) => ($p->selling_price ?? 0) - ($p->cost_price ?? 0)),
            'avg_selling'   => $products->avg('selling_price') ?? 0,
        ];

        $shop = \App\Models\Shop::find(Auth::user()->shop_id);
        $generatedBy = Auth::user();
        $generatedAt = now();

        ActivityLog::log(
            $generatedBy->id, $generatedBy->shop_id, null,
            'DOWNLOAD_PRODUCTS', 'products',
            "Downloaded product report (" . $products->count() . " products)"
        );

        return view('products.report', compact(
            'grouped', 'totals', 'stocks', 'shop', 'generatedBy', 'generatedAt'
        ));
    }

    /**
     * Ensure product belongs to same shop as user.
     */
    private function authorizeProduct(Product $product): void
    {
        $user = Auth::user();

        if (!$product->store_id) {
            return;
        }

        $store = Store::withoutGlobalScopes()
            ->where('id', $product->store_id)
            ->first();

        if (!$store) {
            return;
        }

        if ((int) $store->shop_id !== (int) $user->shop_id) {
            abort(403, 'Unauthorized — product belongs to a different shop.');
        }
    }
}