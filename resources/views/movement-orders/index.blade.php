@extends('layouts.app')
@section('title', 'Movement Orders')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-gray-500 text-sm">{{ $orders->total() }} total orders</p>
    <a href="{{ route('movement-orders.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">+ New Order</a>
</div>
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">Reference</th>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">Product</th>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">Type</th>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">From → To</th>
                <th class="text-right px-6 py-3 text-gray-600 font-medium">Qty</th>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">Status</th>
                <th class="text-left px-6 py-3 text-gray-600 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($orders as $order)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-3 font-mono text-xs text-gray-700">{{ $order->reference_number }}</td>
                <td class="px-6 py-3 font-medium text-gray-800">{{ $order->product->name }}</td>
                <td class="px-6 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        {{ $order->type === 'inbound' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $order->type === 'outbound' ? 'bg-orange-100 text-orange-700' : '' }}
                        {{ $order->type === 'transfer' ? 'bg-purple-100 text-purple-700' : '' }}">
                        {{ ucfirst($order->type) }}
                    </span>
                </td>
                <td class="px-6 py-3 text-gray-500 font-mono text-xs">
                    {{ $order->sourceLocation?->code ?? '—' }} → {{ $order->destinationLocation?->code ?? '—' }}
                </td>
                <td class="px-6 py-3 text-right font-semibold text-gray-800">{{ $order->quantity }}</td>
                <td class="px-6 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        {{ $order->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                        {{ $order->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : '' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td class="px-6 py-3">
                    <div class="flex items-center gap-2">
                        @if($order->isPending() && $order->type === 'transfer')
                        <form action="{{ route('movement-orders.complete', $order) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="text-green-600 hover:underline text-xs">Complete</button>
                        </form>
                        @endif
                        @if($order->isPending())
                        <form action="{{ route('movement-orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Cancel this order?')">
                            @csrf @method('PATCH')
                            <button class="text-orange-500 hover:underline text-xs">Cancel</button>
                        </form>
                        @endif
                        <form action="{{ route('movement-orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Delete this order?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:underline text-xs">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400">No orders yet. <a href="{{ route('movement-orders.create') }}" class="text-blue-600 hover:underline">Create one</a>.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($orders->hasPages())
    <div class="px-6 py-4 border-t">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
