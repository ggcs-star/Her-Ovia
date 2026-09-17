<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Models\PurchaseOrderItem;
use App\Models\PlatformPricing;
use App\Models\InvoiceItem;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\StockMovement;
use App\Models\PlatformProduct;
use App\Models\Platform;
use App\Models\AmazonOrderItem;
use App\Models\Product;
class InventoryController extends Controller
{
    private function getOfflinePlatformId()
    {
        $platform = Platform::where('display_name', 'Offline')->first();
        if (!$platform) {
            throw new \Exception('Offline platform not found. Please check platforms table.');
        }
        return $platform->id;
    }

    private function getWebsitePlatformId()
{
    $platform = Platform::where('display_name', 'Our Website')
        ->orWhere('display_name', 'own_website')
        ->orWhere('display_name', 'Own Website')
        ->orWhere('display_name', 'Website')
        ->orWhere('name', 'own_website')
        ->first();
    
    if (!$platform) {
        throw new \Exception('Website platform not found. Please check platforms table.');
    }
    return $platform->id;
}

public function dashboard(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $search = $request->get('search', '');
        $brand = $request->get('brand', '');
        $category = $request->get('category', '');
        $supplier = $request->get('supplier', '');
        $channel = $request->get('channel', '');
        $stockStatus = $request->get('stock_status', '');
        $perPage = (int) $request->get('per_page', 15);

        $offlinePlatformId = $this->getOfflinePlatformId();
        $websitePlatformId = $this->getWebsitePlatformId();
        $amazonPlatformId = Platform::where('display_name', 'Amazon')->value('id');

        $variants = ProductVariant::with(['product', 'product.category', 'product.supplier', 'platformPricings.platformProduct'])
            ->whereHas('product')
           ->whereHas('platformPricings', function($q) use ($offlinePlatformId, $websitePlatformId, $amazonPlatformId) {
                $q->whereHas('platformProduct', function($sub) use ($offlinePlatformId, $websitePlatformId, $amazonPlatformId) {
                    $sub->whereIn('platform_id', [
                        $websitePlatformId,
                        $offlinePlatformId,
                        $amazonPlatformId
                    ]);
                });
            })
            ->when($search, function($q) use ($search) {
                $q->whereHas('product', function($sub) use ($search) {
                    $sub->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('sku', 'LIKE', "%{$search}%");
                })->orWhere('sku_suffix', 'LIKE', "%{$search}%");
            })
            ->when($brand, function($q) use ($brand) {
                $q->whereHas('product', function($sub) use ($brand) {
                    $sub->where('brand', $brand);
                });
            })
            ->when($category, function($q) use ($category) {
                $q->whereHas('product', function($sub) use ($category) {
                    $sub->where('category_id', $category);
                });
            })
            ->when($supplier, function($q) use ($supplier) {
                $q->whereHas('product', function($sub) use ($supplier) {
                    $sub->where('supplier_id', $supplier);
                });
            })
            ->get();

        $inventoryData = [];
        $totalStock = 0;
        $totalWebsite = 0;
        $totalOffline = 0;
        $totalWebsiteSold = 0;
        $totalOfflineSold = 0;
        $totalAmazon = 0;
        $totalAmazonSold = 0;

        foreach ($variants as $variant) {
            if ($variant->product == null) continue;

            $poQty = PurchaseOrderItem::where('product_variant_id', $variant->id)->sum('quantity');

            $websitePushed = PlatformProduct::where('product_variant_id', $variant->id)
                ->where('platform_id', $websitePlatformId)
                ->sum('platform_stock');

            $websiteAvailable = PlatformPricing::where('product_variant_id', $variant->id)
                ->whereHas('platformProduct', function($q) use ($websitePlatformId) {
                    $q->where('platform_id', $websitePlatformId);
                })
                ->sum('quantity');
           $offlinePushed = PlatformProduct::where('product_variant_id', $variant->id)
                ->where('platform_id', $offlinePlatformId)
                ->sum('platform_stock');
                            
            $amazonProduct = PlatformProduct::where('platform_id', $amazonPlatformId)
                ->where('product_variant_id', $variant->id)
                ->first();

            $amazonPushed = $amazonProduct ? (int) $amazonProduct->platform_stock : 0;

            $websiteSold = OrderItem::where('variant_id', $variant->id)->sum('quantity');
            $offlineSold = InvoiceItem::where('product_variant_id', $variant->id)->sum('quantity');

            $amazonSold = AmazonOrderItem::where('product_variant_id', $variant->id)
                ->sum('quantity_ordered');
            
            $websiteAvailable = max(0, $websiteAvailable);
            $offlineAvailable = max(0, $offlinePushed - $offlineSold);
            $amazonAvailable = max(0, $amazonPushed - $amazonSold);

            
            $totalPushed = $websitePushed + $offlinePushed + $amazonPushed;

            $totalSold = $websiteSold + $offlineSold + $amazonSold;

            $finalStock = max(0, $totalPushed - $totalSold);

            $totalStock += ($poQty + $amazonPushed);
            $totalWebsite += $websitePushed;
            $totalOffline += $offlinePushed;
            $totalWebsiteSold += $websiteSold;
            $totalOfflineSold += $offlineSold;
            $totalAmazon += $amazonPushed;
            $totalAmazonSold += $amazonSold;

            if ($stockStatus == 'in_stock' && $finalStock <= 0) continue;
            if ($stockStatus == 'low_stock' && ($finalStock > 5 || $finalStock <= 0)) continue;
            if ($stockStatus == 'out_of_stock' && $finalStock > 0) continue;

            if ($filter == 'website') {
                if ($websiteAvailable <= 0 && $websiteSold <= 0) continue;
            } elseif ($filter == 'offline') {
                if ($offlineAvailable <= 0 && $offlineSold <= 0) continue;
            } elseif ($filter == 'low') {
                if ($finalStock > 5 || $finalStock <= 0) continue;
            } elseif ($filter == 'out') {
                if ($finalStock > 0) continue;
            }

            $inventoryData[] = [
                 'product_id' => $variant->product->id,
    
                'variant_id' => $variant->id,
                'sku' => $variant->sku_suffix ?? 'N/A',
                'product_name' => $variant->product ? $variant->product->name : 'N/A',
                'brand' => $variant->product ? $variant->product->brand : '',
                'category' => $variant->product && $variant->product->category ? $variant->product->category->name : '',
                'supplier' => $variant->product && $variant->product->supplier ? $variant->product->supplier->name : '',
                'variant_name' => optional($variant->variant)->name . ': ' . optional($variant->value)->value,
                'total_stock' => $poQty + $amazonPushed,
                'total_pushed' => $websitePushed + $offlinePushed + $amazonPushed,
                
                'website_pushed' => $websitePushed,
                'website_available' => $websiteAvailable,
                'website_sold' => $websiteSold,
                
                'offline_pushed' => $offlinePushed,
                'offline_available' => $offlineAvailable,
                'offline_sold' => $offlineSold,
                
                'amazon_pushed' => $amazonPushed,
                'amazon_available' => $amazonAvailable,
                'amazon_sold' => $amazonSold,
                                
                'flipkart_pushed' => 0,
                'flipkart_available' => 0,
                'flipkart_sold' => 0,
                
                'total_sold' => $websiteSold + $offlineSold + $amazonSold,
                'final_stock' => $finalStock,
                'status' => $finalStock > 10 ? 'In Stock' : ($finalStock > 0 ? 'Low Stock' : 'Out of Stock'),
                'image_url' => $variant->image_url,
            ];
        }

        // ✅ CHANNEL FILTER - SIRF DISPLAY KE LIYE
        if ($channel == 'website') {
            $inventoryData = array_filter($inventoryData, function($item) {
                return $item['website_available'] > 0 || $item['website_sold'] > 0;
            });
        } elseif ($channel == 'offline') {
            $inventoryData = array_filter($inventoryData, function($item) {
                return $item['offline_available'] > 0 || $item['offline_sold'] > 0;
            });
        }
        elseif ($channel == 'amazon') {
            $inventoryData = array_filter($inventoryData, function ($item) {
                return $item['amazon_pushed'] > 0 || $item['amazon_sold'] > 0;
            });
        }
        $stockSetting = \App\Models\StockSetting::first();
$lowStockThreshold = $stockSetting ? (int) $stockSetting->threshold : 15;

$lowStockCount = 0;
$outOfStockCount = 0;

foreach ($inventoryData as $item) {
    $stock = $item['final_stock'] ?? 0;
    if ($stock > 0 && $stock <= $lowStockThreshold) {
        $lowStockCount++;
    } elseif ($stock == 0) {
        $outOfStockCount++;
    }
}


        $currentPage = (int) $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedData = array_slice($inventoryData, $offset, $perPage);
        $totalItems = count($inventoryData);

       $summary = [
        'total_products'      => $variants->count(),

        'total_stock'         => $totalStock,

        'total_website'       => $totalWebsite,
        'total_offline'       => $totalOffline,
        'total_amazon'        => $totalAmazon,
        'total_website_available' => max(0, $totalWebsite - $totalWebsiteSold),
        'total_offline_available' => max(0, $totalOffline - $totalOfflineSold),
        'total_amazon_available'  => max(0, $totalAmazon - $totalAmazonSold),

        'total_website_sold'  => $totalWebsiteSold,
        'total_offline_sold'  => $totalOfflineSold,
        'total_amazon_sold'   => $totalAmazonSold,

        'total_sold'          => $totalWebsiteSold + $totalOfflineSold + $totalAmazonSold,

        'total_available'     => max(
            0,
            $totalStock - ($totalWebsiteSold + $totalOfflineSold + $totalAmazonSold)
        ),
    ];

        $brands = \App\Models\Product::whereNotNull('brand')->distinct()->pluck('brand');
        $categories = \App\Models\Category::select('id', 'name')->get();
        $suppliers = \App\Models\Supplier::select('id', 'name')->where('status', 'active')->get();

        $platforms = Platform::where('is_enabled', true)
            ->where('status', 'active')
            ->distinct('name')
            ->get();
            // InventoryController.php - dashboard function me
// $inventoryData banane ke baad yeh daalo

foreach ($inventoryData as $item) {
    if (strpos($item['product_name'], 'Mint Blossom') !== false) {
        \Log::info('Mint Blossom: ' . $item['product_id'] . ' - ' . $item['variant_name']);
    }
    if (strpos($item['product_name'], 'Heritage Black') !== false) {
        \Log::info('Heritage Black: ' . $item['product_id'] . ' - ' . $item['variant_name']);
    }
}

        return view('inventory.dashboard', compact(
            'paginatedData', 'inventoryData', 'summary', 'totalItems', 
            'perPage', 'currentPage', 'filter', 'search', 'brand', 
            'category', 'supplier', 'stockStatus', 'brands', 'categories', 
            'suppliers', 'channel', 'platforms',  'lowStockCount', 'outOfStockCount'
        ));
    }

   public function searchSuggestions(Request $request)
    {
        $query = $request->get('q');

        if (empty($query)) {
            return response()->json([]);
        }

        $results = \App\Models\Product::where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                ->orWhere('sku', 'LIKE', "%{$query}%")
                ->orWhereHas('variants', function ($variant) use ($query) {
                    $variant->where('sku_suffix', 'LIKE', "%{$query}%");
                });
            })
            ->with('variants:id,product_id,sku_suffix')
            ->limit(10)
            ->get(['id', 'name', 'sku']);

        return response()->json($results);
    }
    private function getAmazonPlatformId()
    {
        $platform = Platform::where('display_name', 'Amazon')->first();

        if (!$platform) {
            throw new \Exception('Amazon platform not found.');
        }

        return $platform->id;
    }
    public function details(Product $product)
    {
        $offlinePlatformId = $this->getOfflinePlatformId();
        $websitePlatformId = $this->getWebsitePlatformId();
        $amazonPlatformId = Platform::where('display_name', 'Amazon')->value('id');

        $product->load([
            'category',
            'supplier',
            'variants.variant',
            'variants.value',
            'variants.platformPricings.platformProduct',
        ])->append('gallery_images_public');

        $variantData = [];

        $summary = [
            'master_stock'    => 0,
            'total_pushed'    => 0,
            'website_push'    => 0,
            'offline_push'    => 0,
            'amazon_push'     => 0,
            'website_sold'    => 0,
            'offline_sold'    => 0,
            'amazon_sold'     => 0,
            'total_sold'      => 0,
            'available_stock' => 0,
        ];

        foreach ($product->variants as $variant) {

            // Purchase Qty
            $poQty = PurchaseOrderItem::where('product_variant_id', $variant->id)
                ->sum('quantity');

            $websitePush = PlatformProduct::where('product_variant_id', $variant->id)
                ->where('platform_id', $websitePlatformId)
                ->sum('platform_stock');

            $websiteAvailableDetail = PlatformPricing::where('product_variant_id', $variant->id)
                ->whereHas('platformProduct', function ($q) use ($websitePlatformId) {
                    $q->where('platform_id', $websitePlatformId);
                })
                ->sum('quantity');

            // Offline Push
            $offlinePush = PlatformPricing::where('product_variant_id', $variant->id)
                ->whereHas('platformProduct', function ($q) use ($offlinePlatformId) {
                    $q->where('platform_id', $offlinePlatformId);
                })
                ->sum('quantity');

            // Amazon Push
            $amazonPush = (int) PlatformProduct::where('platform_id', $amazonPlatformId)
                ->where('product_variant_id', $variant->id)
                ->sum('platform_stock');

            // Website Sold
            $websiteSold = OrderItem::where('variant_id', $variant->id)
                ->sum('quantity');

            // Offline Sold
            $offlineSold = InvoiceItem::where('product_variant_id', $variant->id)
                ->sum('quantity');

            // Amazon Sold
            $amazonSold = AmazonOrderItem::where('product_variant_id', $variant->id)
                ->sum('quantity_ordered');

            // Dashboard Logic
            $masterStock = $poQty + $amazonPush;

            $totalPushed = $websitePush + $offlinePush + $amazonPush;

            $totalSold = $websiteSold + $offlineSold + $amazonSold;

            $available = max(0, $totalPushed - $totalSold);

            $variantData[] = [

                'variant' => $variant,

                'master_stock' => $masterStock,

                'po_qty' => $poQty,

                'website_push' => $websitePush,

                'offline_push' => $offlinePush,

                'amazon_push' => $amazonPush,

                'total_push' => $totalPushed,

                'website_sold' => $websiteSold,

                'offline_sold' => $offlineSold,

                'amazon_sold' => $amazonSold,

                'total_sold' => $totalSold,

                'available' => $available,

            ];

            // Summary

            $summary['master_stock'] += $masterStock;

            $summary['website_push'] += $websitePush;

            $summary['offline_push'] += $offlinePush;

            $summary['amazon_push'] += $amazonPush;

            $summary['total_pushed'] += $totalPushed;

            $summary['website_sold'] += $websiteSold;

            $summary['offline_sold'] += $offlineSold;

            $summary['amazon_sold'] += $amazonSold;

            $summary['total_sold'] += $totalSold;

            $summary['available_stock'] += $available;
        }

        return view('inventory.details', compact(
            'product',
            'variantData',
            'summary'
        ));
    }
}