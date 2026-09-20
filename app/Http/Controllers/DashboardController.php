<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Stock;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the dashboard based on the authenticated user's role.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin() || $user->isOwner()) {
            return $this->adminDashboard($user);
        }

        if ($user->isCashier()) {
            return $this->cashierDashboard($user);
        }

        abort(403, 'Unauthorized.');
    }

    /**
     * Admin / Owner dashboard.
     */
    private function adminDashboard($user)
    {
        $shopId = $user->shop_id;

        $stats = [
            'total_stores'    => Store::where('shop_id', $shopId)->count(),
            'total_products'  => Product::count(),
            'total_users'     => User::where('shop_id', $shopId)->count(),
            'today_sales'     => Sale::whereHas('store', function ($q) use ($shopId) {
                                    $q->where('shop_id', $shopId);
                                })
                                ->whereDate('created_at', today())
                                ->sum('total'),
            'today_count'     => Sale::whereHas('store', function ($q) use ($shopId) {
                                    $q->where('shop_id', $shopId);
                                })
                                ->whereDate('created_at', today())
                                ->count(),
            'month_sales'     => Sale::whereHas('store', function ($q) use ($shopId) {
                                    $q->where('shop_id', $shopId);
                                })
                                ->whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)
                                ->sum('total'),
            // ✅ NEW — All Sales
            'all_sales'       => Sale::whereHas('store', function ($q) use ($shopId) {
                                    $q->where('shop_id', $shopId);
                                })
                                ->sum('total'),
            'all_sales_count' => Sale::whereHas('store', function ($q) use ($shopId) {
                                    $q->where('shop_id', $shopId);
                                })
                                ->count(),
        ];

        $recentSales = Sale::with(['store', 'cashier'])
            ->whereHas('store', function ($q) use ($shopId) {
                $q->where('shop_id', $shopId);
            })
            ->latest()
            ->limit(10)
            ->get();

        $lowStock = Stock::with(['product', 'store'])
            ->whereHas('store', function ($q) use ($shopId) {
                $q->where('shop_id', $shopId);
            })
            ->whereColumn('quantity', '<=', 'min_quantity')
            ->limit(10)
            ->get();

        return view('dashboard.admin', compact('stats', 'recentSales', 'lowStock'));
    }

    /**
     * Cashier dashboard.
     */
    private function cashierDashboard($user)
    {
        $shopId = $user->shop_id;

        $todaySales = Sale::where('cashier_id', $user->id)
            ->whereHas('store', function ($q) use ($shopId) {
                $q->where('shop_id', $shopId);
            })
            ->whereDate('created_at', today())
            ->sum('total');

        $todayCount = Sale::where('cashier_id', $user->id)
            ->whereHas('store', function ($q) use ($shopId) {
                $q->where('shop_id', $shopId);
            })
            ->whereDate('created_at', today())
            ->count();

        $recentSales = Sale::with('store')
            ->where('cashier_id', $user->id)
            ->whereHas('store', function ($q) use ($shopId) {
                $q->where('shop_id', $shopId);
            })
            ->latest()
            ->limit(10)
            ->get();

        $stores = Store::where('shop_id', $shopId)
            ->where('is_active', true)
            ->select('id', 'name')
            ->get();

        return view('dashboard.cashier', compact(
            'todaySales',
            'todayCount',
            'recentSales',
            'stores'
        ));
    }
}