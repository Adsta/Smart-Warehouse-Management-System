<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Warehouse\InventoryRepositoryInterface;
use App\Models\Inventory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class EloquentInventoryRepository implements InventoryRepositoryInterface
{
    public function findAvailableStock(int $productId, int $minimumQuantity): Collection
    {
        return Inventory::query()
            ->with(['product', 'location.zone'])
            ->where('product_id', $productId)
            ->whereRaw('(quantity - reserved_quantity) >= ?', [$minimumQuantity])
            ->orderByDesc(DB::raw('quantity - reserved_quantity'))
            ->get();
    }

    public function lockForUpdate(int $productId, int $locationId): ?Inventory
    {
        return Inventory::query()
            ->where('product_id', $productId)
            ->where('location_id', $locationId)
            ->lockForUpdate()
            ->first();
    }

    public function findOrCreateForLocation(int $productId, int $locationId): Inventory
    {
        return Inventory::firstOrCreate(
            ['product_id' => $productId, 'location_id' => $locationId],
            ['quantity' => 0, 'reserved_quantity' => 0],
        );
    }

    public function reserveStock(int $productId, int $locationId, int $quantity): bool
    {
        return DB::transaction(function () use ($productId, $locationId, $quantity): bool {
            $inventory = $this->lockForUpdate($productId, $locationId);

            if ($inventory === null) {
                return false;
            }

            $available = $inventory->quantity - $inventory->reserved_quantity;

            if ($available < $quantity) {
                return false;
            }

            $inventory->increment('reserved_quantity', $quantity);

            return true;
        });
    }

    public function fulfilReservation(int $productId, int $locationId, int $quantity): void
    {
        DB::transaction(function () use ($productId, $locationId, $quantity): void {
            $inventory = $this->lockForUpdate($productId, $locationId);

            if ($inventory === null) {
                throw new RuntimeException(
                    "Inventory record not found for product {$productId} at location {$locationId}."
                );
            }

            if ($inventory->reserved_quantity < $quantity || $inventory->quantity < $quantity) {
                throw new RuntimeException('Reservation quantity exceeds available or reserved stock.');
            }

            $inventory->decrement('reserved_quantity', $quantity);
            $inventory->decrement('quantity', $quantity);
        });
    }

    public function addStock(int $productId, int $locationId, int $quantity): Inventory
    {
        return DB::transaction(function () use ($productId, $locationId, $quantity): Inventory {
            $inventory = $this->findOrCreateForLocation($productId, $locationId);
            $inventory->lockForUpdate();
            $inventory->increment('quantity', $quantity);
            $inventory->refresh();

            return $inventory;
        });
    }
}
