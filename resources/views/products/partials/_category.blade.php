<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-5">
    <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-[#8B2452]/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-[#8B2452]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-800">Category</h3>
            <span class="ml-auto text-xs text-gray-400">Organize your product</span>
        </div>
    </div>

    <div class="p-5">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            <!-- Main Category -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Category <span class="text-red-500">*</span>
                </label>
                <select id="mainCategory" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white">
                    <option value="">Select category</option>
                    @php
                        $selectedMain = old('main_category', $product->category?->parent?->parent?->id ?? $product->category?->parent?->id ?? '');
                    @endphp
                    @foreach ($categories->whereNull('parent_id') as $cat)
                        <option value="{{ $cat->id }}" {{ $selectedMain == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">Choose the primary category for this product.</p>
            </div>

            <!-- Sub Category -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Sub Category
                </label>
                <select id="subCategory" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white">
                    <option value="">Select sub category</option>
                </select>
                <p class="text-xs text-gray-400 mt-1">Available after selecting main category.</p>
            </div>

            <!-- Sub-Sub Category -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Sub-Sub Category
                </label>
                <select id="subSubCategory" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm bg-white">
                    <option value="">Select sub-sub category</option>
                </select>
                <p class="text-xs text-gray-400 mt-1">Available after selecting sub category.</p>
            </div>

            <!-- Brand -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Brand
                </label>
                <input type="text"
                       name="brand"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-sm"
                       placeholder="e.g. Samsung, Nike"
                       value="{{ old('brand', $product->brand ?? '') }}">
                <p class="text-xs text-gray-400 mt-1">Optional – helps with filtering & search.</p>
            </div>

            <!-- Hidden final category -->
            <input type="hidden"
                   name="category_id"
                   id="finalCategoryId"
                   value="{{ old('category_id', $product->category_id ?? '') }}">
        </div>
    </div>
</div>