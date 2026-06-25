@extends('layouts.app')
@section('title', 'Zones')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-gray-500 text-sm">{{ $zones->count() }} zones configured</p>
    <a href="{{ route('zones.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">+ Add Zone</a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">Name</th>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">Code</th>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">Type</th>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">Locations</th>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($zones as $zone)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-3 font-medium text-gray-800">{{ $zone->name }}</td>
                <td class="px-6 py-3 text-gray-500 font-mono">{{ $zone->code }}</td>
                <td class="px-6 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        {{ $zone->type === 'cold_storage' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $zone->type === 'hazmat' ? 'bg-red-100 text-red-700' : '' }}
                        {{ $zone->type === 'standard' ? 'bg-gray-100 text-gray-700' : '' }}
                        {{ $zone->type === 'inbound' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $zone->type === 'outbound' ? 'bg-orange-100 text-orange-700' : '' }}">
                        {{ ucfirst(str_replace('_', ' ', $zone->type)) }}
                    </span>
                </td>
                <td class="px-6 py-3 text-gray-500">{{ $zone->locations_count }}</td>
                <td class="px-6 py-3 flex items-center gap-3">
                    <a href="{{ route('zones.edit', $zone) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form action="{{ route('zones.destroy', $zone) }}" method="POST" onsubmit="return confirm('Delete this zone?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">No zones yet. <a href="{{ route('zones.create') }}" class="text-blue-600 hover:underline">Add one</a>.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
