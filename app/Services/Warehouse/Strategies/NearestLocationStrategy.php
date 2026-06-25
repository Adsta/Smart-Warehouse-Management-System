<?php

declare(strict_types=1);

namespace App\Services\Warehouse\Strategies;

use App\Contracts\Warehouse\PutawayStrategyInterface;
use App\Models\Location;
use App\Models\Product;
use App\ValueObjects\SpatialCoordinate;

/**
 * Selects the active location closest (Euclidean) to the inbound zone origin
 * that has sufficient capacity for the product and quantity.
 */
class NearestLocationStrategy implements PutawayStrategyInterface
{
    // The inbound zone is treated as the warehouse origin (0, 0, 0).
    private readonly SpatialCoordinate $inboundOrigin;

    public function __construct()
    {
        $this->inboundOrigin = new SpatialCoordinate(0, 0, 0);
    }

    public function findOptimalLocation(Product $product, int $quantity): ?Location
    {
        $requiredWeight = $product->dimensions->weight * $quantity;
        $requiredVolume = $product->dimensions->volume * $quantity;

        $candidates = Location::query()
            ->with('zone')
            ->where('is_active', true)
            ->where('max_weight', '>=', $requiredWeight)
            ->where('max_volume', '>=', $requiredVolume)
            ->get();

        if ($candidates->isEmpty()) {
            return null;
        }

        return $candidates
            ->sortBy(fn (Location $location) => $location->coordinates->calculateEuclideanDistance($this->inboundOrigin))
            ->first();
    }
}
