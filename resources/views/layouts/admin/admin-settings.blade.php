<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings - Inventory</title>

    <link rel="stylesheet" href="{{ asset('assets/css/admin/catalog.css') }}">

    <!-- ✅ Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ✅ Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- ✅ Alpine -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body
    class="bg-gray-50 text-gray-900"
    x-data="{ sidebarOpen: true, loaded: false }"
    x-init="loaded = true">

<div class="min-h-screen flex">

    {{-- ✅ SETTINGS SIDEBAR --}}
    @include('layouts.admin.admin-settings-sidebar')

    <div
    class="flex-1 flex flex-col transition-all duration-300 md:ml-[260px]"
    :class="loaded ? (sidebarOpen ? 'md:ml-[260px]' : 'md:ml-0') : 'md:ml-[260px]'">

        {{-- ✅ SAME INVENTORY HEADER --}}
        <div class="sticky top-0 z-30">
            @include('layouts.admin.header')
        </div>

        {{-- ✅ PAGE CONTENT --}}
        <main class="p-6 mt-[64px] flex-grow">
            @yield('settings-content')
        </main>

        <footer class="p-4 text-center text-sm text-gray-500 border-t bg-white">
            &copy; {{ date('Y') }} Her Ovia. All rights reserved.
        </footer>

    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>
