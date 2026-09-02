<?php

namespace App\Transformers;

use App\Models\Product;
use App\Services\ProductPricingService;
use Illuminate\Support\Facades\Storage;
use App\Helpers\S3Helper;

class ProductDetailTransformer
{
    public static function transform(Product $product): array
    {
        $websitePlatform = \App\Models\Platform::getOwnWebsite();

// $listing = $product->platformListings
//     ->where('platform_id', $websitePlatform->id)
//     ->first();
    

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'product_price' => $product->product_price,
            'description' => $product->description,
            'short_description' => $product->short_description,
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
            'click_count' => (int) ($product->click_count ?? 0),

            'image_url' => $product->image_url
                ? S3Helper::url($product->image_url)
                : null,

            'gallery_images' => is_array($product->gallery_images)
                ? collect($product->gallery_images)
                    ->map(fn ($img) =>
                        S3Helper::url(str_replace('\\', '/', $img))
                    )
                    ->values()
                : [],

            'category' => $product->category ? [
                'id' => $product->category->id,
                'name' => $product->category->name,
                'parent' => $product->category->parent ? [
                    'id' => $product->category->parent->id,
                    'name' => $product->category->parent->name,
                    'parent' => $product->category->parent->parent ? [
                        'id' => $product->category->parent->parent->id,
                        'name' => $product->category->parent->parent->name,
                    ] : null,
                ] : null,
            ] : null,

            'stock' => $listing?->platform_stock ?? 0,
            'in_stock' => ($listing?->platform_stock ?? 0) > 0,

            'variants' => $product->variants->map(function ($variant) use ($product, $websitePlatform) {

                $listing = $product->platformListings
                    ->where('platform_id', $websitePlatform->id)
                    ->where('product_variant_id', $variant->id)
                    ->first();

                $pricing = ProductPricingService::getVariantPricing(
                    $variant,
                    $listing
                );
                

                return [
                    'id' => $variant->id,
                    'variant_type' => optional($variant->variant)->name,
                    'variant_value' => optional($variant->value)->value,
                    'color' => $variant->color,
                    'sku' => $variant->sku_suffix,

                    'price' => $pricing?->price,
                    'final_price' => $pricing?->final_price,
                    'currency' => $pricing?->currency ?? 'INR',
                    'quantity' => $pricing?->quantity ?? 0,

                    // ✅ PO Data
                    'po_quantity' => $variant->po_quantity ?? 0,
                    'po_purchase_price' => $variant->po_purchase_price ?? 0,
                    'po_number' => $variant->po_number ?? null,
                    'has_po' => $variant->has_po ?? false,

                    'image_url' => $variant->image_url
                        ? S3Helper::url($variant->image_url)
                        : null,

                    'in_stock' => ($pricing?->quantity ?? 0) > 0,

                    'discount' => $pricing ? [
                        'type' => $pricing->discount_type,
                        'value' => $pricing->discount_value,
                    ] : null,
                ];
            })->values(),
        ];
    }
}