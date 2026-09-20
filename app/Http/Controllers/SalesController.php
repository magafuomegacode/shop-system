<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    /**
     * Display a listing of sales.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $shopId = $user->shop_id;

        $query = Sale::with(['store:id,name', 'cashier:id,full_name,role'])
            ->whereHas('store', function ($q) use ($shopId) {
                $q->where('shop_id', $shopId);
            });

        // Search by invoice
        if ($search = $request->input('search')) {
            $query->where('invoice_no', 'like', "%{$search}%");
        }

        // Filter by store
        if ($storeId = $request->input('store')) {
            $query->where('store_id', $storeId);
        }

        // Filter by date range
        if ($from = $request->input('from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        // Filter by payment method
        if ($payment = $request->input('payment')) {
            $query->where('payment_method', $payment);
        }

        // Cashier only sees own sales
        if ($user->isCashier()) {
            $query->where('cashier_id', $user->id);
        }

        // Clone query kwa totals kabla ya pagination
        $totalsQuery = clone $query;

        $sales = $query->orderByDesc('created_at')->paginate(20);

        $stores = Store::where('shop_id', $shopId)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        // Totals
        $totals = [
            'count' => $sales->total(),
            'sum'   => $totalsQuery->sum('total'),
            'today' => Sale::whereHas('store', function ($q) use ($shopId) {
                            $q->where('shop_id', $shopId);
                        })
                        ->when($user->isCashier(), function ($q) use ($user) {
                            $q->where('cashier_id', $user->id);
                        })
                        ->whereDate('created_at', today())
                        ->sum('total'),
            'month' => Sale::whereHas('store', function ($q) use ($shopId) {
                            $q->where('shop_id', $shopId);
                        })
                        ->when($user->isCashier(), function ($q) use ($user) {
                            $q->where('cashier_id', $user->id);
                        })
                        ->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->sum('total'),
        ];

        return view('sales.index', compact('sales', 'stores', 'totals'));
    }

    /**
     * Display the specified sale.
     */
    public function show(Sale $sale)
    {
        $user = Auth::user();

        // Security: only same shop
        if (!$sale->store || $sale->store->shop_id !== $user->shop_id) {
            abort(403, 'Unauthorized.');
        }

        // Cashier: only own sales
        if ($user->isCashier() && $sale->cashier_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $sale->load(['items.product', 'store', 'cashier']);

        return view('sales.show', compact('sale'));
    }

    /**
     * Print sales report (printable HTML → Save as PDF).
     */
    public function print(Request $request)
    {
        $user = Auth::user();
        $shopId = $user->shop_id;

        $query = Sale::with(['store:id,name', 'cashier:id,full_name,role'])
            ->whereHas('store', function ($q) use ($shopId) {
                $q->where('shop_id', $shopId);
            });

        // Same filters as index
        if ($search = $request->input('search')) {
            $query->where('invoice_no', 'like', "%{$search}%");
        }

        if ($storeId = $request->input('store')) {
            $query->where('store_id', $storeId);
        }

        if ($from = $request->input('from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        if ($payment = $request->input('payment')) {
            $query->where('payment_method', $payment);
        }

        // Cashier: only own sales
        if ($user->isCashier()) {
            $query->where('cashier_id', $user->id);
        }

        $sales = $query->orderByDesc('created_at')->get();

        // Totals
        $totals = [
            'count'          => $sales->count(),
            'sum'            => $sales->sum('total'),
            'total_discount' => $sales->sum('discount_amount'),
            'total_subtotal' => $sales->sum('subtotal'),
        ];

        // Shop info
        $shop        = \App\Models\Shop::find($shopId);
        $generatedBy = $user;
        $generatedAt = now();

        // Filter info kwa header
        $filterStore = null;
        if ($storeId = $request->input('store')) {
            $filterStore = Store::find($storeId);
        }

        return view('sales.print', compact(
            'sales',
            'totals',
            'shop',
            'generatedBy',
            'generatedAt',
            'filterStore'
        ));
    }

    /**
     * Download sales as CSV.
     */
    public function downloadCsv(Request $request)
    {
        $user = Auth::user();
        $shopId = $user->shop_id;

        $query = Sale::with(['store:id,name', 'cashier:id,full_name,role'])
            ->whereHas('store', function ($q) use ($shopId) {
                $q->where('shop_id', $shopId);
            });

        if ($search = $request->input('search')) {
            $query->where('invoice_no', 'like', "%{$search}%");
        }

        if ($storeId = $request->input('store')) {
            $query->where('store_id', $storeId);
        }

        if ($from = $request->input('from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        if ($user->isCashier()) {
            $query->where('cashier_id', $user->id);
        }

        $sales = $query->orderByDesc('created_at')->get();

        $filename = 'sales-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($sales) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'No.',
                'Invoice',
                'Date',
                'Store',
                'Cashier',
                'Subtotal',
                'Discount',
                'Total',
                'Payment',
                'Customer',
            ]);

            foreach ($sales as $i => $s) {
                fputcsv($file, [
                    $i + 1,
                    $s->invoice_no,
                    $s->created_at ? $s->created_at->format('Y-m-d H:i') : '-',
                    $s->store->name ?? '-',
                    $s->cashier->full_name ?? '-',
                    number_format((float) $s->subtotal, 2, '.', ''),
                    number_format((float) $s->discount_amount, 2, '.', ''),
                    number_format((float) $s->total, 2, '.', ''),
                    strtoupper($s->payment_method),
                    $s->customer_name ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}