<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $order->order_number }}</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            background: #ffffff;
            color: #321d27;
            font-family: DejaVu Sans, sans-serif;
            font-size: 8.5px;
        }

        .invoice-page {
            position: relative;
            width: 190mm;
            min-height: 277mm;
            margin: 10mm auto;
            padding: 0;
            overflow: hidden;
            background: #fffdfb;
        }

        .invoice-content {
            position: relative;
            z-index: 2;
            width: 100%;
        }

        .invoice-watermark {
            position: absolute;
            top: 105mm;
            left: 50%;
            width: 92mm;
            height: 92mm;
            margin-left: -46mm;
            opacity: .035;
            z-index: 0;
        }

        .invoice-watermark img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .decor-top-right {
            position: absolute;
            top: -35px;
            right: -35px;
            width: 125px;
            height: 125px;
            background: #f4e3e5;
            border-radius: 50%;
            opacity: .5;
            z-index: 0;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            border-bottom: 1px solid #dcb6bd;
        }

        .header-table td {
            vertical-align: top;
            padding: 0 0 12px;
        }

        .company-column {
            width: 55%;
            padding-right: 12px !important;
        }

        .invoice-column {
            width: 45%;
            text-align: right;
            padding-left: 12px !important;
        }

        .logo {
            display: block;
            width: 115px;
            height: 55px;
            object-fit: contain;
            object-position: left center;
            margin-bottom: 4px;
        }

        .company-name {
            color: #6e213c;
            font-family: Georgia, serif;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .company-detail {
            color: #684c58;
            font-size: 8px;
            line-height: 1.55;
        }

        .invoice-title {
            color: #761d3b;
            font-family: Georgia, serif;
            font-size: 27px;
            font-weight: bold;
            letter-spacing: 1px;
            line-height: 1;
            margin-bottom: 7px;
        }

        .invoice-title-line {
            width: 90px;
            height: 1px;
            background: #bd7989;
            margin: 0 0 7px auto;
        }

        .invoice-detail {
            color: #5d4550;
            font-size: 8px;
            line-height: 1.65;
            white-space: nowrap;
        }

        .invoice-detail strong {
            color: #321d27;
        }

        .status {
            display: inline-block;
            margin-top: 5px;
            padding: 4px 11px;
            border-radius: 15px;
            background: #e4f5ea;
            color: #16804a;
            font-size: 7.5px;
            font-weight: bold;
        }

        .section-gap {
            height: 10px;
        }

        .address-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .address-table td {
            width: 50%;
            vertical-align: top;
            padding: 0 4px;
        }

        .address-table td:first-child {
            padding-left: 0;
        }

        .address-table td:last-child {
            padding-right: 0;
        }

        .address-card {
            width: 100%;
            min-height: 108px;
            border: 1px solid #dfbfc6;
            border-radius: 7px;
            background: #fffafa;
            overflow: hidden;
        }

        .address-heading {
            background: #f3dddd;
            padding: 7px 10px;
            color: #70223e;
            font-family: Georgia, serif;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: .4px;
        }

        .address-body {
            padding: 8px 10px;
        }

        .address-name {
            color: #1d1d25;
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .address-line {
            color: #4e3b44;
            font-size: 8px;
            line-height: 1.6;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 10px;
            border: 1px solid #dfc3c8;
        }

        .products-table th {
            background: #842741;
            color: #ffffff;
            padding: 7px 5px;
            font-family: Georgia, serif;
            font-size: 8px;
            letter-spacing: .25px;
            text-align: left;
        }

        .products-table th.center,
        .products-table td.center {
            text-align: center;
        }

        .products-table th.right,
        .products-table td.right {
            text-align: right;
        }

        .products-table td {
            background: #ffffff;
            border-right: 1px solid #ead9dc;
            border-bottom: 1px solid #ead9dc;
            padding: 6px 5px;
            vertical-align: middle;
            font-size: 7.8px;
            overflow: hidden;
        }

        .products-table tr:last-child td {
            border-bottom: 0;
        }

        .products-table td:last-child,
        .products-table th:last-child {
            border-right: 0;
        }

        .product-image {
            width: 42px;
            height: 48px;
            object-fit: contain;
            background: #f7eeee;
            border: 1px solid #ead5d9;
            border-radius: 4px;
            vertical-align: middle;
            margin-right: 5px;
        }

        .product-info {
            display: inline-block;
            vertical-align: middle;
            width: 170px;
            max-width: 170px;
        }

        .product-name {
            color: #211b20;
            font-weight: bold;
            font-size: 8px;
            line-height: 1.35;
        }

        .product-meta {
            color: #7d6570;
            font-size: 6.8px;
            margin-top: 2px;
            line-height: 1.35;
        }

        .price {
            color: #31242b;
            font-weight: 600;
            white-space: nowrap;
        }

        .summary-area {
            width: 100%;
            margin-top: 10px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .summary-table > tbody > tr > td {
            vertical-align: top;
            padding: 0 4px;
        }

        .summary-table > tbody > tr > td:first-child {
            padding-left: 0;
        }

        .summary-table > tbody > tr > td:last-child {
            padding-right: 0;
        }

        .payment-column {
            width: 58%;
        }

        .totals-column {
            width: 42%;
        }

        .info-card,
        .totals-card {
            border: 1px solid #dfc3c8;
            border-radius: 7px;
            background: #fffafa;
            overflow: hidden;
        }

        .card-heading,
        .totals-heading {
            background: #f3dddd;
            padding: 7px 10px;
            color: #70223e;
            font-family: Georgia, serif;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: .3px;
        }

        .card-body {
            padding: 8px 10px;
        }

        .payment-row {
            width: 100%;
            padding: 3.5px 0;
            font-size: 7.8px;
        }

        .payment-row table,
        .total-row table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .payment-label {
            color: #6c5861;
            width: 44%;
        }

        .payment-value {
            color: #2b2026;
            font-weight: 600;
        }

        .paid-badge {
            display: inline-block;
            padding: 3px 7px;
            border-radius: 12px;
            background: #e4f5ea;
            color: #16804a;
            font-weight: bold;
            font-size: 6.8px;
        }

        .totals-body {
            padding: 7px 9px 0;
        }

        .total-row {
            width: 100%;
            padding: 3px 0;
            font-size: 7.8px;
        }

        .total-label {
            color: #64505a;
        }

        .total-value {
            color: #30232a;
            text-align: right;
            font-weight: 600;
            white-space: nowrap;
        }

        .discount {
            color: #e23b66;
        }

        .grand-total {
            margin: 5px -9px 0;
            padding: 8px 9px;
            background: #7f2944;
        }

        .grand-total .total-label,
        .grand-total .total-value {
            color: #ffffff;
            font-size: 9.5px;
            font-weight: bold;
        }

        .footer {
            margin-top: 10px;
            border-top: 1px solid #e1c4ca;
            padding-top: 8px;
            text-align: center;
        }

        .thank-you {
            color: #7a2441;
            font-family: Georgia, serif;
            font-size: 12px;
            font-style: italic;
        }

        .footer-brand {
            color: #76213d;
            font-family: Georgia, serif;
            font-size: 17px;
            font-weight: bold;
            margin-top: 2px;
        }

        .footer-tagline {
            color: #8a6873;
            font-size: 6px;
            letter-spacing: 1.3px;
            margin-top: 2px;
        }

        .footer-features {
            width: 100%;
            margin-top: 7px;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .footer-features td {
            width: 25%;
            border-right: 1px solid #e1c4ca;
            color: #76213d;
            font-size: 6px;
            padding: 3px;
        }

        .footer-features td:last-child {
            border-right: 0;
        }

        .bottom-bar {
            margin-top: 8px;
            padding: 6px;
            background: #76213d;
            color: #ffffff;
            text-align: center;
            font-size: 6.5px;
        }
    </style>
</head>

<body>

@php
    $shippingAddress = $order->shippingAddress;
    $billingAddress = $order->billingAddress ?: $shippingAddress;

    $statusLabels = [
        'pending' => 'Order Placed',
        'confirmed' => 'Confirmed',
        'processing' => 'Processing',
        'shipped' => 'Shipped',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
    ];

    $statusLabel = $statusLabels[$order->status] ?? ucfirst($order->status ?? '');

    $paymentMethod = strtoupper($order->payment_method ?? 'N/A');
    $paymentStatus = ucfirst($order->payment_status ?? 'N/A');

    $subtotal = (float) ($order->subtotal ?? 0);
    $tax = (float) ($order->tax ?? 0);
    $shippingCharge = (float) ($order->shipping ?? 0);
    $discount = (float) ($order->discount ?? 0);
    $platformFee = (float) ($order->platform_fee ?? 0);
    $total = (float) ($order->total ?? 0);

    $logoUrl = '';

    if ($company && $company->invoice_logo) {
        $logoUrl = \App\Helpers\S3Helper::url($company->invoice_logo);
    } elseif ($company && $company->logo_url) {
        $logoUrl = $company->logo_url;
    }
@endphp

<div class="invoice-page">

    <div class="decor-top-right"></div>

    @if($logoUrl)
        <div class="invoice-watermark">
            <img src="{{ $logoUrl }}" alt="">
        </div>
    @endif

    <div class="invoice-content">

        <table class="header-table">
            <tr>

                <td class="company-column">

                    @if($logoUrl)
                        <img
                            src="{{ $logoUrl }}"
                            class="logo"
                            alt="Her-Ovia"
                        >
                    @endif

                    <div class="company-name">
                        {{ $company->invoice_name ?? $company->name ?? 'Her-Ovia' }}
                    </div>

                    <div class="company-detail">
                        {{ $company->address ?? '' }}

                        @if($company->city || $company->state || $company->country)
                            <br>
                            {{ $company->city ?? '' }}
                            @if($company->state)
                                , {{ $company->state }}
                            @endif
                            @if($company->country)
                                , {{ $company->country }}
                            @endif
                            @if($company->pincode)
                                - {{ $company->pincode }}
                            @endif
                        @endif

                        @if($company->invoice_email ?? $company->email)
                            <br>
                            {{ $company->invoice_email ?? $company->email }}
                        @endif

                        @if($company->mobile)
                            <br>
                            {{ $company->mobile }}
                        @endif
                    </div>

                </td>

                <td class="invoice-column">

                    <div class="invoice-title">
                        INVOICE
                    </div>

                    <div class="invoice-title-line"></div>

                    <div class="invoice-detail">
                        <strong>Invoice No</strong>
                        : {{ $order->order_number }}
                    </div>

                    <div class="invoice-detail">
                        <strong>Date</strong>
                        : {{ $order->created_at ? $order->created_at->format('d M Y') : '' }}
                    </div>

                    <div class="invoice-detail">
                        <strong>Payment</strong>
                        : {{ $paymentMethod }}
                    </div>

                    <div class="invoice-detail">
                        <strong>Payment Status</strong>
                        : {{ $paymentStatus }}
                    </div>

                    <div class="status">
                        {{ $statusLabel }}
                    </div>

                </td>

            </tr>
        </table>

        <div class="section-gap"></div>

        <table class="address-table">
            <tr>

                <td>

                    <div class="address-card">

                        <div class="address-heading">
                            BILLING ADDRESS
                        </div>

                        <div class="address-body">

                            <div class="address-name">
                                {{ optional($billingAddress)->full_name ?? 'Customer' }}
                            </div>

                            @if(optional($billingAddress)->phone)
                                <div class="address-line">
                                    Phone: {{ $billingAddress->phone }}
                                </div>
                            @endif

                            <div class="address-line">
                                Address:
                                {{ optional($billingAddress)->address_line_1 ?? '' }}

                                @if(optional($billingAddress)->address_line_2)
                                    <br>
                                    {{ $billingAddress->address_line_2 }}
                                @endif

                                <br>

                                {{ optional($billingAddress)->city ?? '' }},
                                {{ optional($billingAddress)->state ?? '' }}
                                -
                                {{ optional($billingAddress)->postal_code ?? '' }}

                                <br>

                                {{ optional($billingAddress)->country ?? 'India' }}
                            </div>

                        </div>

                    </div>

                </td>

                <td>

                    <div class="address-card">

                        <div class="address-heading">
                            SHIPPING ADDRESS
                        </div>

                        <div class="address-body">

                            <div class="address-name">
                                {{ optional($shippingAddress)->full_name ?? 'Customer' }}
                            </div>

                            @if(optional($shippingAddress)->phone)
                                <div class="address-line">
                                    Phone: {{ $shippingAddress->phone }}
                                </div>
                            @endif

                            <div class="address-line">
                                Address:
                                {{ optional($shippingAddress)->address_line_1 ?? '' }}

                                @if(optional($shippingAddress)->address_line_2)
                                    <br>
                                    {{ $shippingAddress->address_line_2 }}
                                @endif

                                <br>

                                {{ optional($shippingAddress)->city ?? '' }},
                                {{ optional($shippingAddress)->state ?? '' }}
                                -
                                {{ optional($shippingAddress)->postal_code ?? '' }}

                                <br>

                                {{ optional($shippingAddress)->country ?? 'India' }}
                            </div>

                        </div>

                    </div>

                </td>

            </tr>
        </table>

        <table class="products-table">

            <thead>
                <tr>
                    <th style="width:28px;">#</th>
                    <th>PRODUCT</th>
                    <th class="center" style="width:42px;">QTY</th>
                    <th class="right" style="width:78px;">PRICE</th>
                    <th class="right" style="width:85px;">TOTAL</th>
                </tr>
            </thead>

            <tbody>

                @foreach($order->items as $key => $item)

                    @php
                        $productImage = null;

                        if ($item->image) {
                            $productImage = str_starts_with($item->image, 'http')
                                ? $item->image
                                : \App\Helpers\S3Helper::url($item->image);
                        }

                        $variantType = optional($item->variant)->variant_type;
                        $variantValue = optional($item->variant)->variant_value;
                    @endphp

                    <tr>

                        <td>
                            {{ $key + 1 }}
                        </td>

                        <td>

                            @if($productImage)
                                <img
                                    src="{{ $productImage }}"
                                    class="product-image"
                                    alt=""
                                >
                            @endif

                            <div class="product-info">

                                <div class="product-name">
                                    {{ $item->product_name ?? 'Product' }}
                                </div>

                                @if($variantType || $variantValue || ($item->sku ?? null))

                                    <div class="product-meta">

                                        @if($variantType && $variantValue)
                                            {{ $variantType }}: {{ $variantValue }}
                                        @endif

                                        @if($item->sku ?? null)
                                            <br>
                                            SKU: {{ $item->sku }}
                                        @endif

                                    </div>

                                @endif

                            </div>

                        </td>

                        <td class="center">
                            {{ $item->quantity }}
                        </td>

                        <td class="right price">
                            Rs. {{ number_format((float) $item->price, 2) }}
                        </td>

                        <td class="right price">
                            Rs. {{ number_format((float) $item->price * (int) $item->quantity, 2) }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

        <div class="summary-area">

            <table class="summary-table">

                <tr>

                    <td class="payment-column">

                        <div class="info-card">

                            <div class="card-heading">
                                PAYMENT INFORMATION
                            </div>

                            <div class="card-body">

                                <div class="payment-row">
                                    <table>
                                        <tr>
                                            <td class="payment-label">
                                                Payment Method
                                            </td>
                                            <td class="payment-value">
                                                : {{ $paymentMethod }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="payment-row">
                                    <table>
                                        <tr>
                                            <td class="payment-label">
                                                Payment Status
                                            </td>
                                            <td class="payment-value">
                                                :
                                                <span class="paid-badge">
                                                    {{ $paymentStatus }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="payment-row">
                                    <table>
                                        <tr>
                                            <td class="payment-label">
                                                Order Status
                                            </td>
                                            <td class="payment-value">
                                                :
                                                <span class="paid-badge">
                                                    {{ $statusLabel }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                            </div>

                        </div>

                    </td>

                    <td class="totals-column">

                        <div class="totals-card">

                            <div class="totals-heading">
                                ORDER SUMMARY
                            </div>

                            <div class="totals-body">

                                <div class="total-row">
                                    <table>
                                        <tr>
                                            <td class="total-label">
                                                Subtotal
                                            </td>
                                            <td class="total-value">
                                                Rs. {{ number_format($subtotal, 2) }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                @if($discount > 0)

                                    <div class="total-row">
                                        <table>
                                            <tr>
                                                <td class="total-label discount">
                                                    Discount
                                                </td>
                                                <td class="total-value discount">
                                                    - Rs. {{ number_format($discount, 2) }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                @endif

                                @if($order->coupon_code)

                                    <div class="total-row">
                                        <table>
                                            <tr>
                                                <td class="total-label">
                                                    Coupon
                                                </td>
                                                <td class="total-value">
                                                    {{ $order->coupon_code }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                @endif

                                <div class="total-row">
                                    <table>
                                        <tr>
                                            <td class="total-label">
                                                Tax
                                            </td>
                                            <td class="total-value">
                                                Rs. {{ number_format($tax, 2) }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="total-row">
                                    <table>
                                        <tr>
                                            <td class="total-label">
                                                Shipping
                                            </td>
                                            <td class="total-value">
                                                Rs. {{ number_format($shippingCharge, 2) }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                @if($platformFee > 0)

                                    <div class="total-row">
                                        <table>
                                            <tr>
                                                <td class="total-label">
                                                    Platform Fee
                                                </td>
                                                <td class="total-value">
                                                    Rs. {{ number_format($platformFee, 2) }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                @endif

                                <div class="grand-total">

                                    <table style="width:100%;border-collapse:collapse;">

                                        <tr>
                                            <td class="total-label">
                                                Total Amount
                                            </td>

                                            <td class="total-value">
                                                Rs. {{ number_format($total, 2) }}
                                            </td>
                                        </tr>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </td>

                </tr>

            </table>

        </div>

        <div class="footer">

            <div class="thank-you">
                Thank you for shopping with
            </div>

            <div class="footer-brand">
                {{ $company->invoice_name ?? $company->name ?? 'Her-Ovia' }}
            </div>

            <div class="footer-tagline">
                CRAFTED WITH ELEGANCE, DELIVERED WITH CARE.
            </div>

            <table class="footer-features">

                <tr>

                    <td>
                        PREMIUM QUALITY
                    </td>

                    <td>
                        TRENDY COLLECTIONS
                    </td>

                    <td>
                        SAFE & ON-TIME DELIVERY
                    </td>

                    <td>
                        CUSTOMER SATISFACTION
                    </td>

                </tr>

            </table>

        </div>

        <div class="bottom-bar">
            Her-Ovia
            &nbsp; | &nbsp;
            {{ $company->city ?? 'Ahmedabad' }}
            &nbsp; | &nbsp;
            {{ $company->country ?? 'India' }}
        </div>

    </div>

</div>

</body>
</html>