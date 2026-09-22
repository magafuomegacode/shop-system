@extends('layouts.app')

@section('title', $product->name)

@section('content')

    <a href="{{ route('products.index') }}"
       class="inline-flex items-center gap-2 text-lime-400 hover:text-lime-300 text-sm mb-4 font-bold">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Products
    </a>

    <div class="bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-6 mb-4 shadow-2xl border border-stone-600/50">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-yellow-600 to-lime-600 flex items-center justify-center flex-shrink-0 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div class="min-w-0">
                <h2 class="text-white text-xl font-bold truncate">{{ $product->name }}</h2>
                <p class="text-white text-sm truncate">
                    @if($product->store) {{ $product->store->name }} · @endif
                    {{ $product->sku ?? 'No SKU' }}
                </p>
            </div>
        </div>

        @php
            // Size display — remove trailing zeros
            $sizeDisplay = '—';
            if ($product->size !== null && $product->size !== '') {
                $sizeDisplay = rtrim(rtrim(number_format((float) $product->size, 2, '.', ''), '0'), '.');
            }
        @endphp

        <div class="grid grid-cols-2 gap-3">
            <div class="bg-stone-800/60 backdrop-blur rounded-xl p-4 border border-stone-600/50">
                <p class="text-white text-xs">Selling Price</p>
                <p class="text-white font-bold text-lg mt-1">TSh {{ number_format((float) $product->selling_price, 0) }}</p>
            </div>
            <div class="bg-stone-800/60 backdrop-blur rounded-xl p-4 border border-stone-600/50">
                <p class="text-white text-xs">Cost Price</p>
                <p class="text-white font-bold text-lg mt-1">
                    {{ $product->cost_price ? 'TSh ' . number_format((float) $product->cost_price, 0) : '—' }}
                </p>
            </div>
            <div class="bg-stone-800/60 backdrop-blur rounded-xl p-4 border border-stone-600/50">
                <p class="text-white text-xs">Unit</p>
                <p class="text-white font-semibold mt-1">{{ $product->unit ?? '—' }}</p>
            </div>
            <div class="bg-stone-800/60 backdrop-blur rounded-xl p-4 border border-stone-600/50">
                <p class="text-white text-xs">Size</p>
                <p class="text-white font-bold text-lg mt-1">{{ $sizeDisplay }}</p>
            </div>
            <div class="bg-stone-800/60 backdrop-blur rounded-xl p-4 border border-stone-600/50 col-span-2">
                <p class="text-white text-xs">Status</p>
                <p class="text-sm mt-1 font-semibold {{ $product->is_active ? 'text-green-400' : 'text-red-400' }}">
                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                </p>
            </div>
        </div>

        {{-- Stock Info --}}
        <div class="mt-6 pt-6 border-t border-stone-600/50">
            <h3 class="text-white font-semibold mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Stock
            </h3>

            @php
                $stock = $product->stocks->firstWhere('store_id', $product->store_id);
                $stockQty = $stock ? (int) $stock->quantity : 0;
                $minQty = $stock ? (int) $stock->min_quantity : 5;
                $isOutOfStock = $stockQty <= 0;
                $isLowStock = $stockQty > 0 && $stockQty <= $minQty;
            @endphp

            <div class="grid grid-cols-2 gap-3">
                <div class="bg-stone-800/60 backdrop-blur rounded-xl p-4 border border-stone-600/50">
                    <p class="text-white text-xs">Quantity in Stock</p>
                    <p class="text-2xl font-bold mt-1
                        {{ $isOutOfStock ? 'text-red-400' : ($isLowStock ? 'text-yellow-400' : 'text-green-400') }}">
                        {{ $stockQty }}
                    </p>
                    <p class="text-xs text-white/70 mt-1">{{ $product->unit ?? 'pcs' }}</p>
                </div>
                <div class="bg-stone-800/60 backdrop-blur rounded-xl p-4 border border-stone-600/50">
                    <p class="text-white text-xs">Min Alert Level</p>
                    <p class="text-2xl font-bold mt-1 text-white">{{ $minQty }}</p>
                    <p class="text-xs text-white/70 mt-1">Alert when below</p>
                </div>
            </div>

            {{-- 🔴 OUT OF STOCK --}}
            @if($isOutOfStock)
                <div class="mt-4 bg-gradient-to-r from-red-600 to-red-500 border-2 border-red-400 shadow-lg shadow-red-500/50 px-5 py-4 rounded-xl flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-white font-bold text-base uppercase tracking-wide">Out of Stock!</p>
                        <p class="text-white text-sm mt-0.5">
                            Stock is empty (0 {{ $product->unit ?? 'pcs' }}). Please restock immediately.
                        </p>
                    </div>
                </div>
            @endif

            {{-- 🟡 LOW STOCK --}}
            @if($isLowStock)
                <div class="mt-4 bg-gradient-to-r from-yellow-600 to-yellow-500 border-2 border-yellow-400 shadow-lg shadow-yellow-500/40 px-5 py-4 rounded-xl flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-white font-bold text-base uppercase tracking-wide">Low Stock Alert!</p>
                        <p class="text-white text-sm mt-0.5">
                            Stock is below the minimum level ({{ $stockQty }} {{ $product->unit ?? 'pcs' }} remaining).
                        </p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Store Info --}}
        @if($product->store)
            <div class="mt-6 pt-6 border-t border-stone-600/50">
                <h3 class="text-white font-semibold mb-3">Store</h3>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-yellow-600 to-lime-600 flex items-center justify-center flex-shrink-0 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-white font-medium text-sm">{{ $product->store->name }}</p>
                        @if($product->store->location)
                            <p class="text-white text-xs">{{ $product->store->location }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Added By --}}
        <div class="mt-6 pt-6 border-t border-stone-600/50">
            <h3 class="text-white font-semibold mb-3">Added by</h3>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-yellow-600 to-lime-600 flex items-center justify-center text-white font-bold shadow-lg">
                    {{ strtoupper(substr($product->creator->full_name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <p class="text-white font-medium text-sm">{{ $product->creator->full_name ?? 'Unknown' }}</p>
                    <p class="text-white text-xs capitalize">
                        {{ $product->creator->role ?? '' }} ·
                        @if($product->created_at)
                            {{ $product->created_at->format('d M Y, H:i') }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ Edit button visible to Admin, Owner AND Cashier --}}
    @if(auth()->user()->isAdmin() || auth()->user()->isOwner() || auth()->user()->isCashier())
        <div class="flex gap-3">
            <a href="{{ route('products.edit', $product) }}"
               class="bg-gradient-to-br from-yellow-600 to-lime-600 hover:from-yellow-700 hover:to-lime-700 text-white px-5 py-3 rounded-xl text-sm font-bold transition shadow-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Product
            </a>
        </div>
    @endif

@endsection