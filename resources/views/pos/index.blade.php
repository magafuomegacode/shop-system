@extends('layouts.app')

@section('title', 'POS - Sell')

@section('content')

    {{-- ========================================================== --}}
    {{-- STORE PICKER --}}
    {{-- ========================================================== --}}
    @if(!$selectedStore)
        <div class="fade-in-up d-1 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-6 mb-6 shadow-2xl border border-blue-700/50 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-48 h-48 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-full -mr-24 -mt-24 pointer-events-none"></div>

            <div class="relative z-10">
                <h2 class="text-white font-bold text-xl mb-1">Choose a Store</h2>
                <p class="text-white font-bold text-sm mb-5 opacity-80">Select the store you want to sell from</p>

                @if($stores->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($stores as $store)
                            <a href="{{ route('pos.index', ['store_id' => $store->id]) }}"
                               class="group bg-blue-900/40 backdrop-blur rounded-2xl p-5 hover:bg-blue-800/60 transition flex items-center gap-3 border border-blue-700/50 hover:border-cyan-400/80 hover:shadow-lg hover:shadow-cyan-500/20">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center flex-shrink-0 shadow-lg group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                                    </svg>
                                </div>
                                <div class="text-left min-w-0 flex-1">
                                    <p class="text-white font-bold truncate">{{ $store->name }}</p>
                                    <p class="text-white font-bold text-xs opacity-70">Tap to select</p>
                                </div>
                                <svg class="w-5 h-5 text-white/50 group-hover:text-cyan-300 group-hover:translate-x-1 transition-all flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="bg-yellow-500/20 border border-yellow-500/40 text-white font-bold px-4 py-3 rounded-xl text-sm">
                        No stores available. Contact your admin to create a store.
                    </div>
                @endif
            </div>
        </div>
    @else

    {{-- ========================================================== --}}
    {{-- STORE HEADER --}}
    {{-- ========================================================== --}}
    <div class="fade-in-up d-1 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 mb-4 flex items-center justify-between gap-3 shadow-2xl border border-blue-700/50">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center flex-shrink-0 shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-white font-bold text-[10px] uppercase tracking-widest opacity-70">Selling from</p>
                <p class="text-white font-bold truncate">{{ $selectedStore->name }}</p>
            </div>
        </div>
        <a href="{{ route('pos.index') }}"
           class="flex-shrink-0 bg-blue-900/40 hover:bg-blue-800/60 border border-blue-700/50 text-white font-bold text-xs px-3 py-2 rounded-lg transition flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
            Change
        </a>
    </div>

    {{-- ========================================================== --}}
    {{-- POS LAYOUT --}}
    {{-- ========================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- =============== PRODUCTS PANEL =============== --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Search --}}
            <div class="fade-in-up d-2 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 shadow-2xl border border-blue-700/50">
                <div class="relative">
                    <svg class="w-5 h-5 text-white/60 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="pos-search"
                           placeholder="Search products by name or scan barcode..."
                           autocomplete="off"
                           autofocus
                           class="w-full bg-blue-900/40 backdrop-blur border border-blue-700/50 text-white font-bold placeholder-white/40 pl-12 pr-4 py-3.5 rounded-xl outline-none text-sm focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30 transition">

                    <kbd class="hidden sm:flex absolute right-3 top-1/2 -translate-y-1/2 items-center gap-1 text-[10px] text-white/40 font-bold">
                        <span class="px-1.5 py-0.5 rounded bg-blue-800/60 border border-blue-700/50">/</span>
                        <span>to search</span>
                    </kbd>
                </div>
            </div>

            {{-- Product grid --}}
            <div class="fade-in-up d-2 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 shadow-2xl border border-blue-700/50">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-white font-bold text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Products
                    </h3>
                    <span id="product-count-label" class="text-white font-bold text-[10px] opacity-60"></span>
                </div>

                <div id="product-grid" class="grid grid-cols-2 sm:grid-cols-3 gap-2 min-h-[200px]">
                    <div class="col-span-full flex flex-col items-center justify-center py-12 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-blue-900/40 border border-blue-700/50 flex items-center justify-center mb-3">
                            <svg class="w-8 h-8 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <p class="text-white font-bold text-sm opacity-70">Type to search products</p>
                        <p class="text-white font-bold text-xs opacity-50 mt-1">Start typing a product name or scan a barcode</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- =============== CART PANEL =============== --}}
        <div class="lg:col-span-1">
            <div class="fade-in-up d-2 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 rounded-2xl p-4 lg:sticky lg:top-20 shadow-2xl border border-blue-700/50">

                {{-- Cart header --}}
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-white font-bold flex items-center gap-2">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Cart
                    </h3>
                    <span id="cart-count" class="text-xs bg-gradient-to-br from-blue-500 to-cyan-500 text-white border border-cyan-400/40 px-2.5 py-0.5 rounded-full font-bold shadow-lg">0</span>
                </div>

                {{-- Cart items --}}
                <div id="cart-items" class="space-y-2 max-h-[280px] overflow-y-auto mb-3 pr-1">
                    <div class="flex flex-col items-center justify-center py-10 text-center">
                        <svg class="w-10 h-10 text-white/20 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17"/>
                        </svg>
                        <p class="text-white font-bold text-sm opacity-60">Cart is empty</p>
                        <p class="text-white font-bold text-xs opacity-40 mt-0.5">Add products to get started</p>
                    </div>
                </div>

                {{-- Discount --}}
                <div class="border-t border-blue-700/50 pt-3 mb-3">
                    <label class="text-white font-bold text-xs uppercase tracking-wider mb-1.5 block opacity-80">Discount</label>
                    <div class="flex gap-2">
                        <select id="discount-type"
                                class="bg-blue-900/40 border border-blue-700/50 text-white font-bold px-2 py-2 rounded-lg text-xs flex-1 outline-none focus:border-cyan-400 transition">
                            <option value="none" class="bg-blue-950 text-white">None</option>
                            @if($discountAllowed)
                                <option value="percent" class="bg-blue-950 text-white">Percent (%)</option>
                                <option value="amount" class="bg-blue-950 text-white">Amount</option>
                            @endif
                        </select>
                        <input type="number" id="discount-value" value="0" min="0" step="0.01"
                               class="bg-blue-900/40 border border-blue-700/50 text-white font-bold px-2 py-2 rounded-lg text-xs w-24 outline-none focus:border-cyan-400 transition">
                    </div>
                    @if($discountAllowed)
                        <p class="text-white font-bold text-[10px] mt-1.5 opacity-60">
                            Max allowed: {{ $maxDiscount }}%
                        </p>
                    @endif
                </div>

                {{-- Totals --}}
                <div class="border-t border-blue-700/50 pt-3 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-white font-bold opacity-80">Subtotal</span>
                        <span class="text-white font-bold" id="subtotal">TSh 0</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white font-bold opacity-80">Discount</span>
                        <span class="text-white font-bold" id="discount-amount">-TSh 0</span>
                    </div>
                    <div class="flex justify-between text-lg border-t border-blue-700/50 pt-2 mt-2">
                        <span class="text-white font-bold">TOTAL</span>
                        <span class="text-white font-bold text-cyan-400" id="total">TSh 0</span>
                    </div>
                </div>

                {{-- Payment --}}
                <div class="mt-3">
                    <label class="text-white font-bold text-xs uppercase tracking-wider mb-1.5 block opacity-80">Payment Method</label>
                    <select id="payment-method"
                            class="bg-blue-900/40 border border-blue-700/50 text-white font-bold w-full px-3 py-2.5 rounded-lg text-sm outline-none focus:border-cyan-400 transition">
                        <option value="cash" class="bg-blue-950 text-white">💵 Cash</option>
                        <option value="mobile" class="bg-blue-950 text-white">📱 Mobile Money</option>
                        <option value="card" class="bg-blue-950 text-white">💳 Card</option>
                    </select>
                </div>

                {{-- Actions --}}
                <button type="button" id="complete-sale"
                        class="w-full mt-4 bg-gradient-to-br from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-bold py-3.5 rounded-xl transition shadow-lg shadow-cyan-500/30 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span id="complete-sale-text">Complete Sale</span>
                </button>

                <button type="button" id="clear-cart"
                        class="w-full mt-2 bg-blue-900/40 hover:bg-red-500/20 border border-blue-700/50 hover:border-red-500/40 text-white font-bold text-xs py-2.5 rounded-xl transition flex items-center justify-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Clear Cart
                </button>
            </div>
        </div>
    </div>
    @endif

@endsection

{{-- ========================================================== --}}
{{-- SCRIPT --}}
{{-- ========================================================== --}}
@if($selectedStore)
    <script>
    (function () {
        'use strict';

        function init() {
            // === ELEMENTS ===
            const searchInput    = document.getElementById('pos-search');
            const productGrid    = document.getElementById('product-grid');
            const productCount   = document.getElementById('product-count-label');
            const cartItems      = document.getElementById('cart-items');
            const cartCount      = document.getElementById('cart-count');
            const subtotalEl     = document.getElementById('subtotal');
            const discountAmtEl  = document.getElementById('discount-amount');
            const totalEl        = document.getElementById('total');
            const discountTypeEl = document.getElementById('discount-type');
            const discountValEl  = document.getElementById('discount-value');
            const paymentEl      = document.getElementById('payment-method');
            const completeBtn    = document.getElementById('complete-sale');
            const completeTxt    = document.getElementById('complete-sale-text');
            const clearBtn       = document.getElementById('clear-cart');

            if (!searchInput || !productGrid) return;

            // === CONFIG ===
            const storeId    = {{ $selectedStoreId }};
            const csrfToken  = '{{ csrf_token() }}';
            const searchUrl  = '{{ route('pos.search-products') }}';
            const storeUrl   = '{{ route('pos.store') }}';
            const maxDiscount = {{ $maxDiscount ?? 20 }};

            let cart = {};
            let searchTimer;

            // === SEARCH ===
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(loadProducts, 220);
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === '/' && document.activeElement !== searchInput) {
                    e.preventDefault();
                    searchInput.focus();
                }
            });

            function loadProducts() {
                const q = searchInput.value.trim();

                if (!q) {
                    productGrid.innerHTML = `
                        <div class="col-span-full flex flex-col items-center justify-center py-12 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-blue-900/40 border border-blue-700/50 flex items-center justify-center mb-3">
                                <svg class="w-8 h-8 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <p class="text-white font-bold text-sm opacity-70">Type to search products</p>
                            <p class="text-white font-bold text-xs opacity-50 mt-1">Start typing a product name or scan a barcode</p>
                        </div>`;
                    if (productCount) productCount.textContent = '';
                    return;
                }

                productGrid.innerHTML = `
                    <div class="col-span-full flex items-center justify-center py-12">
                        <svg class="w-6 h-6 text-cyan-400 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>`;

                const url = searchUrl + '?store_id=' + storeId + '&search=' + encodeURIComponent(q);

                fetch(url, { headers: { 'Accept': 'application/json' } })
                    .then(r => r.json())
                    .then(data => renderProducts(data))
                    .catch(err => {
                        productGrid.innerHTML = `
                            <div class="col-span-full text-center text-red-300 font-bold text-sm py-12">
                                Error: ${escapeHtml(err.message)}
                            </div>`;
                    });
            }

            /**
             * Build the display text for a product.
             * Uses product's unit (pcs, set, m2, box, etc.) + size.
             * Examples: "SET", "3 m2", "500 ml", "M2"
             */
            function buildUnitDisplay(product) {
                const unitRaw = (product.unit || '').toString().trim().toLowerCase();

                // Friendly label map
                const unitLabels = {
                    pcs:   'PCS',
                    piece: 'PCS',
                    set:   'SET',
                    pair:  'PAIR',
                    pack:  'PACK',
                    box:   'BOX',
                    bunch: 'BUNCH',
                    m2:    'M²',
                    sqm:   'M²',
                    m:     'M',
                    metre: 'M',
                    kg:    'KG',
                    g:     'G',
                    litre: 'L',
                    l:     'L',
                    ml:    'ML',
                };

                const friendlyUnit = unitLabels[unitRaw] || (unitRaw ? unitRaw.toUpperCase() : '');

                // If size is available and unit is metric (m2, m, kg, ml, etc.), show "SIZE UNIT" e.g. "3 M²"
                const size = product.size;

                if (size !== null && size !== undefined && size !== '' && friendlyUnit) {
                    let sizeValue = String(size);
                    if (sizeValue.indexOf('.') !== -1) {
                        sizeValue = sizeValue.replace(/\.?0+$/, '');
                    }
                    return sizeValue + ' ' + friendlyUnit;
                }

                return friendlyUnit;
            }

            function renderProducts(products) {
                if (!products || !products.length) {
                    productGrid.innerHTML = `
                        <div class="col-span-full flex flex-col items-center justify-center py-12 text-center">
                            <svg class="w-12 h-12 text-white/20 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-white font-bold text-sm opacity-70">No products found</p>
                            <p class="text-white font-bold text-xs opacity-50 mt-1">Try a different search term</p>
                        </div>`;
                    if (productCount) productCount.textContent = '0 results';
                    return;
                }

                if (productCount) {
                    productCount.textContent = products.length + ' result' + (products.length === 1 ? '' : 's');
                }

                let html = '';
                products.forEach(function (p) {
                    const disabled = p.stock <= 0;
                    const unitDisplay = buildUnitDisplay(p);

                    html += `<button type="button"
                        data-id="${p.id}"
                        data-name="${escapeAttr(p.name)}"
                        data-price="${p.selling_price}"
                        data-stock="${p.stock}"
                        data-unit="${escapeAttr(p.unit || '')}"
                        data-unit-display="${escapeAttr(unitDisplay)}"
                        class="product-btn text-left bg-blue-900/40 border border-blue-700/50 hover:border-cyan-400/80 hover:bg-blue-800/60 rounded-xl p-3 transition group ${disabled ? 'opacity-40 cursor-not-allowed' : 'hover:shadow-lg hover:shadow-cyan-500/20 active:scale-95'}"
                        ${disabled ? 'disabled' : ''}>

                        <div class="flex items-start justify-between gap-2 mb-1">
                            <p class="text-white font-bold text-xs truncate flex-1">${escapeHtml(p.name)}</p>
                            ${disabled ? '<span class="text-[9px] px-1.5 py-0.5 rounded bg-red-500/30 text-white font-bold border border-red-400/40 uppercase flex-shrink-0">Out</span>' : ''}
                        </div>

                        ${unitDisplay ? `
                            <p class="text-cyan-400 font-bold text-[11px] mb-1">
                                ${escapeHtml(unitDisplay)}
                            </p>
                        ` : ''}

                        <p class="text-white font-bold text-sm mt-1">TSh ${formatNumber(p.selling_price)}</p>

                        <div class="flex items-center gap-1 mt-1.5">
                            <div class="flex-1 h-1 rounded-full bg-blue-900/60 overflow-hidden">
                                <div class="h-full ${p.stock <= 5 ? 'bg-red-500' : (p.stock <= 10 ? 'bg-yellow-500' : 'bg-green-500')}"
                                     style="width: ${Math.min(100, (p.stock / 20) * 100)}%"></div>
                            </div>
                            <p class="text-white font-bold text-[10px] opacity-70 flex-shrink-0">
                                ${p.stock}${unitDisplay ? ' ' + unitDisplay : ''}
                            </p>
                        </div>
                    </button>`;
                });

                productGrid.innerHTML = html;

                document.querySelectorAll('.product-btn').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        addToCart(
                            parseInt(this.dataset.id),
                            this.dataset.name,
                            parseFloat(this.dataset.price),
                            parseInt(this.dataset.stock),
                            this.dataset.unit,
                            this.dataset.unitDisplay
                        );
                    });
                });
            }

            // === CART ===
            function addToCart(id, name, price, stock, unit, unitDisplay) {
                if (cart[id]) {
                    if (cart[id].quantity >= stock) {
                        flashToast('Only ' + stock + ' ' + (unitDisplay || '') + ' available', 'warning');
                        return;
                    }
                    cart[id].quantity += 1;
                } else {
                    cart[id] = {
                        id,
                        name,
                        price,
                        quantity: 1,
                        stock,
                        unit,
                        unitDisplay
                    };
                }
                renderCart();
            }

            function updateQty(id, delta) {
                if (!cart[id]) return;
                const newQty = cart[id].quantity + delta;
                if (newQty <= 0) {
                    delete cart[id];
                } else if (newQty > cart[id].stock) {
                    flashToast('Only ' + cart[id].stock + ' ' + (cart[id].unitDisplay || '') + ' available', 'warning');
                    return;
                } else {
                    cart[id].quantity = newQty;
                }
                renderCart();
            }

            function removeFromCart(id) {
                delete cart[id];
                renderCart();
            }

            function renderCart() {
                const ids = Object.keys(cart);
                cartCount.textContent = ids.length;

                if (!ids.length) {
                    cartItems.innerHTML = `
                        <div class="flex flex-col items-center justify-center py-10 text-center">
                            <svg class="w-10 h-10 text-white/20 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17"/>
                            </svg>
                            <p class="text-white font-bold text-sm opacity-60">Cart is empty</p>
                            <p class="text-white font-bold text-xs opacity-40 mt-0.5">Add products to get started</p>
                        </div>`;
                } else {
                    let html = '';
                    ids.forEach(function (id) {
                        const item = cart[id];
                        let displayName = escapeHtml(item.name);
                        if (item.unitDisplay) {
                            displayName += ' <span class="text-cyan-400 font-bold">(' + escapeHtml(item.unitDisplay) + ')</span>';
                        }

                        html += `
                            <div class="flex items-center gap-2 bg-blue-900/40 border border-blue-700/50 rounded-lg p-2 hover:bg-blue-800/50 transition">
                                <div class="flex-1 min-w-0">
                                    <p class="text-white text-xs font-bold truncate">${displayName}</p>
                                    <p class="text-white text-[10px] font-bold opacity-70">
                                        TSh ${formatNumber(item.price)} × ${item.quantity} =
                                        <span class="text-cyan-400">TSh ${formatNumber(item.price * item.quantity)}</span>
                                    </p>
                                </div>
                                <div class="flex items-center gap-1 flex-shrink-0">
                                    <button type="button" data-action="dec" data-id="${id}"
                                            class="cart-btn w-6 h-6 rounded bg-blue-800/60 border border-blue-700/50 hover:bg-blue-700/60 text-white font-bold text-sm transition">−</button>
                                    <span class="text-white font-bold text-xs w-5 text-center">${item.quantity}</span>
                                    <button type="button" data-action="inc" data-id="${id}"
                                            class="cart-btn w-6 h-6 rounded bg-blue-800/60 border border-blue-700/50 hover:bg-blue-700/60 text-white font-bold text-sm transition">+</button>
                                    <button type="button" data-action="rm" data-id="${id}"
                                            class="cart-btn w-6 h-6 rounded bg-red-500/20 border border-red-500/40 hover:bg-red-500/30 text-white text-sm ml-1 transition">×</button>
                                </div>
                            </div>`;
                    });
                    cartItems.innerHTML = html;

                    document.querySelectorAll('.cart-btn').forEach(function (btn) {
                        btn.addEventListener('click', function () {
                            const action = this.dataset.action;
                            const id = parseInt(this.dataset.id);
                            if (action === 'inc') updateQty(id, 1);
                            else if (action === 'dec') updateQty(id, -1);
                            else if (action === 'rm') removeFromCart(id);
                        });
                    });
                }

                let subtotal = 0;
                ids.forEach(id => subtotal += cart[id].price * cart[id].quantity);

                const dType = discountTypeEl.value;
                let dValue = parseFloat(discountValEl.value) || 0;
                let discountAmount = 0;

                if (dType === 'percent') {
                    if (dValue > maxDiscount) {
                        dValue = maxDiscount;
                        discountValEl.value = maxDiscount;
                    }
                    discountAmount = subtotal * (dValue / 100);
                } else if (dType === 'amount') {
                    discountAmount = Math.min(dValue, subtotal);
                }

                const total = subtotal - discountAmount;

                subtotalEl.textContent = 'TSh ' + formatNumber(subtotal);
                discountAmtEl.textContent = '-TSh ' + formatNumber(discountAmount);
                totalEl.textContent = 'TSh ' + formatNumber(total);
            }

            discountTypeEl.addEventListener('change', renderCart);
            discountValEl.addEventListener('input', renderCart);

            // === COMPLETE SALE ===
            completeBtn.addEventListener('click', function () {
                const ids = Object.keys(cart);
                if (!ids.length) {
                    flashToast('Cart is empty', 'warning');
                    return;
                }

                completeBtn.disabled = true;
                completeTxt.textContent = 'Processing...';

                const items = ids.map(id => ({ product_id: cart[id].id, quantity: cart[id].quantity }));

                const payload = {
                    store_id: storeId,
                    items: items,
                    discount_type: discountTypeEl.value,
                    discount_value: parseFloat(discountValEl.value) || 0,
                    payment_method: paymentEl.value,
                };

                fetch(storeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify(payload),
                })
                .then(async function (r) {
                    const text = await r.text();
                    let data;
                    try { data = JSON.parse(text); }
                    catch (e) { throw new Error('Server error (HTTP ' + r.status + ').'); }
                    if (!r.ok || !data.success) {
                        throw new Error(data.error || ('HTTP ' + r.status + ' — Sale failed'));
                    }
                    return data;
                })
                .then(function (data) {
                    cart = {};
                    renderCart();
                    searchInput.value = '';
                    loadProducts();
                    discountTypeEl.value = 'none';
                    discountValEl.value = 0;
                    flashToast('Sale completed successfully!', 'success');
                    window.open(data.receipt_url, '_blank');
                })
                .catch(function (err) {
                    flashToast('Error: ' + err.message, 'error');
                })
                .finally(function () {
                    completeBtn.disabled = false;
                    completeTxt.textContent = 'Complete Sale';
                });
            });

            clearBtn.addEventListener('click', function () {
                if (Object.keys(cart).length && confirm('Clear all items from cart?')) {
                    cart = {};
                    renderCart();
                }
            });

            // === HELPERS ===
            function formatNumber(n) {
                return Number(n || 0).toLocaleString('en-US', { maximumFractionDigits: 0 });
            }

            function escapeHtml(str) {
                const div = document.createElement('div');
                div.textContent = str;
                return div.innerHTML;
            }

            function escapeAttr(str) {
                return String(str).replace(/"/g, '&quot;').replace(/'/g, '&#39;');
            }

            // === TOAST ===
            function flashToast(message, type = 'success') {
                const colors = {
                    success: 'from-green-600 to-emerald-600 border-green-400',
                    warning: 'from-yellow-600 to-orange-600 border-yellow-400',
                    error:   'from-red-600 to-rose-600 border-red-400',
                };

                const toast = document.createElement('div');
                toast.className = `fixed top-4 left-1/2 -translate-x-1/2 z-[9999] bg-gradient-to-r ${colors[type]} border-2 text-white font-bold px-5 py-3 rounded-xl shadow-2xl text-sm transition-all duration-300 flex items-center gap-3`;
                toast.innerHTML = `
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>${escapeHtml(message)}</span>`;

                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translate(-50%, -20px)';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }

            renderCart();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    })();
    </script>
@endif