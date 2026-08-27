@extends('layouts.admin')

@section('content')

@if(request()->has('print'))
<script>
    window.onload = () => window.print();
</script>
@endif

<style>
    .invoice-container {
        max-width: 1000px;
        margin: 0 auto;
        background: white;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }

    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 2px solid #e9f0f5;
        padding-bottom: 24px;
        margin-bottom: 24px;
    }

    .invoice-left {
        max-width: 50%;
    }

    .invoice-left .logo {
        max-height: 60px;
        margin-bottom: 12px;
    }

    .invoice-left .company-name {
        font-size: 18px;
        font-weight: 700;
        color: #0a1e2f;
        margin-bottom: 4px;
    }

    .invoice-left .company-address {
        font-size: 13px;
        color: #7c9eb2;
        line-height: 1.6;
    }

    .invoice-left .company-contact {
        font-size: 13px;
        color: #7c9eb2;
        margin-top: 4px;
    }

    .invoice-right {
        text-align: right;
        max-width: 50%;
    }

    .invoice-right h2 {
        font-size: 28px;
        font-weight: 800;
        color: #8B2452;
        letter-spacing: -0.5px;
        margin-bottom: 8px;
    }

    .invoice-right .invoice-detail {
        font-size: 13px;
        color: #7c9eb2;
    }

    .invoice-right .invoice-detail strong {
        color: #0a1e2f;
    }

    .invoice-right .invoice-status {
        display: inline-block;
        margin-top: 8px;
        padding: 4px 16px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        background: #e8f5e9;
        color: #0f7b4b;
    }

    .invoice-addresses {
        display: flex;
        justify-content: space-between;
        margin-bottom: 24px;
        gap: 20px;
    }

    .invoice-address-box {
        flex: 1;
        background: #f8fcff;
        border-radius: 12px;
        padding: 16px 20px;
        border: 1px solid #e9f0f5;
    }

    .invoice-address-box .label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #7c9eb2;
        margin-bottom: 4px;
    }

    .invoice-address-box .name {
        font-size: 16px;
        font-weight: 700;
        color: #0a1e2f;
    }

    .invoice-address-box .detail {
        font-size: 13px;
        color: #7c9eb2;
        line-height: 1.6;
    }

    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 24px;
    }

    .invoice-table thead th {
        background: #f8fcff;
        border-bottom: 2px solid #e9f0f5;
        padding: 12px 14px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #3e6579;
    }

    .invoice-table thead th.text-right {
        text-align: right;
    }

    .invoice-table thead th.text-center {
        text-align: center;
    }

    .invoice-table tbody td {
        padding: 12px 14px;
        border-bottom: 1px solid #f0f6fa;
        font-size: 13px;
        color: #1a2e3f;
    }

    .invoice-table tbody td.text-right {
        text-align: right;
    }

    .invoice-table tbody td.text-center {
        text-align: center;
    }

    .invoice-table .product-name {
        font-weight: 600;
        color: #0a1e2f;
    }

    .invoice-table .product-sku {
        font-size: 11px;
        color: #7c9eb2;
    }

    .invoice-totals {
        display: flex;
        justify-content: flex-end;
        margin-top: 24px;
    }

    .invoice-totals-box {
        width: 320px;
        background: #f8fcff;
        border-radius: 12px;
        padding: 16px 20px;
        border: 1px solid #e9f0f5;
    }

    .invoice-totals-box .total-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 13px;
        color: #1a2e3f;
    }

    .invoice-totals-box .total-row.border-top {
        border-top: 1px solid #e9f0f5;
        margin-top: 4px;
        padding-top: 12px;
    }

    .invoice-totals-box .total-row .label {
        color: #7c9eb2;
    }

    .invoice-totals-box .total-row .value {
        font-weight: 600;
    }

    .invoice-totals-box .total-row.grand-total {
        font-size: 16px;
        font-weight: 800;
        color: #8B2452;
    }

    .invoice-totals-box .total-row.grand-total .value {
        color: #8B2452;
    }

    .invoice-footer {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #e9f0f5;
        text-align: center;
        font-size: 13px;
        color: #7c9eb2;
    }

    .invoice-footer .thank-you {
        font-size: 16px;
        font-weight: 600;
        color: #8B2452;
    }

    @media print {
        body * {
            visibility: hidden !important;
        }
        
        .invoice-container, .invoice-container * {
            visibility: visible !important;
        }
        
        .invoice-container {
            position: fixed !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            padding: 30px !important;
            margin: 0 !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            background: white !important;
            max-width: 100% !important;
            z-index: 9999 !important;
        }
        
        .no-print {
            display: none !important;
        }
        
        body {
            background: white !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        
        .invoice-table th,
        .invoice-table td {
            border: 1px solid #000 !important;
        }
        
        .invoice-table thead th {
            background: #f0f0f0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        .invoice-status,
        .invoice-totals-box,
        .invoice-address-box {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        .invoice-header {
            border-bottom: 2px solid #000 !important;
        }
        
        .invoice-footer {
            border-top: 1px solid #000 !important;
        }
        
        .total-row.border-top {
            border-top: 2px solid #000 !important;
        }
    }

    @media (max-width: 768px) {
        .invoice-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
        .invoice-right {
            text-align: left;
            width: 100%;
            max-width: 100%;
        }
        .invoice-left {
            max-width: 100%;
        }
        .invoice-addresses {
            flex-direction: column;
        }
        .invoice-totals {
            justify-content: flex-start;
        }
        .invoice-totals-box {
            width: 100%;
        }
        .invoice-table {
            font-size: 12px;
        }
        .invoice-table thead th,
        .invoice-table tbody td {
            padding: 8px 10px;
        }
        .d-flex.justify-content-between {
            flex-direction: column;
            align-items: stretch !important;
            gap: 10px;
        }
        .d-flex.justify-content-between .d-flex.gap-2 {
            justify-content: flex-start;
        }
    }
</style>

<div class="container-fluid px-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Order
        </a>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print me-1"></i> Print
            </button>
            <button onclick="downloadPDF()" class="btn btn-success">
                <i class="fas fa-file-pdf me-1"></i> Download PDF
            </button>
        </div>
    </div>

    <div class="invoice-container">

        <div class="invoice-header">
            <div class="invoice-left">
                @if($company && $company->invoice_logo)
                    <img src="{{ \App\Helpers\S3Helper::url($company->invoice_logo) }}" 
                         alt="Invoice Logo" 
                         class="logo">
                @elseif($company && $company->logo_url)
                    <img src="{{ $company->logo_url }}" 
                         alt="Logo" 
                         class="logo">
                @endif

                <div class="company-name">
                    {{ $company->invoice_name ?? $company->name ?? 'Her Ovia' }}
                </div>

                <div class="company-address">
                    {{ $company->address ?? '' }}<br>
                    {{ $company->city ?? '' }}, {{ $company->state ?? '' }}<br>
                    {{ $company->country ?? '' }} - {{ $company->pincode ?? '' }}
                </div>

                <div class="company-contact">
                    Email: {{ $company->invoice_email ?? $company->email ?? '' }}<br>
                    Phone: {{ $company->mobile ?? '' }}
                </div>
            </div>

            <div class="invoice-right">
                <h2>INVOICE</h2>
                <div class="invoice-detail">
                    <strong>No:</strong> {{ $order->order_number }}
                </div>
                <div class="invoice-detail">
                    <strong>Date:</strong> {{ $order->created_at->format('d M Y') }}
                </div>
                <div class="invoice-status">
                    @php
                        $statusLabels = [
                            'pending' => 'Order Placed',
                            'confirmed' => 'Confirmed',
                            'processing' => 'Processing',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                            'cancelled' => 'Cancelled',
                        ];
                    @endphp
                    {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                </div>
            </div>
        </div>

        <div class="invoice-addresses">
            <div class="invoice-address-box">
                <div class="label">Billing To</div>
                <div class="name">
                    {{ optional($order->billingAddress)->full_name ?? optional($order->shippingAddress)->full_name ?? 'Walking Customer' }}
                </div>
                <div class="detail">
                    Mobile: {{ optional($order->billingAddress)->phone ?? optional($order->shippingAddress)->phone ?? '' }}<br>
                    {{ optional($order->billingAddress)->address_line_1 ?? optional($order->shippingAddress)->address_line_1 ?? '' }}<br>
                    {{ optional($order->billingAddress)->city ?? optional($order->shippingAddress)->city ?? '' }}, 
                    {{ optional($order->billingAddress)->state ?? optional($order->shippingAddress)->state ?? '' }}
                </div>
            </div>

            <div class="invoice-address-box">
                <div class="label">Shipping To</div>
                <div class="name">
                    {{ optional($order->shippingAddress)->full_name ?? 'Walking Customer' }}
                </div>
                <div class="detail">
                    Mobile: {{ optional($order->shippingAddress)->phone ?? '' }}<br>
                    {{ optional($order->shippingAddress)->address_line_1 ?? '' }}<br>
                    {{ optional($order->shippingAddress)->city ?? '' }}, 
                    {{ optional($order->shippingAddress)->state ?? '' }}
                </div>
            </div>
        </div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Item</th>
                    <th style="width:80px;text-align:center;">Qty</th>
                    <th style="width:120px;text-align:right;">Price</th>
                    <th style="width:130px;text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $subtotal = $order->items->sum(function($item) {
                        return $item->price * $item->quantity;
                    });
                @endphp
                @foreach($order->items as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                        <div class="product-name">{{ $item->product_name ?? optional($item->product)->name }}</div>
                        <div class="product-sku">SKU: {{ $item->sku ?? 'N/A' }}</div>
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">₹{{ number_format($item->price, 2) }}</td>
                    <td class="text-right">₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="invoice-totals">
            <div class="invoice-totals-box">
                <div class="total-row">
                    <span class="label">Subtotal</span>
                    <span class="value">₹{{ number_format($subtotal, 2) }}</span>
                </div>

                @if($order->discount > 0)
                <div class="total-row" style="color:#0f7b4b;">
                    <span class="label">Discount</span>
                    <span class="value">- ₹{{ number_format($order->discount, 2) }}</span>
                </div>
                @endif

                <div class="total-row">
                    <span class="label">Tax</span>
                    <span class="value">₹{{ number_format($order->tax ?? 0, 2) }}</span>
                </div>

                <div class="total-row">
                    <span class="label">Shipping</span>
                    <span class="value">₹{{ number_format($order->shipping ?? 0, 2) }}</span>
                </div>

                @if($order->platform_fee > 0)
                <div class="total-row">
                    <span class="label">Platform Fee</span>
                    <span class="value">₹{{ number_format($order->platform_fee, 2) }}</span>
                </div>
                @endif

                @if($order->coupon_code)
                <div class="total-row" style="color:#0f7b4b;">
                    <span class="label">Coupon: {{ $order->coupon_code }}</span>
                    <span class="value">- ₹{{ number_format($order->discount ?? 0, 2) }}</span>
                </div>
                @endif

                <div class="total-row border-top grand-total">
                    <span class="label">Total Amount</span>
                    <span class="value">₹{{ number_format($order->total ?? 0, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="invoice-footer">
            <div class="thank-you">Thank you </div>
            <div style="margin-top:4px;font-size:12px;">
                {{ $company->name ?? 'Her Ovia' }} - {{ $company->city ?? '' }}
            </div>
        </div>

    </div>
</div>

<script>
    function downloadPDF() {
        window.print();
    }
</script>

@endsection