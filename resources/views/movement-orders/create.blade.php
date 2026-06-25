@extends('layouts.app')
@section('title', 'New Movement Order')

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('movement-orders.index') }}" class="text-sm text-gray-500 hover:text-gray-700 mb-6 inline-block">&larr; Back to Orders</a>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6" x-data="{ type: '{{ old('type', 'inbound') }}' }">
        <h3 class="font-semibold text-gray-800 mb-6">New Movement Order</h3>

        <form action="{{ route('movement-orders.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Type selector --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Order Type</label>
                <div class="flex gap-3">
                    <label class="flex-1 border rounded-lg p-3 cursor-pointer flex items-start gap-3 transition" :class="type === 'inbound' ? 'border-blue-500 bg-blue-50' : 'hover:bg-gray-50'">
                        <input type="radio" name="type" value="inbound" x-model="type" class="mt-0.5">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Inbound</p>
                            <p class="text-xs text-gray-500">Receive goods into a location</p>
                        </div>
                    </label>
                    <label class="flex-1 border rounded-lg p-3 cursor-pointer flex items-start gap-3 transition" :class="type === 'transfer' ? 'border-blue-500 bg-blue-50' : 'hover:bg-gray-50'">
                        <input type="radio" name="type" value="transfer" x-model="type" class="mt-0.5">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Transfer</p>
                            <p class="text-xs text-gray-500">Move goods between locations</p>
                        </div>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                <select name="product_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('product_id') border-red-400 @enderror">
                    <option value="">Select a product...</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->name }} ({{ $product->sku }})
                        @if($product->requires_cold_storage) ❄️ @endif
                        @if($product->is_hazmat) ⚠️ @endif
                    </option>
                    @endforeach
                </select>
                @error('product_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div x-show="type === 'transfer'" x-transition>
                <label class="block text-sm font-medium text-gray-700 mb-1">Source Location <span class="text-gray-400">(where stock comes from)</span></label>
                <select name="source_location_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select source location...</option>
                    @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ old('source_location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->code }} — {{ $loc->zone->name }}</option>
                    @endforeach
                </select>
                @error('source_location_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <span x-text="type === 'transfer' ? 'Destination Location' : 'Store At Location'"></span>
                </label>
                <select name="destination_location_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('destination_location_id') border-red-400 @enderror">
                    <option value="">Select destination...</option>
                    @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ old('destination_location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->code }} — {{ $loc->zone->name }}</option>
                    @endforeach
                </select>
                @error('destination_location_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('quantity') border-red-400 @enderror">
                @error('quantity')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes <span class="text-gray-400">(optional)</span></label>
                <textarea name="notes" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
            </div>

            <div class="pt-2 flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700">Create Order</button>
                <a href="{{ route('movement-orders.index') }}" class="px-5 py-2 rounded-lg text-sm border text-gray-600 hover:bg-gray-50">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
