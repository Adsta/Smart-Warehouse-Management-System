@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500">Total Zones</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalZones }}</p>
        <a href="{{ route('zones.index') }}" class="text-xs text-blue-600 mt-2 inline-block hover:underline">Manage &rarr;</a>
    </div>
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500">Total Locations</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalLocations }}</p>
        <a href="{{ route('locations.index') }}" class="text-xs text-blue-600 mt-2 inline-block hover:underline">Manage &rarr;</a>
    </div>
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500">Total Products</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalProducts }}</p>
        <a href="{{ route('products.index') }}" class="text-xs text-blue-600 mt-2 inline-block hover:underline">Manage &rarr;</a>
    </div>
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500">Units In Stock</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($totalStock) }}</p>
        <a href="{{ route('inventory.index') }}" class="text-xs text-blue-600 mt-2 inline-block hover:underline">View &rarr;</a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Recent Orders --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Recent Movement Orders</h3>
            <a href="{{ route('movement-orders.index') }}" class="text-sm text-blue-600 hover:underline">View all</a>
        </div>
        <div class="divide-y">
            @forelse($recentOrders as $order)
            <div class="px-6 py-3 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $order->reference_number }}</p>
                    <p class="text-xs text-gray-500">{{ $order->product->name }} &middot; {{ $order->quantity }} units</p>
                </div>
                <span class="text-xs px-2 py-1 rounded-full font-medium
                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                    {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                    {{ $order->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : '' }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            @empty
            <p class="px-6 py-4 text-sm text-gray-500">No orders yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Low Stock --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Low Stock Alerts <span class="text-red-500 text-xs">(≤10 units)</span></h3>
            <a href="{{ route('movement-orders.create') }}" class="text-sm text-blue-600 hover:underline">+ Inbound</a>
        </div>
        <div class="divide-y">
            @forelse($lowStock as $item)
            <div class="px-6 py-3 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $item->product->name }}</p>
                    <p class="text-xs text-gray-500">{{ $item->location->code }}</p>
                </div>
                <span class="text-sm font-bold {{ $item->quantity == 0 ? 'text-red-600' : 'text-orange-500' }}">
                    {{ $item->quantity }} units
                </span>
            </div>
            @empty
            <p class="px-6 py-4 text-sm text-gray-500">All stock levels are healthy.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <h3 class="font-semibold text-gray-800 mb-4">Quick Actions</h3>
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('movement-orders.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">+ New Movement Order</a>
        <a href="{{ route('products.create') }}" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-900">+ Add Product</a>
        <a href="{{ route('locations.create') }}" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-900">+ Add Location</a>
        <a href="{{ route('zones.create') }}" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-900">+ Add Zone</a>
    </div>
</div>
@endsection
