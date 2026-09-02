@extends('layouts.admin')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Categories</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manage your catalog hierarchy and visibility settings</p>
        </div>
        <a href="{{ admin_route('categories.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 shadow-md" style="background: var(--primary-light); color: white;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Add Category
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-3">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-medium text-emerald-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-medium text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <form method="GET" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Search</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input
                                type="text"
                                name="search"
                                id="categorySearch"
                                class="w-full pl-10 pr-8 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-base auto-submit"
                                placeholder="Name or slug..."
                                value="{{ request('search') }}"
                                autocomplete="off"
                            >
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-base bg-white auto-submit">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Visibility</label>
                        <select name="visibility" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-base bg-white auto-submit">
                            <option value="">All Visibility</option>
                            <option value="public" {{ request('visibility') == 'public' ? 'selected' : '' }}>Public</option>
                            <option value="private" {{ request('visibility') == 'private' ? 'selected' : '' }}>Private</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Parent Category</label>
                        <div class="relative">
                            <select name="parent_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20 transition-all text-base bg-white appearance-none auto-submit">
    <option value="">All Parents</option>
    @foreach($parents as $parent)
        <option value="{{ $parent->id }}" {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
            {{ $parent->name }}
        </option>
        @foreach($parent->children as $child)
            <option value="{{ $child->id }}" {{ request('parent_id') == $child->id ? 'selected' : '' }}>
                &nbsp;&nbsp;— {{ $child->name }}
            </option>
            @foreach($child->children as $grandchild)
                <option value="{{ $grandchild->id }}" {{ request('parent_id') == $grandchild->id ? 'selected' : '' }}>
                    &nbsp;&nbsp;&nbsp;&nbsp;— {{ $grandchild->name }}
                </option>
            @endforeach
        @endforeach
    @endforeach
</select>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="px-4 py-2 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-gray-300 text-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20" form="bulkDeleteForm">
                <span class="text-sm font-medium text-gray-600">Select All</span>
            </div>
            <button type="submit" form="bulkDeleteForm" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Delete Selected
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px]">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left w-10">
                            <span class="sr-only">Select</span>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-16">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Slug</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Visibility</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php
                        function renderCategoryRow($category, $level = 0, $parentId = null) {
                            $padding = $level * 20;
                            $parentClass = $parentId ? 'parent-' . $parentId : '';
                            $isHidden = $parentId ? 'd-none' : '';
                    @endphp
        <tr class="category-row {{ $isHidden }} {{ $parentClass }}" data-id="{{ $category->id }}">
            <td class="px-4 py-3">
                <input class="row-checkbox w-4 h-4 rounded border-gray-300 text-[#8B2452] focus:ring-2 focus:ring-[#8B2452]/20" type="checkbox" name="ids[]" value="{{ $category->id }}" form="bulkDeleteForm">
            </td>
            <td class="px-4 py-3 text-sm font-medium text-gray-500">{{ $category->serial ?? '#' }}</td>
            <td class="px-4 py-3">
                <div class="flex items-center gap-2" style="padding-left: {{ $padding }}px;">
                    @if($category->children->count())
                        <button type="button" class="toggle-icon w-5 h-5 rounded bg-gray-100 text-gray-600 text-xs flex items-center justify-center hover:bg-gray-200 transition-colors">
                            ▶
                        </button>
                    @endif
                    @if($category->image_url)
                        <img src="{{ $category->image_url }}" width="28" height="28" class="rounded border border-gray-200 object-cover category-image cursor-zoom-in shadow-sm" data-full="{{ $category->image_url }}">
                    @endif
                    <a href="{{ route('admin.categories.details', $category->id) }}" class="text-sm font-semibold text-gray-700 hover:text-[#8B2452] transition-colors">
                        {{ $category->name }}
                    </a>
                </div>
            </td>
            <td class="px-4 py-3">
                <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $category->slug }}</span>
            </td>
            <td class="px-4 py-3">
                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $category->visibility == 'public' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ ucfirst($category->visibility) }}
                </span>
            </td>
            <td class="px-4 py-3">
                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $category->status == 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ ucfirst($category->status) }}
                </span>
            </td>
            <td class="px-4 py-3 text-center">
                <div class="flex items-center justify-center gap-1.5">
                    <a href="{{ admin_route('categories.edit', $category) }}" class="p-1.5 text-gray-400 hover:text-[#8B2452] transition-colors rounded-lg hover:bg-indigo-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                    <form action="{{ admin_route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 transition-colors rounded-lg hover:bg-red-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
            @php
                    foreach($category->children as $child) {
                        renderCategoryRow($child, $level + 1, $category->id);
                    }
                }
            @endphp

            @forelse($categories as $category)
                @php renderCategoryRow($category, 0, null); @endphp
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        <p class="text-gray-500 text-sm font-medium">No categories found matching your filters</p>
                    </td>
                </tr>
            @endforelse
                </tbody>
            </table>
        </div>

        <form method="POST" action="{{ admin_route('categories.bulk-delete') }}" id="bulkDeleteForm">
            @csrf
        </form>

        @if($categories->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/50">
                {{ $categories->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<div id="imagePreviewModal" class="image-preview-modal shadow-lg">
    <div class="modal-content border-0 rounded-4 overflow-hidden">
        <img id="previewImage" class="img-fluid" />
    </div>
</div>

<div id="filterSidebar" class="filter-sidebar shadow-2xl">
    <div class="filter-sidebar-header bg-white text-dark py-4 px-4 border-bottom d-flex align-items-center justify-content-between">
        <h5 class="mb-0 fw-bold">
            <i class="fas fa-filter me-2" style="color: #8B2452;"></i>Advanced Filter
        </h5>
        <button type="button" id="closeFilterSidebar">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="filter-sidebar-body p-4 bg-light bg-opacity-50">
        <div class="mb-4">
            <label class="form-label fw-bold small text-muted text-uppercase mb-2 ls-1">Select Field</label>
            <select id="advField" class="form-control form-select shadow-xs py-2 border">
                <option value="name">Name</option>
                <option value="slug">Slug</option>
                <option value="status">Status</option>
                <option value="visibility">Visibility</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="form-label fw-bold small text-muted text-uppercase mb-2 ls-1">Condition</label>
            <select id="advCondition" class="form-control form-select shadow-xs py-2 border">
                <option value="like">Contains</option>
                <option value="=">Equals</option>
                <option value="!=">Not Equals</option>
                <option value="starts_with">Starts With</option>
                <option value="ends_with">Ends With</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="form-label fw-bold small text-muted text-uppercase mb-2 ls-1">Filter Value</label>
            <input type="text" id="advValue" class="form-control shadow-xs py-2 border" placeholder="Type here...">
        </div>
        <button type="button" id="applyAdvancedFilter" class="btn w-100 py-3 rounded-3 shadow fw-bold" style="background: var(--primary-light); color: white; border: none;">
            <i class="fas fa-check-circle me-2"></i>Apply Changes
        </button>
        <button type="button" id="clearAdvancedFilter" class="btn btn-outline-secondary w-100 mt-3 py-2 rounded-3 fw-medium">
            <i class="fas fa-times-circle me-2"></i>Clear Filter
        </button>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 0.05rem; }
    .shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .bg-light-50 { background-color: rgba(248, 249, 250, 0.5); }
    .filter-sidebar {
        position: fixed;
        top: 0;
        right: 0;
        width: 400px;
        height: 100%;
        background: white;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        z-index: 1000;
        box-shadow: -4px 0 20px rgba(0,0,0,0.1);
    }
    .filter-sidebar.open {
        transform: translateX(0);
    }
    .filter-sidebar-header {
        padding: 18px 24px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .filter-sidebar-body {
        padding: 24px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const autoSubmitElements = document.querySelectorAll('.auto-submit');
        const filterForm = document.getElementById('filterForm');
        
        autoSubmitElements.forEach(element => {
            element.addEventListener('change', function() {
                filterForm.submit();
            });
            
            if (element.tagName === 'INPUT') {
                let timeout;
                element.addEventListener('input', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        filterForm.submit();
                    }, 500);
                });
            }
        });
    });
</script>
@endsection