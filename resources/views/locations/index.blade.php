@extends('layouts.app')
@section('title', 'Locations')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-gray-500 text-sm">{{ $locations->count() }} locations configured</p>
    <a href="{{ route('locations.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">+ Add Location</a>
</div>
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">Code</th>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">Zone</th>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">Coordinates (X,Y,Z)</th>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">Max Weight</th>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">Max Volume</th>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">Status</th>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($locations as $loc)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-3 font-mono font-medium text-gray-800">{{ $loc->code }}</td>
                <td class="px-6 py-3 text-gray-600">{{ $loc->zone->name }}</td>
                <td class="px-6 py-3 text-gray-500 font-mono">({{ $loc->x_coord }}, {{ $loc->y_coord }}, {{ $loc->z_coord }})</td>
                <td class="px-6 py-3 text-gray-500">{{ $loc->max_weight }} kg</td>
                <td class="px-6 py-3 text-gray-500">{{ $loc->max_volume }} m³</td>
                <td class="px-6 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $loc->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $loc->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="px-6 py-3 flex items-center gap-3">
                    <a href="{{ route('locations.edit', $loc) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form action="{{ route('locations.destroy', $loc) }}" method="POST" onsubmit="return confirm('Delete this location?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400">No locations yet. <a href="{{ route('locations.create') }}" class="text-blue-600 hover:underline">Add one</a>.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
