<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
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
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $shopId = $user->shop_id;

        $query = Product::query()
            ->with([
                'store:id,name',
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

        $products = $query->orderByDesc('created_at')->paginate(15);

        $stores = Store::where('shop_id', $shopId)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('products.index', compact('products', 'stores'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $user = Auth::user();

        $stores = Store::where('shop_id', $user->shop_id)
            ->where('is_active', true)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('products.create', compact('stores'));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'store_id'      => ['required', 'exists:stores,id'],
            'name'          => ['required', 'string', 'max:150'],
            'sku'           => ['nullable', 'string', 'max:50', 'unique:products,sku'],
            'unit'          => ['required', 'string', 'max:20'],
            'cost_price'    => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'quantity'      => ['required', 'integer', 'min:0'],
            'min_quantity'  => ['nullable', 'integer', 'min:0'],
            'is_active'     => ['boolean'],
        ]);

        $store = Store::where('shop_id', $user->shop_id)
            ->where('id', $validated['store_id'])
            ->firstOrFail();

        DB::beginTransaction();

        try {
            // 1. Create product
            $product = Product::create([
                'store_id'      => $store->id,
                'name'          => $validated['name'],
                'sku'           => $validated['sku'] ?? null,
                'unit'          => $validated['unit'],
                'cost_price'    => $validated['cost_price'] ?? null,
                'selling_price' => $validated['selling_price'],
                'is_active'     => $request->boolean('is_active', true),
                'created_by'    => $user->id,
            ]);

            // 2. Create stock record
            Stock::create([
                'store_id'     => $store->id,
                'product_id'   => $product->id,
                'quantity'     => $validated['quantity'],
                'min_quantity' => $validated['min_quantity'] ?? 5,
            ]);

            // 3. Log stock movement
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

            // 4. Activity log
            ActivityLog::log(
                $user->id, $user->shop_id, $store->id,
                'CREATE_PRODUCT', 'products',
                "Created product '{$product->name}' in '{$store->name}' with {$validated['quantity']} stock",
                $product->id, 'products',
                null,
                ['name' => $product->name, 'store' => $store->name, 'quantity' => $validated['quantity']]
            );

            // 5. Notification: Product Added — ✅ FIXED: send() not push()
            Notification::send(
                $user->shop_id,
                'product_added',
                'New Product Added',
                "{$product->name} was added to {$store->name} with {$validated['quantity']} {$validated['unit']} stock",
                route('products.show', $product),
                'plus-circle'
            );

            // 6. Notification: Low stock (kama initial quantity ni ndogo) — ✅ FIXED: send() not push()
            if ($validated['quantity'] <= ($validated['min_quantity'] ?? 5)) {
                Notification::send(
                    $user->shop_id,
                    'low_stock',
                    'Low Stock Alert',
                    "{$product->name} has low stock ({$validated['quantity']} {$validated['unit']}) in {$store->name}",
                    route('products.show', $product),
                    'alert-triangle'
                );
            }

            DB::commit();

            return redirect()
                ->route('products.index')
                ->with('success', "Product '{$product->name}' created with {$validated['quantity']} {$validated['unit']} stock in {$store->name}!");

        } catch (\Exception $e) {
            DB::rollBack();
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

        $product->load(['store:id,name,location', 'creator:id,full_name,role']);

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

        // ✅ Allow Admin, Owner, and Cashier
        if (!$user->isAdmin() && !$user->isOwner() && !$user->isCashier()) {
            abort(403, 'Only Admin, Owner, or Cashier can edit products.');
        }

        $stores = Store::where('shop_id', $user->shop_id)
            ->where('is_active', true)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $stock = Stock::where('product_id', $product->id)
            ->where('store_id', $product->store_id)
            ->first();

        return view('products.edit', compact('product', 'stores', 'stock'));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        $this->authorizeProduct($product);

        $user = Auth::user();

        // ✅ Allow Admin, Owner, and Cashier
        if (!$user->isAdmin() && !$user->isOwner() && !$user->isCashier()) {
            abort(403, 'Only Admin, Owner, or Cashier can update products.');
        }

        $validated = $request->validate([
            'store_id'      => ['required', 'exists:stores,id'],
            'name'          => ['required', 'string', 'max:150'],
            'sku'           => ['nullable', 'string', 'max:50', Rule::unique('products')->ignore($product->id)],
            'unit'          => ['required', 'string', 'max:20'],
            'cost_price'    => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'quantity'      => ['required', 'integer', 'min:0'],
            'min_quantity'  => ['nullable', 'integer', 'min:0'],
            'is_active'     => ['boolean'],
        ]);

        $store = Store::where('shop_id', $user->shop_id)
            ->where('id', $validated['store_id'])
            ->firstOrFail();

        DB::beginTransaction();

        try {
            $oldValues = $product->only(['name', 'selling_price', 'cost_price', 'store_id', 'is_active']);

            // 1. Update product
            $product->update([
                'store_id'      => $store->id,
                'name'          => $validated['name'],
                'sku'           => $validated['sku'] ?? null,
                'unit'          => $validated['unit'],
                'cost_price'    => $validated['cost_price'] ?? null,
                'selling_price' => $validated['selling_price'],
                'is_active'     => $request->boolean('is_active', true),
            ]);

            // 2. Update or create stock
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

            // 3. Log stock movement
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

            // 4. Activity log
            ActivityLog::log(
                $user->id, $user->shop_id, $store->id,
                'UPDATE_PRODUCT', 'products',
                "Updated product '{$product->name}' — stock: {$newQuantity}",
                $product->id, 'products',
                $oldValues,
                $product->only(['name', 'selling_price', 'cost_price', 'store_id', 'is_active'])
            );

            // 5. Notification: Low stock (kama stock mpya ni ndogo) — ✅ FIXED: send() not push()
            if ($newQuantity <= ($validated['min_quantity'] ?? 5)) {
                Notification::send(
                    $user->shop_id,
                    'low_stock',
                    'Low Stock Alert',
                    "{$product->name} has low stock ({$newQuantity} {$product->unit}) in {$store->name}",
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

        // 🔒 Delete stays restricted to Admin / Owner only
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
                'No.', 'Product Name', 'SKU', 'Store', 'Unit',
                'Stock', 'Min Stock', 'Cost Price', 'Selling Price', 'Profit',
                'Status', 'Added By', 'Role', 'Date Added',
            ]);

            foreach ($products as $i => $p) {
                $profit = ($p->selling_price ?? 0) - ($p->cost_price ?? 0);
                $stockKey = $p->store_id . '-' . $p->id;
                $stock = $stocks->get($stockKey);

                fputcsv($file, [
                    $i + 1,
                    $p->name,
                    $p->sku ?? '-',
                    $p->store->name ?? '-',
                    $p->unit ?? '-',
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