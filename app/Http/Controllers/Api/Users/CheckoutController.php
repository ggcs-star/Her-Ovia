<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserAddress;
use App\Models\Coupon;
use App\Models\DeliverySetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\PlatformPricing;
use App\Models\PlatformProduct;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Illuminate\Support\Facades\DB;
use Throwable;
use App\Services\ActivityLogService;
use App\Jobs\SendNewOrderNotificationJob;
use App\Services\ShiprocketService;
class CheckoutController extends Controller
{
    public function summary(Request $request): JsonResponse
    {
        try {
            $cart = Cart::where('user_id', auth()->id())
                ->with([
                    'items.product:id,name,image_url',
                    'items.variant:id,image_url'
                ])
                ->firstOrFail();

            if ($cart->items->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty'
                ], 422);
            }

            $subtotal = $cart->items->sum('subtotal');

            $discount = $this->calculateDiscount(
                $request->coupon_code,
                $subtotal
            );

            $afterDiscount = max($subtotal - $discount, 0);

            $settings = DeliverySetting::getSettings();

            if (!$settings) {
                throw new \RuntimeException('Delivery settings not configured.');
            }

            $shippingAddressId = $request->input('shipping_address_id');

            if (!$shippingAddressId) {
                $shippingAddress = UserAddress::where('user_id', auth()->id())
                    ->where('type', 'shipping')
                    ->where('is_default', true)
                    ->first();
            } else {
                $shippingAddress = UserAddress::where('id', $shippingAddressId)
                    ->where('user_id', auth()->id())
                    ->where('type', 'shipping')
                    ->first();
            }

            $shipping = 0;
            $shippingData = null;

            if ($shippingAddress) {
                $paymentMethod = $request->input('payment_method', 'cod');

                $shippingData = $this->getShiprocketShipping(
                    $shippingAddress,
                    $paymentMethod
                );

                $shipping = (float) $shippingData['shipping'];
            }
            $platformFee = $settings->platform_fee;

            $tax = ($afterDiscount * $settings->tax_percent) / 100;

            $total = round($afterDiscount + $shipping + $platformFee + $tax, 2);

            $addresses = UserAddress::where('user_id', auth()->id())
                ->where('type', 'shipping')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'cart' => [
                        'currency' => 'INR',
                        'subtotal' => round($subtotal, 2),
                        'tax' => $tax,
                        'shipping' => $shipping,
                        'discount' => round($discount, 2),
                        'platform_fee' => round($platformFee, 2),
                        'total' => $total,
                        'items_count' => $cart->items->sum('quantity'),
                        'coupon_code' => $request->coupon_code ?? null,
                    ],
                    'shipping_addresses' => $addresses,
                    'payment_methods' => [
                        [
                            'id' => 1,
                            'name' => 'Cash on Delivery',
                            'type' => 'cod',
                            'is_available' => true,
                        ],
                        [
                            'id' => 2,
                            'name' => 'Online Payment',
                            'type' => 'online',
                            'is_available' => true,
                        ],
                    ],
                ]
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
private function getShiprocketShipping(
    UserAddress $shippingAddress,
    string $paymentMethod
): array {
    $settings = DeliverySetting::getSettings();

    if (!$settings) {
        throw new \RuntimeException('Delivery settings not configured.');
    }

    try {
        $shiprocket = app(ShiprocketService::class);

        $shippingData = $shiprocket->checkServiceability(
            $shippingAddress->postal_code,
            $paymentMethod === 'cod'
        );

    } catch (\Throwable $e) {

        // Shiprocket API/credentials error
        // Use Delivery Settings delivery fee
        return [
            'serviceable' => true,
            'shipping' => (float) $settings->delivery_fee,
            'delivery_date' => null,
            'source' => 'delivery_setting',
        ];
    }

    // Shiprocket responded successfully,
    // but pincode is not serviceable
    if (!($shippingData['serviceable'] ?? false)) {
        throw new \RuntimeException(
            'Delivery is not available at this pincode.'
        );
    }

    // Shipping charge missing from Shiprocket response
    if (($shippingData['shipping'] ?? null) === null) {
        return [
            'serviceable' => true,
            'shipping' => (float) $settings->delivery_fee,
            'delivery_date' => null,
            'source' => 'delivery_setting',
        ];
    }

    return $shippingData;
}
    public function placeOrder(Request $request): JsonResponse
    {
        $request->validate([
            'shipping_address_id' => 'required|integer',
            'billing_address_id' => 'required|integer',
            'payment_method_id' => 'required|integer',
            'coupon_code' => 'nullable|string',
        ]);

        try {

            DB::beginTransaction();

            $userId = auth()->id();

            $cart = Cart::where('user_id', $userId)
                ->with([
                    'items.product:id,name,image_url',
                    'items.variant:id,image_url'
                ])
                ->firstOrFail();
            $shippingAddress = UserAddress::where('id', $request->shipping_address_id)
                ->where('user_id', $userId)
                ->where('type', 'shipping')
                ->firstOrFail();

            $billingAddress = UserAddress::where('id', $request->billing_address_id)
                ->where('user_id', $userId)
                ->firstOrFail();

            if ($cart->items->isEmpty()) {
                abort(422, 'Cart is empty');
            }

            $subtotal = $cart->items->sum('subtotal');

            $settings = DeliverySetting::getSettings();

            $discount = $this->calculateDiscount($request->coupon_code, $subtotal);

            $afterDiscount = max($subtotal - $discount, 0);

            $shippingData = $this->getShiprocketShipping($shippingAddress, 'cod');

            $shipping = (float) $shippingData['shipping'];

            $estimatedDeliveryDate = !empty($shippingData['delivery_date'])
                ? \Carbon\Carbon::parse($shippingData['delivery_date'])->toDateString()
                : null;

            $platformFee = $settings->platform_fee;

            $tax = ($afterDiscount * $settings->tax_percent) / 100;

            $total = round($afterDiscount + $shipping + $platformFee + $tax, 2);

            $order = Order::create([
                
                'user_id' => $userId,
                'order_number' => 'ORD-' . now()->format('Ymd') . '-' . rand(100, 999),
                'status' => 'pending',
                'payment_status' => 'pending',
                'subtotal' => round($subtotal, 2),
                'tax' => round($tax, 2),
                'shipping' => $shipping,
                'estimated_delivery_date' => $estimatedDeliveryDate,
                'discount' => round($discount, 2),
                'platform_fee' => $platformFee,
                'total' => $total,
                'coupon_code' => $request->coupon_code,
                'shipping_address_id' => $shippingAddress->id,
                'billing_address_id' => $billingAddress->id,
                'payment_method' => 'cod',
            ]);
            // Update coupon usage after successful order creation
if ($request->filled('coupon_code')) {

    $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();

    if ($coupon) {

        $coupon->increment('used_count');

        // Disable coupon if usage limit reached
        if (
            $coupon->usage_limit &&
            ($coupon->used_count + 1) >= $coupon->usage_limit
        ) {
            $coupon->update([
                'is_active' => false
            ]);
        }
    }
}

            foreach ($cart->items as $item) {

                $image = $item->image
                    ?? $item->variant->image_url
                    ?? $item->product->image_url;

                if ($image && str_starts_with($image, 'http')) {
                    $image = ltrim(parse_url($image, PHP_URL_PATH), '/');
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name ?? 'Product',
                    'variant_id' => $item->product_variant_id,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                    'image' => $image,
                ]);
                 \App\Models\PlatformPricing::where('product_variant_id', $item->product_variant_id)
                    ->decrement('quantity', $item->quantity);

                // \App\Models\PlatformProduct::where('product_variant_id', $item->product_variant_id)
                    // ->decrement('platform_stock', $item->quantity);
            }

            // Activity Log
            ActivityLogService::log(
                auth()->user(),
                'order',
                $order->id,
                'success',
                [
                    'payment_method' => 'COD',
                    'order_number' => $order->order_number,
                    'amount' => $order->total,
                ]
            );

            $cart->items()->delete();

            DB::commit();
            SendNewOrderNotificationJob::dispatch($order)->afterCommit();
            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',
                'data' => [
                    'order' => [
                        'id' => $order->id,
                        'order_number' => $order->order_number,
                        'status' => $order->status,
                        'total' => $order->total,
                        'payment_status' => $order->payment_status,
                        'created_at' => $order->created_at,
                    ]
                ]
            ]);

        } catch (Throwable $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    private function calculateDiscount(?string $couponCode, float $subtotal): float
    {
        if (!$couponCode) {
            return 0;
        }

        $coupon = Coupon::where('code', strtoupper($couponCode))
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return 0;
        }
        // Coupon usage limit
if (
    $coupon->usage_limit &&
    $coupon->used_count >= $coupon->usage_limit
) {
    return 0;
}

// One time per user
if ($coupon->one_time_per_user) {

    $alreadyUsed = Order::where('user_id', auth()->id())
        ->where('coupon_code', strtoupper($coupon->code))
        ->exists();

    if ($alreadyUsed) {
        return 0;
    }
}

        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            return 0;
        }

        if ($coupon->discount_type === 'PERCENT') {
            $discount = ($subtotal * $coupon->value) / 100;

            if ($coupon->max_discount) {
                $discount = min($discount, $coupon->max_discount);
            }
        } else {
            $discount = $coupon->value;
        }

        $discount = min($discount, $subtotal);

        return round($discount, 2);
    }
    public function createRazorpayOrder(Request $request): JsonResponse
    {
        $request->validate([
            'shipping_address_id' => 'required|integer',
            'billing_address_id' => 'required|integer',
            'payment_method_id' => 'required|integer|in:2',
            'coupon_code' => 'nullable|string',
        ]);
        try {
            $userId = auth()->id();

            $cart = Cart::where('user_id', $userId)
                ->with([
                    'items.product:id,name,image_url',
                    'items.variant:id,image_url'
                ])
                ->firstOrFail();
            $shippingAddress = UserAddress::where('id', $request->shipping_address_id)
                ->where('user_id', $userId)
                ->where('type', 'shipping')
                ->firstOrFail();

            $billingAddress = UserAddress::where('id', $request->billing_address_id)
                ->where('user_id', $userId)
                ->firstOrFail();

            if ($cart->items->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty'
                ], 422);
            }

            $subtotal = $cart->items->sum('subtotal');
            $settings = DeliverySetting::getSettings();
            if (!$settings) {
                throw new \Exception('Delivery settings not configured');
            }
            $discount = $this->calculateDiscount($request->coupon_code, $subtotal);

            $afterDiscount = max($subtotal - $discount, 0);

            $shippingData = $this->getShiprocketShipping(
                $shippingAddress,
                'razorpay'
            );

            $shipping = (float) $shippingData['shipping'];

            $estimatedDeliveryDate = !empty($shippingData['delivery_date'])
                ? \Carbon\Carbon::parse($shippingData['delivery_date'])->toDateString()
                : null;

            $platformFee = $settings->platform_fee;

            $tax = ($afterDiscount * $settings->tax_percent) / 100;

            $total = round($afterDiscount + $shipping + $platformFee + $tax, 2);


            $payment = Payment::create([
                'user_id' => $userId,
                'amount' => (float) $total,
                'currency' => 'INR',
                'payment_method' => 'razorpay',
                'payment_status' => 'pending',
                'client_ip' => $request->ip(),
                'coupon_code' => $request->coupon_code,
                'payment_meta' => [
                    'shipping_address_id' => $shippingAddress->id,
                    'billing_address_id' => $billingAddress->id,
                    'shipping' => $shipping,
                    'estimated_delivery_date' => $estimatedDeliveryDate,
                ],
            ]);


            $razorpay = new Api(
                config('services.razorpay.key'),
                config('services.razorpay.secret')
            );
            $razorpayOrder = $razorpay->order->create([
                'receipt' => 'pay_' . $payment->id,
                'amount' => (int) round($total * 100),
                'currency' => 'INR',
                'notes' => [
                    'payment_id' => $payment->id,
                    'user_id' => $userId
                ]
            ]);

            $payment->update([
                'razorpay_order_id' => $razorpayOrder['id']
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'payment_id' => $payment->id,
                    'razorpay_order_id' => $razorpayOrder['id'],
                    'amount' => (float) $total,
                    'currency' => 'INR',
                ]
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }
    public function verifyRazorpayPayment(Request $request): JsonResponse
    {
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        DB::beginTransaction();

        try {

            $userId = auth()->id();

            $payment = Payment::where('razorpay_order_id', $request->razorpay_order_id)
                ->where('user_id', $userId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($payment->payment_status === 'paid') {

                DB::rollBack();

                return response()->json([
                    'success' => true,
                    'message' => 'Payment already processed'
                ]);
            }

            $api = new Api(
                config('services.razorpay.key'),
                config('services.razorpay.secret')
            );

            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ]);

            $paymentDetails = $api->payment->fetch($request->razorpay_payment_id);
            $orderDetails = $api->order->fetch($request->razorpay_order_id);

            if ($orderDetails['status'] !== 'paid') {
                throw new \Exception('Order not paid yet');
            }

            if ($paymentDetails['status'] !== 'captured') {
                throw new \Exception('Payment not captured');
            }

            if ($paymentDetails['currency'] !== 'INR') {
                throw new \Exception('Invalid currency');
            }

            if ($paymentDetails['amount'] != ($payment->amount * 100)) {
                throw new \Exception('Amount mismatch');
            }

            if ($payment->order_id) {

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Order already created',
                    'data' => [
                        'order_id' => $payment->order_id
                    ]
                ]);
            }

            $cart = Cart::where('user_id', $userId)
                ->with([
                    'items.product:id,name,image_url',
                    'items.variant:id,image_url'
                ])
                ->firstOrFail();

            if ($cart->items->isEmpty()) {
                throw new \Exception('Cart empty');
            }

            $subtotal = $cart->items->sum('subtotal');

            $settings = DeliverySetting::getSettings();

            $discount = $this->calculateDiscount($payment->coupon_code, $subtotal);

            $afterDiscount = max($subtotal - $discount, 0);

            $meta = $payment->payment_meta;

            if (!is_array($meta) || !array_key_exists('shipping', $meta)) {
                throw new \RuntimeException('Shipping amount missing from payment.');
            }

            $shipping = (float) $meta['shipping'];
            $estimatedDeliveryDate = $meta['estimated_delivery_date'] ?? null;

            if ($shipping < 0) {
                throw new \RuntimeException('Invalid shipping amount.');
            }

            $platformFee = $settings->platform_fee;

            $tax = ($afterDiscount * $settings->tax_percent) / 100;

            $total = round($afterDiscount + $shipping + $platformFee + $tax, 2);

            // $meta = $payment->payment_meta;

            $order = Order::create([
                'user_id' => $userId,
                'order_number' => 'ORD-' . now()->format('Ymd') . '-' . rand(1000, 9999),
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'estimated_delivery_date' => $estimatedDeliveryDate,
                'discount' => $discount,
                'platform_fee' => $platformFee,
                'total' => $total,
                'shipping_address_id' => $meta['shipping_address_id'],
                'billing_address_id' => $meta['billing_address_id'],
                'payment_method' => 'razorpay',
                'razorpay_order_id' => $payment->razorpay_order_id,
            ]);
            if ($payment->coupon_code) {

                $coupon = Coupon::where('code', strtoupper($payment->coupon_code))->first();

                if ($coupon) {

                    $coupon->increment('used_count');
                    $coupon->refresh();

                    if (
                        $coupon->usage_limit &&
                        $coupon->used_count >= $coupon->usage_limit
                    ) {
                        $coupon->update([
                            'is_active' => false
                        ]);
                    }
                }
            }
            foreach ($cart->items as $item) {

                $image = $item->image
                    ?? $item->variant->image_url
                    ?? $item->product->image_url;

                        if ($image && str_starts_with($image, 'http')) {
                            $image = ltrim(parse_url($image, PHP_URL_PATH), '/');
                        }

                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $item->product_id,
                            'variant_id' => $item->product_variant_id,
                            'product_name' => $item->product->name ?? 'Product',
                            'price' => $item->price,
                            'quantity' => $item->quantity,
                            'subtotal' => $item->subtotal,
                            'image' => $image,
                        ]);
                \App\Models\PlatformPricing::where('product_variant_id', $item->product_variant_id)
                    ->decrement('quantity', $item->quantity);

                // \App\Models\PlatformProduct::where('product_variant_id', $item->product_variant_id)
                //     ->decrement('platform_stock', $item->quantity);
            }

            $payment->update([
                'order_id' => $order->id,
                'payment_status' => 'paid',
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
                'razorpay_payload' => $paymentDetails->toArray(),
                'paid_at' => now()
            ]);
            // Payment Activity
            ActivityLogService::log(
                auth()->user(),
                'payment',
                $payment->id,
                'success',
                [
                    'payment_method' => 'Razorpay',
                    'transaction_id' => $request->razorpay_payment_id,
                    'amount' => $payment->amount,
                    'order_number' => $order->order_number,
                ]
            );

            // Order Activity
            ActivityLogService::log(
                auth()->user(),
                'order',
                $order->id,
                'success',
                [
                    'payment_method' => 'Razorpay',
                    'order_number' => $order->order_number,
                    'amount' => $order->total,
                ]
            );
            $cart->items()->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment verified & order placed',
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number
                ]
            ]);

        } catch (SignatureVerificationError $e) {

            DB::rollBack();

            if (isset($payment)) {
                $payment->update(['payment_status' => 'failed']);
            }

            return response()->json([
                'success' => false,
                'message' => 'Signature verification failed'
            ], 400);

        } catch (Throwable $e) {

            DB::rollBack();

            if (isset($payment)) {
                $payment->update(['payment_status' => 'failed']);
            }

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function razorpayWebhook(Request $request)
    {
        $secret = config('services.razorpay.webhook_secret');

        $payload = $request->getContent();
        $signature = $request->header('X-Razorpay-Signature');

        try {

            $api = new Api(
                config('services.razorpay.key'),
                config('services.razorpay.secret')
            );

            $api->utility->verifyWebhookSignature($payload, $signature, $secret);

            $data = json_decode($payload, true);

            if ($data['event'] === 'payment.captured') {

                $paymentId = $data['payload']['payment']['entity']['id'];
                $orderId = $data['payload']['payment']['entity']['order_id'];
                $status = $data['payload']['payment']['entity']['status'];

                if ($status !== 'captured') {
                    return response()->json(['status' => 'ignored']);
                }

                DB::transaction(function () use ($orderId, $paymentId, $data) {

                    $payment = Payment::where('razorpay_order_id', $orderId)
                        ->lockForUpdate()
                        ->firstOrFail();
                    if ($payment && $payment->payment_status !== 'paid') {

                        $payment->update([
                            'razorpay_payment_id' => $paymentId,
                            'payment_status' => 'paid',
                            'razorpay_payload' => $data,
                            'paid_at' => now()
                        ]);
                    }
                });
            }

            return response()->json(['status' => 'ok']);

        } catch (Throwable $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
