<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\PlatformProduct;
use App\Models\PlatformPricing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CartController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $cart = $this->getUserCart(true);

            if (!$cart || $cart->items->isEmpty()) {
                return $this->emptyCartResponse($cart);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'cart_id' => $cart->id,
                    'items' => $cart->items->map(fn ($item) => $this->formatCartItem($item)),
                    'cart_total' => $this->cartTotal($cart),
                    'items_count' => $this->cartItemsCount($cart),
                ]
            ]);

        } catch (Throwable $e) {
            return $this->errorResponse('Get Cart API Error', $e);
        }
    }

    
    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.variant_id' => 'nullable|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $cart = $this->getOrCreateCart();

            foreach ($request->items as $itemData) {

            $platformProduct = $this->getPlatformProduct(
                $itemData['product_id'],
                null,
                $itemData['variant_id'] ?? null
            );
            
            $variantId = $itemData['variant_id'] ?? null;
                if ($variantId === null) {
                    $firstVariant = \App\Models\ProductVariant::where('product_id', $itemData['product_id'])->first();
                    $variantId = $firstVariant ? $firstVariant->id : 0;
                }

            $pricing = $this->getPricing(
                $platformProduct->id,
                $variantId,
                $itemData['quantity']
            );

            $cartItem = CartItem::firstOrNew([
                'cart_id' => $cart->id,
                'product_id' => $itemData['product_id'],
                'product_variant_id' => $variantId,
                'platform_id' => $platformProduct->platform_id,
            ]);

                $unitPrice = isset($itemData['price'])
                    ? (float) $itemData['price']
                    : (float) ($pricing->final_price ?? $pricing->price);

                $existingQty = $cartItem->exists ? $cartItem->quantity : 0;
                $newQty = $existingQty + $itemData['quantity'];

                if ($pricing->quantity < $newQty) {
                    abort(422, "Only {$pricing->quantity} items available in stock");
                }

                $cartItem->price = $unitPrice;
                $cartItem->quantity = $newQty;
                $cartItem->subtotal = $newQty * $unitPrice;
                $cartItem->image = $itemData['image'] ?? null;
                $cartItem->save();
            }

            $cart->refresh();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Items added to cart successfully',
                'data' => [
                    'cart_total' => $this->cartTotal($cart),
                    'items_count' => $this->cartItemsCount($cart),
                ]
            ]);

        } catch (Throwable $e) {
            DB::rollBack();
            return $this->errorResponse('Add to Cart API Error', $e);
        }
    }

    public function update(Request $request, int $cartItemId): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $cartItem = $this->getUserCartItem($cartItemId);

            $platformProduct = $this->getPlatformProduct(
                $cartItem->product_id,
                $cartItem->platform_id
            );

            $pricing = $this->getPricing(
                $platformProduct->id,
                $cartItem->product_variant_id,
                $request->quantity
            );

            $unitPrice = $pricing->final_price ?? $pricing->price;

            $cartItem->quantity = $request->quantity;
            $cartItem->price = $unitPrice;
            $cartItem->subtotal = $unitPrice * $request->quantity;
            $cartItem->save();

            $cart = $cartItem->cart;
            $cart->refresh();

            DB::commit();

            return $this->successCartResponse(
                'Cart item updated successfully',
                $cartItem,
                $cart
            );

        } catch (Throwable $e) {
            DB::rollBack();
            return $this->errorResponse('Update Cart Item API Error', $e);
        }
    }

    
    public function remove(int $cartItemId): JsonResponse
    {
        try {
            DB::beginTransaction();

            $cartItem = $this->getUserCartItem($cartItemId);
            $cart = $cartItem->cart;

            $cartItem->delete();
            $cart->refresh();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart successfully',
                'data' => [
                    'cart_total' => $this->cartTotal($cart),
                    'items_count' => $this->cartItemsCount($cart),
                ]
            ]);

        } catch (Throwable $e) {
            DB::rollBack();
            return $this->errorResponse('Remove Cart Item API Error', $e);
        }
    }

    private function getUserCart(bool $withRelations = false): ?Cart
    {
        return Cart::where('user_id', auth()->id())
            ->when($withRelations, fn ($q) =>
        $q->with([
            'items.product:id,name,image_url',
            'items.variant.variant',
            'items.variant.value'
        ])

            )
            ->latest('id') 
            ->first();
    }


    private function getOrCreateCart(): Cart
    {
        return Cart::firstOrCreate(['user_id' => auth()->id()]);
    }

    private function getUserCartItem(int $id): CartItem
    {
        return CartItem::where('id', $id)
            ->whereHas('cart', fn ($q) => $q->where('user_id', auth()->id()))
            ->firstOrFail();
    }

    private function getPlatformProduct(int $productId, ?int $platformId = null, ?int $variantId = null): PlatformProduct
    {
        $query = PlatformProduct::where('product_id', $productId)
            ->when($platformId, fn ($q) => $q->where('platform_id', $platformId))
            ->when($variantId, fn ($q) => $q->where('product_variant_id', $variantId))
            ->userVisible();

        $product = $query->first();

        if (!$product) {
            throw new \Exception("Platform product not found for product_id: {$productId}, variant_id: {$variantId}");
        }

        return $product;
    }

    private function getPricing(int $platformProductId, ?int $variantId, int $qty): PlatformPricing
    {
        $query = PlatformPricing::where([
            'platform_product_id' => $platformProductId,
            'status' => 'active',
        ]);
        
        if ($variantId !== null && $variantId !== 0) {
            $query->where('product_variant_id', $variantId);
        } else {
            $query->where(function($q) {
                $q->whereNull('product_variant_id')
                ->orWhere('product_variant_id', 0);
            });
        }
        
        $pricing = $query->first();
        
        if (!$pricing) {
            abort(422, 'Pricing not found for selected variant');
        }
        
        if (!$pricing) {
            abort(422, 'Pricing configuration missing for this product');
        }

        if ($pricing->quantity < $qty) {
            abort(422, 'Requested quantity not available');
        }

        return $pricing;
    }

    private function cartTotal(Cart $cart): float
    {
        return (float) $cart->items->sum('subtotal');
    }

    private function cartItemsCount(Cart $cart): int
    {
        return (int) $cart->items->sum('quantity');
    }

    private function formatCartItem(CartItem $item): array
    {
        return [
            'id' => $item->id,
            'product_id' => $item->product_id,
            'variant_id' => $item->product_variant_id,
            'platform_id' => $item->platform_id,
            'product_name' => $item->product?->name,
            'variant' => [
                'type' => $item->variant?->variant?->name,
                'value' => $item->variant?->value?->value,
            ],

            'price' => $item->price,
            'quantity' => $item->quantity,
            'subtotal' => $item->subtotal,
            'image_url' => $item->image ? $item->image : $item->product?->image_url,
        ];
    }


    private function emptyCartResponse(?Cart $cart = null): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'cart_id' => $cart?->id,   
                'items' => [],
                'cart_total' => 0,
                'items_count' => 0,
            ]
        ]);
    }

    private function errorResponse(string $context, Throwable $e): JsonResponse
    {
        Log::error($context, [
            'message' => $e->getMessage(),
            'exception' => $e,
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Something went wrong. Please try again.',
        ], 500);
    }


    private function successCartResponse(string $msg, CartItem $item, Cart $cart): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $msg,
            'data' => [
                'cart_item' => [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->product_variant_id,
                    'platform_id' => $item->platform_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->subtotal,
                ],
                'cart_total' => $this->cartTotal($cart),
                'items_count' => $this->cartItemsCount($cart),
            ]
        ]);
    }
}
