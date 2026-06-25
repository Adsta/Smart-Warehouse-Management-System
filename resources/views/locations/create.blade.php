@extends('layouts.app')
@section('title', 'Add Location')

@section('content')
<div class="max-w-xl">
    <a href="{{ route('locations.index') }}" class="text-sm text-gray-500 hover:text-gray-700 mb-6 inline-block">&larr; Back to Locations</a>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-800 mb-6">New Location</h3>
        <form action="{{ route('locations.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Zone</label>
                <select name="zone_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select a zone...</option>
                    @foreach($zones as $zone)
                    <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>{{ $zone->name }} ({{ $zone->code }})</option>
                    @endforeach
                </select>
                @error('zone_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Location Code</label>
                <input type="text" name="code" value="{{ old('code') }}" class="w-full border rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g. A-01-01">
                @error('code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">X Coordinate</label>
                    <input type="number" name="x_coord" value="{{ old('x_coord', 0) }}" min="0" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('x_coord')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Y Coordinate</label>
                    <input type="number" name="y_coord" value="{{ old('y_coord', 0) }}" min="0" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Z Coordinate</label>
                    <input type="number" name="z_coord" value="{{ old('z_coord', 0) }}" min="0" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Max Weight (kg)</label>
                    <input type="number" step="0.01" name="max_weight" value="{{ old('max_weight') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('max_weight')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Max Volume (m³)</label>
                    <input type="number" step="0.01" name="max_volume" value="{{ old('max_volume') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('max_volume')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="pt-2 flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700">Create Location</button>
                <a href="{{ route('locations.index') }}" class="px-5 py-2 rounded-lg text-sm border text-gray-600 hover:bg-gray-50">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
