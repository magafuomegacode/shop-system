@extends('layouts.app')

@section('title', $product->name)

@section('content')

    <a href="{{ route('products.index') }}"
       class="inline-flex items-center gap-2 text-white hover:text-cyan-300 text-sm mb-4 font-bold">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Products
    </a>

    @php
        // Resolve unit: product's own unit OR fall back to category config
        $unitLabel = $product->unit
            ?? (config("category_specs.{$product->category->name}.unit") ?? null);

        $unitFriendly = [
            'pcs'   => 'pcs',
            'set'   => 'set',
            'pair'  => 'pair',
            'pack'  => 'pack',
            'box'   => 'box',
            'bunch' => 'bunch',
            'm2'    => 'm²',
            'm'     => 'm',
            'kg'    => 'kg',
            'g'     => 'g',
            'L'     => 'L',
            'ml'    => 'ml',
        ][$unitLabel ?? ''] ?? $unitLabel;

        $unitColors = [
            'pcs'   => 'bg-blue-500/30 border-blue-400/40',
            'set'   => 'bg-purple-500/30 border-purple-400/40',
            'pack'  => 'bg-emerald-500/30 border-emerald-400/40',
            'box'   => 'bg-amber-500/30 border-amber-400/40',
            'bunch' => 'bg-pink-500/30 border-pink-400/40',
            'm2'    => 'bg-cyan-500/30 border-cyan-400/40',
            'm'     => 'bg-teal-500/30 border-teal-400/40',
        ][$unitLabel ?? ''] ?? 'bg-slate-500/30 border-slate-400/40';
    @endphp

    <div class="bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-6 mb-4 shadow-2xl border border-blue-700/50">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center flex-shrink-0 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div class="min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <h2 class="text-white font-bold text-xl truncate">{{ $product->name }}</h2>

                    {{-- ✅ UNIT BADGE --}}
                    @if($unitFriendly)
                        <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold uppercase border {{ $unitColors }} text-white flex-shrink-0"
                              title="Sold per {{ $unitFriendly }}">
                            {{ $unitFriendly }}
                        </span>
                    @endif
                </div>
                <p class="text-white font-bold text-sm truncate mt-1">
                    @if($product->store) {{ $product->store->name }} @endif
                    @if($product->category) · {{ $product->category->name }} @endif
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div class="bg-blue-900/40 backdrop-blur rounded-xl p-4 border border-blue-700/50">
                <p class="text-white font-bold text-xs">Selling Price</p>
                <p class="text-white font-bold text-lg mt-1">TSh {{ number_format((float) $product->selling_price, 0) }}</p>
                @if($unitFriendly)
                    <p class="text-white font-bold text-[10px] opacity-70 mt-0.5">per {{ $unitFriendly }}</p>
                @endif
            </div>
            <div class="bg-blue-900/40 backdrop-blur rounded-xl p-4 border border-blue-700/50">
                <p class="text-white font-bold text-xs">Cost Price</p>
                <p class="text-white font-bold text-lg mt-1">
                    {{ $product->cost_price ? 'TSh ' . number_format((float) $product->cost_price, 0) : '—' }}
                </p>
                @if($unitFriendly && $product->cost_price)
                    <p class="text-white font-bold text-[10px] opacity-70 mt-0.5">per {{ $unitFriendly }}</p>
                @endif
            </div>

            {{-- Category --}}
            <div class="bg-blue-900/40 backdrop-blur rounded-xl p-4 border border-blue-700/50">
                <p class="text-white font-bold text-xs">Category</p>
                @if($product->category)
                    <a href="{{ route('products.index', ['category' => $product->category->id]) }}"
                       class="inline-block mt-1 px-2 py-0.5 bg-purple-500/30 text-white font-bold rounded-md text-xs border border-purple-400/40 hover:bg-purple-500/50 transition">
                        {{ $product->category->name }}
                    </a>
                @else
                    <p class="text-white font-bold text-lg mt-1">—</p>
                @endif
            </div>

            {{-- Profit --}}
            @php
                $profit = ($product->selling_price ?? 0) - ($product->cost_price ?? 0);
            @endphp
            <div class="bg-blue-900/40 backdrop-blur rounded-xl p-4 border border-blue-700/50">
                <p class="text-white font-bold text-xs">Profit / Unit</p>
                <p class="font-bold text-lg mt-1 {{ $profit >= 0 ? 'text-green-400' : 'text-red-400' }}">
                    TSh {{ number_format($profit, 0) }}
                </p>
                @if($unitFriendly)
                    <p class="text-white font-bold text-[10px] opacity-70 mt-0.5">per {{ $unitFriendly }}</p>
                @endif
            </div>

            <div class="bg-blue-900/40 backdrop-blur rounded-xl p-4 border border-blue-700/50 col-span-2">
                <p class="text-white font-bold text-xs">Status</p>
                <p class="text-sm mt-1 font-bold {{ $product->is_active ? 'text-green-400' : 'text-red-400' }}">
                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                </p>
            </div>
        </div>

        {{-- ========================================================== --}}
        {{-- CATEGORY SPECS --}}
        {{-- ========================================================== --}}
        @if(!empty($product->specs) && is_array($product->specs))
            <div class="mt-6 pt-6 border-t border-blue-700/50">
                <h3 class="text-white font-bold mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Specifications
                </h3>

                <div class="grid grid-cols-2 gap-3">
                    @foreach($product->specs as $key => $value)
                        <div class="bg-blue-900/40 backdrop-blur rounded-xl p-4 border border-blue-700/50">
                            <p class="text-white font-bold text-xs uppercase tracking-wide">
                                {{ str_replace('_', ' ', $key) }}
                            </p>
                            <p class="text-white font-bold text-base mt-1">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Stock Info --}}
        <div class="mt-6 pt-6 border-t border-blue-700/50">
            <h3 class="text-white font-bold mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Stock
                @if($unitFriendly)
                    <span class="text-white font-bold text-[10px] px-2 py-0.5 rounded-md bg-cyan-500/30 border border-cyan-400/40 uppercase">
                        in {{ $unitFriendly }}
                    </span>
                @endif
            </h3>

            @php
                $stock = $product->stocks->firstWhere('store_id', $product->store_id);
                $stockQty = $stock ? (int) $stock->quantity : 0;
                $minQty = $stock ? (int) $stock->min_quantity : 5;
                $isOutOfStock = $stockQty <= 0;
                $isLowStock = $stockQty > 0 && $stockQty <= $minQty;
            @endphp

            <div class="grid grid-cols-2 gap-3">
                <div class="bg-blue-900/40 backdrop-blur rounded-xl p-4 border border-blue-700/50">
                    <p class="text-white font-bold text-xs">Quantity in Stock</p>
                    <p class="text-2xl font-bold mt-1
                        {{ $isOutOfStock ? 'text-red-400' : ($isLowStock ? 'text-yellow-400' : 'text-green-400') }}">
                        {{ $stockQty }}
                        @if($unitFriendly)
                            <span class="text-sm opacity-70">{{ $unitFriendly }}</span>
                        @endif
                    </p>
                </div>
                <div class="bg-blue-900/40 backdrop-blur rounded-xl p-4 border border-blue-700/50">
                    <p class="text-white font-bold text-xs">Min Alert Level</p>
                    <p class="text-2xl font-bold mt-1 text-white">
                        {{ $minQty }}
                        @if($unitFriendly)
                            <span class="text-sm opacity-70">{{ $unitFriendly }}</span>
                        @endif
                    </p>
                    <p class="text-white font-bold text-[10px] mt-1 opacity-70">Alert when below</p>
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
                        <p class="text-white font-bold text-sm mt-0.5">
                            Stock is empty. Please restock immediately.
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
                        <p class="text-white font-bold text-sm mt-0.5">
                            Stock is below minimum level ({{ $stockQty }} {{ $unitFriendly }} remaining).
                        </p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Store Info --}}
        @if($product->store)
            <div class="mt-6 pt-6 border-t border-blue-700/50">
                <h3 class="text-white font-bold mb-3">Store</h3>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center flex-shrink-0 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-white font-bold text-sm">{{ $product->store->name }}</p>
                        @if($product->store->location)
                            <p class="text-white font-bold text-xs">{{ $product->store->location }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Added By --}}
        <div class="mt-6 pt-6 border-t border-blue-700/50">
            <h3 class="text-white font-bold mb-3">Added by</h3>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold shadow-lg">
                    {{ strtoupper(substr($product->creator->full_name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <p class="text-white font-bold text-sm">{{ $product->creator->full_name ?? 'Unknown' }}</p>
                    <p class="text-white font-bold text-xs capitalize">
                        {{ $product->creator->role ?? '' }} ·
                        @if($product->created_at)
                            {{ $product->created_at->format('d M Y, H:i') }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit button --}}
    @if(auth()->user()->isAdmin() || auth()->user()->isOwner() || auth()->user()->isCashier())
        <div class="flex gap-3">
            <a href="{{ route('products.edit', $product) }}"
               class="bg-gradient-to-br from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-bold px-5 py-3 rounded-xl text-sm transition shadow-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Product
            </a>
        </div>
    @endif

@endsection