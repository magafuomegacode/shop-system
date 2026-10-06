@extends('layouts.app')

@section('title', 'Products')

@section('content')

    {{-- Header --}}
    <div class="fade-in-up bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 mb-6 relative z-30 shadow-2xl border border-blue-700/50" style="overflow: visible;">
        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-full -mr-20 -mt-20 pointer-events-none"></div>
        <div class="relative z-20 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-white font-bold text-xl sm:text-2xl">Products</h2>
                <p class="text-white font-bold text-xs sm:text-sm mt-1">
                    {{ $products->total() }} {{ Str::plural('product', $products->total()) }} found
                </p>
            </div>

            <div class="flex items-center gap-2 flex-wrap w-full sm:w-auto">
                <button type="button" id="download-trigger"
                        class="flex-1 sm:flex-initial flex-shrink-0 bg-blue-900/40 backdrop-blur border border-blue-700/50 hover:bg-blue-800/50 text-white font-bold text-sm px-4 py-2.5 rounded-xl flex items-center justify-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Download</span>
                </button>

                <a href="{{ route('stores.index') }}"
                   class="flex-1 sm:flex-initial flex-shrink-0 bg-gradient-to-br from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-bold text-sm px-4 py-2.5 rounded-xl flex items-center justify-center gap-2 transition shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span>Go to Stores</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Success --}}
    @if(session('success'))
        <div class="fade-in-up d-2 bg-green-500/20 border border-green-500/40 text-white font-bold px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ============================================================== --}}
    {{-- CATEGORY FILTER — search + show more/less --}}
    {{-- ============================================================== --}}
    @if(isset($categories) && $categories->count() > 0)
        <div class="fade-in-up d-2 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 mb-4 shadow-2xl border border-blue-700/50">
            <div class="flex items-center justify-between gap-3 mb-3 flex-wrap">
                <p class="text-white font-bold text-xs uppercase tracking-wider">Browse by Category</p>

                <div class="flex items-center gap-2">
                    @if(request('category'))
                        <a href="{{ route('products.index', request()->except('category')) }}"
                           class="text-white font-bold text-xs hover:text-cyan-300 transition">
                            ✕ Clear category
                        </a>
                    @endif
                </div>
            </div>

            {{-- Category search input --}}
            <div class="relative mb-3">
                <svg class="w-4 h-4 text-white absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text"
                       id="category-search"
                       placeholder="Search categories..."
                       autocomplete="off"
                       class="w-full pl-9 pr-4 py-2 rounded-xl outline-none text-sm bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white font-bold placeholder-white/40 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                <button type="button"
                        id="category-search-clear"
                        class="hidden absolute right-2 top-1/2 -translate-y-1/2 w-6 h-6 rounded-md bg-blue-800/60 hover:bg-blue-700/60 flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Chips container --}}
            <div id="category-chips" class="flex flex-wrap gap-2">
                @foreach($categories as $index => $cat)
                    @php
                        $isActive  = (int) request('category') === (int) $cat->id;
                        $isHidden  = $index >= 12;
                    @endphp

                    <a href="{{ route('products.index', array_merge(request()->except('category'), ['category' => $cat->id])) }}"
                       data-category-name="{{ strtolower($cat->name) }}"
                       class="category-chip px-3 py-1.5 rounded-lg text-xs font-bold border transition
                              {{ $isActive
                                    ? 'bg-gradient-to-br from-blue-500 to-cyan-500 text-white border-cyan-400 shadow-lg'
                                    : 'bg-blue-900/40 text-white border-blue-700/50 hover:bg-blue-800/50' }}
                              {{ $isHidden ? 'hidden extra-category' : '' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach

                {{-- No results message (for search) --}}
                <p id="category-no-results"
                   class="hidden text-white font-bold text-xs italic opacity-70 py-2">
                    No categories match your search
                </p>
            </div>

            {{-- Show more / less button --}}
            @if($categories->count() > 12)
                <button type="button"
                        id="category-toggle"
                        class="mt-3 w-full sm:w-auto px-4 py-2 rounded-xl bg-blue-900/40 hover:bg-blue-800/50 border border-blue-700/50 text-white font-bold text-xs transition flex items-center justify-center gap-2">
                    <svg id="toggle-icon" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                    <span id="toggle-text">Show all {{ $categories->count() }} categories</span>
                </button>
            @endif
        </div>
    @endif

    {{-- ============================================================== --}}
    {{-- ACTIVE FILTER BANNER --}}
    {{-- ============================================================== --}}
    @php
        $activeCategory = request('category') ? $categories->firstWhere('id', (int) request('category')) : null;
    @endphp

    @if($activeCategory)
        <div class="fade-in-up d-2 bg-gradient-to-br from-purple-950 via-purple-900 to-blue-950 rounded-2xl p-4 mb-4 shadow-2xl border border-purple-500/50">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-purple-500/30 border border-purple-400/40 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M7 7h.01M7 3h5a2 2 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-white font-bold text-xs uppercase tracking-wide">Filtered by category</p>
                        <p class="text-white font-bold text-base truncate">{{ $activeCategory->name }}</p>
                    </div>
                </div>

                <a href="{{ route('products.index', request()->except('category')) }}"
                   class="flex-shrink-0 bg-purple-500/30 hover:bg-purple-500/50 border border-purple-400/40 text-white font-bold text-xs px-4 py-2 rounded-xl transition">
                    Show All Products
                </a>
            </div>
        </div>
    @endif

    {{-- Filters --}}
    <div class="fade-in-up d-2 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 mb-4 shadow-2xl border border-blue-700/50">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif

            <div class="flex-1 relative">
                <svg class="w-5 h-5 text-white absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by name..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl outline-none text-sm bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white font-bold placeholder-white/40 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
            </div>

            <select name="store" class="px-4 py-2.5 rounded-xl outline-none text-sm appearance-none min-w-[150px] bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white font-bold focus:border-cyan-400">
                <option value="" class="bg-blue-950 text-white">All stores</option>
                @foreach($stores as $store)
                    <option value="{{ $store->id }}" class="bg-blue-950 text-white" {{ request('store') == $store->id ? 'selected' : '' }}>
                        {{ $store->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                    class="bg-gradient-to-br from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-bold text-sm px-5 py-2.5 rounded-xl transition shadow-lg">
                Filter
            </button>

            @if(request()->hasAny(['search', 'store', 'category']))
                <a href="{{ route('products.index') }}"
                   class="bg-blue-900/40 backdrop-blur hover:bg-blue-800/50 text-white font-bold text-sm px-5 py-2.5 rounded-xl transition text-center border border-blue-700/50">
                    Clear All
                </a>
            @endif
        </form>
    </div>

    {{-- Bordered Table --}}
    @if($products->count() > 0)
        <div class="fade-in-up bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl overflow-hidden shadow-2xl border border-blue-700/50">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-blue-950 border-b-2 border-cyan-500/60">
                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wide border-r border-blue-700/50 w-12">#</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wide border-r border-blue-700/50">Product</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wide border-r border-blue-700/50">Category</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wide border-r border-blue-700/50">Store</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-white uppercase tracking-wide border-r border-blue-700/50">Price</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-white uppercase tracking-wide border-r border-blue-700/50">Stock</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wide border-r border-blue-700/50">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wide border-r border-blue-700/50">Added By</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-white uppercase tracking-wide w-32">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $i => $product)
                            @php
                                $stock = $product->stocks->firstWhere('store_id', $product->store_id);
                                $stockQty = $stock ? (int) $stock->quantity : 0;
                                $isLowStock = $stock && $stock->quantity <= $stock->min_quantity;
                            @endphp
                            <tr class="border-b border-blue-700/30 hover:bg-blue-800/40 transition {{ $i % 2 === 0 ? 'bg-blue-900/40' : 'bg-blue-800/20' }}">
                                <td class="px-4 py-3 text-sm text-white font-bold border-r border-blue-700/30">
                                    {{ $products->firstItem() + $i }}
                                </td>

                                <td class="px-4 py-3 border-r border-blue-700/30">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center flex-shrink-0 shadow-lg">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-white font-bold text-sm truncate max-w-[200px]">
                                                {{ $product->name }}
                                            </p>
                                            @if(!empty($product->specs) && is_array($product->specs))
                                                @php
                                                    $firstSpec = array_slice($product->specs, 0, 1, true);
                                                    $firstKey = array_key_first($firstSpec);
                                                    $firstVal = $firstSpec[$firstKey] ?? null;
                                                @endphp
                                                @if($firstVal)
                                                    <p class="text-white/70 font-bold text-[10px] truncate max-w-[200px]">
                                                        {{ ucwords(str_replace('_', ' ', $firstKey)) }}: {{ $firstVal }}
                                                    </p>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-3 border-r border-blue-700/30">
                                    @if($product->category)
                                        <a href="{{ route('products.index', ['category' => $product->category->id]) }}"
                                           class="inline-block px-2 py-0.5 bg-purple-500/30 text-white font-bold rounded-md text-xs border border-purple-400/40 hover:bg-purple-500/50 transition"
                                           title="Filter by {{ $product->category->name }}">
                                            {{ $product->category->name }}
                                        </a>
                                    @else
                                        <span class="text-white/60 font-bold text-xs">—</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 border-r border-blue-700/30">
                                    @if($product->store)
                                        <span class="px-2 py-0.5 bg-cyan-500/30 text-white font-bold rounded-md text-xs border border-cyan-500/40">
                                            {{ $product->store->name }}
                                        </span>
                                    @else
                                        <span class="text-white/60 font-bold text-xs">—</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-sm text-white font-bold text-right border-r border-blue-700/30">
                                    TSh {{ number_format((float) $product->selling_price, 0) }}
                                </td>

                                <td class="px-4 py-3 text-center border-r border-blue-700/30">
                                    <span class="inline-block px-2 py-0.5 rounded-md text-xs font-bold {{ $isLowStock ? 'bg-red-500/30 text-white border border-red-400/40' : 'bg-green-500/30 text-white border border-green-400/40' }}">
                                        {{ $stockQty }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 border-r border-blue-700/30">
                                    @if($product->is_active)
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-green-500/30 text-white border border-green-400/40">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-red-500/30 text-white border border-red-400/40">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 border-r border-blue-700/30">
                                    <div class="text-xs">
                                        <p class="text-white font-bold truncate max-w-[120px]">
                                            {{ $product->creator->full_name ?? 'Unknown' }}
                                        </p>
                                        <p class="text-white/70 font-bold text-[10px]">
                                            {{ $product->created_at ? $product->created_at->format('d M Y') : '—' }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('products.show', $product) }}"
                                           class="w-7 h-7 rounded-lg bg-blue-900/40 hover:bg-blue-800/50 border border-blue-700/50 flex items-center justify-center transition"
                                           title="View">
                                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        @if(auth()->user()->isAdmin() || auth()->user()->isOwner() || auth()->user()->isCashier())
                                            <a href="{{ route('products.edit', $product) }}"
                                               class="w-7 h-7 rounded-lg bg-blue-900/40 hover:bg-blue-800/50 border border-blue-700/50 flex items-center justify-center transition"
                                               title="Edit">
                                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                        @endif

                                        @if(auth()->user()->isAdmin() || auth()->user()->isOwner())
                                            <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline"
                                                  onsubmit="return confirm('Delete {{ $product->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="w-7 h-7 rounded-lg bg-red-500/30 hover:bg-red-500/50 border border-red-400/40 flex items-center justify-center transition"
                                                        title="Delete">
                                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">{{ $products->links() }}</div>
    @else
        <div class="bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-10 text-center shadow-2xl border border-blue-700/50">
            <svg class="w-16 h-16 text-blue-500/40 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>

            @if($activeCategory)
                <p class="text-white font-bold text-sm">No products in "{{ $activeCategory->name }}"</p>
                <p class="text-white/70 font-bold text-xs mt-1">Try another category or view all products</p>
                <a href="{{ route('products.index') }}"
                   class="inline-block mt-4 text-white font-bold hover:text-cyan-300 text-sm">
                    Show all products →
                </a>
            @else
                <p class="text-white font-bold text-sm">No products yet</p>
                <p class="text-white/70 font-bold text-xs mt-1">Add products by clicking a store first</p>
                <a href="{{ route('stores.index') }}"
                   class="inline-block mt-4 text-white font-bold hover:text-cyan-300 text-sm">
                    Go to Stores →
                </a>
            @endif
        </div>
    @endif

    {{-- ===== Download Modal ===== --}}
    <div id="download-modal"
         class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4"
         style="background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);">

        <div class="bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden border border-blue-700/50">
            <div class="px-5 py-4 border-b border-blue-700/50 flex items-center justify-between bg-blue-950">
                <div>
                    <h3 class="text-white font-bold text-base">Download Products</h3>
                    <p class="text-white font-bold text-xs mt-0.5">Choose a format</p>
                </div>
                <button type="button"
                        onclick="closeDownloadModal()"
                        class="w-8 h-8 rounded-lg bg-blue-900/40 hover:bg-blue-800/50 flex items-center justify-center transition border border-blue-700/50">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-3">
                <a href="{{ route('products.download.report', request()->only(['search', 'store', 'category'])) }}"
                   target="_blank"
                   onclick="closeDownloadModal()"
                   class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-blue-800/40 transition">
                    <div class="w-10 h-10 rounded-xl bg-red-500/30 border border-red-400/40 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="text-left flex-1">
                        <p class="text-white font-bold text-sm">PDF Report</p>
                        <p class="text-white font-bold text-xs">Printable · Grouped by store</p>
                    </div>
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>

                <a href="{{ route('products.download.csv', request()->only(['search', 'store', 'category'])) }}"
                   onclick="closeDownloadModal()"
                   class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-blue-800/40 transition">
                    <div class="w-10 h-10 rounded-xl bg-green-500/30 border border-green-400/40 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="text-left flex-1">
                        <p class="text-white font-bold text-sm">Excel (CSV)</p>
                        <p class="text-white font-bold text-xs">For spreadsheets</p>
                    </div>
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <script>
        // =====================================================
        // Download modal
        // =====================================================
        (function () {
            const trigger = document.getElementById('download-trigger');
            const modal = document.getElementById('download-modal');

            if (!trigger || !modal) return;

            trigger.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });

            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    closeDownloadModal();
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeDownloadModal();
                }
            });

            window.closeDownloadModal = function () {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            };
        })();

        // =====================================================
        // Category: search + show more/less
        // =====================================================
        (function () {
            const searchInput   = document.getElementById('category-search');
            const searchClear   = document.getElementById('category-search-clear');
            const chips         = Array.from(document.querySelectorAll('.category-chip'));
            const noResults     = document.getElementById('category-no-results');
            const toggleBtn     = document.getElementById('category-toggle');
            const toggleText    = document.getElementById('toggle-text');
            const toggleIcon    = document.getElementById('toggle-icon');

            const SHOW_LIMIT    = 12;
            let   expanded      = false;

            if (!chips.length) return;

            // --- Apply visibility based on search + expanded state ---
            function applyVisibility() {
                const query = (searchInput?.value || '').trim().toLowerCase();

                let visibleCount = 0;

                chips.forEach((chip, index) => {
                    const name = chip.getAttribute('data-category-name') || '';
                    const matchesSearch = !query || name.includes(query);
                    const withinLimit = expanded || query || index < SHOW_LIMIT;

                    if (matchesSearch && withinLimit) {
                        chip.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        chip.classList.add('hidden');
                    }
                });

                // No-results message
                if (noResults) {
                    noResults.classList.toggle('hidden', !(query && visibleCount === 0));
                }

                // Toggle button visibility
                if (toggleBtn) {
                    // Hide "show more" while searching
                    if (query) {
                        toggleBtn.classList.add('hidden');
                    } else {
                        toggleBtn.classList.remove('hidden');
                        toggleBtn.classList.toggle('hidden', chips.length <= SHOW_LIMIT);
                    }
                }
            }

            // --- Toggle button ---
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function () {
                    expanded = !expanded;

                    if (expanded) {
                        toggleText.textContent = 'Show less';
                        toggleIcon.style.transform = 'rotate(180deg)';
                    } else {
                        toggleText.textContent = 'Show all ' + chips.length + ' categories';
                        toggleIcon.style.transform = 'rotate(0deg)';
                    }

                    applyVisibility();
                });
            }

            // --- Search input ---
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    if (searchClear) {
                        searchClear.classList.toggle('hidden', !searchInput.value);
                    }
                    applyVisibility();
                });
            }

            // --- Clear search ---
            if (searchClear) {
                searchClear.addEventListener('click', function () {
                    searchInput.value = '';
                    searchClear.classList.add('hidden');
                    applyVisibility();
                    searchInput.focus();
                });
            }

            // --- Initial state ---
            applyVisibility();
        })();
    </script>

@endsection