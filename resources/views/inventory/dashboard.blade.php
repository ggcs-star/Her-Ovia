@extends('layouts.admin')

@section('content')
<style>
    /* ===== SIMPLE & CLEAN STYLES ===== */
    .inventory-dashboard {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    
    /* ===== STATS CARDS ===== */
    .stat-card {
        transition: all 0.2s ease;
        cursor: default;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -8px rgba(0,0,0,0.08);
    }
    
    /* ===== BADGES ===== */
    .badge-healthy {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 4px 14px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
    }
    .badge-low {
        background: #fff8e1;
        color: #e65100;
        padding: 4px 14px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
    }
    .badge-out {
        background: #fce4ec;
        color: #c62828;
        padding: 4px 14px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
    }
    .badge-synced {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 4px 14px;
        border-radius: 30px;
        font-size: 0.65rem;
        font-weight: 600;
        display: inline-block;
    }
    .badge-pending {
        background: #fff8e1;
        color: #e65100;
        padding: 4px 14px;
        border-radius: 30px;
        font-size: 0.65rem;
        font-weight: 600;
        display: inline-block;
    }
    
    /* ===== CHANNEL LOGOS ===== */
    .channel-logo {
        width: 20px;
        height: 20px;
        object-fit: contain;
        margin-right: 6px;
        vertical-align: middle;
    }
    .channel-label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        font-size: 0.6rem;
        font-weight: 600;
        color: #7c9eb2;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* ===== TABLE ===== */
    .inventory-table thead th {
        font-weight: 700;
        font-size: 0.6rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #3e6579;
        background: #f8fcff;
        border-bottom: 2px solid #e9f0f5;
        padding: 12px 14px;
        white-space: nowrap;
    }
    .inventory-table tbody td {
        padding: 12px 14px;
        border-bottom: 1px solid #f0f6fa;
        font-size: 0.8rem;
        color: #1a2e3f;
        vertical-align: middle;
    }
    
    .product-name {
        font-weight: 600;
        color: #0a1e2f;
        font-size: 0.85rem;
    }
    .product-sku {
        font-size: 0.6rem;
        color: #7c9eb2;
        font-family: monospace;
        background: #f1f7fd;
        padding: 1px 10px;
        border-radius: 12px;
        display: inline-block;
        margin-top: 2px;
    }
    .variant-count-label {
        font-size: 0.6rem;
        color: #8B2452;
        font-weight: 600;
        margin-left: 6px;
    }
    
    .product-row {
        cursor: pointer;
        transition: background 0.15s ease;
    }
    .product-row:hover {
        background: #f8fcff;
    }
    .product-row.expanded {
        background: #f8fcff;
        border-bottom: 2px solid #8B2452;
    }
    
    /* ===== VARIANT DROPDOWN ===== */
    .variant-row td {
        padding: 0 !important;
        background: #fafdff;
    }
    .variant-table-wrapper {
        padding: 10px 20px 10px 48px;
        background: #fafdff;
        border-top: 1px solid #e9f0f5;
        border-bottom: 1px solid #e9f0f5;
    }
    .variant-table {
        width: 100%;
        font-size: 0.72rem;
        border-collapse: collapse;
    }
    .variant-table thead th {
        background: #f0f6fa;
        color: #3e6579;
        font-size: 0.55rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 6px 12px;
        border-bottom: 1px solid #e9f0f5;
        text-align: center;
    }
    .variant-table tbody td {
        padding: 6px 12px;
        border-bottom: 1px solid #f0f6fa;
        text-align: center;
        font-size: 0.72rem;
    }
    .variant-table tbody td:first-child {
        text-align: left;
        font-weight: 500;
    }
    .variant-table tbody tr:hover {
        background: #f8fcff;
    }
    
    /* ===== BUTTONS ===== */
    .btn-manage {
        background: transparent;
        border: 1px solid #e2ecf5;
        border-radius: 30px;
        padding: 4px 16px;
        font-size: 0.65rem;
        font-weight: 600;
        color: #3e6579;
        transition: all 0.2s ease;
    }
    .btn-manage:hover {
        background: #f0f6fa;
        border-color: #8B2452;
        color: #8B2452;
    }
    
    .btn-filter {
        background: #8B2452;
        border: none;
        border-radius: 30px;
        padding: 8px 24px;
        font-size: 0.75rem;
        font-weight: 600;
        color: white;
        transition: all 0.2s ease;
    }
    .btn-filter:hover {
        background: #6B1A3A;
    }
    .btn-reset {
        background: transparent;
        border: 1px solid #e2ecf5;
        border-radius: 30px;
        padding: 8px 24px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #3e6579;
        transition: all 0.2s ease;
    }
    .btn-reset:hover {
        background: #f0f6fa;
        border-color: #8B2452;
        color: #8B2452;
    }
    
    /* ===== PAGINATION ===== */
    .pagination .page-link {
        border-radius: 30px !important;
        color: #3e6579;
        border: 1px solid #e2ecf5;
        padding: 6px 14px;
        font-size: 0.75rem;
        font-weight: 600;
        margin: 0 2px;
        transition: all 0.2s ease;
    }
    .pagination .page-link:hover {
        background: #f0f6fa;
        border-color: #8B2452;
        color: #8B2452;
    }
    .pagination .page-item.active .page-link {
        background: #8B2452;
        border-color: #8B2452;
        color: white;
    }
    .pagination .page-item.disabled .page-link {
        opacity: 0.4;
    }
    
    /* ===== FILTER BAR ===== */
    .filter-bar .form-control,
    .filter-bar .form-select {
        border-radius: 30px;
        border-color: #e2ecf5;
        font-size: 0.8rem;
        padding: 8px 16px;
        background: #fafcff;
    }
    .filter-bar .form-control:focus,
    .filter-bar .form-select:focus {
        border-color: #8B2452;
        box-shadow: 0 0 0 3px rgba(139, 36, 82, 0.08);
    }
    
    /* ===== CHANNEL CARDS ===== */
    .channel-card {
        transition: all 0.2s ease;
    }
    .channel-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -8px rgba(0,0,0,0.06);
    }
    .channel-card.overall {
        background: linear-gradient(135deg, #f8fcff, #f3e8f0);
        border: 2px solid #8B2452;
    }
    
    /* ===== OVERALL STOCK BAR ===== */
    .stock-bar-container {
        background: #f0f6fa;
        border-radius: 20px;
        height: 8px;
        width: 100%;
        overflow: hidden;
        margin-top: 6px;
    }
    .stock-bar {
        height: 100%;
        border-radius: 20px;
        background: linear-gradient(90deg, #8B2452, #B84A6A);
        transition: width 0.8s ease;
    }
    
    /* ===== SEARCH SUGGESTIONS ===== */
    .search-suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #e2ecf5;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        z-index: 1000;
        max-height: 300px;
        overflow-y: auto;
        display: none;
    }
    .search-suggestions .suggestion-item {
        padding: 10px 16px;
        cursor: pointer;
        font-size: 0.82rem;
        color: #0a1e2f;
        border-bottom: 1px solid #f0f6fa;
        transition: background 0.15s ease;
    }
    .search-suggestions .suggestion-item:hover {
        background: #f8fcff;
    }
    .search-suggestions .suggestion-item:last-child {
        border-bottom: none;
    }
    .search-wrapper {
        position: relative;
    }
    
    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .inventory-table thead th {
            font-size: 0.5rem;
            padding: 8px 6px;
        }
        .inventory-table tbody td {
            font-size: 0.7rem;
            padding: 8px 6px;
        }
        .product-name {
            font-size: 0.75rem;
        }
        .variant-table-wrapper {
            padding: 8px 12px 8px 24px;
        }
        .variant-table {
            font-size: 0.65rem;
        }
        .variant-table thead th,
        .variant-table tbody td {
            padding: 4px 8px;
        }
        .channel-logo {
            width: 16px;
            height: 16px;
        }
    }
</style>

<div class="container-fluid px-4 py-4 inventory-dashboard" style="background: #f4f7fc; min-height: 100vh;">

    {{-- ===== HEADER ===== --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 700; color: #0a1e2f; margin: 0;">
                <i class="fas fa-boxes me-2" style="color: #8B2452;"></i> Master Inventory Dashboard
            </h1>
            <p style="font-size: 0.85rem; color: #7c9eb2; margin: 2px 0 0;">
                Real-time overview of inventory across all sales channels and locations
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge px-3 py-2 rounded-5" style="background: #10b981; color: white; font-size: 0.65rem; font-weight: 600;">
                <i class="fas fa-circle text-white me-1" style="font-size: 0.35rem;"></i> Live Sync
            </span>
            <span class="badge bg-white text-dark px-3 py-2 rounded-5 border" style="font-size: 0.65rem; box-shadow: 0 2px 6px rgba(0,0,0,0.04);">
                <i class="far fa-clock me-1"></i> {{ now()->format('d M Y, h:i A') }}
            </span>
        </div>
    </div>

    {{-- ===== STATS CARDS ===== --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100 stat-card" style="border-left: 4px solid #8B2452;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span style="font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #7c9eb2;">Total Products</span>
                        <div style="font-size: 1.8rem; font-weight: 800; color: #0a1e2f;">{{ $summary['total_products'] }}</div>
                        <span style="font-size: 0.65rem; color: #7c9eb2;">Active SKUs</span>
                    </div>
                    <div class="rounded-circle p-3" style="background: #f3e8f0; width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-boxes" style="color: #8B2452; font-size: 1.3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100 stat-card" style="border-left: 4px solid #0f7b4b;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span style="font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #7c9eb2;">Master Stock</span>
                        <div style="font-size: 1.8rem; font-weight: 800; color: #0a1e2f;">{{ $summary['total_stock'] }}</div>
                        <span style="font-size: 0.65rem; color: #7c9eb2;">Total Units</span>
                    </div>
                    <div class="rounded-circle p-3" style="background: #e8f3ed; width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-warehouse" style="color: #0f7b4b; font-size: 1.3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100 stat-card" style="border-left: 4px solid #d97706;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span style="font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #7c9eb2;">Low Stock</span>
                        <div style="font-size: 1.8rem; font-weight: 800; color: #d97706;">{{ $lowStockCount ?? 0 }}</div>
                        <span style="font-size: 0.65rem; color: #7c9eb2;">Products</span>
                    </div>
                    <div class="rounded-circle p-3" style="background: #fef3c7; width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-exclamation-triangle" style="color: #d97706; font-size: 1.3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100 stat-card" style="border-left: 4px solid #dc2626;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span style="font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #7c9eb2;">Out of Stock</span>
                        <div style="font-size: 1.8rem; font-weight: 800; color: #dc2626;">{{ $outOfStockCount ?? 0 }}</div>
                        <span style="font-size: 0.65rem; color: #7c9eb2;">Products</span>
                    </div>
                    <div class="rounded-circle p-3" style="background: #fee2e2; width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-times-circle" style="color: #dc2626; font-size: 1.3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== CHANNEL SUMMARY WITH LOGOS ===== --}}
    <div class="row g-2 mb-4">
        @foreach($platforms as $platform)
            @php 
                $name = strtolower($platform->name);
                $icon = '📱';
                if(str_contains($name, 'amazon')) $icon = '🛒';
                elseif(str_contains($name, 'flipkart')) $icon = '🛒';
                elseif(str_contains($name, 'website') || str_contains($name, 'online')) $icon = '🌐';
                elseif(str_contains($name, 'offline')) $icon = '🏪';
                elseif(str_contains($name, 'meesho')) $icon = '🛍️';
            @endphp
            <div class="col-md-2 col-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 text-center channel-card" style="background: white;">
                    <div class="channel-label">
                        <span style="font-size:1.2rem;">{{ $icon }}</span>
                        {{ $platform->display_name ?? ucfirst($platform->name) }}
                    </div>
                    <h4 class="fw-bold mb-0" style="color: #1a56db; font-size: 1.4rem;">
                       @php
                            $summaryKey = match($name) {
                                'website', 'online', 'own website', 'our website', 'own_website' => 'total_website_available',
                                'offline', 'ofline' => 'total_offline_available',
                                'amazon' => 'total_amazon_available',
                                default => 'total_' . $name . '_available'
                            };
                        @endphp
                        {{ $summary[$summaryKey] ?? 0 }}
                    </h4>
                    <span style="font-size: 0.55rem; color: #7c9eb2;">Units Available</span>
                </div>
            </div>
        @endforeach
        <div class="col-md-4 col-12">
            <div class="card border-0 shadow-sm rounded-4 p-3 text-center channel-card overall">
                <span style="font-size: 0.6rem; color: #7c9eb2; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Overall Available Stock</span>
                <h4 class="fw-bold mb-0" style="color: #8B2452; font-size: 1.8rem;">{{ $summary['total_available'] }}</h4>
                @php
                    $maxStock = max($summary['total_available'], 1);
                    $overallPercent = min(($summary['total_available'] / $maxStock) * 100, 100);
                @endphp
                <div class="stock-bar-container">
                    <div class="stock-bar" style="width: {{ $overallPercent }}%;"></div>
                </div>
                <span style="font-size: 0.55rem; color: #7c9eb2; margin-top: 4px; display: block;">{{ $summary['total_available'] }} Units Available</span>
            </div>
        </div>
    </div>

    {{-- ===== VIEW TABS ===== --}}
    <div class="row mb-3">
        <div class="col-12">
            <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap; background: white; padding: 6px 16px; border-radius: 30px; border: 1px solid #e9f0f5; display: inline-flex;">
                <span style="font-size: 0.65rem; font-weight: 700; color: #7c9eb2; text-transform: uppercase; letter-spacing: 0.5px;">View:</span>
                
                {{-- ALL CHANNELS --}}
                <a href="{{ url('admin/inventory/dashboard') }}{{ request()->has('search') ? '?search='.request('search') : '' }}{{ request()->has('brand') ? '&brand='.request('brand') : '' }}{{ request()->has('category') ? '&category='.request('category') : '' }}{{ request()->has('supplier') ? '&supplier='.request('supplier') : '' }}" 
                   class="btn btn-sm {{ !request('channel') ? 'active' : '' }}" 
                   style="border-radius: 30px; padding: 4px 16px; font-size: 0.7rem; font-weight: 600; border: none; transition: all 0.3s ease; {{ !request('channel') ? 'background: #8B2452; color: white; box-shadow: 0 2px 8px rgba(139,36,82,0.25);' : 'background: transparent; color: #3e6579;' }}">
                    📊 All Channels
                </a>
                
                @foreach($platforms as $platform)
                    @php 
                        $name = strtolower($platform->name);
                        $channelParam = match($name) {
                            'website', 'online', 'own website', 'own_website' => 'website',
                            'offline', 'ofline' => 'offline',
                            default => $name
                        };
                        $icon = '📱';
                        if(str_contains($name, 'amazon')) $icon = '🛒';
                        elseif(str_contains($name, 'flipkart')) $icon = '🛒';
                        elseif(str_contains($name, 'website') || str_contains($name, 'online')) $icon = '🌐';
                        elseif(str_contains($name, 'offline')) $icon = '🏪';
                        elseif(str_contains($name, 'meesho')) $icon = '🛍️';
                    @endphp
                    <a href="{{ url('admin/inventory/dashboard?channel=' . $channelParam) }}{{ request()->has('search') ? '&search='.request('search') : '' }}{{ request()->has('brand') ? '&brand='.request('brand') : '' }}{{ request()->has('category') ? '&category='.request('category') : '' }}{{ request()->has('supplier') ? '&supplier='.request('supplier') : '' }}" 
                       class="btn btn-sm {{ request('channel') == $channelParam ? 'active' : '' }}" 
                       style="border-radius: 30px; padding: 4px 16px; font-size: 0.7rem; font-weight: 600; border: none; transition: all 0.3s ease; {{ request('channel') == $channelParam ? 'background: #8B2452; color: white; box-shadow: 0 2px 8px rgba(139,36,82,0.25);' : 'background: transparent; color: #3e6579;' }}">
                        {{ $icon }} {{ ucfirst($platform->display_name ?? $platform->name) }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ===== FILTERS WITH AJAX SEARCH ===== --}}
    <div class="filter-bar mb-4">
        <form method="GET" action="{{ url('admin/inventory/dashboard') }}" class="row g-2 align-items-end" id="filterForm">
            <div class="col-lg-2 col-md-3 col-6">
                <label style="font-size: 0.55rem; font-weight: 700; color: #7c9eb2; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; display: block;">Search</label>
                <div class="search-wrapper">
                    <input type="text" name="search" id="searchInput" class="form-control form-control-sm" 
                        placeholder="Search Product, SKU..." 
                        value="{{ request('search') }}" 
                        style="border-radius: 30px; border-color: #e2ecf5; font-size: 0.8rem; padding: 8px 16px; background: #fafcff;" 
                        autocomplete="off">
                    <div id="searchSuggestions" class="search-suggestions"></div>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <label style="font-size: 0.55rem; font-weight: 700; color: #7c9eb2; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; display: block;">Brands</label>
                <select name="brand" class="form-select form-select-sm" style="border-radius: 30px; border-color: #e2ecf5; font-size: 0.8rem; padding: 8px 16px; background: #fafcff;">
                    <option value="">All Brands</option>
                    @foreach($brands as $b)
                        <option value="{{ $b }}" {{ request('brand') == $b ? 'selected' : '' }}>{{ $b }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <label style="font-size: 0.55rem; font-weight: 700; color: #7c9eb2; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; display: block;">Categories</label>
                <select name="category" class="form-select form-select-sm" style="border-radius: 30px; border-color: #e2ecf5; font-size: 0.8rem; padding: 8px 16px; background: #fafcff;">
                    <option value="">All Categories</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ request('category') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <label style="font-size: 0.55rem; font-weight: 700; color: #7c9eb2; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; display: block;">Suppliers</label>
                <select name="supplier" class="form-select form-select-sm" style="border-radius: 30px; border-color: #e2ecf5; font-size: 0.8rem; padding: 8px 16px; background: #fafcff;">
                    <option value="">All Suppliers</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}" {{ request('supplier') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- ✅ CHANNEL FILTER - DYNAMIC --}}
            <div class="col-lg-2 col-md-4 col-6">
                <label style="font-size: 0.55rem; font-weight: 700; color: #7c9eb2; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; display: block;">Channels</label>
                <select name="channel" class="form-select form-select-sm" style="border-radius: 30px; border-color: #e2ecf5; font-size: 0.8rem; padding: 8px 16px; background: #fafcff;">
                    <option value="">📊 All Channels</option>
                    @foreach($platforms as $platform)
                        @php 
                            $channelVal = match(strtolower($platform->name)) {
                                'website', 'online', 'own website', 'own_website' => 'website',
                                'offline', 'ofline' => 'offline',
                                default => strtolower($platform->name)
                            };
                        @endphp
                        <option value="{{ $channelVal }}" {{ request('channel') == $channelVal ? 'selected' : '' }}>
                            {{ $platform->display_name ?? ucfirst($platform->name) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-2 col-md-4 col-12">
                <div class="d-flex gap-2 mt-md-0 mt-2">
                    <button type="submit" class="btn-filter w-100">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ url('admin/inventory/dashboard') }}" class="btn-reset">
                        <i class="fas fa-times me-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- ===== TABLE ===== --}}
    <div class="card border-0 shadow-sm rounded-4" style="background: white; overflow: hidden;">
        <div class="card-header bg-white py-3 px-4 border-0" style="border-bottom: 2px solid #f0f6fa;">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="mb-0 fw-bold" style="color: #0a1e2f; font-size: 0.95rem;">
                    <i class="fas fa-list me-2" style="color: #8B2452;"></i> Product Overview
                </h6>
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <span style="font-size: 0.65rem; color: #7c9eb2; cursor: pointer;">Channel Summary</span>
                    <span style="font-size: 0.65rem; color: #7c9eb2; cursor: pointer;">Show Variants</span>
                    <button class="btn btn-sm" style="background: #8B2452; color: white; border-radius: 30px; font-size: 0.65rem; padding: 4px 18px; font-weight: 600; border: none;">
                        <i class="fas fa-file-export me-1"></i> Export
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 inventory-table" style="font-size: 0.8rem;">
                    <thead>
                        <tr>
                            <th style="min-width: 200px;">PRODUCT</th>
                            <th class="text-center" style="min-width: 80px;">VARIANTS</th>
                            <th class="text-center" style="min-width: 100px;">MASTER STOCK</th>
                            <th class="text-center" style="min-width: 90px;">
                                TOTAL PUSHED
                                <div style="font-size:0.5rem; font-weight:400; color:#7c9eb2; margin-top:2px;">
                                    @if(request('channel') == 'website')
                                        Website
                                    @elseif(request('channel') == 'offline')
                                        Offline
                                    @else
                                        All Channels
                                    @endif
                                </div>
                            </th>
                            {{-- ===== DYNAMIC PLATFORM COLUMNS IN HEADER (Sirf selected channel) ===== --}}
                            @foreach($platforms as $platform)
                                @php 
                                    $name = strtolower($platform->name);
                                    $channelParam = match($name) {
                                        'website', 'online', 'own website', 'own_website' => 'website',
                                        'offline', 'ofline' => 'offline',
                                        default => $name
                                    };
                                @endphp
                                @if(!request('channel') || request('channel') == $channelParam)
                                    <th class="text-center" style="min-width: 80px;">
                                        {{ $platform->display_name ?? ucfirst($platform->name) }}
                                    </th>
                                @endif
                            @endforeach
                            <th class="text-center" style="min-width: 85px;">AVAILABLE</th>
                            <th class="text-center" style="min-width: 100px;">SYNC STATUS</th>
                            <th class="text-center" style="min-width: 90px;">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $allData = $inventoryData;
                            $groupedProducts = [];
                            foreach ($allData as $item) {
                                $productKey = $item['product_id'];
                                if (!isset($groupedProducts[$productKey])) {
                                    $groupedProducts[$productKey] = [
                                        'product_id' => $item['product_id'],
                                        'product_name' => $item['product_name'],
                                        'sku' => $item['sku'],
                                        'image_url' => $item['image_url'],
                                        'total_stock' => 0,
                                        'total_pushed' => 0,
                                        'website_pushed' => 0,
                                        'website_available' => 0,
                                        'offline_pushed' => 0,
                                        'offline_available' => 0,
                                        'amazon_pushed' => 0,
                                        'amazon_available' => 0,   
                                        'amazon_sold' => 0,    
                                        'flipkart_pushed' => 0,
                                        'final_stock' => 0,
                                        'website_sold' => 0,
                                        'offline_sold' => 0,
                                        'amazon_sold' => 0,
                                        'flipkart_sold' => 0,
                                        'variants' => [],
                                    ];
                                }
                                $groupedProducts[$productKey]['total_stock'] += $item['total_stock'];
                                $groupedProducts[$productKey]['total_pushed'] += $item['total_pushed'] ?? 0;
                                $groupedProducts[$productKey]['website_pushed'] += $item['website_pushed'] ?? 0;
                                $groupedProducts[$productKey]['website_available'] += $item['website_available'];
                                
                                $groupedProducts[$productKey]['offline_pushed'] += $item['offline_pushed'] ?? 0;
                                $groupedProducts[$productKey]['offline_available'] += $item['offline_available'];
                                $groupedProducts[$productKey]['amazon_pushed'] += $item['amazon_pushed'] ?? 0;
                                $groupedProducts[$productKey]['amazon_available'] += $item['amazon_available'] ?? 0;
                                $groupedProducts[$productKey]['amazon_sold'] += $item['amazon_sold'] ?? 0;
                                $groupedProducts[$productKey]['flipkart_pushed'] += $item['flipkart_pushed'] ?? 0;
                                $groupedProducts[$productKey]['final_stock'] += $item['final_stock'];
                                $groupedProducts[$productKey]['website_sold'] += $item['website_sold'];
                                $groupedProducts[$productKey]['offline_sold'] += $item['offline_sold'];
                                $groupedProducts[$productKey]['amazon_sold'] += $item['amazon_sold'] ?? 0;
                                $groupedProducts[$productKey]['flipkart_sold'] += $item['flipkart_sold'] ?? 0;
                                
                                $groupedProducts[$productKey]['variants'][] = [
                                    'variant_name' => $item['variant_name'],
                                    'sku' => $item['sku'],
                                    'total_stock' => $item['total_stock'],
                                    'total_pushed' => $item['total_pushed'] ?? 0,
                                    'website_pushed' => $item['website_pushed'] ?? 0,
                                    'website_available' => $item['website_available'],
                                    'offline_pushed' => $item['offline_pushed'] ?? 0,
                                    'offline_available' => $item['offline_available'],
                                    'amazon_pushed' => $item['amazon_pushed'] ?? 0,
                                    'flipkart_pushed' => $item['flipkart_pushed'] ?? 0,
                                    'amazon_available' => $item['amazon_available'] ?? 0,
                                    'amazon_sold' => $item['amazon_sold'] ?? 0,
                                    'final_stock' => $item['final_stock'],
                                    'website_sold' => $item['website_sold'],
                                    'offline_sold' => $item['offline_sold'],
                                    'amazon_sold' => $item['amazon_sold'] ?? 0,
                                    'flipkart_sold' => $item['flipkart_sold'] ?? 0,
                                ];
                            }
                             $perPage = 15;
                                $currentPage = request()->get('page', 1);
                                $offset = ($currentPage - 1) * $perPage;
                                $paginatedProducts = array_slice($groupedProducts, $offset, $perPage);
                                $totalItems = count($groupedProducts);
                        @endphp

                        @forelse($paginatedProducts as $product)
                            @php
                                $variantsCount = count($product['variants']);
                                $syncStatus = 'Synced';
                                $syncBadgeClass = 'badge-synced';
                                if ($product['final_stock'] <= 0) {
                                    $syncStatus = 'Pending';
                                    $syncBadgeClass = 'badge-pending';
                                }
                            @endphp
                            <tr class="product-row" onclick="toggleVariants(this)" style="border-bottom: 1px solid #f0f6fa;">
                                <td>
                                    <div class="product-name">{{ $product['product_name'] }}</div>
                                    <div>
                                        <span class="product-sku">{{ $product['sku'] }}</span>
                                        <span class="variant-count-label"><i class="fas fa-layer-group me-1"></i> {{ $variantsCount }} variants</span>
                                    </div>
                                </td>
                                <td class="text-center" style="font-weight: 600; color: #8B2452;">{{ $variantsCount }}</td>
                                <td class="text-center fw-bold">{{ $product['total_stock'] }}</td>
                                <td class="text-center fw-bold" style="color: #8B2452; font-size:0.85rem;">
                                    @if(request('channel') == 'website')
                                        {{ $product['website_pushed'] ?? 0 }}
                                    @elseif(request('channel') == 'offline')
                                        {{ $product['offline_pushed'] ?? 0 }}
                                    @elseif(request('channel') == 'amazon')
                                        {{ $product['amazon_pushed'] ?? 0 }}
                                    @elseif(request('channel') == 'flipkart')
                                        {{ $product['flipkart_pushed'] ?? 0 }}
                                    @else
                                        {{ $product['total_pushed'] ?? 0 }}
                                    @endif
                                </td>
                                
                                {{-- ===== DYNAMIC PLATFORM COLUMNS (Sirf selected channel) ===== --}}
                                @foreach($platforms as $platform)
                                    @php 
                                        $name = strtolower($platform->name);
                                        $channelParam = match($name) {
                                            'website', 'online', 'own website', 'own_website' => 'website',
                                            'offline', 'ofline' => 'offline',
                                            default => $name
                                        };
                                        $soldKey = $channelParam . '_sold';
                                    @endphp
                                    @if(!request('channel') || request('channel') == $channelParam)
                                        <td class="text-center" style="color: #E74C3C; font-weight: 600;">
                                            {{ $product[$soldKey] ?? 0 }}
                                        </td>
                                    @endif
                                @endforeach
                                
                                    <td class="text-center fw-bold" style="color: #0f7b4b;">
                                        @if(request('channel') == 'website')
                                            {{ $product['website_available'] }}
                                        @elseif(request('channel') == 'offline')
                                            {{ $product['offline_available'] }}
                                        @elseif(request('channel') == 'amazon')
                                            {{ $product['amazon_available'] ?? 0 }}
                                        @else
                                            {{ $product['final_stock'] }}
                                        @endif
                                    </td>                               
                                    <td class="text-center">
                                    <span class="{{ $syncBadgeClass }}">
                                        <i class="fas {{ $syncStatus == 'Synced' ? 'fa-check-circle' : 'fa-clock' }} me-1"></i> {{ $syncStatus }}
                                    </span>
                                </td>
                                
                                <td class="text-center">
                                    <a href="{{ route('admin.inventory.details', $product['product_id']) }}"
                                    class="btn btn-sm" 
                                    style="background: #8B2452; color: white; border-radius: 30px; font-size: 0.65rem; padding: 5px 18px; font-weight: 600; border: none; transition: all 0.2s ease; text-decoration: none;">
                                        <i class="fas fa-edit me-1"></i> Manage
                                    </a>
                                </td>
                            </tr>

                            {{-- ===== VARIANT DROPDOWN ===== --}}
                            @php
                                $visibleCols = 6; // PRODUCT, SKU, MASTER STOCK, TOTAL PUSHED, AVAILABLE, STATUS
                                if(!request('channel')) {
                                    $visibleCols += count($platforms);
                                } else {
                                    $visibleCols += 1;
                                }
                            @endphp
                            <tr class="variant-row" style="display:none;">
                                <td colspan="{{ $visibleCols }}" style="padding: 0 !important;">
                                    <div class="variant-table-wrapper">
                                        <table class="variant-table">
                                            <thead>
                                                <tr>
                                                    <th style="text-align:left;">VARIANT</th>
                                                    <th>SKU</th>
                                                    <th>MASTER STOCK</th>
                                                    <th>TOTAL PUSHED</th>
                                                    @foreach($platforms as $platform)
                                                        @php 
                                                            $name = strtolower($platform->name);
                                                            $channelParam = match($name) {
                                                                'website', 'online', 'own website', 'own_website' => 'website',
                                                                'offline', 'ofline' => 'offline',
                                                                default => $name
                                                            };
                                                        @endphp
                                                        @if(!request('channel') || request('channel') == $channelParam)
                                                            <th>{{ $platform->display_name ?? ucfirst($platform->name) }}</th>
                                                        @endif
                                                    @endforeach
                                                    <th>AVAILABLE</th>
                                                    <th>STATUS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                @foreach($product['variants'] as $variant)
                                                    @php
                                                        $vStatusClass = 'Healthy';
                                                        $vStatusBadge = 'badge-healthy';
                                                        if ($variant['final_stock'] == 0) {
                                                            $vStatusClass = 'Out of Stock';
                                                            $vStatusBadge = 'badge-out';
                                                        } elseif ($variant['final_stock'] <= 5) {
                                                            $vStatusClass = 'Low Stock';
                                                            $vStatusBadge = 'badge-low';
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td style="text-align:left; font-weight:500;">{{ $variant['variant_name'] }}</td>
                                                        <td style="font-family: monospace; color: #7c9eb2;">{{ $variant['sku'] }}</td>
                                                        <td style="font-weight:600;">{{ $variant['total_stock'] }}</td>
                                                        <td style="font-weight:600; color:#8B2452;">
                                                            @if(request('channel') == 'website')
                                                                {{ $variant['website_pushed'] ?? 0 }}
                                                            @elseif(request('channel') == 'offline')
                                                                {{ $variant['offline_pushed'] ?? 0 }}
                                                            @elseif(request('channel') == 'amazon')
                                                                {{ $variant['amazon_pushed'] ?? 0 }}
                                                            @elseif(request('channel') == 'flipkart')
                                                                {{ $variant['flipkart_pushed'] ?? 0 }}
                                                            @else
                                                                {{ $variant['total_pushed'] ?? 0 }}
                                                            @endif
                                                        </td>
                                                        
                                                        {{-- ===== DYNAMIC VARIANT PLATFORM COLUMNS (Sirf selected channel) ===== --}}
                                                        @foreach($platforms as $platform)
                                                            @php 
                                                                $name = strtolower($platform->name);
                                                                $channelParam = match($name) {
                                                                    'website', 'online', 'own website', 'own_website' => 'website',
                                                                    'offline', 'ofline' => 'offline',
                                                                    default => $name
                                                                };
                                                                $soldKey = $channelParam . '_sold';
                                                            @endphp
                                                            @if(!request('channel') || request('channel') == $channelParam)
                                                                <td style="color: #E74C3C; font-weight:600;">
                                                                    {{ $variant[$soldKey] ?? 0 }}
                                                                </td>
                                                            @endif
                                                        @endforeach
                                                        
                                                        <td style="font-weight:600; color: #0f7b4b;">
    @if(request('channel') == 'website')
        {{ $variant['website_available'] }}
    @elseif(request('channel') == 'offline')
        {{ $variant['offline_available'] }}
    @elseif(request('channel') == 'amazon')
        {{ $variant['amazon_available'] ?? 0 }}
    @else
        {{ $variant['final_stock'] }}
    @endif
</td>
                                                        <td>
                                                            <span class="{{ $vStatusBadge }}" style="font-size: 0.55rem; padding: 2px 12px;">
                                                                {{ $vStatusClass }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $visibleCols ?? 10 }}" class="text-center py-5">
                                    <i class="fas fa-box-open" style="font-size: 2.5rem; color: #d4e2f0;"></i>
                                    <p class="text-muted mt-2" style="font-size: 0.9rem;">No products found in inventory</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===== PAGINATION ===== --}}
    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-3">
        <div style="font-size: 0.78rem; color: #7c9eb2;">
            <i class="fas fa-info-circle me-1"></i> 
            Showing {{ ($currentPage - 1) * $perPage + 1 }} to {{ min($currentPage * $perPage, $totalItems) }} of {{ $totalItems }} products
        </div>
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
                        <a class="page-link" href="?{{ http_build_query(array_merge(request()->except('page'), ['page' => $currentPage - 1])) }}">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                    @php
                        $totalPages = ceil($totalItems / $perPage);
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($totalPages, $currentPage + 2);
                    @endphp
                    @if($startPage > 1)
                        <li class="page-item">
                            <a class="page-link" href="?{{ http_build_query(array_merge(request()->except('page'), ['page' => 1])) }}">1</a>
                        </li>
                        @if($startPage > 2)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif
                    @endif
                    @for($i = $startPage; $i <= $endPage; $i++)
                        <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                            <a class="page-link" href="?{{ http_build_query(array_merge(request()->except('page'), ['page' => $i])) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    @if($endPage < $totalPages)
                        @if($endPage < $totalPages - 1)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif
                        <li class="page-item">
                            <a class="page-link" href="?{{ http_build_query(array_merge(request()->except('page'), ['page' => $totalPages])) }}">{{ $totalPages }}</a>
                        </li>
                    @endif
                    <li class="page-item {{ $currentPage >= $totalPages ? 'disabled' : '' }}">
                        <a class="page-link" href="?{{ http_build_query(array_merge(request()->except('page'), ['page' => $currentPage + 1])) }}">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="d-flex align-items-center gap-2">
                <span style="font-size: 0.7rem; color: #7c9eb2; font-weight: 500;">Rows per page:</span>
                <select class="form-select form-select-sm" style="width: 70px; font-size: 0.75rem; border-radius: 30px; border-color: #e2ecf5; background: #fafcff;" onchange="window.location.href='?' + new URLSearchParams({...Object.fromEntries(new URLSearchParams(window.location.search)), per_page: this.value, page: 1}).toString()">
                    <option value="10" {{ request('per_page', 15) == 10 ? 'selected' : '' }}>10</option>
                    <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                    <option value="25" {{ request('per_page', 15) == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page', 15) == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page', 15) == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>
        </div>
    </div>
</div>

<script>
// ===== TOGGLE VARIANTS =====
function toggleVariants(row) {
    var variantRow = row.nextElementSibling;
    if (variantRow && variantRow.classList.contains('variant-row')) {
        if (variantRow.style.display === 'none' || variantRow.style.display === '') {
            variantRow.style.display = 'table-row';
            row.classList.add('expanded');
        } else {
            variantRow.style.display = 'none';
            row.classList.remove('expanded');
        }
    }
}

// ===== AJAX AUTO-SEARCH (No Enter Key Needed) =====
$(document).ready(function() {
    var searchInput = $('#searchInput');
    var suggestionsDiv = $('#searchSuggestions');
    var timer;
    var form = $('#filterForm');

    // ✅ Auto-search on input (No Enter key needed)
    searchInput.on('input', function() {
        var query = $(this).val();
        clearTimeout(timer);

        // ✅ Agar empty hai toh submit karo (sab results dikhao)
        if (query.length === 0) {
            suggestionsDiv.hide();
            form.submit();
            return;
        }

        if (query.length < 1) {
            suggestionsDiv.hide();
            return;
        }

        // ✅ Suggestions dikhao
        timer = setTimeout(function() {
            $.ajax({
                url: '{{ url("admin/inventory/search-suggestions") }}',
                method: 'GET',
                data: { q: query },
                success: function(data) {
                    if (data.length > 0) {
                        var html = '';
                        data.forEach(function(item) {
                            html += '<div class="suggestion-item" data-name="' + item.name + '" data-sku="' + item.sku + '">' + 
                                    '<strong>' + item.name + '</strong>' + 
                                    ' <span style="color:#7c9eb2;font-size:0.7rem;">' + item.sku + '</span>' +
                                    '</div>';
                        });
                        suggestionsDiv.html(html);
                        suggestionsDiv.show();
                    } else {
                        suggestionsDiv.html('<div class="suggestion-item" style="color:#7c9eb2;">No results found</div>');
                        suggestionsDiv.show();
                    }
                }
            });
        }, 300);
    });

    // ✅ Click on suggestion
    $(document).on('click', '.suggestion-item', function() {
        var name = $(this).data('name');
        searchInput.val(name);
        suggestionsDiv.hide();
        form.submit();
    });

    // ✅ Hide suggestions on click outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-wrapper').length) {
            suggestionsDiv.hide();
        }
    });

    // ✅ Enter key bhi kaam karega (backup)
    searchInput.on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            suggestionsDiv.hide();
            form.submit();
        }
    });
});
</script>
@endsection