<?php

declare(strict_types=1);

namespace App\Services\Warehouse;

use App\Contracts\Warehouse\InventoryRepositoryInterface;
use App\DTOs\MovementOrderDTO;
use App\Models\MovementOrder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class MovementOrderService
{
    public function __construct(
        private readonly InventoryRepositoryInterface $inventoryRepository,
        private readonly PutawayService $putawayService,
    ) {}

    public function createInbound(MovementOrderDTO $dto, int $userId): MovementOrder
    {
        $product = Product::findOrFail($dto->productId);

        $destination = $dto->destinationLocationId !== 0
            ? \App\Models\Location::findOrFail($dto->destinationLocationId)
            : $this->putawayService->findOptimalLocation($product, $dto->quantity);

        return DB::transaction(function () use ($dto, $product, $destination, $userId): MovementOrder {
            $order = MovementOrder::create([
                'reference_number'        => 'MO-' . strtoupper(Str::random(10)),
                'product_id'              => $product->id,
                'source_location_id'      => null,
                'destination_location_id' => $destination->id,
                'quantity'                => $dto->quantity,
                'type'                    => 'inbound',
                'status'                  => 'pending',
                'notes'                   => $dto->notes,
                'created_by'              => $userId,
            ]);

            $this->inventoryRepository->addStock($product->id, $destination->id, $dto->quantity);

            $order->update(['status' => 'completed', 'completed_at' => now()]);

            return $order->fresh();
        });
    }

    public function createTransfer(MovementOrderDTO $dto, int $userId): MovementOrder
    {
        if ($dto->sourceLocationId === null) {
            throw new RuntimeException('A transfer order requires a source location.');
        }

        return DB::transaction(function () use ($dto, $userId): MovementOrder {
            $reserved = $this->inventoryRepository->reserveStock(
                $dto->productId,
                $dto->sourceLocationId,
                $dto->quantity,
            );

            if (!$reserved) {
                throw new RuntimeException('Insufficient available stock to fulfil the transfer.');
            }

            return MovementOrder::create([
                'reference_number'        => 'MO-' . strtoupper(Str::random(10)),
                'product_id'              => $dto->productId,
                'source_location_id'      => $dto->sourceLocationId,
                'destination_location_id' => $dto->destinationLocationId,
                'quantity'                => $dto->quantity,
                'type'                    => 'transfer',
                'status'                  => 'pending',
                'notes'                   => $dto->notes,
                'created_by'              => $userId,
            ]);
        });
    }

    public function completeTransfer(MovementOrder $order): MovementOrder
    {
        if (!$order->isPending()) {
            throw new RuntimeException("Movement order [{$order->reference_number}] is not in a pending state.");
        }

        return DB::transaction(function () use ($order): MovementOrder {
            $this->inventoryRepository->fulfilReservation(
                $order->product_id,
                $order->source_location_id,
                $order->quantity,
            );

            $this->inventoryRepository->addStock(
                $order->product_id,
                $order->destination_location_id,
                $order->quantity,
            );

            $order->update(['status' => 'completed', 'completed_at' => now()]);

            return $order->fresh();
        });
    }
}
