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
        if ($user->isCashier()) {
            $query->where('cashier_id', $user->id);
        }

        $totalsQuery = clone $query;
        $sales = $query->orderByDesc('created_at')->paginate(20);

        $stores = Store::where('shop_id', $shopId)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $totals = [
            'count' => $sales->total(),
            'sum'   => $totalsQuery->sum('total'),
            'today' => Sale::whereHas('store', fn($q) => $q->where('shop_id', $shopId))
                        ->when($user->isCashier(), fn($q) => $q->where('cashier_id', $user->id))
                        ->whereDate('created_at', today())
                        ->sum('total'),
            'month' => Sale::whereHas('store', fn($q) => $q->where('shop_id', $shopId))
                        ->when($user->isCashier(), fn($q) => $q->where('cashier_id', $user->id))
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
        if (!$sale->store || $sale->store->shop_id !== $user->shop_id) {
            abort(403, 'Unauthorized.');
        }
        if ($user->isCashier() && $sale->cashier_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }
        $sale->load(['items.product', 'store', 'cashier']);
        return view('sales.show', compact('sale'));
    }

    /**
     * Print sales report.
     */
    public function print(Request $request)
    {
        $user = Auth::user();
        $shopId = $user->shop_id;

        $query = Sale::with(['store:id,name', 'cashier:id,full_name,role'])
            ->whereHas('store', fn($q) => $q->where('shop_id', $shopId));

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
        if ($user->isCashier()) {
            $query->where('cashier_id', $user->id);
        }

        $sales = $query->orderByDesc('created_at')->get();

        $totals = [
            'count'          => $sales->count(),
            'sum'            => $sales->sum('total'),
            'total_discount' => $sales->sum('discount_amount'),
            'total_subtotal' => $sales->sum('subtotal'),
        ];

        $shop        = \App\Models\Shop::find($shopId);
        $generatedBy = $user;
        $generatedAt = now();

        $filterStore = null;
        if ($storeId = $request->input('store')) {
            $filterStore = Store::find($storeId);
        }

        return view('sales.print', compact(
            'sales', 'totals', 'shop', 'generatedBy', 'generatedAt', 'filterStore'
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
            ->whereHas('store', fn($q) => $q->where('shop_id', $shopId));

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
            fputcsv($file, ['No.', 'Invoice', 'Date', 'Store', 'Cashier', 'Subtotal', 'Discount', 'Total', 'Payment', 'Customer']);
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

    /**
     * Day-by-day JSON (kept for charts).
     */
    public function dailyChart(Request $request)
    {
        $user   = Auth::user();
        $shopId = $user->shop_id;

        $query = Sale::query()
            ->whereHas('store', fn($q) => $q->where('shop_id', $shopId));

        if ($search = $request->input('search')) {
            $query->where('invoice_no', 'like', "%{$search}%");
        }
        if ($storeId = $request->input('store')) {
            $query->where('store_id', $storeId);
        }

        $from = $request->input('from');
        $to   = $request->input('to');

        if ($from) $query->whereDate('created_at', '>=', $from);
        if ($to)   $query->whereDate('created_at', '<=', $to);

        if (!$from && !$to) {
            $query->whereDate('created_at', '>=', now()->subDays(29)->toDateString());
        }

        if ($user->isCashier()) {
            $query->where('cashier_id', $user->id);
        }

        $rows = $query
            ->selectRaw('DATE(created_at) as day, SUM(total) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $labels = [];
        $values = [];

        if ($from || $to) {
            $startDate = $from ? \Carbon\Carbon::parse($from) : $rows->min('day');
            $endDate   = $to   ? \Carbon\Carbon::parse($to)   : now();

            if ($startDate) {
                $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
                $endDate   = \Carbon\Carbon::parse($endDate)->startOfDay();
            }

            $map = [];
            foreach ($rows as $r) $map[$r->day] = (float) $r->total;

            $cursor = $startDate->copy();
            while ($cursor->lte($endDate)) {
                $key = $cursor->toDateString();
                $labels[] = $cursor->format('d M');
                $values[] = $map[$key] ?? 0;
                $cursor->addDay();
            }
        } else {
            foreach ($rows as $r) {
                $labels[] = \Carbon\Carbon::parse($r->day)->format('d M');
                $values[] = (float) $r->total;
            }
        }

        if ($from && $to) {
            $rangeText = \Carbon\Carbon::parse($from)->format('d M') . ' – ' . \Carbon\Carbon::parse($to)->format('d M');
        } elseif ($from) {
            $rangeText = 'From ' . \Carbon\Carbon::parse($from)->format('d M');
        } elseif ($to) {
            $rangeText = 'Up to ' . \Carbon\Carbon::parse($to)->format('d M');
        } else {
            $rangeText = 'Last 30 days';
        }

        $storeName = $request->filled('store')
            ? optional(Store::find($request->store))->name
            : null;

        return response()->json([
            'labels' => $labels,
            'values' => $values,
            'meta'   => [
                'range_text' => $rangeText,
                'store_name' => $storeName,
                'count'      => count($labels),
            ],
        ]);
    }

    /**
     * Business Analytics — full page dashboard WITH real calendar.
     */
    public function analytics(Request $request)
    {
        $user   = Auth::user();
        $shopId = $user->shop_id;

        // Shared filter base
        $baseQuery = function () use ($shopId, $user) {
            $q = Sale::query()
                ->whereHas('store', fn($qq) => $qq->where('shop_id', $shopId));
            if ($user->isCashier()) {
                $q->where('cashier_id', $user->id);
            }
            return $q;
        };

        $applyFilters = function ($q) use ($request) {
            if ($s = $request->input('search')) {
                $q->where('invoice_no', 'like', "%{$s}%");
            }
            if ($sid = $request->input('store')) {
                $q->where('store_id', $sid);
            }
            return $q;
        };

        $from = $request->input('from');
        $to   = $request->input('to');

        if (!$from && !$to) {
            $from = now()->subDays(29)->toDateString();
            $to   = now()->toDateString();
        }

        $fromCarbon = \Carbon\Carbon::parse($from)->startOfDay();
        $toCarbon   = \Carbon\Carbon::parse($to)->endOfDay();

        $periodDays = $fromCarbon->diffInDays($toCarbon) + 1;
        $prevFrom   = $fromCarbon->copy()->subDays($periodDays);
        $prevTo     = $fromCarbon->copy()->subSecond();

        $currentQuery = $applyFilters($baseQuery());
        $currentQuery->whereBetween('created_at', [$fromCarbon, $toCarbon]);

        $kpis = [
            'revenue'  => (float) (clone $currentQuery)->sum('total'),
            'count'    => (int)   (clone $currentQuery)->count(),
            'discount' => (float) (clone $currentQuery)->sum('discount_amount'),
            'subtotal' => (float) (clone $currentQuery)->sum('subtotal'),
        ];
        $kpis['avg_sale'] = $kpis['count'] > 0 ? $kpis['revenue'] / $kpis['count'] : 0;

        $prevQuery = $applyFilters($baseQuery());
        $prevQuery->whereBetween('created_at', [$prevFrom, $prevTo]);

        $prevKpis = [
            'revenue' => (float) (clone $prevQuery)->sum('total'),
            'count'   => (int)   (clone $prevQuery)->count(),
        ];
        $prevKpis['avg_sale'] = $prevKpis['count'] > 0 ? $prevKpis['revenue'] / $prevKpis['count'] : 0;

        $pct = function ($curr, $prev) {
            if ($prev == 0) return $curr > 0 ? 100 : 0;
            return (($curr - $prev) / $prev) * 100;
        };

        $compare = [
            'revenue_prev' => $prevKpis['revenue'],
            'count_prev'   => $prevKpis['count'],
            'avg_prev'     => $prevKpis['avg_sale'],
            'revenue_pct'  => $pct($kpis['revenue'], $prevKpis['revenue']),
            'count_pct'    => $pct($kpis['count'], $prevKpis['count']),
            'avg_pct'      => $pct($kpis['avg_sale'], $prevKpis['avg_sale']),
        ];

        $chartQuery = $applyFilters($baseQuery());
        $chartQuery->whereBetween('created_at', [$fromCarbon, $toCarbon]);

        $dailyRows = $chartQuery
            ->selectRaw('DATE(created_at) as day, SUM(total) as total, COUNT(*) as cnt')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $dayMap = [];
        foreach ($dailyRows as $r) {
            $dayMap[$r->day] = ['total' => (float) $r->total, 'cnt' => (int) $r->cnt];
        }

        $labels = [];
        $values = [];
        $counts = [];

        $cursor = $fromCarbon->copy();
        while ($cursor->lte($toCarbon)) {
            $key = $cursor->toDateString();
            $labels[] = $cursor->format('d M');
            $values[] = $dayMap[$key]['total'] ?? 0;
            $counts[] = $dayMap[$key]['cnt']   ?? 0;
            $cursor->addDay();
        }

        // Calendar grid
        $calendar = ['months' => []];
        $startMonth = $fromCarbon->copy()->startOfMonth();
        $endMonth   = $toCarbon->copy()->startOfMonth();
        $monthCursor = $startMonth->copy();

        while ($monthCursor->lte($endMonth)) {
            $monthStart = $monthCursor->copy()->startOfMonth();
            $monthEnd   = $monthCursor->copy()->endOfMonth();

            $monthQuery = $applyFilters($baseQuery());
            $monthQuery->whereBetween('created_at', [
                $monthStart->copy()->max($fromCarbon),
                $monthEnd->copy()->min($toCarbon),
            ]);

            $monthDays = $monthQuery
                ->selectRaw('DATE(created_at) as day, SUM(total) as total, COUNT(*) as cnt')
                ->groupBy('day')
                ->orderBy('day')
                ->get()
                ->keyBy('day');

            $firstDayOfWeek = $monthStart->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
            $lastDayOfWeek  = $monthEnd->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);

            $weeks = [];
            $week  = [];
            $dayCursor = $firstDayOfWeek->copy();

            while ($dayCursor->lte($lastDayOfWeek)) {
                $key = $dayCursor->toDateString();
                $inMonth = $dayCursor->month === $monthCursor->month
                        && $dayCursor->year  === $monthCursor->year;
                $inRange = $dayCursor->between($fromCarbon, $toCarbon);
                $row = $monthDays->get($key);

                $week[] = [
                    'date'        => $key,
                    'day'         => (int) $dayCursor->format('j'),
                    'in_month'    => $inMonth,
                    'in_range'    => $inRange,
                    'is_today'    => $dayCursor->isToday(),
                    'total'       => $row ? (float) $row->total : 0,
                    'transactions'=> $row ? (int) $row->cnt : 0,
                ];

                if (count($week) === 7) { $weeks[] = $week; $week = []; }
                $dayCursor->addDay();
            }

            $calendar['months'][] = [
                'label'        => $monthCursor->format('F Y'),
                'month_short'  => $monthCursor->format('M'),
                'year'         => (int) $monthCursor->year,
                'month_number' => (int) $monthCursor->month,
                'weeks'        => $weeks,
                'total'        => (float) $monthDays->sum('total'),
                'count'        => (int) $monthDays->sum('cnt'),
                'days_in_month'=> (int) $monthEnd->format('j'),
            ];

            $monthCursor->addMonth();
        }

        $maxDailyValue = count($values) > 0 ? max($values) : 0;

        // Top products
        $topProducts = \DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('stores', 'sales.store_id', '=', 'stores.id')
            ->where('stores.shop_id', $shopId)
            ->whereBetween('sales.created_at', [$fromCarbon, $toCarbon])
            ->when($user->isCashier(), fn($q) => $q->where('sales.cashier_id', $user->id))
            ->when($request->input('store'), fn($q, $sid) => $q->where('sales.store_id', $sid))
            ->select(
                'products.name',
                \DB::raw('SUM(sale_items.quantity) as total_qty'),
                \DB::raw('SUM(sale_items.line_total) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        // Top stores
        $topStores = Sale::query()
            ->join('stores', 'sales.store_id', '=', 'stores.id')
            ->where('stores.shop_id', $shopId)
            ->whereBetween('sales.created_at', [$fromCarbon, $toCarbon])
            ->when($user->isCashier(), fn($q) => $q->where('sales.cashier_id', $user->id))
            ->select(
                'stores.name',
                \DB::raw('SUM(sales.total) as total_revenue'),
                \DB::raw('COUNT(*) as total_count')
            )
            ->groupBy('stores.id', 'stores.name')
            ->orderByDesc('total_revenue')
            ->get();

        // Payment breakdown
        $paymentBreakdown = Sale::query()
            ->whereHas('store', fn($q) => $q->where('shop_id', $shopId))
            ->whereBetween('created_at', [$fromCarbon, $toCarbon])
            ->when($user->isCashier(), fn($q) => $q->where('cashier_id', $user->id))
            ->when($request->input('store'), fn($q, $sid) => $q->where('store_id', $sid))
            ->select(
                'payment_method',
                \DB::raw('SUM(total) as total'),
                \DB::raw('COUNT(*) as cnt')
            )
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get();

        $stores = Store::where('shop_id', $shopId)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $rangeLabel = $fromCarbon->format('d M Y') . ' – ' . $toCarbon->format('d M Y');

        return view('sales.analytics', compact(
            'kpis',
            'prevKpis',
            'compare',
            'labels',
            'values',
            'counts',
            'calendar',
            'maxDailyValue',
            'topProducts',
            'topStores',
            'paymentBreakdown',
            'stores',
            'rangeLabel'
        ));
    }

    /**
     * ✅ Single-day deep-dive — returns JSON for the analytics page.
     *
     * Query params:
     *   - date  (YYYY-MM-DD) required
     *   - store (int)        optional
     */
    public function dayDetail(Request $request)
    {
        $user   = Auth::user();
        $shopId = $user->shop_id;

        $date = $request->input('date');
        if (!$date) {
            return response()->json(['error' => 'Missing date'], 422);
        }

        try {
            $day = \Carbon\Carbon::parse($date)->startOfDay();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date'], 422);
        }

        $dayEnd = $day->copy()->endOfDay();
        $yesterdayStart = $day->copy()->subDay()->startOfDay();
        $yesterdayEnd   = $day->copy()->subDay()->endOfDay();

        // Base query scoped to shop + cashier
        $base = function () use ($shopId, $user) {
            $q = Sale::query()
                ->whereHas('store', fn($qq) => $qq->where('shop_id', $shopId));
            if ($user->isCashier()) {
                $q->where('cashier_id', $user->id);
            }
            return $q;
        };

        // Optional store filter
        $applyFilter = function ($q) use ($request) {
            if ($sid = $request->input('store')) {
                $q->where('store_id', $sid);
            }
            return $q;
        };

        // ---------- Today's KPIs ----------
        $today = $applyFilter($base())->whereBetween('created_at', [$day, $dayEnd]);

        $kpis = [
            'revenue'  => (float) (clone $today)->sum('total'),
            'count'    => (int)   (clone $today)->count(),
            'discount' => (float) (clone $today)->sum('discount_amount'),
        ];
        $kpis['avg_sale'] = $kpis['count'] > 0 ? $kpis['revenue'] / $kpis['count'] : 0;

        // ---------- Yesterday for comparison ----------
        $yest = $applyFilter($base())->whereBetween('created_at', [$yesterdayStart, $yesterdayEnd]);
        $prevRevenue = (float) (clone $yest)->sum('total');
        $prevCount   = (int)   (clone $yest)->count();

        $pctChange = function ($curr, $prev) {
            if ($prev == 0) return $curr > 0 ? 100 : 0;
            return (($curr - $prev) / $prev) * 100;
        };

        $compare = [
            'revenue_pct' => $pctChange($kpis['revenue'], $prevRevenue),
            'count_pct'   => $pctChange($kpis['count'], $prevCount),
        ];

        // ---------- Hourly breakdown ----------
        $hourlyRows = $applyFilter($base())
            ->whereBetween('created_at', [$day, $dayEnd])
            ->selectRaw('HOUR(created_at) as hour, SUM(total) as total, COUNT(*) as cnt')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->keyBy('hour');

        $hourly = [];
        for ($h = 0; $h < 24; $h++) {
            $row = $hourlyRows->get($h);
            $hourly[] = [
                'hour'  => str_pad($h, 2, '0', STR_PAD_LEFT),
                'label' => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00 – ' . str_pad($h, 2, '0', STR_PAD_LEFT) . ':59',
                'total' => $row ? (float) $row->total : 0,
                'count' => $row ? (int) $row->cnt : 0,
            ];
        }

        // ---------- Top products ----------
        $topProducts = \DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('stores', 'sales.store_id', '=', 'stores.id')
            ->where('stores.shop_id', $shopId)
            ->whereBetween('sales.created_at', [$day, $dayEnd])
            ->when($user->isCashier(), fn($q) => $q->where('sales.cashier_id', $user->id))
            ->when($request->input('store'), fn($q, $sid) => $q->where('sales.store_id', $sid))
            ->select(
                'products.name',
                \DB::raw('SUM(sale_items.quantity) as qty'),
                \DB::raw('SUM(sale_items.line_total) as revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('qty')
            ->limit(5)
            ->get()
            ->map(fn($p) => [
                'name'    => $p->name,
                'qty'     => (int) $p->qty,
                'revenue' => (float) $p->revenue,
            ])
            ->toArray();

        // ---------- Payment mix ----------
        $payMix = $applyFilter($base())
            ->whereBetween('created_at', [$day, $dayEnd])
            ->select(
                'payment_method',
                \DB::raw('SUM(total) as total'),
                \DB::raw('COUNT(*) as cnt')
            )
            ->groupBy('payment_method')
            ->get()
            ->map(fn($p) => [
                'method' => $p->payment_method ?? 'unknown',
                'total'  => (float) $p->total,
                'count'  => (int) $p->cnt,
            ])
            ->toArray();

        // ---------- Cashier performance ----------
        $cashiers = $applyFilter($base())
            ->whereBetween('created_at', [$day, $dayEnd])
            ->select(
                'cashier_id',
                \DB::raw('SUM(total) as total'),
                \DB::raw('COUNT(*) as cnt')
            )
            ->groupBy('cashier_id')
            ->get()
            ->map(function ($c) {
                $u = \App\Models\User::find($c->cashier_id);
                return [
                    'name'  => $u->full_name ?? 'Unknown',
                    'total' => (float) $c->total,
                    'count' => (int) $c->cnt,
                    'avg'   => $c->cnt > 0 ? (float) ($c->total / $c->cnt) : 0,
                ];
            })
            ->sortByDesc('total')
            ->values()
            ->toArray();

        return response()->json([
            'day_label'    => $day->format('l, d M Y'),
            'day_of_week'  => $day->format('l'),
            'week_no'      => $day->isoWeek(),
            'year'         => (int) $day->year,
            'kpis'         => $kpis,
            'compare'      => $compare,
            'hourly'       => $hourly,
            'top_products' => $topProducts,
            'payment_mix'  => $payMix,
            'cashiers'     => $cashiers,
        ]);
    }
}