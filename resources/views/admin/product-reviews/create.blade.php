@extends('layouts.admin.admin-settings')

@section('settings-content')

<style>
    .product-review-create-page {
        background:
            radial-gradient(circle at 92% 4%, rgba(139, 36, 82, .07), transparent 25%),
            radial-gradient(circle at 5% 45%, rgba(216, 155, 37, .045), transparent 22%),
            #f8f9fb;
    }

    .product-review-create-page .page-shell {
        max-width: 1280px;
    }

    .product-review-create-page .page-title {
        letter-spacing: -.025em;
    }

    .product-review-create-page .back-review-btn {
        border-color: #eadde3;
        box-shadow: 0 4px 14px rgba(75, 25, 48, .045);
    }

    .product-review-create-page .back-review-btn:hover {
        border-color: #d8b9c5;
        color: #8b2452;
        background: #fffafd;
    }

    .product-review-create-page .error-panel {
        border-color: #f1cfd5;
        background: linear-gradient(135deg, #fff7f8, #fff1f3);
        box-shadow: 0 5px 18px rgba(170, 50, 70, .045);
    }

    .product-review-create-page .form-card,
    .product-review-create-page .side-card {
        border-color: #eadde3;
        box-shadow: 0 7px 24px rgba(63, 29, 43, .055);
        overflow: hidden;
        transition: box-shadow .2s ease, transform .2s ease;
    }

    .product-review-create-page .form-card:hover,
    .product-review-create-page .side-card:hover {
        box-shadow: 0 11px 30px rgba(63, 29, 43, .075);
    }

    .product-review-create-page .card-header {
        background: linear-gradient(135deg, #fffafd 0%, #fff 72%);
        border-bottom-color: #eee3e7;
    }

    .product-review-create-page .section-icon {
        box-shadow: 0 5px 13px rgba(90, 28, 52, .08);
    }

    .product-review-create-page .field-control {
        background: #fbfafb;
        border-color: #e7dde2;
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .015);
    }

    .product-review-create-page .field-control:hover {
        border-color: #d7bdc8;
        background: #fff;
    }

    .product-review-create-page .field-control:focus {
        border-color: #8b2452;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(139, 36, 82, .08);
    }

    .product-review-create-page .rating-option {
        position: relative;
        transition: transform .15s ease;
    }

    .product-review-create-page .rating-option:hover {
        transform: translateY(-2px);
    }

    .product-review-create-page .rating-star {
        box-shadow: 0 3px 10px rgba(73, 35, 48, .045);
    }

    .product-review-create-page .media-upload {
        min-height: 168px;
        border-color: #dfd2d8;
        background:
            linear-gradient(135deg, rgba(139, 36, 82, .025), rgba(216, 155, 37, .018)),
            #fcfbfc;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .75);
    }

    .product-review-create-page .media-upload:hover {
        border-color: #b97998;
        background:
            linear-gradient(135deg, rgba(139, 36, 82, .055), rgba(216, 155, 37, .025)),
            #fff;
    }

    .product-review-create-page .video-upload {
        border-color: #e5dbe0;
        background: #fcfbfc;
    }

    .product-review-create-page .video-upload:hover {
        border-color: #b97998;
        background: #fff;
    }

    .product-review-create-page .guide-card {
        background: linear-gradient(145deg, #fff 0%, #fff9fb 100%);
    }

    .product-review-create-page .guide-row {
        padding: 9px 10px;
        border-radius: 10px;
        transition: background .15s ease;
    }

    .product-review-create-page .guide-row:hover {
        background: #fbf2f6;
    }

    .product-review-create-page .info-card {
        border-color: #ead6df;
        background: linear-gradient(145deg, #fff9fb, #fff);
        box-shadow: 0 5px 18px rgba(83, 29, 49, .045);
    }

    .product-review-create-page .bottom-actions {
        padding-top: 2px;
    }

    .product-review-create-page .cancel-btn {
        border-color: #e2d9de;
        box-shadow: 0 3px 12px rgba(50, 25, 38, .035);
    }

    .product-review-create-page .cancel-btn:hover {
        border-color: #cdb9c2;
        background: #fff;
        color: #6b1a3a;
    }

    .product-review-create-page .submit-btn {
        background: linear-gradient(135deg, #8b2452 0%, #6b1a3a 100%);
        box-shadow: 0 7px 18px rgba(107, 26, 58, .18);
    }

    .product-review-create-page .submit-btn:hover {
        background: linear-gradient(135deg, #762044 0%, #55132d 100%);
        box-shadow: 0 9px 22px rgba(107, 26, 58, .23);
        transform: translateY(-1px);
    }

    @media (max-width: 1023px) {
        .product-review-create-page .page-shell {
            max-width: 900px;
        }
    }

    @media (max-width: 639px) {
        .product-review-create-page {
            padding-left: 14px !important;
            padding-right: 14px !important;
        }

        .product-review-create-page .card-header,
        .product-review-create-page .card-body {
            padding-left: 18px !important;
            padding-right: 18px !important;
        }

        .product-review-create-page .rating-options {
            gap: 7px !important;
        }

        .product-review-create-page .rating-star {
            width: 42px !important;
            height: 42px !important;
        }
    }

    .product-review-create-page .page-heading {
        position: relative;
        overflow: hidden;
        padding: 22px 24px;
        border: 1px solid #ead9e1;
        border-radius: 20px;
        background:
            radial-gradient(circle at 92% 20%, rgba(139, 36, 82, .12), transparent 24%),
            linear-gradient(135deg, #fffafb 0%, #fff 62%, #fcf1f5 100%);
        box-shadow: 0 8px 26px rgba(70, 25, 45, .055);
    }

    .product-review-create-page .page-heading::after {
        content: "";
        position: absolute;
        right: -55px;
        bottom: -65px;
        width: 170px;
        height: 170px;
        border-radius: 50%;
        background: rgba(139, 36, 82, .055);
        pointer-events: none;
    }

    .product-review-create-page .page-heading > div,
    .product-review-create-page .page-heading > a {
        position: relative;
        z-index: 1;
    }

    .product-review-create-page .page-title {
        color: #5f1735;
        font-size: 27px;
        letter-spacing: -.035em;
    }

    .product-review-create-page .breadcrumb-current {
        padding: 4px 9px;
        border-radius: 999px;
        background: #f5e5eb;
        color: #8b2452;
    }

    .product-review-create-page .form-card,
    .product-review-create-page .side-card {
        border-radius: 20px;
    }

    .product-review-create-page .card-header {
        padding-top: 18px;
        padding-bottom: 18px;
        background:
            linear-gradient(135deg, #fff 0%, #fff9fb 100%);
    }

    .product-review-create-page .section-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
    }

    .product-review-create-page .field-control {
        height: 46px;
        border-radius: 12px;
    }

    .product-review-create-page textarea.field-control {
        height: auto;
    }

    .product-review-create-page .rating-box {
        position: relative;
        padding: 18px;
        border: 1px solid #ead9e1;
        border-radius: 18px;
        background:
            radial-gradient(circle at 90% 10%, rgba(244, 185, 78, .12), transparent 25%),
            linear-gradient(135deg, #fffafb, #fff);
    }

    .product-review-create-page .rating-options {
        padding: 14px 16px;
        border: 1px solid #eadde3;
        border-radius: 16px;
        background: #fcf8fa;
    }

    .product-review-create-page .rating-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
        min-width: 54px;
    }

    .product-review-create-page .rating-option::after {
        color: #8b6d79;
        font-size: 7px;
        font-weight: 700;
        white-space: nowrap;
    }

    .product-review-create-page .rating-option:nth-child(1)::after {
        content: "Very Bad";
    }

    .product-review-create-page .rating-option:nth-child(2)::after {
        content: "Bad";
    }

    .product-review-create-page .rating-option:nth-child(3)::after {
        content: "Okay";
    }

    .product-review-create-page .rating-option:nth-child(4)::after {
        content: "Good";
    }

    .product-review-create-page .rating-option:nth-child(5)::after {
        content: "Very Good";
    }

    .product-review-create-page .rating-star {
        width: 54px !important;
        height: 54px !important;
        border: 1px solid #eadde3 !important;
        border-radius: 15px !important;
        background: linear-gradient(145deg, #fff, #f9eef2) !important;
        color: #d7c7cd !important;
        box-shadow: 0 5px 14px rgba(90, 30, 52, .07);
    }

    .product-review-create-page .rating-star svg {
        width: 25px;
        height: 25px;
        filter: drop-shadow(0 2px 2px rgba(183, 125, 28, .08));
    }

    .product-review-create-page .rating-option:hover .rating-star {
        border-color: #e6b44f !important;
        background: linear-gradient(145deg, #fffdf5, #fff3cf) !important;
        color: #e0a52e !important;
        box-shadow: 0 7px 16px rgba(217, 164, 65, .18);
    }

    .product-review-create-page .rating-option input:checked + .rating-star {
        border-color: #e1a83c !important;
        background: linear-gradient(145deg, #fff9e9, #ffeab3) !important;
        color: #e0a12b !important;
        box-shadow: 0 7px 18px rgba(217, 164, 65, .24);
        transform: translateY(-2px) scale(1.03);
    }

    .product-review-create-page .rating-option input:checked + .rating-star::after {
        content: "";
        position: absolute;
        width: 7px;
        height: 7px;
        margin-top: -47px;
        margin-left: 47px;
        border-radius: 50%;
        background: #8b2452;
        box-shadow: 0 0 0 3px #fff;
    }

    .product-review-create-page #ratingTitle {
        border: 1px solid #f0d58e;
        background: linear-gradient(135deg, #fff9e7, #fff2cb);
        color: #9a6811;
        box-shadow: 0 4px 12px rgba(217, 164, 65, .08);
    }

    .product-review-create-page .guide-card {
        background:
            radial-gradient(circle at 95% 5%, rgba(217, 164, 65, .10), transparent 25%),
            linear-gradient(145deg, #fff 0%, #fff7fa 100%);
    }

    .product-review-create-page .guide-row {
        min-height: 43px;
        border: 1px solid transparent;
    }

    .product-review-create-page .guide-row:hover {
        border-color: #ead7df;
        background: #fff;
        box-shadow: 0 4px 12px rgba(88, 29, 51, .05);
    }

    .product-review-create-page .guide-row > span:first-child {
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .product-review-create-page .guide-row > span:first-child::before {
        content: "★";
        color: #e0a52e;
        font-size: 12px;
    }

    .product-review-create-page .guide-row > span:last-child {
        color: #70213f;
    }

    .product-review-create-page .media-upload {
        min-height: 190px;
        border-radius: 18px;
        background:
            radial-gradient(circle at 50% 0%, rgba(139, 36, 82, .07), transparent 35%),
            linear-gradient(145deg, #fffafd, #fbf7f9);
    }

    .product-review-create-page .media-upload > div:first-child {
        width: 58px;
        height: 58px;
        border-radius: 17px;
        background: linear-gradient(145deg, #fff, #f6e7ed);
        color: #8b2452;
        box-shadow: 0 8px 18px rgba(139, 36, 82, .10);
    }

    .product-review-create-page .video-upload {
        min-height: 76px;
        border-radius: 16px;
        background: linear-gradient(135deg, #fffafd, #fbf8fa);
    }

    .product-review-create-page .info-card {
        border-radius: 18px;
    }

    .product-review-create-page .submit-btn {
        min-width: 155px;
        border-radius: 13px;
    }

    .product-review-create-page .cancel-btn {
        border-radius: 13px;
    }

    @media (max-width: 639px) {
        .product-review-create-page .page-heading {
            padding: 18px;
        }

        .product-review-create-page .page-title {
            font-size: 23px;
        }

        .product-review-create-page .rating-options {
            justify-content: space-between;
            gap: 4px;
            padding: 12px 8px;
        }

        .product-review-create-page .rating-star {
            width: 45px !important;
            height: 45px !important;
        }

        .product-review-create-page .rating-option {
            min-width: 45px;
        }
    }

</style>


<div class="product-review-create-page min-h-screen px-4 py-7 sm:px-6 lg:px-8">

    <div class="page-shell mx-auto">

        <div class="page-heading mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <div class="flex items-center gap-2 text-xs font-medium uppercase tracking-wider text-gray-400">
                    <a href="{{ admin_route('product-reviews.index') }}" class="transition hover:text-[#8B2452]">
                        Product Reviews
                    </a>

                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6"/>
                    </svg>

                    <span class="breadcrumb-current">Add Review</span>
                </div>

                <h1 class="page-title mt-2 text-2xl font-bold">
                    Add Product Review
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Add a customer review and rating for a product.
                </p>
            </div>

            <a
                href="{{ admin_route('product-reviews.index') }}"
                class="back-review-btn inline-flex h-10 items-center justify-center gap-2 rounded-xl border bg-white px-4 text-sm font-semibold text-gray-600 transition"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 18-6-6 6-6"/>
                </svg>
                Back to Reviews
            </a>

        </div>

        @if($errors->any())

            <div class="error-panel mb-6 rounded-2xl border p-4">

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
            action="{{ admin_route('product-reviews.store') }}"
            enctype="multipart/form-data"
        >

            @csrf

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                <div class="space-y-6 lg:col-span-2">

                    <div class="form-card rounded-2xl border bg-white shadow-sm">

                        <div class="card-header border-b px-6 py-5">
                            <div class="flex items-center gap-3">

                                <div class="section-icon flex h-10 w-10 items-center justify-center rounded-xl bg-[#6B1A3A]/10 text-[#6B1A3A]">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7.5 12 3 4 7.5m16 0v9L12 21l-8-4.5v-9m16 0-8 4.5m0 0L4 7.5m8 4.5V21"/>
                                    </svg>
                                </div>

                                <div>
                                    <h2 class="text-base font-bold text-gray-800">
                                        Review Information
                                    </h2>

                                    <p class="text-xs text-gray-400">
                                        Select the product and customer for this review.
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
                                    class="field-control h-11 w-full rounded-xl border px-3 text-sm text-gray-700 outline-none transition focus:border-[#8B2452] focus:bg-white focus:ring-2 focus:ring-[#8B2452]/10"
                                >
                                    <option value="">Select Product</option>

                                    @foreach($products as $product)
                                        <option
                                            value="{{ $product->id }}"
                                            @selected(old('product_id') == $product->id)
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
                                        class="field-control h-11 w-full rounded-xl border px-3 text-sm text-gray-700 outline-none transition focus:border-[#8B2452] focus:bg-white focus:ring-2 focus:ring-[#8B2452]/10"
                                    >
                                        <option value="">Guest / No Customer</option>

                                        @foreach($users as $user)
                                            <option
                                                value="{{ $user->id }}"
                                                @selected(old('user_id') == $user->id)
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
                                        class="field-control h-11 w-full rounded-xl border px-3 text-sm text-gray-700 outline-none transition focus:border-[#8B2452] focus:bg-white focus:ring-2 focus:ring-[#8B2452]/10"
                                    >
                                        <option value="">No Order</option>

                                        @foreach($orders as $order)
                                            <option
                                                value="{{ $order->id }}"
                                                @selected(old('order_id') == $order->id)
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
                                        value="{{ old('size') }}"
                                        maxlength="100"
                                        placeholder="Example: M, L, XL"
                                        class="field-control h-11 w-full rounded-xl border px-3 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-[#8B2452] focus:bg-white focus:ring-2 focus:ring-[#8B2452]/10"
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
                                        value="{{ old('color') }}"
                                        maxlength="100"
                                        placeholder="Example: Maroon"
                                        class="field-control h-11 w-full rounded-xl border px-3 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-[#8B2452] focus:bg-white focus:ring-2 focus:ring-[#8B2452]/10"
                                    >

                                    @error('color')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="form-card rounded-2xl border bg-white shadow-sm">

                        <div class="card-header border-b px-6 py-5">
                            <div class="flex items-center gap-3">

                                <div class="section-icon flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-[#D89B25]">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="m12 2.8 2.78 5.63 6.22.9-4.5 4.39 1.06 6.2L12 17l-5.56 2.92 1.06-6.2L3 9.33l6.22-.9L12 2.8Z"/>
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

                            <div class="rating-box mb-6">

                                <label class="mb-3 block text-sm font-semibold text-gray-700">
                                    Rating
                                </label>

                                <div class="rating-options flex items-center gap-2">

                                    @for($rating = 1; $rating <= 5; $rating++)

                                        <label class="rating-option cursor-pointer">

                                            <input
                                                type="radio"
                                                name="rating"
                                                value="{{ $rating }}"
                                                class="peer sr-only"
                                                @checked(old('rating') == $rating)
                                            >

                                            <span class="rating-star flex h-11 w-11 items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-300 transition peer-checked:border-[#F4B94E] peer-checked:bg-amber-50 peer-checked:text-[#F4B94E] hover:border-[#F4B94E] hover:text-[#F4B94E]">

                                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="m12 2.8 2.78 5.63 6.22.9-4.5 4.39 1.06 6.2L12 17l-5.56 2.92 1.06-6.2L12 2.8Z"/>
                                                </svg>

                                            </span>

                                        </label>

                                    @endfor

                                    <button
                                        type="button"
                                        id="clearRating"
                                        class="ml-2 hidden text-xs font-semibold text-gray-400 transition hover:text-red-500"
                                    >
                                        Clear
                                    </button>

                                </div>

                                <div
                                    id="ratingTitle"
                                    class="mt-3 hidden rounded-xl bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-700"
                                ></div>

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
                                    rows="7"
        
                                    maxlength="5000"
                                    placeholder="Write customer feedback here..."
                                    class="field-control w-full resize-y rounded-xl border px-4 py-3 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-[#8B2452] focus:bg-white focus:ring-2 focus:ring-[#8B2452]/10"
                                >{{ old('review') }}</textarea>

                                <div class="mt-1 flex justify-between">
                                    @error('review')
                                        <p class="text-xs text-red-500">{{ $message }}</p>
                                    @else
                                        <span></span>
                                    @enderror

                                    <span id="reviewCounter" class="text-xs text-gray-400">
                                        0 / 5000
                                    </span>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="form-card rounded-2xl border bg-white shadow-sm">

                        <div class="card-header border-b px-6 py-5">

                            <div class="flex items-center gap-3">

                                <div class="section-icon flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
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
                                        Upload up to 5 images and one video.
                                    </p>
                                </div>

                            </div>

                        </div>

                        <div class="space-y-6 p-6">

                            <div>

                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                    Review Images
                                </label>

                                <label
                                    for="reviewImages"
                                    class="media-upload flex min-h-[168px] cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed px-5 py-8 text-center transition"
                                >

                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white text-[#8B2452] shadow-sm">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 16V4m0 0L8 8m4-4 4 4M5 20h14"/>
                                        </svg>
                                    </div>

                                    <p class="mt-3 text-sm font-semibold text-gray-700">
                                        Click to upload images
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

                            <div>

                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                    Review Video
                                </label>

                                <label
                                    for="reviewVideo"
                                    class="video-upload flex cursor-pointer items-center gap-4 rounded-xl border p-4 transition"
                                >

                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#6B1A3A]/10 text-[#6B1A3A]">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m10 8 5 4-5 4V8Z"/>
                                            <rect width="18" height="18" x="3" y="3" rx="4" stroke-width="1.8"/>
                                        </svg>
                                    </div>

                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-700">
                                            Choose Review Video
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

                    <div class="side-card guide-card rounded-2xl border bg-white shadow-sm">

                        <div class="card-header border-b px-5 py-4">
                            <h2 class="text-sm font-bold text-gray-800">
                                Rating Guide
                            </h2>
                        </div>

                        <div class="space-y-3 p-5">

                            <div class="guide-row flex items-center justify-between">
                                <span class="text-sm text-gray-500">5 Stars</span>
                                <span class="text-sm font-semibold text-gray-700">Very Good</span>
                            </div>

                            <div class="guide-row flex items-center justify-between">
                                <span class="text-sm text-gray-500">4 Stars</span>
                                <span class="text-sm font-semibold text-gray-700">Good</span>
                            </div>

                            <div class="guide-row flex items-center justify-between">
                                <span class="text-sm text-gray-500">3 Stars</span>
                                <span class="text-sm font-semibold text-gray-700">Okay-Okay</span>
                            </div>

                            <div class="guide-row flex items-center justify-between">
                                <span class="text-sm text-gray-500">2 Stars</span>
                                <span class="text-sm font-semibold text-gray-700">Bad</span>
                            </div>

                            <div class="guide-row flex items-center justify-between">
                                <span class="text-sm text-gray-500">1 Star</span>
                                <span class="text-sm font-semibold text-gray-700">Very Bad</span>
                            </div>

                        </div>

                    </div>

                    <div class="info-card rounded-2xl border p-5">

                        <div class="flex gap-3">

                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 17v-5m0-4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>

                            <p class="text-xs leading-5 text-gray-500">
                                Reviews without a rating are stored as feedback and are not included in the product rating calculation.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

            <div class="bottom-actions mt-7 flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 sm:flex-row sm:justify-end">

                <a
                    href="{{ admin_route('product-reviews.index') }}"
                    class="cancel-btn inline-flex h-11 items-center justify-center rounded-xl border bg-white px-6 text-sm font-semibold text-gray-600 transition"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="submit-btn inline-flex h-11 items-center justify-center gap-2 rounded-xl px-7 text-sm font-semibold text-white transition"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4 4L19 6"/>
                    </svg>
                    Create Review
                </button>

            </div>

        </form>

    </div>

</div>

@push('scripts')
<script src="{{ asset('assets/js/admin/product-reviews.js') }}"></script>
@endpush

@endsection