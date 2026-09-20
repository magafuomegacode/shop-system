@extends('layouts.app')

@section('title', 'Dashboard - Cashier')

@section('content')

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 gap-3 sm:gap-4 mb-6">

        {{-- Today's Sales --}}
        <div class="stat-card fade-in-up d-1 bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-4 sm:p-5 shadow-2xl border border-stone-600/50">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-600 to-lime-600 flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-stone-300 text-xs mt-3">My Sales Today</p>
            <p class="text-white text-xl sm:text-2xl font-bold mt-1">
                TSh {{ number_format($todaySales, 0) }}
            </p>
            <p class="text-lime-400 text-xs mt-1 font-medium">Today</p>
        </div>

        {{-- Sales Count --}}
        <div class="stat-card fade-in-up d-2 bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-4 sm:p-5 shadow-2xl border border-stone-600/50">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-600 to-lime-600 flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
            </div>
            <p class="text-stone-300 text-xs mt-3">Sales Count</p>
            <p class="text-white text-xl sm:text-2xl font-bold mt-1">{{ $todayCount }}</p>
            <p class="text-lime-400 text-xs mt-1 font-medium">Today</p>
        </div>

    </div>

    {{-- Start Selling --}}
    <div class="fade-in-up d-3 bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-5 mb-6 relative overflow-hidden shadow-2xl border border-stone-600/50">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-yellow-500/20 to-lime-500/20 rounded-full -mr-16 -mt-16 pointer-events-none"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-600 to-lime-600 flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-semibold">Start Selling</h3>
                    <p class="text-stone-300 text-xs">Open POS to record a sale</p>
                </div>
            </div>

            <a href="{{ route('pos.index') }}"
               class="inline-flex items-center gap-2 bg-gradient-to-br from-yellow-600 to-lime-600 hover:from-yellow-700 hover:to-lime-700 text-white font-semibold px-6 py-3 rounded-xl transition shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Open POS
            </a>
        </div>
    </div>

    {{-- Recent Sales --}}
    <div class="fade-in-up d-4 bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-5 shadow-2xl border border-stone-600/50">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-white font-semibold">My Recent Sales</h3>
            <a href="{{ route('sales.index') }}" class="text-lime-300 text-xs hover:text-lime-200 transition font-medium">View all →</a>
        </div>

        @if ($recentSales->count() > 0)
            <div class="space-y-2">
                @foreach ($recentSales as $sale)
                    <a href="{{ route('sales.show', $sale) }}"
                       class="flex items-center justify-between py-3 px-3 rounded-xl bg-stone-800/60 hover:bg-stone-700/60 transition border border-stone-600/50">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-lg bg-lime-500/20 border border-lime-500/30 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-lime-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-white text-sm font-mono truncate font-medium">{{ $sale->invoice_no }}</p>
                                <p class="text-stone-300 text-xs truncate">
                                    {{ $sale->store->name }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 ml-2">
                            <p class="text-white text-sm font-semibold">
                                TSh {{ number_format($sale->total, 0) }}
                            </p>
                            <p class="text-stone-300 text-xs">{{ $sale->created_at->format('H:i') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-10">
                <svg class="w-16 h-16 text-stone-500/50 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6m4 6v-3m4 3V8M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <p class="text-stone-200 text-sm font-medium">No sales yet</p>
                <p class="text-stone-400/70 text-xs mt-1">Your sales will appear here</p>
            </div>
        @endif
    </div>

@endsection