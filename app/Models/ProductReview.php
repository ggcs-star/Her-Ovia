<?php

namespace App\Models;

use App\Helpers\S3Helper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReview extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'order_id',
        'rating',
        'title',
        'review',
        'size',
        'color',
        'images',
        'video',
        'status',
        'approved_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'images' => 'array',
        'approved_at' => 'datetime',
    ];

    protected $appends = [
        'image_urls',
        'video_url',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopeRated(Builder $query): Builder
    {
        return $query->whereNotNull('rating');
    }

    public function getImageUrlsAttribute(): array
    {
        return collect($this->images ?? [])
            ->filter()
            ->map(fn ($image) => str_starts_with($image, 'http')
                ? $image
                : S3Helper::url($image)
            )
            ->values()
            ->all();
    }

    public function getVideoUrlAttribute(): ?string
    {
        if (!$this->video) {
            return null;
        }

        return str_starts_with($this->video, 'http')
            ? $this->video
            : S3Helper::url($this->video);
    }
}