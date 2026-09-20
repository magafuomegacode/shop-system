@extends('layouts.app')

@section('title', 'POS - Sell')

@section('content')

    {{-- Store Picker --}}
    @if(!$selectedStore)
        <div class="bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-6 mb-6 shadow-2xl border border-stone-600/50">
            <h2 class="text-white text-xl font-bold mb-1">Choose a Store</h2>
            <p class="text-white text-sm mb-4">Select the store you want to sell from</p>

            @if($stores->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($stores as $store)
                        <a href="{{ route('pos.index', ['store_id' => $store->id]) }}"
                           class="bg-stone-800/60 backdrop-blur rounded-2xl p-5 hover:bg-stone-700/60 transition flex items-center gap-3 border border-stone-600/50 hover:border-lime-400/60">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-600 to-lime-600 flex items-center justify-center flex-shrink-0 shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <p class="text-white font-semibold">{{ $store->name }}</p>
                                <p class="text-white text-xs">Tap to select</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="bg-yellow-500/20 border border-yellow-500/40 text-white px-4 py-3 rounded-xl text-sm">
                    No stores available. Contact your admin to create a store.
                </div>
            @endif
        </div>
    @else
        {{-- Store header --}}
        <div class="bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-4 mb-4 flex items-center justify-between gap-3 shadow-2xl border border-stone-600/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-600 to-lime-600 flex items-center justify-center flex-shrink-0 shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white text-xs">Selling from</p>
                    <p class="text-white font-semibold">{{ $selectedStore->name }}</p>
                </div>
            </div>
            <a href="{{ route('pos.index') }}" class="text-white hover:text-lime-200 text-xs font-semibold">Change</a>
        </div>

        {{-- POS Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            {{-- Products --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-4 shadow-2xl border border-stone-600/50">
                    <div class="relative">
                        <svg class="w-5 h-5 text-white absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" id="pos-search"
                               placeholder="Search products by name or SKU..."
                               autocomplete="off"
                               class="w-full bg-stone-800/60 backdrop-blur border border-stone-600/50 text-white placeholder-white/60 pl-10 pr-4 py-3 rounded-xl outline-none text-sm focus:border-lime-400 focus:ring-2 focus:ring-lime-400/30 transition">
                    </div>
                </div>

                <div class="bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-4 shadow-2xl border border-stone-600/50">
                    <h3 class="text-white font-semibold mb-3 text-sm">Tap a product to add</h3>
                    <div id="product-grid" class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        <p class="col-span-full text-center text-white text-sm py-8">
                            Type to search products...
                        </p>
                    </div>
                </div>
            </div>

            {{-- Cart --}}
            <div class="lg:col-span-1">
                <div class="bg-gradient-to-br from-stone-800 via-stone-700 to-stone-800 rounded-2xl p-4 lg:sticky lg:top-20 shadow-2xl border border-stone-600/50">
                    <h3 class="text-white font-bold mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17"/>
                        </svg>
                        Cart
                        <span id="cart-count" class="ml-auto text-xs bg-lime-500/20 text-white border border-lime-500/30 px-2 py-0.5 rounded-full font-semibold">0</span>
                    </h3>

                    <div id="cart-items" class="space-y-2 max-h-64 overflow-y-auto mb-3">
                        <p class="text-center text-white text-sm py-6">Cart is empty</p>
                    </div>

                    <div class="border-t border-stone-600/50 pt-3 mt-3">
                        <label class="text-white text-xs font-medium mb-1 block">Discount</label>
                        <div class="flex gap-2 mb-2">
                            <select id="discount-type" class="bg-stone-800/60 border border-stone-600/50 text-white px-2 py-2 rounded-lg text-xs flex-1 outline-none focus:border-lime-400">
                                <option value="none" class="bg-stone-800 text-white">None</option>
                                @if($discountAllowed)
                                    <option value="percent" class="bg-stone-800 text-white">Percent (%)</option>
                                    <option value="amount" class="bg-stone-800 text-white">Amount (TSh)</option>
                                @endif
                            </select>
                            <input type="number" id="discount-value" value="0" min="0" step="0.01"
                                   class="bg-stone-800/60 border border-stone-600/50 text-white px-2 py-2 rounded-lg text-xs w-24 outline-none focus:border-lime-400">
                        </div>
                        @if($discountAllowed)
                            <p class="text-white text-[10px]">Max {{ $maxDiscount }}%</p>
                        @endif
                    </div>

                    <div class="border-t border-stone-600/50 pt-3 mt-3 space-y-1 text-sm">
                        <div class="flex justify-between">
                            <span class="text-white">Subtotal</span>
                            <span class="text-white font-medium" id="subtotal">TSh 0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-white">Discount</span>
                            <span class="text-white font-medium" id="discount-amount">-TSh 0</span>
                        </div>
                        <div class="flex justify-between text-lg border-t border-stone-600/50 pt-2 mt-2">
                            <span class="text-white font-bold">Total</span>
                            <span class="text-white font-bold" id="total">TSh 0</span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="text-white text-xs font-medium mb-1 block">Payment</label>
                        <select id="payment-method" class="bg-stone-800/60 border border-stone-600/50 text-white w-full px-3 py-2 rounded-lg text-sm outline-none focus:border-lime-400">
                            <option value="cash" class="bg-stone-800 text-white">Cash</option>
                            <option value="mobile" class="bg-stone-800 text-white">Mobile Money</option>
                            <option value="card" class="bg-stone-800 text-white">Card</option>
                        </select>
                    </div>

                    <button type="button" id="complete-sale"
                            class="w-full mt-4 bg-gradient-to-br from-yellow-600 to-lime-600 hover:from-yellow-700 hover:to-lime-700 text-white font-bold py-3 rounded-xl transition shadow-lg disabled:opacity-50">
                        Complete Sale
                    </button>

                    <button type="button" id="clear-cart"
                            class="w-full mt-2 bg-stone-800/60 hover:bg-stone-700/60 border border-stone-600/50 text-white text-xs font-medium py-2 rounded-xl transition">
                        Clear Cart
                    </button>
                </div>
            </div>
        </div>
    @endif

@endsection

{{-- ===== SCRIPT — nje ya section ===== --}}
@if($selectedStore)
    <script>
    (function () {
        'use strict';

        function init() {
            console.log('🟢 POS Init started');

            // === ELEMENTS ===
            const searchInput = document.getElementById('pos-search');
            const productGrid = document.getElementById('product-grid');
            const cartItems = document.getElementById('cart-items');
            const cartCount = document.getElementById('cart-count');
            const subtotalEl = document.getElementById('subtotal');
            const discountAmountEl = document.getElementById('discount-amount');
            const totalEl = document.getElementById('total');
            const discountTypeEl = document.getElementById('discount-type');
            const discountValueEl = document.getElementById('discount-value');
            const paymentEl = document.getElementById('payment-method');
            const completeBtn = document.getElementById('complete-sale');
            const clearBtn = document.getElementById('clear-cart');

            const elements = {
                'pos-search': searchInput,
                'product-grid': productGrid,
                'cart-items': cartItems,
                'cart-count': cartCount,
                'subtotal': subtotalEl,
                'discount-amount': discountAmountEl,
                'total': totalEl,
                'discount-type': discountTypeEl,
                'discount-value': discountValueEl,
                'payment-method': paymentEl,
                'complete-sale': completeBtn,
                'clear-cart': clearBtn,
            };

            console.log('🔍 Element check:');
            let missing = [];
            for (const [name, el] of Object.entries(elements)) {
                if (el) {
                    console.log('  ✅ ' + name);
                } else {
                    console.log('  ❌ ' + name + ' — MISSING!');
                    missing.push(name);
                }
            }

            if (missing.length > 0) {
                console.error('❌ Missing elements:', missing);
                return;
            }

            console.log('✅ All elements found!');

            // === CONFIG ===
            const storeId = {{ $selectedStoreId }};
            const csrfToken = '{{ csrf_token() }}';
            const searchUrl = '{{ route('pos.search-products') }}';
            const storeUrl = '{{ route('pos.store') }}';
            const maxDiscount = {{ $maxDiscount ?? 20 }};

            let cart = {};
            let searchTimer;

            // === SEARCH ===
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(loadProducts, 250);
            });

            function loadProducts() {
                const q = searchInput.value.trim();
                if (!q) {
                    productGrid.innerHTML = '<p class="col-span-full text-center text-white text-sm py-8">Type to search products...</p>';
                    return;
                }

                productGrid.innerHTML = '<p class="col-span-full text-center text-white text-sm py-8">Searching...</p>';

                const url = searchUrl + '?store_id=' + storeId + '&search=' + encodeURIComponent(q);
                console.log('🔍 Fetching:', url);

                fetch(url, {
                    headers: { 'Accept': 'application/json' }
                })
                .then(r => r.json())
                .then(data => {
                    console.log('✅ Data:', data);
                    renderProducts(data);
                })
                .catch(err => {
                    console.error('❌ Error:', err);
                    productGrid.innerHTML = '<p class="col-span-full text-center text-red-300 text-sm py-8">Error: ' + err.message + '</p>';
                });
            }

            function renderProducts(products) {
                console.log('🎨 Rendering', products.length);

                if (!products || !products.length) {
                    productGrid.innerHTML = '<p class="col-span-full text-center text-white text-sm py-8">No products found.</p>';
                    return;
                }

                let html = '';
                products.forEach(function (p) {
                    const disabled = p.stock <= 0;
                    html += '<button type="button" data-id="' + p.id + '" data-name="' + escapeAttr(p.name) + '" data-price="' + p.selling_price + '" data-stock="' + p.stock + '" data-unit="' + escapeAttr(p.unit || '') + '" class="product-btn text-left bg-stone-800/60 border border-stone-600/50 hover:border-lime-400/60 hover:bg-stone-700/60 rounded-xl p-3 transition' + (disabled ? ' opacity-40 cursor-not-allowed' : '') + '" ' + (disabled ? 'disabled' : '') + '>';
                    html += '<p class="text-white font-semibold text-xs truncate">' + escapeHtml(p.name) + '</p>';
                    html += '<p class="text-white font-bold text-sm mt-1">TSh ' + formatNumber(p.selling_price) + '</p>';
                    html += '<p class="text-white text-[10px] mt-1">Stock: ' + p.stock + ' ' + escapeHtml(p.unit || '') + '</p>';
                    html += '</button>';
                });

                productGrid.innerHTML = html;

                document.querySelectorAll('.product-btn').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        addToCart(
                            parseInt(this.dataset.id),
                            this.dataset.name,
                            parseFloat(this.dataset.price),
                            parseInt(this.dataset.stock),
                            this.dataset.unit
                        );
                    });
                });
            }

            // === CART ===
            function addToCart(id, name, price, stock, unit) {
                if (cart[id]) {
                    if (cart[id].quantity >= stock) {
                        alert('Only ' + stock + ' available');
                        return;
                    }
                    cart[id].quantity += 1;
                } else {
                    cart[id] = { id: id, name: name, price: price, quantity: 1, stock: stock, unit: unit };
                }
                renderCart();
            }

            function updateQty(id, delta) {
                if (!cart[id]) return;
                const newQty = cart[id].quantity + delta;
                if (newQty <= 0) {
                    delete cart[id];
                } else if (newQty > cart[id].stock) {
                    alert('Only ' + cart[id].stock + ' available');
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
                    cartItems.innerHTML = '<p class="text-center text-white text-sm py-6">Cart is empty</p>';
                } else {
                    let html = '';
                    ids.forEach(function (id) {
                        const item = cart[id];
                        html += '<div class="flex items-center gap-2 bg-stone-800/60 border border-stone-600/50 rounded-lg p-2">';
                        html += '<div class="flex-1 min-w-0">';
                        html += '<p class="text-white text-xs font-semibold truncate">' + escapeHtml(item.name) + '</p>';
                        html += '<p class="text-white text-[10px]">TSh ' + formatNumber(item.price) + ' × ' + item.quantity + ' = TSh ' + formatNumber(item.price * item.quantity) + '</p>';
                        html += '</div>';
                        html += '<div class="flex items-center gap-1">';
                        html += '<button type="button" data-action="dec" data-id="' + id + '" class="cart-btn w-6 h-6 rounded bg-stone-700/60 border border-stone-600/50 hover:bg-stone-600/60 text-white font-bold text-xs">−</button>';
                        html += '<span class="text-white font-semibold text-xs w-6 text-center">' + item.quantity + '</span>';
                        html += '<button type="button" data-action="inc" data-id="' + id + '" class="cart-btn w-6 h-6 rounded bg-stone-700/60 border border-stone-600/50 hover:bg-stone-600/60 text-white font-bold text-xs">+</button>';
                        html += '<button type="button" data-action="rm" data-id="' + id + '" class="cart-btn w-6 h-6 rounded bg-red-500/20 border border-red-500/40 hover:bg-red-500/30 text-white text-xs ml-1">×</button>';
                        html += '</div></div>';
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
                ids.forEach(function (id) {
                    subtotal += cart[id].price * cart[id].quantity;
                });

                const dType = discountTypeEl.value;
                let dValue = parseFloat(discountValueEl.value) || 0;
                let discountAmount = 0;

                if (dType === 'percent') {
                    if (dValue > maxDiscount) {
                        dValue = maxDiscount;
                        discountValueEl.value = maxDiscount;
                    }
                    discountAmount = subtotal * (dValue / 100);
                } else if (dType === 'amount') {
                    discountAmount = Math.min(dValue, subtotal);
                }

                const total = subtotal - discountAmount;

                subtotalEl.textContent = 'TSh ' + formatNumber(subtotal);
                discountAmountEl.textContent = '-TSh ' + formatNumber(discountAmount);
                totalEl.textContent = 'TSh ' + formatNumber(total);
            }

            discountTypeEl.addEventListener('change', renderCart);
            discountValueEl.addEventListener('input', renderCart);

            // === COMPLETE SALE ===
            completeBtn.addEventListener('click', function () {
                const ids = Object.keys(cart);
                if (!ids.length) {
                    alert('Cart is empty');
                    return;
                }

                completeBtn.disabled = true;
                completeBtn.textContent = 'Processing...';

                const items = ids.map(function (id) {
                    return { product_id: cart[id].id, quantity: cart[id].quantity };
                });

                const payload = {
                    store_id: storeId,
                    items: items,
                    discount_type: discountTypeEl.value,
                    discount_value: parseFloat(discountValueEl.value) || 0,
                    payment_method: paymentEl.value,
                };

                console.log('📤 Sending payload:', payload);

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

                    try {
                        data = JSON.parse(text);
                    } catch (e) {
                        console.error('❌ Non-JSON response:', text);
                        throw new Error('Server error (HTTP ' + r.status + '). Check browser console.');
                    }

                    if (!r.ok || !data.success) {
                        console.error('❌ Sale failed:', r.status, data);
                        throw new Error(data.error || ('HTTP ' + r.status + ' — Sale failed'));
                    }
                    return data;
                })
                .then(function (data) {
                    console.log('✅ Sale success:', data);
                    cart = {};
                    renderCart();
                    searchInput.value = '';
                    productGrid.innerHTML = '<p class="col-span-full text-center text-white text-sm py-8">Type to search products...</p>';
                    discountTypeEl.value = 'none';
                    discountValueEl.value = 0;
                    window.open(data.receipt_url, '_blank');
                })
                .catch(function (err) {
                    console.error('❌ Complete sale error:', err);
                    alert('Error: ' + err.message);
                })
                .finally(function () {
                    completeBtn.disabled = false;
                    completeBtn.textContent = 'Complete Sale';
                });
            });

            clearBtn.addEventListener('click', function () {
                if (Object.keys(cart).length && confirm('Clear all items?')) {
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

            renderCart();
            console.log('✅ POS Ready');
        }

        // === INIT ===
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    })();
    </script>
@endif