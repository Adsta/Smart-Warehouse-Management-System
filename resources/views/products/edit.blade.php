@extends('layouts.app')
@section('title', 'Edit Product')

@section('content')
<div class="max-w-xl">
    <a href="{{ route('products.index') }}" class="text-sm text-gray-500 hover:text-gray-700 mb-6 inline-block">&larr; Back to Products</a>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-800 mb-6">Edit: {{ $product->name }}</h3>
        <form action="{{ route('products.update', $product) }}" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full border rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('sku')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $product->description) }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                    <input type="number" step="0.01" name="weight" value="{{ old('weight', $product->weight) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Volume (m³)</label>
                    <input type="number" step="0.01" name="volume" value="{{ old('volume', $product->volume) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="space-y-2 pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="requires_cold_storage" value="1" {{ old('requires_cold_storage', $product->requires_cold_storage) ? 'checked' : '' }} class="rounded">
                    <span class="text-sm text-gray-700">Requires Cold Storage</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_hazmat" value="1" {{ old('is_hazmat', $product->is_hazmat) ? 'checked' : '' }} class="rounded">
                    <span class="text-sm text-gray-700">Hazardous Material</span>
                </label>
            </div>
            <div class="pt-2 flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700">Save Changes</button>
                <a href="{{ route('products.index') }}" class="px-5 py-2 rounded-lg text-sm border text-gray-600 hover:bg-gray-50">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
