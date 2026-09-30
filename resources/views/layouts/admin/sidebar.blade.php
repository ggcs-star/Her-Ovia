<div 
    x-show="sidebarOpen" 
    class="fixed inset-0 bg-gray-900/30 z-30 md:hidden backdrop-blur-sm transition-opacity duration-300" 
    @click="sidebarOpen = false"
></div>

<aside 
    class="fixed top-0 left-0 bottom-0 z-40 w-[260px] bg-white text-gray-600 transition-transform duration-300 ease-out transform shadow-xl overflow-y-auto border-r border-gray-100"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    style="scrollbar-width: thin; scrollbar-color: #e2e8f0 #f1f5f9"
>
  @php
    $organization = \App\Models\Organization::where('is_active', 1)->first();
@endphp

@if($organization && $organization->logo_url)
<div class="h-[82px] flex items-center px-5 bg-gradient-to-r from-white to-gray-50 border-b border-gray-100">
    <div class="flex items-center justify-center w-full">
        <img
            src="{{ $organization->logo_url }}"
            alt="{{ $organization->name }}"
            class="h-14 w-auto object-contain"
        >
    </div>
</div>
@endif

    <nav class="px-3 py-4 space-y-0.5">
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200
           {{ request()->routeIs('dashboard') ? 'bg-[#8B2452] text-white font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]' }}">
            <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-sm">Dashboard</span>
        </a>

        <a href="{{ admin_route('suppliers.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200
           {{ request()->routeIs('suppliers.*') ? 'bg-[#8B2452] text-white font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]' }}">
            <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span class="text-sm">Suppliers</span>
        </a>

        <a href="{{ admin_route('categories.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 
           {{ request()->routeIs('categories.*') ? 'bg-[#8B2452] text-white font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]' }}">
            <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
            </svg>
            <span class="text-sm">Categories</span>
        </a>

        <a href="{{ admin_route('products.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200
           {{ (request()->routeIs('products.*') && !request()->routeIs('products.list') && !request()->routeIs('products.push')) ? 'bg-[#8B2452] text-white font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]' }}">
            <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
            </svg>
            <span class="text-sm">Items</span>
        </a>
        <a href="{{ admin_route('purchase-orders.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200
            {{ request()->routeIs('purchase-orders.*') ? 'bg-[#8B2452] text-white font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]' }}">

                <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M3 7h18M6 7V5a2 2 0 012-2h8a2 2 0 012 2v2M5 7v12a2 2 0 002 2h10a2 2 0 002-2V7M9 12h6M12 9v6"/>
                </svg>

                <span class="text-sm">Purchase Orders</span>

        </a>

        <a href="{{ admin_route('products.list') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200
           {{ (request()->routeIs('products.list') || request()->routeIs('products.push')) ? 'bg-[#8B2452] text-white font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]' }}">
            <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <span class="text-sm">Push Products</span>
        </a>

        
        <a href="{{ admin_route('orders.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200
           {{ request()->routeIs('orders.*') ? 'bg-[#8B2452] text-white font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]' }}">
            <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h18l-2 13H5L3 3zm6 16a2 2 0 100 4 2 2 0 000-4zm8 0a2 2 0 100 4 2 2 0 000-4z"/>
            </svg>
            <span class="text-sm">Orders</span>
        </a>

   

        <a href="{{ admin_route('invoices.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200
           {{ request()->routeIs('invoices.*') ? 'bg-[#8B2452] text-white font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]' }}">
            <i class="fas fa-file-invoice w-5 h-5 text-center" style="font-weight: 900;"></i>
            <span class="text-sm">Invoices</span>
        </a>

        <a href="{{ admin_route('coupons.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200
           {{ request()->routeIs('coupons.*') ? 'bg-[#8B2452] text-white font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]' }}">
            <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 14l6-6m-7 0h.01M16 14h.01M5 7h14v10H5z"/>
            </svg>
            <span class="text-sm">Coupons</span>
        </a>

        <a href="{{ admin_route('banners.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200
           {{ request()->routeIs('banners.*') ? 'bg-[#8B2452] text-white font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]' }}">
            <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 8h10M7 12h6m-6 4h10M5 4h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z"/>
            </svg>
            <span class="text-sm">Banners</span>
        </a>

        <!-- <a href="{{ admin_route('reels.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200
           {{ request()->routeIs('reels.*') ? 'bg-[#8B2452] text-white font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]' }}">
            <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 4h10M7 8h10M7 12h10M5 4v16l14-8L5 4z"/>
            </svg>
            <span class="text-sm">Reels</span>
        </a> -->
        <a href="{{ route('admin.instagram') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200
            {{ request()->routeIs('admin.instagram') ? 'bg-[#8B2452] text-white font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]' }}">

                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M7.75 2C4.574 2 2 4.574 2 7.75v8.5C2 19.426 4.574 22 22 16.25v-8.5C22 4.574 19.426 2 16.25 2h-8.5zm0 2h8.5A3.75 3.75 0 0120 7.75v8.5A3.75 3.75 0 0116.25 20h-8.5A3.75 3.75 0 014 16.25v-8.5A3.75 3.75 0 017.75 4zm8.75 1a1.25 1.25 0 100 2.5 1.25 1.25 0 000-2.5zM12 7a5 5 0 100 10 5 5 0 000-10zm0 2a3 3 0 110 6 3 3 0 010-6z"/>
                </svg>

                <span class="text-sm">Instagram</span>
            </a>
        <!-- <a href="{{ admin_route('stock.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200
           {{ (request()->routeIs('stock.*') && !request()->routeIs('stock.settings')) ? 'bg-[#8B2452] text-white font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]' }}">
            <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 7l9-4 9 4-9 4-9-4z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 7v10l9 4 9-4V7"/>
            </svg>
            <span class="text-sm">Stock Management</span>
        </a> -->
        <a href="{{ admin_route('inventory.dashboard') }}"
        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200
        {{ request()->routeIs('inventory.*') ? 'bg-[#8B2452] text-white font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]' }}">
            <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <span class="text-sm">Inventory</span>
        </a>

        <a href="{{ admin_route('stock.settings') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200
           {{ request()->routeIs('stock.settings') ? 'bg-[#8B2452] text-white font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]' }}">
            <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6V4m0 16v-2m8-6h-2M6 12H4m13.657-5.657l-1.414 1.414M7.757 16.243l-1.414 1.414M12 8a4 4 0 100 8 4 4 0 000-8z"/>
            </svg>
            <span class="text-sm">Stock Alert</span>
        </a>

        <a href="{{ admin_route('customers.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200
           {{ request()->routeIs('customers.*') ? 'bg-[#8B2452] text-white font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]' }}">
            <i class="fas fa-user-friends w-5 h-5 text-center" style="font-weight: 900;"></i>
            <span class="text-sm">Customers</span>
        </a>


        <!-- <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]">
            <svg class="w-5 h-5 stroke-[2.5]" fill="currentColor" viewBox="0 0 24 24">
                <path d="M6.012 18H21V8a2 2 0 0 0-2-2h-8L9 4H3a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h1.012a3 3 0 1 0 6 0m5 0h4.976a3 3 0 1 0 6 0H11z"/>
            </svg>
            <span class="text-sm">Marketplace</span>
        </a> -->

        <!-- <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]">
            <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 17v-2m3 2v-4m3 4v-6" />
            </svg>
            <span class="text-sm">Reports</span>
        </a> -->
     <a href="{{ admin_route('banks.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200
           {{ request()->routeIs('banks.*') ? 'bg-[#8B2452] text-white font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]' }}">
            <i class="fas fa-university w-5 h-5 text-center" style="font-weight: 900;"></i>
            <span class="text-sm">Banks</span>
        </a>
        <a href="{{ admin_route('warehouses.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200
           {{ request()->routeIs('warehouses.*') ? 'bg-[#8B2452] text-white font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]' }}">
            <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6V4m0 16v-2m8-6h-2M6 12H4m13.657-5.657l-1.414 1.414M7.757 16.243l-1.414 1.414"/>
            </svg>
            <span class="text-sm">Settings</span>
        </a>
    </nav>
</aside>

<style>
    aside::-webkit-scrollbar {
        width: 4px;
    }
    aside::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    aside::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
    aside::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
</style>