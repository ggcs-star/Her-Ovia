<?php

namespace App\Services\Search;

use App\Models\Category;
use App\Models\Platform;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ProductSearchService
{
    public function suggestions(string $query, int $limit = 6): array
    {
        $query = $this->normalize($query);

        if ($query === '' || mb_strlen($query) < 2) {
            return [
                'products' => [],
                'categories' => [],
                'subcategories' => [],
                'brands' => [],
            ];
        }

        $cacheKey = 'search_suggestions_' . md5($query . '_' . $limit);

        return Cache::remember(
            $cacheKey,
            now()->addSeconds(30),
            function () use ($query, $limit) {
                $platform = Platform::getOwnWebsite();

                if (!$platform) {
                    return [
                        'products' => [],
                        'categories' => [],
                        'subcategories' => [],
                        'brands' => [],
                    ];
                }

                $tokens = $this->tokens($query);

                $products = $this->searchProducts(
                    $query,
                    $platform,
                    $limit
                );

                $allCategories = $this->searchCategories(
                    $query,
                    $tokens,
                    $limit
                );

                $brands = $this->searchBrands(
                    $query,
                    $tokens,
                    $platform,
                    $limit
                );

                return [
                    'products' => $products,
                    'categories' => $allCategories
                        ->whereNull('parent_id')
                        ->values(),
                    'subcategories' => $allCategories
                        ->whereNotNull('parent_id')
                        ->values(),
                    'brands' => $brands,
                ];
            }
        );
    }

    public function search(string $query, int $limit = 12): Collection
    {
        $query = $this->normalize($query);

        if ($query === '' || mb_strlen($query) < 2) {
            return collect();
        }

        $platform = Platform::getOwnWebsite();

        if (!$platform) {
            return collect();
        }

        return $this->searchProducts(
            $query,
            $platform,
            $limit
        );
    }

    private function searchProducts(
        string $query,
        Platform $platform,
        int $limit
    ): Collection {
        return Product::search($query)
            ->take($limit)
            ->get()
            ->filter(
                fn (Product $product) =>
                    $product->platformListings()
                        ->where('platform_id', $platform->id)
                        ->userVisible()
                        ->exists()
            )
            ->values();
    }

    private function searchCategories(
        string $query,
        array $tokens,
        int $limit
    ): Collection {
        $categories = Category::query()
            ->with(['parent:id,name,slug'])
            ->where('status', 'active')
            ->where('visibility', 'public')
            ->where(
                function ($builder) use ($query, $tokens) {
                    $builder->where('name', 'LIKE', "%{$query}%");

                    foreach ($tokens as $token) {
                        $builder->orWhere(
                            'name',
                            'LIKE',
                            "%{$token}%"
                        );

                        if (mb_strlen($token) >= 3) {
                            $prefix = mb_substr($token, 0, 3);

                            $builder->orWhere(
                                'name',
                                'LIKE',
                                "%{$prefix}%"
                            );
                        }
                    }
                }
            )
            ->select([
                'id',
                'name',
                'slug',
                'parent_id',
            ])
            ->limit(50)
            ->get();

        return $categories
            ->map(
                function (Category $category) use ($query, $tokens) {
                    $category->search_score = $this->categoryScore(
                        $category,
                        $query,
                        $tokens
                    );

                    return $category;
                }
            )
            ->filter(
                fn (Category $category) =>
                    $category->search_score >= 80
            )
            ->sortByDesc('search_score')
            ->take($limit)
            ->values();
    }

    private function searchBrands(
        string $query,
        array $tokens,
        Platform $platform,
        int $limit
    ): Collection {
        $brands = Product::query()
            ->whereHas(
                'platformListings',
                function ($query) use ($platform) {
                    $query
                        ->where('platform_id', $platform->id)
                        ->userVisible();
                }
            )
            ->whereNotNull('brand')
            ->where(
                function ($builder) use ($query, $tokens) {
                    $builder->where(
                        'brand',
                        'LIKE',
                        "%{$query}%"
                    );

                    foreach ($tokens as $token) {
                        $builder->orWhere(
                            'brand',
                            'LIKE',
                            "%{$token}%"
                        );

                        if (mb_strlen($token) >= 3) {
                            $prefix = mb_substr($token, 0, 3);

                            $builder->orWhere(
                                'brand',
                                'LIKE',
                                "%{$prefix}%"
                            );
                        }
                    }
                }
            )
            ->select('brand')
            ->distinct()
            ->limit(30)
            ->pluck('brand');

        return $brands
            ->filter()
            ->unique()
            ->map(
                function ($brand) use ($query, $tokens) {
                    return [
                        'name' => $brand,
                        'search_score' => $this->textScore(
                            $brand,
                            $query,
                            $tokens
                        ),
                    ];
                }
            )
            ->filter(
                fn ($brand) =>
                    $brand['search_score'] >= 80
            )
            ->sortByDesc('search_score')
            ->take($limit)
            ->values();
    }

    private function categoryScore(
        Category $category,
        string $query,
        array $tokens
    ): int {
        $name = $this->normalize($category->name);
        $parent = $this->normalize($category->parent?->name);

        $score = $this->textScore(
            $name,
            $query,
            $tokens
        );

        $score += (int) (
            $this->textScore(
                $parent,
                $query,
                $tokens
            ) * 0.7
        );

        return $score;
    }

    private function textScore(
        ?string $text,
        string $query,
        array $tokens = []
    ): int {
        $text = $this->normalize($text);

        if ($text === '') {
            return 0;
        }

        $score = 0;

        if ($text === $query) {
            $score += 1000;
        }

        if (str_contains($text, $query)) {
            $score += 600;
        }

        foreach ($tokens as $token) {
            if (str_contains($text, $token)) {
                $score += 180;
            }
        }

        return $score;
    }

    private function normalize(?string $value): string
    {
        $value = mb_strtolower(trim((string) $value));
        $value = preg_replace(
            '/[^\p{L}\p{N}\s]+/u',
            ' ',
            $value
        );

        return trim(
            preg_replace('/\s+/', ' ', $value)
        );
    }

    private function tokens(string $query): array
    {
        return collect(
            preg_split(
                '/\s+/',
                $query,
                -1,
                PREG_SPLIT_NO_EMPTY
            )
        )
            ->map(fn ($token) => trim($token))
            ->filter(
                fn ($token) => mb_strlen($token) >= 2
            )
            ->unique()
            ->values()
            ->all();
    }
}