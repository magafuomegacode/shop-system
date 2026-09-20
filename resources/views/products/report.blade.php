<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @php
        use App\Models\Setting;
        $shopId = $shop->id ?? 0;
        $systemName = Setting::get($shopId, 'system_name', $shop->name ?? 'Duka System');
        $systemPhone = Setting::get($shopId, 'phone', $shop->phone ?? '');
        $systemAddress = Setting::get($shopId, 'address', $shop->location ?? '');
        $currency = Setting::get($shopId, 'currency', 'TSh');
    @endphp
    <title>Product Report - {{ now()->format('Y-m-d') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }

        body {
            background: #f8fafc;
            color: #0f172a;
        }

        @media print {
            body { background: white; }
            .no-print { display: none !important; }
            .print-break { page-break-inside: avoid; }
            .page-break { page-break-before: always; }
            @page { margin: 1.5cm; size: A4; }
        }

        .report-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }

        @media print {
            .report-container { padding: 0; max-width: 100%; }
        }

        /* Bordered table */
        .bordered-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .bordered-table th,
        .bordered-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            vertical-align: middle;
        }

        .bordered-table thead th {
            background: #f1f5f9;
            color: #0f172a;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
        }

        .bordered-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .bordered-table tfoot td {
            background: #e2e8f0;
            font-weight: 700;
            border-top: 2px solid #64748b;
        }

        @media print {
            .bordered-table th,
            .bordered-table td {
                border: 1px solid #000 !important;
                padding: 4px 6px;
            }
            .bordered-table thead th {
                background: #e5e7eb !important;
                color: #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .bordered-table tfoot td {
                background: #e5e7eb !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    {{-- Floating action bar (no-print) --}}
    <div class="no-print fixed top-4 right-4 z-50 flex gap-2">
        <button onclick="window.print()"
                class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 hover:opacity-90 text-white font-semibold px-5 py-3 rounded-xl shadow-lg flex items-center gap-2 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Print / Save as PDF
        </button>
        <a href="{{ route('products.index') }}"
           class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold px-5 py-3 rounded-xl shadow-lg flex items-center gap-2 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>
    </div>

    <div class="report-container">

        {{-- Header --}}
        <div class="bg-white rounded-2xl p-8 mb-6 shadow-sm">
            <div class="flex items-start justify-between gap-4 pb-6 border-b border-slate-200">
                <div>
                    {{-- ✅ System name from settings --}}
                    <h1 class="text-3xl font-bold text-slate-900">
                        {{ strtoupper($systemName) }}
                    </h1>
                    <p class="text-slate-500 text-sm mt-1">Products Report</p>
                    @if($systemAddress)
                        <p class="text-slate-500 text-xs mt-0.5">{{ $systemAddress }}</p>
                    @endif
                    @if($systemPhone)
                        <p class="text-slate-500 text-xs">Tel: {{ $systemPhone }}</p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-slate-500 text-xs uppercase tracking-wide">Generated</p>
                    <p class="text-slate-900 font-semibold text-sm">
                        {{ $generatedAt->format('d M Y, H:i') }}
                    </p>
                    <p class="text-slate-500 text-xs mt-2">By</p>
                    <p class="text-slate-900 font-semibold text-sm">
                        {{ $generatedBy->full_name }}
                    </p>
                    <p class="text-slate-500 text-xs capitalize">{{ $generatedBy->role }}</p>
                </div>
            </div>

            {{-- Summary --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6">
                <div class="bg-indigo-50 rounded-xl p-4">
                    <p class="text-slate-500 text-xs">Total Products</p>
                    <p class="text-slate-900 font-bold text-2xl mt-1">{{ $totals['count'] }}</p>
                </div>
                <div class="bg-green-50 rounded-xl p-4">
                    <p class="text-slate-500 text-xs">Avg. Selling Price</p>
                    <p class="text-slate-900 font-bold text-lg mt-1">
                        {{ $currency }} {{ number_format($totals['avg_selling'], 0) }}
                    </p>
                </div>
                <div class="bg-purple-50 rounded-xl p-4">
                    <p class="text-slate-500 text-xs">Total Cost Value</p>
                    <p class="text-slate-900 font-bold text-lg mt-1">
                        {{ $currency }} {{ number_format($totals['total_cost'], 0) }}
                    </p>
                </div>
                <div class="bg-emerald-50 rounded-xl p-4">
                    <p class="text-slate-500 text-xs">Potential Profit</p>
                    <p class="text-slate-900 font-bold text-lg mt-1">
                        {{ $currency }} {{ number_format($totals['total_profit'], 0) }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Products grouped by store --}}
        @forelse($grouped as $storeName => $items)
            <div class="bg-white rounded-2xl p-6 mb-4 shadow-sm print-break">

                {{-- Store header --}}
                <div class="flex items-center justify-between pb-4 mb-4 border-b-2 border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-slate-900 font-bold text-lg">{{ $storeName }}</h2>
                            <p class="text-slate-500 text-xs">
                                {{ $items->count() }} {{ Str::plural('product', $items->count()) }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Bordered Products Table --}}
                <div class="overflow-x-auto">
                    <table class="bordered-table">
                        <thead>
                            <tr>
                                <th class="text-center w-10">#</th>
                                <th class="text-left">Product</th>
                                <th class="text-left w-24">SKU</th>
                                <th class="text-center w-16">Unit</th>
                                <th class="text-center w-20">Stock</th>
                                <th class="text-right w-24">Cost</th>
                                <th class="text-right w-28">Selling</th>
                                <th class="text-right w-28">Added By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $i => $product)
                                @php
                                    $stockKey = $product->store_id . '-' . $product->id;
                                    $stock = $stocks->get($stockKey);
                                    $stockQty = $stock ? (int) $stock->quantity : 0;
                                    $minQty = $stock ? (int) $stock->min_quantity : 5;
                                    $isLowStock = $stockQty <= $minQty;
                                @endphp
                                <tr>
                                    <td class="text-center text-slate-500">{{ $i + 1 }}</td>
                                    <td>
                                        <span class="text-slate-900 font-medium">{{ $product->name }}</span>
                                        @if(!$product->is_active)
                                            <span class="text-[9px] px-1.5 py-0.5 rounded bg-red-100 text-red-700 font-semibold uppercase ml-1">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-slate-600 font-mono text-xs">
                                        {{ $product->sku ?? '—' }}
                                    </td>
                                    <td class="text-center text-slate-600">{{ $product->unit ?? '—' }}</td>
                                    <td class="text-center font-semibold {{ $isLowStock ? 'text-red-600' : 'text-slate-900' }}">
                                        {{ $stockQty }}
                                    </td>
                                    <td class="text-right text-slate-600">
                                        {{ $product->cost_price ? number_format((float) $product->cost_price, 0) : '—' }}
                                    </td>
                                    <td class="text-right text-slate-900 font-semibold">
                                        {{ number_format((float) $product->selling_price, 0) }}
                                    </td>
                                    <td class="text-right text-slate-500 text-xs">
                                        {{ $product->creator->full_name ?? 'Unknown' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-left uppercase text-xs tracking-wide">Subtotal</td>
                                <td class="text-center">
                                    @php
                                        $storeStockTotal = 0;
                                        foreach ($items as $p) {
                                            $key = $p->store_id . '-' . $p->id;
                                            $s = $stocks->get($key);
                                            $storeStockTotal += $s ? (int) $s->quantity : 0;
                                        }
                                    @endphp
                                    {{ $storeStockTotal }}
                                </td>
                                <td class="text-right">
                                    {{ number_format($items->sum('cost_price'), 0) }}
                                </td>
                                <td class="text-right">
                                    {{ number_format($items->sum('selling_price'), 0) }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-slate-200">
                <p class="text-slate-500">No products found for this report.</p>
            </div>
        @endforelse

        {{-- Footer --}}
        <div class="mt-6 text-center text-xs text-slate-400">
            <p>© {{ date('Y') }} {{ $systemName }} · Products Report</p>
            <p class="mt-1">{{ $totals['count'] }} products · Generated on {{ $generatedAt->format('d M Y, H:i') }}</p>
        </div>

    </div>

</body>
</html>