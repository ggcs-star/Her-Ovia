<?php

namespace App\Services;

use App\Helpers\S3Helper;
use App\Models\Order;
use App\Models\ProductReview;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

class ProductReviewService
{
    private const RATING_TITLES = [
        1 => 'Very Bad',
        2 => 'Bad',
        3 => 'Okay-Okay',
        4 => 'Good',
        5 => 'Very Good',
    ];

    public function resolveTitle(?int $rating, ?string $userName): string
    {
        if ($rating !== null && isset(self::RATING_TITLES[$rating])) {
            return self::RATING_TITLES[$rating];
        }

        return trim($userName ?: 'Customer');
    }

    public function verifyCustomerPurchase(
        int $userId,
        int $productId,
        int $orderId
    ): array {
        $order = Order::query()
            ->whereKey($orderId)
            ->where('user_id', $userId)
            ->where('platform', 'website')
            ->whereIn('status', ['delivered'])
            ->whereHas('items', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->with([
                'items' => function ($query) use ($productId) {
                    $query->where('product_id', $productId)
                        ->with('variant.variant:id,name', 'variant.value:id,value');
                }
            ])
            ->first();

        if (!$order) {
            throw ValidationException::withMessages([
                'order_id' => 'You can review only products from your delivered orders.',
            ]);
        }

        $item = $order->items->first();

        return [
            'order' => $order,
            'item' => $item,
        ];
    }

    public function uploadImages(array $files, int $productId): array
    {
        $paths = [];

        foreach ($files as $index => $file) {
            if (!$file instanceof UploadedFile) {
                continue;
            }

            $extension = strtolower($file->getClientOriginalExtension());

            $filename = 'review-' . $productId . '-' . uniqid('', true) . '-' . $index . '.' . $extension;

            $paths[] = S3Helper::storeAs(
                $file,
                "reviews/products/{$productId}/images",
                $filename
            );
        }

        return $paths;
    }

    public function uploadVideo(UploadedFile $file, int $productId): string
    {
        $extension = strtolower($file->getClientOriginalExtension());

        $filename = 'review-' . $productId . '-' . uniqid('', true) . '.' . $extension;

        return S3Helper::storeAs(
            $file,
            "reviews/products/{$productId}/videos",
            $filename
        );
    }

    public function deleteMedia(ProductReview $review): void
    {
        foreach ($review->images ?? [] as $image) {
            if ($image) {
                S3Helper::delete($image);
            }
        }

        if ($review->video) {
            S3Helper::delete($review->video);
        }
    }

    public function deleteImages(array $images): void
    {
        foreach ($images as $image) {
            if ($image) {
                S3Helper::delete($image);
            }
        }
    }
}