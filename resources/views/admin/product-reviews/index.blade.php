@extends('layouts.admin.admin-settings')

@section('settings-content')
<style>
    .product-reviews-page {
        width: 100%;
        max-width: 100%;
        min-width: 0;
        overflow: hidden;
    }

    .product-reviews-content {
        width: 100%;
        max-width: 100%;
        min-width: 0;
        box-sizing: border-box;
    }

    .review-filter-grid {
        display: grid;
        grid-template-columns: minmax(280px, 2fr) repeat(4, minmax(150px, 1fr));
        gap: 16px;
        width: 100%;
        max-width: 100%;
        min-width: 0;
    }

    .review-filter-field {
        width: 100%;
        min-width: 0;
    }

    .review-filter-field input,
    .review-filter-field select {
        display: block;
        width: 100%;
        max-width: 100%;
        min-width: 0;
        box-sizing: border-box;
    }

    .review-results {
        width: 100%;
        max-width: 100%;
        min-width: 0;
        overflow: hidden;
    }

    .review-table-wrapper {
        width: 100%;
        max-width: 100%;
        min-width: 0;
        overflow: hidden;
    }

    .review-table {
        width: 100%;
        max-width: 100%;
        min-width: 0;
        table-layout: fixed;
        border-collapse: separate;
        border-spacing: 0;
    }

    .review-table th,
    .review-table td {
        min-width: 0;
        white-space: normal;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .review-table th:nth-child(1),
    .review-table td:nth-child(1) {
        width: 38px;
    }

    .review-table th:nth-child(2),
    .review-table td:nth-child(2) {
        width: 18%;
    }

    .review-table th:nth-child(3),
    .review-table td:nth-child(3) {
        width: 15%;
    }

    .review-table th:nth-child(4),
    .review-table td:nth-child(4) {
        width: 11%;
    }

    .review-table th:nth-child(5),
    .review-table td:nth-child(5) {
        width: 20%;
    }

    .review-table th:nth-child(6),
    .review-table td:nth-child(6) {
        width: 9%;
    }

    .review-table th:nth-child(7),
    .review-table td:nth-child(7) {
        width: 10%;
    }

    .review-table th:nth-child(8),
    .review-table td:nth-child(8) {
        width: 120px;
    }

    .review-text-column {
        white-space: normal !important;
        overflow: hidden;
    }

    .review-text-column > div {
        width: 100%;
        max-width: 100%;
        min-width: 0;
    }

    .review-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 5px;
        width: 100%;
        max-width: 100%;
        white-space: nowrap;
    }

    .review-action-btn {
        width: 34px;
        height: 34px;
        min-width: 34px;
        flex: 0 0 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e5e7eb;
        border-radius: 7px;
        background: #ffffff;
        transition: all 0.2s ease;
    }

    .review-action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    }

    .review-stat-card {
        position: relative;
        min-height: 142px;
        overflow: hidden;
        border: 1px solid #ead8e0;
        border-radius: 18px;
        background: linear-gradient(135deg, #fff8fa 0%, #f8e9ef 100%);
        box-shadow: 0 8px 24px rgba(92, 32, 58, 0.07);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .review-stat-card.rated {
        border-color: #eee1bd;
        background: linear-gradient(135deg, #fffdf5 0%, #fff5d9 100%);
    }

    .review-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(92, 32, 58, 0.10);
    }

    .review-stat-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 5px;
        background: #8B2452;
    }

    .review-stat-card.rated::before {
        background: #D9A441;
    }

    .review-stat-card::after {
        content: '';
        position: absolute;
        right: -35px;
        bottom: -45px;
        width: 135px;
        height: 135px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.42);
        pointer-events: none;
    }

    .review-stat-icon {
        position: relative;
        z-index: 1;
        width: 52px;
        height: 52px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 5px 14px rgba(92, 32, 58, 0.08);
    }

    .review-stat-card p {
        position: relative;
        z-index: 1;
    }

    .review-stat-card > div {
        position: relative;
        z-index: 1;
    }

    #reviewSearchLoader {
        display: none !important;
    }

    .review-avatar {
        width: 38px;
        height: 38px;
        min-width: 38px;
        flex: 0 0 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f4e7ed;
        color: #8B2452;
        font-size: 13px;
        font-weight: 700;
        border: 1px solid #ead3dc;
    }

    .review-filters {
        box-shadow: 0 8px 24px rgba(48, 27, 38, 0.05);
    }

    .review-results {
        box-shadow: 0 8px 24px rgba(48, 27, 38, 0.05);
    }

    .review-product-image {
        width: 46px;
        height: 56px;
        min-width: 46px;
        flex: 0 0 46px;
        object-fit: contain;
        object-position: center;
        border: 1px solid #eadde2;
        border-radius: 10px;
        background: #faf6f8;
    }

    .review-action-view {
        color: #8B2452;
    }

    .review-action-view:hover {
        background: #f9eef3;
        border-color: #e8cbd8;
    }

    .review-action-edit {
        color: #b7791f;
    }

    .review-action-edit:hover {
        background: #fff9eb;
        border-color: #f3dfaa;
    }

    .review-stars {
        display: inline-flex;
        align-items: center;
        gap: 1px;
        white-space: nowrap;
    }

    .review-pagination {
        width: 100%;
        max-width: 100%;
        min-width: 0;
        overflow: hidden;
    }

    .review-pagination nav {
        width: 100%;
        max-width: 100%;
    }

    .review-pagination svg {
        width: 16px;
        height: 16px;
    }

    @media (max-width: 1280px) {
        .review-filter-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .review-table th:nth-child(8),
        .review-table td:nth-child(8) {
            width: 150px;
        }

        .review-action-btn {
            width: 30px;
            height: 30px;
            min-width: 30px;
            flex-basis: 30px;
        }
    }

    @media (max-width: 900px) {
        .review-filter-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .review-table {
            font-size: 12px;
        }

        .review-table th,
        .review-table td {
            padding-left: 8px !important;
            padding-right: 8px !important;
        }

        .review-table th:nth-child(6),
        .review-table td:nth-child(6),
        .review-table th:nth-child(8),
        .review-table td:nth-child(8) {
            display: none;
        }

        .review-table th:nth-child(2),
        .review-table td:nth-child(2) {
            width: 20%;
        }

        .review-table th:nth-child(3),
        .review-table td:nth-child(3) {
            width: 17%;
        }

        .review-table th:nth-child(4),
        .review-table td:nth-child(4) {
            width: 12%;
        }

        .review-table th:nth-child(5),
        .review-table td:nth-child(5) {
            width: 25%;
        }

        .review-table th:nth-child(7),
        .review-table td:nth-child(7) {
            width: 12%;
        }

        .review-table th:nth-child(8),
        .review-table td:nth-child(8) {
            width: 150px;
        }
    }

    @media (max-width: 640px) {
        .review-filter-grid {
            grid-template-columns: 1fr;
        }

        .review-table th:nth-child(6),
        .review-table td:nth-child(6),
        .review-table th:nth-child(8),
        .review-table td:nth-child(8) {
            display: none;
        }

        .review-table th:nth-child(2),
        .review-table td:nth-child(2) {
            width: 22%;
        }

        .review-table th:nth-child(3),
        .review-table td:nth-child(3) {
            width: 18%;
        }

        .review-table th:nth-child(4),
        .review-table td:nth-child(4) {
            width: 12%;
        }

        .review-table th:nth-child(5),
        .review-table td:nth-child(5) {
            width: 24%;
        }

        .review-table th:nth-child(7),
        .review-table td:nth-child(7) {
            width: 12%;
        }

        .review-table th:nth-child(8),
        .review-table td:nth-child(8) {
            width: 135px;
        }

        .review-actions {
            justify-content: flex-start;
            gap: 3px;
        }

        .review-action-btn {
            width: 26px;
            height: 26px;
            min-width: 26px;
            flex-basis: 26px;
        }

        .review-action-btn svg {
            width: 13px;
            height: 13px;
        }

        .review-product-image {
            width: 34px;
            height: 34px;
            min-width: 34px;
            flex-basis: 34px;
        }

        .review-avatar {
            width: 28px;
            height: 28px;
            min-width: 28px;
            flex-basis: 28px;
        }
    }
</style>
<div class="product-reviews-page">
    <div class="product-reviews-content px-4 py-5 sm:px-6 lg:px-8">

        <div class="mb-6">
            <div class="mb-2 flex items-center gap-2 text-xs font-medium uppercase tracking-wide text-gray-400">
                <span>Pages</span>
                <span>
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                    </svg>
                </span>
                <span class="text-[#8B2452]">Product Reviews</span>
            </div>

            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">
                        Product Reviews
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Manage customer reviews, ratings and feedback.
                    </p>
                </div>

                <a href="{{ admin_route('product-reviews.create') }}"
                   class="inline-flex w-fit items-center gap-2 rounded-lg bg-[#8B2452] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#751d45]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Review
                </a>
            </div>
        </div>

        <div class="mb-6 grid grid-cols-1 gap-5 sm:grid-cols-2">

            <div class="review-stat-card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Reviews</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900">
                            {{ $stats['total'] }}
                        </p>
                    </div>

                    <div class="review-stat-icon bg-[#f4e7ed] text-[#8B2452]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 10h8M8 14h5m7-2a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z"/>
                        </svg>
                    </div>
                </div>
            </div>

           

            <div class="review-stat-card rated p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Rated Reviews</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900">
                            {{ $stats['rated'] }}
                        </p>
                    </div>

                    <div class="review-stat-icon bg-yellow-50 text-yellow-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linejoin="round" stroke-width="1.8"
                                  d="m12 4 2.3 4.7 5.2.8-3.8 3.7.9 5.2-4.6-2.4-4.6 2.4.9-5.2-3.8-3.7 5.2-.8L12 4Z"/>
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        <div class="review-filters mb-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <form id="reviewFilterForm"
                  action="{{ admin_route('product-reviews.index') }}"
                  method="GET">

                <div class="review-filter-grid">

                    <div class="review-filter-field">
                        <label for="reviewSearch"
                               class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Search
                        </label>

                        <div class="relative">
                            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="7" stroke-width="2"/>
                                <path stroke-linecap="round" stroke-width="2" d="m20 20-4-4"/>
                            </svg>

                            <input id="reviewSearch"
                                   name="search"
                                   type="text"
                                   value="{{ request('search') }}"
                                   placeholder="Search product, customer, email, order or review..."
                                   class="h-11 rounded-xl border border-gray-200 bg-white pl-10 pr-4 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/10">
                        </div>
                    </div>

                    <div class="review-filter-field">
                        <label for="reviewProduct"
                               class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Product
                        </label>

                        <select id="reviewProduct"
                                name="product_id"
                                class="review-filter h-11 rounded-xl border border-gray-200 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/10">

                            <option value="">All Products</option>

                            @foreach($products as $product)
                                <option value="{{ $product->id }}"
                                    {{ (string) request('product_id') === (string) $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="review-filter-field">
                        <label for="reviewRating"
                               class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Rating
                        </label>

                        <select id="reviewRating"
                                name="rating"
                                class="review-filter h-11 rounded-xl border border-gray-200 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/10">

                            <option value="">All Ratings</option>
                            <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                            <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                            <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>

                        </select>
                    </div>

                    <div class="review-filter-field">
                        <label for="reviewDateFrom"
                               class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-500">
                            From Date
                        </label>

                        <input id="reviewDateFrom"
                               name="date_from"
                               type="date"
                               value="{{ request('date_from') }}"
                               class="review-filter h-11 rounded-xl border border-gray-200 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/10">
                    </div>

                    <div class="review-filter-field">
                        <label for="reviewDateTo"
                               class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-500">
                            To Date
                        </label>

                        <input id="reviewDateTo"
                               name="date_to"
                               type="date"
                               value="{{ request('date_to') }}"
                               class="review-filter h-11 rounded-xl border border-gray-200 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/10">
                    </div>

                </div>

                <div class="mt-6 flex flex-wrap items-center gap-3">

                    <button type="submit"
                            class="inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-[#8B2452] px-6 text-sm font-semibold text-white transition hover:bg-[#751d45]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="m21 21-4.3-4.3M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"/>
                        </svg>
                        Apply Filters
                    </button>

                    <a id="resetReviewFilters"
                       href="{{ admin_route('product-reviews.index') }}"
                       class="inline-flex h-11 items-center justify-center rounded-lg border border-gray-200 bg-white px-6 text-sm font-semibold text-gray-600 transition hover:border-[#8B2452] hover:text-[#8B2452]">
                        Reset
                    </a>

                    <span id="reviewSearchLoader"
                          class="ml-1 hidden items-center gap-2 text-sm text-gray-500">
                        <svg class="h-4 w-4 animate-spin text-[#8B2452]"
                             fill="none"
                             viewBox="0 0 24 24">
                            <circle class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="9"
                                    stroke="currentColor"
                                    stroke-width="3"/>
                            <path class="opacity-75"
                                  fill="currentColor"
                                  d="M4 12a8 8 0 0 1 8-8v3a5 5 0 0 0-5 5H4Z"/>
                        </svg>
                        Loading...
                    </span>

                </div>

            </form>
        </div>

        <div id="reviewResults"
             class="review-results rounded-2xl border border-gray-200 bg-white shadow-sm">

            <div class="flex flex-col gap-4 border-b border-gray-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">

                <div class="flex items-center gap-3">
                    <label class="flex cursor-pointer items-center gap-2 text-sm font-medium text-gray-600">
                        <input id="selectAllReviews"
                               type="checkbox"
                               class="h-4 w-4 rounded border-gray-300 text-[#8B2452] focus:ring-[#8B2452]">
                        Select All
                    </label>

                    <button id="bulkDeleteReviews"
                            type="button"
                            class="hidden items-center rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-100">
                    </button>
                </div>

                <div class="text-sm text-gray-500">
                    Showing
                    <span class="font-semibold text-gray-700">
                        {{ $reviews->firstItem() ?? 0 }}
                    </span>
                    to
                    <span class="font-semibold text-gray-700">
                        {{ $reviews->lastItem() ?? 0 }}
                    </span>
                    of
                    <span class="font-semibold text-gray-700">
                        {{ $reviews->total() }}
                    </span>
                    reviews
                </div>

            </div>

            @if($reviews->count())

                <div class="review-table-wrapper">
                    <table class="review-table text-left">

                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50/70">
                                <th class="w-12 px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">
                                </th>

                                <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Product
                                </th>

                                <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Customer
                                </th>

                                <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Rating
                                </th>

                                <th class="review-text-column px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Review
                                </th>

                                <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Order
                                </th>

                                <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Date
                                </th>

                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @foreach($reviews as $review)

                                @php
                                    $customerName = $review->user?->name ?? 'Customer';
                                    $initial = strtoupper(substr(trim($customerName), 0, 1));
                                @endphp

                                <tr class="transition hover:bg-gray-50/60">

                                    <td class="px-4 py-4">
                                        <input type="checkbox"
                                               value="{{ $review->id }}"
                                               class="review-checkbox h-4 w-4 rounded border-gray-300 text-[#8B2452] focus:ring-[#8B2452]">
                                    </td>

                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($review->product?->image_url)
                                                <img src="{{ $review->product->image_url }}"
                                                     alt="{{ $review->product?->name }}"
                                                     class="review-product-image">
                                            @else
                                                <div class="review-product-image flex items-center justify-center text-[#8B2452]">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <rect x="4" y="4" width="16" height="16" rx="2" stroke-width="1.8"/>
                                                        <path stroke-linecap="round" stroke-width="1.8" d="m7 15 3-3 2 2 2-2 3 3"/>
                                                    </svg>
                                                </div>
                                            @endif

                                            <div class="min-w-0 max-w-[220px]">
                                                <p class="truncate text-sm font-semibold text-gray-800">
                                                    {{ $review->product?->name ?? 'Deleted Product' }}
                                                </p>

                                                @if($review->size || $review->color)
                                                    <p class="mt-1 text-xs text-gray-400">
                                                        @if($review->size)
                                                            Size: {{ $review->size }}
                                                        @endif

                                                        @if($review->size && $review->color)
                                                            <span class="mx-1">•</span>
                                                        @endif

                                                        @if($review->color)
                                                            Color: {{ $review->color }}
                                                        @endif
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="review-avatar">
                                                {{ $initial ?: 'C' }}
                                            </div>

                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-semibold text-gray-800">
                                                    {{ $customerName }}
                                                </p>

                                                @if($review->user?->email)
                                                    <p class="mt-0.5 max-w-[190px] truncate text-xs text-gray-400">
                                                        {{ $review->user->email }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-4 py-4">
                                        @if($review->rating)

                                            <div class="review-stars">
                                                @for($star = 1; $star <= 5; $star++)
                                                    <svg class="h-4 w-4 {{ $star <= $review->rating ? 'text-[#D9A441]' : 'text-gray-200' }}"
                                                         fill="currentColor"
                                                         viewBox="0 0 24 24">
                                                        <path d="m12 3.8 2.47 5 5.52.8-4 3.9.94 5.5L12 16.4l-4.93 2.6.94-5.5-4-3.9 5.52-.8L12 3.8Z"/>
                                                    </svg>
                                                @endfor
                                            </div>

                                            <p class="mt-1 text-xs font-semibold text-gray-500">
                                                {{ $review->rating }}/5
                                            </p>

                                        @else
                                            <span class="text-xs font-medium text-gray-400">
                                                No rating
                                            </span>
                                        @endif
                                    </td>

                                    <td class="review-text-column px-4 py-4">
                                        <div class="max-w-[340px]">
                                            @if($review->title)
                                                <p class="mb-1 text-sm font-semibold text-gray-800">
                                                    {{ $review->title }}
                                                </p>
                                            @endif

                                            <p class="line-clamp-2 text-sm leading-5 text-gray-500">
                                                {{ $review->review }}
                                            </p>
                                        </div>
                                    </td>

                                    <td class="px-4 py-4">
                                        @if($review->order)
                                            <span class="text-sm font-semibold text-[#8B2452]">
                                                {{ $review->order->order_number }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400">
                                                Admin / Seed
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm text-gray-600">
                                            {{ $review->created_at?->format('d M Y') }}
                                        </div>

                                        <div class="mt-0.5 text-xs text-gray-400">
                                            {{ $review->created_at?->format('h:i A') }}
                                        </div>
                                    </td>

                                    <td class="px-4 py-4">

                                        <div class="review-actions">

                                            <a href="{{ admin_route('product-reviews.show', $review) }}"
                                               class="review-action-btn review-action-view"
                                               title="View Review"
                                               aria-label="View Review">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7Z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                                </svg>
                                            </a>

                                            <a href="{{ admin_route('product-reviews.edit', $review) }}"
                                               class="review-action-btn review-action-edit"
                                               title="Edit Review"
                                               aria-label="Edit Review">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="m15.5 3.5 5 5L12 17H7v-5l8.5-8.5Z"/>
                                                </svg>
                                            </a>

                                            <button type="button"
                                                    class="review-action-btn review-action-delete delete-review"
                                                    data-delete-url="{{ admin_route('product-reviews.destroy', $review) }}"
                                                    title="Delete Review"
                                                    aria-label="Delete Review">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M6 7h12m-9 0V5h6v2m-7 0 1 13h6l1-13"/>
                                                </svg>
                                            </button>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>
                </div>

                <div class="review-pagination border-t border-gray-100 px-5 py-4 sm:px-6">
                    {{ $reviews->links() }}
                </div>

            @else

                <div class="px-6 py-16 text-center">

                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#f4e7ed] text-[#8B2452]">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M8 10h8M8 14h5m7-2a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z"/>
                        </svg>
                    </div>

                    <h3 class="mt-4 text-base font-semibold text-gray-800">
                        No reviews found
                    </h3>

                    <p class="mx-auto mt-1 max-w-md text-sm text-gray-500">
                        There are no product reviews matching your current filters.
                    </p>

                    <a href="{{ admin_route('product-reviews.create') }}"
                       class="mt-5 inline-flex items-center gap-2 rounded-lg bg-[#8B2452] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#751d45]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Review
                    </a>

                </div>

            @endif

        </div>

    </div>
</div>

<form id="singleDeleteReviewForm"
      method="POST"
      class="hidden">
    @csrf
    @method('DELETE')
</form>

<form id="bulkDeleteReviewForm"
      action="{{ admin_route('product-reviews.bulk-delete') }}"
      method="POST"
      class="hidden">
    @csrf
</form>

@push('scripts')
    <script src="{{ asset('assets/js/admin/product-reviews.js') }}"></script>
@endpush

@endsection