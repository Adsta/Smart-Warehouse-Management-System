<?php

declare(strict_types=1);

namespace App\Services\Warehouse;

use App\Contracts\Warehouse\PutawayStrategyInterface;
use App\Models\Location;
use App\Models\Product;
use App\Services\Warehouse\Strategies\ColdChainStrategy;
use App\Services\Warehouse\Strategies\NearestLocationStrategy;
use RuntimeException;

/**
 * Selects and delegates to the correct putaway strategy based on product attributes.
 * Follows the Strategy pattern — adding a new strategy requires no changes here.
 */
class PutawayService
{
    public function __construct(
        private readonly NearestLocationStrategy $nearestStrategy,
        private readonly ColdChainStrategy $coldChainStrategy,
    ) {}

    public function findOptimalLocation(Product $product, int $quantity): Location
    {
        $strategy = $this->resolveStrategy($product);

        $location = $strategy->findOptimalLocation($product, $quantity);

        if ($location === null) {
            throw new RuntimeException(
                "No suitable location found for product [{$product->sku}] using strategy ["
                . $strategy::class . '].'
            );
        }

        return $location;
    }

    private function resolveStrategy(Product $product): PutawayStrategyInterface
    {
        if ($product->requires_cold_storage) {
            return $this->coldChainStrategy;
        }

        return $this->nearestStrategy;
    }
}
