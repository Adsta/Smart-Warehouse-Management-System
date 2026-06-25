<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTOs\MovementOrderDTO;
use App\Models\MovementOrder;
use App\Services\Warehouse\MovementOrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovementOrderController extends Controller
{
    public function __construct(
        private readonly MovementOrderService $movementOrderService,
    ) {}

    public function store(Request $request): JsonResponse
    {
        $dto = MovementOrderDTO::fromRequest($request);

        $userId = Auth::id() ?? 1;

        $order = match ($dto->type) {
            'inbound'  => $this->movementOrderService->createInbound($dto, $userId),
            'transfer' => $this->movementOrderService->createTransfer($dto, $userId),
            default    => abort(422, "Unsupported movement type [{$dto->type}]."),
        };

        return response()->json($order->load(['product', 'sourceLocation', 'destinationLocation']), 201);
    }

    public function complete(MovementOrder $movementOrder): JsonResponse
    {
        $order = $this->movementOrderService->completeTransfer($movementOrder);

        return response()->json($order->load(['product', 'sourceLocation', 'destinationLocation']));
    }
}
