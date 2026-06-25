<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\MovementOrder;
use App\Models\Product;
use App\Services\Warehouse\MovementOrderService;
use App\DTOs\MovementOrderDTO;
use Illuminate\Http\Request;

class MovementOrderWebController extends Controller
{
    public function __construct(private readonly MovementOrderService $service) {}

    public function index()
    {
        $orders = MovementOrder::with(['product', 'sourceLocation', 'destinationLocation'])
            ->latest()->paginate(15);

        return view('movement-orders.index', compact('orders'));
    }

    public function create()
    {
        return view('movement-orders.create', [
            'products'  => Product::orderBy('name')->get(),
            'locations' => Location::with('zone')->where('is_active', true)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id'              => 'required|exists:products,id',
            'destination_location_id' => 'required|exists:locations,id',
            'quantity'                => 'required|integer|min:1',
            'type'                    => 'required|in:inbound,transfer',
            'source_location_id'      => 'nullable|exists:locations,id',
            'notes'                   => 'nullable|string|max:1000',
        ]);

        try {
            $dto = new MovementOrderDTO(
                productId:             (int) $request->product_id,
                destinationLocationId: (int) $request->destination_location_id,
                quantity:              (int) $request->quantity,
                type:                  $request->type,
                sourceLocationId:      $request->source_location_id ? (int) $request->source_location_id : null,
                notes:                 $request->notes,
            );

            if ($dto->type === 'inbound') {
                $this->service->createInbound($dto, 1);
            } else {
                $this->service->createTransfer($dto, 1);
            }

            return redirect()->route('movement-orders.index')->with('success', 'Movement order created successfully.');
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function complete(MovementOrder $movementOrder)
    {
        try {
            $this->service->completeTransfer($movementOrder);
            return back()->with('success', "Order {$movementOrder->reference_number} completed.");
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function cancel(MovementOrder $movementOrder)
    {
        if (!$movementOrder->isPending()) {
            return back()->with('error', 'Only pending orders can be cancelled.');
        }
        $movementOrder->update(['status' => 'cancelled']);
        return back()->with('success', "Order {$movementOrder->reference_number} cancelled.");
    }

    public function destroy(MovementOrder $movementOrder)
    {
        $movementOrder->delete();
        return redirect()->route('movement-orders.index')->with('success', 'Order deleted.');
    }
}
