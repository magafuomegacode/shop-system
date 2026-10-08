<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Forgot / Reset Password Routes (Public)
|--------------------------------------------------------------------------
*/

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

/*
|--------------------------------------------------------------------------
| Protected Routes (Authenticated Users)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |----------------------------------------------------------------------
    | Dashboard — All Roles
    |----------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |----------------------------------------------------------------------
    | Profile — All Roles
    |----------------------------------------------------------------------
    */
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'changePassword'])->name('change-password');
    });

    /*
    |----------------------------------------------------------------------
    | Notifications — All Roles
    |----------------------------------------------------------------------
    */
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::patch('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::patch('/read-all', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    });

    /*
    |----------------------------------------------------------------------
    | Admin & Owner
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin,owner')->group(function () {

        // Users
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])
            ->name('users.toggle-active');
        Route::patch('users/{user}/change-password', [UserController::class, 'changePassword'])
            ->name('users.change-password');
        Route::patch('users/{user}/reset-password', [UserController::class, 'resetPassword'])
            ->name('users.reset-password');

        // Stores
        Route::resource('stores', StoreController::class);
        Route::patch('stores/{store}/toggle-active', [StoreController::class, 'toggleActive'])
            ->name('stores.toggle-active');

        // ⚙️ Settings — Admin & Owner only
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

    /*
    |----------------------------------------------------------------------
    | Admin, Owner & Cashier
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin,owner,cashier')->group(function () {

        // Products — Downloads & Category Chooser MUST come before the resource
        Route::get('products-download/csv', [ProductController::class, 'downloadCsv'])
            ->name('products.download.csv');
        Route::get('products-download/report', [ProductController::class, 'downloadReport'])
            ->name('products.download.report');

        // 🆕 Category chooser — step 1 of the add-product flow
        Route::get('products/categories', [ProductController::class, 'chooseCategory'])
            ->name('products.categories');

        // Products resource
        Route::resource('products', ProductController::class);

        // 📦 Stock Movements — inventory log (in/out, who & when)
        Route::prefix('stock-movements')->name('stock-movements.')->group(function () {
            Route::get('/', [StockMovementController::class, 'index'])->name('index');
        });

        // POS
        Route::prefix('pos')->name('pos.')->group(function () {
            Route::get('/', [PosController::class, 'index'])->name('index');
            Route::get('/search-products', [PosController::class, 'searchProducts'])->name('search-products');
            Route::post('/store', [PosController::class, 'store'])->name('store');
            Route::get('/receipt/{sale}', [PosController::class, 'receipt'])->name('receipt');
        });

        // Sales — all specific routes MUST come before {sale}
        Route::prefix('sales')->name('sales.')->group(function () {
            Route::get('/', [SalesController::class, 'index'])->name('index');
            Route::get('/print', [SalesController::class, 'print'])->name('print');
            Route::get('/download/csv', [SalesController::class, 'downloadCsv'])->name('download.csv');

            // ✅ Analytics — dedicated business analysis page
            Route::get('/analytics', [SalesController::class, 'analytics'])->name('analytics');

            // ✅ Day-by-day chart data (JSON — used on analytics page if needed)
            Route::get('/daily-chart', [SalesController::class, 'dailyChart'])
                ->name('daily-chart');

            // ✅ Single-day deep-dive — JSON for the analytics page
            Route::get('/day-detail', [SalesController::class, 'dayDetail'])
                ->name('day-detail');

            // Show a single sale — MUST be last so it doesn't swallow the above
            Route::get('/{sale}', [SalesController::class, 'show'])->name('show');
        });
    });
});