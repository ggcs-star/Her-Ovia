<?php

namespace App\Http\Controllers\Api\Users;

use App\Helpers\S3Helper;
use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Services\ProductReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductReviewController extends Controller
{
    public function __construct(
        private readonly ProductReviewService $reviewService
    ) {
    }

    public function index(Request $request, int $productId): JsonResponse
    {
        $perPage = min(
            max((int) $request->input('per_page', 10), 1),
            50
        );

        $reviews = ProductReview::query()
            ->where('product_id', $productId)
            ->with([
                'user:id,name',
            ])
            ->latest()
            ->paginate($perPage);

        $ratingQuery = ProductReview::query()
            ->rated()
            ->where('product_id', $productId);

        $ratingCount = (clone $ratingQuery)->count();

        $averageRating = $ratingCount > 0
            ? round((float) $ratingQuery->avg('rating'), 1)
            : 0;

        $distribution = [];

        for ($rating = 5; $rating >= 1; $rating--) {
            $distribution[$rating] = (clone $ratingQuery)
                ->where('rating', $rating)
                ->count();
        }

        $reviews->getCollection()->transform(
            fn (ProductReview $review) => $this->transformReview($review)
        );

        return response()->json([
            'success' => true,
            'data' => [
                'reviews' => $reviews,
                'summary' => [
                    'average_rating' => $averageRating,
                    'rating_count' => $ratingCount,
                    'distribution' => $distribution,
                ],
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],

            'order_id' => [
                'required',
                'integer',
                'exists:orders,id',
            ],

            'rating' => [
                'nullable',
                'integer',
                'between:1,5',
            ],

            'review' => [
                'required',
                'string',
                'min:2',
                'max:5000',
            ],

            'size' => [
                'nullable',
                'string',
                'max:100',
            ],

            'color' => [
                'nullable',
                'string',
                'max:100',
            ],

            'images' => [
                'nullable',
                'array',
                'max:5',
            ],

            'images.*' => [
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'video' => [
                'nullable',
                'file',
                'mimes:mp4,mov,avi,webm',
                'max:51200',
            ],
        ]);

        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $purchase = $this->reviewService->verifyCustomerPurchase(
            (int) $user->id,
            (int) $validated['product_id'],
            (int) $validated['order_id']
        );

        $existing = ProductReview::query()
            ->where('user_id', $user->id)
            ->where('product_id', $validated['product_id'])
            ->where('order_id', $validated['order_id'])
            ->exists();

        if ($existing) {
            throw ValidationException::withMessages([
                'review' => 'You have already reviewed this product for this order.',
            ]);
        }

        $images = [];
        $video = null;

        DB::beginTransaction();

        try {
            if (!empty($validated['images'])) {
                $images = $this->reviewService->uploadImages(
                    $validated['images'],
                    (int) $validated['product_id']
                );
            }

            if ($request->hasFile('video')) {
                $video = $this->reviewService->uploadVideo(
                    $request->file('video'),
                    (int) $validated['product_id']
                );
            }

            $review = ProductReview::create([
                'product_id' => $validated['product_id'],
                'user_id' => $user->id,
                'order_id' => $validated['order_id'],
                'rating' => $validated['rating'] ?? null,
                'title' => $this->reviewService->resolveTitle(
                    $validated['rating'] ?? null,
                    $user->name
                ),
                'review' => trim($validated['review']),
                'size' => $validated['size'] ?? null,
                'color' => $validated['color'] ?? null,
                'images' => $images,
                'video' => $video,
            ]);

            DB::commit();

            $review->load('user:id,name');

            return response()->json([
                'success' => true,
                'message' => 'Your review has been submitted successfully.',
                'data' => [
                    'review' => $this->transformReview($review),
                    
                ],
            ], 201);

        } catch (\Throwable $e) {

            DB::rollBack();

            if (!empty($images)) {
                $this->reviewService->deleteImages($images);
            }

            if ($video) {
                S3Helper::delete($video);
            }

            throw $e;
        }
    }
    public function existing(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],
            'order_id' => [
                'required',
                'integer',
                'exists:orders,id',
            ],
        ]);

        $user = $request->user();

        $review = ProductReview::query()
            ->where('user_id', $user->id)
            ->where('product_id', $validated['product_id'])
            ->where('order_id', $validated['order_id'])
            ->with('user:id,name')
            ->first();

        return response()->json([
            'success' => true,
            'exists' => (bool) $review,
            'data' => [
                'review' => $review
                    ? $this->transformReview($review)
                    : null,
            ],
        ]);
    }

    public function update(Request $request, ProductReview $productReview): JsonResponse
    {
        $user = $request->user();

        if (!$user || $productReview->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not allowed to edit this review.',
            ], 403);
        }

        $validated = $request->validate([
            'rating' => [
                'nullable',
                'integer',
                'between:1,5',
            ],

            'review' => [
                'required',
                'string',
                'min:2',
                'max:5000',
            ],

            'size' => [
                'nullable',
                'string',
                'max:100',
            ],

            'color' => [
                'nullable',
                'string',
                'max:100',
            ],

            'images' => [
                'nullable',
                'array',
                'max:5',
            ],

            'images.*' => [
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'video' => [
                'nullable',
                'file',
                'mimes:mp4,mov,avi,webm',
                'max:51200',
            ],
        ]);

        $images = [];
        $video = null;
        $oldImages = $productReview->images ?? [];
        $oldVideo = $productReview->video;

        DB::beginTransaction();

        try {
            $newImagesUploaded = !empty($validated['images']);
            $newVideoUploaded = $request->hasFile('video');

            if ($newImagesUploaded) {
                $images = $this->reviewService->uploadImages(
                    $validated['images'],
                    (int) $productReview->product_id
                );
            } else {
                $images = $oldImages;
            }

            if ($newVideoUploaded) {
                $video = $this->reviewService->uploadVideo(
                    $request->file('video'),
                    (int) $productReview->product_id
                );
            } else {
                $video = $oldVideo;
            }

            $productReview->update([
                'rating' => $validated['rating'] ?? null,
                'title' => $this->reviewService->resolveTitle(
                    $validated['rating'] ?? null,
                    $user->name
                ),
                'review' => trim($validated['review']),
                'size' => $validated['size'] ?? null,
                'color' => $validated['color'] ?? null,
                'images' => $images,
                'video' => $video,
            ]);

            DB::commit();

            if ($newImagesUploaded && !empty($oldImages)) {
                $this->reviewService->deleteImages($oldImages);
            }

            if ($newVideoUploaded && $oldVideo) {
                S3Helper::delete($oldVideo);
            }

            $productReview->load('user:id,name');

            return response()->json([
                'success' => true,
                'message' => 'Your review has been updated successfully.',
                'data' => [
                    'review' => $this->transformReview($productReview),
                ],
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            if (!empty($images) && $images !== $oldImages) {
                $this->reviewService->deleteImages($images);
            }

            if ($video && $video !== $oldVideo) {
                S3Helper::delete($video);
            }

            throw $e;
        }
    }

    private function transformReview(ProductReview $review): array
    {
        return [
            'id' => $review->id,
            'reviewer' => $review->user?->name ?? 'Customer',
            'rating' => $review->rating,
            'title' => $review->title,
            'text' => $review->review,
            'size' => $review->size,
            'color' => $review->color,
            'date' => $review->created_at?->diffForHumans(),
            'images' => $review->image_urls,
            'video' => $review->video_url,
        ];
    }
}