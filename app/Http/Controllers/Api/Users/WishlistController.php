<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WishlistController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $wishlist = Wishlist::where(
                'user_id',
                $request->user()->id
            )
                ->latest()
                ->get()
                ->map(function ($item) {
                    return $item->product_data;
                })
                ->values();

            return $this->successResponse(
                'Wishlist fetched successfully',
                $wishlist
            );

        } catch (\Throwable $e) {
            Log::error('Wishlist Index Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => $request->user()?->id,
            ]);

            return $this->errorResponse();
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'id' => ['required', 'integer'],
                'name' => ['nullable', 'string'],
                'price' => ['nullable'],
                'image' => ['nullable', 'string'],
                'brand' => ['nullable', 'string'],
                'slug' => ['nullable', 'string'],
                'variantId' => ['nullable', 'integer'],
                'variantValue' => ['nullable'],
                'variantType' => ['nullable', 'string'],
                'mrp' => ['nullable'],
            ]);

            $userId = $request->user()->id;

            $productId = $validated['id'];
            $variantId = $validated['variantId'] ?? null;

            $wishlist = Wishlist::updateOrCreate(
                [
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                ],
                [
                    'product_data' => $validated,
                ]
            );

            return $this->successResponse(
                'Product added to wishlist successfully',
                $wishlist->product_data
            );

        } catch (\Throwable $e) {
            Log::error('Wishlist Store Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => $request->user()?->id,
            ]);

            return $this->errorResponse();
        }
    }

    public function destroy(
        Request $request,
        int $productId
    ): JsonResponse {
        try {
            Wishlist::where(
                'user_id',
                $request->user()->id
            )
                ->where('product_id', $productId)
                ->delete();

            return $this->successResponse(
                'Product removed from wishlist successfully',
                []
            );

        } catch (\Throwable $e) {
            Log::error('Wishlist Destroy Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => $request->user()?->id,
                'product_id' => $productId,
            ]);

            return $this->errorResponse();
        }
    }
}