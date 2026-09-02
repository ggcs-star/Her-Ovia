<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Throwable;
use App\Models\Platform;
use App\Transformers\ProductListTransformer;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $categories = Category::query()
                ->whereNull('parent_id')
                ->where('visibility', 'public')
                ->where('status', 'active')
                ->orderBy('sort_order')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $categories->map(fn ($cat) => $this->formatCategory($cat)),
            ]);

        } catch (Throwable $e) {
            Log::error('Category List API Error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch categories'
            ], 500);
        }
    }

    public function show(int $category_id): JsonResponse
    {
        try {
            $category = Category::query()
                ->where('id', $category_id)
                ->where('visibility', 'public')
                ->where('status', 'active')
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $this->formatCategory($category),
            ]);

        } catch (Throwable $e) {
            Log::error('Category Detail API Error', [
                'message' => $e->getMessage(),
                'category_id' => $category_id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404);
        }
    }

    public function products(Category $category): JsonResponse
    {
        $platform = Platform::getOwnWebsite();

        $products = $category->products()
            ->leftJoin('product_clicks', 'products.id', '=', 'product_clicks.product_id')
            ->whereHas('platformListings', fn ($q) =>
                $q->where('platform_id', $platform->id)->userVisible()
            )
            ->with([
                'category:id,name',
                'variants.platformPricings' => fn ($q) =>
                    $q->where('status', 'active'),
            ])
            ->select(
                'products.*',
                DB::raw('COALESCE(product_clicks.click_count,0) as click_count')
            )
            ->orderByDesc('click_count')
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => [
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                ],
                'products' => $products->getCollection()
                    ->map(fn ($p) => ProductListTransformer::transform($p)),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'total' => $products->total(),
                ],
            ],
        ]);
    }

    private function formatCategory($category)
    {
        $children = $category->children()
            ->where('visibility', 'public')
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'image_url' => $category->image_url,
            'created_at' => $category->created_at,
            'updated_at' => $category->updated_at,
            'children' => $children->map(fn ($child) => $this->formatCategory($child)),
        ];
    }
}