<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SettingController;
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

        // Products — Downloads MUST come before the resource
        Route::get('products-download/csv', [ProductController::class, 'downloadCsv'])
            ->name('products.download.csv');
        Route::get('products-download/report', [ProductController::class, 'downloadReport'])
            ->name('products.download.report');

        // Products resource
        Route::resource('products', ProductController::class);

        // POS
        Route::prefix('pos')->name('pos.')->group(function () {
            Route::get('/', [PosController::class, 'index'])->name('index');
            Route::get('/search-products', [PosController::class, 'searchProducts'])->name('search-products');
            Route::post('/store', [PosController::class, 'store'])->name('store');
            Route::get('/receipt/{sale}', [PosController::class, 'receipt'])->name('receipt');
        });

        // Sales — Print & Download MUST come before {sale}
        Route::prefix('sales')->name('sales.')->group(function () {
            Route::get('/', [SalesController::class, 'index'])->name('index');
            Route::get('/print', [SalesController::class, 'print'])->name('print');
            Route::get('/download/csv', [SalesController::class, 'downloadCsv'])->name('download.csv');
            Route::get('/{sale}', [SalesController::class, 'show'])->name('show');
        });
    });
});