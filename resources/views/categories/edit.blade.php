@extends('layouts.admin')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ admin_route('dashboard') }}" class="hover:text-[#8B2452] transition-colors">Dashboard</a>
                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ admin_route('categories.index') }}" class="hover:text-[#8B2452] transition-colors">Categories</a>
                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-600">Edit Category</span>
            </div>
            <h1 class="text-xl font-bold text-gray-800">Edit Category</h1>
            <p class="text-xs text-gray-500 mt-0.5">Update category information</p>
        </div>
        <a href="{{ admin_route('categories.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3">
            <div class="flex items-start gap-2">
                <svg class="w-4 h-4 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="text-sm font-medium text-red-700 mb-1">Please fix the following errors:</p>
                    <ul class="text-xs text-red-600 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ admin_route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden mb-4">
            <div class="px-4 py-2.5 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-sm font-semibold text-gray-800">Basic Information</h3>
                </div>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Category Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm"
                               placeholder="Enter category name">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Slug <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" required
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm"
                               placeholder="example-category">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Description</label>
                        <textarea name="description" rows="2"
                                  class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm"
                                  placeholder="Enter category description...">{{ old('description', $category->description) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Category Hierarchy</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Main Category</label>
                                <select id="mainCategory" name="main_category" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm bg-white">
                                    <option value="">None (Top Level)</option>
                                    @foreach($mainCategories as $main)
                                        <option value="{{ $main->id }}" {{ old('main_category', $mainCategoryId) == $main->id ? 'selected' : '' }}>
                                            {{ $main->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Subcategory</label>
                                <select id="subCategory" name="subcategory" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm bg-white">
                                    <option value="">None</option>
                                    @foreach($subcategories as $sub)
                                        <option value="{{ $sub->id }}" {{ old('subcategory', $subCategoryId) == $sub->id ? 'selected' : '' }}>
                                            {{ $sub->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        {{-- ✅ CURRENT HIERARCHY SHOW --}}
                        @if($currentMain || $currentParent)
                            <div class="mt-2 p-2 bg-gray-50 rounded-lg border border-gray-100">
                                <p class="text-xs text-gray-500">Current Hierarchy:</p>
                                <p class="text-sm font-medium text-gray-700">
                                    @if($currentMain)
                                        {{ $currentMain->name }}
                                        @if($currentParent)
                                            → {{ $currentParent->name }}
                                        @endif
                                    @elseif($currentParent)
                                        {{ $currentParent->name }}
                                    @endif
                                    → <span class="text-[#8B2452]">{{ $category->name }}</span>
                                </p>
                            </div>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Category Image</label>
                        @if ($category->image_url)
                            <div class="mb-2">
                                <img src="{{ $category->image_url }}" width="50" height="50" class="rounded-lg border border-gray-200 object-cover">
                            </div>
                        @endif
                        <input type="file" name="image_url"
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm file:mr-2 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden mb-4">
            <div class="px-4 py-2.5 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <h3 class="text-sm font-semibold text-gray-800">SEO Settings</h3>
                </div>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Meta Title</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title', $category->meta_title) }}"
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm"
                               placeholder="SEO title">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Meta Keywords</label>
                        <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $category->meta_keywords) }}"
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm"
                               placeholder="keyword1, keyword2, keyword3">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Meta Description</label>
                        <textarea name="meta_description" rows="2"
                                  class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm"
                                  placeholder="Brief description for search engines...">{{ old('meta_description', $category->meta_description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden mb-4">
            <div class="px-4 py-2.5 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    </svg>
                    <h3 class="text-sm font-semibold text-gray-800">Settings</h3>
                </div>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}"
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Featured</label>
                        <select name="is_featured" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm bg-white">
                            <option value="0" {{ old('is_featured', $category->is_featured) == 0 ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('is_featured', $category->is_featured) == 1 ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Visibility <span class="text-red-500">*</span>
                        </label>
                        <select name="visibility" required class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm bg-white">
                            <option value="public" {{ old('visibility', $category->visibility) == 'public' ? 'selected' : '' }}>Public</option>
                            <option value="private" {{ old('visibility', $category->visibility) == 'private' ? 'selected' : '' }}>Private</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" required class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-1 focus:ring-[#8B2452] transition-all text-sm bg-white">
                            <option value="active" {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ admin_route('categories.index') }}" class="px-5 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-medium transition-all duration-200 shadow-sm" style="background: #8B2452; color: white;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                Update Category
            </button>
        </div>
    </form>
</div>
@endsection