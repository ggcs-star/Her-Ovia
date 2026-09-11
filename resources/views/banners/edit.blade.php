@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-5">

    <div class="flex justify-between items-center mb-6 pb-2 border-b border-gray-200">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Banner</h1>
            <p class="text-sm text-gray-500 mt-1">Update banner details & images.</p>
        </div>
        <a href="{{ admin_route('banners.index') }}" class="px-4 py-2 rounded-lg transition" style="background: #f1f5f9; color: #64748b;">
            Back
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200">
            <ul class="mb-0 list-disc list-inside text-red-600 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ admin_route('banners.update', $banner) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                <h5 class="font-semibold text-gray-800">Basic Information</h5>
            </div>
            <div class="p-6">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">Heading</label>
                        <input type="text" name="title"
                               value="{{ old('title',$banner->title) }}"
                               class="form-control"
                               style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">Sub Heading</label>
                        <input type="text" name="subtitle"
                               value="{{ old('subtitle',$banner->subtitle) }}"
                               class="form-control"
                               style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">Desktop Image</label>
                        <input type="file" name="image" class="form-control"
                               style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                        @if($banner->image)
                            <div class="mt-2">
                                <img src="{{ $banner->image }}" class="rounded-lg border border-gray-200" width="180">
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">Mobile Image</label>
                        <input type="file" name="mobile_image" class="form-control"
                               style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                        @if($banner->mobile_image)
                            <div class="mt-2">
                                <img src="{{ $banner->mobile_image }}" class="rounded-lg border border-gray-200" width="100">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                <h5 class="font-semibold text-gray-800">Placement</h5>
            </div>
            <div class="p-6">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">Page</label>
                        <select name="page" class="form-control" style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                            <option value="home" {{ $banner->page=='home'?'selected':'' }}>Home</option>
                            <option value="category" {{ $banner->page=='category'?'selected':'' }}>Category</option>
                            <option value="product" {{ $banner->page=='product'?'selected':'' }}>Product</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">Position</label>
                        <select name="position" class="form-control" style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                            <option value="hero" {{ $banner->position=='hero'?'selected':'' }}>Hero</option>
                            <option value="mid" {{ $banner->position=='mid'?'selected':'' }}>Middle</option>
                            <option value="bottom" {{ $banner->position=='bottom'?'selected':'' }}>Bottom</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">Layout</label>
                        <select name="layout" class="form-control" style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                            <option value="right" {{ $banner->layout=='right'?'selected':'' }}>Right</option>
                            <option value="left" {{ $banner->layout=='left'?'selected':'' }}>Left</option>
                            <option value="center" {{ $banner->layout=='center'?'selected':'' }}>Center</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                <h5 class="font-semibold text-gray-800">CTA & Display</h5>
            </div>
            <div class="p-6">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">Button Text</label>
                        <input type="text" name="button_text"
                               value="{{ old('button_text',$banner->button_text) }}"
                               class="form-control"
                               style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">Button Link</label>
                        <input type="text" name="button_link"
                               value="{{ old('button_link',$banner->button_link) }}"
                               class="form-control"
                               style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">Text Color</label>
                        <input type="color" name="text_color"
                               value="{{ old('text_color',$banner->text_color) }}"
                               class="form-control form-control-color"
                               style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 4px; height: 42px;">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">Start Date</label>
                        <input type="date"
                            name="start_date"
                            value="{{ old('start_date', $banner->start_date ? \Carbon\Carbon::parse($banner->start_date)->format('Y-m-d') : '') }}"
                            class="form-control"
                            style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">End Date</label>
                        <input type="date"
                            name="end_date"
                            value="{{ old('end_date', $banner->end_date ? \Carbon\Carbon::parse($banner->end_date)->format('Y-m-d') : '') }}"
                            class="form-control"
                            style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                    </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label fw-semibold"
                                style="color: #4a5568; font-size: 13px;">
                                Sort Order
                            </label>

                            <input type="number"
                                name="sort_order"
                                value="{{ old('sort_order', $banner->sort_order ?? 0) }}"
                                min="0"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                        </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label fw-semibold" style="color: #4a5568; font-size: 13px;">Status</label>
                        <select name="status" class="form-control" style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                            <option value="1" {{ $banner->status ? 'selected':'' }}>Active</option>
                            <option value="0" {{ !$banner->status ? 'selected':'' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button class="px-5 py-2 rounded-lg transition" style="background: #440C2C; color: white;">
                Update Banner
            </button>
        </div>

    </form>
</div>
@endsection