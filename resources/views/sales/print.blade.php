<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        use App\Models\Setting;
        $shopId = $shop->id ?? 0;
        $systemName = Setting::get($shopId, 'system_name', $shop->name ?? 'Duka System');
        $systemPhone = Setting::get($shopId, 'phone', $shop->phone ?? '');
        $systemAddress = Setting::get($shopId, 'address', $shop->location ?? '');
        $currency = Setting::get($shopId, 'currency', 'TSh');
    @endphp
    <title>Sales Report - {{ now()->format('Y-m-d') }}</title>
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
            @page { margin: 1.5cm; size: A4; }
        }

        .report-container {
            max-width: 1100px;
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

    {{-- Action bar --}}
    <div class="no-print fixed top-4 right-4 z-50 flex gap-2">
        <button onclick="window.print()"
                class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 hover:opacity-90 text-white font-semibold px-5 py-3 rounded-xl shadow-lg flex items-center gap-2 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Print / Save as PDF
        </button>
        <a href="{{ route('sales.index', request()->only(['search', 'store', 'from', 'to'])) }}"
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
                    <p class="text-slate-500 text-sm mt-1">Sales Report</p>
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

            {{-- Filter info --}}
            @if(request('from') || request('to') || $filterStore)
                <div class="mt-4 pt-4 border-t border-slate-100 text-xs text-slate-500">
                    <p class="font-semibold text-slate-700 mb-1">Filters:</p>
                    <ul class="list-disc pl-5">
                        @if(request('from'))
                            <li>From: {{ request('from') }}</li>
                        @endif
                        @if(request('to'))
                            <li>To: {{ request('to') }}</li>
                        @endif
                        @if($filterStore)
                            <li>Store: {{ $filterStore->name }}</li>
                        @endif
                        @if(request('search'))
                            <li>Search: "{{ request('search') }}"</li>
                        @endif
                    </ul>
                </div>
            @endif

            {{-- Summary --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6">
                <div class="bg-indigo-50 rounded-xl p-4">
                    <p class="text-slate-500 text-xs">Total Sales</p>
                    <p class="text-slate-900 font-bold text-2xl mt-1">{{ $totals['count'] }}</p>
                </div>
                <div class="bg-green-50 rounded-xl p-4">
                    <p class="text-slate-500 text-xs">Total Subtotal</p>
                    <p class="text-slate-900 font-bold text-lg mt-1">
                        {{ $currency }} {{ number_format($totals['total_subtotal'], 0) }}
                    </p>
                </div>
                <div class="bg-red-50 rounded-xl p-4">
                    <p class="text-slate-500 text-xs">Total Discount</p>
                    <p class="text-slate-900 font-bold text-lg mt-1">
                        {{ $currency }} {{ number_format($totals['total_discount'], 0) }}
                    </p>
                </div>
                <div class="bg-emerald-50 rounded-xl p-4">
                    <p class="text-slate-500 text-xs">Total Revenue</p>
                    <p class="text-slate-900 font-bold text-lg mt-1">
                        {{ $currency }} {{ number_format($totals['sum'], 0) }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Sales Table --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm">
            <h2 class="text-slate-900 font-bold text-lg mb-4">Sales Details</h2>

            @if($sales->count() > 0)
                <div class="overflow-x-auto">
                    <table class="bordered-table">
                        <thead>
                            <tr>
                                <th class="text-center w-10">#</th>
                                <th class="text-left">Invoice</th>
                                <th class="text-left">Date</th>
                                <th class="text-left">Store</th>
                                <th class="text-left">Cashier</th>
                                <th class="text-right w-24">Subtotal</th>
                                <th class="text-right w-24">Discount</th>
                                <th class="text-right w-28">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $i => $sale)
                                <tr>
                                    <td class="text-center text-slate-500">{{ $i + 1 }}</td>
                                    <td class="font-mono text-xs font-semibold text-slate-900">
                                        {{ $sale->invoice_no }}
                                    </td>
                                    <td class="text-slate-600 text-xs">
                                        {{ $sale->created_at ? $sale->created_at->format('d M Y, H:i') : '—' }}
                                    </td>
                                    <td class="text-slate-600 text-xs">
                                        {{ $sale->store->name ?? '—' }}
                                    </td>
                                    <td class="text-slate-600 text-xs">
                                        {{ $sale->cashier->full_name ?? '—' }}
                                    </td>
                                    <td class="text-right text-slate-700">
                                        {{ number_format((float) $sale->subtotal, 0) }}
                                    </td>
                                    <td class="text-right text-red-600">
                                        {{ $sale->discount_amount > 0 ? '-' . number_format((float) $sale->discount_amount, 0) : '—' }}
                                    </td>
                                    <td class="text-right font-bold text-slate-900">
                                        {{ $currency }} {{ number_format((float) $sale->total, 0) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-left uppercase text-xs tracking-wide">
                                    Grand Total
                                </td>
                                <td class="text-right">
                                    {{ number_format($totals['total_subtotal'], 0) }}
                                </td>
                                <td class="text-right text-red-700">
                                    -{{ number_format($totals['total_discount'], 0) }}
                                </td>
                                <td class="text-right text-slate-900">
                                    {{ $currency }} {{ number_format($totals['sum'], 0) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-slate-500">No sales found for this report.</p>
                </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="mt-6 text-center text-xs text-slate-400">
            <p>© {{ date('Y') }} {{ $systemName }} · Sales Report</p>
            <p class="mt-1">{{ $totals['count'] }} sales · Generated on {{ $generatedAt->format('d M Y, H:i') }}</p>
        </div>

    </div>

</body>
</html>