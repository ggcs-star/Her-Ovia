<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Inventory Management</title>
    <link rel="stylesheet" href="{{ asset('assets/css/admin/catalog.css') }}">
    <script src="{{ asset('assets/js/admin/categories.js') }}"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/push-product-premium.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- ✅ CHANGE 1: Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="module" src="{{ asset('firebase.js') }}"></script>
    
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

        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
        }

        [x-cloak] {
            display: none !important;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #e2e8f0;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-soft);
        }

        .btn-primary {
            background: var(--primary);
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            background: var(--primary-light);
        }
        .text-primary {
            color: var(--primary);
        }
        .hover\:text-primary:hover {
            color: var(--primary);
        }
        .bg-primary-light {
            background: var(--primary-bg);
        }
        .border-primary {
            border-color: var(--primary);
        }
        .hover\:border-primary:hover {
            border-color: var(--primary);
        }
        .ring-primary {
            ring-color: var(--primary);
        }
        .text-gold {
            color: var(--gold);
        }
        .bg-gold {
            background: var(--gold);
        }
        .bg-gold-light {
            background: var(--gold-bg);
        }
        .border-gold {
            border-color: var(--gold);
        }
        .hover\:text-gold:hover {
            color: var(--gold);
        }
    </style>
</head>

<!-- ✅ CHANGE 2: x-cloak add karo -->
<body class="bg-gray-50 text-gray-800 antialiased"
      x-data="{ sidebarOpen: true, loaded: false }"
      x-init="loaded = true"
      x-cloak>
    <div class="min-h-screen flex">

        @if(request()->routeIs('warehouses.*'))
            @include('layouts.admin.admin-settings-sidebar')
        @else
            @include('layouts.admin.sidebar')
        @endif
        
        <div id="uiToast" class="ui-toast"></div>

        <style>
            .ui-toast {
                position: fixed;
                right: 24px;
                top: 24px;
                background: #ef4444;
                color: #fff;
                padding: 12px 20px;
                border-radius: 12px;
                font-weight: 600;
                font-size: 14px;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
                display: none;
                z-index: 9999;
                animation: slideInRight 0.3s ease;
            }
            @keyframes slideInRight {
                from { transform: translateX(30px); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        </style>

        <script>
            window.showToast = function (message, type = "error") {
                const toast = document.getElementById("uiToast");
                if (!toast) return;
                toast.innerText = message;
                if (type === "success") {
                    toast.style.background = "#10b981";
                } else if (type === "warning") {
                    toast.style.background = "#f59e0b";
                } else {
                    toast.style.background = "#ef4444";
                }
                toast.style.display = "block";
                setTimeout(() => {
                    toast.style.display = "none";
                }, 3500);
            }
        </script>

        <div class="flex-1 flex flex-col transition-[margin-left] duration-300"
             :class="loaded && sidebarOpen ? 'md:ml-[260px]' : 'md:ml-0'">

            @include('layouts.admin.header')

            <main class="p-6 md:p-8 mt-[64px] flex-grow">
                @yield('content')
                @yield('scripts')
            </main>

            <footer class="mt-auto py-6 text-center text-sm text-gray-500 border-t border-gray-200 bg-white">
                <div class="container mx-auto px-6">
                    &copy; {{ date('Y') }} Her Ovia. All rights reserved.
                </div>
            </footer>
        </div>
    </div>
    
    @stack('scripts')
    
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                showToast(@json(session('error')), 'error');
            });
        </script>
    @endif

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                showToast(@json(session('success')), 'success');
            });
        </script>
    @endif

    @if(session('warning'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                showToast(@json(session('warning')), 'warning');
            });
        </script>
    @endif

</body>
</html>