@extends('layouts.app')

@section('title', 'Add Product')

@section('content')

    <a href="{{ url()->previous() }}"
       class="inline-flex items-center gap-2 text-cyan-400 hover:text-cyan-300 text-sm mb-4 fade-in-up d-1 font-bold">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back
    </a>

    {{-- Header --}}
    <div class="fade-in-up d-1 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 mb-6 relative overflow-hidden shadow-2xl border border-blue-700/50">
        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-full -mr-20 -mt-20 pointer-events-none"></div>
        <div class="relative z-10 flex items-start justify-between gap-4">
            <div>
                <h2 class="text-white text-xl sm:text-2xl font-bold">Add New Product</h2>
                <p class="text-blue-200 text-xs sm:text-sm mt-1">
                    You will be recorded as the one who added this product
                </p>
            </div>

            @if($selectedCategoryName)
                <div class="flex-shrink-0 text-right">
                    <p class="text-[10px] uppercase font-bold text-cyan-400 tracking-wider">Category</p>
                    <p class="text-white text-sm font-semibold">{{ $selectedCategoryName }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Success --}}
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

    {{-- Error --}}
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
          class="fade-in-up d-2 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-5 sm:p-6 space-y-6 shadow-2xl border border-blue-700/50">
        @csrf

        {{-- SECTION 1: Added By --}}
        <div>
            <label class="block text-xs font-semibold text-white mb-2">Added By</label>
            <div class="flex items-center gap-2 bg-blue-900/40 backdrop-blur border border-blue-700/50 rounded-xl px-4 py-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold text-xs shadow-lg">
                    {{ strtoupper(substr(auth()->user()->full_name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-white text-sm font-semibold">{{ auth()->user()->full_name }}</p>
                    <p class="text-blue-200 text-xs capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>
        </div>

        {{-- SECTION 2: Store & Category --}}
        <div>
            <h3 class="text-white font-bold text-sm mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 7l9-4 9 4M3 7v10l9 4 9-4V7M3 7l9 4m9-4l-9 4m0 0v10"/>
                </svg>
                Store &amp; Category
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                {{-- Store --}}
                <div>
                    <label class="block text-xs font-semibold text-white mb-2">
                        Store <span class="text-red-400">*</span>
                    </label>

                    @if(isset($selectedStoreId) && $selectedStoreId)
                        <div class="flex items-center gap-2 bg-blue-900/40 border border-cyan-500/40 rounded-xl px-4 py-3">
                            <svg class="w-4 h-4 text-cyan-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="text-white text-sm font-semibold truncate">
                                {{ $stores->firstWhere('id', $selectedStoreId)->name ?? 'Selected Store' }}
                            </span>
                        </div>
                        <input type="hidden" name="store_id" id="store_id" value="{{ $selectedStoreId }}">
                        <p class="text-blue-200/70 text-xs mt-1">🔒 Locked for this flow</p>
                    @else
                        <select name="store_id" id="store_id" required
                                class="w-full bg-blue-900/40 border border-blue-700/50 text-white px-4 py-3 rounded-xl outline-none appearance-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                            <option value="" class="bg-blue-950 text-white">-- Select Store --</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}" class="bg-blue-950 text-white"
                                    {{ old('store_id', $selectedStoreId ?? '') == $store->id ? 'selected' : '' }}>
                                    {{ $store->name }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>

                {{-- Category --}}
                <div>
                    <label class="block text-xs font-semibold text-white mb-2">
                        Category <span class="text-red-400">*</span>
                    </label>

                    @if(isset($selectedCategoryId) && $selectedCategoryId)
                        <div class="flex items-center gap-2 bg-blue-900/40 border border-cyan-500/40 rounded-xl px-4 py-3">
                            <svg class="w-4 h-4 text-cyan-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M7 7h.01M7 3h5a2 2 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span class="text-white text-sm font-semibold truncate">
                                {{ $selectedCategoryName ?? 'Selected Category' }}
                            </span>
                        </div>
                        <input type="hidden" name="category_id" id="category_id" value="{{ $selectedCategoryId }}">
                        <p class="text-blue-200/70 text-xs mt-1">🔒 Locked for this flow</p>
                    @else
                        <select name="category_id" id="category_id" required
                                class="w-full bg-blue-900/40 border border-blue-700/50 text-white px-4 py-3 rounded-xl outline-none appearance-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                            <option value="" class="bg-blue-950 text-white">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" class="bg-blue-950 text-white"
                                    {{ old('category_id', $selectedCategoryId ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>
            </div>
        </div>

        {{-- SECTION 3: Basic info --}}
        <div class="border-t border-blue-700/50 pt-5">
            <h3 class="text-white font-bold text-sm mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Basic Information
            </h3>

            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-xs font-semibold text-white mb-2">
                        Product Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           placeholder="e.g. Coca Cola 500ml"
                           class="w-full bg-blue-900/40 border border-blue-700/50 text-white placeholder-blue-200/40 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                </div>

                {{-- ============================================== --}}
                {{-- UNIT OF SALE — dropdown if multiple, badge if single --}}
                {{-- ============================================== --}}
                @if(isset($categoryUnits) && count($categoryUnits) > 0)

                    <div class="bg-blue-900/40 border border-cyan-500/50 rounded-xl px-4 py-3 flex items-start gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center flex-shrink-0 shadow-lg mt-0.5">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                            </svg>
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-white font-bold text-[10px] uppercase tracking-wider opacity-70">
                                Unit of Sale
                            </p>

                            @if(count($categoryUnits) === 1)
                                {{-- Single unit → display only --}}
                                <p class="text-white font-bold text-sm uppercase mt-0.5">
                                    {{ $categoryUnits[0] }}
                                </p>
                            @else
                                {{-- Multiple units → dropdown --}}
                                <select name="unit" id="unit"
                                        class="mt-1 w-full bg-blue-900/60 border border-cyan-400/40 text-white font-bold text-sm px-3 py-2 rounded-lg outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                                    @foreach($categoryUnits as $unitOption)
                                        <option value="{{ $unitOption }}"
                                                class="bg-blue-950 text-white"
                                                {{ old('unit', $categoryUnit) == $unitOption ? 'selected' : '' }}>
                                            {{ strtoupper($unitOption) }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-white font-bold text-[10px] mt-1 opacity-60">
                                    Chagua unit inayolingana na bidhaa hii
                                </p>
                            @endif
                        </div>

                        <span class="text-white font-bold text-[10px] px-2 py-1 rounded-lg bg-cyan-500/30 border border-cyan-400/40 uppercase flex-shrink-0 mt-0.5">
                            {{ count($categoryUnits) > 1 ? 'Pick' : 'Auto' }}
                        </span>
                    </div>

                    {{-- Hidden input for single unit --}}
                    @if(count($categoryUnits) === 1)
                        <input type="hidden" name="unit" value="{{ $categoryUnits[0] }}">
                    @endif
                @endif
            </div>
        </div>

        {{-- SECTION 4: Category-specific specs (only if any) --}}
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
                    @foreach($categorySpecs as $spec)
                        @if(!is_array($spec)) @continue @endif
                        <div class="{{ ($spec['type'] ?? 'text') === 'select' ? 'sm:col-span-2' : '' }}">
                            <label for="spec_{{ $spec['name'] }}" class="block text-xs font-semibold text-white mb-2">
                                {{ $spec['label'] }}
                                @if($spec['required'] ?? false)
                                    <span class="text-red-400">*</span>
                                @endif
                                @if(isset($spec['unit']))
                                    <span class="text-cyan-400 font-normal">({{ $spec['unit'] }})</span>
                                @endif
                            </label>

                            @if(($spec['type'] ?? 'text') === 'select')
                                <select name="specs[{{ $spec['name'] }}]" id="spec_{{ $spec['name'] }}"
                                        {{ ($spec['required'] ?? false) ? 'required' : '' }}
                                        class="w-full bg-blue-900/40 border border-blue-700/50 text-white px-4 py-3 rounded-xl outline-none appearance-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                                    <option value="" class="bg-blue-950 text-white">-- Select {{ $spec['label'] }} --</option>
                                    @foreach($spec['options'] ?? [] as $option)
                                        <option value="{{ $option }}" class="bg-blue-950 text-white"
                                            {{ old("specs.{$spec['name']}") == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            @elseif(($spec['type'] ?? 'text') === 'number')
                                <input type="number" name="specs[{{ $spec['name'] }}]" id="spec_{{ $spec['name'] }}"
                                       value="{{ old("specs.{$spec['name']}") }}"
                                       step="0.01" min="0"
                                       placeholder="{{ $spec['placeholder'] ?? '0' }}"
                                       {{ ($spec['required'] ?? false) ? 'required' : '' }}
                                       class="w-full bg-blue-900/40 border border-blue-700/50 text-white placeholder-blue-200/40 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                            @else
                                <input type="text" name="specs[{{ $spec['name'] }}]" id="spec_{{ $spec['name'] }}"
                                       value="{{ old("specs.{$spec['name']}") }}"
                                       placeholder="{{ $spec['placeholder'] ?? '' }}"
                                       {{ ($spec['required'] ?? false) ? 'required' : '' }}
                                       class="w-full bg-blue-900/40 border border-blue-700/50 text-white placeholder-blue-200/40 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- SECTION 5: Pricing --}}
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
                    <input type="number" name="cost_price" id="cost_price" value="{{ old('cost_price') }}"
                           min="0" step="0.01" placeholder="0"
                           class="w-full bg-blue-900/40 border border-blue-700/50 text-white placeholder-blue-200/40 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                </div>
                <div>
                    <label for="selling_price" class="block text-xs font-semibold text-white mb-2">
                        Selling Price <span class="text-red-400">*</span>
                    </label>
                    <input type="number" name="selling_price" id="selling_price" value="{{ old('selling_price') }}"
                           min="0" step="0.01" required placeholder="0"
                           class="w-full bg-blue-900/40 border border-blue-700/50 text-white placeholder-blue-200/40 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                </div>
            </div>
        </div>

        {{-- SECTION 6: Stock --}}
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
                        Initial Quantity <span class="text-red-400">*</span>
                    </label>
                    <input type="number" name="quantity" id="quantity"
                           value="{{ old('quantity', 0) }}"
                           min="0" step="1" required placeholder="0"
                           class="w-full bg-blue-900/40 border border-blue-700/50 text-white placeholder-blue-200/40 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                </div>
                <div>
                    <label for="min_quantity" class="block text-xs font-semibold text-white mb-2">
                        Min Quantity (alert)
                    </label>
                    <input type="number" name="min_quantity" id="min_quantity"
                           value="{{ old('min_quantity', 5) }}"
                           min="0" step="1" placeholder="5"
                           class="w-full bg-blue-900/40 border border-blue-700/50 text-white placeholder-blue-200/40 px-4 py-3 rounded-xl outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">
                </div>
            </div>
        </div>

        {{-- Active --}}
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" checked
                   class="w-4 h-4 rounded border-blue-700 bg-blue-900/40 text-cyan-500 focus:ring-cyan-400/50">
            <label for="is_active" class="text-sm text-white">Active product</label>
        </div>

        {{-- Buttons --}}
        <div class="flex items-center gap-3 pt-3 border-t border-blue-700/50">
            <button type="submit" id="submit-btn"
                    class="flex-1 bg-gradient-to-br from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-bold py-3 rounded-xl transition shadow-lg disabled:opacity-50">
                <span id="submit-text">Add Product</span>
            </button>
            <a href="{{ url()->previous() }}"
               class="px-5 py-3 rounded-xl bg-blue-900/40 hover:bg-blue-800/50 text-white text-sm font-bold transition border border-blue-700/50">
                Cancel
            </a>
        </div>
    </form>

    <script>
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

                successMsg.classList.add('hidden');
                errorMsg.classList.add('hidden');

                submitBtn.disabled = true;
                submitText.textContent = 'Adding...';

                const formData = new FormData(form);

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
                    successText.textContent = data.message || 'Product added successfully!';
                    successMsg.classList.remove('hidden');

                    const lockedStore    = document.getElementById('store_id');
                    const lockedCategory = document.getElementById('category_id');
                    const storeVal       = lockedStore ? lockedStore.value : '';
                    const categoryVal    = lockedCategory ? lockedCategory.value : '';

                    form.reset();

                    if (lockedStore) lockedStore.value = storeVal;
                    if (lockedCategory) lockedCategory.value = categoryVal;

                    window.scrollTo({ top: 0, behavior: 'smooth' });

                    setTimeout(function () {
                        successMsg.classList.add('hidden');
                    }, 5000);
                })
                .catch(function (err) {
                    errorText.textContent = err.message;
                    errorMsg.classList.remove('hidden');

                    window.scrollTo({ top: 0, behavior: 'smooth' });

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
    </script>

@endsection