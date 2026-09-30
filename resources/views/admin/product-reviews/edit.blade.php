@extends('layouts.admin.admin-settings')

@section('settings-content')

<style>
.product-review-edit-page {
    color: #3f2632;
}

.product-review-edit-page .edit-hero {
    position: relative;
    overflow: hidden;
    padding: 24px;
    border: 1px solid #ead8e1;
    border-radius: 22px;
    background:
        radial-gradient(circle at 92% 18%, rgba(139,36,82,.13), transparent 25%),
        radial-gradient(circle at 78% 100%, rgba(217,164,65,.08), transparent 24%),
        linear-gradient(135deg, #fff9fb 0%, #ffffff 58%, #fcf0f5 100%);
    box-shadow: 0 10px 30px rgba(77,28,48,.06);
}

.product-review-edit-page .edit-hero::after {
    content: "";
    position: absolute;
    width: 180px;
    height: 180px;
    right: -65px;
    bottom: -85px;
    border-radius: 50%;
    background: rgba(139,36,82,.06);
    pointer-events: none;
}

.product-review-edit-page .edit-hero > div,
.product-review-edit-page .edit-hero > a {
    position: relative;
    z-index: 1;
}

.product-review-edit-page .edit-title {
    color: #5f1735;
    font-size: 28px;
    letter-spacing: -.035em;
}

.product-review-edit-page .breadcrumb-current {
    padding: 4px 10px;
    border-radius: 999px;
    background: #f5e5eb;
    color: #8b2452;
}

.product-review-edit-page .top-action {
    border-color: #ead8e1 !important;
    box-shadow: 0 4px 12px rgba(77,28,48,.04);
}

.product-review-edit-page .form-section {
    overflow: hidden;
    border: 1px solid #eadde3;
    border-radius: 20px;
    background: #fff;
    box-shadow: 0 8px 24px rgba(77,28,48,.055);
}

.product-review-edit-page .section-head {
    padding: 20px 24px;
    border-bottom: 1px solid #f0e5e9;
    background: linear-gradient(135deg, #fff 0%, #fff9fb 100%);
}

.product-review-edit-page .section-icon {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    box-shadow: 0 5px 14px rgba(77,28,48,.07);
}

.product-review-edit-page .field-label {
    color: #4b3440;
}

.product-review-edit-page .field-control {
    min-height: 46px;
    border-color: #eadde3 !important;
    border-radius: 12px !important;
    background: #fcfafb !important;
    box-shadow: inset 0 1px 2px rgba(77,28,48,.025);
}

.product-review-edit-page .field-control:focus {
    border-color: #8b2452 !important;
    background: #fff !important;
    box-shadow: 0 0 0 4px rgba(139,36,82,.08) !important;
}

.product-review-edit-page .rating-panel {
    padding: 18px;
    border: 1px solid #eadde3;
    border-radius: 18px;
    background:
        radial-gradient(circle at 90% 0%, rgba(244,185,78,.13), transparent 28%),
        linear-gradient(145deg, #fffafb, #fff);
}

.product-review-edit-page .rating-options {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px;
    border: 1px solid #eee0e6;
    border-radius: 17px;
    background: #fcf8fa;
}

.product-review-edit-page .rating-choice {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
}

.product-review-edit-page .rating-choice span.rating-star {
    position: relative;
    width: 52px !important;
    height: 52px !important;
    border: 1px solid #eadde3 !important;
    border-radius: 15px !important;
    background: linear-gradient(145deg, #fff, #f9eef2) !important;
    color: #d6c7ce !important;
    box-shadow: 0 5px 13px rgba(77,28,48,.06);
}

.product-review-edit-page .rating-choice span.rating-star svg {
    width: 25px;
    height: 25px;
}

.product-review-edit-page .rating-choice:hover span.rating-star {
    border-color: #e5b44c !important;
    background: linear-gradient(145deg, #fffdf5, #fff2ce) !important;
    color: #dda42d !important;
    box-shadow: 0 7px 17px rgba(217,164,65,.18);
}

.product-review-edit-page .rating-choice input:checked + span.rating-star {
    border-color: #e1a83c !important;
    background: linear-gradient(145deg, #fff9e8, #ffeab5) !important;
    color: #dfa32b !important;
    box-shadow: 0 8px 20px rgba(217,164,65,.24);
    transform: translateY(-2px);
}

.product-review-edit-page .rating-choice input:checked + span.rating-star::after {
    content: "";
    position: absolute;
    top: 6px;
    right: 6px;
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #8b2452;
    box-shadow: 0 0 0 2px #fff;
}

.product-review-edit-page .rating-label {
    min-width: 48px;
    color: #8a707b;
    font-size: 8px;
    font-weight: 700;
    text-align: center;
}

.product-review-edit-page #ratingTitle {
    border: 1px solid #efd48b !important;
    background: linear-gradient(135deg, #fff9e8, #fff1c9) !important;
    color: #986714 !important;
    box-shadow: 0 5px 14px rgba(217,164,65,.08);
}

.product-review-edit-page .review-textarea {
    min-height: 175px;
    border-color: #eadde3 !important;
    border-radius: 14px !important;
    background: #fcfafb !important;
}

.product-review-edit-page .media-card {
    overflow: hidden;
    border: 1px solid #eadde3;
    border-radius: 20px;
    background: #fff;
    box-shadow: 0 8px 24px rgba(77,28,48,.055);
}

.product-review-edit-page .media-grid img {
    transition: transform .2s ease;
}

.product-review-edit-page .media-grid a:hover img {
    transform: scale(1.04);
}

.product-review-edit-page .media-grid a {
    border-color: #eadde3 !important;
    box-shadow: 0 4px 12px rgba(77,28,48,.05);
}

.product-review-edit-page .replacement-note {
    border: 1px solid #f0dca7;
    background: linear-gradient(135deg, #fffaf0, #fff5dc);
    color: #946617;
}

.product-review-edit-page .upload-zone {
    min-height: 155px;
    border-color: #e7d8df !important;
    border-radius: 18px !important;
    background:
        radial-gradient(circle at 50% 0%, rgba(139,36,82,.08), transparent 38%),
        linear-gradient(145deg, #fffafd, #fbf7f9) !important;
}

.product-review-edit-page .upload-zone:hover {
    border-color: #8b2452 !important;
    background: #fff7fa !important;
}

.product-review-edit-page .upload-icon {
    width: 58px;
    height: 58px;
    border-radius: 17px;
    background: linear-gradient(145deg, #fff, #f5e5eb);
    color: #8b2452;
    box-shadow: 0 8px 18px rgba(139,36,82,.10);
}

.product-review-edit-page .current-video {
    border: 1px solid #eadde3;
    box-shadow: 0 7px 18px rgba(77,28,48,.07);
}

.product-review-edit-page .video-upload {
    border-color: #eadde3 !important;
    border-radius: 17px !important;
    background: linear-gradient(145deg, #fffafd, #fbf8fa) !important;
}

.product-review-edit-page .side-card {
    overflow: hidden;
    border: 1px solid #eadde3;
    border-radius: 20px;
    background: #fff;
    box-shadow: 0 8px 24px rgba(77,28,48,.055);
}

.product-review-edit-page .current-rating {
    background:
        radial-gradient(circle at 90% 10%, rgba(244,185,78,.13), transparent 28%),
        linear-gradient(145deg, #fffdf7, #fff);
}

.product-review-edit-page .rating-number {
    color: #6a173b;
}

.product-review-edit-page .side-info {
    border: 1px solid #ead8e1;
    background:
        radial-gradient(circle at 90% 0%, rgba(139,36,82,.10), transparent 30%),
        linear-gradient(145deg, #fff7fa, #fcf1f5);
}

.product-review-edit-page .bottom-actions {
    padding-top: 4px;
}

.product-review-edit-page .cancel-btn {
    border-color: #eadde3 !important;
    box-shadow: 0 4px 12px rgba(77,28,48,.04);
}

.product-review-edit-page .update-btn {
    min-width: 165px;
    border-radius: 13px !important;
    background: linear-gradient(135deg, #8b2452, #6b1a3a) !important;
    box-shadow: 0 8px 18px rgba(107,26,58,.20);
}

.product-review-edit-page .update-btn:hover {
    background: linear-gradient(135deg, #761d45, #4f1029) !important;
    transform: translateY(-1px);
}

@media (max-width: 639px) {
    .product-review-edit-page .edit-hero {
        padding: 18px;
    }

    .product-review-edit-page .edit-title {
        font-size: 23px;
    }

    .product-review-edit-page .rating-options {
        justify-content: space-between;
        gap: 4px;
        padding: 10px 7px;
    }

    .product-review-edit-page .rating-choice span.rating-star {
        width: 44px !important;
        height: 44px !important;
    }

    .product-review-edit-page .rating-label {
        min-width: 42px;
        font-size: 7px;
    }
}
</style>

<div class="product-review-edit-page min-h-screen bg-[#f7f4f6] px-4 py-6 sm:px-6 lg:px-8">

    <div class="mx-auto max-w-[1200px]">

        <div class="edit-hero mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <div class="flex items-center gap-2 text-xs font-medium uppercase tracking-wider text-gray-400">

                    <a
                        href="{{ admin_route('product-reviews.index') }}"
                        class="transition hover:text-[#8B2452]"
                    >
                        Product Reviews
                    </a>

                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6"/>
                    </svg>

                    <span class="breadcrumb-current">
                        Edit Review
                    </span>

                </div>

                <h1 class="edit-title mt-2 text-2xl font-bold">
                    Edit Product Review
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Update customer review, rating, media.
                </p>
            </div>

            <div class="flex gap-2">

                <a
                    href="{{ admin_route('product-reviews.show', $productReview) }}"
                    class="top-action inline-flex h-10 items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 text-sm font-semibold text-gray-600 transition hover:bg-gray-50"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/>
                        <circle cx="12" cy="12" r="2.5" stroke-width="1.8"/>
                    </svg>
                    View
                </a>

                <a
                    href="{{ admin_route('product-reviews.index') }}"
                    class="top-action inline-flex h-10 items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 text-sm font-semibold text-gray-600 transition hover:bg-gray-50"
                >
                    Back
                </a>

            </div>

        </div>

        @if($errors->any())

            <div class="mb-6 rounded-2xl border border-red-100 bg-red-50 p-4">

                <div class="flex gap-3">

                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>
                        </svg>
                    </div>

                    <div>

                        <p class="text-sm font-semibold text-red-700">
                            Please fix the following errors:
                        </p>

                        <ul class="mt-2 space-y-1 text-sm text-red-600">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>

                </div>

            </div>

        @endif

        <form
            method="POST"
            action="{{ admin_route('product-reviews.update', $productReview) }}"
            enctype="multipart/form-data"
        >

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                <div class="space-y-6 lg:col-span-2">

                    <div class="form-section">

                        <div class="section-head">

                            <div class="flex items-center gap-3">

                                <div class="section-icon flex items-center justify-center rounded-xl bg-[#6B1A3A]/10 text-[#6B1A3A]">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7.5 12 3 4 7.5m16 0v9L12 21l-8-4.5v-9m16 0-8 4.5m0 0L4 7.5m8 4.5V21"/>
                                    </svg>
                                </div>

                                <div>
                                    <h2 class="text-base font-bold text-gray-800">
                                        Review Information
                                    </h2>

                                    <p class="text-xs text-gray-400">
                                        Update product, customer and order details.
                                    </p>
                                </div>

                            </div>

                        </div>

                        <div class="space-y-5 p-6">

                            <div>

                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                    Product <span class="text-red-500">*</span>
                                </label>

                                <select
                                    name="product_id"
                                    required
                                    class="field-control h-11 w-full rounded-xl border border-gray-200 bg-gray-50 px-3 text-sm text-gray-700 outline-none transition focus:border-[#8B2452] focus:bg-white focus:ring-2 focus:ring-[#8B2452]/10"
                                >
                                    <option value="">Select Product</option>

                                    @foreach($products as $product)

                                        <option
                                            value="{{ $product->id }}"
                                            @selected(old('product_id', $productReview->product_id) == $product->id)
                                        >
                                            {{ $product->name }}
                                        </option>

                                    @endforeach

                                </select>

                                @error('product_id')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror

                            </div>

                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                                <div>

                                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                                        Customer
                                    </label>

                                    <select
                                        name="user_id"
                                        class="field-control h-11 w-full rounded-xl border border-gray-200 bg-gray-50 px-3 text-sm text-gray-700 outline-none transition focus:border-[#8B2452] focus:bg-white focus:ring-2 focus:ring-[#8B2452]/10"
                                    >
                                        <option value="">Guest / No Customer</option>

                                        @foreach($users as $user)

                                            <option
                                                value="{{ $user->id }}"
                                                @selected(old('user_id', $productReview->user_id) == $user->id)
                                            >
                                                {{ $user->name }} — {{ $user->email }}
                                            </option>

                                        @endforeach

                                    </select>

                                    @error('user_id')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror

                                </div>

                                <div>

                                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                                        Order
                                    </label>

                                    <select
                                        name="order_id"
                                        class="field-control h-11 w-full rounded-xl border border-gray-200 bg-gray-50 px-3 text-sm text-gray-700 outline-none transition focus:border-[#8B2452] focus:bg-white focus:ring-2 focus:ring-[#8B2452]/10"
                                    >
                                        <option value="">No Order</option>

                                        @foreach($orders as $order)

                                            <option
                                                value="{{ $order->id }}"
                                                @selected(old('order_id', $productReview->order_id) == $order->id)
                                            >
                                                {{ $order->order_number }}
                                            </option>

                                        @endforeach

                                    </select>

                                    @error('order_id')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror

                                </div>

                            </div>

                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                                <div>

                                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                                        Size
                                    </label>

                                    <input
                                        type="text"
                                        name="size"
                                        value="{{ old('size', $productReview->size) }}"
                                        maxlength="100"
                                        placeholder="Example: M, L, XL"
                                        class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50 px-3 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-[#8B2452] focus:bg-white focus:ring-2 focus:ring-[#8B2452]/10"
                                    >

                                    @error('size')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror

                                </div>

                                <div>

                                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                                        Color
                                    </label>

                                    <input
                                        type="text"
                                        name="color"
                                        value="{{ old('color', $productReview->color) }}"
                                        maxlength="100"
                                        placeholder="Example: Maroon"
                                        class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50 px-3 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-[#8B2452] focus:bg-white focus:ring-2 focus:ring-[#8B2452]/10"
                                    >

                                    @error('color')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="form-section">

                        <div class="section-head">

                            <div class="flex items-center gap-3">

                                <div class="section-icon flex items-center justify-center rounded-xl bg-amber-50 text-[#D89B25]">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="m12 2.8 2.78 5.63 6.22.9-4.5 4.39 1.06 6.2L12 17l-5.56 2.92 1.06-6.2L12 2.8Z"/>
                                    </svg>
                                </div>

                                <div>
                                    <h2 class="text-base font-bold text-gray-800">
                                        Rating & Review
                                    </h2>

                                    <p class="text-xs text-gray-400">
                                        Rating title is generated automatically.
                                    </p>
                                </div>

                            </div>

                        </div>

                        <div class="p-6">

                            <div class="mb-6">

                                <label class="mb-3 block text-sm font-semibold text-gray-700">
                                    Rating
                                </label>

                                <div class="rating-options">

                                    @for($rating = 1; $rating <= 5; $rating++)

                                        <label class="rating-choice cursor-pointer">

                                            <input
                                                type="radio"
                                                name="rating"
                                                value="{{ $rating }}"
                                                class="peer sr-only"
                                                @checked(old('rating', $productReview->rating) == $rating)
                                            >

                                            <span class="rating-star flex h-11 w-11 items-center justify-center rounded-xl border border-gray-200 bg-gray-50 text-gray-300 transition peer-checked:border-[#F4B94E] peer-checked:bg-amber-50 peer-checked:text-[#F4B94E] hover:border-[#F4B94E] hover:text-[#F4B94E]">

                                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="m12 2.8 2.78 5.63 6.22.9-4.5 4.39 1.06 6.2L12 17l-5.56 2.92 1.06-6.2L3 9.33l6.22-.9L12 2.8Z"/>
                                                </svg>

                                            </span>

                                        </label>

                                    @endfor

                                    <button
                                        type="button"
                                        id="clearRating"
                                        class="{{ old('rating', $productReview->rating) ? '' : 'hidden' }} ml-2 text-xs font-semibold text-gray-400 transition hover:text-red-500"
                                    >
                                        Clear
                                    </button>

                                </div>

                                <div
                                    id="ratingTitle"
                                    class="{{ old('rating', $productReview->rating) ? '' : 'hidden' }} mt-3 rounded-xl bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-700"
                                >
                                    @php
                                        $ratingTitles = [
                                            1 => 'Very Bad',
                                            2 => 'Bad',
                                            3 => 'Okay-Okay',
                                            4 => 'Good',
                                            5 => 'Very Good',
                                        ];

                                        $currentRating = old('rating', $productReview->rating);
                                    @endphp

                                    {{ $ratingTitles[$currentRating] ?? '' }}
                                </div>

                                @error('rating')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror

                            </div>

                            <div>

                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                    Review <span class="text-red-500">*</span>
                                </label>

                                <textarea
                                    name="review"
                                    id="reviewText"
                                    rows="7"
                                    required
                                    maxlength="5000"
                                    placeholder="Write customer feedback here..."
                                    class="review-textarea w-full resize-y rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-[#8B2452] focus:bg-white focus:ring-2 focus:ring-[#8B2452]/10"
                                >{{ old('review', $productReview->review) }}</textarea>

                                <div class="mt-1 flex justify-between">

                                    @error('review')
                                        <p class="text-xs text-red-500">{{ $message }}</p>
                                    @else
                                        <span></span>
                                    @enderror

                                    <span id="reviewCounter" class="text-xs text-gray-400">
                                        {{ strlen(old('review', $productReview->review)) }} / 5000
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="media-card">

                        <div class="border-b border-gray-100 px-6 py-5">

                            <div class="flex items-center gap-3">

                                <div class="section-icon flex items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5Z"/>
                                        <circle cx="9" cy="9" r="1.5"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m20 15-4-4-7 7"/>
                                    </svg>
                                </div>

                                <div>
                                    <h2 class="text-base font-bold text-gray-800">
                                        Review Media
                                    </h2>

                                    <p class="text-xs text-gray-400">
                                        Upload new media only when you want to replace the existing media.
                                    </p>
                                </div>

                            </div>

                        </div>

                        <div class="space-y-6 p-6">

                            @if(!empty($productReview->image_urls))

                                <div>

                                    <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-400">
                                        Current Images
                                    </p>

                                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-5">

                                        @foreach($productReview->image_urls as $image)

                                            <a
                                                href="{{ $image }}"
                                                target="_blank"
                                                class="media-grid aspect-square overflow-hidden rounded-xl border border-gray-100 bg-gray-50"
                                            >
                                                <img
                                                    src="{{ $image }}"
                                                    alt="Review image"
                                                    class="h-full w-full object-cover"
                                                >
                                            </a>

                                        @endforeach

                                    </div>

                                    <div class="replacement-note mt-3 rounded-xl px-4 py-3 text-xs leading-5">
                                        Selecting new images will replace the current review images.
                                    </div>

                                </div>

                            @endif

                            <div>

                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                    New Review Images
                                </label>

                                <label
                                    for="reviewImages"
                                    class="upload-zone flex min-h-[140px] cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 px-5 py-7 text-center transition hover:border-[#8B2452]/40 hover:bg-[#8B2452]/5"
                                >

                                    <div class="upload-icon flex h-11 w-11 items-center justify-center rounded-xl bg-white text-[#8B2452] shadow-sm">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 16V4m0 0L8 8m4-4 4 4M5 20h14"/>
                                        </svg>
                                    </div>

                                    <p class="mt-3 text-sm font-semibold text-gray-700">
                                        Click to select new images
                                    </p>

                                    <p class="mt-1 text-xs text-gray-400">
                                        JPG, JPEG, PNG or WEBP • Maximum 5 images • 5 MB each
                                    </p>

                                </label>

                                <input
                                    type="file"
                                    id="reviewImages"
                                    name="images[]"
                                    multiple
                                    accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                    class="hidden"
                                >

                                <div
                                    id="imagePreview"
                                    class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-5"
                                ></div>

                                @error('images')
                                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                                @enderror

                                @error('images.*')
                                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                                @enderror

                            </div>

                            @if($productReview->video_url)

                                <div>

                                    <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-400">
                                        Current Video
                                    </p>

                                    <video
                                        controls
                                        preload="metadata"
                                        class="current-video max-h-[350px] w-full rounded-2xl bg-black"
                                    >
                                        <source src="{{ $productReview->video_url }}">
                                    </video>

                                    <div class="replacement-note mt-3 rounded-xl px-4 py-3 text-xs leading-5">
                                        Selecting a new video will replace the current review video.
                                    </div>

                                </div>

                            @endif

                            <div>

                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                    New Review Video
                                </label>

                                <label
                                    for="reviewVideo"
                                    class="video-upload flex cursor-pointer items-center gap-4 rounded-xl border border-gray-200 bg-gray-50 p-4 transition hover:border-[#8B2452]/40"
                                >

                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#6B1A3A]/10 text-[#6B1A3A]">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m10 8 5 4-5 4V8Z"/>
                                            <rect width="18" height="18" x="3" y="3" rx="4" stroke-width="1.8"/>
                                        </svg>
                                    </div>

                                    <div class="min-w-0">

                                        <p class="text-sm font-semibold text-gray-700">
                                            Choose New Video
                                        </p>

                                        <p
                                            id="videoName"
                                            class="mt-1 truncate text-xs text-gray-400"
                                        >
                                            MP4, MOV, AVI or WEBM • Maximum 20 MB
                                        </p>

                                    </div>

                                </label>

                                <input
                                    type="file"
                                    id="reviewVideo"
                                    name="video"
                                    accept=".mp4,.mov,.avi,.webm,video/mp4,video/quicktime,video/x-msvideo,video/webm"
                                    class="hidden"
                                >

                                @error('video')
                                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>

                <div class="space-y-6">

                    <div class="side-card">

                        <div class="border-b border-gray-100 px-5 py-4">
                            <h2 class="text-sm font-bold text-gray-800">
                                Current Rating
                            </h2>
                        </div>

                        <div class="current-rating p-5">

                            @if($productReview->rating)

                                <div class="flex items-center gap-3">

                                    <span class="rating-number text-3xl font-bold">
                                        {{ $productReview->rating }}
                                    </span>

                                    <div>

                                        <div class="flex gap-0.5 text-[#F4B94E]">

                                            @for($i = 1; $i <= 5; $i++)

                                                <svg class="h-4 w-4" fill="{{ $i <= $productReview->rating ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-width="1.5" d="m12 2.8 2.78 5.63 6.22.9-4.5 4.39 1.06 6.2L12 17l-5.56 2.92 1.06-6.2L12 2.8Z"/>
                                                </svg>

                                            @endfor

                                        </div>

                                        <p class="mt-1 text-xs font-semibold text-gray-500">
                                            {{ $productReview->title }}
                                        </p>

                                    </div>

                                </div>

                            @else

                                <p class="text-sm text-gray-400">
                                    No rating assigned.
                                </p>

                            @endif

                        </div>

                    </div>

                    <div class="side-info rounded-2xl p-5">

                        <div class="flex gap-3">

                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 17v-5m0-4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>

                            <p class="text-xs leading-5 text-gray-500">
                                The review title is generated automatically from the selected rating. If no rating is selected, the customer name is used.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

            <div class="bottom-actions mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                <a
                    href="{{ admin_route('product-reviews.show', $productReview) }}"
                    class="cancel-btn inline-flex h-11 items-center justify-center rounded-xl border border-gray-200 bg-white px-6 text-sm font-semibold text-gray-600 transition hover:bg-gray-50"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="update-btn inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-[#6B1A3A] px-7 text-sm font-semibold text-white shadow-sm transition hover:bg-[#4A0F26]"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4 4L19 6"/>
                    </svg>
                    Update Review
                </button>

            </div>

        </form>

    </div>

</div>

@push('scripts')
<script src="{{ asset('assets/js/admin/product-reviews.js') }}"></script>
@endpush

@endsection