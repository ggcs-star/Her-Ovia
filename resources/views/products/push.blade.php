@extends('layouts.admin')

@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

* {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif !important;
}

:root {
    --primary: #A3006B;
    --primary-light: #C71585;
    --primary-bg: #F7F3FF;
    --green: #14B86A;
    --green-bg: #E6F9F1;
    --red: #E74C3C;
    --gray-border: #E5E7EB;
    --text-dark: #111827;
    --text-muted: #6B7280;
}

body {
    background: #F3F4F6 !important;
}

.push-wrapper {
    max-width: 1440px;
    margin: 0 auto;
    padding: 20px 24px 40px;
}

.page-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
}

.page-head h1 {
    font-size: 22px;
    font-weight: 700;
    color: #111827;
    margin: 0;
    letter-spacing: -0.3px;
}

.page-head .sub-head {
    font-size: 13px;
    color: #6B7280;
    font-weight: 400;
    margin-top: 2px;
}

.btn-back-link {
    font-size: 13px;
    color: #6B7280;
    text-decoration: none;
    font-weight: 500;
    padding: 6px 16px;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    transition: all 0.2s;
}

.btn-back-link:hover {
    border-color: #A3006B;
    color: #A3006B;
    text-decoration: none;
}

.card-ref {
    background: #FFFFFF;
    border: 1px solid #E5E7EB;
    border-radius: 16px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    overflow: hidden;
    margin-bottom: 20px;
}

.card-ref .card-head {
    background: #F7F3FF;
    border-bottom: 1px solid #E5E7EB;
    padding: 14px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.card-ref .card-head h5 {
    font-size: 14px;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.card-ref .card-head small {
    font-size: 12px;
    color: #6B7280;
    font-weight: 400;
}

.card-ref .card-body {
    padding: 18px 20px 20px;
}

.card-ref .card-foot {
    background: transparent;
    border-top: 1px solid #E5E7EB;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
}

.product-info-row {
    display: flex;
    align-items: flex-start;
    gap: 16px;
}

.product-info-row .prod-img {
    width: 68px;
    height: 68px;
    border-radius: 10px;
    border: 1px solid #E5E7EB;
    object-fit: cover;
    flex-shrink: 0;
}

.product-info-row .prod-details h6 {
    font-size: 15px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 2px;
}

.product-info-row .prod-details .sku-text {
    font-size: 12px;
    color: #6B7280;
}

.product-info-row .prod-details .brand-cat {
    font-size: 12px;
    color: #6B7280;
}

.product-info-row .prod-details .variant-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 4px;
}

.product-info-row .prod-details .variant-tag .v-label {
    font-size: 11px;
    font-weight: 600;
    color: #A3006B;
    background: #F7F3FF;
    padding: 2px 10px;
    border-radius: 20px;
}

.product-info-row .prod-details .variant-tag .v-value {
    font-size: 13px;
    font-weight: 600;
    color: #111827;
}

.product-info-row .prod-details .variant-tag .v-color {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    border: 1px solid #E5E7EB;
    display: inline-block;
}

.variant-po-master {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 12px;
    margin-top: 6px;
}

.select-variant-box {
    background: #F7F3FF;
    border-radius: 10px;
    padding: 10px 14px;
    border: 1px solid rgba(163, 0, 107, 0.08);
}

.select-variant-box label {
    font-size: 11px;
    font-weight: 600;
    color: #6B7280;
    display: block;
    margin-bottom: 4px;
}

.select-variant-box select {
    width: 100%;
    border: 1.5px solid #E5E7EB;
    border-radius: 8px;
    padding: 6px 10px;
    font-size: 13px;
    font-weight: 500;
    color: #111827;
    background: #FFFFFF;
    height: 36px;
}

.select-variant-box select:focus {
    border-color: #A3006B;
    outline: none;
    box-shadow: 0 0 0 3px rgba(163, 0, 107, 0.08);
}

.po-box-ref {
    background: #F7F3FF;
    border-radius: 10px;
    padding: 10px 14px;
    border: 1px solid rgba(163, 0, 107, 0.08);
}

.po-box-ref .po-title {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6B7280;
    text-align: center;
    margin-bottom: 6px;
}

.po-box-ref .po-row {
    display: flex;
    justify-content: space-between;
    padding: 2px 0;
    font-size: 12px;
    border-bottom: 1px solid rgba(163, 0, 107, 0.05);
}

.po-box-ref .po-row:last-child {
    border-bottom: none;
}

.po-box-ref .po-row .po-label {
    color: #6B7280;
    font-weight: 500;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.po-box-ref .po-row .po-value {
    font-weight: 700;
    font-size: 14px;
    color: #111827;
}

.po-box-ref .po-row .po-value.green { color: #14B86A; }
.po-box-ref .po-row .po-value.primary { color: #A3006B; }

.master-box-ref {
    background: #E6F9F1;
    border-radius: 10px;
    padding: 10px 14px;
    border: 1px solid rgba(20, 184, 106, 0.10);
    text-align: center;
}

.master-box-ref .ms-label {
    font-size: 8px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #6B7280;
}

.master-box-ref .ms-value {
    font-size: 24px;
    font-weight: 800;
    color: #14B86A;
    line-height: 1.2;
}

.master-box-ref .ms-sub {
    font-size: 11px;
    font-weight: 500;
    color: #6B7280;
}

.master-box-ref .ms-sub .allocated { color: #A3006B; font-weight: 600; }
.master-box-ref .ms-sub .remaining { color: #14B86A; font-weight: 600; }

.platform-cards-wrap {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.platform-card-ref {
    background: #FFFFFF;
    border: 2px solid #E5E7EB;
    border-radius: 10px;
    padding: 8px 16px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.2s;
    user-select: none;
    min-width: 130px;
    justify-content: center;
}

.platform-card-ref:hover {
    border-color: #A3006B;
    background: #FCF8FF;
}

.platform-card-ref.active {
    border-color: #A3006B;
    background: #F7F3FF;
    box-shadow: 0 0 0 3px rgba(163, 0, 107, 0.08);
}

.platform-card-ref input[type="checkbox"] {
    width: 15px;
    height: 15px;
    accent-color: #A3006B;
    cursor: pointer;
    margin: 0;
    flex-shrink: 0;
}

.platform-card-ref .p-icon {
    font-size: 15px;
    line-height: 1;
}

.platform-card-ref .p-name {
    font-weight: 600;
    font-size: 13px;
    color: #111827;
}

.pricing-table-wrap {
    overflow-x: auto;
}

.pricing-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 6px;
    font-size: 12px;
}

.pricing-table thead th {
    font-size: 9px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6B7280;
    padding: 4px 4px 8px;
    text-align: center;
    border-bottom: 1px solid #E5E7EB;
}

.pricing-table thead th:first-child { text-align: left; }

.pricing-table thead th span.sub {
    font-weight: 400;
    font-size: 7px;
    color: #9CA3AF;
    display: block;
}

.pricing-row-ref {
    background: #FFFFFF;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    transition: all 0.2s;
}

.pricing-row-ref:hover {
    border-color: #A3006B;
}

.pricing-row-ref td {
    padding: 6px 4px;
    vertical-align: middle;
    text-align: center;
}

.pricing-row-ref td:first-child {
    text-align: left;
    padding-left: 12px;
}

.pricing-row-ref td:last-child {
    text-align: right;
    padding-right: 12px;
}

.pricing-row-ref .platform-name {
    font-weight: 600;
    font-size: 12px;
    color: #111827;
    white-space: nowrap;
}

.qty-input-wrap {
    position: relative;
    display: inline-block;
    width: 100%;
    max-width: 85px;
}

.qty-input-wrap input {
    width: 100%;
    height: 34px;
    padding: 2px 32px 2px 8px;
    font-size: 13px;
    font-weight: 700;
    color: #14B86A;
    border: 1.5px solid #E5E7EB;
    border-radius: 8px;
    background: #FFFFFF;
    text-align: center;
    transition: all 0.2s;
}

.qty-input-wrap input:focus {
    border-color: #A3006B;
    outline: none;
    box-shadow: 0 0 0 3px rgba(163, 0, 107, 0.08);
}

.qty-input-wrap .max-label {
    position: absolute;
    right: 6px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 8px;
    font-weight: 500;
    color: #9CA3AF;
    pointer-events: none;
    background: #FFFFFF;
    padding: 0 2px;
    border-radius: 4px;
}

.qty-input-wrap .max-label strong {
    color: #111827;
    font-weight: 600;
}

.price-input {
    width: 100%;
    max-width: 80px;
    height: 34px;
    padding: 2px 6px;
    font-size: 13px;
    font-weight: 500;
    color: #111827;
    border: 1.5px solid #E5E7EB;
    border-radius: 8px;
    background: #FFFFFF;
    text-align: center;
    transition: all 0.2s;
}

.price-input:focus {
    border-color: #A3006B;
    outline: none;
    box-shadow: 0 0 0 3px rgba(163, 0, 107, 0.08);
}

.discount-group-ref {
    display: inline-flex;
    align-items: center;
    gap: 3px;
}

.discount-group-ref input {
    width: 44px;
    height: 34px;
    padding: 2px 4px;
    font-size: 13px;
    font-weight: 600;
    color: #111827;
    border: 1.5px solid #E5E7EB;
    border-radius: 8px;
    background: #FFFFFF;
    text-align: center;
    transition: all 0.2s;
}

.discount-group-ref input:focus {
    border-color: #A3006B;
    outline: none;
    box-shadow: 0 0 0 3px rgba(163, 0, 107, 0.08);
}

.discount-group-ref select {
    width: 30px;
    height: 34px;
    font-size: 10px;
    font-weight: 700;
    color: #111827;
    border: 1.5px solid #E5E7EB;
    border-radius: 8px;
    background: #FFFFFF;
    padding: 0 2px;
}

.discount-group-ref select:focus {
    border-color: #A3006B;
    outline: none;
}

.discount-off-text {
    font-size: 8px;
    color: #E74C3C;
    font-weight: 600;
    display: block;
    min-height: 14px;
    margin-top: 1px;
}

.final-price-input {
    width: 100%;
    max-width: 80px;
    height: 34px;
    padding: 2px 6px;
    font-size: 13px;
    font-weight: 700;
    color: #14B86A;
    background: #E6F9F1;
    border: 1.5px solid rgba(20, 184, 106, 0.15);
    border-radius: 8px;
    text-align: center;
    cursor: default;
}

.total-selling-input {
    width: 100%;
    max-width: 90px;
    height: 34px;
    padding: 2px 6px;
    font-size: 13px;
    font-weight: 700;
    color: #A3006B;
    background: #F7F3FF;
    border: 1.5px solid rgba(163, 0, 107, 0.08);
    border-radius: 8px;
    text-align: center;
    cursor: default;
}

.status-pill-ref {
    font-size: 9px;
    font-weight: 600;
    padding: 3px 12px;
    border-radius: 20px;
    display: inline-block;
    white-space: nowrap;
}

.status-pill-ref.active {
    background: #E6F9F1;
    color: #14B86A;
}

.status-pill-ref.disabled {
    background: #F3F4F6;
    color: #6B7280;
}

.btn-delete-row {
    background: transparent;
    border: none;
    padding: 2px 6px;
    font-size: 14px;
    color: #9CA3AF;
    cursor: pointer;
    border-radius: 6px;
    transition: all 0.2s;
}

.btn-delete-row:hover {
    background: #FEE2E2;
    color: #E74C3C;
}

.summary-ref {
    background: #F7F3FF;
    border: 1px solid #E5E7EB;
    border-radius: 16px;
    padding: 18px 20px;
    position: sticky;
    top: 20px;
}

.summary-ref .sum-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6B7280;
    padding-bottom: 8px;
    border-bottom: 2px solid #E5E7EB;
    margin-bottom: 10px;
}

.summary-ref .sum-item {
    display: flex;
    justify-content: space-between;
    padding: 3px 0;
    font-size: 13px;
}

.summary-ref .sum-item .label {
    color: #6B7280;
    font-weight: 500;
}

.summary-ref .sum-item .value {
    font-weight: 700;
    color: #111827;
}

.summary-ref .sum-item .value.green { color: #14B86A; }
.summary-ref .sum-item .value.primary { color: #A3006B; }
.summary-ref .sum-item .value.red { color: #E74C3C; }

.summary-ref .divider {
    border-top: 1px dashed #E5E7EB;
    margin: 8px 0;
}

.summary-ref .progress-summary {
    margin-top: 6px;
}

.summary-ref .progress-summary .ps-row {
    display: flex;
    justify-content: space-between;
    font-size: 11px;
    color: #6B7280;
}

.summary-ref .progress-summary .ps-row strong {
    color: #111827;
}

.summary-ref .progress-bar-ref {
    height: 4px;
    border-radius: 10px;
    background: #E5E7EB;
    overflow: hidden;
    margin-top: 4px;
}

.summary-ref .progress-bar-ref .fill {
    height: 100%;
    border-radius: 10px;
    background: linear-gradient(90deg, #14B86A, #20B75E);
    transition: width 0.5s ease;
}

.summary-ref .status-valid {
    text-align: center;
    margin-top: 8px;
    font-size: 12px;
    font-weight: 600;
    color: #14B86A;
}

.summary-ref .status-valid.error {
    color: #E74C3C;
}

.summary-ref .note-box-ref {
    background: #FFFBEB;
    border-radius: 8px;
    padding: 10px 12px;
    font-size: 11px;
    color: #6B7280;
    border: 1px solid rgba(245, 166, 35, 0.12);
    margin-top: 10px;
    line-height: 1.6;
}

.summary-ref .note-box-ref strong {
    color: #111827;
}

.footer-badge {
    font-size: 12px;
    color: #6B7280;
    background: #F9FAFB;
    padding: 3px 12px;
    border-radius: 20px;
}

.footer-badge strong {
    color: #111827;
    font-weight: 700;
}

.footer-status-text {
    font-size: 13px;
    font-weight: 500;
}

.footer-status-text.success { color: #14B86A; }
.footer-status-text.error { color: #E74C3C; }

.btn-success-ref {
    background: #14B86A;
    border: none;
    padding: 8px 28px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 13px;
    color: #FFFFFF;
    transition: all 0.3s;
}

.btn-success-ref:hover {
    background: #0D9E58;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(20, 184, 106, 0.30);
    color: #FFFFFF;
}

.btn-outline-ref {
    background: transparent;
    border: 2px solid #E5E7EB;
    padding: 6px 20px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 13px;
    color: #111827;
    transition: all 0.3s;
}

.btn-outline-ref:hover {
    border-color: #A3006B;
    color: #A3006B;
    background: #F7F3FF;
}

.btn-add-ref {
    border: 2px dashed #E5E7EB;
    background: transparent;
    padding: 6px 24px;
    border-radius: 10px;
    font-weight: 500;
    font-size: 12px;
    color: #6B7280;
    transition: all 0.3s;
}

.btn-add-ref:hover {
    border-color: #A3006B;
    color: #A3006B;
    background: #F7F3FF;
}

.btn-push-ref {
    background: #6C3CE1;
    border: none;
    padding: 10px 32px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 14px;
    color: #FFFFFF;
    transition: all 0.3s;
    box-shadow: 0 4px 12px rgba(108, 60, 225, 0.30);
}

.btn-push-ref:hover {
    background: #7B4EE8;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(108, 60, 225, 0.35);
    color: #FFFFFF;
}

@media (max-width: 992px) {
    .variant-po-master {
        grid-template-columns: 1fr 1fr;
    }
    .summary-ref {
        position: relative;
        top: 0;
        margin-top: 16px;
    }
}

@media (max-width: 768px) {
    .push-wrapper {
        padding: 12px 12px 24px;
    }
    .variant-po-master {
        grid-template-columns: 1fr;
    }
    .product-info-row {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .page-head {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    .card-ref .card-foot {
        flex-direction: column;
        align-items: flex-start;
    }
}

.modal-ref .modal-content {
    border-radius: 16px;
    border: 1px solid #E5E7EB;
}

.modal-ref .modal-header {
    border-bottom: 1px solid #E5E7EB;
    padding: 14px 20px;
}

.modal-ref .modal-header .modal-title {
    font-weight: 700;
    font-size: 15px;
    color: #111827;
}

.modal-ref .modal-body {
    padding: 16px 20px;
}

.modal-ref .modal-footer {
    border-top: 1px solid #E5E7EB;
    padding: 12px 20px;
}

.toast-ref {
    position: fixed;
    bottom: 30px;
    right: 30px;
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 500;
    font-size: 13px;
    z-index: 9999;
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    animation: slideUp 0.35s ease;
    border-left: 4px solid #A3006B;
    color: #FFFFFF;
    background: #111827;
}

.toast-ref.success { border-left-color: #14B86A; }
.toast-ref.error { border-left-color: #E74C3C; }

@keyframes slideUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
/* Select2 Design */
.select2-container {
    width: 100% !important;
    font-family: 'Inter', sans-serif !important;
}

.select2-container .select2-selection--single {
    height: 44px !important;
    border: 2px solid #E5E7EB !important;
    border-radius: 10px !important;
    display: flex !important;
    align-items: center !important;
    background: #fff !important;
    padding: 0 10px !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    color: #111827 !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #111827 !important;
    line-height: 40px !important;
    padding-left: 2px !important;
    font-size: 13px !important;
    font-weight: 500 !important;
}

.select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #9CA3AF !important;
    font-weight: 500 !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 42px !important;
    right: 10px !important;
}

.select2-dropdown {
    border: 2px solid #E5E7EB !important;
    border-radius: 10px !important;
    overflow: hidden;
    font-family: 'Inter', sans-serif !important;
}

.select2-search__field {
    height: 38px !important;
    border: 1px solid #E5E7EB !important;
    border-radius: 8px !important;
    font-size: 13px !important;
    padding: 8px 12px !important;
    font-family: 'Inter', sans-serif !important;
}

.select2-results__option {
    font-size: 13px !important;
    padding: 10px 14px !important;
    font-weight: 500 !important;
}

.select2-container--default .select2-results__option--highlighted {
    background: #A3006B !important;
    color: #fff !important;
}
</style>

<div class="push-wrapper">

    <div class="page-head">
        <div>
            <h1>Push Product to Marketplace</h1>
            <div class="sub-head">Select product and configure platform wise stock, price and discount</div>
        </div>
        <a href="{{ admin_route('products.index') }}" class="btn-back-link">← Back to Product</a>
    </div>

    <script>
        window.existingVariantPlatformData = @json($existingVariantPlatformData ?? []);
        window.purchaseOrderData = @json($purchaseOrderData ?? []);
        window.selectedProductId = @json($productId ?? null);
        window.pushedQuantities = @json($pushedQuantities ?? []);
        window.platformMap = {
            @foreach($platforms as $platform)
                "{{ $platform->id }}": "{{ ucfirst($platform->name) }}",
            @endforeach
        };
    </script>

    @include('products.partials._errors')

    <form id="pushProductForm" action="{{ admin_route('products.push.store') }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" id="selectedProductId">
        <input type="hidden" name="variant_platform_data" id="variantPlatformDataInput">

        <div id="productSelectSection">
            <div class="card-ref">
                <div class="card-head">
                    <h5>📦 Select Product</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-8">
                            <label style="font-size:13px; font-weight:600; color:#111827; display:block; margin-bottom:4px;">
                                Product Name <span class="text-danger">*</span>
                            </label>
                            <select id="productSelect" name="product_id" style="width:100%; border:2px solid #E5E7EB; border-radius:10px; padding:10px 14px; font-size:13px; font-weight:500; height:44px; background:#FFFFFF;">
                                <option value="">— Select product —</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
                                        data-category="{{ $product->display_category }}"
                                        data-subcategory="{{ $product->display_subcategory }}"
                                        data-sku="{{ $product->sku }}"
                                        data-slug="{{ $product->slug }}"
                                        data-brand="{{ $product->brand }}"
                                        data-warehouse="{{ $product->warehouse->name ?? '' }}"
                                        data-image="{{ $product->image }}"
                                        data-variants='@json($product->variant_payload)'>
                                        {{ $product->name }} ({{ $product->sku }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div>
                                <small style="font-size:11px; font-weight:500; color:#6B7280;">Selected Product</small>
                                <div id="selectedProductName" style="font-weight:700; color:#A3006B; font-size:14px;">—</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="configureVariantSection" style="display:none;">

            <div class="card-ref">

                <div class="card-head">
                    <h5>⚙️ Configure Variant</h5>
                    <small>Set platform wise stock, price and discount</small>
                </div>

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-5">
                            <div class="product-info-row">
                                <img id="pageProductImage"
                                     src="{{ asset('images/no-image.png') }}"
                                     class="prod-img">
                                <div class="prod-details">
                                    <h6 id="pageProductName">Product Name</h6>
                                    <div class="sku-text">SKU: <span id="pageSku">-</span></div>
                                    <div class="brand-cat">Brand: <span id="pageBrand">-</span> | Category: <span id="pageCategory">-</span></div>
                                    <div class="variant-tag">
                                        <span class="v-label">Variant</span>
                                        <span class="v-value" id="pageVariantValue">-</span>
                                        <span class="v-color" id="pageVariantColor" style="background:#808080;"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="variant-po-master">

                                <div class="select-variant-box">
                                    <label>Select Variant</label>
                                    <select id="variantSelect">
                                        <option value="">— Select variant —</option>
                                    </select>
                                </div>

                                <div class="po-box-ref">
                                    <div class="po-title">Purchase Order (PO)</div>
                                    <div class="po-row">
                                        <span class="po-label">PO Qty</span>
                                        <span class="po-value green"><span id="pagePoQty">0</span> Units</span>
                                    </div>
                                    <div class="po-row">
                                        <span class="po-label">PO Price</span>
                                        <span class="po-value primary">₹ <span id="pagePoPrice">0</span></span>
                                    </div>
                                    <div class="po-row">
                                        <span class="po-label">PO Value</span>
                                        <span class="po-value">₹ <span id="pagePoValue">0</span></span>
                                    </div>
                                </div>

                                <div class="master-box-ref">
                                    <div class="ms-label">Master Stock</div>
                                    <div class="ms-value"><span id="pageMasterStockNum">0</span> Units</div>
                                    <div class="ms-sub">
                                        Allocated: <span class="allocated" id="pageAllocated">0</span>
                                        &nbsp;|&nbsp; Remaining: <span class="remaining" id="pageRemaining">0</span>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div style="margin-top:20px;">
                        <div style="background:#FFFFFF; border:1px solid #E5E7EB; border-radius:12px; overflow:hidden;">
                            <div style="padding:10px 16px; border-bottom:1px solid #E5E7EB; display:flex; align-items:center; justify-content:space-between; background:#F7F3FF;">
                                <span style="font-size:13px; font-weight:700; color:#111827;">Select Sales Platforms</span>
                                <small style="font-size:11px; color:#6B7280;">Uncheck to disable and release allocated stock</small>
                            </div>
                            <div style="padding:12px 16px;">
                                <div class="platform-cards-wrap">
                                    @foreach($platforms as $platform)
                                        @php $name = strtolower($platform->name); @endphp
                                        <label class="platform-card-ref" id="platformLabel_{{ $platform->id }}">
                                            <input type="checkbox"
                                                   class="platform-checkbox"
                                                   value="{{ $platform->id }}"
                                                   data-platform-id="{{ $platform->id }}"
                                                   data-platform-name="{{ $platform->name }}"
                                                   id="platform_checkbox_{{ $platform->id }}">
                                            <span class="p-icon">
                                                @if(str_contains($name, 'amazon')) 🛒
                                                @elseif(str_contains($name, 'flipkart')) 🛒
                                                @elseif(str_contains($name, 'website') || str_contains($name, 'online')) 🌐
                                                @elseif(str_contains($name, 'offline')) 🏪
                                                @elseif(str_contains($name, 'meesho')) 🛍️
                                                @else 📱
                                                @endif
                                            </span>
                                            <span class="p-name">
                                                @if(str_contains($name, 'amazon')) Amazon
                                                @elseif(str_contains($name, 'flipkart')) Flipkart
                                                @elseif(str_contains($name, 'website') || str_contains($name, 'online')) Own Website
                                                @elseif(str_contains($name, 'offline')) Offline
                                                @elseif(str_contains($name, 'meesho')) Meesho
                                                @else {{ ucfirst($platform->name) }}
                                                @endif
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="pagePricingSection" style="display:none; margin-top:16px;">

                        <div class="row g-3">

                            <div class="col-lg-8">
                                <div style="background:#FFFFFF; border:1px solid #E5E7EB; border-radius:12px; overflow:hidden;">
                                    <div style="padding:10px 16px; border-bottom:1px solid #E5E7EB; background:#F7F3FF;">
                                        <span style="font-size:13px; font-weight:700; color:#111827;">Platform Allocation &amp; Pricing</span>
                                    </div>
                                    <div style="padding:4px 12px 12px;">
                                        <div class="pricing-table-wrap">
                                            <table class="pricing-table">
                                                <thead>
                                                    <tr>
                                                        <th style="width:13%; text-align:left;">Platform</th>
                                                        <th style="width:14%; text-align:center;">Allocate Stock<br><span class="sub">(Units)</span></th>
                                                        <th style="width:14%; text-align:center;">Selling Price<br><span class="sub">(₹) * Per Unit</span></th>
                                                        <th style="width:14%; text-align:center;">Discount<br><span class="sub">Per Unit</span></th>
                                                        <th style="width:14%; text-align:center;">Final Price<br><span class="sub">(₹) Per Unit</span></th>
                                                        <th style="width:16%; text-align:center;">Total Selling<br><span class="sub">(₹) Qty x Final Price</span></th>
                                                        <th style="width:9%; text-align:center;">Status</th>
                                                        <th style="width:6%; text-align:center;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="pagePricingTableBody">
                                                    @foreach($platforms as $platform)
                                                        @php $name = strtolower($platform->name); @endphp
                                                        <tr id="pageRow_{{ $platform->id }}" style="display:none;">
                                                            <td colspan="8" style="padding:2px 0;">
                                                                <div class="pricing-row-ref" id="pagePricingCard_{{ $platform->id }}" style="display:none; padding:4px 8px;">
                                                                    <div style="display:flex; align-items:center; gap:0; width:100%;">

                                                                        <div style="flex:0 0 13%; min-width:13%; padding:0 4px; text-align:left;">
                                                                            <span class="platform-name">
                                                                                @if(str_contains($name, 'amazon')) 🛒 Amazon
                                                                                @elseif(str_contains($name, 'flipkart')) 🛒 Flipkart
                                                                                @elseif(str_contains($name, 'website') || str_contains($name, 'online')) 🌐 Own Website
                                                                                @elseif(str_contains($name, 'offline')) 🏪 Offline
                                                                                @elseif(str_contains($name, 'meesho')) 🛍️ Meesho
                                                                                @else {{ ucfirst($platform->name) }}
                                                                                @endif
                                                                            </span>
                                                                        </div>

                                                                        <div style="flex:0 0 14%; min-width:14%; padding:0 4px; text-align:center;">
                                                                            <div class="qty-input-wrap">
                                                                                <input type="number"
                                                                                       id="page_qty_{{ $platform->id }}"
                                                                                       class="page-qty-input"
                                                                                       placeholder="0"
                                                                                       min="0"
                                                                                       value="0">
                                                                                <span class="max-label">Max: <strong id="page_max_{{ $platform->id }}">0</strong></span>
                                                                            </div>
                                                                        </div>

                                                                        <div style="flex:0 0 14%; min-width:14%; padding:0 4px; text-align:center;">
                                                                            <input type="number"
                                                                                   step="0.01"
                                                                                   id="page_price_{{ $platform->id }}"
                                                                                   class="page-price-input price-input"
                                                                                   placeholder="0.00"
                                                                                   min="0"
                                                                                   value="">
                                                                        </div>

                                                                        <div style="flex:0 0 14%; min-width:14%; padding:0 4px; text-align:center;">
                                                                            <div class="discount-group-ref">
                                                                                <input type="number"
                                                                                       step="0.01"
                                                                                       id="page_discount_value_{{ $platform->id }}"
                                                                                       placeholder="0"
                                                                                       min="0"
                                                                                       value="0">
                                                                                <select id="page_discount_type_{{ $platform->id }}">
                                                                                    <option value="amount">₹</option>
                                                                                    <option value="percent" selected>%</option>
                                                                                </select>
                                                                            </div>
                                                                            <span class="discount-off-text" id="page_discount_text_{{ $platform->id }}"></span>
                                                                        </div>

                                                                        <div style="flex:0 0 14%; min-width:14%; padding:0 4px; text-align:center;">
                                                                            <input type="text"
                                                                                   id="page_final_price_{{ $platform->id }}"
                                                                                   class="final-price-input"
                                                                                   value="₹ 0.00"
                                                                                   readonly>
                                                                        </div>

                                                                        <div style="flex:0 0 16%; min-width:16%; padding:0 4px; text-align:center;">
                                                                            <input type="text"
                                                                                   id="page_total_selling_{{ $platform->id }}"
                                                                                   class="total-selling-input"
                                                                                   value="₹ 0.00"
                                                                                   readonly>
                                                                        </div>

                                                                        <div style="flex:0 0 9%; min-width:9%; padding:0 4px; text-align:center;">
                                                                            <span class="status-pill-ref active" id="page_status_{{ $platform->id }}">Active</span>
                                                                        </div>

                                                                        <div style="flex:0 0 6%; min-width:6%; padding:0 4px; text-align:center;">
                                                                            <button type="button" class="btn-delete-row page-delete-row" data-platform-id="{{ $platform->id }}" style="display:none;">✕</button>
                                                                        </div>

                                                                    </div>
                                                                    <input type="hidden" id="page_po_available_{{ $platform->id }}" value="0">
                                                                    <input type="hidden" id="page_po_price_{{ $platform->id }}" value="0">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="summary-ref">

                                    <div class="sum-title">Allocation Summary</div>
                                    <div class="sum-item">
                                        <span class="label">Master Stock</span>
                                        <span class="value" id="pageSummaryMaster">0 Units</span>
                                    </div>
                                    <div class="sum-item">
                                        <span class="label">Total Allocated</span>
                                        <span class="value primary" id="pageSummaryAllocated">0 Units</span>
                                    </div>
                                    <div class="sum-item">
                                        <span class="label">Remaining</span>
                                        <span class="value green" id="pageSummaryRemaining">0 Units</span>
                                    </div>

                                    <div class="divider"></div>

                                    <div class="sum-title" style="margin-top:2px;">Financial Summary</div>
                                    <div class="sum-item">
                                        <span class="label">Total Purchase Value</span>
                                        <span class="value" id="pageSummaryPurchase">₹0</span>
                                    </div>
                                    <div class="sum-item">
                                        <span class="label">Expected Revenue</span>
                                        <span class="value" id="pageSummaryRevenue">₹0</span>
                                    </div>
                                    <div class="sum-item">
                                        <span class="label">Expected Profit</span>
                                        <span class="value green" id="pageSummaryProfit">₹0</span>
                                    </div>
                                    <div class="sum-item">
                                        <span class="label">Average Selling Price</span>
                                        <span class="value" id="pageSummaryAvgPrice">₹0 / Unit</span>
                                    </div>
                                    <div class="sum-item">
                                        <span class="label">Expected Margin</span>
                                        <span class="value primary" id="pageSummaryMargin">0%</span>
                                    </div>

                                    <div class="divider"></div>

                                    <div class="progress-summary">
                                        <div class="ps-row">
                                            <span>Master: <strong id="pageSummaryMasterSmall">0</strong></span>
                                            <span>Allocated: <strong id="pageSummaryAllocatedSmall">0</strong></span>
                                            <span>Remaining: <strong id="pageSummaryRemainingSmall">0</strong></span>
                                        </div>
                                        <div class="progress-bar-ref">
                                            <div id="pageSummaryProgress" class="fill" style="width:0%;"></div>
                                        </div>
                                    </div>

                                    <div class="status-valid" id="pageSummaryStatus">✅ Stock allocation is valid.</div>

                                    <div class="note-box-ref">
                                        <strong>Note:</strong> Discount is applied per unit.<br>
                                        Example: Selling Price ₹500 - 10% = ₹450 per unit.<br>
                                        For Qty 12 → Total = ₹450 × 12 = ₹5,400
                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="text-center mt-3">
                        <button type="button" class="btn-add-ref">+ Add More Platform</button>
                    </div>

                </div>

                <div class="card-foot">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <span class="footer-status-text success" id="pageStockStatus">✅ Stock allocation is valid.</span>
                        <span class="footer-badge">Master: <strong id="pageFooterMaster">0</strong></span>
                        <span class="footer-badge">Allocated: <strong id="pageFooterAllocated">0</strong></span>
                        <span class="footer-badge">Remaining: <strong id="pageFooterRemaining">0</strong></span>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn-outline-ref" id="cancelConfigure">Cancel</button>
                        <button type="button" class="btn-success-ref" id="savePageVariant">✅ Save Variant</button>
                    </div>
                </div>

            </div>

        </div>

        <div class="text-end mt-3">
            <button type="button" id="previewPushBtn" class="btn-push-ref">🚀 Push to Selected Platforms</button>
        </div>

        <div class="modal fade modal-ref" id="pushPreviewModal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Push Summary</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="pricing-table">
                                <thead>
                                    <tr>
                                        <th>Platform</th>
                                        <th>Type</th>
                                        <th>Value</th>
                                        <th>Color</th>
                                        <th>SKU</th>
                                        <th>Qty</th>
                                        <th>Price (₹)</th>
                                        <th>Discount</th>
                                        <th>Final Total (₹)</th>
                                    </tr>
                                </thead>
                                <tbody id="pushPreviewBody"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-outline-ref" data-bs-dismiss="modal" style="padding:4px 16px; font-size:12px;">Edit</button>
                        <button type="submit" class="btn-success-ref" id="confirmPushBtn" style="padding:4px 20px; font-size:12px;">Confirm &amp; Push</button>
                    </div>
                </div>
            </div>
        </div>

    </form>

</div>
@endsection
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  $('#productSelect').select2({
        placeholder: 'Search Product...',
        width: '100%'
    });

    $('#productSelect').on('select2:select', function () {
        this.dispatchEvent(new Event('change'));
    });
    const productSelect = document.getElementById('productSelect');
    const productSelectSection = document.getElementById('productSelectSection');
    const variantSelect = document.getElementById('variantSelect');
    const configureSection = document.getElementById('configureVariantSection');
    const pricingSection = document.getElementById('pagePricingSection');
    const previewBtn = document.getElementById('previewPushBtn');
    const previewBody = document.getElementById('pushPreviewBody');
    const confirmBtn = document.getElementById('confirmPushBtn');
    const form = document.getElementById('pushProductForm');

    let activeVariantId = null;
    let variantPlatformData = {};
    let currentVariantData = null;
    let alreadyPushed = 0;

    let saved = localStorage.getItem('variantPlatformData');
    if (saved) {
        try {
            let draft = JSON.parse(saved);
            if (draft._productId && draft._productId == document.getElementById('selectedProductId').value) {
                variantPlatformData = draft.data || {};
            }
        } catch(e) {}
    }

    productSelect.addEventListener('change', function() {
        document.getElementById('selectedProductId').value = this.value;
        const option = this.options[this.selectedIndex];

        if (!this.value) {
            configureSection.style.display = 'none';
            productSelectSection.style.display = 'block';
            document.getElementById('selectedProductName').innerText = '—';
            return;
        }

        productSelectSection.style.display = 'none';

        document.getElementById('selectedProductName').innerText = option.text;

        document.getElementById('pageProductName').innerText = option.text;
        document.getElementById('pageSku').innerText = option.dataset.sku || '-';
        document.getElementById('pageBrand').innerText = option.dataset.brand || '-';
        document.getElementById('pageCategory').innerText = option.dataset.category || '-';

        let imageUrl = option.dataset.image || '';
        document.getElementById('pageProductImage').src = imageUrl || '/images/no-image.png';

        let variants = [];
        try {
            variants = JSON.parse(option.dataset.variants || '[]');
        } catch(e) {}

        populateVariantDropdown(variants);
        configureSection.style.display = 'block';
        configureSection.scrollIntoView({ behavior: 'smooth' });
    });

function populateVariantDropdown(variants) {
    variantSelect.innerHTML = '<option value="">— Select variant —</option>';
    let hasPoVariant = false;
    variants.forEach(v => {
        const poData = window.purchaseOrderData[v.id] || null;
        if (poData) {
            hasPoVariant = true;
            const option = document.createElement('option');
            option.value = v.id;
            option.dataset.variantType = v.variant_type || '';
            option.dataset.variantValue = v.variant_value || '';
            option.dataset.variantColor = v.color || '#808080';
            option.dataset.variantSku = v.sku_suffix || '';
            option.dataset.variantImage = v.image_url || '';
            option.dataset.poQty = poData.quantity || 0;
            option.dataset.poPrice = poData.purchase_price || 0;
            option.dataset.alreadyPushed = window.pushedQuantities?.[v.id] || 0;
            option.dataset.availableStock = poData.available_stock || (poData.quantity - (window.pushedQuantities?.[v.id] || 0));
            option.text = v.variant_type + ' - ' + v.variant_value + ' (' + v.sku_suffix + ')';
            variantSelect.appendChild(option);
        }
    });
    if (!hasPoVariant) {
        const option = document.createElement('option');
        option.value = '';
        option.text = '❌ No PO variants available';
        option.disabled = true;
        variantSelect.appendChild(option);
    }
    if (variantSelect.options.length === 2) {
        variantSelect.selectedIndex = 1;
        variantSelect.dispatchEvent(new Event('change'));
    }
}

    if (productSelect && productSelect.value) {
        const event = new Event('change');
        productSelect.dispatchEvent(event);
    }

variantSelect.addEventListener('change', function() {
    const option = this.options[this.selectedIndex];
    if (!this.value || !option.value) {
        pricingSection.style.display = 'none';
        return;
    }

    activeVariantId = parseInt(this.value);
    
    let poQty = parseInt(option.dataset.poQty) || 0;
    let alreadyPushed = parseInt(option.dataset.alreadyPushed) || 0;
    let availableStock = poQty - alreadyPushed;
    let poPrice = parseFloat(option.dataset.poPrice) || 0;

    currentVariantData = {
        id: activeVariantId,
        type: option.dataset.variantType || '',
        value: option.dataset.variantValue || '',
        color: option.dataset.variantColor || '#808080',
        sku: option.dataset.variantSku || '',
        image: option.dataset.variantImage || '',
        poQty: poQty,
        poPrice: poPrice,
        alreadyPushed: alreadyPushed,
        availableStock: availableStock
    };

    document.getElementById('pageVariantValue').innerText = currentVariantData.value || '-';
    document.getElementById('pageVariantColor').style.background = currentVariantData.color;

    document.getElementById('pagePoQty').innerText = currentVariantData.poQty;
    document.getElementById('pagePoPrice').innerText = currentVariantData.poPrice;
    document.getElementById('pagePoValue').innerText = (currentVariantData.poQty * currentVariantData.poPrice).toFixed(2);

    document.getElementById('pageMasterStockNum').innerText = currentVariantData.poQty;
    document.getElementById('pageAllocated').innerText = currentVariantData.alreadyPushed;
    document.getElementById('pageRemaining').innerText = currentVariantData.availableStock;

    document.querySelectorAll('.max, #page_max_').forEach(el => {
        el.innerText = currentVariantData.availableStock;
    });

    document.querySelectorAll('#page_po_available_').forEach(el => {
        el.value = currentVariantData.availableStock;
    });
    document.querySelectorAll('#page_po_price_').forEach(el => {
        el.value = currentVariantData.poPrice;
    });

    document.querySelectorAll('.page-qty-input').forEach(input => {
        input.value = 0;
        input.max = currentVariantData.availableStock;
    });

    document.querySelectorAll('.platform-checkbox:checked').forEach(cb => {
        const platformId = cb.dataset.platformId;
        const card = document.getElementById('pagePricingCard_' + platformId);
        const row = document.getElementById('pageRow_' + platformId);
        
        if (card && currentVariantData) {
            card.style.display = 'block';
            document.getElementById('page_max_' + platformId).innerText = currentVariantData.availableStock;
            document.getElementById('page_po_available_' + platformId).value = currentVariantData.availableStock;
            document.getElementById('page_po_price_' + platformId).value = currentVariantData.poPrice;
            document.getElementById('page_qty_' + platformId).max = currentVariantData.availableStock;
        }
        if (row) row.style.display = 'table-row';
    });

    const data = variantPlatformData[activeVariantId];
    if (data && Object.keys(data).length > 0) {
        Object.entries(data).forEach(([platformId, values]) => {
            const checkbox = document.querySelector(`.platform-checkbox[data-platform-id="${platformId}"]`);
            if (checkbox) {
                checkbox.checked = true;
                const label = document.getElementById('platformLabel_' + platformId);
                if (label) label.classList.add('active');
                const card = document.getElementById('pagePricingCard_' + platformId);
                if (card) {
                    card.style.display = 'block';
                    const row = document.getElementById('pageRow_' + platformId);
                    if (row) row.style.display = 'table-row';
                    document.getElementById('page_max_' + platformId).innerText = currentVariantData.availableStock;
                    document.getElementById('page_po_available_' + platformId).value = currentVariantData.availableStock;
                    document.getElementById('page_po_price_' + platformId).value = currentVariantData.poPrice;
                    document.getElementById('page_qty_' + platformId).value = values.qty || 0;
                    document.getElementById('page_price_' + platformId).value = values.price || '';
                    document.getElementById('page_discount_value_' + platformId).value = values.discount_value || 0;
                    document.getElementById('page_discount_type_' + platformId).value = values.discount_type || 'amount';
                    calculatePageFinalTotal(platformId);
                }
            }
        });
    }

    pricingSection.style.display = 'block';
    updatePageSummary();
});

    document.querySelectorAll('.platform-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const platformId = this.dataset.platformId;
            const label = document.getElementById('platformLabel_' + platformId);
            if (label) {
                if (this.checked) label.classList.add('active');
                else label.classList.remove('active');
            }

            const card = document.getElementById('pagePricingCard_' + platformId);
            const row = document.getElementById('pageRow_' + platformId);

            if (this.checked) {
                if (card) {
                    card.style.display = 'block';
                    if (currentVariantData) {
                        document.getElementById('page_po_available_' + platformId).value = currentVariantData.availableStock;
                        document.getElementById('page_po_price_' + platformId).value = currentVariantData.poPrice;
                        document.getElementById('page_qty_' + platformId).max = currentVariantData.availableStock;
                        document.getElementById('page_qty_' + platformId).value = 0;
                        document.getElementById('page_max_' + platformId).innerText = currentVariantData.availableStock;
                    } else {
                        document.getElementById('page_max_' + platformId).innerText = '0';
                    }
                }
                if (row) row.style.display = 'table-row';
            } else {
                if (card) card.style.display = 'none';
                if (row) row.style.display = 'none';
            }

            const anyChecked = document.querySelectorAll('.platform-checkbox:checked').length > 0;
            if (pricingSection) {
                pricingSection.style.display = anyChecked ? 'block' : 'none';
            }

            if (anyChecked && currentVariantData) {
                setTimeout(updatePageSummary, 100);
            }
        });
    });

    function calculatePageFinalTotal(platformId) {
    let price = parseFloat(document.getElementById('page_price_' + platformId)?.value) || 0;
    let qty = parseFloat(document.getElementById('page_qty_' + platformId)?.value) || 0;
    let total = price * qty;

    let discountValue = parseFloat(document.getElementById('page_discount_value_' + platformId)?.value) || 0;
    let discountType = document.getElementById('page_discount_type_' + platformId)?.value;

    let discountAmount = 0;
    if (discountType === 'percent') {
        discountAmount = total * (discountValue / 100);
    } else {
        discountAmount = discountValue * qty;
    }

    if (discountAmount > total) discountAmount = total;

    let finalTotal = total - discountAmount;
    let finalPricePerUnit = qty > 0 ? finalTotal / qty : 0;

    document.getElementById('page_final_price_' + platformId).value = '₹ ' + finalPricePerUnit.toFixed(2);
    document.getElementById('page_total_selling_' + platformId).value = '₹ ' + finalTotal.toFixed(2);

    const discountTextEl = document.getElementById('page_discount_text_' + platformId);
    if (discountTextEl && discountValue > 0 && qty > 0 && price > 0) {
        let offPerUnit = price - finalPricePerUnit;
        discountTextEl.innerText = '₹' + offPerUnit.toFixed(2) + ' off per unit';
    } else if (discountTextEl) {
        discountTextEl.innerText = '';
    }

    updatePageSummary();
}

function updatePageSummary() {
    let currentAllocated = 0;
    let totalRevenue = 0;
    let totalItems = 0;
    
    let poQty = currentVariantData?.poQty || 0;
    let alreadyPushed = currentVariantData?.alreadyPushed || 0;
    let availableStock = poQty - alreadyPushed;
    let poPrice = currentVariantData?.poPrice || 0;

    document.querySelectorAll('.page-qty-input').forEach(input => {
        let qty = parseInt(input.value) || 0;
        currentAllocated += qty;
        const platformId = input.id.split('_').pop();
        const totalEl = document.getElementById('page_total_selling_' + platformId);
        if (totalEl) {
            let rev = parseFloat(totalEl.value.replace(/[₹,£$]/g, '')) || 0;
            totalRevenue += rev;
            totalItems += qty;
        }
    });

    let totalAllocated = alreadyPushed + currentAllocated;
    let remaining = availableStock - currentAllocated;
    if (remaining < 0) remaining = 0;

    let totalPurchaseValue = totalAllocated * poPrice;
    let expectedProfit = totalRevenue - totalPurchaseValue;
    let avgSellingPrice = totalItems > 0 ? totalRevenue / totalItems : 0;
    let expectedMargin = totalRevenue > 0 ? (expectedProfit / totalRevenue) * 100 : 0;

    const setText = (id, value) => {
        const el = document.getElementById(id);
        if (el) el.innerText = value;
    };
    const setHTML = (id, value) => {
        const el = document.getElementById(id);
        if (el) el.innerHTML = value;
    };
    const setStyle = (id, prop, value) => {
        const el = document.getElementById(id);
        if (el) el.style[prop] = value;
    };

    setText('pageMasterStockNum', poQty);
    setText('pageAllocated', totalAllocated);
    setText('pageRemaining', remaining);

    setText('pageSummaryMaster', poQty + ' Units');
    setText('pageSummaryAllocated', totalAllocated + ' Units');
    setText('pageSummaryRemaining', remaining + ' Units');
    setText('pageSummaryMasterSmall', poQty);
    setText('pageSummaryAllocatedSmall', totalAllocated);
    setText('pageSummaryRemainingSmall', remaining);

    const percent = availableStock > 0 ? (currentAllocated / availableStock * 100) : 0;
    setStyle('pageSummaryProgress', 'width', Math.min(percent, 100) + '%');
    setStyle('pageMasterProgress', 'width', Math.min(percent, 100) + '%');

    setHTML('pageSummaryPurchase', '₹' + totalPurchaseValue.toFixed(2));
    setHTML('pageSummaryRevenue', '₹' + totalRevenue.toFixed(2));
    setHTML('pageSummaryProfit', '₹' + expectedProfit.toFixed(2));
    setHTML('pageSummaryAvgPrice', '₹' + avgSellingPrice.toFixed(2) + ' / Unit');
    setHTML('pageSummaryMargin', expectedMargin.toFixed(1) + '%');

    setText('pageFooterMaster', poQty);
    setText('pageFooterAllocated', totalAllocated);
    setText('pageFooterRemaining', remaining);

    const statusEl = document.getElementById('pageSummaryStatus');
    const statusEl2 = document.getElementById('pageStockStatus');
    if (currentAllocated <= availableStock) {
        if (statusEl) { statusEl.innerHTML = '✅ Stock allocation is valid.'; statusEl.className = 'status-valid'; }
        if (statusEl2) { statusEl2.innerHTML = '✅ Stock allocation is valid.'; statusEl2.className = 'footer-status-text success'; }
    } else {
        if (statusEl) { statusEl.innerHTML = '⚠️ Stock allocation exceeds available stock!'; statusEl.className = 'status-valid error'; }
        if (statusEl2) { statusEl2.innerHTML = '⚠️ Stock allocation exceeds available stock!'; statusEl2.className = 'footer-status-text error'; }
    }
}

    document.addEventListener('input', function(e) {
        if (e.target.id && e.target.id.startsWith('page_qty_')) {
            const platformId = e.target.id.split('_').pop();
            let poQty = currentVariantData?.poQty || 0;
            let alreadyPushed = currentVariantData?.alreadyPushed || 0;
            let availableStock = poQty - alreadyPushed;
            const qty = parseInt(e.target.value) || 0;

            if (qty > availableStock) {
                e.target.style.borderColor = '#E74C3C';
                e.target.value = availableStock;
                showToast('⚠️ Quantity exceeds available stock: ' + availableStock, 'error');
                updatePageSummary();
            } else {
                e.target.style.borderColor = '';
                calculatePageFinalTotal(platformId);
            }
        }

        if (e.target.id && (e.target.id.startsWith('page_price_') || e.target.id.startsWith('page_discount_value_'))) {
            const platformId = e.target.id.split('_').pop();
            calculatePageFinalTotal(platformId);
        }
    });

    document.addEventListener('change', function(e) {
        if (e.target.id && e.target.id.startsWith('page_discount_type_')) {
            const platformId = e.target.id.split('_').pop();
            calculatePageFinalTotal(platformId);
        }
    });

    document.getElementById('savePageVariant')?.addEventListener('click', function() {
        if (!activeVariantId) {
            showToast('⚠ No variant selected', 'error');
            return;
        }

        variantPlatformData[activeVariantId] = {};

        let totalQty = 0;
        document.querySelectorAll('.platform-checkbox:checked').forEach(cb => {
            const platformId = cb.dataset.platformId;
            const qty = parseInt(document.getElementById('page_qty_' + platformId)?.value) || 0;
            totalQty += qty;

            variantPlatformData[activeVariantId][platformId] = {
                price: parseFloat(document.getElementById('page_price_' + platformId)?.value) || 0,
                qty: qty,
                discount_value: parseFloat(document.getElementById('page_discount_value_' + platformId)?.value) || 0,
                discount_type: document.getElementById('page_discount_type_' + platformId)?.value || 'amount',
                final_total: parseFloat(document.getElementById('page_total_selling_' + platformId)?.value?.replace(/[₹,£$]/g, '')) || 0,
                variant_type: currentVariantData?.type || '',
                variant_value: currentVariantData?.value || '',
                variant_sku: currentVariantData?.sku || '',
                color: currentVariantData?.color || ''
            };
        });

        let poQty = currentVariantData?.poQty || 0;
        let alreadyPushed = currentVariantData?.alreadyPushed || 0;
        let availableStock = poQty - alreadyPushed;

        if (totalQty > availableStock) {
            showToast('❌ Quantity exceeds available stock (' + availableStock + ')', 'error');
            return;
        }

        localStorage.setItem('variantPlatformData', JSON.stringify({
            _productId: document.getElementById('selectedProductId').value,
            data: variantPlatformData
        }));

        showToast('✅ Variant configured successfully!', 'success');
    });

    document.getElementById('cancelConfigure')?.addEventListener('click', function() {
        configureSection.style.display = 'none';
        productSelectSection.style.display = 'block';
        productSelect.value = '';
        document.getElementById('selectedProductName').innerText = '—';
    });

    previewBtn?.addEventListener('click', function() {
        previewBody.innerHTML = '';
        let hasData = false;

        Object.keys(variantPlatformData).forEach(variantId => {
            const platforms = variantPlatformData[variantId] || {};
            Object.keys(platforms).forEach(platformId => {
                const p = platforms[platformId];
                let qty = parseInt(p.qty) || 0;
                if (!qty) return;
                hasData = true;

                let discountText = p.discount_type === 'percent' ? p.discount_value + '%' : '₹' + p.discount_value;

                previewBody.insertAdjacentHTML('beforeend', `
                    <tr>
                        <td style="font-weight:600; font-size:12px; color:#111827;">${window.platformMap?.[platformId] || platformId}</td>
                        <td style="font-size:12px; color:#6B7280;">${p.variant_type || '-'}</td>
                        <td style="font-size:12px; color:#6B7280;">${p.variant_value || '-'}</td>
                        <td style="font-size:12px; color:#6B7280;">${p.color || '-'}</td>
                        <td style="font-size:12px; color:#6B7280;">${p.variant_sku || '-'}</td>
                        <td style="font-weight:600; font-size:13px; color:#14B86A;">${qty}</td>
                        <td style="font-size:12px; color:#111827;">₹${p.price}</td>
                        <td style="font-size:12px; color:#E74C3C;">${discountText}</td>
                        <td style="font-weight:700; font-size:13px; color:#A3006B;">₹${p.final_total.toFixed(2)}</td>
                    </tr>
                `);
            });
        });

        if (!hasData) {
            showToast('⚠ No platform data configured!', 'error');
            return;
        }

        const previewModal = new bootstrap.Modal(document.getElementById('pushPreviewModal'));
        previewModal.show();
    });

    confirmBtn?.addEventListener('click', function() {
        const input = document.getElementById('variantPlatformDataInput');
        if (input) {
            input.value = JSON.stringify(variantPlatformData);
        }
        form.submit();
    });

    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = 'toast-ref ' + type;
        toast.innerText = message;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.4s ease';
            setTimeout(() => toast.remove(), 400);
        }, 3000);
    }

});
</script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection