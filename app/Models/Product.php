<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\PhysicalDimensionCast;
use App\ValueObjects\PhysicalDimension;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property PhysicalDimension $dimensions
 */
class Product extends Model
{
    protected $fillable = [
        'sku',
        'name',
        'description',
        'weight',
        'volume',
        'requires_cold_storage',
        'is_hazmat',
    ];

    protected $casts = [
        'dimensions'            => PhysicalDimensionCast::class,
        'requires_cold_storage' => 'boolean',
        'is_hazmat'             => 'boolean',
    ];

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function movementOrders(): HasMany
    {
        return $this->hasMany(MovementOrder::class);
    }

    public function requiresSpecialHandling(): bool
    {
        return $this->requires_cold_storage || $this->is_hazmat;
    }
}
