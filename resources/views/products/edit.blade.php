@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')

    <a href="{{ route('products.index') }}"
       class="inline-flex items-center gap-2 text-lime-400 hover:text-lime-300 text-sm mb-4 font-bold">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Products
    </a>

    {{-- Header --}}
    <div class="bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-5 mb-6 shadow-2xl border border-stone-600/50 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-yellow-500/20 to-lime-500/20 rounded-full -mr-20 -mt-20"></div>
        <div class="relative z-10 flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-600 to-lime-600 flex items-center justify-center flex-shrink-0 shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <h2 class="text-white text-xl sm:text-2xl font-bold truncate">Edit Product</h2>
                <p class="text-white text-xs sm:text-sm mt-0.5 truncate">
                    {{ $product->name }} · Added by {{ $product->creator->full_name ?? 'Unknown' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Errors --}}
    @if($errors->any())
        <div class="bg-red-500/20 border border-red-500/40 text-white px-4 py-3 rounded-xl mb-6 text-sm">
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

    <form method="POST" action="{{ route('products.update', $product) }}"
          class="bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-5 sm:p-6 space-y-5 shadow-2xl border border-stone-600/50">
        @csrf
        @method('PUT')

        {{-- Added by (read-only) --}}
        <div>
            <label class="block text-xs font-semibold text-white mb-2">Added By</label>
            <div class="flex items-center gap-2 bg-stone-800/60 backdrop-blur border border-stone-600/50 rounded-xl px-4 py-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-yellow-600 to-lime-600 flex items-center justify-center text-white font-bold text-xs shadow-lg">
                    {{ strtoupper(substr($product->creator->full_name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <p class="text-white text-sm font-semibold">{{ $product->creator->full_name ?? 'Unknown' }}</p>
                    <p class="text-white text-xs capitalize">
                        {{ $product->creator->role ?? '' }} ·
                        @if($product->created_at)
                            {{ $product->created_at->format('d M Y, H:i') }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- Name --}}
        <div>
            <label for="name" class="block text-xs font-semibold text-white mb-2">
                Product Name <span class="text-red-400">*</span>
            </label>
            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                   placeholder="e.g. Coca Cola 500ml"
                   class="w-full bg-stone-800/60 backdrop-blur border border-stone-600/50 text-white placeholder-stone-400/50 px-4 py-3 rounded-xl outline-none focus:border-lime-400 focus:ring-2 focus:ring-lime-400/30 transition">
        </div>

        {{-- Store --}}
        <div>
            <div class="flex items-center justify-between mb-2">
                <label for="store_id" class="block text-xs font-semibold text-white">
                    Store <span class="text-red-400">*</span>
                </label>
                <a href="{{ route('stores.create') }}"
                   class="inline-flex items-center gap-1 text-lime-400 hover:text-lime-300 text-xs font-semibold">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Store
                </a>
            </div>
            <select name="store_id" id="store_id" required
                    class="w-full bg-stone-800/60 backdrop-blur border border-stone-600/50 text-white px-4 py-3 rounded-xl outline-none appearance-none focus:border-lime-400 focus:ring-2 focus:ring-lime-400/30 transition">
                <option value="" class="bg-stone-800">-- Select Store --</option>
                @foreach($stores as $store)
                    <option value="{{ $store->id }}" class="bg-stone-800"
                        {{ old('store_id', $product->store_id) == $store->id ? 'selected' : '' }}>
                        {{ $store->name }}
                    </option>
                @endforeach
            </select>
            <p class="text-white/70 text-xs mt-1">Product is stored in this store</p>
        </div>

        {{-- SKU + Unit --}}
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="sku" class="block text-xs font-semibold text-white mb-2">SKU (optional)</label>
                <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}"
                       placeholder="e.g. CC500"
                       class="w-full bg-stone-800/60 backdrop-blur border border-stone-600/50 text-white placeholder-stone-400/50 px-4 py-3 rounded-xl outline-none focus:border-lime-400 focus:ring-2 focus:ring-lime-400/30 transition">
            </div>
            <div>
                <label for="unit" class="block text-xs font-semibold text-white mb-2">
                    Unit <span class="text-red-400">*</span>
                </label>
                <select name="unit" id="unit" required onchange="toggleSizeInput()"
                        class="w-full bg-stone-800/60 backdrop-blur border border-stone-600/50 text-white px-4 py-3 rounded-xl outline-none appearance-none focus:border-lime-400 focus:ring-2 focus:ring-lime-400/30 transition">
                    <option value="" class="bg-stone-800" data-size="none">-- Select Unit --</option>

                    <option value="pcs"    class="bg-stone-800" data-size="none"   {{ old('unit', $product->unit) == 'pcs' ? 'selected' : '' }}>pcs (pieces)</option>
                    <option value="kg"     class="bg-stone-800" data-size="weight" data-label="kg"  {{ old('unit', $product->unit) == 'kg' ? 'selected' : '' }}>kg (kilogram)</option>
                    <option value="g"      class="bg-stone-800" data-size="weight" data-label="g"   {{ old('unit', $product->unit) == 'g' ? 'selected' : '' }}>g (gram)</option>
                    <option value="litre"  class="bg-stone-800" data-size="volume" data-label="L"   {{ old('unit', $product->unit) == 'litre' ? 'selected' : '' }}>litre</option>
                    <option value="ml"     class="bg-stone-800" data-size="volume" data-label="ml"  {{ old('unit', $product->unit) == 'ml' ? 'selected' : '' }}>ml (millilitre)</option>
                    <option value="box"    class="bg-stone-800" data-size="none"   {{ old('unit', $product->unit) == 'box' ? 'selected' : '' }}>box</option>
                    <option value="pack"   class="bg-stone-800" data-size="none"   {{ old('unit', $product->unit) == 'pack' ? 'selected' : '' }}>pack</option>
                    <option value="carton" class="bg-stone-800" data-size="none"   {{ old('unit', $product->unit) == 'carton' ? 'selected' : '' }}>carton</option>
                    <option value="bottle" class="bg-stone-800" data-size="none"   {{ old('unit', $product->unit) == 'bottle' ? 'selected' : '' }}>bottle</option>
                    <option value="can"    class="bg-stone-800" data-size="none"   {{ old('unit', $product->unit) == 'can' ? 'selected' : '' }}>can</option>
                    <option value="sachet" class="bg-stone-800" data-size="none"   {{ old('unit', $product->unit) == 'sachet' ? 'selected' : '' }}>sachet</option>
                    <option value="dozen"  class="bg-stone-800" data-size="none"   {{ old('unit', $product->unit) == 'dozen' ? 'selected' : '' }}>dozen</option>
                    <option value="bundle" class="bg-stone-800" data-size="none"   {{ old('unit', $product->unit) == 'bundle' ? 'selected' : '' }}>bundle</option>
                    <option value="bag"    class="bg-stone-800" data-size="none"   {{ old('unit', $product->unit) == 'bag' ? 'selected' : '' }}>bag</option>
                    <option value="roll"   class="bg-stone-800" data-size="none"   {{ old('unit', $product->unit) == 'roll' ? 'selected' : '' }}>roll</option>
                    <option value="metre"  class="bg-stone-800" data-size="none"   {{ old('unit', $product->unit) == 'metre' ? 'selected' : '' }}>metre</option>
                    <option value="pair"   class="bg-stone-800" data-size="none"   {{ old('unit', $product->unit) == 'pair' ? 'selected' : '' }}>pair</option>
                    <option value="set"    class="bg-stone-800" data-size="none"   {{ old('unit', $product->unit) == 'set' ? 'selected' : '' }}>set</option>
                    <option value="other"  class="bg-stone-800" data-size="none"   {{ old('unit', $product->unit) == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
        </div>

        {{-- SIZE INPUT (Dynamic) --}}
        <div id="size-wrapper" class="hidden">
            <label for="size" class="block text-xs font-semibold text-white mb-2">
                <span id="size-label">Size</span> <span class="text-red-400">*</span>
            </label>
            <div class="flex items-center gap-2">
                <input type="number" name="size" id="size"
                       value="{{ old('size', $product->size) }}"
                       min="0" step="0.01" placeholder="0"
                       class="flex-1 bg-stone-800/60 backdrop-blur border border-stone-600/50 text-white placeholder-stone-400/50 px-4 py-3 rounded-xl outline-none focus:border-lime-400 focus:ring-2 focus:ring-lime-400/30 transition">
                <span id="size-unit-display"
                      class="px-4 py-3 rounded-xl bg-stone-800/60 border border-stone-600/50 text-lime-400 font-bold text-sm min-w-[60px] text-center">
                    L
                </span>
            </div>
            <p class="text-white/70 text-xs mt-1">
                <span id="size-hint">Enter the size</span>
            </p>
        </div>

        {{-- Prices --}}
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="cost_price" class="block text-xs font-semibold text-white mb-2">Cost Price (optional)</label>
                <input type="number" name="cost_price" id="cost_price" value="{{ old('cost_price', $product->cost_price) }}"
                       min="0" step="0.01" placeholder="0"
                       class="w-full bg-stone-800/60 backdrop-blur border border-stone-600/50 text-white placeholder-stone-400/50 px-4 py-3 rounded-xl outline-none focus:border-lime-400 focus:ring-2 focus:ring-lime-400/30 transition">
            </div>
            <div>
                <label for="selling_price" class="block text-xs font-semibold text-white mb-2">
                    Selling Price <span class="text-red-400">*</span>
                </label>
                <input type="number" name="selling_price" id="selling_price" value="{{ old('selling_price', $product->selling_price) }}"
                       min="0" step="0.01" required placeholder="0"
                       class="w-full bg-stone-800/60 backdrop-blur border border-stone-600/50 text-white placeholder-stone-400/50 px-4 py-3 rounded-xl outline-none focus:border-lime-400 focus:ring-2 focus:ring-lime-400/30 transition">
            </div>
        </div>

        {{-- Profit preview --}}
        <div id="profit-preview" class="bg-green-500/20 border border-green-500/40 rounded-xl px-4 py-3 text-sm">
            <div class="flex items-center justify-between">
                <span class="text-white font-medium">Expected profit per unit:</span>
                <span id="profit-value" class="font-bold text-green-400">TSh 0</span>
            </div>
            <p class="text-xs text-white/70 mt-1">Calculated from Selling Price − Cost Price</p>
        </div>

        {{-- Stock Section --}}
        <div class="border-t border-stone-600/50 pt-5 mt-5">
            <h3 class="text-white font-bold text-sm mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Stock Information
            </h3>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="quantity" class="block text-xs font-semibold text-white mb-2">
                        Quantity <span class="text-red-400">*</span>
                    </label>
                    <input type="number" name="quantity" id="quantity"
                           value="{{ old('quantity', $stock->quantity ?? 0) }}"
                           min="0" step="1" required placeholder="0"
                           class="w-full bg-stone-800/60 backdrop-blur border border-stone-600/50 text-white placeholder-stone-400/50 px-4 py-3 rounded-xl outline-none focus:border-lime-400 focus:ring-2 focus:ring-lime-400/30 transition">
                    <p class="text-white/70 text-xs mt-1">How many items are in stock?</p>
                </div>
                <div>
                    <label for="min_quantity" class="block text-xs font-semibold text-white mb-2">
                        Min Quantity (alert)
                    </label>
                    <input type="number" name="min_quantity" id="min_quantity"
                           value="{{ old('min_quantity', $stock->min_quantity ?? 5) }}"
                           min="0" step="1" placeholder="5"
                           class="w-full bg-stone-800/60 backdrop-blur border border-stone-600/50 text-white placeholder-stone-400/50 px-4 py-3 rounded-xl outline-none focus:border-lime-400 focus:ring-2 focus:ring-lime-400/30 transition">
                    <p class="text-white/70 text-xs mt-1">Alert when stock is below this</p>
                </div>
            </div>
        </div>

        {{-- Active --}}
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                   class="w-4 h-4 rounded border-stone-600 bg-stone-800/60 text-lime-500 focus:ring-lime-400/50">
            <label for="is_active" class="text-sm text-white">Active product</label>
        </div>

        {{-- Buttons --}}
        <div class="flex items-center gap-3 pt-3">
            <button type="submit"
                    class="flex-1 bg-gradient-to-br from-yellow-600 to-lime-600 hover:from-yellow-700 hover:to-lime-700 text-white font-bold py-3 rounded-xl transition shadow-lg">
                Save Changes
            </button>
            <a href="{{ route('products.index') }}"
               class="px-5 py-3 rounded-xl bg-stone-800/60 hover:bg-stone-700/60 border border-stone-600/50 text-white text-sm font-bold transition">
                Cancel
            </a>
        </div>

    </form>

    {{-- Inline Script --}}
    <script>
        // ===== Size Input Toggle =====
        function toggleSizeInput() {
            const unitSelect = document.getElementById('unit');
            const selectedOption = unitSelect.options[unitSelect.selectedIndex];
            const sizeType = selectedOption.getAttribute('data-size');
            const sizeLabel = selectedOption.getAttribute('data-label') || '';

            const sizeWrapper = document.getElementById('size-wrapper');
            const sizeLabelEl = document.getElementById('size-label');
            const sizeUnitDisplay = document.getElementById('size-unit-display');
            const sizeHint = document.getElementById('size-hint');
            const sizeInput = document.getElementById('size');

            if (sizeType === 'none' || !sizeType) {
                sizeWrapper.classList.add('hidden');
                sizeInput.removeAttribute('required');
            } else {
                sizeWrapper.classList.remove('hidden');
                sizeInput.setAttribute('required', 'required');

                if (sizeType === 'weight') {
                    sizeLabelEl.textContent = 'Weight';
                    sizeUnitDisplay.textContent = sizeLabel;
                    sizeHint.textContent = 'Enter the weight (e.g. 1.5 for 1.5' + sizeLabel + ')';
                } else if (sizeType === 'volume') {
                    sizeLabelEl.textContent = 'Volume';
                    sizeUnitDisplay.textContent = sizeLabel;
                    sizeHint.textContent = 'Enter the volume (e.g. 1.5 for 1.5' + sizeLabel + ')';
                }
            }
        }

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

        // ===== Run on load =====
        document.addEventListener('DOMContentLoaded', function () {
            const unitSelect = document.getElementById('unit');
            if (unitSelect.value) {
                toggleSizeInput();
            }
        });
    </script>

@endsection