@extends('layouts.app')

@section('title', 'Stock Movement Analytics')

@section('content')

    {{-- ========================================================== --}}
    {{-- HEADER --}}
    {{-- ========================================================== --}}
    <div class="fade-in-up d-1 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 mb-6 relative overflow-hidden shadow-2xl border border-blue-700/50">
        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-full -mr-20 -mt-20 pointer-events-none"></div>

        <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-white font-bold text-xl sm:text-2xl flex items-center gap-2">
                    <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Stock Movement Analytics
                </h2>
                <p class="text-white font-bold text-xs sm:text-sm mt-1 opacity-80">
                    Uchambuzi wa bidhaa zilizoingia na kutoka store
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-white font-bold text-[10px] uppercase tracking-wider opacity-70">Period</span>
                <span class="text-white font-bold text-sm bg-cyan-500/30 border border-cyan-400/40 px-3 py-1.5 rounded-lg">
                    {{ request('from') ? \Carbon\Carbon::parse(request('from'))->format('d M') : 'Last 30 days' }}
                    @if(request('to'))
                        · {{ \Carbon\Carbon::parse(request('to'))->format('d M') }}
                    @endif
                </span>
            </div>
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- FILTER BAR --}}
    {{-- ========================================================== --}}
    <div class="fade-in-up d-2 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 mb-6 shadow-2xl border border-blue-700/50">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <select name="store" class="bg-blue-900/40 border border-blue-700/50 text-white font-bold px-4 py-2.5 rounded-xl outline-none text-sm appearance-none focus:border-cyan-400">
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
                <a href="{{ route('stock-movements.index', ['from' => now()->subDays(6)->format('Y-m-d'), 'to' => now()->format('Y-m-d')]) }}"
                   class="flex-1 bg-blue-900/40 hover:bg-blue-800/50 border border-blue-700/50 text-white font-bold text-xs px-2 py-2.5 rounded-xl transition text-center">7D</a>
                <a href="{{ route('stock-movements.index', ['from' => now()->subDays(29)->format('Y-m-d'), 'to' => now()->format('Y-m-d')]) }}"
                   class="flex-1 bg-blue-900/40 hover:bg-blue-800/50 border border-blue-700/50 text-white font-bold text-xs px-2 py-2.5 rounded-xl transition text-center">30D</a>
                <a href="{{ route('stock-movements.index', ['from' => now()->startOfMonth()->format('Y-m-d'), 'to' => now()->endOfMonth()->format('Y-m-d')]) }}"
                   class="flex-1 bg-blue-900/40 hover:bg-blue-800/50 border border-blue-700/50 text-white font-bold text-xs px-2 py-2.5 rounded-xl transition text-center">MTD</a>
            </div>
        </form>
    </div>

    {{-- ========================================================== --}}
    {{-- KPI CARDS --}}
    {{-- ========================================================== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">

        {{-- Total Movements --}}
        <div class="fade-in-up d-2 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 shadow-2xl border border-blue-700/50">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                    </svg>
                </div>
            </div>
            <p class="text-white font-bold text-[10px] uppercase tracking-wider mt-3 opacity-70">Total Movements</p>
            <p class="text-white font-bold text-xl mt-1">{{ number_format($totals['count']) }}</p>
            <p class="text-white font-bold text-[10px] mt-0.5 opacity-60">Filtered period</p>
        </div>

        {{-- Stock In --}}
        <div class="fade-in-up d-3 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 shadow-2xl border border-green-500/40">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
            </div>
            <p class="text-white font-bold text-[10px] uppercase tracking-wider mt-3 opacity-70">Stock In</p>
            <p class="text-white font-bold text-xl mt-1 text-green-300">{{ number_format($totals['in']) }}</p>
            <p class="text-white font-bold text-[10px] mt-0.5 opacity-60">Units received</p>
        </div>

        {{-- Stock Out --}}
        <div class="fade-in-up d-4 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 shadow-2xl border border-red-500/40">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-rose-500 flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/>
                    </svg>
                </div>
            </div>
            <p class="text-white font-bold text-[10px] uppercase tracking-wider mt-3 opacity-70">Stock Out</p>
            <p class="text-white font-bold text-xl mt-1 text-red-300">{{ number_format($totals['out']) }}</p>
            <p class="text-white font-bold text-[10px] mt-0.5 opacity-60">Units sold</p>
        </div>

        {{-- Net Change --}}
        <div class="fade-in-up d-4 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 shadow-2xl border border-cyan-500/40">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                </div>
            </div>
            @php
                $net = ($totals['in'] ?? 0) - ($totals['out'] ?? 0);
            @endphp
            <p class="text-white font-bold text-[10px] uppercase tracking-wider mt-3 opacity-70">Net Change</p>
            <p class="text-white font-bold text-xl mt-1 {{ $net >= 0 ? 'text-green-300' : 'text-red-300' }}">
                {{ $net >= 0 ? '+' : '' }}{{ number_format($net) }}
            </p>
            <p class="text-white font-bold text-[10px] mt-0.5 opacity-60">In minus Out</p>
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- MOVEMENT TREND CHART --}}
    {{-- ========================================================== --}}
    <div class="fade-in-up d-5 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 mb-6 shadow-2xl border border-blue-700/50">
        <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
            <div>
                <h3 class="text-white font-bold text-base flex items-center gap-2">
                    <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                    Movement Trend
                </h3>
                <p class="text-white font-bold text-xs mt-0.5 opacity-70">
                    Stock In vs Stock Out — siku kwa siku
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
            <div style="position: relative; height: 320px;">
                <canvas id="movement-chart"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mt-4">
            <div class="bg-blue-900/40 rounded-xl p-3 border border-blue-700/50">
                <p class="text-white font-bold text-[10px] uppercase tracking-wider opacity-70">Peak In Day</p>
                <p class="text-white font-bold text-base mt-1" id="chart-peak-in">—</p>
            </div>
            <div class="bg-blue-900/40 rounded-xl p-3 border border-blue-700/50">
                <p class="text-white font-bold text-[10px] uppercase tracking-wider opacity-70">Peak Out Day</p>
                <p class="text-white font-bold text-base mt-1" id="chart-peak-out">—</p>
            </div>
            <div class="bg-blue-900/40 rounded-xl p-3 border border-blue-700/50">
                <p class="text-white font-bold text-[10px] uppercase tracking-wider opacity-70">Avg In / Day</p>
                <p class="text-white font-bold text-base mt-1" id="chart-avg-in">0</p>
            </div>
            <div class="bg-blue-900/40 rounded-xl p-3 border border-blue-700/50">
                <p class="text-white font-bold text-[10px] uppercase tracking-wider opacity-70">Avg Out / Day</p>
                <p class="text-white font-bold text-base mt-1" id="chart-avg-out">0</p>
            </div>
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- TOP MOVING PRODUCTS + MOVEMENT BREAKDOWN --}}
    {{-- ========================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">

        {{-- Top Moving Products --}}
        <div class="fade-in-up d-6 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 shadow-2xl border border-blue-700/50">
            <h3 class="text-white font-bold text-base mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                Top Moving Products
            </h3>

            @php
                $topProducts = $topProducts ?? collect();
                $maxTotal = $topProducts->max('total_qty') ?: 1;
            @endphp

            @forelse($topProducts as $i => $p)
                @php
                    $inQty   = (int) $p->in_qty;
                    $outQty  = (int) $p->out_qty;
                    $totalQty = (int) $p->total_qty;
                    $net = $inQty - $outQty;

                    $inPct  = $totalQty > 0 ? ($inQty / $totalQty) * 100 : 0;
                    $outPct = $totalQty > 0 ? ($outQty / $totalQty) * 100 : 0;
                @endphp
                <div class="bg-blue-900/40 rounded-xl p-3 border border-blue-700/50 mb-2">
                    <div class="flex items-center justify-between mb-2 gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-6 h-6 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white text-[10px] font-bold flex-shrink-0">
                                {{ $i + 1 }}
                            </span>
                            <p class="text-white font-bold text-xs truncate">{{ $p->name }}</p>
                        </div>
                        <span class="text-white font-bold text-xs flex-shrink-0">
                            {{ number_format($totalQty) }} total
                        </span>
                    </div>

                    <div class="h-2 rounded-full bg-blue-950 overflow-hidden flex">
                        <div class="h-full bg-gradient-to-r from-green-500 to-emerald-500" style="width: {{ $inPct }}%"></div>
                        <div class="h-full bg-gradient-to-r from-red-500 to-rose-500" style="width: {{ $outPct }}%"></div>
                    </div>

                    <div class="flex items-center justify-between mt-1.5 text-[10px] font-bold">
                        <span class="text-green-300">IN: {{ number_format($inQty) }}</span>
                        <span class="text-white opacity-70">Net: {{ $net >= 0 ? '+' : '' }}{{ number_format($net) }}</span>
                        <span class="text-red-300">OUT: {{ number_format($outQty) }}</span>
                    </div>
                </div>
            @empty
                <p class="text-white font-bold text-xs opacity-70 text-center py-6">
                    Hakuna movements katika kipindi hiki
                </p>
            @endforelse
        </div>

        {{-- Movement Breakdown by Store --}}
        <div class="fade-in-up d-6 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 shadow-2xl border border-blue-700/50">
            <h3 class="text-white font-bold text-base mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Movement by Store
            </h3>

            @php
                $byStore = $byStore ?? collect();
                $maxStoreTotal = $byStore->max('total_qty') ?: 1;
            @endphp

            @forelse($byStore as $i => $s)
                @php
                    $inQty   = (int) $s->in_qty;
                    $outQty  = (int) $s->out_qty;
                    $totalQty = (int) $s->total_qty;
                @endphp
                <div class="bg-blue-900/40 rounded-xl p-3 border border-blue-700/50 mb-2">
                    <div class="flex items-center justify-between mb-2 gap-2">
                        <p class="text-white font-bold text-xs truncate">{{ $s->name }}</p>
                        <span class="text-white font-bold text-xs flex-shrink-0">
                            {{ number_format($totalQty) }} units
                        </span>
                    </div>
                    <div class="h-1.5 rounded-full bg-blue-950 overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-cyan-400 to-blue-500 rounded-full"
                             style="width: {{ min(100, ($totalQty / $maxStoreTotal) * 100) }}%"></div>
                    </div>
                    <div class="flex items-center justify-between mt-1.5 text-[10px] font-bold">
                        <span class="text-green-300">IN: {{ number_format($inQty) }}</span>
                        <span class="text-red-300">OUT: {{ number_format($outQty) }}</span>
                    </div>
                </div>
            @empty
                <p class="text-white font-bold text-xs opacity-70 text-center py-6">
                    Hakuna data ya store
                </p>
            @endforelse
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- INVENTORY ALERTS --}}
    {{-- ========================================================== --}}
    @if(isset($lowStock) && ($lowStock->count() > 0 || (isset($outOfStock) && $outOfStock->count() > 0)))
        <div class="fade-in-up d-7 bg-gradient-to-br from-yellow-950 via-blue-950 to-blue-950 rounded-2xl p-5 mb-6 shadow-2xl border-2 border-yellow-500/50 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-yellow-500/10 to-orange-500/10 rounded-full -mr-16 -mt-16 pointer-events-none"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4 flex-wrap">
                    <div class="w-10 h-10 rounded-xl bg-yellow-500/30 border border-yellow-400/40 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-base">⚠️ Inventory Alerts</h3>
                        <p class="text-white font-bold text-xs opacity-80">
                            Bidhaa zinazohitaji uangalizi wa haraka
                        </p>
                    </div>
                </div>

                @if(isset($outOfStock) && $outOfStock->count() > 0)
                    <div class="mb-4">
                        <p class="text-white font-bold text-xs uppercase tracking-wider mb-2 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                            Zilizoisha kabisa ({{ $outOfStock->count() }})
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                            @foreach($outOfStock as $s)
                                <div class="bg-red-500/20 border border-red-400/40 rounded-xl p-3 flex items-center justify-between gap-2">
                                    <div class="min-w-0">
                                        <p class="text-white font-bold text-xs truncate">
                                            {{ $s->product->name ?? 'Unknown' }}
                                        </p>
                                        <p class="text-white font-bold text-[10px] opacity-80">
                                            {{ $s->store->name ?? '—' }}
                                        </p>
                                    </div>
                                    <span class="text-red-300 font-bold text-xs flex-shrink-0">0 left</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(isset($lowStock) && $lowStock->count() > 0)
                    <div>
                        <p class="text-white font-bold text-xs uppercase tracking-wider mb-2 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                            Zinazokaribia kuisha ({{ $lowStock->count() }})
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                            @foreach($lowStock as $s)
                                @php
                                    $unitRaw = $s->product->unit ?? null;
                                    if (!$unitRaw && $s->product && $s->product->category) {
                                        $cfg = config('category_specs.' . $s->product->category->name, []);
                                        $unitRaw = $cfg['unit'] ?? ($cfg['units'][0] ?? null);
                                    }
                                    $unitLabels = [
                                        'pcs' => 'PCS', 'set' => 'SET', 'pack' => 'PACK',
                                        'box' => 'BOX', 'bunch' => 'BUNCH', 'm2' => 'M²',
                                        'kg' => 'KG', 'litre' => 'L', 'ml' => 'ML',
                                    ];
                                    $unitDisplay = $unitRaw ? ($unitLabels[strtolower($unitRaw)] ?? strtoupper($unitRaw)) : '';
                                @endphp
                                <div class="bg-yellow-500/20 border border-yellow-400/40 rounded-xl p-3 flex items-center justify-between gap-2">
                                    <div class="min-w-0">
                                        <p class="text-white font-bold text-xs truncate">
                                            {{ $s->product->name ?? 'Unknown' }}
                                        </p>
                                        <p class="text-white font-bold text-[10px] opacity-80">
                                            {{ $s->store->name ?? '—' }}
                                        </p>
                                    </div>
                                    <span class="text-yellow-300 font-bold text-xs flex-shrink-0">
                                        {{ $s->quantity }} {{ $unitDisplay }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- ========================================================== --}}
    {{-- ALL STOCK IN STORE --}}
    {{-- ========================================================== --}}
    @if(isset($allStock) && $allStock->count() > 0)
        <div class="fade-in-up d-7 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 mb-6 shadow-2xl border border-blue-700/50">
            <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                <div>
                    <h3 class="text-white font-bold text-base flex items-center gap-2">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Stock Iliyobaki Store
                    </h3>
                    <p class="text-white font-bold text-xs mt-0.5 opacity-70">
                        Orodha ya bidhaa zote zilizopo kwa sasa
                    </p>
                </div>
                <span class="text-white font-bold text-xs px-3 py-1.5 rounded-lg bg-cyan-500/30 border border-cyan-400/40">
                    {{ $allStock->count() }} products
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="bg-blue-950/80 border-b-2 border-cyan-500/60">
                            <th class="text-left text-white font-bold text-xs uppercase tracking-wider px-3 py-2 border-r border-blue-700/50">#</th>
                            <th class="text-left text-white font-bold text-xs uppercase tracking-wider px-3 py-2 border-r border-blue-700/50">Product</th>
                            <th class="text-left text-white font-bold text-xs uppercase tracking-wider px-3 py-2 border-r border-blue-700/50">Category</th>
                            <th class="text-left text-white font-bold text-xs uppercase tracking-wider px-3 py-2 border-r border-blue-700/50">Store</th>
                            <th class="text-center text-white font-bold text-xs uppercase tracking-wider px-3 py-2 border-r border-blue-700/50">Stock</th>
                            <th class="text-center text-white font-bold text-xs uppercase tracking-wider px-3 py-2 border-r border-blue-700/50">Min</th>
                            <th class="text-center text-white font-bold text-xs uppercase tracking-wider px-3 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allStock as $i => $s)
                            @php
                                $qty = (int) $s->quantity;
                                $min = (int) $s->min_quantity;

                                $statusLabel = 'Healthy';
                                $statusClass = 'bg-green-500/30 text-white border-green-400/40';

                                if ($qty <= 0) {
                                    $statusLabel = 'OUT';
                                    $statusClass = 'bg-red-500/30 text-white border-red-400/40';
                                } elseif ($qty <= $min) {
                                    $statusLabel = 'LOW';
                                    $statusClass = 'bg-yellow-500/30 text-white border-yellow-400/40';
                                }

                                $unitRaw = $s->product->unit ?? null;
                                if (!$unitRaw && $s->product && $s->product->category) {
                                    $cfg = config('category_specs.' . $s->product->category->name, []);
                                    $unitRaw = $cfg['unit'] ?? ($cfg['units'][0] ?? null);
                                }
                                $unitLabels = [
                                    'pcs' => 'PCS', 'set' => 'SET', 'pack' => 'PACK',
                                    'box' => 'BOX', 'bunch' => 'BUNCH', 'm2' => 'M²',
                                    'kg' => 'KG', 'litre' => 'L', 'ml' => 'ML',
                                ];
                                $unitDisplay = $unitRaw ? ($unitLabels[strtolower($unitRaw)] ?? strtoupper($unitRaw)) : '';
                            @endphp
                            <tr class="border-b border-blue-700/30 hover:bg-blue-800/40 transition">
                                <td class="px-3 py-2 text-white font-bold text-xs border-r border-blue-700/30">{{ $i + 1 }}</td>
                                <td class="px-3 py-2 border-r border-blue-700/30">
                                    <p class="text-white font-bold text-sm truncate max-w-[200px]">
                                        {{ $s->product->name ?? 'Unknown' }}
                                    </p>
                                    @if($s->product && $s->product->size)
                                        <p class="text-white/70 font-bold text-[10px]">
                                            Size: {{ rtrim(rtrim(number_format((float) $s->product->size, 2, '.', ''), '0'), '.') }}
                                        </p>
                                    @endif
                                </td>
                                <td class="px-3 py-2 border-r border-blue-700/30">
                                    <span class="px-2 py-0.5 bg-purple-500/30 text-white font-bold rounded-md text-xs border border-purple-400/40">
                                        {{ $s->product->category->name ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 border-r border-blue-700/30">
                                    <span class="px-2 py-0.5 bg-cyan-500/30 text-white font-bold rounded-md text-xs border border-cyan-500/40">
                                        {{ $s->store->name ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 text-center border-r border-blue-700/30">
                                    <span class="inline-block px-2 py-0.5 rounded-md text-xs font-bold {{ $statusClass }}">
                                        {{ $qty }}
                                        @if($unitDisplay)
                                            <span class="text-[9px] opacity-80">{{ $unitDisplay }}</span>
                                        @endif
                                    </span>
                                </td>
                                <td class="px-3 py-2 text-center border-r border-blue-700/30">
                                    <span class="text-white font-bold text-xs">{{ $min }}</span>
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- ========================================================== --}}
    {{-- MOVEMENT HISTORY --}}
    {{-- ========================================================== --}}
    @if(isset($movements) && $movements->count() > 0)
        <div class="fade-in-up d-8 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 mb-4 shadow-2xl border border-blue-700/50">
            <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                <div>
                    <h3 class="text-white font-bold text-base flex items-center gap-2">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Movement History
                    </h3>
                    <p class="text-white font-bold text-xs mt-0.5 opacity-70">
                        Bidhaa zote zilizoingia na kutoka
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="bg-blue-950/80 border-b-2 border-cyan-500/60">
                            <th class="text-left text-white font-bold text-xs uppercase tracking-wider px-3 py-2 border-r border-blue-700/50">Date & Time</th>
                            <th class="text-left text-white font-bold text-xs uppercase tracking-wider px-3 py-2 border-r border-blue-700/50">Type</th>
                            <th class="text-left text-white font-bold text-xs uppercase tracking-wider px-3 py-2 border-r border-blue-700/50">Product</th>
                            <th class="text-left text-white font-bold text-xs uppercase tracking-wider px-3 py-2 border-r border-blue-700/50">Store</th>
                            <th class="text-center text-white font-bold text-xs uppercase tracking-wider px-3 py-2 border-r border-blue-700/50">Qty</th>
                            <th class="text-left text-white font-bold text-xs uppercase tracking-wider px-3 py-2 border-r border-blue-700/50">By</th>
                            <th class="text-left text-white font-bold text-xs uppercase tracking-wider px-3 py-2">Reference</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movements as $m)
                            @php
                                $isIn  = in_array($m->movement_type, ['in', 'adjustment']) && $m->quantity > 0;

                                $typeLabel = [
                                    'in'         => 'IN',
                                    'sale'       => 'SALE',
                                    'adjustment' => 'ADJ',
                                ][$m->movement_type] ?? strtoupper($m->movement_type);

                                $badgeClass = $isIn
                                    ? 'bg-green-500/30 text-white border-green-400/40'
                                    : 'bg-red-500/30 text-white border-red-400/40';

                                $qtyDisplay = $m->quantity > 0 ? '+' . $m->quantity : $m->quantity;

                                $unitRaw = $m->product->unit ?? null;
                                if (!$unitRaw && $m->product && $m->product->category) {
                                    $cfg = config('category_specs.' . $m->product->category->name, []);
                                    $unitRaw = $cfg['unit'] ?? ($cfg['units'][0] ?? null);
                                }
                                $unitLabels = [
                                    'pcs' => 'PCS', 'set' => 'SET', 'pair' => 'PAIR',
                                    'pack' => 'PACK', 'box' => 'BOX', 'bunch' => 'BUNCH',
                                    'm2' => 'M²', 'm' => 'M', 'kg' => 'KG', 'g' => 'G',
                                    'litre' => 'L', 'l' => 'L', 'ml' => 'ML',
                                ];
                                $unitDisplay = $unitRaw ? ($unitLabels[strtolower($unitRaw)] ?? strtoupper($unitRaw)) : '';
                            @endphp
                            <tr class="border-b border-blue-700/30 hover:bg-blue-800/40 transition">
                                <td class="px-3 py-2 border-r border-blue-700/30">
                                    <p class="text-white font-bold text-xs">
                                        {{ $m->created_at ? $m->created_at->format('d M Y') : '—' }}
                                    </p>
                                    <p class="text-white/70 font-bold text-[10px]">
                                        {{ $m->created_at ? $m->created_at->format('H:i') : '' }}
                                    </p>
                                </td>
                                <td class="px-3 py-2 border-r border-blue-700/30">
                                    <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold uppercase border {{ $badgeClass }}">
                                        {{ $typeLabel }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 border-r border-blue-700/30">
                                    <p class="text-white font-bold text-xs truncate max-w-[200px]">
                                        {{ $m->product->name ?? 'Unknown' }}
                                    </p>
                                </td>
                                <td class="px-3 py-2 border-r border-blue-700/30">
                                    <span class="px-2 py-0.5 bg-cyan-500/30 text-white font-bold rounded-md text-[10px] border border-cyan-500/40">
                                        {{ $m->store->name ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 text-center border-r border-blue-700/30">
                                    <span class="inline-block px-2 py-0.5 rounded-md text-xs font-bold {{ $badgeClass }}">
                                        {{ $qtyDisplay }}
                                        @if($unitDisplay)
                                            <span class="text-[9px] opacity-80">{{ $unitDisplay }}</span>
                                        @endif
                                    </span>
                                </td>
                                <td class="px-3 py-2 border-r border-blue-700/30">
                                    <p class="text-white font-bold text-xs truncate max-w-[120px]">
                                        {{ $m->user->full_name ?? 'System' }}
                                    </p>
                                    <p class="text-white/70 font-bold text-[10px] capitalize">
                                        {{ $m->user->role ?? '' }}
                                    </p>
                                </td>
                                <td class="px-3 py-2">
                                    <p class="text-white font-bold text-[10px] font-mono">
                                        {{ $m->reference ?? '—' }}
                                    </p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $movements->links() }}</div>
        </div>
    @endif

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script>
        (function () {
            const labels      = @json($chartLabels ?? []);
            const inValues    = @json($chartIn ?? []);
            const outValues   = @json($chartOut ?? []);

            const canvas = document.getElementById('movement-chart');
            if (!canvas || !labels.length) return;

            let chartType = 'line';
            let chartInstance = null;

            const totalIn = inValues.reduce((a, b) => a + b, 0);
            const totalOut = outValues.reduce((a, b) => a + b, 0);

            let peakInIdx = 0;
            inValues.forEach((v, i) => { if (v > inValues[peakInIdx]) peakInIdx = i; });

            let peakOutIdx = 0;
            outValues.forEach((v, i) => { if (v > outValues[peakOutIdx]) peakOutIdx = i; });

            const avgIn  = totalIn / (inValues.length || 1);
            const avgOut = totalOut / (outValues.length || 1);

            document.getElementById('chart-peak-in').textContent  = labels[peakInIdx] + ' · ' + Number(inValues[peakInIdx]).toLocaleString('en-US');
            document.getElementById('chart-peak-out').textContent = labels[peakOutIdx] + ' · ' + Number(outValues[peakOutIdx]).toLocaleString('en-US');
            document.getElementById('chart-avg-in').textContent   = Number(Math.round(avgIn)).toLocaleString('en-US');
            document.getElementById('chart-avg-out').textContent  = Number(Math.round(avgOut)).toLocaleString('en-US');

            function buildChart() {
                if (chartInstance) chartInstance.destroy();

                const ctx = canvas.getContext('2d');

                const gradientIn = ctx.createLinearGradient(0, 0, 0, 320);
                gradientIn.addColorStop(0, 'rgba(34, 197, 94, 0.6)');
                gradientIn.addColorStop(1, 'rgba(34, 197, 94, 0.02)');

                const gradientOut = ctx.createLinearGradient(0, 0, 0, 320);
                gradientOut.addColorStop(0, 'rgba(239, 68, 68, 0.6)');
                gradientOut.addColorStop(1, 'rgba(239, 68, 68, 0.02)');

                const isLine = chartType === 'line';

                chartInstance = new Chart(ctx, {
                    type: isLine ? 'line' : 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Stock In',
                                data: inValues,
                                borderColor: '#22c55e',
                                backgroundColor: isLine ? gradientIn : 'rgba(34, 197, 94, 0.7)',
                                borderWidth: isLine ? 2.5 : 1,
                                fill: isLine,
                                tension: 0.35,
                                borderRadius: isLine ? 0 : 4,
                                pointBackgroundColor: '#22c55e',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: isLine ? 3 : 0,
                                pointHoverRadius: 6,
                            },
                            {
                                label: 'Stock Out',
                                data: outValues,
                                borderColor: '#ef4444',
                                backgroundColor: isLine ? gradientOut : 'rgba(239, 68, 68, 0.7)',
                                borderWidth: isLine ? 2.5 : 1,
                                fill: isLine,
                                tension: 0.35,
                                borderRadius: isLine ? 0 : 4,
                                pointBackgroundColor: '#ef4444',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: isLine ? 3 : 0,
                                pointHoverRadius: 6,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                labels: {
                                    color: '#fff',
                                    font: { weight: '700', size: 12 },
                                    boxWidth: 12,
                                    boxHeight: 12,
                                    padding: 15
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.95)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                padding: 12,
                                borderColor: '#38bdf8',
                                borderWidth: 1,
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
                                        if (v >= 1000) return (v / 1000).toFixed(0) + 'k';
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
        })();
    </script>

@endsection