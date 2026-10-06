@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')

    <a href="{{ route('products.index') }}"
       class="inline-flex items-center gap-2 text-cyan-400 hover:text-cyan-300 text-sm mb-4 fade-in-up d-1 font-bold">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Products
    </a>

    {{-- Header --}}
    <div class="fade-in-up d-1 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 mb-6 relative overflow-hidden shadow-2xl border border-blue-700/50">
        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-full -mr-20 -mt-20 pointer-events-none"></div>
        <div class="relative z-10 flex items-start justify-between gap-4">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center flex-shrink-0 shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <h2 class="text-white text-xl sm:text-2xl font-bold truncate">Edit Product</h2>
                    <p class="text-blue-200 text-xs sm:text-sm mt-0.5 truncate">
                        {{ $product->name }} · Added by {{ $product->creator->full_name ?? 'Unknown' }}
                    </p>
                </div>
            </div>

            @if($selectedCategoryName)
                <div class="flex-shrink-0 text-right">
                    <p class="text-[10px] uppercase font-bold text-cyan-400 tracking-wider">Category</p>
                    <p class="text-white text-sm font-semibold">{{ $selectedCategoryName }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Success / Error --}}
    <div id="success-message" class="hidden fade-in-up d-2 bg-gradient-to-r from-green-600 to-emerald-600 border-2 border-green-400 text-white px-5 py-4 rounded-xl mb-6 text-sm shadow-2xl shadow-green-500/50 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="flex-1">
            <p class="font-bold text-white text-base">Success!</p>
            <p class="text-white text-sm mt-0.5" id="success-text">Product updated successfully!</p>
        </div>
    </div>

    <div id="error-message" class="hidden fade-in-up d-2 bg-gradient-to-r from-red-600 to-rose-600 border-2 border-red-400 text-white px-5 py-4 rounded-xl mb-6 text-sm shadow-2xl shadow-red-500/50 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="flex-1">
            <p class="font-bold text-white text-base">Error!</p>
            <p class="text-white text-sm mt-0.5" id="error-text">Failed to update product.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="fade-in-up d-2 bg-red-500/20 border border-red-500/40 text-white px-4 py-3 rounded-xl mb-6 text-sm">
            @foreach($errors->all() as $error)
                <p class="flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $error }}
                </p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('products.update', $product) }}" id="product-form"
          class="fade-in-up d-2 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 sm:p-6 space-y-6 shadow-2xl border border-blue-700/50">
        @csrf
        @method('PUT')

        {{-- ===================================================== --}}
        {{-- SECTION 1: Added By (read-only) --}}
        {{-- ===================================================== --}}
        <div>
            <label class="block text-xs font-semibold text-white mb-2">Added By</label>
            <div class="flex items-center gap-2 bg-blue-900/40 backdrop-blur border border-blue-700/50 rounded-xl px-4 py-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold text-xs shadow-lg">
                    {{ strtoupper(substr($product->creator->full_name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <p class="text-white text-sm font-semibold">{{ $product->creator->full_name ?? 'Unknown' }}</p>
                    <p class="text-blue-200 text-xs capitalize">
                        {{ $product->creator->role ?? '' }} ·
                        @if($product->created_at)
                            {{ $product->created_at->format('d M Y, H:i') }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- ===================================================== --}}
        {{-- SECTION 2: Store & Category context --}}
        {{-- ===================================================== --}}
        <div>
            <h3 class="text-white font-bold text-sm mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 7l9-4 9 4M3 7v10l9 4 9-4V7M3 7l9 4m9-4l-9 4m0 0v10"/>
                </svg>
                Store &amp; Category
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                {{-- Store (editable) --}}
                <div>
                    <label for="store_id" class="block text-xs font-semibold text-white mb-2">
                        Store <span class="text-red-400">*</span>
                    </label>
                    <select name="store_id" id="store_id" required
                            class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white px-4 py-3 rounded-xl outline-none appearance-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                        <option value="" class="bg-blue-950">-- Select Store --</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" class="bg-blue-950"
                                {{ old('store_id', $product->store_id) == $store->id ? 'selected' : '' }}>
                                {{ $store->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Category (locked/read-only — to change, pick a different product category path) --}}
                <div>
                    <label class="block text-xs font-semibold text-white mb-2">
                        Category <span class="text-red-400">*</span>
                    </label>
                    <div class="flex items-center gap-2 bg-blue-900/40 border border-cyan-500/40 rounded-xl px-4 py-3">
                        <svg class="w-4 h-4 text-cyan-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M7 7h.01M7 3h5a2 2 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <span class="text-white text-sm font-semibold truncate">
                            {{ $selectedCategoryName ?? ($product->category->name ?? 'No category') }}
                        </span>
                    </div>
                    <input type="hidden" name="category_id" id="category_id" value="{{ $product->category_id }}">
                    <p class="text-blue-200/70 text-xs mt-1">🔒 Category is fixed for this product</p>
                </div>
            </div>
        </div>

        {{-- ===================================================== --}}
        {{-- SECTION 3: Basic info --}}
        {{-- ===================================================== --}}
        <div class="border-t border-blue-700/50 pt-5">
            <h3 class="text-white font-bold text-sm mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Basic Information
            </h3>

            <div>
                <label for="name" class="block text-xs font-semibold text-white mb-2">
                    Product Name <span class="text-red-400">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                       placeholder="e.g. Coca Cola 500ml"
                       class="w-full bg-blue-900/40 border border-blue-700/50 text-white placeholder-blue-200/40 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
            </div>
        </div>

        {{-- ===================================================== --}}
        {{-- SECTION 4: Category-specific specs --}}
        {{-- ===================================================== --}}
        @if(!empty($categorySpecs))
            <div class="border-t border-blue-700/50 pt-5">
                <h3 class="text-white font-bold text-sm mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    {{ $selectedCategoryName }} Details
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @php
                        $existingSpecs = $product->specs ?? [];
                    @endphp

                    @foreach($categorySpecs as $spec)
                        @php
                            $specKey = $spec['name'];
                            $specValue = old("specs.{$specKey}", $existingSpecs[$specKey] ?? '');
                        @endphp

                        <div class="{{ ($spec['type'] ?? 'text') === 'select' ? 'sm:col-span-2' : '' }}">
                            <label for="spec_{{ $specKey }}" class="block text-xs font-semibold text-white mb-2">
                                {{ $spec['label'] }}
                                @if($spec['required'] ?? false)
                                    <span class="text-red-400">*</span>
                                @endif
                                @if(isset($spec['unit']))
                                    <span class="text-cyan-400 font-normal">({{ $spec['unit'] }})</span>
                                @endif
                            </label>

                            @if(($spec['type'] ?? 'text') === 'select')
                                <select name="specs[{{ $specKey }}]" id="spec_{{ $specKey }}"
                                        {{ ($spec['required'] ?? false) ? 'required' : '' }}
                                        class="w-full bg-blue-900/40 border border-blue-700/50 text-white px-4 py-3 rounded-xl outline-none appearance-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                                    <option value="" class="bg-blue-950 text-white">-- Select {{ $spec['label'] }} --</option>
                                    @foreach($spec['options'] ?? [] as $option)
                                        <option value="{{ $option }}" class="bg-blue-950 text-white"
                                            {{ $specValue == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            @elseif(($spec['type'] ?? 'text') === 'number')
                                <input type="number" name="specs[{{ $specKey }}]" id="spec_{{ $specKey }}"
                                       value="{{ $specValue }}"
                                       step="0.01" min="0"
                                       placeholder="{{ $spec['placeholder'] ?? '0' }}"
                                       {{ ($spec['required'] ?? false) ? 'required' : '' }}
                                       class="w-full bg-blue-900/40 border border-blue-700/50 text-white placeholder-blue-200/40 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                            @else
                                <input type="text" name="specs[{{ $specKey }}]" id="spec_{{ $specKey }}"
                                       value="{{ $specValue }}"
                                       placeholder="{{ $spec['placeholder'] ?? '' }}"
                                       {{ ($spec['required'] ?? false) ? 'required' : '' }}
                                       class="w-full bg-blue-900/40 border border-blue-700/50 text-white placeholder-blue-200/40 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ===================================================== --}}
        {{-- SECTION 5: Pricing --}}
        {{-- ===================================================== --}}
        <div class="border-t border-blue-700/50 pt-5">
            <h3 class="text-white font-bold text-sm mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Pricing
            </h3>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="cost_price" class="block text-xs font-semibold text-white mb-2">Cost Price</label>
                    <input type="number" name="cost_price" id="cost_price" value="{{ old('cost_price', $product->cost_price) }}"
                           min="0" step="0.01" placeholder="0"
                           class="w-full bg-blue-900/40 border border-blue-700/50 text-white placeholder-blue-200/40 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                </div>
                <div>
                    <label for="selling_price" class="block text-xs font-semibold text-white mb-2">
                        Selling Price <span class="text-red-400">*</span>
                    </label>
                    <input type="number" name="selling_price" id="selling_price" value="{{ old('selling_price', $product->selling_price) }}"
                           min="0" step="0.01" required placeholder="0"
                           class="w-full bg-blue-900/40 border border-blue-700/50 text-white placeholder-blue-200/40 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                </div>
            </div>

            {{-- Profit preview --}}
            <div id="profit-preview" class="mt-3 bg-green-500/20 border border-green-500/40 rounded-xl px-4 py-3 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-white font-medium">Expected profit per unit:</span>
                    <span id="profit-value" class="font-bold text-green-400">TSh 0</span>
                </div>
                <p class="text-xs text-blue-200/70 mt-1">Calculated from Selling Price − Cost Price</p>
            </div>
        </div>

        {{-- ===================================================== --}}
        {{-- SECTION 6: Stock --}}
        {{-- ===================================================== --}}
        <div class="border-t border-blue-700/50 pt-5">
            <h3 class="text-white font-bold text-sm mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Stock
            </h3>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="quantity" class="block text-xs font-semibold text-white mb-2">
                        Quantity <span class="text-red-400">*</span>
                    </label>
                    <input type="number" name="quantity" id="quantity"
                           value="{{ old('quantity', $stock->quantity ?? 0) }}"
                           min="0" step="1" required placeholder="0"
                           class="w-full bg-blue-900/40 border border-blue-700/50 text-white placeholder-blue-200/40 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                </div>
                <div>
                    <label for="min_quantity" class="block text-xs font-semibold text-white mb-2">
                        Min Quantity (alert)
                    </label>
                    <input type="number" name="min_quantity" id="min_quantity"
                           value="{{ old('min_quantity', $stock->min_quantity ?? 5) }}"
                           min="0" step="1" placeholder="5"
                           class="w-full bg-blue-900/40 border border-blue-700/50 text-white placeholder-blue-200/40 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                </div>
            </div>
        </div>

        {{-- Active --}}
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                   class="w-4 h-4 rounded border-blue-700 bg-blue-900/40 text-cyan-500 focus:ring-cyan-400/50">
            <label for="is_active" class="text-sm text-white">Active product</label>
        </div>

        {{-- Buttons --}}
        <div class="flex items-center gap-3 pt-3 border-t border-blue-700/50">
            <button type="submit" id="submit-btn"
                    class="flex-1 bg-gradient-to-br from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-bold py-3 rounded-xl transition shadow-lg disabled:opacity-50">
                <span id="submit-text">Save Changes</span>
            </button>
            <a href="{{ route('products.index') }}"
               class="px-5 py-3 rounded-xl bg-blue-900/40 hover:bg-blue-800/50 border border-blue-700/50 text-white text-sm font-bold transition">
                Cancel
            </a>
        </div>
    </form>

    <script>
        // ===== Profit Preview =====
        (function () {
            const costInput = document.getElementById('cost_price');
            const sellInput = document.getElementById('selling_price');
            const profitBox = document.getElementById('profit-preview');
            const profitValue = document.getElementById('profit-value');

            if (!costInput || !sellInput || !profitBox || !profitValue) return;

            function updateProfit() {
                const cost = parseFloat(costInput.value) || 0;
                const sell = parseFloat(sellInput.value) || 0;
                const profit = sell - cost;

                profitValue.textContent = 'TSh ' + profit.toLocaleString();

                if (profit < 0) {
                    profitBox.classList.remove('bg-green-500/20', 'border-green-500/40');
                    profitBox.classList.add('bg-red-500/20', 'border-red-500/40');
                    profitValue.classList.remove('text-green-400');
                    profitValue.classList.add('text-red-400');
                } else {
                    profitBox.classList.remove('bg-red-500/20', 'border-red-500/40');
                    profitBox.classList.add('bg-green-500/20', 'border-green-500/40');
                    profitValue.classList.remove('text-red-400');
                    profitValue.classList.add('text-green-400');
                }
            }

            costInput.addEventListener('input', updateProfit);
            sellInput.addEventListener('input', updateProfit);

            updateProfit();
        })();
    </script>

@endsection