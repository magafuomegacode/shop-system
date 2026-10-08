<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        use App\Models\Setting;
        $shopId = $shop->id ?? ($sale->store->shop_id ?? 0);
        $systemName    = Setting::get($shopId, 'system_name', $shop->name ?? 'Duka System');
        $systemPhone   = Setting::get($shopId, 'phone', $shop->phone ?? '');
        $systemAddress = Setting::get($shopId, 'address', $shop->location ?? '');
        $currency      = Setting::get($shopId, 'currency', $currency ?? 'TSh');
        $receiptHeader = Setting::get($shopId, 'receipt_header', 'ASANTE KWA KUNUNUA!');
        $receiptFooter = Setting::get($shopId, 'receipt_footer', '');
        $taxNumber     = Setting::get($shopId, 'tax_number', '');
        $website       = Setting::get($shopId, 'website', '');
    @endphp
    <title>Receipt - {{ $sale->invoice_no }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto Mono', 'Courier New', monospace;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            background: #e5e7eb;
            padding: 20px;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }

        /* ===== RECEIPT PAPER ===== */
        .receipt {
            width: 80mm;
            background: #ffffff;
            padding: 6mm 4mm;
            color: #000000;
            font-size: 11px;
            line-height: 1.45;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }

        /* ===== TYPOGRAPHY ===== */
        .center { text-align: center; }
        .right  { text-align: right; }
        .left   { text-align: left; }
        .bold   { font-weight: 700; }
        .medium { font-weight: 500; }
        .big    { font-size: 16px; letter-spacing: 0.5px; }
        .small  { font-size: 10px; }
        .tiny   { font-size: 9px; }
        .muted  { color: #555; }
        .upper  { text-transform: uppercase; letter-spacing: 0.5px; }

        /* ===== DIVIDERS ===== */
        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
        .divider-thick {
            border-top: 2px solid #000;
            margin: 6px 0;
        }
        .divider-double {
            border-top: 3px double #000;
            margin: 8px 0;
        }

        /* ===== ROWS ===== */
        .row {
            display: flex;
            justify-content: space-between;
            gap: 6px;
        }
        .row > span:first-child { flex-shrink: 0; }
        .row > span:last-child  { text-align: right; }

        /* ===== BRAND HEADER ===== */
        .brand-header {
            text-align: center;
            padding-bottom: 4px;
        }
        .brand-name {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            line-height: 1.1;
        }
        .brand-sub {
            font-size: 9px;
            font-weight: 500;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #444;
            margin-top: 3px;
        }
        .brand-meta {
            font-size: 10px;
            color: #333;
            margin-top: 4px;
            line-height: 1.5;
        }

        /* ===== SECTION HEADER ===== */
        .section-head {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #000;
            margin-bottom: 4px;
        }

        /* ===== SALE INFO GRID ===== */
        .info-grid {
            font-size: 10px;
            line-height: 1.55;
        }
        .info-grid .row > span:first-child {
            color: #555;
            font-weight: 500;
        }
        .info-grid .row > span:last-child {
            font-weight: 600;
            color: #000;
        }

        /* ===== ITEMS TABLE ===== */
        table.items {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
        }
        table.items thead th {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 4px 0;
        }
        table.items thead th.qty   { text-align: center; width: 30px; }
        table.items thead th.price { text-align: right; width: 55px; }
        table.items thead th.total { text-align: right; width: 60px; }

        table.items tbody td {
            padding: 4px 0;
            vertical-align: top;
            border-bottom: 1px dotted #ccc;
        }
        table.items tbody tr:last-child td {
            border-bottom: none;
        }
        table.items td.qty   { text-align: center; font-weight: 600; }
        table.items td.price { text-align: right; font-weight: 500; }
        table.items td.total { text-align: right; font-weight: 700; }

        .item-name {
            font-weight: 600;
            word-break: break-word;
            line-height: 1.3;
        }

        /* ===== UNIT BADGE ===== */
        .unit-badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid #000;
            margin-left: 4px;
            vertical-align: middle;
        }

        .item-meta {
            font-size: 9px;
            color: #666;
            margin-top: 1px;
            line-height: 1.35;
        }

        /* ===== TOTALS ===== */
        .totals {
            font-size: 11px;
        }
        .totals .row {
            padding: 2px 0;
        }
        .totals .row > span:first-child {
            color: #333;
        }
        .grand-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 15px;
            font-weight: 700;
            padding: 6px 0;
            margin: 4px 0;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            letter-spacing: 0.5px;
        }
        .grand-total .amount {
            font-size: 16px;
        }

        /* ===== BADGE (payment method etc.) ===== */
        .badge {
            display: inline-block;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 2px 6px;
            border: 1px solid #000;
            border-radius: 3px;
        }

        /* ===== BARCODE ===== */
        .barcode {
            font-family: 'Libre Barcode 39', 'Roboto Mono', monospace;
            font-size: 30px;
            letter-spacing: 1px;
            text-align: center;
            line-height: 1;
            margin: 4px 0;
        }

        /* ===== FOOTER BLOCKS ===== */
        .footer-thanks {
            text-align: center;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            padding: 4px 0;
        }
        .footer-note {
            text-align: center;
            font-size: 9px;
            color: #555;
            line-height: 1.5;
            margin-top: 4px;
        }
        .footer-powered {
            text-align: center;
            font-size: 9px;
            color: #333;
            margin-top: 6px;
            letter-spacing: 0.5px;
        }
        .footer-powered .brand {
            font-weight: 700;
            color: #000;
        }

        /* ===== FLOATING ACTIONS ===== */
        .no-print {
            position: fixed;
            top: 16px;
            right: 16px;
            z-index: 50;
            display: flex;
            gap: 8px;
        }
        .no-print button,
        .no-print a {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 13px;
            font-weight: 600;
            padding: 10px 18px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: all 0.15s;
        }
        .btn-print { background: #2563eb; color: #ffffff; }
        .btn-print:hover { background: #1d4ed8; }
        .btn-back  { background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; }
        .btn-back:hover { background: #f1f5f9; }

        /* ===== PRINT MODE ===== */
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .no-print { display: none !important; }
            .receipt {
                box-shadow: none;
                width: 100%;
                padding: 0 2mm;
            }
            @page {
                margin: 3mm;
                size: 80mm auto;
            }
        }
    </style>
</head>
<body>

    {{-- ===== FLOATING ACTION BUTTONS ===== --}}
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">
            🖨 Print
        </button>
        <a href="{{ route('pos.index', ['store_id' => $sale->store_id]) }}" class="btn-back">
            ← Back to POS
        </a>
    </div>

    {{-- ===== RECEIPT ===== --}}
    <div class="receipt">

        {{-- ===== BRAND HEADER ===== --}}
        <div class="brand-header">
            <div class="brand-name">{{ $systemName }}</div>
            <div class="brand-sub">Official Receipt</div>

            @if($systemAddress || $systemPhone || $taxNumber)
                <div class="brand-meta">
                    @if($systemAddress)
                        <div>{{ $systemAddress }}</div>
                    @endif
                    @if($systemPhone)
                        <div>Tel: {{ $systemPhone }}</div>
                    @endif
                    @if($taxNumber)
                        <div>TIN: {{ $taxNumber }}</div>
                    @endif
                </div>
            @endif
        </div>

        <div class="divider-double"></div>

        {{-- ===== SALE INFO ===== --}}
        <div class="section-head">Transaction Details</div>

        <div class="info-grid">
            <div class="row">
                <span>Invoice No.</span>
                <span>{{ $sale->invoice_no }}</span>
            </div>
            <div class="row">
                <span>Date</span>
                <span>{{ $sale->created_at ? $sale->created_at->format('d/m/Y H:i') : '—' }}</span>
            </div>
            <div class="row">
                <span>Store</span>
                <span>{{ $sale->store->name ?? '—' }}</span>
            </div>
            <div class="row">
                <span>Cashier</span>
                <span>{{ $sale->cashier->full_name ?? '—' }}</span>
            </div>
            @if($sale->customer_name)
                <div class="row">
                    <span>Customer</span>
                    <span>{{ $sale->customer_name }}</span>
                </div>
            @endif
            @if($sale->payment_method)
                <div class="row">
                    <span>Payment</span>
                    <span class="upper">{{ $sale->payment_method }}</span>
                </div>
            @endif
        </div>

        <div class="divider"></div>

        {{-- ===== ITEMS ===== --}}
        <div class="section-head">Items Purchased</div>

        <table class="items">
            <thead>
                <tr>
                    <th class="left">Item</th>
                    <th class="qty">Qty</th>
                    <th class="price">Price</th>
                    <th class="total">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sale->items as $item)
                    @php
                        // Resolve unit label
                        $product = $item->product;
                        $unitRaw = $product->unit ?? null;

                        // Fall back to category config if product has no unit
                        if (!$unitRaw && $product && $product->category) {
                            $cfg = config('category_specs.' . $product->category->name, []);
                            $unitRaw = $cfg['unit'] ?? ($cfg['units'][0] ?? null);
                        }

                        $unitLabels = [
                            'pcs'   => 'PCS',
                            'piece' => 'PCS',
                            'set'   => 'SET',
                            'pair'  => 'PAIR',
                            'pack'  => 'PACK',
                            'box'   => 'BOX',
                            'bunch' => 'BUNCH',
                            'm2'    => 'M²',
                            'sqm'   => 'M²',
                            'm'     => 'M',
                            'metre' => 'M',
                            'kg'    => 'KG',
                            'g'     => 'G',
                            'litre' => 'L',
                            'l'     => 'L',
                            'ml'    => 'ML',
                        ];

                        $unitDisplay = '';
                        if ($unitRaw) {
                            $u = strtolower(trim($unitRaw));
                            $unitDisplay = $unitLabels[$u] ?? strtoupper($unitRaw);
                        }
                    @endphp
                    <tr>
                        <td>
                            <div class="item-name">
                                {{ $item->product->name ?? 'Unknown Item' }}
                                @if($unitDisplay)
                                    <span class="unit-badge">{{ $unitDisplay }}</span>
                                @endif
                            </div>

                            @if(!empty($product->specs) && is_array($product->specs))
                                @php
                                    $specBits = [];
                                    foreach ($product->specs as $k => $v) {
                                        if ($v === null || $v === '') continue;
                                        $specBits[] = ucwords(str_replace('_', ' ', $k)) . ': ' . $v;
                                    }
                                    $specLine = implode(' · ', array_slice($specBits, 0, 2));
                                @endphp
                                @if($specLine)
                                    <div class="item-meta">{{ $specLine }}</div>
                                @endif
                            @endif

                            @if($product && $product->size)
                                <div class="item-meta">Size: {{ rtrim(rtrim(number_format((float) $product->size, 2, '.', ''), '0'), '.') }}</div>
                            @endif
                        </td>
                        <td class="qty">{{ $item->quantity }}</td>
                        <td class="price">{{ number_format((float) $item->unit_price, 0) }}</td>
                        <td class="total">{{ number_format((float) $item->line_total, 0) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="center muted" style="padding: 10px 0;">No items in this sale</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="divider"></div>

        {{-- ===== TOTALS ===== --}}
        <div class="section-head">Payment Summary</div>

        <div class="totals">
            <div class="row">
                <span>Subtotal</span>
                <span>{{ $currency }} {{ number_format((float) $sale->subtotal, 0) }}</span>
            </div>

            @if($sale->discount_amount > 0)
                <div class="row">
                    <span>
                        Discount
                        @if($sale->discount_type === 'percent')
                            ({{ $sale->discount_value }}%)
                        @endif
                    </span>
                    <span>-{{ $currency }} {{ number_format((float) $sale->discount_amount, 0) }}</span>
                </div>
            @endif

            @if(!empty($sale->tax_amount) && $sale->tax_amount > 0)
                <div class="row">
                    <span>Tax</span>
                    <span>{{ $currency }} {{ number_format((float) $sale->tax_amount, 0) }}</span>
                </div>
            @endif

            <div class="grand-total">
                <span>TOTAL</span>
                <span class="amount">{{ $currency }} {{ number_format((float) $sale->total, 0) }}</span>
            </div>

            @if(!empty($sale->amount_paid))
                <div class="row">
                    <span>Amount Paid</span>
                    <span>{{ $currency }} {{ number_format((float) $sale->amount_paid, 0) }}</span>
                </div>
            @endif

            @if(!empty($sale->change_amount) && $sale->change_amount > 0)
                <div class="row">
                    <span>Change</span>
                    <span class="bold">{{ $currency }} {{ number_format((float) $sale->change_amount, 0) }}</span>
                </div>
            @endif

            <div class="row">
                <span>Payment Method</span>
                <span class="badge">{{ strtoupper($sale->payment_method ?? 'CASH') }}</span>
            </div>
        </div>

        <div class="divider-double"></div>

        {{-- ===== FOOTER — thanks message ===== --}}
        <div class="footer-thanks">{{ strtoupper($receiptHeader) }}</div>

        @if(!empty($receiptFooter))
            <div class="footer-note">{{ $receiptFooter }}</div>
        @endif

        <div class="footer-note">
            Bidhaa zilizolipiwa hazirudishwi.<br>
            Goods sold are not returnable.
        </div>

        {{-- ===== BARCODE ===== --}}
        <div style="margin-top: 8px;">
            <div class="barcode">*{{ $sale->invoice_no }}*</div>
            <div class="center tiny muted" style="letter-spacing: 1px; margin-top: 2px;">
                {{ $sale->invoice_no }}
            </div>
        </div>

        <div class="divider"></div>

        {{-- ===== SYSTEM FOOTER ===== --}}
        <div class="footer-powered">
            Powered by <span class="brand">{{ $systemName }}</span>
            @if($website)
                <br>{{ $website }}
            @endif
        </div>

    </div>

    <script>
        // Auto-print after page loads (400ms delay to allow fonts to render)
        window.addEventListener('load', function () {
            setTimeout(function () {
                window.print();
            }, 400);
        });
    </script>

</body>
</html>