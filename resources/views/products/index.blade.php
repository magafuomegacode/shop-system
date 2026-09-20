@extends('layouts.app')

@section('title', 'Products')

@section('content')

    {{-- Header --}}
    <div class="fade-in-up bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-5 mb-6 relative z-30 shadow-2xl border border-stone-600/50" style="overflow: visible;">
        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-yellow-500/20 to-lime-500/20 rounded-full -mr-20 -mt-20 pointer-events-none"></div>
        <div class="relative z-20 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-white text-xl sm:text-2xl font-bold">Products</h2>
                <p class="text-stone-300 text-xs sm:text-sm mt-1">
                    {{ $products->total() }} {{ Str::plural('product', $products->total()) }} found
                </p>
            </div>

            <div class="flex items-center gap-2 flex-wrap w-full sm:w-auto">
                {{-- Download button --}}
                <button type="button"
                        id="download-trigger"
                        class="flex-1 sm:flex-initial flex-shrink-0 bg-stone-800/60 backdrop-blur border border-stone-600/50 hover:bg-stone-700/60 text-white text-sm font-semibold px-4 py-2.5 rounded-xl flex items-center justify-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Download</span>
                </button>

                {{-- Add Product --}}
                <a href="{{ route('products.create') }}"
                   class="flex-1 sm:flex-initial flex-shrink-0 bg-gradient-to-br from-yellow-600 to-lime-600 hover:from-yellow-700 hover:to-lime-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl flex items-center justify-center gap-2 transition shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Add</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Success --}}
    @if(session('success'))
        <div class="fade-in-up d-2 bg-green-500/20 border border-green-500/40 text-green-200 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Filters --}}
    <div class="fade-in-up d-2 bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-4 mb-4 shadow-2xl border border-stone-600/50">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <svg class="w-5 h-5 text-stone-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by name or SKU..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl outline-none text-sm bg-stone-800/60 backdrop-blur border border-stone-600/50 text-white placeholder-stone-400/60 focus:border-lime-400 focus:ring-2 focus:ring-lime-400/30 transition">
            </div>

            <select name="store" class="px-4 py-2.5 rounded-xl outline-none text-sm appearance-none min-w-[150px] bg-stone-800/60 backdrop-blur border border-stone-600/50 text-white focus:border-lime-400">
                <option value="" class="bg-stone-800 text-white">All stores</option>
                @foreach($stores as $store)
                    <option value="{{ $store->id }}" class="bg-stone-800 text-white" {{ request('store') == $store->id ? 'selected' : '' }}>
                        {{ $store->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                    class="bg-gradient-to-br from-yellow-600 to-lime-600 hover:from-yellow-700 hover:to-lime-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition shadow-lg">
                Filter
            </button>

            @if(request()->hasAny(['search', 'store']))
                <a href="{{ route('products.index') }}"
                   class="bg-stone-800/60 backdrop-blur hover:bg-stone-700/60 text-stone-200 text-sm font-semibold px-5 py-2.5 rounded-xl transition text-center border border-stone-600/50">
                    Clear
                </a>
            @endif
        </form>
    </div>

    {{-- Bordered Table (Khaki) --}}
    @if($products->count() > 0)
        <div class="fade-in-up bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl overflow-hidden shadow-2xl border border-stone-600/50">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-stone-900 border-b-2 border-lime-600/60">
                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wide border-r border-stone-600/50 w-12">#</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wide border-r border-stone-600/50">Product</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wide border-r border-stone-600/50">SKU</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wide border-r border-stone-600/50">Store</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wide border-r border-stone-600/50">Unit</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-white uppercase tracking-wide border-r border-stone-600/50">Price</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-white uppercase tracking-wide border-r border-stone-600/50">Stock</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wide border-r border-stone-600/50">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wide border-r border-stone-600/50">Added By</th>
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
                            <tr class="border-b border-stone-600/30 hover:bg-stone-700/40 transition {{ $i % 2 === 0 ? 'bg-stone-800/40' : 'bg-stone-700/20' }}">
                                {{-- # --}}
                                <td class="px-4 py-3 text-sm text-stone-300 border-r border-stone-600/30">
                                    {{ $products->firstItem() + $i }}
                                </td>

                                {{-- Product --}}
                                <td class="px-4 py-3 border-r border-stone-600/30">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-yellow-600 to-lime-600 flex items-center justify-center flex-shrink-0 shadow-lg">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                        <span class="text-white font-semibold text-sm truncate max-w-[200px]">
                                            {{ $product->name }}
                                        </span>
                                    </div>
                                </td>

                                {{-- SKU --}}
                                <td class="px-4 py-3 text-sm text-stone-300 font-mono border-r border-stone-600/30">
                                    {{ $product->sku ?? '—' }}
                                </td>

                                {{-- Store --}}
                                <td class="px-4 py-3 border-r border-stone-600/30">
                                    @if($product->store)
                                        <span class="px-2 py-0.5 bg-lime-500/20 text-lime-200 rounded-md text-xs font-medium border border-lime-500/40">
                                            {{ $product->store->name }}
                                        </span>
                                    @else
                                        <span class="text-stone-500 text-xs">—</span>
                                    @endif
                                </td>

                                {{-- Unit --}}
                                <td class="px-4 py-3 text-sm text-stone-300 border-r border-stone-600/30">
                                    {{ $product->unit ?? '—' }}
                                </td>

                                {{-- Price --}}
                                <td class="px-4 py-3 text-sm text-white font-semibold text-right border-r border-stone-600/30">
                                    TSh {{ number_format((float) $product->selling_price, 0) }}
                                </td>

                                {{-- Stock --}}
                                <td class="px-4 py-3 text-center border-r border-stone-600/30">
                                    <span class="inline-block px-2 py-0.5 rounded-md text-xs font-bold {{ $isLowStock ? 'bg-red-500/30 text-red-200 border border-red-400/40' : 'bg-green-500/30 text-green-200 border border-green-400/40' }}">
                                        {{ $stockQty }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-3 border-r border-stone-600/30">
                                    @if($product->is_active)
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase bg-green-500/30 text-green-200 border border-green-400/40">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase bg-red-500/30 text-red-200 border border-red-400/40">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                {{-- Added By --}}
                                <td class="px-4 py-3 border-r border-stone-600/30">
                                    <div class="text-xs">
                                        <p class="text-white font-medium truncate max-w-[120px]">
                                            {{ $product->creator->full_name ?? 'Unknown' }}
                                        </p>
                                        <p class="text-stone-300 text-[10px]">
                                            {{ $product->created_at ? $product->created_at->format('d M Y') : '—' }}
                                        </p>
                                    </div>
                                </td>

                                {{-- Actions --}}
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('products.show', $product) }}"
                                           class="w-7 h-7 rounded-lg bg-stone-800/60 hover:bg-stone-700/60 border border-stone-600/50 flex items-center justify-center transition"
                                           title="View">
                                            <svg class="w-3.5 h-3.5 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        @if(auth()->user()->isAdmin() || auth()->user()->isOwner() || auth()->user()->isCashier())
                                            <a href="{{ route('products.edit', $product) }}"
                                               class="w-7 h-7 rounded-lg bg-stone-800/60 hover:bg-stone-700/60 border border-stone-600/50 flex items-center justify-center transition"
                                               title="Edit">
                                                <svg class="w-3.5 h-3.5 text-lime-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
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
                                                    <svg class="w-3.5 h-3.5 text-red-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
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
        <div class="bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-10 text-center shadow-2xl border border-stone-600/50">
            <svg class="w-16 h-16 text-stone-500/50 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <p class="text-stone-300 text-sm font-medium">No products yet</p>
            <a href="{{ route('products.create') }}" class="inline-block mt-4 text-lime-400 hover:text-lime-300 text-sm font-semibold">
                Add your first product →
            </a>
        </div>
    @endif

    {{-- ===== Download Modal ===== --}}
    <div id="download-modal"
         class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4"
         style="background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);">

        <div class="bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden border border-stone-600/50">
            <div class="px-5 py-4 border-b border-stone-600/50 flex items-center justify-between bg-stone-900">
                <div>
                    <h3 class="text-white font-bold text-base">Download Products</h3>
                    <p class="text-stone-300 text-xs mt-0.5">Choose a format</p>
                </div>
                <button type="button"
                        onclick="closeDownloadModal()"
                        class="w-8 h-8 rounded-lg bg-stone-800/60 hover:bg-stone-700/60 flex items-center justify-center transition border border-stone-600/50">
                    <svg class="w-4 h-4 text-stone-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-3">
                <a href="{{ route('products.download.report', request()->only(['search', 'store'])) }}"
                   target="_blank"
                   onclick="closeDownloadModal()"
                   class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-stone-700/40 transition">
                    <div class="w-10 h-10 rounded-xl bg-red-500/30 border border-red-400/40 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-red-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="text-left flex-1">
                        <p class="text-white text-sm font-semibold">PDF Report</p>
                        <p class="text-stone-300 text-xs">Printable · Grouped by store</p>
                    </div>
                    <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>

                <a href="{{ route('products.download.csv', request()->only(['search', 'store'])) }}"
                   onclick="closeDownloadModal()"
                   class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-stone-700/40 transition">
                    <div class="w-10 h-10 rounded-xl bg-green-500/30 border border-green-400/40 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="text-left flex-1">
                        <p class="text-white text-sm font-semibold">Excel (CSV)</p>
                        <p class="text-stone-300 text-xs">For spreadsheets</p>
                    </div>
                    <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <script>
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
    </script>

@endsection