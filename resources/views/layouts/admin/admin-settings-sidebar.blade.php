{{-- SIDEBAR OVERLAY --}}
<div 
    x-show="sidebarOpen" 
    class="fixed inset-0 bg-gray-900/30 z-30 md:hidden backdrop-blur-sm transition-opacity duration-300" 
    @click="sidebarOpen = false"
    x-cloak
></div>
<style>
 :root {
            --primary: #6B1A3A;
            --primary-light: #8B2452;
            --primary-dark: #4A0F26;
            --primary-soft: #B84A6A;
            --primary-bg: rgba(139, 36, 82, 0.08);
            --primary-hover: rgba(139, 36, 82, 0.12);
            
            --gold: #F4B94E;
            --gold-light: #F5C46B;
            --gold-bg: rgba(244, 185, 78, 0.1);
        }
</style>

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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span class="text-sm">Back to Dashboard</span>
        </a>

        <a href="{{ admin_route('warehouses.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200
           {{ request()->routeIs('warehouses.*') ? 'bg-[#8B2452] text-white font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]' }}">
            <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
            </svg>
            <span class="text-sm">Warehouses</span>
        </a>

        <a href="{{ admin_route('variants.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200
           {{ request()->routeIs('variants.*') ? 'bg-[#8B2452] text-white font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]' }}">
            <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            <span class="text-sm">Variants</span>
        </a>

        <a href="{{ admin_route('organizations.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200
           {{ request()->routeIs('organizations.*') ? 'bg-[#8B2452] text-white font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]' }}">
            <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6" />
            </svg>
            <span class="text-sm">Organizations</span>
        </a>

        <a href="{{ admin_route('delivery-settings.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200
           {{ request()->routeIs('delivery-settings.*') ? 'bg-[#8B2452] text-white font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]' }}">
            <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h11M9 21V3m12 7l-3 3m0 0l-3-3m3 3V3" />
            </svg>
            <span class="text-sm">Delivery Settings</span>
        </a>

        <a href="{{ admin_route('app-settings.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200
           {{ request()->routeIs('app-settings.*') ? 'bg-[#8B2452] text-white font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]' }}">
            <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0-6v2m0 16v2m10-10h-2M4 12H2m15.66 6.34l-1.41-1.41M6.34 6.34 4.93 4.93m12.73 0-1.41 1.41M6.34 17.66l-1.41 1.41" />
            </svg>
            <span class="text-sm">App Settings</span>
        </a>
<a href="{{ admin_route('product-reviews.index') }}"
   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200
   {{ request()->routeIs('product-reviews.*') ? 'bg-[#8B2452] text-white font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]' }}">

    <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2.5"
            d="M7 8h10M7 12h6m8-1a8 8 0 11-16 0 8 8 0 0116 0z"
        />
    </svg>

    <span class="text-sm">Product Reviews</span>

</a>
        <a href="{{ admin_route('notification-settings.index') }}"
   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200
   {{ request()->routeIs('notification-settings.*') ? 'bg-[#8B2452] text-white font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#8B2452]' }}">

    <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2.5"
              d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0h6z"/>
    </svg>

    <span class="text-sm">Notification Settings</span>

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
    background: #cbd5e1;
    border-radius: 10px;
}
aside::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>