<?php

declare(strict_types=1);

namespace App\Contracts\Warehouse;

use App\Models\Location;
use App\Models\Product;

interface PutawayStrategyInterface
{
    /**
     * Find the optimal storage location for a given product and quantity.
     * Returns null when no suitable location can be found.
     */
    public function findOptimalLocation(Product $product, int $quantity): ?Location;
}
