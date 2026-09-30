<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductReviewSeeder extends Seeder
{
    private const REVIEW_DATA = [
        [
            'rating' => 5,
            'review' => 'The product quality is excellent. The material feels premium and the product looks exactly as expected.',
            'size' => 'M',
            'color' => 'Black',
            'status' => 'approved',
        ],
        [
            'rating' => 4,
            'review' => 'Really good product and the quality is nice. The fitting and overall finish are very good.',
            'size' => 'L',
            'color' => 'Maroon',
            'status' => 'approved',
        ],
        [
            'rating' => 3,
            'review' => 'The product is okay. Quality is decent and the overall experience was satisfactory.',
            'size' => 'M',
            'color' => 'Blue',
            'status' => 'approved',
        ],
        [
            'rating' => 2,
            'review' => 'The product is okay but the quality could be improved. The finish was not as expected.',
            'size' => 'L',
            'color' => 'Pink',
            'status' => 'pending',
        ],
        [
            'rating' => 1,
            'review' => 'The product did not meet my expectations. The quality and finish need improvement.',
            'size' => 'XL',
            'color' => 'Green',
            'status' => 'rejected',
        ],
    ];

    public function run(): void
    {
        $products = Product::query()
            ->select('id')
            ->get();

        $users = User::query()
            ->select('id', 'name')
            ->get();

        if ($products->isEmpty()) {
            $this->command?->warn('No products found. Product reviews were not created.');
            return;
        }

        foreach ($products as $product) {

            ProductReview::query()
                ->where('product_id', $product->id)
                ->whereNull('order_id')
                ->delete();

            foreach (self::REVIEW_DATA as $reviewData) {

                $user = $users->isNotEmpty()
                    ? $users->random()
                    : null;

                $rating = $reviewData['rating'];

                $title = match ($rating) {
                    1 => 'Very Bad',
                    2 => 'Bad',
                    3 => 'Okay-Okay',
                    4 => 'Good',
                    5 => 'Very Good',
                };

                ProductReview::create([
                    'product_id' => $product->id,
                    'user_id' => $user?->id,
                    'order_id' => null,
                    'rating' => $rating,
                    'title' => $title,
                    'review' => $reviewData['review'],
                    'size' => $reviewData['size'],
                    'color' => $reviewData['color'],
                    'images' => null,
                    'video' => null,
                    'status' => $reviewData['status'],
                    'approved_at' => $reviewData['status'] === 'approved'
                        ? now()
                        : null,
                ]);
            }
        }

        $this->command?->info(
            $products->count() . ' products received ' .
            count(self::REVIEW_DATA) .
            ' testing reviews each.'
        );
    }
}