@extends('layouts.app')

@section('title', 'Add Product')

@section('content')

    <a href="{{ route('products.index') }}"
       class="inline-flex items-center gap-2 text-lime-400 hover:text-lime-300 text-sm mb-4 fade-in-up d-1 font-bold">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Products
    </a>

    <div class="fade-in-up d-1 bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-5 mb-6 relative overflow-hidden shadow-2xl border border-stone-600/50">
        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-yellow-500/20 to-lime-500/20 rounded-full -mr-20 -mt-20 pointer-events-none"></div>
        <div class="relative z-10">
            <h2 class="text-white text-xl sm:text-2xl font-bold">Add New Product</h2>
            <p class="text-white text-xs sm:text-sm mt-1">
                You will be recorded as the one who added this product
            </p>
        </div>
    </div>

    {{-- ✅ SUCCESS MESSAGE — Kijani yenye nguvu --}}
    <div id="success-message" class="hidden fade-in-up d-2 bg-gradient-to-r from-green-600 to-emerald-600 border-2 border-green-400 text-white px-5 py-4 rounded-xl mb-6 text-sm shadow-2xl shadow-green-500/50 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="flex-1">
            <p class="font-bold text-white text-base">Success!</p>
            <p class="text-white text-sm mt-0.5" id="success-text">Product added successfully!</p>
        </div>
    </div>

    {{-- ❌ ERROR MESSAGE — Nyekundu yenye nguvu --}}
    <div id="error-message" class="hidden fade-in-up d-2 bg-gradient-to-r from-red-600 to-rose-600 border-2 border-red-400 text-white px-5 py-4 rounded-xl mb-6 text-sm shadow-2xl shadow-red-500/50 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="flex-1">
            <p class="font-bold text-white text-base">Error!</p>
            <p class="text-white text-sm mt-0.5" id="error-text">Failed to add product.</p>
        </div>
    </div>

    {{-- Errors (server-side) --}}
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

    <form method="POST" action="{{ route('products.store') }}" id="product-form"
          class="fade-in-up d-2 bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-5 sm:p-6 space-y-5 shadow-2xl border border-stone-600/50">
        @csrf

        {{-- Added by --}}
        <div>
            <label class="block text-xs font-semibold text-white mb-2">Added By</label>
            <div class="flex items-center gap-2 bg-stone-800/60 backdrop-blur border border-stone-600/50 rounded-xl px-4 py-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-yellow-600 to-lime-600 flex items-center justify-center text-white font-bold text-xs shadow-lg">
                    {{ strtoupper(substr(auth()->user()->full_name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-white text-sm font-semibold">{{ auth()->user()->full_name }}</p>
                    <p class="text-white text-xs capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>
        </div>

        {{-- Name --}}
        <div>
            <label for="name" class="block text-xs font-semibold text-white mb-2">
                Product Name <span class="text-red-400">*</span>
            </label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                   placeholder="e.g. Coca Cola"
                   class="w-full bg-stone-800/60 border border-stone-600/50 text-white placeholder-stone-400/60 px-4 py-3 rounded-xl outline-none focus:border-lime-400 focus:ring-2 focus:ring-lime-400/30 transition">
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
                    class="w-full bg-stone-800/60 border border-stone-600/50 text-white px-4 py-3 rounded-xl outline-none appearance-none focus:border-lime-400 focus:ring-2 focus:ring-lime-400/30 transition">
                <option value="" class="bg-stone-800 text-white">-- Select Store --</option>
                @foreach($stores as $store)
                    <option value="{{ $store->id }}" class="bg-stone-800 text-white" {{ old('store_id') == $store->id ? 'selected' : '' }}>
                        {{ $store->name }}
                    </option>
                @endforeach
            </select>
            <p class="text-white/70 text-xs mt-1">Product will be stored in this store</p>
        </div>

        {{-- SKU + Unit --}}
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="sku" class="block text-xs font-semibold text-white mb-2">SKU (optional)</label>
                <input type="text" name="sku" id="sku" value="{{ old('sku') }}"
                       placeholder="e.g. CC500"
                       class="w-full bg-stone-800/60 border border-stone-600/50 text-white placeholder-stone-400/60 px-4 py-3 rounded-xl outline-none focus:border-lime-400 focus:ring-2 focus:ring-lime-400/30 transition">
            </div>
            <div>
                <label for="unit" class="block text-xs font-semibold text-white mb-2">
                    Unit <span class="text-red-400">*</span>
                </label>
                <select name="unit" id="unit" required onchange="toggleSizeInput()"
                        class="w-full bg-stone-800/60 border border-stone-600/50 text-white px-4 py-3 rounded-xl outline-none appearance-none focus:border-lime-400 focus:ring-2 focus:ring-lime-400/30 transition">
                    <option value="" class="bg-stone-800 text-white" data-size="none">-- Select Unit --</option>

                    <option value="pcs"    class="bg-stone-800 text-white" data-size="none"  {{ old('unit', 'pcs') == 'pcs' ? 'selected' : '' }}>pcs (pieces)</option>
                    <option value="kg"     class="bg-stone-800 text-white" data-size="weight" data-label="kg"    {{ old('unit') == 'kg' ? 'selected' : '' }}>kg (kilogram)</option>
                    <option value="g"      class="bg-stone-800 text-white" data-size="weight" data-label="g"     {{ old('unit') == 'g' ? 'selected' : '' }}>g (gram)</option>
                    <option value="litre"  class="bg-stone-800 text-white" data-size="volume" data-label="L"     {{ old('unit') == 'litre' ? 'selected' : '' }}>litre</option>
                    <option value="ml"     class="bg-stone-800 text-white" data-size="volume" data-label="ml"    {{ old('unit') == 'ml' ? 'selected' : '' }}>ml (millilitre)</option>
                    <option value="box"    class="bg-stone-800 text-white" data-size="none"  {{ old('unit') == 'box' ? 'selected' : '' }}>box</option>
                    <option value="pack"   class="bg-stone-800 text-white" data-size="none"  {{ old('unit') == 'pack' ? 'selected' : '' }}>pack</option>
                    <option value="carton" class="bg-stone-800 text-white" data-size="none"  {{ old('unit') == 'carton' ? 'selected' : '' }}>carton</option>
                    <option value="bottle" class="bg-stone-800 text-white" data-size="none"  {{ old('unit') == 'bottle' ? 'selected' : '' }}>bottle</option>
                    <option value="can"    class="bg-stone-800 text-white" data-size="none"  {{ old('unit') == 'can' ? 'selected' : '' }}>can</option>
                    <option value="sachet" class="bg-stone-800 text-white" data-size="none"  {{ old('unit') == 'sachet' ? 'selected' : '' }}>sachet</option>
                    <option value="dozen"  class="bg-stone-800 text-white" data-size="none"  {{ old('unit') == 'dozen' ? 'selected' : '' }}>dozen</option>
                    <option value="bundle" class="bg-stone-800 text-white" data-size="none"  {{ old('unit') == 'bundle' ? 'selected' : '' }}>bundle</option>
                    <option value="bag"    class="bg-stone-800 text-white" data-size="none"  {{ old('unit') == 'bag' ? 'selected' : '' }}>bag</option>
                    <option value="roll"   class="bg-stone-800 text-white" data-size="none"  {{ old('unit') == 'roll' ? 'selected' : '' }}>roll</option>
                    <option value="metre"  class="bg-stone-800 text-white" data-size="none"  {{ old('unit') == 'metre' ? 'selected' : '' }}>metre</option>
                    <option value="pair"   class="bg-stone-800 text-white" data-size="none"  {{ old('unit') == 'pair' ? 'selected' : '' }}>pair</option>
                    <option value="set"    class="bg-stone-800 text-white" data-size="none"  {{ old('unit') == 'set' ? 'selected' : '' }}>set</option>
                    <option value="other"  class="bg-stone-800 text-white" data-size="none"  {{ old('unit') == 'other' ? 'selected' : '' }}>Other</option>
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
                       value="{{ old('size') }}"
                       min="0" step="0.01" placeholder="0"
                       class="flex-1 bg-stone-800/60 border border-stone-600/50 text-white placeholder-stone-400/60 px-4 py-3 rounded-xl outline-none focus:border-lime-400 focus:ring-2 focus:ring-lime-400/30 transition">
                <span id="size-unit-display"
                      class="px-4 py-3 rounded-xl bg-stone-800/60 border border-stone-600/50 text-lime-400 font-bold text-sm min-w-[60px] text-center">
                    L
                </span>
            </div>
            <p class="text-white/70 text-xs mt-1">
                <span id="size-hint">Enter the size (e.g. 1.5 for 1.5L)</span>
            </p>
        </div>

        {{-- Prices --}}
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="cost_price" class="block text-xs font-semibold text-white mb-2">Cost Price (optional)</label>
                <input type="number" name="cost_price" id="cost_price" value="{{ old('cost_price') }}"
                       min="0" step="0.01" placeholder="0"
                       class="w-full bg-stone-800/60 border border-stone-600/50 text-white placeholder-stone-400/60 px-4 py-3 rounded-xl outline-none focus:border-lime-400 focus:ring-2 focus:ring-lime-400/30 transition">
            </div>
            <div>
                <label for="selling_price" class="block text-xs font-semibold text-white mb-2">
                    Selling Price <span class="text-red-400">*</span>
                </label>
                <input type="number" name="selling_price" id="selling_price" value="{{ old('selling_price') }}"
                       min="0" step="0.01" required placeholder="0"
                       class="w-full bg-stone-800/60 border border-stone-600/50 text-white placeholder-stone-400/60 px-4 py-3 rounded-xl outline-none focus:border-lime-400 focus:ring-2 focus:ring-lime-400/30 transition">
            </div>
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
                        Initial Quantity <span class="text-red-400">*</span>
                    </label>
                    <input type="number" name="quantity" id="quantity"
                           value="{{ old('quantity', 0) }}"
                           min="0" step="1" required placeholder="0"
                           class="w-full bg-stone-800/60 border border-stone-600/50 text-white placeholder-stone-400/60 px-4 py-3 rounded-xl outline-none focus:border-lime-400 focus:ring-2 focus:ring-lime-400/30 transition">
                    <p class="text-white/70 text-xs mt-1">How many items are in stock?</p>
                </div>
                <div>
                    <label for="min_quantity" class="block text-xs font-semibold text-white mb-2">
                        Min Quantity (alert)
                    </label>
                    <input type="number" name="min_quantity" id="min_quantity"
                           value="{{ old('min_quantity', 5) }}"
                           min="0" step="1" placeholder="5"
                           class="w-full bg-stone-800/60 border border-stone-600/50 text-white placeholder-stone-400/60 px-4 py-3 rounded-xl outline-none focus:border-lime-400 focus:ring-2 focus:ring-lime-400/30 transition">
                    <p class="text-white/70 text-xs mt-1">Alert when stock is below this</p>
                </div>
            </div>
        </div>

        {{-- Active --}}
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" checked
                   class="w-4 h-4 rounded border-stone-600 bg-stone-800/60 text-lime-500 focus:ring-lime-400/50">
            <label for="is_active" class="text-sm text-white">Active product</label>
        </div>

        {{-- Buttons --}}
        <div class="flex items-center gap-3 pt-3">
            <button type="submit" id="submit-btn"
                    class="flex-1 bg-gradient-to-br from-yellow-600 to-lime-600 hover:from-yellow-700 hover:to-lime-700 text-white font-bold py-3 rounded-xl transition shadow-lg disabled:opacity-50">
                <span id="submit-text">Add Product</span>
            </button>
            <a href="{{ route('products.index') }}"
               class="px-5 py-3 rounded-xl bg-stone-800/60 hover:bg-stone-700/60 text-white text-sm font-bold transition border border-stone-600/50">
                Cancel
            </a>
        </div>
    </form>

    {{-- JavaScript: Dynamic Size Input + AJAX Form --}}
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
                sizeInput.value = '';
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

        // ===== AJAX Form Submission =====
        (function () {
            const form = document.getElementById('product-form');
            const submitBtn = document.getElementById('submit-btn');
            const submitText = document.getElementById('submit-text');
            const successMsg = document.getElementById('success-message');
            const successText = document.getElementById('success-text');
            const errorMsg = document.getElementById('error-message');
            const errorText = document.getElementById('error-text');

            if (!form) return;

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                e.stopPropagation();

                // Hide previous messages
                successMsg.classList.add('hidden');
                errorMsg.classList.add('hidden');

                // Disable submit button
                submitBtn.disabled = true;
                submitText.textContent = 'Adding...';

                // Get form data
                const formData = new FormData(form);

                // Send via fetch
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                })
                .then(async function (r) {
                    const data = await r.json().catch(() => ({}));

                    if (!r.ok) {
                        if (data.errors) {
                            const firstError = Object.values(data.errors).flat()[0];
                            throw new Error(firstError || 'Validation failed.');
                        }
                        throw new Error(data.message || data.error || 'Failed to add product.');
                    }

                    return data;
                })
                .then(function (data) {
                    // Success
                    successText.textContent = data.message || 'Product added successfully!';
                    successMsg.classList.remove('hidden');

                    // Reset form (except store_id)
                    const storeValue = document.getElementById('store_id').value;
                    form.reset();
                    document.getElementById('store_id').value = storeValue;

                    // Hide size input
                    document.getElementById('size-wrapper').classList.add('hidden');

                    // Scroll to top
                    window.scrollTo({ top: 0, behavior: 'smooth' });

                    // Auto-hide success message after 5 seconds
                    setTimeout(function () {
                        successMsg.classList.add('hidden');
                    }, 5000);
                })
                .catch(function (err) {
                    errorText.textContent = err.message;
                    errorMsg.classList.remove('hidden');

                    window.scrollTo({ top: 0, behavior: 'smooth' });

                    // Auto-hide error after 5 seconds
                    setTimeout(function () {
                        errorMsg.classList.add('hidden');
                    }, 5000);
                })
                .finally(function () {
                    submitBtn.disabled = false;
                    submitText.textContent = 'Add Product';
                });
            });
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