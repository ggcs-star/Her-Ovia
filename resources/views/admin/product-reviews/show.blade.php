@extends('layouts.admin.admin-settings')

@section('settings-content')

<style>
.product-review-view-page {
    color: #3f2632;
}

.product-review-view-page .view-hero {
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

.product-review-view-page .view-hero::after {
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

.product-review-view-page .view-hero > div,
.product-review-view-page .view-hero > a {
    position: relative;
    z-index: 1;
}

.product-review-view-page .view-title {
    color: #5f1735;
    font-size: 28px;
    letter-spacing: -.035em;
}

.product-review-view-page .breadcrumb-current {
    padding: 4px 10px;
    border-radius: 999px;
    background: #f5e5eb;
    color: #8b2452;
}

.product-review-view-page .hero-edit {
    border-radius: 13px !important;
    background: linear-gradient(135deg, #8b2452, #6b1a3a) !important;
    box-shadow: 0 8px 18px rgba(107,26,58,.20);
}

.product-review-view-page .hero-back {
    border-color: #ead8e1 !important;
    box-shadow: 0 4px 12px rgba(77,28,48,.04);
}

.product-review-view-page .view-card {
    overflow: hidden;
    border: 1px solid #eadde3;
    border-radius: 20px;
    background: #fff;
    box-shadow: 0 8px 24px rgba(77,28,48,.055);
}

.product-review-view-page .card-head {
    padding: 20px 24px;
    border-bottom: 1px solid #f0e5e9;
    background: linear-gradient(135deg, #fff 0%, #fff9fb 100%);
}

.product-review-view-page .section-icon {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    box-shadow: 0 5px 14px rgba(77,28,48,.07);
}

.product-review-view-page .rating-panel {
    border: 1px solid #efdca5;
    border-radius: 18px;
    background:
        radial-gradient(circle at 92% 0%, rgba(244,185,78,.16), transparent 28%),
        linear-gradient(145deg, #fffdf7, #fff7df);
    box-shadow: 0 7px 18px rgba(217,164,65,.08);
}

.product-review-view-page .rating-number {
    color: #6a173b;
}

.product-review-view-page .rating-stars {
    color: #dfa32b;
    filter: drop-shadow(0 2px 3px rgba(217,164,65,.16));
}

.product-review-view-page .rating-title {
    border: 1px solid #eadde3;
    border-radius: 13px;
    background: rgba(255,255,255,.86);
    box-shadow: 0 4px 12px rgba(77,28,48,.05);
}

.product-review-view-page .feedback-panel {
    border: 1px solid #eadde3;
    border-radius: 18px;
    background: linear-gradient(145deg, #fbf8fa, #f7f3f5);
}

.product-review-view-page .review-label,
.product-review-view-page .meta-label {
    color: #9a818c;
}

.product-review-view-page .review-text {
    border: 1px solid #eadde3;
    border-radius: 16px;
    background: #fcfafb;
    box-shadow: inset 0 1px 2px rgba(77,28,48,.025);
}

.product-review-view-page .media-card {
    border: 1px solid #eadde3;
    border-radius: 20px;
    box-shadow: 0 8px 24px rgba(77,28,48,.055);
}

.product-review-view-page .media-item {
    border-color: #eadde3 !important;
    box-shadow: 0 5px 14px rgba(77,28,48,.06);
}

.product-review-view-page .media-item img {
    transition: transform .25s ease;
}

.product-review-view-page .media-item:hover img {
    transform: scale(1.05);
}

.product-review-view-page .media-overlay {
    background: rgba(95,23,53,.08);
}

.product-review-view-page .media-item:hover .media-overlay {
    background: rgba(95,23,53,.18);
}

.product-review-view-page .review-video {
    border: 1px solid #eadde3;
    box-shadow: 0 8px 20px rgba(77,28,48,.08);
}

.product-review-view-page .side-card {
    overflow: hidden;
    border: 1px solid #eadde3;
    border-radius: 20px;
    background: #fff;
    box-shadow: 0 8px 24px rgba(77,28,48,.055);
}

.product-review-view-page .side-row {
    transition: background .2s ease;
}

.product-review-view-page .side-row:hover {
    background: #fff8fa;
}

.product-review-view-page .product-name {
    color: #6b1a3a;
}

.product-review-view-page .order-number {
    color: #8b2452;
    padding: 7px 10px;
    border-radius: 10px;
    background: #f8eaf0;
    display: inline-block;
}

.product-review-view-page .timeline-dot {
    width: 11px;
    height: 11px;
    margin-top: 3px;
    border: 3px solid #f3dce6;
    background: #8b2452;
    box-sizing: content-box;
}

@media (max-width: 639px) {
    .product-review-view-page .view-hero {
        padding: 18px;
    }

    .product-review-view-page .view-title {
        font-size: 23px;
    }
}
</style>


<div class="product-review-view-page min-h-screen bg-[#f7f4f6] px-4 py-6 sm:px-6 lg:px-8">

    <div class="mx-auto max-w-[1200px]">

        <div class="view-hero mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

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

                    <span class="breadcrumb-current">View Review</span>
                </div>

                <h1 class="view-title mt-2 text-2xl font-bold">
                    Review Details
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    View complete customer review information.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">

                <a
                    href="{{ admin_route('product-reviews.edit', $productReview) }}"
                    class="hero-edit inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-[#6B1A3A] px-4 text-sm font-semibold text-white transition hover:bg-[#4A0F26]"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m14.5 5.5 4 4M4 20l3.5-.8L19 7.7a2.12 2.12 0 0 0-3-3L4.5 16.7 4 20Z"/>
                    </svg>
                    Edit
                </a>

                <a
                    href="{{ admin_route('product-reviews.index') }}"
                    class="hero-back inline-flex h-10 items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 text-sm font-semibold text-gray-600 transition hover:bg-gray-50"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 18-6-6 6-6"/>
                    </svg>
                    Back
                </a>

            </div>

        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            <div class="space-y-6 lg:col-span-2">

                <div class="view-card">

                    <div class="card-head">

                        <div class="flex items-center justify-between gap-4">

                            <div class="flex items-center gap-3">

                                <div class="section-icon flex items-center justify-center rounded-xl bg-[#6B1A3A]/10 text-[#6B1A3A]">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h8M8 14h5m7-2a8 8 0 1 1-16 0c0-1.58.46-3.05 1.25-4.29L4 4l3.71 1.25A8 8 0 0 1 12 4a8 8 0 0 1 8 8Z"/>
                                    </svg>
                                </div>

                                <div>
                                    <h2 class="text-base font-bold text-gray-800">
                                        Customer Review
                                    </h2>

                                    <p class="text-xs text-gray-400">
                                        Review #{{ $productReview->id }}
                                    </p>
                                </div>

                            </div>

                        
                        </div>

                    </div>

                    <div class="p-6">

                        @if($productReview->rating)

                            <div class="rating-panel mb-6 p-5">

                                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                                            Rating
                                        </p>

                                        <div class="mt-2 flex items-center gap-3">

                                            <span class="rating-number text-3xl font-bold">
                                                {{ $productReview->rating }}
                                            </span>

                                            <div class="rating-stars flex gap-1">

                                                @for($i = 1; $i <= 5; $i++)

                                                    <svg class="h-6 w-6" fill="{{ $i <= $productReview->rating ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-width="1.5" d="m12 2.8 2.78 5.63 6.22.9-4.5 4.39 1.06 6.2L12 17l-5.56 2.92 1.06-6.2L12 2.8Z"/>
                                                    </svg>

                                                @endfor

                                            </div>

                                        </div>
                                    </div>

                                    <div class="rating-title px-4 py-3">
                                        <p class="text-xs text-gray-400">Rating Title</p>
                                        <p class="mt-1 text-sm font-bold text-gray-700">
                                            {{ $productReview->title }}
                                        </p>
                                    </div>

                                </div>

                            </div>

                        @else

                            <div class="feedback-panel mb-6 p-5">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 text-gray-400">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h8M8 14h5"/>
                                        </svg>
                                    </div>

                                    <div>
                                        <p class="text-sm font-semibold text-gray-700">
                                            Feedback without rating
                                        </p>

                                        <p class="mt-1 text-xs text-gray-400">
                                            This feedback is not included in the product rating calculation.
                                        </p>
                                    </div>

                                </div>

                            </div>

                        @endif

                        <div>

                            <p class="review-label mb-3 text-xs font-semibold uppercase tracking-wide">
                                Review
                            </p>

                            <div class="review-text p-5">

                                <p class="whitespace-pre-line text-sm leading-7 text-gray-700">
                                    {{ $productReview->review }}
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

                @if(!empty($productReview->image_urls) || $productReview->video_url)

                    <div class="view-card">

                        <div class="card-head">

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
                                        Images and video attached to this review.
                                    </p>
                                </div>

                            </div>

                        </div>

                        <div class="p-6">

                            @if(!empty($productReview->image_urls))

                                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">

                                    @foreach($productReview->image_urls as $image)

                                        <a
                                            href="{{ $image }}"
                                            target="_blank"
                                            class="media-item group relative aspect-square overflow-hidden rounded-2xl border border-gray-100 bg-gray-50"
                                        >
                                            <img
                                                src="{{ $image }}"
                                                alt="Review image"
                                                class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                            >

                                            <div class="media-overlay absolute inset-0 flex items-center justify-center transition">
                                                <div class="flex h-9 w-9 scale-75 items-center justify-center rounded-full bg-white text-gray-700 opacity-0 shadow transition group-hover:scale-100 group-hover:opacity-100">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 3h6v6M10 14 21 3M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </a>

                                    @endforeach

                                </div>

                            @endif

                            @if($productReview->video_url)

                                <div class="{{ !empty($productReview->image_urls) ? 'mt-6' : '' }}">

                                    <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-400">
                                        Video
                                    </p>

                                    <video
                                        controls
                                        preload="metadata"
                                        class="review-video max-h-[450px] w-full rounded-2xl bg-black"
                                    >
                                        <source src="{{ $productReview->video_url }}">
                                    </video>

                                </div>

                            @endif

                        </div>

                    </div>

                @endif

            </div>

            <div class="space-y-6">

                <div class="media-card rounded-2xl bg-white shadow-sm">

                    <div class="border-b border-gray-100 px-5 py-4">
                        <h2 class="text-sm font-bold text-gray-800">
                            Product & Customer
                        </h2>
                    </div>

                    <div class="divide-y divide-gray-100">

                        <div class="p-5">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                                Product
                            </p>

                            <p class="product-name mt-2 text-sm font-semibold">
                                {{ $productReview->product?->name ?? 'Deleted Product' }}
                            </p>
                        </div>

                        <div class="p-5">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                                Customer
                            </p>

                            @if($productReview->user)

                                <p class="mt-2 text-sm font-semibold text-gray-700">
                                    {{ $productReview->user->name }}
                                </p>

                                <p class="mt-1 break-all text-xs text-gray-400">
                                    {{ $productReview->user->email }}
                                </p>

                            @else

                                <p class="mt-2 text-sm text-gray-400">
                                    Guest / Deleted User
                                </p>

                            @endif
                        </div>

                        <div class="grid grid-cols-2 divide-x divide-gray-100">

                            <div class="side-row p-5">
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                                    Size
                                </p>

                                <p class="mt-2 text-sm font-semibold text-gray-700">
                                    {{ $productReview->size ?: '—' }}
                                </p>
                            </div>

                            <div class="side-row p-5">
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                                    Color
                                </p>

                                <p class="mt-2 text-sm font-semibold text-gray-700">
                                    {{ $productReview->color ?: '—' }}
                                </p>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="side-card">

                    <div class="border-b border-gray-100 px-5 py-4">
                        <h2 class="text-sm font-bold text-gray-800">
                            Order Information
                        </h2>
                    </div>

                    <div class="side-row p-5">

                        @if($productReview->order)

                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                                Order Number
                            </p>

                            <p class="order-number text-sm font-bold">
                                {{ $productReview->order->order_number }}
                            </p>

                            @if($productReview->order->status)
                                <p class="mt-2 text-xs text-gray-400">
                                    Status: {{ ucfirst($productReview->order->status) }}
                                </p>
                            @endif

                        @else

                            <p class="text-sm text-gray-400">
                                No order associated with this review.
                            </p>

                        @endif

                    </div>

                </div>

                <div class="side-card">

                    <div class="border-b border-gray-100 px-5 py-4">
                        <h2 class="text-sm font-bold text-gray-800">
                            Review Timeline
                        </h2>
                    </div>

                    <div class="space-y-5 p-5">

                        <div class="flex gap-3">

                            <div class="timeline-dot shrink-0 rounded-full"></div>

                            <div>
                                <p class="text-xs text-gray-400">
                                    Created
                                </p>

                                <p class="mt-1 text-sm font-semibold text-gray-700">
                                    {{ $productReview->created_at?->format('d M Y, h:i A') }}
                                </p>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection