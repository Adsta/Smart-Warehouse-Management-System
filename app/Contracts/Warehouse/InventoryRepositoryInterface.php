<?php

declare(strict_types=1);

namespace App\Contracts\Warehouse;

use App\Models\Inventory;
use Illuminate\Database\Eloquent\Collection;

interface InventoryRepositoryInterface
{
    /**
     * Find inventory records with available stock for a product across all locations.
     *
     * @return Collection<int, Inventory>
     */
    public function findAvailableStock(int $productId, int $minimumQuantity): Collection;

    /**
     * Lock an inventory row for update within a transaction (SELECT FOR UPDATE).
     */
    public function lockForUpdate(int $productId, int $locationId): ?Inventory;

    /**
     * Find or create an inventory record for a product-location pair.
     */
    public function findOrCreateForLocation(int $productId, int $locationId): Inventory;

    /**
     * Reserve stock by incrementing reserved_quantity atomically.
     * Returns false if available stock is insufficient.
     */
    public function reserveStock(int $productId, int $locationId, int $quantity): bool;

    /**
     * Deduct reserved stock and reduce total quantity after a movement completes.
     */
    public function fulfilReservation(int $productId, int $locationId, int $quantity): void;

    /**
     * Add stock to a location (inbound).
     */
    public function addStock(int $productId, int $locationId, int $quantity): Inventory;
}
