@extends('layouts.app')

@section('title', 'Dashboard - Cashier')

@section('content')

    {{-- ========================================================== --}}
    {{-- WELCOME HEADER --}}
    {{-- ========================================================== --}}
    <div class="fade-in-up d-1 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 sm:p-6 mb-6 relative overflow-hidden shadow-2xl border border-blue-700/50">
        <div class="absolute top-0 right-0 w-56 h-56 bg-gradient-to-br from-cyan-500/20 to-blue-500/20 rounded-full -mr-28 -mt-28 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-gradient-to-tr from-cyan-500/10 to-blue-500/10 rounded-full -ml-16 -mb-16 pointer-events-none"></div>

        <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center shadow-lg flex-shrink-0">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-white font-bold text-[10px] uppercase tracking-widest opacity-70">
                        Welcome back
                    </p>
                    <h1 class="text-white font-bold text-xl sm:text-2xl mt-0.5 truncate">
                        {{ auth()->user()->full_name ?? 'Cashier' }}
                    </h1>
                    <p class="text-white font-bold text-xs mt-1 opacity-80">
                        {{ now()->format('l, d M Y') }} · {{ now()->format('H:i') }}
                    </p>
                </div>
            </div>

            <a href="{{ route('pos.index') }}"
               class="w-full sm:w-auto bg-gradient-to-br from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-bold text-sm px-5 py-3 rounded-xl flex items-center justify-center gap-2 transition shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Open POS
            </a>
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- KPI CARDS --}}
    {{-- ========================================================== --}}
    <div class="grid grid-cols-2 gap-3 sm:gap-4 mb-6">

        {{-- Today's Sales --}}
        <div class="stat-card fade-in-up d-2 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 sm:p-5 shadow-2xl border border-blue-700/50 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-cyan-500/10 to-blue-500/10 rounded-full -mr-12 -mt-12 pointer-events-none"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-1 rounded-lg border bg-cyan-500/20 text-white border-cyan-400/40">
                        TODAY
                    </span>
                </div>
                <p class="text-white font-bold text-xs mt-3 uppercase tracking-wider opacity-70">My Sales Today</p>
                <p class="text-white font-bold text-xl sm:text-2xl mt-1">
                    TSh {{ number_format($todaySales, 0) }}
                </p>
                <p class="text-white font-bold text-xs mt-1.5 opacity-80">
                    {{ now()->format('d M Y') }}
                </p>
            </div>
        </div>

        {{-- Sales Count --}}
        <div class="stat-card fade-in-up d-3 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 sm:p-5 shadow-2xl border border-blue-700/50 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-green-500/10 to-emerald-500/10 rounded-full -mr-12 -mt-12 pointer-events-none"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-1 rounded-lg border bg-green-500/20 text-white border-green-400/40">
                        TODAY
                    </span>
                </div>
                <p class="text-white font-bold text-xs mt-3 uppercase tracking-wider opacity-70">Transactions</p>
                <p class="text-white font-bold text-xl sm:text-2xl mt-1">{{ $todayCount }}</p>
                <p class="text-white font-bold text-xs mt-1.5 opacity-80">
                    {{ Str::plural('sale', $todayCount) }} completed
                </p>
            </div>
        </div>

    </div>

    {{-- ========================================================== --}}
    {{-- QUICK ACTIONS --}}
    {{-- ========================================================== --}}
    <div class="fade-in-up d-4 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 mb-6 shadow-2xl border border-blue-700/50">
        <h3 class="text-white font-bold text-sm mb-4 uppercase tracking-wider flex items-center gap-2">
            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            Quick Actions
        </h3>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3">

            <a href="{{ route('pos.index') }}"
               class="bg-blue-900/40 hover:bg-blue-800/60 border border-blue-700/50 hover:border-cyan-400/60 rounded-xl p-3 flex flex-col items-center gap-2 transition group">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-xs text-center">New Sale</span>
            </a>

            <a href="{{ route('sales.index') }}"
               class="bg-blue-900/40 hover:bg-blue-800/60 border border-blue-700/50 hover:border-cyan-400/60 rounded-xl p-3 flex flex-col items-center gap-2 transition group">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-xs text-center">My Sales</span>
            </a>

            <a href="{{ route('products.index') }}"
               class="bg-blue-900/40 hover:bg-blue-800/60 border border-blue-700/50 hover:border-cyan-400/60 rounded-xl p-3 flex flex-col items-center gap-2 transition group">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-xs text-center">Products</span>
            </a>

            <a href="{{ route('notifications.index') }}"
               class="bg-blue-900/40 hover:bg-blue-800/60 border border-blue-700/50 hover:border-cyan-400/60 rounded-xl p-3 flex flex-col items-center gap-2 transition group">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-xs text-center">Alerts</span>
            </a>

        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- RECENT SALES --}}
    {{-- ========================================================== --}}
    <div class="fade-in-up d-5 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 shadow-2xl border border-blue-700/50">
        <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
            <h3 class="text-white font-bold text-base flex items-center gap-2">
                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                My Recent Sales
            </h3>
            <a href="{{ route('sales.index') }}"
               class="text-white font-bold text-xs hover:text-cyan-300 transition flex items-center gap-1">
                View all
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        @if ($recentSales->count() > 0)
            <div class="space-y-2">
                @foreach ($recentSales as $sale)
                    <a href="{{ route('sales.show', $sale) }}"
                       class="flex items-center justify-between py-3 px-3 rounded-xl bg-blue-900/40 hover:bg-blue-800/60 transition border border-blue-700/50 hover:border-cyan-400/60 group">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-lg bg-cyan-500/20 border border-cyan-400/40 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-white font-bold text-sm font-mono truncate">
                                    {{ $sale->invoice_no }}
                                </p>
                                <p class="text-white font-bold text-xs opacity-70 truncate">
                                    {{ $sale->store->name ?? '—' }}
                                </p>
                            </div>
                        </div>

                        <div class="text-right flex-shrink-0 ml-2">
                            <p class="text-white font-bold text-sm">
                                TSh {{ number_format($sale->total, 0) }}
                            </p>
                            <p class="text-white font-bold text-[10px] opacity-70">
                                {{ $sale->created_at->format('H:i') }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-10">
                <div class="w-16 h-16 rounded-2xl bg-blue-900/40 border border-blue-700/50 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-8 h-8 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6m4 6v-3m4 3V8M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-white font-bold text-sm">No sales yet</p>
                <p class="text-white font-bold text-xs mt-1 opacity-70">Your sales will appear here</p>
                <a href="{{ route('pos.index') }}"
                   class="inline-flex items-center gap-1 mt-4 text-white font-bold text-xs hover:text-cyan-300 transition">
                    Start selling
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        @endif
    </div>

@endsection