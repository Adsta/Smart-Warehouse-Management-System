@extends('layouts.app')
@section('title', 'Inventory')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-gray-500 text-sm">{{ $inventory->count() }} stock records</p>
    <a href="{{ route('movement-orders.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">+ New Inbound</a>
</div>
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">Product</th>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">SKU</th>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">Location</th>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">Zone</th>
                <th class="text-right px-6 py-3 text-gray-600 font-medium">Quantity</th>
                <th class="text-right px-6 py-3 text-gray-600 font-medium">Reserved</th>
                <th class="text-right px-6 py-3 text-gray-600 font-medium">Available</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($inventory as $item)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-3 font-medium text-gray-800">{{ $item->product->name }}</td>
                <td class="px-6 py-3 font-mono text-gray-500">{{ $item->product->sku }}</td>
                <td class="px-6 py-3 font-mono text-gray-600">{{ $item->location->code }}</td>
                <td class="px-6 py-3 text-gray-500">{{ $item->location->zone->name }}</td>
                <td class="px-6 py-3 text-right font-semibold text-gray-800">{{ $item->quantity }}</td>
                <td class="px-6 py-3 text-right text-orange-500">{{ $item->reserved_quantity }}</td>
                <td class="px-6 py-3 text-right font-bold {{ $item->available_quantity <= 10 ? 'text-red-600' : 'text-green-600' }}">
                    {{ $item->available_quantity }}
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400">No inventory yet. Create an inbound movement order to add stock.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
