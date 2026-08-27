<style>
.color-cell {
    position: relative;
}
.variant-color {
    position: relative;
    z-index: 1;
}
.color-blocker {
    position: absolute;
    inset: 0;
    z-index: 9999;
    background: transparent;
    cursor: not-allowed;
    display: none;
    pointer-events: all;
}
.badge {
    display: inline-block;
    padding: 4px 10px;
    font-size: 11px;
    font-weight: 500;
    border-radius: 20px;
}
.bg-info {
    background-color: #8B2452;
    color: white;
}
.bg-secondary {
    background-color: #6c757d;
    color: white;
}
.btn-sm {
    padding: 4px 10px;
    font-size: 12px;
}
.btn-outline-primary {
    border-color: #8B2452;
    color: #8B2452;
}
.btn-outline-primary:hover {
    background-color: #8B2452;
    border-color: #8B2452;
    color: white;
}
.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
}
.table thead th {
    background-color: #f8f9fa;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #4a5568;
}
.form-control:focus {
    border-color: #8B2452;
    box-shadow: 0 0 0 2px rgba(139,36,82,0.1);
}
</style>

<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-5">
    <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-[#8B2452]/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-800">Product Variants</h3>
            <button type="button" id="addVariantBtn" class="ml-auto px-3 py-1.5 text-sm font-medium rounded-lg border border-[#8B2452] text-[#8B2452] hover:bg-[#8B2452] hover:text-white transition-all">
                + Add Variant
            </button>
        </div>
    </div>

    <div class="p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="variantTable">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr class="text-center">
                        <th class="px-3 py-3 text-xs font-semibold text-gray-600">Type</th>
                        <th class="px-3 py-3 text-xs font-semibold text-gray-600">Value</th>
                        <th class="px-3 py-3 text-xs font-semibold text-gray-600">Color</th>
                        <th class="px-3 py-3 text-xs font-semibold text-gray-600">SKU</th>
                        <th class="px-3 py-3 text-xs font-semibold text-gray-600">Height</th>
                        <th class="px-3 py-3 text-xs font-semibold text-gray-600">Width</th>
                        <th class="px-3 py-3 text-xs font-semibold text-gray-600">Image</th>
                        <th class="px-3 py-3 text-xs font-semibold text-gray-600 w-16">Sort</th>
                        <th class="px-3 py-3 text-xs font-semibold text-gray-600 w-24">Status</th>
                        <th class="px-3 py-3 text-xs font-semibold text-gray-600 w-20">Qty</th>
                        <th class="px-3 py-3 text-xs font-semibold text-gray-600">Purchase</th>
                        <th class="px-3 py-3 text-xs font-semibold text-gray-600">Selling</th>
                        <th class="px-3 py-3 text-xs font-semibold text-gray-600">Total</th>
                        <th class="px-3 py-3 text-xs font-semibold text-gray-600 w-10"></th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($product) && $product->variants->count())
                        @foreach($product->variants as $i => $variant)
                            <tr class="variant-row border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-2 py-2">
                                    <select name="variants[{{ $i }}][variant_id]" class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-sm variant-type focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452]/20">
                                        <option value="">Select</option>
                                        @foreach($variants as $masterVariant)
                                            <option value="{{ $masterVariant->id }}" data-input-type="{{ $masterVariant->input_type }}" data-has-dimensions="{{ $masterVariant->has_dimensions }}" {{ $variant->variant_id == $masterVariant->id ? 'selected' : '' }}>
                                                {{ $masterVariant->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-2 py-2">
                                    <select name="variants[{{ $i }}][variant_value_id]" class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-sm variant-value focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452]/20">
                                        <option value="">Select value</option>
                                        @foreach($variants as $masterVariant)
                                            @if($masterVariant->id == $variant->variant_id)
                                                @foreach($masterVariant->values as $val)
                                                    <option value="{{ $val->id }}" {{ $variant->variant_value_id == $val->id ? 'selected' : '' }}>{{ $val->value }}</option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                                <td class="color-cell px-2 py-2">
                                    <input type="color" name="variants[{{ $i }}][color]" class="w-10 h-8 rounded border border-gray-200 cursor-pointer variant-color" value="{{ $variant->color ?? '' }}">
                                    <div class="color-blocker"></div>
                                </td>
                                <td class="px-2 py-2">
                                    <input type="text" name="variants[{{ $i }}][sku_suffix]" class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-sm" value="{{ $variant->sku_suffix }}" placeholder="SKU">
                                </td>
                                <td class="px-2 py-2">
                                    <input type="text" name="variants[{{ $i }}][height]" class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-sm" value="{{ $variant->height ?? '' }}" placeholder="Height">
                                </td>
                                <td class="px-2 py-2">
                                    <input type="text" name="variants[{{ $i }}][width]" class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-sm" value="{{ $variant->width ?? '' }}" placeholder="Width">
                                </td>
                                <td class="px-2 py-2 text-center">
                                    @if($loop->first)
                                        @if($variant->image_url)
                                            @php $s3Url = 'https://her-ovia.s3.us-east-1.amazonaws.com/' . $variant->image_url; @endphp
                                            <img src="{{ $s3Url }}" class="w-8 h-8 object-cover rounded mx-auto">
                                        @else
                                            <span class="badge bg-info">Auto from gallery</span>
                                        @endif
                                        <input type="hidden" name="variants[{{ $i }}][selected_gallery_image]" class="selected-gallery-image" value="{{ $variant->image_url ?? '' }}">
                                    @else
                                        @if($variant->image_url)
                                            @php $s3Url = 'https://her-ovia.s3.us-east-1.amazonaws.com/' . $variant->image_url; @endphp
                                            <img src="{{ $s3Url }}" class="w-8 h-8 object-cover rounded mx-auto mb-1">
                                        @endif
                                        <input type="file" name="variants[{{ $i }}][image_file]" class="w-full px-2 py-1 border border-gray-200 rounded-lg text-sm" accept="image/*">
                                    @endif
                                </td>
                                <td class="px-2 py-2">
                                    <input type="number" name="variants[{{ $i }}][sort_order]" class="w-16 px-2 py-1.5 border border-gray-200 rounded-lg text-sm text-center" value="{{ $variant->sort_order ?? 0 }}">
                                </td>
                                <td class="px-2 py-2">
                                    <select name="variants[{{ $i }}][status]" class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-sm">
                                        <option value="active" {{ $variant->status === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $variant->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </td>
                                <td class="px-2 py-2">
                                    <input type="number" name="variants[{{ $i }}][quantity]" class="w-20 px-2 py-1.5 border border-gray-200 rounded-lg text-sm text-center qty" value="{{ $variant->quantity }}" min="0">
                                </td>
                                <td class="px-2 py-2">
                                    <input type="number" step="0.01" name="variants[{{ $i }}][purchase_price]" class="w-24 px-2 py-1.5 border border-gray-200 rounded-lg text-sm text-right purchase" value="{{ $variant->purchase_price }}">
                                </td>
                                <td class="px-2 py-2">
                                    <input type="number" step="0.01" name="variants[{{ $i }}][selling_price]" class="w-24 px-2 py-1.5 border border-gray-200 rounded-lg text-sm text-right selling" value="{{ $variant->selling_price ?? 0 }}">
                                </td>
                                <td class="px-2 py-2">
                                    <input type="number" step="0.01" class="w-24 px-2 py-1.5 border border-gray-200 rounded-lg text-sm text-right total bg-gray-50" readonly value="{{ number_format($variant->quantity * $variant->purchase_price, 2) }}">
                                </td>
                                <td class="px-2 py-2 text-center">
                                    <button type="button" class="remove-variant text-red-500 hover:text-red-700 text-xl font-bold">×</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr id="variantEmptyRow">
                            <td colspan="14" class="text-center text-gray-400 py-8">No variants added yet</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="border-t border-gray-100 bg-gray-50 p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg border border-gray-100 p-3">
                    <div class="text-xs text-gray-400 mb-1">Gross Total</div>
                    <div class="text-xl font-bold text-gray-800">₹ <span id="grossTotal">0.00</span></div>
                </div>
                <div class="bg-white rounded-lg border border-gray-100 p-3">
                    <div class="text-xs text-gray-400 mb-1" id="commissionLabel">Supplier Commission</div>
                    <div class="text-xl font-bold text-[#8B2452]">₹ <span id="commissionAmount">0.00</span></div>
                </div>
                <div class="bg-white rounded-lg border border-gray-100 p-3">
                    <div class="text-xs text-gray-400 mb-1">Total</div>
                    <div class="text-xl font-bold text-green-600">₹ <span id="netPayable">0.00</span></div>
                </div>
            </div>
        </div>
    </div>
</div>

<template id="variantRowTemplate">
    <tr class="variant-row border-b border-gray-100 hover:bg-gray-50">
        <td class="px-2 py-2">
            <select name="variants[__INDEX__][variant_id]" class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-sm variant-type focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452]/20">
                <option value="">Select</option>
                @foreach($variants as $masterVariant)
                    <option value="{{ $masterVariant->id }}" data-input-type="{{ $masterVariant->input_type }}" data-has-dimensions="{{ $masterVariant->has_dimensions }}">{{ $masterVariant->name }}</option>
                @endforeach
            </select>
        </td>
        <td class="px-2 py-2">
            <select name="variants[__INDEX__][variant_value_id]" class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-sm variant-value" data-selected-id="">
                <option value="">Select value</option>
            </select>
        </td>
        <td class="color-cell px-2 py-2">
            <input type="color" name="variants[__INDEX__][color]" class="w-10 h-8 rounded border border-gray-200 cursor-pointer variant-color" value="">
            <div class="color-blocker"></div>
        </td>
        <td class="px-2 py-2">
            <input type="text" name="variants[__INDEX__][sku_suffix]" class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-sm" placeholder="SKU">
        </td>
        <td class="px-2 py-2">
            <input type="text" name="variants[__INDEX__][height]" class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-sm" placeholder="Height">
        </td>
        <td class="px-2 py-2">
            <input type="text" name="variants[__INDEX__][width]" class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-sm" placeholder="Width">
        </td>
        <td class="px-2 py-2 text-center">
    <input type="hidden" name="variants[__INDEX__][selected_gallery_image]" class="selected-gallery-image">
    <div class="auto-text">
        <span class="badge bg-info">Auto from gallery</span>
    </div>
    <div class="manual-upload" style="display:none;">
        <input type="file" name="variants[__INDEX__][image_file]" class="variant-image-file" accept="image/*" style="display:none;">
        <button type="button" class="choose-file-btn" style="padding:4px 12px; font-size:12px; border:1px solid #8B2452; background:white; color:#8B2452; border-radius:6px; cursor:pointer;">Choose File</button>
    </div>
</td>
        <td class="px-2 py-2">
            <input type="number" name="variants[__INDEX__][sort_order]" class="w-16 px-2 py-1.5 border border-gray-200 rounded-lg text-sm text-center" value="0">
        </td>
        <td class="px-2 py-2">
            <select name="variants[__INDEX__][status]" class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-sm">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </td>
        <td class="px-2 py-2">
            <input type="number" name="variants[__INDEX__][quantity]" class="w-20 px-2 py-1.5 border border-gray-200 rounded-lg text-sm text-center qty" value="0" min="0">
        </td>
        <td class="px-2 py-2">
            <input type="number" step="0.01" name="variants[__INDEX__][purchase_price]" class="w-24 px-2 py-1.5 border border-gray-200 rounded-lg text-sm text-right purchase" value="0">
        </td>
        <td class="px-2 py-2">
            <input type="number" step="0.01" name="variants[__INDEX__][selling_price]" class="w-24 px-2 py-1.5 border border-gray-200 rounded-lg text-sm text-right selling" value="0">
        </td>
        <td class="px-2 py-2">
            <input type="number" step="0.01" class="w-24 px-2 py-1.5 border border-gray-200 rounded-lg text-sm text-right total bg-gray-50" readonly value="0">
        </td>
        <td class="px-2 py-2 text-center">
            <button type="button" class="remove-variant text-red-500 hover:text-red-700 text-xl font-bold">×</button>
        </td>
    </tr>
</template>