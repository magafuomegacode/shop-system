<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Setting;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PosController extends Controller
{
    /**
     * Show the POS screen.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $shopId = $user->shop_id;

        $stores = Store::where('shop_id', $shopId)
            ->where('is_active', true)
            ->select('id', 'name')
            ->get();

        $selectedStoreId = $request->input('store_id');

        if (!$selectedStoreId && $stores->count() === 1) {
            $selectedStoreId = $stores->first()->id;
        }

        $selectedStore = null;
        if ($selectedStoreId) {
            $selectedStore = Store::where('shop_id', $shopId)
                ->where('id', $selectedStoreId)
                ->first();
        }

        $maxDiscount = (float) Setting::get($shopId, 'discount_max_percent', 20);
        $discountAllowed = Setting::get($shopId, 'discount_allowed', '1') === '1';

        return view('pos.index', compact(
            'stores',
            'selectedStore',
            'selectedStoreId',
            'maxDiscount',
            'discountAllowed'
        ));
    }

    /**
     * Search products in a specific store.
     */
    public function searchProducts(Request $request)
    {
        $user = Auth::user();
        $shopId = $user->shop_id;

        $request->validate([
            'store_id' => ['required', 'exists:stores,id'],
            'search'   => ['nullable', 'string', 'max:100'],
        ]);

        $store = Store::where('shop_id', $shopId)
            ->where('id', $request->store_id)
            ->firstOrFail();

        $query = Product::select('id', 'name', 'sku', 'unit', 'selling_price', 'is_active')
            ->where('is_active', true)
            ->where('store_id', $store->id);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('name')->limit(20)->get();

        $productIds = $products->pluck('id');
        $stocks = Stock::where('store_id', $store->id)
            ->whereIn('product_id', $productIds)
            ->pluck('quantity', 'product_id');

        $products = $products->map(function ($p) use ($stocks) {
            return [
                'id'             => $p->id,
                'name'           => $p->name,
                'sku'            => $p->sku,
                'unit'           => $p->unit,
                'selling_price'  => (float) $p->selling_price,
                'stock'          => (int) ($stocks[$p->id] ?? 0),
            ];
        });

        return response()->json($products);
    }

    /**
     * Complete a sale.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $shopId = $user->shop_id;

        $validated = $request->validate([
            'store_id'        => ['required', 'exists:stores,id'],
            'items'           => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity'   => ['required', 'integer', 'min:1'],
            'discount_type'   => ['nullable', 'in:none,percent,amount'],
            'discount_value'  => ['nullable', 'numeric', 'min:0'],
            'payment_method'  => ['required', 'in:cash,mobile,card'],
            'customer_name'   => ['nullable', 'string', 'max:100'],
        ]);

        $store = Store::where('shop_id', $shopId)
            ->where('id', $validated['store_id'])
            ->firstOrFail();

        $maxDiscount = (float) Setting::get($shopId, 'discount_max_percent', 20);
        $discountAllowed = Setting::get($shopId, 'discount_allowed', '1') === '1';

        $discountType = $validated['discount_type'] ?? 'none';
        $discountValue = (float) ($validated['discount_value'] ?? 0);

        if (!$discountAllowed && $discountType !== 'none') {
            return response()->json([
                'success' => false,
                'error'   => 'Discounts are not allowed.',
            ], 422);
        }

        if ($discountType === 'percent' && $discountValue > $maxDiscount) {
            return response()->json([
                'success' => false,
                'error'   => "Discount cannot exceed {$maxDiscount}%.",
            ], 422);
        }

        try {
            DB::beginTransaction();

            $subtotal = 0;
            $lineItems = [];

            $productIds = collect($validated['items'])->pluck('product_id')->unique();
            $products = Product::whereIn('id', $productIds)
                ->get()
                ->keyBy('id');

            // ===== FIX: Ensure stock records exist for all products =====
            foreach ($productIds as $pid) {
                Stock::firstOrCreate(
                    ['store_id' => $store->id, 'product_id' => $pid],
                    ['quantity' => 0, 'min_quantity' => 5]
                );
            }

            $storeStocks = Stock::where('store_id', $store->id)
                ->whereIn('product_id', $productIds)
                ->get()
                ->keyBy('product_id');

            foreach ($validated['items'] as $item) {
                $product = $products->get($item['product_id']);
                if (!$product) {
                    throw new \Exception("Product not found (ID: {$item['product_id']}).");
                }

                $stock = $storeStocks->get($item['product_id']);
                $availableQty = $stock ? (int) $stock->quantity : 0;

                if ($availableQty < $item['quantity']) {
                    throw new \Exception("Insufficient stock for '{$product->name}'. Available: {$availableQty}");
                }

                $unitPrice = (float) $product->selling_price;
                $costPrice = (float) ($product->cost_price ?? 0);
                $lineTotal = $unitPrice * $item['quantity'];

                $lineItems[] = [
                    'product_id' => $product->id,
                    'quantity'   => (int) $item['quantity'],
                    'unit_price' => $unitPrice,
                    'cost_price' => $costPrice,
                    'line_total' => $lineTotal,
                    'name'       => $product->name,
                ];

                $subtotal += $lineTotal;
            }

            $discountAmount = 0;
            if ($discountType === 'percent') {
                $discountAmount = $subtotal * ($discountValue / 100);
            } elseif ($discountType === 'amount') {
                $discountAmount = min($discountValue, $subtotal);
            }

            $total = $subtotal - $discountAmount;

            $invoiceNo = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(4));

            // ===== Create Sale =====
            $sale = Sale::create([
                'invoice_no'      => $invoiceNo,
                'store_id'        => $store->id,
                'cashier_id'      => $user->id,
                'subtotal'        => $subtotal,
                'discount_type'   => $discountType,
                'discount_value'  => $discountValue,
                'discount_amount' => $discountAmount,
                'total'           => $total,
                'payment_method'  => $validated['payment_method'],
                'customer_name'   => $validated['customer_name'] ?? null,
            ]);

            // ===== Create Sale Items + Update Stock + Log Movements =====
            foreach ($lineItems as $li) {
                SaleItem::create([
                    'sale_id'     => $sale->id,
                    'product_id'  => $li['product_id'],
                    'quantity'    => $li['quantity'],
                    'unit_price'  => $li['unit_price'],
                    'cost_price'  => $li['cost_price'],
                    'line_total'  => $li['line_total'],
                ]);

                Stock::where('store_id', $store->id)
                    ->where('product_id', $li['product_id'])
                    ->decrement('quantity', $li['quantity']);

                StockMovement::create([
                    'store_id'      => $store->id,
                    'product_id'    => $li['product_id'],
                    'user_id'       => $user->id,
                    'movement_type' => 'sale',
                    'quantity'      => -$li['quantity'],
                    'reference'     => $invoiceNo,
                ]);
            }

            // ===== Activity Log (wrapped — won't break sale if it fails) =====
            try {
                ActivityLog::log(
                    $user->id, $shopId, $store->id,
                    'SALE', 'sales',
                    "Sale {$invoiceNo} - TSh " . number_format($total, 0) . " (" . count($lineItems) . " items)",
                    $sale->id, 'sales',
                    null,
                    [
                        'invoice'    => $invoiceNo,
                        'subtotal'   => $subtotal,
                        'discount'   => $discountAmount,
                        'total'      => $total,
                        'items'      => count($lineItems),
                    ]
                );
            } catch (\Exception $e) {
                \Log::warning('ActivityLog failed: ' . $e->getMessage());
            }

            // ===== Notification: Sale Made (wrapped) — ✅ FIXED: send() not push() =====
            try {
                Notification::send(
                    $shopId,
                    'sale_made',
                    'New Sale',
                    "Sale {$invoiceNo} — TSh " . number_format($total, 0) . " by {$user->full_name}",
                    route('sales.show', $sale->id),
                    'shopping-cart'
                );
            } catch (\Exception $e) {
                \Log::warning('Sale notification failed: ' . $e->getMessage());
            }

            // ===== Notification: Low Stock Alerts (wrapped) — ✅ FIXED: send() not push() =====
            try {
                foreach ($lineItems as $li) {
                    $stock = Stock::where('store_id', $store->id)
                        ->where('product_id', $li['product_id'])
                        ->first();

                    if ($stock && $stock->quantity <= $stock->min_quantity) {
                        Notification::send(
                            $shopId,
                            'low_stock',
                            'Low Stock Alert',
                            "{$li['name']} is running low ({$stock->quantity} left) in {$store->name}",
                            route('products.show', $li['product_id']),
                            'alert-triangle'
                        );
                    }
                }
            } catch (\Exception $e) {
                \Log::warning('Low stock notification failed: ' . $e->getMessage());
            }

            DB::commit();

            return response()->json([
                'success'    => true,
                'sale_id'    => $sale->id,
                'invoice_no' => $invoiceNo,
                'total'      => $total,
                'receipt_url' => route('pos.receipt', $sale->id),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            // Log full error for debugging
            \Log::error('POS Sale failed: ' . $e->getMessage(), [
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ], 422);
        }
    }

    /**
     * Show a printable receipt.
     */
    public function receipt(Sale $sale)
    {
        $user = Auth::user();

        if (!$sale->store || $sale->store->shop_id !== $user->shop_id) {
            abort(403);
        }

        $sale->load(['items.product', 'store', 'cashier']);

        $shop = \App\Models\Shop::find($user->shop_id);
        $currency = Setting::get($user->shop_id, 'currency', 'TSh');
        $receiptHeader = Setting::get($user->shop_id, 'receipt_header', 'Asante kwa kununua!');
        $receiptFooter = Setting::get($user->shop_id, 'receipt_footer', '');

        return view('pos.receipt', compact(
            'sale', 'shop', 'currency', 'receiptHeader', 'receiptFooter'
        ));
    }
}