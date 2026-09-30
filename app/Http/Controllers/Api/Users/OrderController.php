<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;
use App\Helpers\S3Helper;
use App\Models\Organization;
use Barryvdh\DomPDF\Facade\Pdf;
class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $orders = Order::with([
                'items' => function($query) {
                    $query->select('id', 'order_id', 'product_id', 'variant_id', 'product_name', 'price', 'quantity', 'subtotal', 'image');
                },
                'items.product:id,image_url,gallery_images', 
                'items.variant:id,image_url'
            ])
            ->where('user_id', auth()->id())
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

            $orders->getCollection()->transform(function ($order) {
            $order->items->transform(function ($item) {

                if ($item->image && !str_starts_with($item->image, 'http')) {
                    $item->image = S3Helper::url($item->image);
                }

                return $item;
            });

            return $order;
        });

            return response()->json([
                'success' => true,
                'data' => $orders
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($orderId): JsonResponse
    {
        try {
            $order = Order::with([
                'items' => function($query) {
                    $query->select('id', 'order_id', 'product_id', 'variant_id', 'product_name', 'price', 'quantity', 'subtotal', 'image');
                },
                'items.product:id,image_url,gallery_images', 
                'items.variant:id,image_url'
                
            ])
            ->where('user_id', auth()->id())
            ->findOrFail($orderId);
                $order->items->transform(function ($item) {

            if ($item->image && !str_starts_with($item->image, 'http')) {
                $item->image = S3Helper::url($item->image);
            }

            return $item;
        });

            return response()->json([
                'success' => true,
                'data' => $order
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }
    public function invoice($orderId)
    {
        try {
            $order = Order::with([
                'items.product',
                'items.variant',
                'user',
                'shippingAddress',
                'billingAddress',
                'payment'
            ])
            ->where('user_id', auth()->id())
            ->findOrFail($orderId);

            $company = Organization::first();

            $pdf = Pdf::loadView(
                'invoices.order-invoice',
                compact('order', 'company')
            )
            ->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', true);

            return response($pdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . $order->order_number . '-invoice.pdf"');

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }
    public function cancel(Request $request, $orderId): JsonResponse
    {
        try {
            DB::beginTransaction();

            $order = Order::where('user_id', auth()->id())
                ->findOrFail($orderId);

            if (!in_array($order->status, ['pending', 'confirmed'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order cannot be cancelled'
                ], 400);
            }

            $order->status = 'cancelled';
            $order->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully',
                'data' => [
                    'order' => [
                        'id' => $order->id,
                        'status' => $order->status
                    ]
                ]
            ]);
            
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
            
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }

    public function track($orderId): JsonResponse
    {
        try {
            $order = Order::where('user_id', auth()->id())
                ->findOrFail($orderId);
            return response()->json([
                'success' => true,
                'data' => [
                    'order_number' => $order->order_number,
                    'status' => $order->status,
                    'tracking_number' => $order->tracking_number ?? null,
                    'estimated_delivery' => $order->estimated_delivery ?? null,
                ]
            ]);
            
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
            
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }
}
