<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $user   = Auth::user();
        $shopId = $user->shop_id;

        $storeIds = Store::where('shop_id', $shopId)->pluck('id')->toArray();

        // ============================================================
        // 1. BASE QUERY (with filters)
        // ============================================================
        $baseQuery = StockMovement::query()->whereIn('store_id', $storeIds);

        if ($storeId = $request->input('store')) {
            $baseQuery->where('store_id', $storeId);
        }
        if ($userId = $request->input('user')) {
            $baseQuery->where('user_id', $userId);
        }
        if ($search = $request->input('search')) {
            $baseQuery->whereHas('product', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        // Date range
        $from = $request->input('from', now()->subDays(29)->format('Y-m-d'));
        $to   = $request->input('to', now()->format('Y-m-d'));

        $baseQuery->whereDate('created_at', '>=', $from)
                  ->whereDate('created_at', '<=', $to);

        if ($user->isCashier()) {
            $baseQuery->where('user_id', $user->id);
        }

        // ============================================================
        // 2. KPI TOTALS
        // ============================================================
        $totalIn  = (clone $baseQuery)->where('movement_type', 'in')->sum('quantity');
        $totalOut = (clone $baseQuery)->where('movement_type', 'sale')->sum('quantity');

        $totals = [
            'count' => (clone $baseQuery)->count(),
            'in'    => (int) $totalIn,
            'out'   => (int) abs($totalOut),
        ];

        // ============================================================
        // 3. DAY-BY-DAY CHART DATA
        // ============================================================
        $dailyIn = (clone $baseQuery)
            ->where('movement_type', 'in')
            ->selectRaw('DATE(created_at) as day, SUM(quantity) as total')
            ->groupBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $dailyOut = (clone $baseQuery)
            ->where('movement_type', 'sale')
            ->selectRaw('DATE(created_at) as day, ABS(SUM(quantity)) as total')
            ->groupBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $chartLabels = [];
        $chartIn     = [];
        $chartOut    = [];

        $cursor = \Carbon\Carbon::parse($from)->startOfDay();
        $end    = \Carbon\Carbon::parse($to)->startOfDay();

        while ($cursor->lte($end)) {
            $key = $cursor->toDateString();
            $chartLabels[] = $cursor->format('d M');
            $chartIn[]     = (int) ($dailyIn[$key]  ?? 0);
            $chartOut[]    = (int) ($dailyOut[$key] ?? 0);
            $cursor->addDay();
        }

        // ============================================================
        // 4. TOP MOVING PRODUCTS (as objects)
        // ============================================================
        $topProducts = StockMovement::query()
            ->whereIn('store_id', $storeIds)
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->when($user->isCashier(), fn($q) => $q->where('user_id', $user->id))
            ->when($request->input('store'), fn($q, $sid) => $q->where('store_id', $sid))
            ->select(
                'product_id',
                DB::raw('SUM(CASE WHEN movement_type = "in" THEN quantity ELSE 0 END) as in_qty'),
                DB::raw('SUM(CASE WHEN movement_type = "sale" THEN ABS(quantity) ELSE 0 END) as out_qty'),
                DB::raw('SUM(ABS(quantity)) as total_qty')
            )
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                $p = Product::find($row->product_id);
                return (object) [
                    'name'      => $p->name ?? 'Unknown',
                    'in_qty'    => (int) $row->in_qty,
                    'out_qty'   => (int) $row->out_qty,
                    'total_qty' => (int) $row->total_qty,
                ];
            });

        // ============================================================
        // 5. MOVEMENT BY STORE (as objects)
        // ============================================================
        $byStore = StockMovement::query()
            ->whereIn('store_id', $storeIds)
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->when($user->isCashier(), fn($q) => $q->where('user_id', $user->id))
            ->select(
                'store_id',
                DB::raw('SUM(CASE WHEN movement_type = "in" THEN quantity ELSE 0 END) as in_qty'),
                DB::raw('SUM(CASE WHEN movement_type = "sale" THEN ABS(quantity) ELSE 0 END) as out_qty'),
                DB::raw('SUM(ABS(quantity)) as total_qty')
            )
            ->groupBy('store_id')
            ->orderByDesc('total_qty')
            ->get()
            ->map(function ($row) {
                $s = Store::find($row->store_id);
                return (object) [
                    'name'      => $s->name ?? 'Unknown',
                    'in_qty'    => (int) $row->in_qty,
                    'out_qty'   => (int) $row->out_qty,
                    'total_qty' => (int) $row->total_qty,
                ];
            });

        // ============================================================
        // 6. MOVEMENTS LIST (paginated)
        // ============================================================
        $movements = (clone $baseQuery)->with([
                'store:id,name',
                'product:id,name,unit,size',
                'user:id,full_name,role',
            ])
            ->orderByDesc('created_at')
            ->paginate(30);

        // ============================================================
        // 7. STOCK OVERVIEW (baki, low, out)
        // ============================================================
        $stockBase = Stock::query()
            ->with([
                'product:id,name,unit,size,category_id',
                'product.category:id,name',
                'store:id,name',
            ])
            ->whereIn('store_id', $storeIds);

        if ($storeId = $request->input('store')) {
            $stockBase->where('store_id', $storeId);
        }

        $allStock   = (clone $stockBase)->orderBy('quantity', 'asc')->get();
        $lowStock   = (clone $stockBase)
            ->where('quantity', '>', 0)
            ->whereColumn('quantity', '<=', 'min_quantity')
            ->orderBy('quantity', 'asc')
            ->get();
        $outOfStock = (clone $stockBase)
            ->where('quantity', '<=', 0)
            ->get();

        $stockStats = [
            'total_products' => $allStock->count(),
            'total_units'    => $allStock->sum('quantity'),
            'low_count'      => $lowStock->count(),
            'out_count'      => $outOfStock->count(),
            'healthy_count'  => max(0, $allStock->count() - $lowStock->count() - $outOfStock->count()),
        ];

        // ============================================================
        // 8. DROPDOWNS
        // ============================================================
        $stores = Store::where('shop_id', $shopId)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $users = ($user->isAdmin() || $user->isOwner())
            ? \App\Models\User::where('shop_id', $shopId)
                ->select('id', 'full_name', 'role')
                ->orderBy('full_name')
                ->get()
            : collect();

        return view('stock-movements.index', compact(
            'movements',
            'stores',
            'users',
            'totals',
            'chartLabels',
            'chartIn',
            'chartOut',
            'topProducts',
            'byStore',
            'allStock',
            'lowStock',
            'outOfStock',
            'stockStats'
        ));
    }
}