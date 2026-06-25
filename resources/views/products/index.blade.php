@extends('layouts.app')
@section('title', 'Products')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-gray-500 text-sm">{{ $products->count() }} products in catalogue</p>
    <a href="{{ route('products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">+ Add Product</a>
</div>
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">SKU</th>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">Name</th>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">Weight</th>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">Volume</th>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">Flags</th>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($products as $product)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-3 font-mono text-gray-800">{{ $product->sku }}</td>
                <td class="px-6 py-3 font-medium text-gray-800">{{ $product->name }}</td>
                <td class="px-6 py-3 text-gray-500">{{ $product->weight }} kg</td>
                <td class="px-6 py-3 text-gray-500">{{ $product->volume }} m³</td>
                <td class="px-6 py-3 flex gap-1">
                    @if($product->requires_cold_storage)
                    <span class="px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-700">Cold</span>
                    @endif
                    @if($product->is_hazmat)
                    <span class="px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700">Hazmat</span>
                    @endif
                    @if(!$product->requires_cold_storage && !$product->is_hazmat)
                    <span class="text-gray-400 text-xs">Standard</span>
                    @endif
                </td>
                <td class="px-6 py-3 flex items-center gap-3">
                    <a href="{{ route('products.edit', $product) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">No products yet. <a href="{{ route('products.create') }}" class="text-blue-600 hover:underline">Add one</a>.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
