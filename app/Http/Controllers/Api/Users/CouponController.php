<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Throwable;

class CouponController extends Controller
{
    public function index(): JsonResponse
    {
        $now = Carbon::now();

        $coupons = Coupon::with('bank:id,name')
            ->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', $now);
            })
            ->get()
            ->map(function ($coupon) {
                return [
                    'id' => $coupon->id,
                    'name' => $coupon->name,
                    'code' => $coupon->code,
                    'coupon_type' => $coupon->coupon_type,
                    'bank' => $coupon->bank?->name,
                    'card_type' => $coupon->card_type,
                    'discount_type' => $coupon->discount_type,
                    'value' => $coupon->value,
                    'max_discount' => $coupon->max_discount, 
                    'min_order_amount' => $coupon->min_order_amount,
                ];
            });

        return $this->successResponse(
            'Coupons fetched successfully',
            $coupons
        );
    }
    
    public function apply(Request $request): JsonResponse
    {
        
      $request->validate([
    'coupon_code' => 'required|string',
    'cart_total'  => 'required|numeric',

    'product_id'  => 'required|integer',
    'category_id' => 'required|integer',

    'bank_id'     => 'nullable|integer',
    'card_type'   => 'nullable|in:credit,debit',
]);
// dd($request->all());

        try {
            // ✅ Ab auth check nahi karte - Guest user allow
            // Cart database check HATAYA

            $coupon = Coupon::where('code', strtoupper($request->coupon_code))
                ->where('is_active', true)
                ->first();

            if (!$coupon) {
                return $this->errorResponse(
                    'Invalid or inactive coupon',
                    422
                );
            }
            // ================= PRODUCT RESTRICTION =================

// Product Restriction
if ($coupon->product_id) {

    if ($coupon->product_id != $request->product_id) {

        return $this->errorResponse(
            'This coupon is valid only for selected product.',
            422
        );

    }
}

// Sub Category Restriction
if ($coupon->subcategory_id) {

    if ($coupon->subcategory_id != $request->category_id) {

        return $this->errorResponse(
            'This coupon is valid only for selected sub category.',
            422
        );

    }
}

// Parent Category Restriction
if ($coupon->category_id) {

    $childIds = \App\Models\Category::where('parent_id', $coupon->category_id)
        ->pluck('id')
        ->toArray();

    if (!in_array($request->category_id, $childIds)) {

        return $this->errorResponse(
            'This coupon is valid only for selected category.',
            422
        );

    }
}

            $now = now();

            if ($coupon->starts_at && $now->lt($coupon->starts_at)) {
                return $this->errorResponse(
                    'Coupon not started yet',
                    422
                );
            }

            if ($coupon->expires_at && $now->gt($coupon->expires_at)) {
                return $this->errorResponse(
                    'Coupon expired',
                    422
                );
            }

            // ✅ Frontend se aaya cart_total use karo
            $cartTotal = $request->cart_total;

            if ($coupon->min_order_amount !== null && $cartTotal < $coupon->min_order_amount) {
                return $this->errorResponse(
                    'Minimum order amount of ₹' . number_format($coupon->min_order_amount, 2) . ' required',
                    422
                );
            }
            
            // Bank coupon validation
            if ($coupon->coupon_type === 'BANK') {
                if (!$request->card_type) {
                    return $this->errorResponse(
                        'Card type required for this coupon',
                        422
                    );
                }
                if ($coupon->bank_id) {
                    if (!$request->bank_id || $coupon->bank_id != $request->bank_id) {
                        return $this->errorResponse(
                            'Coupon not valid for selected bank',
                            422
                        );
                    }
                }

                if ($coupon->card_type !== 'both' && $coupon->card_type !== $request->card_type) {
                    return $this->errorResponse(
                        'Coupon not valid for this card type',
                        422
                    );
                }
            }

            // Discount calculation
            if ($coupon->discount_type === 'PERCENT') {
                $discount = ($cartTotal * $coupon->value) / 100;
                if ($coupon->max_discount) {
                    $discount = min($discount, $coupon->max_discount);
                }
            } else {
                $discount = $coupon->value;
            }

            $finalTotal = max($cartTotal - $discount, 0);

            return $this->successResponse(
                'Coupon applied successfully',
                [
                    'cart_total' => round($cartTotal, 2),
                    'discount' => round($discount, 2),
                    'final_total' => round($finalTotal, 2),
                    'coupon_code' => $coupon->code,
                    'coupon_type' => $coupon->coupon_type
                ]
            );

       } catch (Throwable $e) {

        return $this->errorResponse();

    }
    }

    public function remove(): JsonResponse
    {
        return $this->successResponse(
            'Coupon removed successfully',
            null
        );
    }
}