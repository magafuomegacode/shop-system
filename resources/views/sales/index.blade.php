@extends('layouts.app')

@section('title', 'Sales')

@section('content')

    {{-- Header --}}
    <div class="fade-in-up d-1 bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-5 mb-6 relative overflow-hidden shadow-2xl border border-stone-600/50">
        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-yellow-500/20 to-lime-500/20 rounded-full -mr-20 -mt-20 pointer-events-none"></div>
        <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-white text-xl sm:text-2xl font-bold">All Sales</h2>
                <p class="text-stone-300 text-xs sm:text-sm mt-1">
                    {{ $sales->total() }} {{ Str::plural('sale', $sales->total()) }} found
                </p>
            </div>
            <div class="flex items-center gap-2 flex-wrap w-full sm:w-auto">
                {{-- Print PDF --}}
                <a href="{{ route('sales.print', request()->only(['search', 'store', 'from', 'to'])) }}"
                   target="_blank"
                   class="flex-1 sm:flex-initial flex-shrink-0 bg-stone-800/60 border border-stone-600/50 hover:bg-stone-700/60 text-white text-sm font-semibold px-4 py-2.5 rounded-xl flex items-center justify-center gap-2 transition">
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span class="hidden sm:inline">Print PDF</span>
                </a>

                {{-- CSV --}}
                <a href="{{ route('sales.download.csv', request()->only(['search', 'store', 'from', 'to'])) }}"
                   class="flex-1 sm:flex-initial flex-shrink-0 bg-stone-800/60 border border-stone-600/50 hover:bg-stone-700/60 text-white text-sm font-semibold px-4 py-2.5 rounded-xl flex items-center justify-center gap-2 transition">
                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span class="hidden sm:inline">CSV</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Stats Summary --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 mb-6">

        <div class="fade-in-up d-2 bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-4 sm:p-5 shadow-2xl border border-stone-600/50">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-600 to-lime-600 flex items-center justify-center shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10"/>
                </svg>
            </div>
            <p class="text-stone-300 text-xs mt-3">Total Sales</p>
            <p class="text-white text-xl sm:text-2xl font-bold mt-1">{{ $totals['count'] }}</p>
        </div>

        <div class="fade-in-up d-3 bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-4 sm:p-5 shadow-2xl border border-stone-600/50">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-600 to-lime-600 flex items-center justify-center shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1"/>
                </svg>
            </div>
            <p class="text-stone-300 text-xs mt-3">All Time (Filtered)</p>
            <p class="text-white text-lg sm:text-xl font-bold mt-1">
                TSh {{ number_format($totals['sum'], 0) }}
            </p>
        </div>

        <div class="fade-in-up d-4 bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-4 sm:p-5 col-span-2 lg:col-span-1 shadow-2xl border border-stone-600/50">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-600 to-lime-600 flex items-center justify-center shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="grid grid-cols-2 gap-2 mt-3">
                <div>
                    <p class="text-stone-300 text-xs">Today</p>
                    <p class="text-white text-base font-bold mt-1">
                        TSh {{ number_format($totals['today'], 0) }}
                    </p>
                </div>
                <div>
                    <p class="text-stone-300 text-xs">This Month</p>
                    <p class="text-white text-base font-bold mt-1">
                        TSh {{ number_format($totals['month'], 0) }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="fade-in-up d-5 bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-4 mb-4 shadow-2xl border border-stone-600/50">
        <form method="GET" class="space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div class="relative">
                    <svg class="w-5 h-5 text-stone-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search invoice..."
                           class="w-full bg-stone-800/60 border border-stone-600/50 text-white placeholder-stone-400/60 pl-10 pr-4 py-2.5 rounded-xl outline-none text-sm focus:border-lime-400 focus:ring-2 focus:ring-lime-400/30 transition">
                </div>

                <select name="store" class="bg-stone-800/60 border border-stone-600/50 text-white px-4 py-2.5 rounded-xl outline-none text-sm appearance-none focus:border-lime-400">
                    <option value="" class="bg-stone-800 text-white">All stores</option>
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}" class="bg-stone-800 text-white" {{ request('store') == $store->id ? 'selected' : '' }}>
                            {{ $store->name }}
                        </option>
                    @endforeach
                </select>

                <input type="date" name="from" value="{{ request('from') }}"
                       class="bg-stone-800/60 border border-stone-600/50 text-white px-4 py-2.5 rounded-xl outline-none text-sm focus:border-lime-400">

                <input type="date" name="to" value="{{ request('to') }}"
                       class="bg-stone-800/60 border border-stone-600/50 text-white px-4 py-2.5 rounded-xl outline-none text-sm focus:border-lime-400">
            </div>

            <div class="flex flex-wrap gap-2">
                <button type="submit"
                        class="bg-gradient-to-br from-yellow-600 to-lime-600 hover:from-yellow-700 hover:to-lime-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition shadow-lg">
                    Filter
                </button>

                @if(request()->hasAny(['search', 'store', 'from', 'to']))
                    <a href="{{ route('sales.index') }}"
                       class="bg-stone-800/60 hover:bg-stone-700/60 border border-stone-600/50 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Sales Table --}}
    @if($sales->count() > 0)
        <div class="fade-in-up d-6 bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl shadow-2xl border border-stone-600/50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="bg-stone-900/80 border-b-2 border-lime-600/60">
                            <th class="text-left text-stone-200 font-bold text-xs uppercase tracking-wider px-4 py-3 border-r border-stone-600/50">Invoice</th>
                            <th class="text-left text-stone-200 font-bold text-xs uppercase tracking-wider px-4 py-3 border-r border-stone-600/50">Product</th>
                            <th class="text-left text-stone-200 font-bold text-xs uppercase tracking-wider px-4 py-3 border-r border-stone-600/50">Store</th>
                            <th class="text-left text-stone-200 font-bold text-xs uppercase tracking-wider px-4 py-3 border-r border-stone-600/50">Cashier</th>
                            <th class="text-left text-stone-200 font-bold text-xs uppercase tracking-wider px-4 py-3 border-r border-stone-600/50">Date</th>
                            <th class="text-left text-stone-200 font-bold text-xs uppercase tracking-wider px-4 py-3 border-r border-stone-600/50">Payment</th>
                            <th class="text-right text-stone-200 font-bold text-xs uppercase tracking-wider px-4 py-3 border-r border-stone-600/50">Discount</th>
                            <th class="text-right text-stone-200 font-bold text-xs uppercase tracking-wider px-4 py-3 border-r border-stone-600/50">Total</th>
                            <th class="text-center text-stone-200 font-bold text-xs uppercase tracking-wider px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                            <tr class="border-b border-stone-600/30 hover:bg-stone-700/40 transition">
                                <td class="px-4 py-3 border-r border-stone-600/30">
                                    <span class="text-white font-mono font-semibold text-xs">{{ $sale->invoice_no }}</span>
                                </td>
                                <td class="px-4 py-3 border-r border-stone-600/30">
                                    <div class="flex flex-col gap-0.5">
                                        @forelse($sale->items as $item)
                                            <span class="text-white text-xs truncate max-w-[150px]">
                                                {{ $item->product->name ?? 'Unknown' }}
                                                @if($item->quantity > 1)
                                                    <span class="text-stone-400 text-[10px]">×{{ $item->quantity }}</span>
                                                @endif
                                            </span>
                                        @empty
                                            <span class="text-stone-500 text-xs">—</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-4 py-3 border-r border-stone-600/30">
                                    <span class="inline-block px-2 py-0.5 bg-lime-500/20 text-lime-200 rounded-md font-medium text-xs border border-lime-500/30">
                                        {{ $sale->store->name ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 border-r border-stone-600/30">
                                    <span class="text-white text-xs">{{ $sale->cashier->full_name ?? 'Unknown' }}</span>
                                </td>
                                <td class="px-4 py-3 border-r border-stone-600/30">
                                    <span class="text-stone-300 text-xs">
                                        {{ $sale->created_at ? $sale->created_at->format('d M Y, H:i') : '—' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 border-r border-stone-600/30">
                                    @if($sale->payment_method)
                                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-lime-500/20 text-lime-200 font-semibold uppercase border border-lime-500/30">
                                            {{ $sale->payment_method }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right border-r border-stone-600/30">
                                    @if($sale->discount_amount > 0)
                                        <span class="text-red-300 text-xs font-semibold">
                                            -TSh {{ number_format((float) $sale->discount_amount, 0) }}
                                        </span>
                                    @else
                                        <span class="text-stone-500 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right border-r border-stone-600/30">
                                    <span class="text-white font-bold text-sm">
                                        TSh {{ number_format((float) $sale->total, 0) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('sales.show', $sale) }}"
                                       class="inline-flex items-center gap-1 bg-lime-500/20 hover:bg-lime-500/30 border border-lime-500/40 text-lime-200 text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">{{ $sales->links() }}</div>
    @else
        <div class="bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-10 text-center shadow-2xl border border-stone-600/50">
            <svg class="w-16 h-16 text-stone-500/50 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 17v-6m4 6v-3m4 3V8M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            <p class="text-stone-300 text-sm font-medium">No sales found</p>
            <a href="{{ route('pos.index') }}" class="inline-block mt-4 text-lime-300 hover:text-lime-200 text-sm font-semibold">
                Start selling →
            </a>
        </div>
    @endif

@endsection