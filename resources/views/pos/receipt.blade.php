<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        use App\Models\Setting;
        $shopId = $shop->id ?? ($sale->store->shop_id ?? 0);
        $systemName = Setting::get($shopId, 'system_name', $shop->name ?? 'Duka System');
        $systemPhone = Setting::get($shopId, 'phone', $shop->phone ?? '');
        $systemAddress = Setting::get($shopId, 'address', $shop->location ?? '');
        $currency = Setting::get($shopId, 'currency', $currency ?? 'TSh');
        $receiptHeader = Setting::get($shopId, 'receipt_header', 'ASANTE KWA KUNUNUA!');
        $receiptFooter = Setting::get($shopId, 'receipt_footer', '');
    @endphp
    <title>Receipt - {{ $sale->invoice_no }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto Mono', 'Courier New', monospace;
        }

        body {
            background: #e5e7eb;
            padding: 20px;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }

        .receipt {
            width: 80mm;
            background: #ffffff;
            padding: 6mm 4mm;
            color: #000000;
            font-size: 11px;
            line-height: 1.4;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }

        .center { text-align: center; }
        .right  { text-align: right; }
        .left   { text-align: left; }
        .bold   { font-weight: 700; }
        .big    { font-size: 16px; }
        .small  { font-size: 10px; }

        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        .divider-solid {
            border-top: 1px solid #000;
            margin: 6px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
            gap: 6px;
        }

        .row > span:first-child {
            flex-shrink: 0;
        }

        .row > span:last-child {
            text-align: right;
        }

        .item-name {
            font-weight: 500;
            word-break: break-word;
        }

        .item-meta {
            font-size: 10px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        table th {
            text-align: left;
            font-weight: 700;
            border-bottom: 1px solid #000;
            padding: 3px 0;
        }

        table th.qty   { text-align: center; width: 30px; }
        table th.total { text-align: right; width: 55px; }

        table td {
            padding: 3px 0;
            vertical-align: top;
        }

        table td.qty   { text-align: center; }
        table td.total { text-align: right; font-weight: 500; }

        .total-row {
            font-size: 14px;
            font-weight: 700;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 6px 0;
            margin: 4px 0;
        }

        .barcode {
            font-family: 'Libre Barcode 39', monospace;
            font-size: 28px;
            letter-spacing: 1px;
            text-align: center;
        }

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

        .btn-print {
            background: #2563eb;
            color: #ffffff;
        }
        .btn-print:hover { background: #1d4ed8; }

        .btn-back {
            background: #ffffff;
            color: #1e293b;
            border: 1px solid #cbd5e1;
        }
        .btn-back:hover { background: #f1f5f9; }

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

    {{-- Action buttons (hidden on print) --}}
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">
            🖨 Print
        </button>
        <a href="{{ route('pos.index', ['store_id' => $sale->store_id]) }}" class="btn-back">
            ← Back to POS
        </a>
    </div>

    {{-- Receipt --}}
    <div class="receipt">

        {{-- ===== HEADER — from settings ===== --}}
        <div class="center">
            <div class="bold big">{{ strtoupper($systemName) }}</div>
            @if($systemAddress)
                <div class="small">{{ $systemAddress }}</div>
            @endif
            @if($systemPhone)
                <div class="small">Tel: {{ $systemPhone }}</div>
            @endif
        </div>

        <div class="divider"></div>

        {{-- ===== SALE INFO ===== --}}
        <div class="row">
            <span>Invoice</span>
            <span class="bold">{{ $sale->invoice_no }}</span>
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

        <div class="divider"></div>

        {{-- ===== ITEMS TABLE ===== --}}
        <table>
            <thead>
                <tr>
                    <th>ITEM</th>
                    <th class="qty">QTY</th>
                    <th class="total">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sale->items as $item)
                    <tr>
                        <td>
                            <div class="item-name">{{ $item->product->name ?? 'Unknown' }}</div>
                            <div class="item-meta">@ {{ $currency }} {{ number_format((float) $item->unit_price, 0) }}</div>
                        </td>
                        <td class="qty">{{ $item->quantity }}</td>
                        <td class="total">{{ number_format((float) $item->line_total, 0) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="center" style="padding: 8px 0;">No items</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="divider"></div>

        {{-- ===== TOTALS ===== --}}
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

        <div class="total-row row">
            <span>TOTAL</span>
            <span>{{ $currency }} {{ number_format((float) $sale->total, 0) }}</span>
        </div>

        <div class="row">
            <span>Payment</span>
            <span class="bold">{{ strtoupper($sale->payment_method) }}</span>
        </div>

        <div class="divider"></div>

        {{-- ===== FOOTER — from settings ===== --}}
        <div class="center" style="margin-top: 8px;">
            <div class="bold">{{ strtoupper($receiptHeader) }}</div>
            @if(!empty($receiptFooter))
                <div class="small" style="margin-top: 4px;">{{ $receiptFooter }}</div>
            @endif
            <div class="small" style="margin-top: 6px;">
                Bidhaa zilizolipiwa hazirudishwi
            </div>
        </div>

        <div class="divider"></div>

        {{-- ===== BARCODE ===== --}}
        <div class="center" style="margin-top: 6px;">
            <div class="barcode">*{{ $sale->invoice_no }}*</div>
            <div class="small">{{ $sale->invoice_no }}</div>
        </div>

        <div class="divider"></div>

        {{-- ===== SYSTEM FOOTER — from settings ===== --}}
        <div class="center small" style="margin-top: 6px;">
            Powered by <span class="bold">{{ $systemName }}</span>
        </div>

    </div>

    <script>
        // Auto-print after page loads
        window.addEventListener('load', function () {
            setTimeout(function () {
                window.print();
            }, 400);
        });
    </script>

</body>
</html>