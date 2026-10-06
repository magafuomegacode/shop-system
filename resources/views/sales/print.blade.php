<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        use App\Models\Setting;
        $shopId = $shop->id ?? 0;
        $systemName    = Setting::get($shopId, 'system_name', $shop->name ?? 'Duka System');
        $systemPhone   = Setting::get($shopId, 'phone', $shop->phone ?? '');
        $systemAddress = Setting::get($shopId, 'address', $shop->location ?? '');
        $currency      = Setting::get($shopId, 'currency', 'TSh');
    @endphp
    <title>Sales Report - {{ now()->format('Y-m-d') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }

        body {
            background: #0f172a;
            color: #ffffff;
            -webkit-font-smoothing: antialiased;
        }

        /* ===== Print Rules ===== */
        @media print {
            body { background: white !important; color: #000 !important; font-size: 11px; }
            .no-print { display: none !important; }
            .print-break { page-break-inside: avoid; }
            .avoid-break { page-break-inside: avoid; }
            @page { margin: 1.4cm; size: A4; }
            .report-container { padding: 0 !important; max-width: 100% !important; }
            .shadow-sm, .shadow-lg, .shadow-2xl { box-shadow: none !important; }
            .rounded-2xl, .rounded-xl { border-radius: 4px !important; }

            /* Force dark cards to white on print */
            .dark-card {
                background: #ffffff !important;
                border: 1px solid #cbd5e1 !important;
            }
            .dark-card * {
                color: #000 !important;
            }
        }

        .report-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* ===== Document Header ===== */
        .doc-header {
            border-bottom: 3px double #38bdf8;
            padding-bottom: 1rem;
            margin-bottom: 1.25rem;
        }

        .doc-title {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #ffffff;
            line-height: 1.2;
        }

        .doc-subtitle {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #38bdf8;
            margin-top: 2px;
        }

        .meta-label {
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .meta-value {
            font-size: 12px;
            font-weight: 700;
            color: #ffffff;
        }

        /* ===== Dark cards for screen ===== */
        .dark-card {
            background: linear-gradient(135deg, #020617 0%, #0c1a33 50%, #020617 100%);
            border: 1px solid rgba(56, 189, 248, 0.25);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
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
            border-radius: 10px;
            padding: 12px 14px;
            border: 1px solid rgba(56, 189, 248, 0.3);
            background: rgba(15, 23, 42, 0.6);
        }

        .summary-card.indigo  { border-color: rgba(129, 140, 248, 0.5); }
        .summary-card.green   { border-color: rgba(34, 197, 94, 0.5); }
        .summary-card.red     { border-color: rgba(239, 68, 68, 0.5); }
        .summary-card.emerald { border-color: rgba(16, 185, 129, 0.5); }

        .summary-label {
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #ffffff;
            opacity: 0.75;
        }

        .summary-value {
            font-size: 18px;
            font-weight: 800;
            color: #ffffff;
            margin-top: 4px;
            line-height: 1.1;
        }

        .summary-value.small { font-size: 15px; }

        /* ===== Bordered Table ===== */
        .bordered-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .bordered-table th,
        .bordered-table td {
            border: 1px solid rgba(56, 189, 248, 0.2);
            padding: 7px 9px;
            vertical-align: middle;
        }

        .bordered-table thead th {
            background: rgba(15, 23, 42, 0.85);
            color: #ffffff;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.7px;
            border-bottom: 2px solid rgba(56, 189, 248, 0.6);
        }

        .bordered-table tbody tr:nth-child(even) {
            background: rgba(30, 41, 59, 0.4);
        }

        .bordered-table tbody tr:hover {
            background: rgba(30, 41, 59, 0.7);
        }

        .bordered-table tbody td {
            color: #ffffff;
            font-weight: 700;
        }

        .bordered-table tfoot td {
            background: rgba(15, 23, 42, 0.9);
            color: #ffffff;
            font-weight: 800;
            border-top: 2px solid rgba(56, 189, 248, 0.6);
            font-size: 11px;
        }

        .mono { font-family: 'SF Mono', 'Consolas', 'Monaco', monospace; }

        @media print {
            .bordered-table { font-size: 10px; }
            .bordered-table th,
            .bordered-table td {
                border: 1px solid #000 !important;
                padding: 4px 6px;
                color: #000 !important;
                background: #ffffff !important;
            }
            .bordered-table thead th {
                background: #e5e7eb !important;
                color: #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .bordered-table tbody tr:nth-child(even) {
                background: #f8fafc !important;
            }
            .bordered-table tfoot td {
                background: #e5e7eb !important;
                color: #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .summary-card {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                border: 1px solid #000 !important;
                background: #f1f5f9 !important;
            }
            .summary-label,
            .summary-value { color: #000 !important; }
        }

        /* ===== Filters Box ===== */
        .filters-box {
            border: 1px solid rgba(56, 189, 248, 0.3);
            border-left: 3px solid #38bdf8;
            background: rgba(15, 23, 42, 0.6);
            padding: 10px 14px;
            border-radius: 6px;
            font-size: 11px;
            margin-top: 1rem;
        }

        .filters-box .filter-label {
            font-weight: 800;
            color: #ffffff;
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
            color: #ffffff;
            font-weight: 700;
            padding: 1px 0;
        }

        .filters-box li::before {
            content: '▸ ';
            color: #38bdf8;
            font-weight: 800;
        }

        @media print {
            .filters-box {
                background: #f8fafc !important;
                border: 1px solid #000 !important;
                border-left: 3px solid #000 !important;
            }
            .filters-box .filter-label,
            .filters-box li { color: #000 !important; }
            .filters-box li::before { color: #000 !important; }
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
            border-top: 1px solid #38bdf8;
            padding-top: 6px;
            font-size: 10px;
            color: #ffffff;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        @media print {
            .signature-line {
                border-top: 1px solid #000 !important;
                color: #000 !important;
            }
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

    {{-- ===== Action bar ===== --}}
    <div class="no-print fixed top-4 right-4 z-50 flex gap-2">
        <button onclick="window.print()"
                class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 hover:opacity-90 text-white font-bold px-5 py-3 rounded-xl shadow-lg flex items-center gap-2 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Print / Save as PDF
        </button>
        <a href="{{ route('sales.index', request()->only(['search', 'store', 'from', 'to'])) }}"
           class="bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold px-5 py-3 rounded-xl shadow-lg flex items-center gap-2 transition border border-blue-400/40">
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
        <div class="dark-card p-8 mb-6">
            <div class="doc-header flex items-start justify-between gap-4">
                <div>
                    <h1 class="doc-title">{{ strtoupper($systemName) }}</h1>
                    <p class="doc-subtitle">Sales Report</p>
                    @if($systemAddress)
                        <p class="text-white font-bold text-xs mt-2 opacity-80">{{ $systemAddress }}</p>
                    @endif
                    @if($systemPhone)
                        <p class="text-white font-bold text-xs opacity-80">Tel: {{ $systemPhone }}</p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="meta-label">Generated</p>
                    <p class="meta-value">{{ $generatedAt->format('d M Y, H:i') }}</p>
                    <p class="meta-label mt-3">Generated By</p>
                    <p class="meta-value">{{ $generatedBy->full_name }}</p>
                    <p class="text-white font-bold text-xs capitalize opacity-80">{{ $generatedBy->role }}</p>
                    <p class="meta-label mt-3">Report Ref</p>
                    <p class="meta-value mono text-xs">
                        SR-{{ $generatedAt->format('Ymd-His') }}
                    </p>
                </div>
            </div>

            {{-- Filter info --}}
            @if(request('from') || request('to') || $filterStore || request('search'))
                <div class="filters-box">
                    <div class="filter-label">Applied Filters</div>
                    <ul>
                        @if(request('from'))
                            <li>Date from: <strong>{{ request('from') }}</strong></li>
                        @endif
                        @if(request('to'))
                            <li>Date to: <strong>{{ request('to') }}</strong></li>
                        @endif
                        @if($filterStore)
                            <li>Store: <strong>{{ $filterStore->name }}</strong></li>
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
                    <p class="summary-label">Total Sales</p>
                    <p class="summary-value">{{ $totals['count'] }}</p>
                </div>
                <div class="summary-card green">
                    <p class="summary-label">Total Subtotal</p>
                    <p class="summary-value small">{{ $currency }} {{ number_format($totals['total_subtotal'], 0) }}</p>
                </div>
                <div class="summary-card red">
                    <p class="summary-label">Total Discount</p>
                    <p class="summary-value small">{{ $currency }} {{ number_format($totals['total_discount'], 0) }}</p>
                </div>
                <div class="summary-card emerald">
                    <p class="summary-label">Total Revenue</p>
                    <p class="summary-value small">{{ $currency }} {{ number_format($totals['sum'], 0) }}</p>
                </div>
            </div>
        </div>

        {{-- ===== Sales Table ===== --}}
        <div class="dark-card p-6 avoid-break">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-white font-bold text-lg">Sales Details</h2>
                <p class="text-white font-bold text-xs opacity-80">
                    {{ $sales->count() }} {{ Str::plural('record', $sales->count()) }}
                </p>
            </div>

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
                                    <td class="text-center">{{ $i + 1 }}</td>
                                    <td class="mono text-xs font-bold">
                                        {{ $sale->invoice_no }}
                                    </td>
                                    <td class="text-xs">
                                        {{ $sale->created_at ? $sale->created_at->format('d M Y, H:i') : '—' }}
                                    </td>
                                    <td class="text-xs">
                                        {{ $sale->store->name ?? '—' }}
                                    </td>
                                    <td class="text-xs">
                                        {{ $sale->cashier->full_name ?? '—' }}
                                    </td>
                                    <td class="text-right">
                                        {{ number_format((float) $sale->subtotal, 0) }}
                                    </td>
                                    <td class="text-right text-red-300">
                                        {{ $sale->discount_amount > 0 ? '-' . number_format((float) $sale->discount_amount, 0) : '—' }}
                                    </td>
                                    <td class="text-right font-bold">
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
                                <td class="text-right text-red-300">
                                    -{{ number_format($totals['total_discount'], 0) }}
                                </td>
                                <td class="text-right">
                                    {{ $currency }} {{ number_format($totals['sum'], 0) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-white font-bold text-sm opacity-70">No sales found for this report.</p>
                </div>
            @endif
        </div>

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
        <div class="mt-8 pt-4 border-t border-cyan-500/30 text-center">
            <p class="text-white font-bold text-xs opacity-80">
                © {{ date('Y') }} {{ $systemName }} · Sales Report
            </p>
            <p class="text-white font-bold text-xs mt-1 opacity-70">
                {{ $totals['count'] }} sales · Generated on {{ $generatedAt->format('d M Y, H:i') }}
                · Ref: SR-{{ $generatedAt->format('Ymd-His') }}
            </p>
        </div>

    </div>

</body>
</html>