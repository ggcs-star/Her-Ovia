<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\S3Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductReviewRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use App\Services\ProductReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductReviewController extends Controller
{
    public function __construct(
        private readonly ProductReviewService $reviewService
    ) {
    }

    public function index(Request $request)
    {
        $query = ProductReview::query()
            ->with([
                'product:id,name,image_url',
                'user:id,name,email',
                'order:id,order_number',
            ]);

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('review', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('product', function ($productQuery) use ($search) {
                        $productQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('order', function ($orderQuery) use ($search) {
                        $orderQuery->where('order_number', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->integer('product_id'));
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->integer('rating'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $reviews = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $products = Product::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $stats = [
            'total' => ProductReview::count(),
            'rated' => ProductReview::whereNotNull('rating')->count(),
        ];

        return view('admin.product-reviews.index', compact(
            'reviews',
            'products',
            'stats'
        ));
    }

    public function create()
    {
        $products = Product::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $users = User::query()
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        $orders = Order::query()
            ->select('id', 'order_number', 'user_id')
            ->where('platform', 'website')
            ->latest()
            ->get();

        return view('admin.product-reviews.create', compact(
            'products',
            'users',
            'orders'
        ));
    }

    public function store(ProductReviewRequest $request)
    {
        $data = $request->validated();

        $user = !empty($data['user_id'])
            ? User::find($data['user_id'])
            : null;

        $data['title'] = $this->reviewService->resolveTitle(
            $data['rating'] ?? null,
            $user?->name
        );

        $data['images'] = [];
        $data['video'] = null;

        DB::transaction(function () use ($request, &$data) {
            if ($request->hasFile('images')) {
                $data['images'] = $this->reviewService->uploadImages(
                    $request->file('images'),
                    (int) $data['product_id']
                );
            }

            if ($request->hasFile('video')) {
                $data['video'] = $this->reviewService->uploadVideo(
                    $request->file('video'),
                    (int) $data['product_id']
                );
            }
            unset($data['status'], $data['approved_at']);

            ProductReview::create($data);
        });

        return redirect()
            ->route('admin.product-reviews.index')
            ->with('success', 'Review created successfully.');
    }

    public function show(ProductReview $productReview)
    {
        $productReview->load([
            'product:id,name,image_url',
            'user:id,name,email',
            'order:id,order_number,status',
        ]);

        return view(
            'admin.product-reviews.show',
            compact('productReview')
        );
    }

    public function edit(ProductReview $productReview)
    {
        $productReview->load([
            'product:id,name',
            'user:id,name,email',
            'order:id,order_number,user_id',
        ]);

        $products = Product::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $users = User::query()
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        $orders = Order::query()
            ->select('id', 'order_number', 'user_id')
            ->where('platform', 'website')
            ->latest()
            ->get();

        return view('admin.product-reviews.edit', compact(
            'productReview',
            'products',
            'users',
            'orders'
        ));
    }

    public function update(
        ProductReviewRequest $request,
        ProductReview $productReview
    ) {
        $data = $request->validated();

        $user = !empty($data['user_id'])
            ? User::find($data['user_id'])
            : null;

        $data['title'] = $this->reviewService->resolveTitle(
            $data['rating'] ?? null,
            $user?->name
        );

        $oldImages = $productReview->images ?? [];
        $oldVideo = $productReview->video;

        DB::transaction(function () use (
            $request,
            $productReview,
            &$data,
            $oldImages,
            $oldVideo
        ) {
            if ($request->hasFile('images')) {
                $data['images'] = $this->reviewService->uploadImages(
                    $request->file('images'),
                    (int) $data['product_id']
                );
            } else {
                $data['images'] = $oldImages;
            }

            if ($request->hasFile('video')) {
                $data['video'] = $this->reviewService->uploadVideo(
                    $request->file('video'),
                    (int) $data['product_id']
                );
            } else {
                $data['video'] = $oldVideo;
            }

            unset($data['status'], $data['approved_at']);

            $productReview->update($data);
        });

        if ($request->hasFile('images') && !empty($oldImages)) {
            $this->reviewService->deleteImages($oldImages);
        }

        if ($request->hasFile('video') && $oldVideo) {
            S3Helper::delete($oldVideo);
        }

        return redirect()
            ->route('admin.product-reviews.index')
            ->with('success', 'Review updated successfully.');
    }

    public function destroy(ProductReview $productReview)
    {
        DB::transaction(function () use ($productReview) {
            $this->reviewService->deleteMedia($productReview);

            $productReview->delete();
        });

        return redirect()
            ->route('admin.product-reviews.index')
            ->with('success', 'Review deleted successfully.');
    }


    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:product_reviews,id'],
        ]);

        $reviews = ProductReview::whereIn('id', $validated['ids'])->get();

        DB::transaction(function () use ($reviews) {
            foreach ($reviews as $review) {
                $this->reviewService->deleteMedia($review);
                $review->delete();
            }
        });

        return back()->with(
            'success',
            'Selected reviews deleted successfully.'
        );
    }
}