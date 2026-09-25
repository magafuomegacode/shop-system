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
            -webkit-font-smoothing: antialiased;
        }

        /* ===== Print Rules ===== */
        @media print {
            body { background: white; font-size: 10.5px; }
            .no-print { display: none !important; }
            .print-break { page-break-inside: avoid; }
            .page-break { page-break-before: always; }
            .avoid-break { page-break-inside: avoid; }
            @page { margin: 1.4cm; size: A4; }
            .report-container { padding: 0 !important; max-width: 100% !important; }
            .shadow-sm, .shadow-lg, .shadow-2xl { box-shadow: none !important; }
            .rounded-2xl, .rounded-xl { border-radius: 4px !important; }
        }

        .report-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* ===== Document Header ===== */
        .doc-header {
            border-bottom: 3px double #0f172a;
            padding-bottom: 1rem;
            margin-bottom: 1.25rem;
        }

        .doc-title {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #0f172a;
            line-height: 1.2;
        }

        .doc-subtitle {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #64748b;
            margin-top: 2px;
        }

        .meta-label {
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .meta-value {
            font-size: 12px;
            font-weight: 600;
            color: #0f172a;
        }

        /* ===== Summary Cards ===== */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.75rem;
            margin-top: 1.25rem;
        }

        @media (max-width: 640px) {
            .summary-grid { grid-template-columns: repeat(2, 1fr); }
        }

        .summary-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 14px;
            background: #fff;
        }

        .summary-card.indigo { background: #eef2ff; border-color: #c7d2fe; }
        .summary-card.green  { background: #f0fdf4; border-color: #bbf7d0; }
        .summary-card.purple { background: #faf5ff; border-color: #e9d5ff; }
        .summary-card.emerald{ background: #ecfdf5; border-color: #a7f3d0; }

        .summary-label {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #64748b;
        }

        .summary-value {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 4px;
            line-height: 1.1;
        }

        .summary-value.small { font-size: 15px; }

        /* ===== Store Section ===== */
        .store-section {
            margin-bottom: 1rem;
        }

        .store-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 12px;
            margin-bottom: 12px;
            border-bottom: 2px solid #e2e8f0;
        }

        .store-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
        }

        .store-count {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #64748b;
            margin-top: 2px;
        }

        /* ===== Bordered Table ===== */
        .bordered-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .bordered-table th,
        .bordered-table td {
            border: 1px solid #cbd5e1;
            padding: 7px 9px;
            vertical-align: middle;
        }

        .bordered-table thead th {
            background: #f1f5f9;
            color: #0f172a;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.7px;
            border-bottom: 2px solid #94a3b8;
        }

        .bordered-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .bordered-table tbody tr:hover {
            background: #f1f5f9;
        }

        .bordered-table tfoot td {
            background: #e2e8f0;
            font-weight: 700;
            border-top: 2px solid #475569;
            font-size: 11px;
        }

        .mono { font-family: 'SF Mono', 'Consolas', 'Monaco', monospace; }

        @media print {
            .bordered-table { font-size: 10px; }
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
            .summary-card {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                border: 1px solid #000 !important;
            }
            .summary-card.indigo,
            .summary-card.green,
            .summary-card.purple,
            .summary-card.emerald {
                background: #f1f5f9 !important;
            }
        }

        /* ===== Filters Box ===== */
        .filters-box {
            border: 1px solid #e2e8f0;
            border-left: 3px solid #6366f1;
            background: #f8fafc;
            padding: 10px 14px;
            border-radius: 6px;
            font-size: 11px;
            margin-top: 1rem;
        }

        .filters-box .filter-label {
            font-weight: 700;
            color: #0f172a;
            font-size: 10px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .filters-box ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .filters-box li {
            color: #475569;
            padding: 1px 0;
        }

        .filters-box li::before {
            content: '▸ ';
            color: #6366f1;
            font-weight: 700;
        }

        /* ===== Signature ===== */
        .signature-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            margin-top: 3rem;
            padding-top: 1.5rem;
        }

        .signature-line {
            border-top: 1px solid #94a3b8;
            padding-top: 6px;
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ===== Watermark for print ===== */
        @media print {
            .print-watermark {
                position: fixed;
                top: 45%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-30deg);
                font-size: 90px;
                font-weight: 800;
                color: rgba(15, 23, 42, 0.04);
                z-index: -1;
                pointer-events: none;
                letter-spacing: 8px;
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

    {{-- Print watermark --}}
    <div class="print-watermark hidden print:block">{{ strtoupper($systemName) }}</div>

    <div class="report-container">

        {{-- ===== Document Header ===== --}}
        <div class="bg-white rounded-2xl p-8 mb-6 shadow-sm">
            <div class="doc-header flex items-start justify-between gap-4">
                <div>
                    <h1 class="doc-title">{{ strtoupper($systemName) }}</h1>
                    <p class="doc-subtitle">Products Report</p>
                    @if($systemAddress)
                        <p class="text-slate-500 text-xs mt-2">{{ $systemAddress }}</p>
                    @endif
                    @if($systemPhone)
                        <p class="text-slate-500 text-xs">Tel: {{ $systemPhone }}</p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="meta-label">Generated</p>
                    <p class="meta-value">{{ $generatedAt->format('d M Y, H:i') }}</p>
                    <p class="meta-label mt-3">Generated By</p>
                    <p class="meta-value">{{ $generatedBy->full_name }}</p>
                    <p class="text-slate-500 text-xs capitalize">{{ $generatedBy->role }}</p>
                    <p class="meta-label mt-3">Report Ref</p>
                    <p class="meta-value mono text-xs">PR-{{ $generatedAt->format('Ymd-His') }}</p>
                </div>
            </div>

            {{-- Filter info (optional, if passed) --}}
            @if(request('store') || request('search'))
                <div class="filters-box">
                    <div class="filter-label">Applied Filters</div>
                    <ul>
                        @if(request('store'))
                            <li>Store filter applied</li>
                        @endif
                        @if(request('search'))
                            <li>Search: <strong>"{{ request('search') }}"</strong></li>
                        @endif
                    </ul>
                </div>
            @endif

            {{-- Summary --}}
            <div class="summary-grid">
                <div class="summary-card indigo">
                    <p class="summary-label">Total Products</p>
                    <p class="summary-value">{{ $totals['count'] }}</p>
                </div>
                <div class="summary-card green">
                    <p class="summary-label">Avg. Selling Price</p>
                    <p class="summary-value small">{{ $currency }} {{ number_format($totals['avg_selling'], 0) }}</p>
                </div>
                <div class="summary-card purple">
                    <p class="summary-label">Total Cost Value</p>
                    <p class="summary-value small">{{ $currency }} {{ number_format($totals['total_cost'], 0) }}</p>
                </div>
                <div class="summary-card emerald">
                    <p class="summary-label">Potential Profit</p>
                    <p class="summary-value small">{{ $currency }} {{ number_format($totals['total_profit'], 0) }}</p>
                </div>
            </div>
        </div>

        {{-- ===== Products grouped by store ===== --}}
        @forelse($grouped as $storeName => $items)
            <div class="bg-white rounded-2xl p-6 mb-4 shadow-sm store-section print-break avoid-break">

                {{-- Store header --}}
                <div class="store-header">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="store-title">{{ $storeName }}</h2>
                            <p class="store-count">{{ $items->count() }} {{ Str::plural('product', $items->count()) }}</p>
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
                                <th class="text-left w-20">SKU</th>
                                <th class="text-center w-14">Unit</th>
                                <th class="text-center w-14">Size</th>
                                <th class="text-center w-16">Stock</th>
                                <th class="text-right w-20">Cost</th>
                                <th class="text-right w-24">Selling</th>
                                <th class="text-right w-24">Added By</th>
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

                                    // Size display — remove trailing zeros
                                    $sizeDisplay = '—';
                                    if ($product->size !== null && $product->size !== '') {
                                        $sizeDisplay = rtrim(rtrim(number_format((float) $product->size, 2, '.', ''), '0'), '.');
                                    }
                                @endphp
                                <tr>
                                    <td class="text-center text-slate-500">{{ $i + 1 }}</td>
                                    <td>
                                        <span class="text-slate-900 font-medium">{{ $product->name }}</span>
                                        @if(!$product->is_active)
                                            <span class="text-[9px] px-1.5 py-0.5 rounded bg-red-100 text-red-700 font-semibold uppercase ml-1">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-slate-600 mono text-xs">
                                        {{ $product->sku ?? '—' }}
                                    </td>
                                    <td class="text-center text-slate-600">{{ $product->unit ?? '—' }}</td>
                                    <td class="text-center font-semibold text-slate-900">{{ $sizeDisplay }}</td>
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
                                <td colspan="5" class="text-left uppercase text-xs tracking-wide">Subtotal</td>
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

        {{-- ===== Signature Block ===== --}}
        <div class="signature-row avoid-break">
            <div>
                <div class="signature-line">Prepared By</div>
            </div>
            <div>
                <div class="signature-line">Approved By</div>
            </div>
        </div>

        {{-- ===== Footer ===== --}}
        <div class="mt-8 pt-4 border-t border-slate-200 text-center text-xs text-slate-400">
            <p>© {{ date('Y') }} {{ $systemName }} · Products Report</p>
            <p class="mt-1">
                {{ $totals['count'] }} products · Generated on {{ $generatedAt->format('d M Y, H:i') }}
                · Ref: PR-{{ $generatedAt->format('Ymd-His') }}
            </p>
        </div>

    </div>

</body>
</html>