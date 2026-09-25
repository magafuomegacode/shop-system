@extends('layouts.app')

@section('title', 'Sale - ' . $sale->invoice_no)

@section('content')

    <a href="{{ route('sales.index') }}"
       class="inline-flex items-center gap-2 text-blue-300 hover:text-white text-sm mb-4 font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Sales
    </a>

    {{-- Sale header --}}
    <div class="bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-6 mb-4 shadow-2xl border border-blue-700/50">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center flex-shrink-0 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <h2 class="text-white text-xl font-bold truncate font-mono">
                    {{ $sale->invoice_no }}
                </h2>
                <p class="text-blue-200 text-sm">
                    @if($sale->created_at)
                        {{ $sale->created_at->format('d M Y, H:i') }}
                    @endif
                    · {{ $sale->store->name ?? '—' }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div class="bg-blue-900/40 backdrop-blur rounded-xl p-4 border border-blue-700/50">
                <p class="text-blue-200 text-xs">Cashier</p>
                <p class="text-white font-semibold mt-1">
                    {{ $sale->cashier->full_name ?? 'Unknown' }}
                </p>
            </div>
            <div class="bg-blue-900/40 backdrop-blur rounded-xl p-4 border border-blue-700/50">
                <p class="text-blue-200 text-xs">Payment</p>
                <p class="text-white font-semibold mt-1 uppercase">
                    {{ $sale->payment_method }}
                </p>
            </div>
            @if($sale->customer_name)
                <div class="bg-blue-900/40 backdrop-blur rounded-xl p-4 col-span-2 border border-blue-700/50">
                    <p class="text-blue-200 text-xs">Customer</p>
                    <p class="text-white font-semibold mt-1">{{ $sale->customer_name }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Items --}}
    <div class="bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-6 mb-4 shadow-2xl border border-blue-700/50">
        <h3 class="text-white font-semibold mb-4">
            Items ({{ $sale->items->count() }})
        </h3>

        <div class="space-y-2">
            @foreach($sale->items as $item)
                <div class="flex items-center justify-between py-3 px-3 rounded-xl bg-blue-900/40 border border-blue-700/50">
                    <div class="min-w-0 flex-1">
                        <p class="text-white font-semibold text-sm truncate">
                            {{ $item->product->name ?? 'Unknown' }}
                        </p>
                        <p class="text-blue-200 text-xs">
                            TSh {{ number_format((float) $item->unit_price, 0) }} × {{ $item->quantity }}
                        </p>
                    </div>
                    <p class="text-white font-bold text-sm ml-3">
                        TSh {{ number_format((float) $item->line_total, 0) }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Totals --}}
    <div class="bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-6 mb-4 shadow-2xl border border-blue-700/50">
        <h3 class="text-white font-semibold mb-4">Totals</h3>

        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-blue-200">Subtotal</span>
                <span class="text-white font-medium">
                    TSh {{ number_format((float) $sale->subtotal, 0) }}
                </span>
            </div>
            @if($sale->discount_amount > 0)
                <div class="flex justify-between text-red-300">
                    <span>
                        Discount
                        @if($sale->discount_type === 'percent')
                            ({{ $sale->discount_value }}%)
                        @endif
                    </span>
                    <span class="font-medium">
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

    {{-- Actions --}}
    <div class="flex gap-3">
        <a href="{{ route('pos.receipt', $sale->id) }}"
           target="_blank"
           class="flex-1 bg-gradient-to-br from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-semibold py-3 rounded-xl transition shadow-lg text-center">
            View Receipt
        </a>
        <a href="{{ route('sales.index') }}"
           class="px-5 py-3 rounded-xl bg-blue-900/40 hover:bg-blue-800/50 border border-blue-700/50 text-white text-sm font-medium transition">
            Back
        </a>
    </div>

@endsection