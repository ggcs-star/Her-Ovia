<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use App\Helpers\S3Helper;
use App\Models\AmazonOrder;  

class OrderController extends Controller
{

public function index(Request $request)
{
    $source = $request->get('source', '');
    $platform = $request->get('platform');  

    if ($source === 'amazon' || $platform === 'amazon') {

        $query = AmazonOrder::with('items');
        if ($request->filled('date_range')) {

            if ($request->date_range == 'today') {
                $query->whereDate('purchase_date', today());
            }

            if ($request->date_range == 'week') {
                $query->whereBetween('purchase_date', [
                    now()->startOfWeek(),
                    now()->endOfWeek(),
                ]);
            }

            if ($request->date_range == 'month') {
                $query->whereMonth('purchase_date', now()->month)
                    ->whereYear('purchase_date', now()->year);
            }
        }

        if ($request->filled('search')) {
            $query->where('amazon_order_id', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        $orders = $query->orderByDesc('purchase_date')->paginate(10)->withQueryString();

        $stats = [
            'total' => AmazonOrder::count(),
            'pending' => AmazonOrder::where('order_status', 'Pending')->count(),
            'confirmed' => 0,
            'processing' => 0,
            'shipped' => AmazonOrder::where('order_status', 'Shipped')->count(),
            'delivered' => AmazonOrder::where('order_status', 'Delivered')->count(),
            'cancelled' => AmazonOrder::whereIn('order_status', ['Canceled', 'Cancelled'])->count(),
        ];

    } else if (empty($platform) || $platform === 'all' || $platform === '') {

        $amazonQuery = AmazonOrder::with('items');
        if ($request->filled('date_range')) {

            if ($request->date_range == 'today') {
                $amazonQuery->whereDate('purchase_date', today());
            }

            if ($request->date_range == 'week') {
                $amazonQuery->whereBetween('purchase_date', [
                    now()->startOfWeek(),
                    now()->endOfWeek(),
                ]);
            }

            if ($request->date_range == 'month') {
                $amazonQuery->whereMonth('purchase_date', now()->month)
                            ->whereYear('purchase_date', now()->year);
            }
        }
        if ($request->filled('search')) {
            $amazonQuery->where('amazon_order_id', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $amazonQuery->where('order_status', $request->status);
        }
        $amazonOrders = $amazonQuery->orderByDesc('purchase_date')->get();
   

        $websiteQuery = Order::with('user');
        if ($request->filled('date_range')) {

            if ($request->date_range == 'today') {
                $websiteQuery->whereDate('created_at', today());
            }

            if ($request->date_range == 'week') {
                $websiteQuery->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek(),
                ]);
            }

            if ($request->date_range == 'month') {
                $websiteQuery->whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year);
            }
        }
        if ($request->filled('search')) {
            $websiteQuery->where('order_number', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $websiteQuery->where('status', $request->status);
        }
        $websiteOrders = $websiteQuery->latest()->get();

        $amazonOrders = $amazonOrders->map(function ($order) {
            $order->setAttribute('is_amazon', true);
            return $order;
        })->values();

        $websiteOrders = $websiteOrders->map(function ($order) {
            $order->setAttribute('is_amazon', false);
            return $order;
        })->values();

        $allOrders = collect(array_merge(
            $amazonOrders->all(),
            $websiteOrders->all()
        ));     

        $allOrders = $allOrders->sortByDesc(function($order) {
            return $order->purchase_date ?? $order->created_at;
        });
        

        $perPage = 10;
        $currentPage = request()->get('page', 1);
        $items = $allOrders->values()->all();
        $totalItems = count($items);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedItems = array_slice($items, $offset, $perPage);

        $orders = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems,
            $totalItems,
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $stats = [
            'total' => Order::count() + AmazonOrder::count(),
            'pending' => Order::where('status', 'pending')->count() + AmazonOrder::where('order_status', 'Pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count() + AmazonOrder::where('order_status', 'Shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count() + AmazonOrder::where('order_status', 'Delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count() + AmazonOrder::whereIn('order_status', ['Canceled', 'Cancelled'])->count(),
        ];

    } else {

        $query = Order::with('user');
        if ($request->filled('date_range')) {

            if ($request->date_range == 'today') {
                $query->whereDate('created_at', today());
            }

            if ($request->date_range == 'week') {
                $query->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek(),
                ]);
            }

            if ($request->date_range == 'month') {
                $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
            }
        }

        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];
    }

        $platformStats = [
            'website' => [
                'orders' => Order::where('platform', 'website')->count(),
                'total' => Order::where('platform', 'website')->sum('total'),
            ],
            'amazon' => [
                'orders' => AmazonOrder::count(),
                'total' => AmazonOrder::sum('order_total'),
            ],
            'flipkart' => [
                'orders' => Order::where('platform', 'flipkart')->count(),
                'total' => Order::where('platform', 'flipkart')->sum('total'),
            ],
            'total_revenue' => Order::sum('total') + AmazonOrder::sum('order_total'),
        ];
    

        return view('admin.orders.index', compact(
            'orders',
            'stats',
            'platformStats',
            'source'
        ));
    }

    public function show($id)
{
    $order = Order::with([
        'items.product',
        'items.variant',
        'user',
        'shippingAddress',
        'billingAddress',
        'payment'
    ])->findOrFail($id);

    return view('admin.orders.show', compact('order'));
}

    public function updateStatus(Request $request, $id)
    {
        $order = Order::find($id);
        
        if (!$order) {
            return back()->with('error', 'Amazon orders cannot be updated from here');
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled'
        ]);

        $currentStatus = strtolower(trim($order->status));
        $newStatus = strtolower(trim($request->status));

        if ($currentStatus === $newStatus) {
            return back()->with('error', 'Status is already ' . ucfirst($currentStatus));
        }

        $allowedTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['processing', 'cancelled'],
            'processing' => ['shipped'],
            'shipped' => ['delivered'],
        ];

        if (isset($allowedTransitions[$currentStatus]) && !in_array($newStatus, $allowedTransitions[$currentStatus])) {
            return back()->with('error', 'Invalid status transition');
        }

        $order->status = $newStatus;

        if ($newStatus === 'confirmed') {
            $order->confirmed_at = now();
        }
        if ($newStatus === 'shipped') {
            $order->shipped_at = now();
        }
        if ($newStatus === 'delivered') {
            $order->delivered_at = now();
            if ($order->payment_method_id == 1 || $order->payment_method == 'cod') {
                $order->payment_status = 'paid';
            }
        }

        $order->save();

        if ($newStatus === 'confirmed') {
            foreach ($order->items as $item) {
                $variant = ProductVariant::find($item->variant_id);
                if ($variant) {
                    $variant->decrement('quantity', $item->quantity);
                    StockMovement::create([
                        'product_id' => $item->product_id,
                        'variant_id' => $item->variant_id,
                        'platform_id' => 1,
                        'movement' => 'OUT',
                        'quantity' => $item->quantity,
                        'balance' => $variant->quantity,
                        'reference_type' => 'order',
                        'reference_id' => $order->id,
                        'remarks' => 'Order confirmed',
                    ]);
                }
            }
        }

        return back()->with('success', 'Order status updated to ' . ucfirst($newStatus));
    }

    public function invoice($id)
    {
        $order = Order::with([
            'items.product',
            'items.variant',
            'user',
            'shippingAddress',
            'payment'
        ])->findOrFail($id);

        $company = Organization::first();

        return view('admin.orders.invoice', compact('order', 'company'));
    }

    public function cancel($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status === 'delivered') {
            return back()->with('error', 'Delivered order cannot be cancelled');
        }

        $order->status = 'cancelled';
        $order->save();

        return back()->with('success', 'Order cancelled successfully');
    }

    public function confirm($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status === 'pending') {
            $order->update(['status' => 'confirmed']);
        }

        return redirect()->route('admin.orders.show', $order->id);
    }
}