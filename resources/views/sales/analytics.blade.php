@extends('layouts.app')

@section('title', 'Sales Analytics')

@section('content')

    {{-- ========================================================== --}}
    {{-- HEADER --}}
    {{-- ========================================================== --}}
    <a href="{{ route('sales.index') }}"
       class="inline-flex items-center gap-2 text-white hover:text-cyan-300 text-sm mb-4 font-bold">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Sales
    </a>

    <div class="fade-in-up d-1 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 mb-6 relative overflow-hidden shadow-2xl border border-blue-700/50">
        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-full -mr-20 -mt-20 pointer-events-none"></div>

        <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-white font-bold text-xl sm:text-2xl flex items-center gap-2">
                    <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Business Analytics
                </h2>
                <p class="text-white font-bold text-xs sm:text-sm mt-1 opacity-80">
                    {{ $rangeLabel }}
                </p>
            </div>

            <div class="flex items-center gap-2 flex-wrap w-full sm:w-auto">
                <a href="{{ route('sales.print', request()->only(['search', 'store', 'from', 'to'])) }}"
                   target="_blank"
                   class="flex-1 sm:flex-initial bg-blue-900/40 border border-blue-700/50 hover:bg-blue-800/50 text-white font-bold text-sm px-4 py-2.5 rounded-xl flex items-center justify-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span class="hidden sm:inline">Print</span>
                </a>

                <a href="{{ route('sales.download.csv', request()->only(['search', 'store', 'from', 'to'])) }}"
                   class="flex-1 sm:flex-initial bg-blue-900/40 border border-blue-700/50 hover:bg-blue-800/50 text-white font-bold text-sm px-4 py-2.5 rounded-xl flex items-center justify-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span class="hidden sm:inline">CSV</span>
                </a>
            </div>
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- FILTER BAR --}}
    {{-- ========================================================== --}}
    <div class="fade-in-up d-1 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 mb-6 shadow-2xl border border-blue-700/50">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <select name="store"
                    class="bg-blue-900/40 border border-blue-700/50 text-white font-bold px-4 py-2.5 rounded-xl outline-none text-sm appearance-none focus:border-cyan-400">
                <option value="" class="bg-blue-950 text-white">All stores</option>
                @foreach($stores as $store)
                    <option value="{{ $store->id }}" class="bg-blue-950 text-white" {{ request('store') == $store->id ? 'selected' : '' }}>
                        {{ $store->name }}
                    </option>
                @endforeach
            </select>

            <input type="date" name="from" value="{{ request('from', now()->subDays(29)->format('Y-m-d')) }}"
                   class="bg-blue-900/40 border border-blue-700/50 text-white font-bold px-4 py-2.5 rounded-xl outline-none text-sm focus:border-cyan-400">

            <input type="date" name="to" value="{{ request('to', now()->format('Y-m-d')) }}"
                   class="bg-blue-900/40 border border-blue-700/50 text-white font-bold px-4 py-2.5 rounded-xl outline-none text-sm focus:border-cyan-400">

            <button type="submit"
                    class="bg-gradient-to-br from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-bold text-sm px-5 py-2.5 rounded-xl transition shadow-lg">
                Apply
            </button>

            <div class="flex gap-1">
                <a href="{{ route('sales.analytics', ['from' => now()->format('Y-m-d'), 'to' => now()->format('Y-m-d')]) }}"
                   class="flex-1 bg-blue-900/40 hover:bg-blue-800/50 border border-blue-700/50 text-white font-bold text-xs px-2 py-2.5 rounded-xl transition text-center">Today</a>
                <a href="{{ route('sales.analytics', ['from' => now()->subDays(6)->format('Y-m-d'), 'to' => now()->format('Y-m-d')]) }}"
                   class="flex-1 bg-blue-900/40 hover:bg-blue-800/50 border border-blue-700/50 text-white font-bold text-xs px-2 py-2.5 rounded-xl transition text-center">7D</a>
                <a href="{{ route('sales.analytics', ['from' => now()->subDays(29)->format('Y-m-d'), 'to' => now()->format('Y-m-d')]) }}"
                   class="flex-1 bg-blue-900/40 hover:bg-blue-800/50 border border-blue-700/50 text-white font-bold text-xs px-2 py-2.5 rounded-xl transition text-center">30D</a>
                <a href="{{ route('sales.analytics', ['from' => now()->startOfMonth()->format('Y-m-d'), 'to' => now()->endOfMonth()->format('Y-m-d')]) }}"
                   class="flex-1 bg-blue-900/40 hover:bg-blue-800/50 border border-blue-700/50 text-white font-bold text-xs px-2 py-2.5 rounded-xl transition text-center">MTD</a>
            </div>
        </form>
    </div>

    {{-- ========================================================== --}}
    {{-- KPI CARDS --}}
    {{-- ========================================================== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">

        <div class="fade-in-up d-2 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 sm:p-5 shadow-2xl border border-blue-700/50">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1"/>
                    </svg>
                </div>
                @if(($compare['revenue_pct'] ?? 0) != 0)
                    <span class="text-[10px] font-bold px-2 py-1 rounded-lg border
                        {{ $compare['revenue_pct'] >= 0
                            ? 'bg-green-500/20 text-green-300 border-green-400/40'
                            : 'bg-red-500/20 text-red-300 border-red-400/40' }}">
                        {{ $compare['revenue_pct'] >= 0 ? '▲' : '▼' }}
                        {{ number_format(abs($compare['revenue_pct']), 1) }}%
                    </span>
                @endif
            </div>
            <p class="text-white font-bold text-xs mt-3 opacity-80">Total Revenue</p>
            <p class="text-white font-bold text-lg sm:text-2xl mt-1">TSh {{ number_format($kpis['revenue'], 0) }}</p>
            <p class="text-white font-bold text-[10px] mt-1 opacity-60">prev: TSh {{ number_format($compare['revenue_prev'], 0) }}</p>
        </div>

        <div class="fade-in-up d-3 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 sm:p-5 shadow-2xl border border-blue-700/50">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                @if(($compare['count_pct'] ?? 0) != 0)
                    <span class="text-[10px] font-bold px-2 py-1 rounded-lg border
                        {{ $compare['count_pct'] >= 0
                            ? 'bg-green-500/20 text-green-300 border-green-400/40'
                            : 'bg-red-500/20 text-red-300 border-red-400/40' }}">
                        {{ $compare['count_pct'] >= 0 ? '▲' : '▼' }}
                        {{ number_format(abs($compare['count_pct']), 1) }}%
                    </span>
                @endif
            </div>
            <p class="text-white font-bold text-xs mt-3 opacity-80">Transactions</p>
            <p class="text-white font-bold text-lg sm:text-2xl mt-1">{{ number_format($kpis['count']) }}</p>
            <p class="text-white font-bold text-[10px] mt-1 opacity-60">prev: {{ number_format($compare['count_prev']) }}</p>
        </div>

        <div class="fade-in-up d-4 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 sm:p-5 shadow-2xl border border-blue-700/50">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                @if(($compare['avg_pct'] ?? 0) != 0)
                    <span class="text-[10px] font-bold px-2 py-1 rounded-lg border
                        {{ $compare['avg_pct'] >= 0
                            ? 'bg-green-500/20 text-green-300 border-green-400/40'
                            : 'bg-red-500/20 text-red-300 border-red-400/40' }}">
                        {{ $compare['avg_pct'] >= 0 ? '▲' : '▼' }}
                        {{ number_format(abs($compare['avg_pct']), 1) }}%
                    </span>
                @endif
            </div>
            <p class="text-white font-bold text-xs mt-3 opacity-80">Avg. Sale Value</p>
            <p class="text-white font-bold text-lg sm:text-2xl mt-1">TSh {{ number_format($kpis['avg_sale'], 0) }}</p>
            <p class="text-white font-bold text-[10px] mt-1 opacity-60">prev: TSh {{ number_format($compare['avg_prev'], 0) }}</p>
        </div>

        <div class="fade-in-up d-4 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 sm:p-5 shadow-2xl border border-blue-700/50">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-rose-500 flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M7 7h.01M7 3h5a2 2 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
            </div>
            <p class="text-white font-bold text-xs mt-3 opacity-80">Discounts Given</p>
            <p class="text-white font-bold text-lg sm:text-2xl mt-1">TSh {{ number_format($kpis['discount'], 0) }}</p>
            <p class="text-white font-bold text-[10px] mt-1 opacity-60">
                {{ $kpis['revenue'] > 0 ? number_format(($kpis['discount'] / ($kpis['revenue'] + $kpis['discount'])) * 100, 1) : 0 }}% of gross
            </p>
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- MAIN CHART --}}
    {{-- ========================================================== --}}
    <div class="fade-in-up d-5 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 mb-6 shadow-2xl border border-blue-700/50">
        <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
            <div>
                <h3 class="text-white font-bold text-base flex items-center gap-2">
                    <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                    Day-by-Day Sales
                </h3>
                <p class="text-white font-bold text-xs mt-0.5 opacity-70">
                    Click any point to open single-day deep-dive
                </p>
            </div>

            <div class="flex gap-2">
                <button type="button" id="view-line" data-view="line"
                        class="chart-view-btn px-3 py-1.5 rounded-lg text-xs font-bold transition border border-cyan-400 bg-cyan-500/20 text-white">
                    Line
                </button>
                <button type="button" id="view-bar" data-view="bar"
                        class="chart-view-btn px-3 py-1.5 rounded-lg text-xs font-bold transition border border-blue-700/50 bg-blue-900/40 text-white hover:bg-blue-800/50">
                    Bar
                </button>
            </div>
        </div>

        <div class="bg-blue-900/40 backdrop-blur rounded-xl border border-blue-700/50 p-4">
            <div style="position: relative; height: 340px;">
                <canvas id="sales-chart"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mt-4">
            <div class="bg-blue-900/40 rounded-xl p-3 border border-blue-700/50">
                <p class="text-white font-bold text-[10px] uppercase tracking-wider opacity-70">Period Total</p>
                <p class="text-white font-bold text-base mt-1" id="chart-total">TSh 0</p>
            </div>
            <div class="bg-blue-900/40 rounded-xl p-3 border border-blue-700/50">
                <p class="text-white font-bold text-[10px] uppercase tracking-wider opacity-70">Best Day</p>
                <p class="text-white font-bold text-base mt-1" id="chart-best">—</p>
            </div>
            <div class="bg-blue-900/40 rounded-xl p-3 border border-blue-700/50">
                <p class="text-white font-bold text-[10px] uppercase tracking-wider opacity-70">Daily Average</p>
                <p class="text-white font-bold text-base mt-1" id="chart-avg">TSh 0</p>
            </div>
            <div class="bg-blue-900/40 rounded-xl p-3 border border-blue-700/50">
                <p class="text-white font-bold text-[10px] uppercase tracking-wider opacity-70">Active Days</p>
                <p class="text-white font-bold text-base mt-1" id="chart-days">0</p>
            </div>
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- 🔍 SINGLE DAY DEEP-DIVE --}}
    {{-- ========================================================== --}}
    <div id="day-detail-section" class="fade-in-up d-6 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 mb-6 shadow-2xl border-2 border-cyan-500/40">

        {{-- Placeholder / Prompt --}}
        <div id="day-prompt">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-bold text-lg">Single Day Deep-Dive</h3>
                    <p class="text-white font-bold text-xs opacity-70">
                        Click any day on the chart or on the calendar to see detailed analysis
                    </p>
                </div>
            </div>

            <div class="bg-blue-900/40 border border-blue-700/50 rounded-xl p-6 text-center mt-3">
                <p class="text-white font-bold text-sm opacity-80">
                    💡 <span class="text-cyan-300">Tip:</span> Tap any data point on the chart above to load that day's performance.
                </p>
            </div>
        </div>

        {{-- Loading spinner --}}
        <div id="day-loading" class="hidden flex items-center justify-center py-16">
            <svg class="w-8 h-8 text-cyan-400 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        </div>

        {{-- Loaded content --}}
        <div id="day-content" class="hidden"></div>
    </div>

    {{-- ========================================================== --}}
    {{-- TOP PRODUCTS + TOP STORES --}}
    {{-- ========================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">

        <div class="fade-in-up d-5 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 shadow-2xl border border-blue-700/50">
            <h3 class="text-white font-bold text-base mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Top Products
            </h3>

            @forelse($topProducts as $i => $p)
                @php $maxQty = $topProducts->max('total_qty') ?: 1; @endphp
                <div class="bg-blue-900/40 rounded-xl p-3 border border-blue-700/50 mb-2">
                    <div class="flex items-center justify-between mb-2 gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-6 h-6 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white text-[10px] font-bold flex-shrink-0">
                                {{ $i + 1 }}
                            </span>
                            <p class="text-white font-bold text-xs truncate">{{ $p->name }}</p>
                        </div>
                        <span class="text-white font-bold text-xs flex-shrink-0">
                            {{ number_format($p->total_qty) }} sold
                        </span>
                    </div>
                    <div class="h-1.5 rounded-full bg-blue-950 overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-cyan-400 to-blue-500 rounded-full"
                             style="width: {{ min(100, ($p->total_qty / $maxQty) * 100) }}%"></div>
                    </div>
                    <p class="text-white font-bold text-[10px] mt-1.5 opacity-70">
                        Revenue: TSh {{ number_format($p->total_revenue, 0) }}
                    </p>
                </div>
            @empty
                <p class="text-white font-bold text-xs opacity-70 text-center py-6">No products sold in this period</p>
            @endforelse
        </div>

        <div class="fade-in-up d-5 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 shadow-2xl border border-blue-700/50">
            <h3 class="text-white font-bold text-base mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Top Stores
            </h3>

            @forelse($topStores as $i => $s)
                @php $maxRev = $topStores->max('total_revenue') ?: 1; @endphp
                <div class="bg-blue-900/40 rounded-xl p-3 border border-blue-700/50 mb-2">
                    <div class="flex items-center justify-between mb-2 gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-6 h-6 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-[10px] font-bold flex-shrink-0">
                                {{ $i + 1 }}
                            </span>
                            <p class="text-white font-bold text-xs truncate">{{ $s->name }}</p>
                        </div>
                        <span class="text-white font-bold text-xs flex-shrink-0">
                            {{ number_format($s->total_count) }} sales
                        </span>
                    </div>
                    <div class="h-1.5 rounded-full bg-blue-950 overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-purple-400 to-pink-500 rounded-full"
                             style="width: {{ min(100, ($s->total_revenue / $maxRev) * 100) }}%"></div>
                    </div>
                    <p class="text-white font-bold text-[10px] mt-1.5 opacity-70">
                        Revenue: TSh {{ number_format($s->total_revenue, 0) }}
                    </p>
                </div>
            @empty
                <p class="text-white font-bold text-xs opacity-70 text-center py-6">No store data in this period</p>
            @endforelse
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- PAYMENT METHODS --}}
    {{-- ========================================================== --}}
    <div class="fade-in-up d-6 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 mb-6 shadow-2xl border border-blue-700/50">
        <h3 class="text-white font-bold text-base mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            Payment Methods
        </h3>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            @forelse($paymentBreakdown as $pm)
                @php
                    $pct = $kpis['revenue'] > 0 ? ($pm->total / $kpis['revenue']) * 100 : 0;
                    $colors = [
                        'cash'   => ['from-green-500',  'to-emerald-500', 'text-green-300'],
                        'mobile' => ['from-blue-500',   'to-cyan-500',    'text-cyan-300'],
                        'card'   => ['from-purple-500', 'to-pink-500',    'text-purple-300'],
                    ];
                    $c = $colors[$pm->payment_method] ?? ['from-slate-500', 'to-slate-400', 'text-slate-300'];
                @endphp
                <div class="bg-blue-900/40 rounded-xl p-4 border border-blue-700/50">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br {{ $c[0] }} {{ $c[1] }} flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="text-white font-bold text-xs uppercase tracking-wide">
                            {{ $pm->payment_method ?? 'N/A' }}
                        </p>
                    </div>
                    <p class="text-white font-bold text-base">
                        TSh {{ number_format($pm->total, 0) }}
                    </p>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-white font-bold text-[10px] opacity-70">
                            {{ number_format($pm->cnt) }} txn
                        </span>
                        <span class="text-white font-bold text-[10px] {{ $c[2] }}">
                            {{ number_format($pct, 1) }}%
                        </span>
                    </div>
                    <div class="h-1 rounded-full bg-blue-950 overflow-hidden mt-2">
                        <div class="h-full bg-gradient-to-r {{ $c[0] }} {{ $c[1] }} rounded-full"
                             style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-white font-bold text-xs opacity-70 text-center py-6">No payment data</p>
            @endforelse
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- SCRIPTS --}}
    {{-- ========================================================== --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script>
        (function () {
            const labels = @json($labels);
            const values = @json($values);
            const counts = @json($counts);

            const canvas = document.getElementById('sales-chart');
            if (!canvas || !labels.length) return;

            let chartType = 'line';
            let chartInstance = null;

            const total = values.reduce((a, b) => a + b, 0);
            const avg   = total / values.length;
            let bestIdx = 0;
            values.forEach((v, i) => { if (v > values[bestIdx]) bestIdx = i; });
            const activeDays = values.filter(v => v > 0).length;

            document.getElementById('chart-total').textContent = 'TSh ' + Number(total).toLocaleString('en-US');
            document.getElementById('chart-best').textContent  = labels[bestIdx] + ' · TSh ' + Number(values[bestIdx]).toLocaleString('en-US');
            document.getElementById('chart-avg').textContent   = 'TSh ' + Number(Math.round(avg)).toLocaleString('en-US');
            document.getElementById('chart-days').textContent  = activeDays + ' / ' + labels.length;

            function buildChart() {
                if (chartInstance) chartInstance.destroy();

                const ctx = canvas.getContext('2d');
                const gradient = ctx.createLinearGradient(0, 0, 0, 340);
                gradient.addColorStop(0, 'rgba(56, 189, 248, 0.6)');
                gradient.addColorStop(1, 'rgba(56, 189, 248, 0.02)');

                const isLine = chartType === 'line';

                chartInstance = new Chart(ctx, {
                    type: isLine ? 'line' : 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Sales (TSh)',
                            data: values,
                            borderColor: '#38bdf8',
                            backgroundColor: isLine ? gradient : 'rgba(56, 189, 248, 0.7)',
                            borderWidth: isLine ? 2.5 : 1,
                            fill: isLine,
                            tension: 0.35,
                            borderRadius: isLine ? 0 : 6,
                            pointBackgroundColor: '#0ea5e9',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: isLine ? 5 : 0,
                            pointHoverRadius: 8,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        onClick: function (event, elements) {
                            if (elements.length > 0) {
                                const idx = elements[0].index;
                                loadDayDetail(labels[idx]);
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.95)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                padding: 12,
                                borderColor: '#38bdf8',
                                borderWidth: 1,
                                displayColors: false,
                                callbacks: {
                                    label: function (ctx) {
                                        return 'TSh ' + Number(ctx.parsed.y).toLocaleString('en-US');
                                    },
                                    afterLabel: function (ctx) {
                                        return counts[ctx.dataIndex] + ' transaction' + (counts[ctx.dataIndex] === 1 ? '' : 's')
                                             + '\n👉 Click for details';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { color: 'rgba(148, 163, 184, 0.1)' },
                                ticks: { color: '#cbd5e1', font: { weight: '700', size: 11 } }
                            },
                            y: {
                                grid: { color: 'rgba(148, 163, 184, 0.1)' },
                                ticks: {
                                    color: '#cbd5e1',
                                    font: { weight: '700', size: 11 },
                                    callback: function (v) {
                                        if (v >= 1000000) return (v / 1000000).toFixed(1) + 'M';
                                        if (v >= 1000)    return (v / 1000).toFixed(0) + 'k';
                                        return v;
                                    }
                                },
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            buildChart();

            document.querySelectorAll('.chart-view-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    chartType = this.dataset.view;
                    document.querySelectorAll('.chart-view-btn').forEach(function (b) {
                        b.classList.remove('bg-cyan-500/20', 'border-cyan-400');
                        b.classList.add('bg-blue-900/40', 'border-blue-700/50');
                    });
                    this.classList.remove('bg-blue-900/40', 'border-blue-700/50');
                    this.classList.add('bg-cyan-500/20', 'border-cyan-400');
                    buildChart();
                });
            });

            // =====================================================
            // SINGLE DAY DEEP-DIVE
            // =====================================================
            const daySection = document.getElementById('day-detail-section');
            const dayPrompt  = document.getElementById('day-prompt');
            const dayLoading = document.getElementById('day-loading');
            const dayContent = document.getElementById('day-content');

            let lastLoadedDay = null;

            window.loadDayDetail = function (label) {
                // Reconstruct the actual date (label is like "06 Oct")
                const year = new Date().getFullYear();
                const parsed = new Date(label + ' ' + year);
                if (isNaN(parsed.getTime())) return;

                const yyyy = parsed.getFullYear();
                const mm   = String(parsed.getMonth() + 1).padStart(2, '0');
                const dd   = String(parsed.getDate()).padStart(2, '0');
                const date = yyyy + '-' + mm + '-' + dd;

                lastLoadedDay = date;

                dayPrompt.classList.add('hidden');
                dayContent.classList.add('hidden');
                dayLoading.classList.remove('hidden');

                // Scroll to section
                daySection.scrollIntoView({ behavior: 'smooth', block: 'start' });

                const params = new URLSearchParams(window.location.search);
                params.set('date', date);

                const url = '{{ route("sales.day-detail") }}?' + params.toString();

                fetch(url, { headers: { 'Accept': 'application/json' } })
                    .then(r => r.json())
                    .then(data => {
                        dayLoading.classList.add('hidden');
                        renderDayDetail(data, date);
                    })
                    .catch(err => {
                        dayLoading.classList.add('hidden');
                        dayContent.classList.remove('hidden');
                        dayContent.innerHTML = '<p class="text-red-300 font-bold text-sm">Failed to load day detail: ' + err.message + '</p>';
                    });
            };

            function renderDayDetail(d, date) {
                const fmt = n => 'TSh ' + Number(n || 0).toLocaleString('en-US');
                const fmtNum = n => Number(n || 0).toLocaleString('en-US');

                const k = d.kpis || {};
                const compare = d.compare || {};
                const hourly = d.hourly || [];
                const topProducts = d.top_products || [];
                const paymentMix = d.payment_mix || [];
                const cashiers = d.cashiers || [];

                let html = '';

                // ===================== HEADER =====================
                html += '<div class="flex items-start justify-between gap-3 flex-wrap mb-5">';
                html += '  <div class="flex items-center gap-3">';
                html += '    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center shadow-lg">';
                html += '      <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>';
                html += '    </div>';
                html += '    <div>';
                html += '      <p class="text-white font-bold text-[10px] uppercase tracking-widest opacity-70">Single Day Analysis</p>';
                html += '      <h3 class="text-white font-bold text-xl">' + d.day_label + '</h3>';
                html += '      <p class="text-white font-bold text-xs opacity-70">' + (d.day_of_week || '') + ' · Week ' + (d.week_no || '') + ' of ' + (d.year || '') + '</p>';
                html += '    </div>';
                html += '  </div>';
                html += '  <button type="button" onclick="window.closeDayDetail()" class="bg-blue-900/40 hover:bg-blue-800/60 border border-blue-700/50 text-white font-bold text-xs px-3 py-2 rounded-lg transition">';
                html += '    ✕ Close';
                html += '  </button>';
                html += '</div>';

                // ===================== KPI ROW =====================
                const revArrow = compare.revenue_pct >= 0 ? '▲' : '▼';
                const revColor = compare.revenue_pct >= 0 ? 'text-green-300' : 'text-red-300';
                const cntArrow = compare.count_pct >= 0 ? '▲' : '▼';
                const cntColor = compare.count_pct >= 0 ? 'text-green-300' : 'text-red-300';

                html += '<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-5">';

                // Revenue
                html += '<div class="bg-blue-900/40 rounded-xl p-4 border border-blue-700/50">';
                html += '  <p class="text-white font-bold text-[10px] uppercase tracking-wider opacity-70">Revenue</p>';
                html += '  <p class="text-white font-bold text-xl mt-1">' + fmt(k.revenue) + '</p>';
                html += '  <p class="text-white font-bold text-[10px] mt-1 ' + revColor + '">';
                html += '    ' + revArrow + ' ' + Math.abs(compare.revenue_pct || 0).toFixed(1) + '% vs yesterday';
                html += '  </p>';
                html += '</div>';

                // Transactions
                html += '<div class="bg-blue-900/40 rounded-xl p-4 border border-blue-700/50">';
                html += '  <p class="text-white font-bold text-[10px] uppercase tracking-wider opacity-70">Transactions</p>';
                html += '  <p class="text-white font-bold text-xl mt-1">' + fmtNum(k.count) + '</p>';
                html += '  <p class="text-white font-bold text-[10px] mt-1 ' + cntColor + '">';
                html += '    ' + cntArrow + ' ' + Math.abs(compare.count_pct || 0).toFixed(1) + '% vs yesterday';
                html += '  </p>';
                html += '</div>';

                // Avg Sale
                html += '<div class="bg-blue-900/40 rounded-xl p-4 border border-blue-700/50">';
                html += '  <p class="text-white font-bold text-[10px] uppercase tracking-wider opacity-70">Avg. Sale</p>';
                html += '  <p class="text-white font-bold text-xl mt-1">' + fmt(k.avg_sale) + '</p>';
                html += '  <p class="text-white font-bold text-[10px] mt-1 opacity-70">per transaction</p>';
                html += '</div>';

                // Discounts
                html += '<div class="bg-blue-900/40 rounded-xl p-4 border border-blue-700/50">';
                html += '  <p class="text-white font-bold text-[10px] uppercase tracking-wider opacity-70">Discounts Given</p>';
                html += '  <p class="text-white font-bold text-xl mt-1">' + fmt(k.discount) + '</p>';
                html += '  <p class="text-white font-bold text-[10px] mt-1 opacity-70">';
                const gross = (k.revenue || 0) + (k.discount || 0);
                html += (gross > 0 ? ((k.discount / gross) * 100).toFixed(1) : 0) + '% of gross';
                html += '  </p>';
                html += '</div>';

                html += '</div>';

                // ===================== HOURLY BREAKDOWN =====================
                if (hourly.length) {
                    html += '<div class="bg-blue-900/40 rounded-xl p-4 border border-blue-700/50 mb-4">';
                    html += '  <p class="text-white font-bold text-sm mb-3 flex items-center gap-2">';
                    html += '    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                    html += '    Hourly Breakdown';
                    html += '  </p>';

                    const maxHourly = Math.max(...hourly.map(h => h.total || 0), 1);

                    html += '  <div class="grid grid-cols-12 gap-1 items-end h-32">';
                    hourly.forEach(function (h) {
                        const pct = maxHourly > 0 ? (h.total / maxHourly) * 100 : 0;
                        const height = Math.max(pct, h.total > 0 ? 6 : 2);
                        const bgClass = h.total > 0
                            ? 'bg-gradient-to-t from-cyan-500 to-blue-400'
                            : 'bg-blue-950';
                        html += '<div class="flex flex-col items-center justify-end h-full">';
                        html += '  <div class="w-full rounded-t ' + bgClass + ' transition-all" style="height: ' + height + '%;" title="' + h.label + ' — ' + fmt(h.total) + ' (' + h.count + ' txn)"></div>';
                        html += '  <p class="text-white font-bold text-[8px] opacity-60 mt-1">' + h.hour + '</p>';
                        html += '</div>';
                    });
                    html += '  </div>';

                    // Hour summary — peak hour
                    const peak = hourly.reduce((a, b) => (b.total > a.total ? b : a), hourly[0] || { total: 0, hour: 0 });
                    if (peak.total > 0) {
                        html += '  <p class="text-white font-bold text-xs mt-3 opacity-80">';
                        html += '    🕐 Peak hour: <span class="text-cyan-300">' + peak.label + '</span> — ' + fmt(peak.total);
                        html += '  </p>';
                    }
                    html += '</div>';
                }

                // ===================== TOP PRODUCTS + PAYMENT MIX =====================
                html += '<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">';

                // Top products
                html += '<div class="bg-blue-900/40 rounded-xl p-4 border border-blue-700/50">';
                html += '  <p class="text-white font-bold text-sm mb-3">🏆 Top Products of the Day</p>';

                if (topProducts.length) {
                    const maxQ = Math.max(...topProducts.map(p => p.qty), 1);
                    topProducts.forEach(function (p, i) {
                        html += '<div class="mb-2">';
                        html += '  <div class="flex items-center justify-between gap-2 mb-1">';
                        html += '    <div class="flex items-center gap-2 min-w-0">';
                        html += '      <span class="w-5 h-5 rounded bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white text-[9px] font-bold flex-shrink-0">' + (i + 1) + '</span>';
                        html += '      <span class="text-white font-bold text-xs truncate">' + p.name + '</span>';
                        html += '    </div>';
                        html += '    <span class="text-white font-bold text-xs flex-shrink-0">' + p.qty + ' ×</span>';
                        html += '  </div>';
                        html += '  <div class="h-1 rounded-full bg-blue-950 overflow-hidden mb-1">';
                        html += '    <div class="h-full bg-gradient-to-r from-cyan-400 to-blue-500 rounded-full" style="width: ' + ((p.qty / maxQ) * 100) + '%"></div>';
                        html += '  </div>';
                        html += '  <p class="text-white font-bold text-[10px] opacity-70">Revenue: ' + fmt(p.revenue) + '</p>';
                        html += '</div>';
                    });
                } else {
                    html += '<p class="text-white font-bold text-xs opacity-70 text-center py-4">No products sold</p>';
                }
                html += '</div>';

                // Payment mix
                html += '<div class="bg-blue-900/40 rounded-xl p-4 border border-blue-700/50">';
                html += '  <p class="text-white font-bold text-sm mb-3">💳 Payment Methods</p>';

                if (paymentMix.length) {
                    paymentMix.forEach(function (pm) {
                        const pct = k.revenue > 0 ? (pm.total / k.revenue) * 100 : 0;
                        const colors = {
                            cash:   ['from-green-500',  'to-emerald-500'],
                            mobile: ['from-blue-500',   'to-cyan-500'],
                            card:   ['from-purple-500', 'to-pink-500'],
                        };
                        const cc = colors[pm.method] || ['from-slate-500', 'to-slate-400'];

                        html += '<div class="mb-2">';
                        html += '  <div class="flex items-center justify-between gap-2 mb-1">';
                        html += '    <span class="text-white font-bold text-xs uppercase">' + pm.method + '</span>';
                        html += '    <span class="text-white font-bold text-xs">' + fmt(pm.total) + ' · ' + pct.toFixed(1) + '%</span>';
                        html += '  </div>';
                        html += '  <div class="h-1.5 rounded-full bg-blue-950 overflow-hidden">';
                        html += '    <div class="h-full bg-gradient-to-r ' + cc[0] + ' ' + cc[1] + ' rounded-full" style="width: ' + pct + '%"></div>';
                        html += '  </div>';
                        html += '  <p class="text-white font-bold text-[10px] opacity-70 mt-1">' + pm.count + ' txn</p>';
                        html += '</div>';
                    });
                } else {
                    html += '<p class="text-white font-bold text-xs opacity-70 text-center py-4">No payment data</p>';
                }
                html += '</div>';

                html += '</div>';

                // ===================== CASHIER PERFORMANCE =====================
                if (cashiers.length) {
                    html += '<div class="bg-blue-900/40 rounded-xl p-4 border border-blue-700/50">';
                    html += '  <p class="text-white font-bold text-sm mb-3">👤 Cashier Performance</p>';
                    html += '  <div class="overflow-x-auto">';
                    html += '    <table class="w-full text-xs">';
                    html += '      <thead><tr class="border-b border-blue-700/50">';
                    html += '        <th class="text-left text-white font-bold py-2">Cashier</th>';
                    html += '        <th class="text-right text-white font-bold py-2">Sales</th>';
                    html += '        <th class="text-right text-white font-bold py-2">Txn</th>';
                    html += '        <th class="text-right text-white font-bold py-2">Avg</th>';
                    html += '      </tr></thead><tbody>';
                    cashiers.forEach(function (c) {
                        html += '<tr class="border-b border-blue-700/30">';
                        html += '  <td class="py-2 text-white font-bold">' + c.name + '</td>';
                        html += '  <td class="py-2 text-right text-white font-bold">' + fmt(c.total) + '</td>';
                        html += '  <td class="py-2 text-right text-white font-bold">' + c.count + '</td>';
                        html += '  <td class="py-2 text-right text-white font-bold">' + fmt(c.avg) + '</td>';
                        html += '</tr>';
                    });
                    html += '      </tbody></table>';
                    html += '  </div>';
                    html += '</div>';
                }

                dayContent.innerHTML = html;
                dayContent.classList.remove('hidden');
            }

            window.closeDayDetail = function () {
                dayContent.classList.add('hidden');
                dayContent.innerHTML = '';
                dayPrompt.classList.remove('hidden');
                lastLoadedDay = null;
            };
        })();
    </script>

@endsection