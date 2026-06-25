<?php

declare(strict_types=1);

namespace App\Services\Warehouse\Strategies;

use App\Contracts\Warehouse\PutawayStrategyInterface;
use App\Models\Location;
use App\Models\Product;

/**
 * Strictly limits candidate locations to zones of type 'cold_storage'.
 * Returns null when no cold-storage location with sufficient capacity exists.
 */
class ColdChainStrategy implements PutawayStrategyInterface
{
    public function findOptimalLocation(Product $product, int $quantity): ?Location
    {
        $requiredWeight = $product->dimensions->weight * $quantity;
        $requiredVolume = $product->dimensions->volume * $quantity;

        return Location::query()
            ->with('zone')
            ->whereHas('zone', fn ($q) => $q->where('type', 'cold_storage'))
            ->where('is_active', true)
            ->where('max_weight', '>=', $requiredWeight)
            ->where('max_volume', '>=', $requiredVolume)
            ->orderBy('id')
            ->first();
    }
}
