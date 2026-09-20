@extends('layouts.app')

@section('title', 'Dashboard - Admin')

@section('content')

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 mb-6">

        {{-- Today Sales --}}
        <div class="stat-card fade-in-up d-1 bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-4 sm:p-5 shadow-2xl border border-stone-600/50">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-600 to-lime-600 flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-stone-300 text-xs mt-3">Today's Sales</p>
            <p class="text-white text-xl sm:text-2xl font-bold mt-1">
                TSh {{ number_format($stats['today_sales'], 0) }}
            </p>
            <p class="text-lime-400 text-xs mt-1 font-medium">{{ $stats['today_count'] }} sales</p>
        </div>

        {{-- Month Sales --}}
        <div class="stat-card fade-in-up d-2 bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-4 sm:p-5 shadow-2xl border border-stone-600/50">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-600 to-lime-600 flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
            <p class="text-stone-300 text-xs mt-3">Month's Sales</p>
            <p class="text-white text-xl sm:text-2xl font-bold mt-1">
                TSh {{ number_format($stats['month_sales'], 0) }}
            </p>
            <p class="text-lime-400 text-xs mt-1 font-medium">This month</p>
        </div>

        {{-- Stores --}}
        <div class="stat-card fade-in-up d-3 bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-4 sm:p-5 shadow-2xl border border-stone-600/50">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-600 to-lime-600 flex items-center justify-center shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <p class="text-stone-300 text-xs mt-3">Stores</p>
            <p class="text-white text-xl sm:text-2xl font-bold mt-1">{{ $stats['total_stores'] }}</p>
            <p class="text-lime-400 text-xs mt-1 font-medium">All branches</p>
        </div>

        {{-- Products --}}
        <div class="stat-card fade-in-up d-4 bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-4 sm:p-5 shadow-2xl border border-stone-600/50">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-600 to-lime-600 flex items-center justify-center shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <p class="text-stone-300 text-xs mt-3">Products</p>
            <p class="text-white text-xl sm:text-2xl font-bold mt-1">{{ $stats['total_products'] }}</p>
            <p class="text-lime-400 text-xs mt-1 font-medium">All items</p>
        </div>

        {{-- Users --}}
        <div class="stat-card fade-in-up d-5 bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-4 sm:p-5 shadow-2xl border border-stone-600/50">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-600 to-lime-600 flex items-center justify-center shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <p class="text-stone-300 text-xs mt-3">Users</p>
            <p class="text-white text-xl sm:text-2xl font-bold mt-1">{{ $stats['total_users'] }}</p>
            <p class="text-lime-400 text-xs mt-1 font-medium">Admin, Owner, Cashier</p>
        </div>

        {{-- All Sales (Clickable) --}}
        <a href="{{ route('sales.index') }}"
           class="stat-card fade-in-up d-6 bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-4 sm:p-5 shadow-2xl border border-stone-600/50 hover:bg-stone-700/60 transition block">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-600 to-lime-600 flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
            <p class="text-stone-300 text-xs mt-3">All Sales</p>
            <p class="text-white text-xl sm:text-2xl font-bold mt-1">
                TSh {{ number_format($stats['all_sales'], 0) }}
            </p>
            <p class="text-lime-400 text-xs mt-1 font-medium">
                {{ $stats['all_sales_count'] }} sales · Tap to view
            </p>
        </a>

    </div>

    {{-- Low stock alert --}}
    @if ($lowStock->count() > 0)
        <div class="fade-in-up bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-5 mb-6 border-l-4 border-yellow-500 shadow-2xl border border-stone-600/50">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 rounded-lg bg-yellow-500/20 border border-yellow-500/40 flex items-center justify-center">
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="text-white font-semibold">Low Stock Items</h3>
                <span class="ml-auto text-xs text-yellow-200 bg-yellow-500/20 border border-yellow-500/40 px-2 py-1 rounded-full font-medium">
                    {{ $lowStock->count() }} items
                </span>
            </div>
            <div class="space-y-2">
                @foreach ($lowStock as $stock)
                    <div class="flex justify-between items-center py-2 px-3 rounded-lg bg-stone-800/60 hover:bg-stone-700/60 transition border border-stone-600/50">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full bg-yellow-400 pulse-dot"></div>
                            <span class="text-white text-sm">{{ $stock->product->name }}</span>
                            <span class="text-stone-300 text-xs">· {{ $stock->store->name }}</span>
                        </div>
                        <span class="text-yellow-300 text-sm font-semibold">
                            {{ $stock->quantity }} left
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

@endsection