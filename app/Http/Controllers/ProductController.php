<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Platform;
use App\Models\ProductVariant;
use App\Models\PlatformPricing;

use App\Models\PlatformProduct;
use App\Models\Warehouse;
use App\Models\Variant;
use App\Models\VariantValue;
use Illuminate\Support\Str;
use App\Helpers\S3Helper;
use App\Models\PurchaseOrderItem;

class ProductController extends Controller
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
    $platform = Platform::where('display_name', 'Our Website')->first();
    if (!$platform) {
        throw new \Exception('Website platform not found. Please check platforms table.');
    }
    return $platform->id;
}
private function isUploadedFile($file): bool
{
    return $file instanceof \Illuminate\Http\UploadedFile;
}
    public function index(Request $request)
    {
        $products = Product::select([
            'id',
            'name',
            'sku',
            'slug',
            'category_id',
            'supplier_id',
            'warehouse_id',
            'cost_price',
            'base_selling_price',
            'product_price',
            'image_url',
            'gallery_images',
            'status',
            'visibility'
        ])
            ->with([
                'category:id,name',
                'supplier:id,name',
                'warehouse:id,name,city'
            ])

            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })

            ->when(
                $request->filled('category_id'),
                fn($q) =>
                $q->where('category_id', $request->category_id)
            )

            ->when(
                $request->filled('supplier_id'),
                fn($q) =>
                $q->where('supplier_id', $request->supplier_id)
            )

            ->when(
                $request->filled('visibility'),
                fn($q) =>
                $q->where('visibility', $request->visibility)
            )

            ->when(
                $request->filled('status'),
                fn($q) => $q->where('status', $request->status)
            )
            ->when(
                $request->filled(['adv_field', 'adv_condition', 'adv_value']),
                function ($q) use ($request) {

                    $field = $request->adv_field;
                    $condition = $request->adv_condition;
                    $value = $request->adv_value;

                    $allowedFields = [
                        'name',
                        'sku',
                        'cost_price',
                        'status',
                        'visibility'
                    ];

                    if (!in_array($field, $allowedFields)) {
                        return;
                    }

                    if ($condition === 'like') {

                        $q->where($field, 'LIKE', "%{$value}%");

                    } elseif ($condition === 'starts_with') {

                        $q->where($field, 'LIKE', "{$value}%");

                    } elseif ($condition === 'ends_with') {

                        $q->where($field, 'LIKE', "%{$value}");

                    } else {
                        // =, !=, >, <
                        $q->where($field, $condition, $value);
                    }
                }
            )

            ->orderBy('id', 'desc')
            ->paginate(10);
        $categories = Category::select('id', 'name')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $suppliers = Supplier::select('id', 'name')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'suppliers' => $suppliers,
        ]);
    }

    public function create()
    {
        $categories = Category::select('id', 'name', 'parent_id')
            ->orderBy('name')
            ->get();

        $suppliers = Supplier::active()
            ->orderBy('name')
            ->get();

        $warehouses = Warehouse::where('status', 'active')
            ->orderBy('city')
            ->get();

        $variants = Variant::with('values')
            ->where('is_active', 1)
            ->get();
            
        $product = new Product();

        return view('products.create', compact(
            'categories',
            'suppliers',
            'warehouses',
            'variants',
            'product'  
        ));
    }
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {

            $productData = $this->validateProduct($request);
            $productData = $this->handleProductImages($request, $productData);

            $product = $this->createProduct($request, $productData);

            $totals = $this->handleVariants($request, $product);

            $this->updateProductPrices($product, $totals);
        });

        return redirect()
            ->to(admin_route('products.index'))
            ->with('success', 'Product & variants created successfully.');
    }

    private function validateProduct(Request $request): array
    {
        $data = $request->validate([
            'sku' => 'required|string|max:100|unique:products,sku',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'product_price' => 'nullable|numeric|min:0',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',

            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'brand' => 'nullable|string|max:100',

            'image_url' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',

            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',

            'sort_order' => 'nullable|integer|min:0',
            'is_featured' => 'nullable|boolean',
            'is_top_selling' => 'nullable|boolean',
            'is_best_seller' => 'nullable|boolean',

            'visibility' => 'required|in:public,private',
            'status' => 'required|in:active,inactive',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'expected_delivery_date' => 'nullable|date',
            'payment_terms' => 'nullable|string|max:50',


            

        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_top_selling'] = $request->boolean('is_top_selling');
$data['is_best_seller'] = $request->boolean('is_best_seller');
        return $data;
    }

        private function handleProductImages(Request $request, array $data): array
{
    $productSlug = Str::slug($request->slug);

    if ($request->hasFile('image_url')) {

        $file = $request->file('image_url');

        $fileName = $productSlug . '.' . $file->getClientOriginalExtension();

        $data['image_url'] = S3Helper::storeAs(
            $file,
            "admin/product/{$productSlug}",
            $fileName
        );
    }

    if ($request->hasFile('gallery_images')) {

        $gallery = [];

        foreach ($request->file('gallery_images') as $index => $img) {

            $fileName = $productSlug . '-' . ($index + 1) . '.' . $img->getClientOriginalExtension();

            $gallery[] = S3Helper::storeAs(
                $img,
                "admin/product/{$productSlug}",
                $fileName
            );
        }

        $data['gallery_images'] = $gallery;
    }

    return $data;
}

    private function createProduct(Request $request, array $data): Product
    {
        $data['cost_price'] = 0;
        $data['base_selling_price'] = 0;

          $data['expected_delivery_date'] = $request->expected_delivery_date;
          $data['payment_terms'] = $request->payment_terms;

        return Product::create($data);
    }

    private function handleVariants(Request $request, Product $product): array
{
    $totalPurchase = 0;
    $totalSelling  = 0;

    if (!$request->has('variants')) {
        return compact('totalPurchase', 'totalSelling');
    }

    $seen = []; 
    $processedVariantIds = [];

    foreach ($request->variants as $index => $variant) {

        if (empty($variant['variant_id']) || empty($variant['variant_value_id'])) {
            continue;
        }

        $variantId = (int) $variant['variant_id'];
        $valueId   = (int) $variant['variant_value_id'];

        $key = $variantId . '-' . $valueId;
        if (isset($seen[$key])) {
            continue;
        }
        $seen[$key] = true;

        $qty      = (int) ($variant['quantity'] ?? 0);
        $purchase = (float) ($variant['purchase_price'] ?? 0);
        $selling  = (float) ($variant['selling_price'] ?? 0);
        $color    = !empty($variant['color']) ? $variant['color'] : null;

        if ($qty <= 0) continue;

        $imagePath = null;
        
        $existingVariant = ProductVariant::where([
            'product_id' => $product->id,
            'variant_id' => $variantId,
            'variant_value_id' => $valueId,
        ])->first();

        if ($existingVariant && $existingVariant->image_url) {
            $imagePath = $existingVariant->image_url;
            if (empty($color) && $existingVariant->color) {
                $color = $existingVariant->color;
            }
        }

        // Priority 1: Manual file upload (for second, third variants)
        if (isset($variant['image_file']) && $this->isUploadedFile($variant['image_file'])) {
            $productSlug = Str::slug($product->slug ?? $product->name);
            $variantSlug = Str::slug(($variant['sku_suffix'] ?? 'variant') . '-' . ($variant['variant_value_id'] ?? uniqid()));
            $file = $variant['image_file'];

            $fileName =
                $variantSlug .
                '.' .
                $file->getClientOriginalExtension();

            $imagePath = S3Helper::storeAs(
                $file,
                "admin/product/{$productSlug}/variant/{$variantSlug}",
                $fileName
            );
        }
        // Priority 2: Gallery image (for first variant)
        elseif (!empty($variant['selected_gallery_image'])) {
            $imagePath = $this->saveBase64Image($variant['selected_gallery_image'], $product, $variant);
        }
        // Priority 3: Direct file upload (fallback)
        elseif (isset($variant['image_url']) && $this->isUploadedFile($variant['image_url'])) {
            $productSlug = Str::slug($product->slug ?? $product->name);
            $variantSlug = Str::slug(($variant['sku_suffix'] ?? 'variant') . '-' . ($variant['variant_value_id'] ?? uniqid()));
            $file = $variant['image_url'];

            $fileName =
                $variantSlug .
                '.' .
                $file->getClientOriginalExtension();

            $imagePath = S3Helper::storeAs(
                $file,
                "admin/product/{$productSlug}/variant/{$variantSlug}",
                $fileName
            );
        }

        $variantModel = ProductVariant::updateOrCreate(
            [
                'product_id'       => $product->id,
                'variant_id'       => $variantId,
                'variant_value_id' => $valueId,
            ],
            [
                'quantity'         => $qty,
                'purchase_price'   => $purchase,
                'selling_price'    => $selling,
                'total_price'      => $qty * $purchase,
                'sku_suffix'       => $variant['sku_suffix'] ?? null,
                'sort_order'       => $variant['sort_order'] ?? 0,
                'status'           => $variant['status'] ?? 'active',
                'color'            => $color,
                'height'           => $variant['height'] ?? null,
                'width'            => $variant['width'] ?? null,
                'image_url'        => $imagePath,
            ]
        );
        
        $processedVariantIds[] = $variantModel->id;

        $totalPurchase += $qty * $purchase;
        $totalSelling  += $qty * $selling;
    }
    
    if (!empty($processedVariantIds)) {
        ProductVariant::where('product_id', $product->id)
            ->whereNotIn('id', $processedVariantIds)
            ->delete();
    }

    return compact('totalPurchase', 'totalSelling');
}
    private function saveBase64Image($base64String, $product, $variant)
    {
        try {
            if (strpos($base64String, 'data:image') !== 0) {
                return $base64String;
            }
            
            $image_parts = explode(";base64,", $base64String);
            
            if (count($image_parts) < 2) {
                return null;
            }
            
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1] ?? 'jpeg';
            $image_base64 = base64_decode($image_parts[1]);
            
            if (!$image_base64) {
                return null;
            }
            
            
            $productSlug = Str::slug($product->slug ?? $product->name);
            $variantSlug = Str::slug(($variant['sku_suffix'] ?? 'variant') . '-' . ($variant['variant_value_id'] ?? uniqid()));
            $filename = $variantSlug . '.' . $image_type;
            $path = "admin/product/{$productSlug}/variant/{$variantSlug}/{$filename}";
            
            S3Helper::put($path, $image_base64);
            
            return $path;
            
        } catch (\Exception $e) {
            \Log::error("Failed to save base64 image: " . $e->getMessage());
            return null;
        }
    }
    private function updateProductPrices(Product $product, array $totals): void
    {
        $product->update([
            'cost_price' => $totals['totalPurchase'],
            'base_selling_price' => $totals['totalSelling'],
        ]);
    }
    public function edit(Product $product)
    {
    $product->load('variants');

    $categories = Category::select('id', 'name', 'parent_id')
        ->orderBy('name')
        ->get();

    $suppliers = Supplier::active()
        ->orderBy('name')
        ->get();

    $warehouses = Warehouse::where('status', 'active')
        ->orderBy('city')
        ->get();
     $variants = Variant::with('values')
        ->where('is_active', true)
        ->orderBy('name')
        ->get();


    return view('products.edit', compact(
        'product',
        'categories',
        'suppliers',
        'warehouses',
        'variants'
    ));
}
    public function update(Request $request, Product $product)
    {
        try {
        DB::transaction(function () use ($request, $product) {

            $productData = $this->validateProductForUpdate($request, $product);
            $productData = $this->handleProductImagesForUpdate($request, $product, $productData);

            $product->update($productData);

            $keepVariantIds = [];
            
            if ($request->has('variants')) {
                foreach ($request->variants as $variant) {
                    if (!empty($variant['variant_id']) && !empty($variant['variant_value_id'])) {
                        // Find existing variant or create a temporary key
                        $existingVariant = ProductVariant::where([
                            'product_id' => $product->id,
                            'variant_id' => $variant['variant_id'],
                            'variant_value_id' => $variant['variant_value_id'],
                        ])->first();
                        
                        if ($existingVariant) {
                            $keepVariantIds[] = $existingVariant->id;
                        }
                    }
                }
            }
            ProductVariant::where('product_id', $product->id)
                ->whereNotIn('id', $keepVariantIds)
                ->delete();

            $totals = $this->handleVariants($request, $product);

            $this->updateProductPrices($product, $totals);
        });

        return redirect()
            ->to(admin_route('products.index'))
            ->with('success', 'Product & variants updated successfully.');
    } catch (\Throwable $e) {

            \Log::error($e); 

            return back()->with(
                'error',
                'Something went wrong while saving the product. Please check required fields.'
            );
        }
    }
    private function validateProductForUpdate(Request $request, Product $product): array
    {
        $data = $request->validate([
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'product_price' => 'nullable|numeric|min:0',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',

            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'brand' => 'nullable|string|max:100',

            'image_url' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',

            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',

            'sort_order' => 'nullable|integer|min:0',
            'is_featured' => 'nullable|boolean',
            'is_top_selling' => 'nullable|boolean',
'is_best_seller' => 'nullable|boolean',
            'visibility' => 'required|in:public,private',
            'status' => 'required|in:active,inactive',

            'warehouse_id' => 'nullable|exists:warehouses,id',
            'expected_delivery_date' => 'nullable|date',
            'payment_terms' => 'nullable|string|max:50',

        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_top_selling'] = $request->boolean('is_top_selling');
$data['is_best_seller'] = $request->boolean('is_best_seller');
        return $data;
    }

        private function handleProductImagesForUpdate(
            Request $request,
            Product $product,
            array $data
        ): array {

            $productSlug = Str::slug($request->slug);

            if ($request->hasFile('image_url')) {
                $file = $request->file('image_url');

                $fileName = $productSlug . '.' . $file->getClientOriginalExtension();

                $data['image_url'] = S3Helper::storeAs(
                    $file,
                    "admin/product/{$productSlug}",
                    $fileName
                );
            }

            if ($request->hasFile('gallery_images')) {

                $existingImages = is_array($product->gallery_images)
            ? $product->gallery_images
            : [];

        $newImages = [];

        $start = count($existingImages);

        foreach ($request->file('gallery_images') as $index => $img) {

            $fileName = $productSlug . '-' . ($start + $index + 1) . '.' . $img->getClientOriginalExtension();

            $newImages[] = S3Helper::storeAs(
                $img,
                "admin/product/{$productSlug}",
                $fileName
            );
        }

        $data['gallery_images'] = array_merge(
            $existingImages,
            $newImages
        );
            }

            return $data;
        }


    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {

            foreach ($product->variants as $variant) {

                if ($variant->image_url) {
                    S3Helper::delete($variant->image_url);
                }
            }


           if ($product->image_url) {
            S3Helper::delete($product->image_url);
        }

        if (is_array($product->gallery_images)) {
            foreach ($product->gallery_images as $img) {
                if ($img) {
                    S3Helper::delete($img);
                }
            }
        }

        if ($product->image_url) {
            S3Helper::delete($product->image_url);
        }

            $product->variants()->delete();

            $product->delete();
        });

        return redirect()
            ->to(admin_route('products.index'))
            ->with('success', 'Product deleted successfully.');
    }

    public function show(Product $product)
    {
        $product->load([
    'category:id,name,slug,parent_id',
    'category.parent:id,name',
    'supplier:id,name,company_name,phone,email,type,commission_type,commission_value',
    'warehouse:id,name,city',
'variants' => function ($query) {
    $query->with(['variant:id,name', 'value:id,value'])
          ->orderBy('sort_order')
          ->orderBy('id');
}

]);

    return view('products.show', compact('product'));
}


public function list()
{
    $pushedProducts = PlatformProduct::with([
        'platform:id,display_name',
        'product.category:id,name',
        'product.supplier:id,name',
        'pricing.variant.variant:id,name',
        'pricing.variant.value:id,value'
    ])->get();

    // ✅ Purchase Order Data Fetch
    $poData = [];
    $purchaseItems = \App\Models\PurchaseOrderItem::with('variant', 'purchaseOrder')
        ->whereHas('purchaseOrder', function($query) {
            $query->where('status', '!=', 'cancelled');
        })
        ->get();
    
    foreach ($purchaseItems as $item) {
        if ($item->product_variant_id) {
            $poData[$item->product_variant_id] = [
                'quantity' => $item->quantity,
                'purchase_price' => $item->purchase_price,
                'po_number' => $item->purchaseOrder->po_number ?? 'N/A',
            ];
        }
    }

    return view('products.list', compact('pushedProducts', 'poData'));
}

public function push(Request $request)
{
    $websitePlatformId = $this->getWebsitePlatformId();
    $offlinePlatformId = $this->getOfflinePlatformId();

    $poProductIds = PurchaseOrderItem::whereHas('purchaseOrder', function($query) {
        $query->where('status', '!=', 'cancelled');
    })->pluck('product_id')->unique()->toArray();

    $products = Product::with([
        'category:id,name,parent_id',
        'category.parent:id,name',
        'variants:id,product_id,variant_id,variant_value_id,sku_suffix,image_url,sort_order,status,quantity,purchase_price,selling_price,color',
        'variants.variant:id,name',
        'variants.value:id,value',
        'variants.platformPricings.platformProduct'
    ])
    ->where('status', 'active')
    ->whereIn('id', $poProductIds)
    ->orderBy('name')
    ->get()
    ->map(function ($product) {
        $category = $product->category;
        if ($category) {
            if ($category->parent) {
                $product->display_category = $category->parent->name;
                $product->display_subcategory = $category->name;
            } else {
                $product->display_category = $category->name;
                $product->display_subcategory = null;
            }
        }
        $product->variant_payload = $product->variants->map(function ($v) {
            return [
                'id' => $v->id,
                'variant_type'  => optional($v->variant)->name,
                'variant_value' => optional($v->value)->value,
                'color' => $v->color, 
                'sku_suffix' => $v->sku_suffix,
                'quantity' => $v->quantity,
                'purchase_price' => $v->purchase_price,
                'selling_price'  => $v->selling_price,
            ];
        });
        $gallery = is_array($product->gallery_images)
            ? $product->gallery_images
            : json_decode($product->gallery_images, true);
        $product->image = (!empty($gallery) && !empty($gallery[0]))
            ? \App\Helpers\S3Helper::url($gallery[0])
            : asset('images/no-image.png');
        return $product;
    });

    $existingConfig = [];
    foreach ($products as $product) {
        foreach ($product->variants as $variant) {
            foreach ($variant->platformPricings as $pricing) {
                $platformId = $pricing->platformProduct->platform_id;
                $existingConfig[$variant->id][$platformId] = [
                    'price' => $pricing->price,
                    'qty'   => $pricing->quantity,
                    'discount_value' => $pricing->discount_value,
                    'discount_type'  => $pricing->discount_type === 'percentage' ? 'percent' : 'amount',
                    'final_total'    => $pricing->quantity * $pricing->final_price
                ];
            }
        }
    }

    $platforms = Platform::select('id', 'name')
        ->where('is_enabled', true)
        ->get();

    $pushedQuantities = [];
    $allPushedData = PlatformPricing::with('platformProduct')
        ->whereHas('platformProduct', function($q) use ($websitePlatformId, $offlinePlatformId) {
            $q->whereIn('platform_id', [$websitePlatformId, $offlinePlatformId]);
        })
        ->get();

    foreach ($allPushedData as $pricing) {
        $variantId = $pricing->product_variant_id;
        $pushedQuantities[$variantId] = ($pushedQuantities[$variantId] ?? 0) + $pricing->quantity;
    }

    $purchaseOrderData = [];
    $purchaseItems = PurchaseOrderItem::with('variant', 'purchaseOrder')
        ->whereHas('purchaseOrder', function($query) {
            $query->where('status', '!=', 'cancelled');
        })
        ->get();

    foreach ($purchaseItems as $item) {
        if ($item->product_variant_id) {
            $variant = ProductVariant::find($item->product_variant_id);
            $totalPoQty = $item->quantity;
            $alreadyPushed = $pushedQuantities[$item->product_variant_id] ?? 0;
            $availableStock = $totalPoQty - $alreadyPushed;
            
            $purchaseOrderData[$item->product_variant_id] = [
                'quantity' => $item->quantity,
                'purchase_price' => $item->purchase_price,
                'product_id' => $item->product_id,
                'po_number' => $item->purchaseOrder->po_number ?? 'N/A',
                'actual_stock' => $variant ? $variant->quantity : 0,
                'available_stock' => $availableStock,
                'pushed_quantity' => $alreadyPushed,
            ];
        }
    }

    $productId = $request->product_id ?? null;

    return view('products.push', [
        'products' => $products,
        'platforms' => $platforms,
        'existingVariantPlatformData' => $existingConfig,
        'purchaseOrderData' => $purchaseOrderData,
        'productId' => $productId,
        'pushedQuantities' => $pushedQuantities,
    ]);
}
public function pushStore(Request $request)
{
    \Log::info('PUSH DATA', $request->all());

    try {
        $request->validate([
            'variant_platform_data' => 'required'
        ]);

        $data = json_decode($request->variant_platform_data, true);

        if (!$data || !is_array($data)) {
            return back()->with('error', 'No platform data found');
        }

        DB::transaction(function () use ($data) {

            foreach ($data as $variantId => $platforms) {

                if (!is_array($platforms) || empty($platforms)) {
                    continue;
                }

                $variant = ProductVariant::with(['product','value'])
                    ->where('id', $variantId)
                    ->lockForUpdate()
                    ->firstOrFail();

                $poItem = PurchaseOrderItem::where('product_variant_id', $variantId)
                    ->whereHas('purchaseOrder', function($q) {
                        $q->where('status', '!=', 'cancelled');
                    })
                    ->first();
                
                $poQuantity = $poItem ? $poItem->quantity : 0;
                // $totalPushed calculate karne se pehle
                \Log::info('=== PUSH DEBUG ===');
                \Log::info('Variant ID: ' . $variantId);
                \Log::info('PO Quantity: ' . $poQuantity);

// ✅ Sirf Website + Offline ka pushed count karo (Amazon ko exclude)
                $websitePlatformId = $this->getWebsitePlatformId();
                $offlinePlatformId = $this->getOfflinePlatformId();

                $totalPushed = PlatformPricing::where('product_variant_id', $variantId)
                    ->whereHas('platformProduct', function($q) use ($websitePlatformId, $offlinePlatformId) {
                        $q->whereIn('platform_id', [$websitePlatformId, $offlinePlatformId]);
                    })
                    ->sum('quantity');
                    \Log::info('Total Pushed from PlatformPricing: ' . $totalPushed);

                // Check all tables
                $allTables = [
                    'platform_pricing' => PlatformPricing::where('product_variant_id', $variantId)->sum('quantity'),
                    'platform_products' => PlatformProduct::where('product_variant_id', $variantId)->sum('platform_stock'),
                ];

                \Log::info('All Tables Sum: ', $allTables);

                $totalRequested = collect($platforms)
                    ->filter(fn($p) => isset($p['qty']) && $p['qty'] > 0)
                    ->sum('qty');

                if ($totalRequested <= 0) {
                    continue;
                }

                foreach ($platforms as $platformId => $p) {

                    if (!isset($p['qty']) || $p['qty'] <= 0) continue;

                    if ($platformId == 4) {
                        $platformId = 5;
                    }

                    if ($platformId == 3) {
                        $platformId = 3;
                    }

                    $platformProduct = PlatformProduct::firstOrCreate(
                        [
                            'platform_id' => $platformId,
                            'product_id'  => $variant->product_id,
                            'product_variant_id' => $variant->id,
                        ],
                        [
                            'platform_sku'   => $variant->sku_suffix ?? $variant->product->sku,
                            'platform_price' => $p['price'],
                            'platform_stock' => 0,
                            'status'         => 'active',
                            'sync_status'    => 'pending',
                        ]
                    );

                    $platformProduct->update([
                        'platform_sku'   => $variant->sku_suffix ?? $variant->product->sku,
                        'platform_price' => $p['price'],
                        'status'         => 'active',
                    ]);

                    $discountType = $p['discount_type'] === 'percent' ? 'percentage' : 'fixed';
                    
                    $existingPricing = PlatformPricing::where([
                        'platform_product_id' => $platformProduct->id,
                        'product_variant_id'  => $variantId,
                    ])->first();

                   if ($existingPricing) {
    $totalQty = $existingPricing->quantity + $p['qty'];
    
    $existingPricing->quantity = $totalQty;
    $existingPricing->price = $p['price'];
    $existingPricing->discount_type = $discountType;
    $existingPricing->discount_value = $p['discount_value'];
    $existingPricing->final_price = $p['final_total'] / max($p['qty'], 1);
    $existingPricing->save();
    
    // ✅ DEBUG - Check if saved
    \Log::info('Updated Pricing ID: ' . $existingPricing->id);
    \Log::info('New Quantity: ' . $existingPricing->quantity);
    \Log::info('New Final Price: ' . $existingPricing->final_price);
}
                     else {
                        PlatformPricing::create([
                            'platform_product_id' => $platformProduct->id,
                            'product_variant_id'  => $variantId,
                            'price'          => $p['price'],
                            'discount_type'  => $discountType,
                            'discount_value' => $p['discount_value'],
                            'final_price'    => $p['final_total'] / max($p['qty'], 1),
                            'quantity'       => $p['qty'],
                            'currency'       => 'INR',
                            'status'         => 'active',
                        ]);
                    }

                    $platformProduct->platform_stock = PlatformPricing::where('platform_product_id', $platformProduct->id)->sum('quantity');
                    $platformProduct->save();
                }

                // ✅ Sirf Website + Offline ka pushed count karo (Amazon ko exclude)
            $totalPushed = PlatformPricing::where('product_variant_id', $variantId)
                ->whereHas('platformProduct', function($q) use ($websitePlatformId, $offlinePlatformId) {
                    $q->whereIn('platform_id', [$websitePlatformId, $offlinePlatformId]);
                })
                ->sum('quantity');
                
                if ($totalPushed > $poQuantity) {
                    throw new \Exception(
                        "Not enough stock! PO Qty: {$poQuantity}, Total Pushed: {$totalPushed}"
                    );
                }

                $availableStock = $poQuantity - $totalPushed;
                $variant->quantity = $availableStock;
                $variant->save();
                
                \Log::info('Stock updated for Variant ' . $variantId . ': Available = ' . $availableStock);
            }
        });

        return redirect()->route('admin.products.list')
            ->with('success', 'Product pushed to marketplace successfully!');

    } catch (\Throwable $e) {
        return back()->with('error', $e->getMessage());
    }
}        public function bulkDelete(Request $request)
        {
            $ids = $request->ids;

            if (!$ids || !is_array($ids)) {
                return redirect()->back();
            }

            Product::whereIn('id', $ids)->delete();

            return redirect()->back()->with('success', 'Selected products deleted successfully');
        }
        public function invoiceView(Product $product)
    {
        $product->load(['variants', 'supplier', 'warehouse']);

        return view('products.invoice', compact('product'));
    }
        public function deleteImage(Product $product, $index)
        {
            $images = is_array($product->gallery_images)
                ? $product->gallery_images
                : [];

            if (!isset($images[$index])) {
                return response()->json(['success' => false]);
            }

            S3Helper::delete($images[$index]);

            // remove from array
            unset($images[$index]);
            $images = array_values($images);

            $product->update([
                'gallery_images' => $images
            ]);

            return response()->json(['success' => true]);
        }


}
