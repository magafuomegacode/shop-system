@extends('layouts.app')

@section('title', 'Dashboard - Admin')

@section('content')

    {{-- ========================================================== --}}
    {{-- WELCOME HEADER --}}
    {{-- ========================================================== --}}
    <div class="fade-in-up d-1 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 sm:p-6 mb-6 relative overflow-hidden shadow-2xl border border-blue-700/50">
        <div class="absolute top-0 right-0 w-56 h-56 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-full -mr-28 -mt-28 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-gradient-to-tr from-purple-500/10 to-pink-500/10 rounded-full -ml-16 -mb-16 pointer-events-none"></div>

        <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-lg flex-shrink-0">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-white font-bold text-[10px] uppercase tracking-widest opacity-70">
                        Welcome back
                    </p>
                    <h1 class="text-white font-bold text-xl sm:text-2xl mt-0.5 truncate">
                        {{ auth()->user()->full_name ?? 'Admin' }}
                    </h1>
                    <p class="text-white font-bold text-xs mt-1 opacity-80">
                        {{ now()->format('l, d M Y') }} · {{ now()->format('H:i') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap w-full sm:w-auto">
                <a href="{{ route('pos.index') }}"
                   class="flex-1 sm:flex-initial bg-gradient-to-br from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-bold text-sm px-4 py-2.5 rounded-xl flex items-center justify-center gap-2 transition shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    New Sale
                </a>
                <a href="{{ route('stores.index') }}"
                   class="flex-1 sm:flex-initial bg-blue-900/40 hover:bg-blue-800/60 border border-blue-700/50 text-white font-bold text-sm px-4 py-2.5 rounded-xl flex items-center justify-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Product
                </a>
            </div>
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- KPI STATS GRID --}}
    {{-- ========================================================== --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 mb-6">

        {{-- Today Sales --}}
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
                <p class="text-white font-bold text-xs mt-3 uppercase tracking-wider opacity-70">Today's Sales</p>
                <p class="text-white font-bold text-xl sm:text-2xl mt-1">
                    TSh {{ number_format($stats['today_sales'], 0) }}
                </p>
                <p class="text-white font-bold text-xs mt-1.5 opacity-80">
                    {{ $stats['today_count'] }} {{ Str::plural('sale', $stats['today_count']) }}
                </p>
            </div>
        </div>

        {{-- Month Sales --}}
        <div class="stat-card fade-in-up d-3 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 sm:p-5 shadow-2xl border border-blue-700/50 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-green-500/10 to-emerald-500/10 rounded-full -mr-12 -mt-12 pointer-events-none"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-1 rounded-lg border bg-green-500/20 text-white border-green-400/40">
                        MONTH
                    </span>
                </div>
                <p class="text-white font-bold text-xs mt-3 uppercase tracking-wider opacity-70">Month's Sales</p>
                <p class="text-white font-bold text-xl sm:text-2xl mt-1">
                    TSh {{ number_format($stats['month_sales'], 0) }}
                </p>
                <p class="text-white font-bold text-xs mt-1.5 opacity-80">
                    {{ now()->format('F Y') }}
                </p>
            </div>
        </div>

        {{-- Stores --}}
        <a href="{{ route('stores.index') }}"
           class="stat-card fade-in-up d-4 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 sm:p-5 shadow-2xl border border-blue-700/50 hover:border-cyan-400/60 hover:bg-blue-800/40 transition relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-purple-500/10 to-pink-500/10 rounded-full -mr-12 -mt-12 pointer-events-none"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <svg class="w-4 h-4 text-white/50 group-hover:text-cyan-300 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
                <p class="text-white font-bold text-xs mt-3 uppercase tracking-wider opacity-70">Stores</p>
                <p class="text-white font-bold text-xl sm:text-2xl mt-1">{{ $stats['total_stores'] }}</p>
                <p class="text-white font-bold text-xs mt-1.5 opacity-80">All branches</p>
            </div>
        </a>

        {{-- Products --}}
        <a href="{{ route('products.index') }}"
           class="stat-card fade-in-up d-5 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 sm:p-5 shadow-2xl border border-blue-700/50 hover:border-cyan-400/60 hover:bg-blue-800/40 transition relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-amber-500/10 to-orange-500/10 rounded-full -mr-12 -mt-12 pointer-events-none"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <svg class="w-4 h-4 text-white/50 group-hover:text-cyan-300 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
                <p class="text-white font-bold text-xs mt-3 uppercase tracking-wider opacity-70">Products</p>
                <p class="text-white font-bold text-xl sm:text-2xl mt-1">{{ $stats['total_products'] }}</p>
                <p class="text-white font-bold text-xs mt-1.5 opacity-80">All items</p>
            </div>
        </a>

        {{-- Users --}}
        @if(auth()->user()->isAdmin() || auth()->user()->isOwner())
            <a href="{{ route('users.index') }}"
               class="stat-card fade-in-up d-6 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 sm:p-5 shadow-2xl border border-blue-700/50 hover:border-cyan-400/60 hover:bg-blue-800/40 transition relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-indigo-500/10 to-blue-500/10 rounded-full -mr-12 -mt-12 pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="flex items-start justify-between">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <svg class="w-4 h-4 text-white/50 group-hover:text-cyan-300 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <p class="text-white font-bold text-xs mt-3 uppercase tracking-wider opacity-70">Users</p>
                    <p class="text-white font-bold text-xl sm:text-2xl mt-1">{{ $stats['total_users'] }}</p>
                    <p class="text-white font-bold text-xs mt-1.5 opacity-80">Admin · Owner · Cashier</p>
                </div>
            </a>
        @else
            <div class="stat-card fade-in-up d-6 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 sm:p-5 shadow-2xl border border-blue-700/50 relative overflow-hidden">
                <div class="relative z-10">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-500 flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <p class="text-white font-bold text-xs mt-3 uppercase tracking-wider opacity-70">Users</p>
                    <p class="text-white font-bold text-xl sm:text-2xl mt-1">{{ $stats['total_users'] }}</p>
                    <p class="text-white font-bold text-xs mt-1.5 opacity-80">Team members</p>
                </div>
            </div>
        @endif

        {{-- All Sales --}}
        <a href="{{ route('sales.index') }}"
           class="stat-card fade-in-up d-6 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 sm:p-5 shadow-2xl border border-blue-700/50 hover:border-cyan-400/60 hover:bg-blue-800/40 transition relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-cyan-500/10 to-blue-500/10 rounded-full -mr-12 -mt-12 pointer-events-none"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <svg class="w-4 h-4 text-white/50 group-hover:text-cyan-300 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
                <p class="text-white font-bold text-xs mt-3 uppercase tracking-wider opacity-70">All-Time Sales</p>
                <p class="text-white font-bold text-xl sm:text-2xl mt-1">
                    TSh {{ number_format($stats['all_sales'], 0) }}
                </p>
                <p class="text-white font-bold text-xs mt-1.5 opacity-80">
                    {{ $stats['all_sales_count'] }} {{ Str::plural('sale', $stats['all_sales_count']) }} · Tap to view
                </p>
            </div>
        </a>

    </div>

    {{-- ========================================================== --}}
    {{-- QUICK ACTIONS --}}
    {{-- ========================================================== --}}
    <div class="fade-in-up d-6 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 mb-6 shadow-2xl border border-blue-700/50">
        <h3 class="text-white font-bold text-sm mb-4 uppercase tracking-wider flex items-center gap-2">
            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            Quick Actions
        </h3>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2 sm:gap-3">

            <a href="{{ route('pos.index') }}"
               class="bg-blue-900/40 hover:bg-blue-800/60 border border-blue-700/50 hover:border-cyan-400/60 rounded-xl p-3 flex flex-col items-center gap-2 transition group">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-[11px] text-center">New Sale</span>
            </a>

            <a href="{{ route('products.index') }}"
               class="bg-blue-900/40 hover:bg-blue-800/60 border border-blue-700/50 hover:border-cyan-400/60 rounded-xl p-3 flex flex-col items-center gap-2 transition group">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-[11px] text-center">Products</span>
            </a>

            <a href="{{ route('stores.index') }}"
               class="bg-blue-900/40 hover:bg-blue-800/60 border border-blue-700/50 hover:border-cyan-400/60 rounded-xl p-3 flex flex-col items-center gap-2 transition group">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-[11px] text-center">Stores</span>
            </a>

            <a href="{{ route('sales.index') }}"
               class="bg-blue-900/40 hover:bg-blue-800/60 border border-blue-700/50 hover:border-cyan-400/60 rounded-xl p-3 flex flex-col items-center gap-2 transition group">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-[11px] text-center">Sales</span>
            </a>

            <a href="{{ route('sales.analytics') }}"
               class="bg-blue-900/40 hover:bg-blue-800/60 border border-blue-700/50 hover:border-cyan-400/60 rounded-xl p-3 flex flex-col items-center gap-2 transition group">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-fuchsia-500 to-purple-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-[11px] text-center">Analytics</span>
            </a>

            @if(auth()->user()->isAdmin() || auth()->user()->isOwner())
                <a href="{{ route('settings.index') }}"
                   class="bg-blue-900/40 hover:bg-blue-800/60 border border-blue-700/50 hover:border-cyan-400/60 rounded-xl p-3 flex flex-col items-center gap-2 transition group">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-slate-500 to-slate-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <span class="text-white font-bold text-[11px] text-center">Settings</span>
                </a>
            @endif

        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- LOW STOCK ALERT --}}
    {{-- ========================================================== --}}
    @if ($lowStock->count() > 0)
        <div class="fade-in-up d-6 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 mb-6 shadow-2xl border-2 border-yellow-500/50 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-yellow-500/10 to-orange-500/10 rounded-full -mr-16 -mt-16 pointer-events-none"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-4 flex-wrap">
                    <div class="w-9 h-9 rounded-lg bg-yellow-500/20 border border-yellow-500/40 flex items-center justify-center">
                        <svg class="w-4 h-4 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-white font-bold text-base">Low Stock Alert</h3>
                    <span class="ml-auto text-xs text-white font-bold bg-yellow-500/30 border border-yellow-500/50 px-2.5 py-1 rounded-full">
                        {{ $lowStock->count() }} {{ Str::plural('item', $lowStock->count()) }}
                    </span>
                </div>

                <div class="space-y-2 max-h-[400px] overflow-y-auto pr-1">
                    @foreach ($lowStock as $stock)
                        <div class="flex justify-between items-center py-2.5 px-3 rounded-xl bg-blue-900/40 hover:bg-blue-800/60 transition border border-blue-700/50">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-2 h-2 rounded-full bg-yellow-400 pulse-dot flex-shrink-0"></div>
                                <div class="min-w-0">
                                    <p class="text-white font-bold text-sm truncate">
                                        {{ $stock->product->name ?? 'Unknown product' }}
                                    </p>
                                    <p class="text-white font-bold text-[10px] opacity-70 truncate">
                                        {{ $stock->store->name ?? '—' }}
                                    </p>
                                </div>
                            </div>
                            <span class="text-yellow-300 font-bold text-sm flex-shrink-0 ml-2">
                                {{ $stock->quantity }} left
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

@endsection