@extends('layouts.app')
@section('title', 'Edit Zone')

@section('content')
<div class="max-w-xl">
    <a href="{{ route('zones.index') }}" class="text-sm text-gray-500 hover:text-gray-700 mb-6 inline-block">&larr; Back to Zones</a>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-800 mb-6">Edit Zone: {{ $zone->name }}</h3>
        <form action="{{ route('zones.update', $zone) }}" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name', $zone->name) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Code</label>
                <input type="text" name="code" value="{{ old('code', $zone->code) }}" class="w-full border rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="type" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach(['standard','cold_storage','hazmat','inbound','outbound'] as $type)
                    <option value="{{ $type }}" {{ old('type', $zone->type) == $type ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$type)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $zone->description) }}</textarea>
            </div>
            <div class="pt-2 flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700">Save Changes</button>
                <a href="{{ route('zones.index') }}" class="px-5 py-2 rounded-lg text-sm border text-gray-600 hover:bg-gray-50">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
