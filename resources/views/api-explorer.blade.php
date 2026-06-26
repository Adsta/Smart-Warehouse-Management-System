@extends('layouts.app')
@section('title', 'API Explorer')

@section('content')
<div
    x-data="{
        tab: 'post',
        loading: false,
        response: null,
        responseStatus: null,
        responseTime: null,
        form: {
            product_id: '',
            destination_location_id: '',
            source_location_id: '',
            quantity: 1,
            type: 'inbound',
            notes: ''
        },
        completeId: '',
        completeResponse: null,
        completeStatus: null,

        async sendPost() {
            this.loading = true;
            this.response = null;
            const start = Date.now();
            try {
                const body = {
                    product_id: parseInt(this.form.product_id),
                    destination_location_id: parseInt(this.form.destination_location_id),
                    quantity: parseInt(this.form.quantity),
                    type: this.form.type,
                };
                if (this.form.type === 'transfer' && this.form.source_location_id)
                    body.source_location_id = parseInt(this.form.source_location_id);
                if (this.form.notes)
                    body.notes = this.form.notes;

                const res = await fetch('/api/movement-orders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ $csrfToken }}'
                    },
                    body: JSON.stringify(body)
                });
                this.responseStatus = res.status;
                this.responseTime = Date.now() - start;
                const json = await res.json();
                this.response = JSON.stringify(json, null, 2);
            } catch(e) {
                this.response = 'Network error: ' + e.message;
                this.responseStatus = 0;
            }
            this.loading = false;
        },

        async sendComplete() {
            if (!this.completeId) return;
            this.loading = true;
            this.completeResponse = null;
            const start = Date.now();
            try {
                const res = await fetch('/api/movement-orders/' + this.completeId + '/complete', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ $csrfToken }}'
                    }
                });
                this.completeStatus = res.status;
                this.responseTime = Date.now() - start;
                const json = await res.json();
                this.completeResponse = JSON.stringify(json, null, 2);
            } catch(e) {
                this.completeResponse = 'Network error: ' + e.message;
                this.completeStatus = 0;
            }
            this.loading = false;
        },

        statusColor(code) {
            if (!code) return 'text-gray-400';
            if (code >= 200 && code < 300) return 'text-green-500';
            if (code >= 400 && code < 500) return 'text-yellow-500';
            return 'text-red-500';
        }
    }"
>

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-gray-500 text-sm">Send live requests to the warehouse API and see the response</p>
    </div>
    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-mono font-semibold">Base URL: http://127.0.0.1:8000/api</span>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

    {{-- ── LEFT: Request Panel ─────────────────────────────────────── --}}
    <div class="space-y-4">

        {{-- Tab switcher --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex border-b">
                <button @click="tab = 'post'"
                    :class="tab === 'post' ? 'border-b-2 border-blue-600 text-blue-600 bg-blue-50' : 'text-gray-500 hover:bg-gray-50'"
                    class="flex-1 px-4 py-3 text-sm font-medium transition">
                    <span class="font-mono text-xs bg-green-100 text-green-700 px-1.5 py-0.5 rounded mr-2">POST</span>
                    /api/movement-orders
                </button>
                <button @click="tab = 'patch'"
                    :class="tab === 'patch' ? 'border-b-2 border-blue-600 text-blue-600 bg-blue-50' : 'text-gray-500 hover:bg-gray-50'"
                    class="flex-1 px-4 py-3 text-sm font-medium transition">
                    <span class="font-mono text-xs bg-yellow-100 text-yellow-700 px-1.5 py-0.5 rounded mr-2">PATCH</span>
                    /api/movement-orders/{id}/complete
                </button>
            </div>

            {{-- POST Form --}}
            <div x-show="tab === 'post'" class="p-5 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Order Type</label>
                    <div class="flex gap-2">
                        <label class="flex-1 flex items-center gap-2 border rounded-lg px-3 py-2 cursor-pointer transition"
                            :class="form.type === 'inbound' ? 'border-green-400 bg-green-50' : 'hover:bg-gray-50'">
                            <input type="radio" x-model="form.type" value="inbound" class="accent-green-500">
                            <div>
                                <p class="text-sm font-medium">Inbound</p>
                                <p class="text-xs text-gray-400">Receive goods</p>
                            </div>
                        </label>
                        <label class="flex-1 flex items-center gap-2 border rounded-lg px-3 py-2 cursor-pointer transition"
                            :class="form.type === 'transfer' ? 'border-purple-400 bg-purple-50' : 'hover:bg-gray-50'">
                            <input type="radio" x-model="form.type" value="transfer" class="accent-purple-500">
                            <div>
                                <p class="text-sm font-medium">Transfer</p>
                                <p class="text-xs text-gray-400">Move between locations</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">product_id</label>
                    <select x-model="form.product_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">— select product —</option>
                        @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->id }} · {{ $p->name }} ({{ $p->sku }})</option>
                        @endforeach
                    </select>
                </div>

                <div x-show="form.type === 'transfer'" x-transition>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">source_location_id</label>
                    <select x-model="form.source_location_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">— select source —</option>
                        @foreach($locations as $loc)
                        <option value="{{ $loc->id }}">{{ $loc->id }} · {{ $loc->code }} ({{ $loc->zone->name }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">destination_location_id</label>
                    <select x-model="form.destination_location_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">— select destination —</option>
                        @foreach($locations as $loc)
                        <option value="{{ $loc->id }}">{{ $loc->id }} · {{ $loc->code }} ({{ $loc->zone->name }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">quantity</label>
                        <input type="number" x-model="form.quantity" min="1"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">notes <span class="normal-case font-normal text-gray-400">(optional)</span></label>
                        <input type="text" x-model="form.notes" placeholder="optional"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                {{-- JSON preview --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Request Body Preview</label>
                    <pre class="bg-gray-900 text-green-400 rounded-lg p-3 text-xs overflow-auto max-h-36"
                        x-text="JSON.stringify({
                            product_id: parseInt(form.product_id) || '?',
                            destination_location_id: parseInt(form.destination_location_id) || '?',
                            ...(form.type === 'transfer' && form.source_location_id ? { source_location_id: parseInt(form.source_location_id) } : {}),
                            quantity: parseInt(form.quantity),
                            type: form.type,
                            ...(form.notes ? { notes: form.notes } : {})
                        }, null, 2)">
                    </pre>
                </div>

                <button @click="sendPost()" :disabled="loading || !form.product_id || !form.destination_location_id"
                    class="w-full bg-blue-600 text-white py-2.5 rounded-lg text-sm font-semibold hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <svg x-show="loading" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                    <span x-text="loading ? 'Sending...' : 'Send Request'"></span>
                </button>
            </div>

            {{-- PATCH Form --}}
            <div x-show="tab === 'patch'" class="p-5 space-y-4">
                <p class="text-sm text-gray-500">Marks a pending <strong>transfer</strong> order as completed and moves stock atomically.</p>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Movement Order ID</label>
                    <div class="flex gap-2">
                        <input type="number" x-model="completeId" placeholder="e.g. 5" min="1"
                            class="flex-1 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button @click="sendComplete()" :disabled="loading || !completeId"
                            class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-yellow-600 disabled:opacity-50 flex items-center gap-2">
                            <svg x-show="loading" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                            <span x-text="loading ? 'Sending...' : 'Complete'"></span>
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">PATCH /api/movement-orders/<span x-text="completeId || '{id}'"></span>/complete</p>
                </div>

                {{-- PATCH Response --}}
                <div x-show="completeResponse !== null" x-transition>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-semibold uppercase text-gray-500">Response</span>
                        <span class="text-xs font-mono font-bold" :class="statusColor(completeStatus)" x-text="completeStatus"></span>
                        <span class="text-xs text-gray-400" x-text="responseTime + 'ms'"></span>
                    </div>
                    <pre class="bg-gray-900 text-green-400 rounded-lg p-4 text-xs overflow-auto max-h-96 whitespace-pre-wrap"
                        x-text="completeResponse"></pre>
                </div>
            </div>
        </div>

        {{-- POST Response --}}
        <div x-show="tab === 'post' && response !== null" x-transition
            class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center gap-3 px-5 py-3 border-b bg-gray-50">
                <span class="text-xs font-semibold uppercase text-gray-500">Response</span>
                <span class="text-sm font-mono font-bold" :class="statusColor(responseStatus)" x-text="responseStatus"></span>
                <span class="text-xs text-gray-400" x-text="responseTime + 'ms'"></span>
                <span class="text-xs px-2 py-0.5 rounded-full"
                    :class="responseStatus >= 200 && responseStatus < 300 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                    x-text="responseStatus >= 200 && responseStatus < 300 ? '✓ Success' : '✗ Error'">
                </span>
            </div>
            <pre class="p-5 text-xs text-green-400 bg-gray-900 overflow-auto max-h-96 whitespace-pre-wrap"
                x-text="response"></pre>
        </div>
    </div>

    {{-- ── RIGHT: Live Order Log ────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-gray-800">Recent Orders Log</h3>
                <p class="text-xs text-gray-400 mt-0.5">Last 20 orders in the database</p>
            </div>
            <a href="{{ route('movement-orders.index') }}" class="text-xs text-blue-600 hover:underline">View all →</a>
        </div>
        <div class="overflow-auto max-h-[680px] divide-y">
            @forelse($recentOrders as $order)
            <div class="px-5 py-3 hover:bg-gray-50 transition">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-mono text-xs text-gray-400">#{{ $order->id }}</span>
                            <span class="font-mono text-xs font-semibold text-gray-700">{{ $order->reference_number }}</span>
                            <span class="px-1.5 py-0.5 rounded text-xs font-medium
                                {{ $order->type === 'inbound'  ? 'bg-green-100 text-green-700' : '' }}
                                {{ $order->type === 'transfer' ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ $order->type === 'outbound' ? 'bg-orange-100 text-orange-700' : '' }}">
                                {{ strtoupper($order->type) }}
                            </span>
                            <span class="px-1.5 py-0.5 rounded text-xs font-medium
                                {{ $order->status === 'completed'  ? 'bg-green-100 text-green-700' : '' }}
                                {{ $order->status === 'pending'    ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $order->status === 'cancelled'  ? 'bg-red-100 text-red-700' : '' }}
                                {{ $order->status === 'in_progress'? 'bg-blue-100 text-blue-700' : '' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-700 mt-0.5 truncate">{{ $order->product->name }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $order->sourceLocation?->code ?? '—' }} → {{ $order->destinationLocation?->code ?? '—' }}
                            &middot; <span class="font-semibold">{{ $order->quantity }}</span> units
                        </p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-xs text-gray-400">{{ $order->created_at->diffForHumans() }}</p>
                        @if($order->isPending() && $order->type === 'transfer')
                        <button
                            @click="tab = 'patch'; completeId = {{ $order->id }}"
                            class="mt-1 text-xs text-yellow-600 hover:underline">
                            Complete →
                        </button>
                        @endif
                    </div>
                </div>

                {{-- Raw JSON toggle --}}
                <div x-data="{ open: false }" class="mt-1">
                    <button @click="open = !open" class="text-xs text-gray-400 hover:text-gray-600 flex items-center gap-1">
                        <svg class="w-3 h-3 transition" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span x-text="open ? 'Hide JSON' : 'View JSON'"></span>
                    </button>
                    <pre x-show="open" x-transition
                        class="mt-2 bg-gray-900 text-green-400 rounded-lg p-3 text-xs overflow-auto max-h-48 whitespace-pre-wrap">{{ json_encode([
                            'id'                       => $order->id,
                            'reference_number'         => $order->reference_number,
                            'product_id'               => $order->product_id,
                            'source_location_id'       => $order->source_location_id,
                            'destination_location_id'  => $order->destination_location_id,
                            'quantity'                 => $order->quantity,
                            'type'                     => $order->type,
                            'status'                   => $order->status,
                            'notes'                    => $order->notes,
                            'created_by'               => $order->created_by,
                            'completed_at'             => $order->completed_at?->toISOString(),
                            'created_at'               => $order->created_at->toISOString(),
                            'updated_at'               => $order->updated_at->toISOString(),
                            'product'                  => [
                                'id'   => $order->product->id,
                                'sku'  => $order->product->sku,
                                'name' => $order->product->name,
                            ],
                            'source_location'          => $order->sourceLocation ? [
                                'id'   => $order->sourceLocation->id,
                                'code' => $order->sourceLocation->code,
                            ] : null,
                            'destination_location'     => $order->destinationLocation ? [
                                'id'   => $order->destinationLocation->id,
                                'code' => $order->destinationLocation->code,
                            ] : null,
                        ], JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
            @empty
            <p class="px-5 py-8 text-center text-sm text-gray-400">No orders yet.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection
