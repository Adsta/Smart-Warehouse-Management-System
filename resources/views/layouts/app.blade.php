<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Smart Warehouse')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans">

<div class="flex min-h-screen">
    {{-- Sidebar --}}
    <aside class="w-64 bg-gray-900 text-white flex flex-col fixed h-full">
        <div class="p-6 border-b border-gray-700">
            <h1 class="text-xl font-bold text-white">Smart Warehouse</h1>
            <p class="text-gray-400 text-xs mt-1">Management System</p>
        </div>
        <nav class="flex-1 p-4 space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('zones.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('zones.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                Zones
            </a>
            <a href="{{ route('locations.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('locations.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Locations
            </a>
            <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('products.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Products
            </a>
            <a href="{{ route('inventory.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('inventory.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Inventory
            </a>
            <a href="{{ route('movement-orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('movement-orders.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                Movement Orders
            </a>
            <a href="{{ route('api-explorer') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('api-explorer') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                API Explorer
            </a>
        </nav>
        <div class="p-4 border-t border-gray-700 text-xs text-gray-500">
            OOP / SOLID / DDD Architecture
        </div>
    </aside>

    {{-- Main content --}}
    <main class="flex-1 ml-64">
        <header class="bg-white border-b px-8 py-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">@yield('title', 'Dashboard')</h2>
            <span class="text-sm text-gray-500">{{ now()->format('D, d M Y') }}</span>
        </header>

        <div class="p-8">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="text-green-600 hover:text-green-800">&times;</button>
                </div>
            @endif
            @if(session('error'))
                <div x-data="{ show: true }" x-show="show"
                     class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center justify-between">
                    <span>{{ session('error') }}</span>
                    <button @click="show = false" class="text-red-600 hover:text-red-800">&times;</button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

</body>
</html>
