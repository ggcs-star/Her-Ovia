<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Helpers\S3Helper;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'sku',
        'name',
        'slug',
        'description',
        'short_description',
        'category_id',
        'supplier_id',
        'brand',
        'cost_price',
        'base_selling_price',
        'product_price',
        'image_url',
        'gallery_images',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'sort_order',
        'is_featured',
        'is_top_selling',
        'is_best_seller',
        'visibility',
        'status',
        'warehouse_id',
        'expected_delivery_date',
        'payment_terms',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'is_featured' => 'boolean',
        'is_top_selling' => 'boolean',
        'is_best_seller' => 'boolean',
        'expected_delivery_date' => 'date',
        'product_price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function variants()
    {
        return $this->hasMany(
            \App\Models\ProductVariant::class,
            'product_id',
            'id'
        );
    }

    public function platforms()
    {
        return $this->belongsToMany(Platform::class, 'platform_products')
            ->withPivot([
                'platform_sku',
                'platform_price',
                'platform_stock',
                'status'
            ])
            ->withTimestamps();
    }

    public function platformListings()
    {
        return $this->hasMany(PlatformProduct::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function searchableAs(): string
    {
        return 'products';
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'brand' => $this->brand,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'category_id' => $this->category_id,
        ];
    }

    public function getImageUrlPublicAttribute()
    {
        if (!$this->image_url) {
            return null;
        }

        return S3Helper::url(
            str_replace('\\', '/', $this->image_url)
        );
    }

    public function clicks()
    {
        return $this->hasMany(ProductClick::class);
    }

    public function getGalleryImagesPublicAttribute()
    {
        if (!$this->gallery_images) {
            return [];
        }

        $images = is_array($this->gallery_images)
            ? $this->gallery_images
            : json_decode($this->gallery_images, true);

        return collect($images)->map(function ($path) {
            return S3Helper::url(
                str_replace('\\', '/', $path)
            );
        })->toArray();
    }
}