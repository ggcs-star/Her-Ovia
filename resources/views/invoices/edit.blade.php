@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-3" style="background: #f8fafc; min-height: 100vh; font-family: 'Inter', 'SF Pro Display', -apple-system, sans-serif;">

{{-- ================ HEADER ================ --}}
<div class="d-flex justify-content-between align-items-center mb-5">
    <div class="d-flex align-items-center gap-3">
        <div class="position-relative">
            <div style="width: 48px; height: 48px; background: var(--primary-light); border-radius: 18px; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 16px -4px rgba(139,36,82,0.3);">
                <i class="fas fa-edit text-white" style="font-size: 1.4rem;"></i>
            </div>
            <span style="position: absolute; top: -4px; right: -4px; width: 14px; height: 14px; background: #f59e0b; border: 2px solid white; border-radius: 50%;"></span>
        </div>
        <div>
            <h1 style="font-size: 1.8rem; font-weight: 700; color: #0a1e2f; letter-spacing: -0.03em; margin-bottom: 0;">Edit Invoice</h1>
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="background: #e8f0fe; padding: 4px 10px; border-radius: 30px; font-size: 0.7rem; color: var(--primary-light); font-weight: 600; letter-spacing: 0.5px;">
                    <i class="fas fa-pencil-alt me-1"></i> Update Mode
                </span>
                <span style="background: #fff4e5; padding: 4px 10px; border-radius: 30px; font-size: 0.7rem; color: #b85e00; font-weight: 600;">
                    <i class="fas fa-hashtag me-1"></i> Invoice #{{ $invoice->id }}
                </span>
            </div>
        </div>
    </div>
    <a href="{{ admin_route('invoices.index') }}" style="background: white; padding: 12px 28px; border-radius: 60px; color: #1e3a5f; font-weight: 600; font-size: 0.9rem; text-decoration: none; display: flex; align-items: center; gap: 8px; border: 1.5px solid #e2ecf5; transition: all 0.2s; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
        <i class="fas fa-arrow-left" style="color: var(--primary-light);"></i> Back to Invoices
    </a>
</div>

<form method="POST" action="{{ admin_route('invoices.update', $invoice->id) }}" id="invoiceForm">
    @csrf
    @method('PUT')

<div class="row g-4">

{{-- ================ LEFT COLUMN - MAIN CONTENT ================ --}}
<div class="col-lg-7 col-xl-8">

    {{-- ================ CUSTOMER PROFILE CARD ================ --}}
    <div style="background: white; border-radius: 32px; padding: 28px; margin-bottom: 24px; box-shadow: 0 20px 35px -12px rgba(0,20,40,0.08); border: 1px solid rgba(255,255,255,0.6);">
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
            <div style="width: 6px; height: 32px; background: var(--primary-light); border-radius: 10px;"></div>
            <h3 style="font-size: 1.2rem; font-weight: 700; color: #0a1e2f; letter-spacing: -0.01em; margin-bottom: 0;">
                <i class="fas fa-user-circle me-2" style="color: var(--primary-light);"></i> Customer Details
            </h3>
            <span style="background: #e9f2fa; padding: 6px 16px; border-radius: 40px; font-size: 0.7rem; font-weight: 600; color: var(--primary-light); margin-left: auto;">
                <i class="fas fa-check-circle me-1"></i> Verified
            </span>
        </div>
        
        <div class="row g-3">
            <div class="col-md-7">
                <label style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #547087; margin-bottom: 6px; display: block;">
                    Select Customer <span style="color: #dc2626;">*</span>
                </label>
                <div style="display: flex; gap: 8px;">
                    <select name="customer_id" id="customerSelect" style="flex: 1; background: #fafcff; border: 1.8px solid #e9f0f5; border-radius: 20px; padding: 12px 20px; font-size: 0.95rem; color: #0a1e2f; font-weight: 500; outline: none; transition: all 0.2s; box-shadow: inset 0 2px 6px rgba(0,0,0,0.01);">
                        <option value="">🚶 Walking Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}"
                                    data-mobile="{{ $customer->mobile }}"
                                    data-address="{{ $customer->address_line_1 }}"
                                    @selected($invoice->customer_id == $customer->id)>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="button" id="openCustomerModal" style="background: var(--primary-light); border: none; border-radius: 20px; padding: 0 20px; color: white; font-size: 1.2rem; display: flex; align-items: center; justify-content: center; transition: all 0.2s; box-shadow: 0 6px 14px -6px var(--primary-light);">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-5">
                <label style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #547087; margin-bottom: 6px; display: block;">
                    Mobile Number
                </label>
                <div style="background: #f5faff; border: 1.8px solid #e9f0f5; border-radius: 20px; padding: 12px 20px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-phone-alt" style="color: #7c9eb2;"></i>
                    <input type="text" id="customerMobile" readonly value="{{ $invoice->customer_mobile }}" style="background: transparent; border: none; outline: none; width: 100%; font-size: 0.95rem; color: #1e3a5f; font-weight: 500;" placeholder="Not selected">
                </div>
            </div>
            <div class="col-12">
                <label style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #547087; margin-bottom: 6px; display: block;">
                    Delivery Address
                </label>
                <div style="background: #f5faff; border: 1.8px solid #e9f0f5; border-radius: 20px; padding: 12px 20px; display: flex; align-items: start; gap: 8px;">
                    <i class="fas fa-map-marker-alt" style="color: #7c9eb2; margin-top: 3px;"></i>
                    <textarea id="customerAddress" name="customer_address" rows="1" style="background: transparent; border: none; outline: none; width: 100%; font-size: 0.95rem; color: #1e3a5f; resize: none; font-weight: 400;">{{ $invoice->customer_address }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- ================ ITEMS SECTION ================ --}}
    <div style="background: white; border-radius: 32px; overflow: hidden; box-shadow: 0 20px 35px -12px rgba(0,20,40,0.08); border: 1px solid rgba(255,255,255,0.6);">
        
        <div style="padding: 24px 28px 16px; background: white; border-bottom: 1px solid #f0f6fa;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="width: 44px; height: 44px; background: #eef7ff; border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-boxes" style="color: var(--primary-light); font-size: 1.2rem;"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 1.2rem; font-weight: 700; color: #0a1e2f; letter-spacing: -0.01em; margin-bottom: 4px;">Order Items</h3>
                        <span style="color: #547087; font-size: 0.8rem; display: flex; align-items: center; gap: 4px;">
                            <i class="fas fa-bolt" style="color: #f59e0b;"></i> Real-time calculation
                        </span>
                    </div>
                </div>
                <div style="background: #f5faff; padding: 8px 16px; border-radius: 40px; border: 1px solid #e2ecf5; display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-calculator" style="color: var(--primary-light);"></i>
                    <span style="font-weight: 600; color: #1e3a5f; font-size: 0.8rem;">Auto-calc</span>
                </div>
            </div>
        </div>

        <div style="padding: 16px 24px 24px;">
            <div style="background: #fafdff; border-radius: 24px; border: 1px solid #ecf3f8;">
                <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
                    <thead>
                        <tr style="background: white; border-bottom: 2px solid #e2eef5;">
                            <th style="width: 22%; padding: 16px 12px 16px 20px; text-align: left; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #3e6579;">Product</th>
                            <th style="width: 18%; padding: 16px 12px; text-align: left; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #3e6579;">Variant</th>
                            <th style="width: 10%; padding: 16px 12px; text-align: center; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #3e6579;">Qty</th>
                            <th style="width: 14%; padding: 16px 12px; text-align: right; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #3e6579;">Price</th>
                            <th style="width: 20%; padding: 16px 12px; text-align: right; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #3e6579;">Discount</th>
                            <th style="width: 12%; padding: 16px 12px; text-align: right; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #3e6579;">Total</th>
                            <th style="width: 10%; padding: 16px 20px 16px 12px; text-align: center; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #3e6579;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="invoiceItems">
                        @foreach($invoice->items as $i => $item)
                        <tr>
                            <td style="padding-left: 20px; padding-right: 8px;">
                                <select name="items[{{ $i }}][product_id]" 
                                        class="product-select" 
                                        style="width: 100%; background: #f9fcff; border: 1.8px solid #e9f0f5; border-radius: 16px; padding: 10px 12px; font-size: 0.9rem; color: #0a1e2f; outline: none;">
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" @selected($item->product_id == $product->id)>
                                            {{ $product->name }} ({{ $product->sku }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            
                            <td>
                                <select name="items[{{ $i }}][variant_id]" 
                                        class="variant-select" 
                                        style="width: 100%; background: #f9fcff; border: 1.8px solid #e9f0f5; border-radius: 16px; padding: 10px 12px; font-size: 0.9rem; color: #0a1e2f;">
                                    <option value="{{ $item->product_variant_id }}" 
                                            data-price="{{ $item->price }}" 
                                            selected>
                                        {{ $item->variant_name }}
                                    </option>
                                </select>
                            </td>
                            
                            <td>
                                <input type="number" 
                                    name="items[{{ $i }}][qty]" 
                                    class="qty text-center" 
                                    value="{{ $item->quantity }}" 
                                    style="width: 70px; margin: 0 auto; background: white; border: 1.8px solid #e9f0f5; border-radius: 16px; padding: 10px 4px; font-size: 0.9rem; font-weight: 600; color: #0a1e2f; text-align: center;">
                                <div class="small mt-1 text-muted stock-info" style="display:none;">
                                    Available: <strong class="available-stock">0</strong> | 
                                    Remaining: <strong class="remaining-stock">0</strong>
                                </div>
                                <div id="stockError_{{ $i }}" class="text-danger small" style="display:none; margin-top:2px;">
                                    ⚠️ Quantity exceeds available stock!
                                </div>
                            </td>
                            
                            <td>
                                <input type="number" 
                                       name="items[{{ $i }}][price]" 
                                       class="price fw-semibold" 
                                       readonly 
                                       value="{{ $item->price }}" 
                                       style="width: 110px; background: #f5faff; border: 1.8px solid #e9f0f5; border-radius: 16px; padding: 10px 12px; font-size: 0.9rem; color: #0a1e2f;">
                            </td>
                            
                            <td>
                                <div style="display: flex; gap: 0;">
                                    <input type="number" 
                                           name="items[{{ $i }}][discount]" 
                                           class="discount" 
                                           value="{{ $item->discount ?? 0 }}" 
                                           min="0" step="0.01"
                                           placeholder="0.00"
                                           style="width: 70px; background: white; border: 1.8px solid #e9f0f5; border-right: none; border-radius: 16px 0 0 16px; padding: 10px 8px; font-size: 0.9rem;">
                                    <select name="items[{{ $i }}][discount_type]"
                                            class="discount-type"
                                            style="width: 45px; background: white; border: 1.8px solid #e9f0f5; border-left: none; border-radius: 0 16px 16px 0; padding: 10px 2px; font-size: 0.9rem; color: #0a1e2f; outline: none;">
                                        <option value="percent" @selected(($item->discount_type ?? 'percent') == 'percent')>%</option>
                                        <option value="flat" @selected(($item->discount_type ?? '') == 'flat')>₹</option>
                                    </select>
                                </div>
                            </td>
                            
                            <td>
                                <input type="number" 
                                       class="total fw-bold" 
                                       readonly 
                                       value="{{ $item->total }}" 
                                       style="width: 110px; background: #f0f7ff; border: 1.8px solid #e9f0f5; border-radius: 16px; padding: 10px 12px; font-size: 0.95rem; font-weight: 700; color: #0a1e2f;">
                            </td>
                            
                            <td style="text-align: center;">
                                <button type="button" 
                                        class="removeRow" 
                                        style="background: rgba(220,53,69,0.08); border: none; border-radius: 40px; padding: 10px 12px; color: #dc3545; transition: all 0.2s; cursor: pointer;">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    
                    <tfoot>
                        <tr style="background: #f8fcff; border-top: 2px solid #e2eef5;">
                            <td colspan="2" style="padding: 16px 20px;">
                                <span style="font-weight: 700; color: #0a1e2f; font-size: 0.95rem;">Total Amount</span>
                            </td>
                            <td style="padding: 16px 12px; text-align: center;">
                                <input type="text" id="totalQty" readonly value="{{ $invoice->items->sum('quantity') }}" style="width: 70px; background: white; border: 1.8px solid #e2ecf5; border-radius: 16px; padding: 8px; text-align: center; font-weight: 700; color: #0a1e2f; font-size: 0.9rem;">
                            </td>
                            <td style="padding: 16px 12px; text-align: right;">
                                <input type="text" id="totalPrice" readonly value="{{ number_format($invoice->items->sum('total'), 2) }}" style="width: 110px; background: white; border: 1.8px solid #e2ecf5; border-radius: 16px; padding: 8px 12px; text-align: right; font-weight: 700; color: #0a1e2f; font-size: 0.95rem;">
                            </td>
                            <td colspan="3" style="padding: 16px 20px;"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div style="padding: 8px 28px 28px;">
            <button type="button" id="addRow" style="background: linear-gradient(95deg, #1a1e2b, #2a2f3f); border: none; border-radius: 60px; padding: 14px 32px; color: white; font-weight: 600; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 10px; box-shadow: 0 12px 24px -8px rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1);">
                <i class="fas fa-plus-circle"></i> Add New Item
            </button>
        </div>
    </div>
</div>

{{-- ================ RIGHT COLUMN - PAYMENT & SUMMARY ================ --}}
<div class="col-lg-5 col-xl-4">

    <div style="background: white; border-radius: 32px; padding: 28px; margin-bottom: 24px; box-shadow: 0 20px 35px -12px rgba(0,20,40,0.08); border: 1px solid rgba(255,255,255,0.6);">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
            <div style="width: 48px; height: 48px; background: linear-gradient(145deg, #fff9e6, #fff3d4); border-radius: 18px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-credit-card" style="color: #b85e00; font-size: 1.2rem;"></i>
            </div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: #0a1e2f; margin-bottom: 0;">Payment</h3>
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #547087; margin-bottom: 8px; display: block;">
                Payment Method
            </label>
            <div style="position: relative;">
                <select name="payment_type" style="width: 100%; background: white; border: 1.8px solid #e9f0f5; border-radius: 20px; padding: 14px 20px; font-size: 0.95rem; color: #0a1e2f; font-weight: 500; appearance: none; outline: none;">
                    <option value="cash" @selected($invoice->payment_type == 'cash')>💰 Cash</option>
                    <option value="bank" @selected($invoice->payment_type == 'bank')>🏦 Bank Transfer</option>
                </select>
                <i class="fas fa-chevron-down" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); color: #7c9eb2;"></i>
            </div>
        </div>
        
        <div>
            <label style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #547087; margin-bottom: 8px; display: block;">
                Amount Received
            </label>
            <div style="display: flex; align-items: center; background: white; border: 1.8px solid #e9f0f5; border-radius: 20px; overflow: hidden;">
                <span style="background: #f5faff; padding: 14px 16px; color: var(--primary-light); font-weight: 700; border-right: 1.8px solid #e9f0f5;">₹</span>
                <input type="number" name="paid_amount" value="{{ (int) $invoice->paid_amount }}" placeholder="0" min="0" step="1" oninput="this.value = this.value.replace(/\..*$/, '')" style="flex: 1; border: none; padding: 14px 20px; font-size: 0.95rem; font-weight: 500; color: #0a1e2f; outline: none;">            </div>
        </div>
    </div>

    {{-- ================ INVOICE SUMMARY ================ --}}
    <div style="background: white; border-radius: 32px; padding: 28px; box-shadow: 0 20px 35px -12px rgba(0,20,40,0.08); border: 1px solid rgba(255,255,255,0.6);">
        
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 44px; height: 44px; background: #eef7ff; border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-receipt" style="color: var(--primary-light);"></i>
                </div>
                <div>
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: #0a1e2f; margin-bottom: 4px;">Invoice Summary</h3>
                    <span style="color: #547087; font-size: 0.7rem; display: flex; align-items: center; gap: 4px;">
                        <i class="fas fa-pencil-alt" style="color: #f59e0b;"></i> Edit Mode
                    </span>
                </div>
            </div>
            <span style="background: #fff4e5; padding: 6px 16px; border-radius: 40px; font-size: 0.7rem; font-weight: 600; color: #b85e00; border: 1px solid #ffebc8;">
                <i class="fas-regular fa-file me-1"></i> #INV-{{ $invoice->id }}
            </span>
        </div>

        <div style="background: linear-gradient(145deg, #f8fcff, #f0f7ff); border-radius: 24px; padding: 24px; margin-bottom: 24px; border: 1px solid rgba(139,36,82,0.1); box-shadow: inset 0 1px 4px rgba(255,255,255,0.8);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span style="color: #547087; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; display: block; margin-bottom: 4px;">Grand Total</span>
                    <span style="color: #3a6579; font-size: 0.8rem;">Invoice Amount</span>
                </div>
                <span style="font-size: 2.4rem; font-weight: 700; color: #0a2647; letter-spacing: -0.03em; line-height: 1;" id="grandTotal">{{ number_format($invoice->grand_total, 2) }}</span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 24px; background: #fafdff; border-radius: 20px; padding: 14px 16px;">
            
            <div style="display: flex; flex-direction: column; gap: 2px;">
                <span style="color: #3a6579; display: flex; align-items: center; gap: 6px; font-size: 0.75rem;">
                    <span style="width: 24px; height: 24px; background: #e8f3ed; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-percent" style="color: #0f7b4b; font-size: 0.6rem;"></i>
                    </span>
                    Discount
                </span>
                <span style="font-weight: 700; color: #0f7b4b; font-size: 0.95rem; margin-left: 30px;" id="totalDiscountLabel">{{ number_format($invoice->items->sum('discount'), 2) }}</span>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 2px;">
                <span style="color: #3a6579; display: flex; align-items: center; gap: 6px; font-size: 0.75rem;">
                    <span style="width: 24px; height: 24px; background: #f0ebff; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-calculator" style="color: #6b4b9e; font-size: 0.6rem;"></i>
                    </span>
                    Tax (GST)
                </span>
                <span style="font-weight: 700; color: #6b4b9e; font-size: 0.95rem; margin-left: 30px;" id="totalTaxLabel">0.00</span>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 2px;">
                <span style="color: #3a6579; display: flex; align-items: center; gap: 6px; font-size: 0.75rem;">
                    <span style="width: 24px; height: 24px; background: #e8f0fe; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-credit-card" style="color: var(--primary-light); font-size: 0.6rem;"></i>
                    </span>
                    Paid
                </span>
                <span style="font-weight: 700; color: #0f7b4b; font-size: 0.95rem; margin-left: 30px;" id="paidAmountDisplay">{{ number_format($invoice->paid_amount, 2) }}</span>
            </div>
        </div>

        <div style="background: var(--primary-light); border-radius: 24px; padding: 20px 24px; margin-bottom: 24px; box-shadow: 0 12px 24px -8px var(--primary-light);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span style="color: rgba(255,255,255,0.9); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; display: block; margin-bottom: 4px;">NET PAYABLE</span>
                    <span style="color: rgba(255,255,255,0.8); font-size: 0.75rem;">Final Amount</span>
                </div>
                <span style="font-size: 2rem; font-weight: 700; color: white; letter-spacing: -0.02em;" id="netTotal">{{ number_format($invoice->grand_total, 2) }}</span>
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-bottom: 24px;">
            <div style="flex: 1; background: #fff8e8; border-radius: 18px; padding: 12px 14px; border: 1px solid #ffebc8;">
                <span style="color: #b85e00; font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; display: block; margin-bottom: 4px;">Due</span>
                <span style="font-weight: 800; color: #b85e00; font-size: 1.1rem;" id="dueAmount">{{ number_format($invoice->due_amount, 2) }}</span>
            </div>
            <div style="flex: 1; background: #e8f0fe; border-radius: 18px; padding: 12px 14px; border: 1px solid #d4e2f0;">
                <span style="color: var(--primary-light); font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; display: block; margin-bottom: 4px;">Change</span>
                <span style="font-weight: 800; color: var(--primary-light); font-size: 1.1rem;" id="changeAmount">0.00</span>
            </div>
        </div>

        <div style="display: flex; gap: 12px;">
            <button type="button" style="flex: 1; background: white; border: 1.8px solid var(--primary-light); border-radius: 60px; padding: 14px; color: var(--primary-light); font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s;">
                <i class="fas fa-bolt"></i> Quick Pay
            </button>
            <button type="submit" style="flex: 1; background: var(--primary-light); border: none; border-radius: 60px; padding: 14px; color: white; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 8px 18px -6px var(--primary-light);">
                <i class="fas fa-check-circle"></i> Update Invoice
            </button>
        </div>
    </div>

    <div style="margin-top: 24px; padding: 20px 8px 8px; display: flex; justify-content: space-between; border-top: 1.5px solid #e2ecf5; color: #6b8a9c; font-size: 0.7rem;">
        <span><i class="fas fa-copyright me-1"></i> 2026 Her Ovia</span>
        <span>v3.0 · EN</span>
    </div>
</div>

</div>
</form>
</div>

{{-- ================ ADD CUSTOMER MODAL ================ --}}
<div class="modal fade" id="customerModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
<div class="modal-dialog modal-dialog-centered modal-md">
<div style="background: white; border-radius: 36px; overflow: hidden; box-shadow: 0 40px 60px -20px rgba(0,0,0,0.2); border: 2px solid white;">

    <div style="padding: 28px 28px 8px; display: flex; align-items: center; justify-content: space-between; background: white; border-bottom: 1px solid #f0f6fa;">
        <div style="display: flex; align-items: center; gap: 14px;">
            <div style="width: 48px; height: 48px; background: #eef7ff; border-radius: 18px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-user-plus" style="color: var(--primary-light); font-size: 1.2rem;"></i>
            </div>
            <div>
                <h3 style="font-size: 1.2rem; font-weight: 700; color: #0a1e2f; margin-bottom: 4px;">Add New Customer</h3>
                <span style="color: #547087; font-size: 0.7rem;">Enter customer details below</span>
            </div>
        </div>
        <button type="button" data-bs-dismiss="modal" style="background: #f1f7fd; border: none; width: 42px; height: 42px; border-radius: 14px; display: flex; align-items: center; justify-content: center; color: #4e6a7c; cursor: pointer;">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div style="padding: 28px; background: white;">
        <div style="margin-bottom: 24px;">
            <label style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #547087; margin-bottom: 8px; display: block;">
                Full Name <span style="color: #dc2626;">*</span>
            </label>
            <input id="newCustomerName" type="text" placeholder="Enter customer full name" style="width: 100%; background: white; border: 2px solid #e9f0f5; border-radius: 20px; padding: 16px 22px; font-size: 1rem; outline: none;" onfocus="this.style.borderColor='var(--primary-light)'" onblur="this.style.borderColor='#e9f0f5'">
        </div>
        
        <div style="margin-bottom: 24px;">
            <label style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #547087; margin-bottom: 8px; display: block;">
                Mobile Number <span style="color: #dc2626;">*</span>
            </label>
            <div style="display: flex; align-items: center; background: white; border: 2px solid #e9f0f5; border-radius: 20px; overflow: hidden;">
                <span style="background: #f5faff; padding: 16px 18px; color: var(--primary-light); font-weight: 700; border-right: 2px solid #e9f0f5;">+91</span>
                <input id="newCustomerMobile" type="tel" placeholder="9876543210" oninput="this.value=this.value.replace(/[^0-9]/g,'')" maxlength="10" style="flex: 1; border: none; padding: 16px 22px; font-size: 1rem; outline: none;">
            </div>
        </div>
        
        <div>
            <label style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #547087; margin-bottom: 8px; display: block;">
                Complete Address
            </label>
            <textarea id="newCustomerAddress" placeholder="House / Shop No., Street, Landmark, City, Pincode" rows="3" style="width: 100%; background: white; border: 2px solid #e9f0f5; border-radius: 20px; padding: 16px 22px; font-size: 1rem; outline: none; resize: none;" onfocus="this.style.borderColor='var(--primary-light)'" onblur="this.style.borderColor='#e9f0f5'"></textarea>
        </div>
        
        <div style="margin-top: 16px; padding: 12px 16px; background: #f8fcff; border-radius: 16px; border: 1px solid #e2ecf5;">
            <span style="color: #547087; font-size: 0.75rem; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-info-circle" style="color: var(--primary-light);"></i> Customer will be saved and auto-selected in invoice
            </span>
        </div>
    </div>

    <div style="padding: 16px 28px 28px; display: flex; gap: 16px; justify-content: flex-end; background: white; border-top: 1px solid #f0f6fa;">
        <button type="button" data-bs-dismiss="modal" style="background: white; border: 2px solid #e2ecf5; border-radius: 60px; padding: 14px 32px; font-weight: 700; color: #4e6a7c; font-size: 0.95rem; cursor: pointer;">
            Cancel
        </button>
        <button type="button" id="saveCustomer" style="background: var(--primary-light); border: none; border-radius: 60px; padding: 14px 36px; color: white; font-weight: 700; font-size: 0.95rem; display: flex; align-items: center; gap: 10px; box-shadow: 0 10px 20px -6px var(--primary-light); cursor: pointer;">
            <i class="fas fa-check-circle"></i> Save Customer
        </button>
    </div>

</div>
</div>
</div>

<select id="productOptionsTemplate" class="d-none">
    <option value="">Select Product</option>
    @foreach($products as $product)
        <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
    @endforeach
</select>
<script>
    window.poData = @json($poData ?? []);
    window.pushedQuantities = @json($pushedQuantities ?? []);
    window.offlinePricing = @json($offlinePricing ?? []);
</script>
<style>
.modal-backdrop { z-index: 1040 !important; }
.modal { z-index: 1050 !important; }
#customerModal input, #customerModal textarea, #customerModal button { 
    position: relative; 
    z-index: 1060; 
}
.modal-dialog { pointer-events: auto; }
</style>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/admin/invoice.js') }}"></script>
@endpush