@extends('layouts.app')

@section('title', 'Sale - ' . $sale->invoice_no)

@section('content')

    <a href="{{ route('sales.index') }}"
       class="inline-flex items-center gap-2 text-white hover:text-cyan-300 text-sm mb-4 font-bold">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Sales
    </a>

    {{-- ====================================================== --}}
    {{-- SALE HEADER --}}
    {{-- ====================================================== --}}
    <div class="fade-in-up d-1 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-6 mb-4 shadow-2xl border border-blue-700/50 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-full -mr-20 -mt-20 pointer-events-none"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center flex-shrink-0 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <h2 class="text-white font-bold text-xl truncate font-mono">
                        {{ $sale->invoice_no }}
                    </h2>
                    <p class="text-white font-bold text-sm">
                        @if($sale->created_at)
                            {{ $sale->created_at->format('d M Y, H:i') }}
                        @endif
                        · {{ $sale->store->name ?? '—' }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="bg-blue-900/40 backdrop-blur rounded-xl p-4 border border-blue-700/50">
                    <p class="text-white font-bold text-xs">Cashier</p>
                    <p class="text-white font-bold mt-1">
                        {{ $sale->cashier->full_name ?? 'Unknown' }}
                    </p>
                </div>

                <div class="bg-blue-900/40 backdrop-blur rounded-xl p-4 border border-blue-700/50">
                    <p class="text-white font-bold text-xs">Payment</p>
                    <p class="text-white font-bold mt-1 uppercase">
                        {{ $sale->payment_method }}
                    </p>
                </div>

                @if($sale->customer_name)
                    <div class="bg-blue-900/40 backdrop-blur rounded-xl p-4 col-span-2 border border-blue-700/50">
                        <p class="text-white font-bold text-xs">Customer</p>
                        <p class="text-white font-bold mt-1">{{ $sale->customer_name }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ====================================================== --}}
    {{-- ITEMS --}}
    {{-- ====================================================== --}}
    <div class="fade-in-up d-2 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-6 mb-4 shadow-2xl border border-blue-700/50">
        <h3 class="text-white font-bold mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            Items ({{ $sale->items->count() }})
        </h3>

        <div class="space-y-2">
            @forelse($sale->items as $item)
                <div class="flex items-center justify-between py-3 px-4 rounded-xl bg-blue-900/40 border border-blue-700/50 hover:bg-blue-800/40 transition">
                    <div class="min-w-0 flex-1">
                        <p class="text-white font-bold text-sm truncate">
                            {{ $item->product->name ?? 'Unknown' }}
                        </p>
                        <p class="text-white font-bold text-xs opacity-80 mt-0.5">
                            TSh {{ number_format((float) $item->unit_price, 0) }} × {{ $item->quantity }}
                        </p>
                    </div>
                    <p class="text-white font-bold text-sm ml-3 flex-shrink-0">
                        TSh {{ number_format((float) $item->line_total, 0) }}
                    </p>
                </div>
            @empty
                <div class="text-center py-6">
                    <p class="text-white font-bold text-sm opacity-70">No items in this sale</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ====================================================== --}}
    {{-- TOTALS --}}
    {{-- ====================================================== --}}
    <div class="fade-in-up d-3 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-6 mb-4 shadow-2xl border border-blue-700/50">
        <h3 class="text-white font-bold mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1"/>
            </svg>
            Totals
        </h3>

        <div class="space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-white font-bold">Subtotal</span>
                <span class="text-white font-bold">
                    TSh {{ number_format((float) $sale->subtotal, 0) }}
                </span>
            </div>

            @if($sale->discount_amount > 0)
                <div class="flex justify-between">
                    <span class="text-white font-bold">
                        Discount
                        @if($sale->discount_type === 'percent')
                            <span class="text-white/70">({{ $sale->discount_value }}%)</span>
                        @endif
                    </span>
                    <span class="text-red-300 font-bold">
                        -TSh {{ number_format((float) $sale->discount_amount, 0) }}
                    </span>
                </div>
            @endif

            <div class="flex justify-between text-lg font-bold border-t border-blue-700/50 pt-3 mt-3">
                <span class="text-white">TOTAL</span>
                <span class="text-cyan-400">
                    TSh {{ number_format((float) $sale->total, 0) }}
                </span>
            </div>
        </div>
    </div>

    {{-- ====================================================== --}}
    {{-- ACTIONS --}}
    {{-- ====================================================== --}}
    <div class="flex gap-3">
        <a href="{{ route('pos.receipt', $sale->id) }}"
           target="_blank"
           class="flex-1 bg-gradient-to-br from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-bold py-3 rounded-xl transition shadow-lg text-center flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            View Receipt
        </a>

        {{-- ✅ BACK button — now BLUE --}}
        <a href="{{ route('sales.index') }}"
           class="px-5 py-3 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 border border-blue-400/40 text-white font-bold text-sm transition shadow-lg flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>
    </div>

@endsection