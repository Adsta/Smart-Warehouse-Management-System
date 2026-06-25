<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\SpatialCoordinateCast;
use App\ValueObjects\SpatialCoordinate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property SpatialCoordinate $coordinates
 * @property float $max_weight
 * @property float $max_volume
 */
class Location extends Model
{
    protected $fillable = [
        'zone_id',
        'code',
        'x_coord',
        'y_coord',
        'z_coord',
        'max_weight',
        'max_volume',
        'is_active',
    ];

    protected $casts = [
        'coordinates' => SpatialCoordinateCast::class,
        'max_weight'  => 'float',
        'max_volume'  => 'float',
        'is_active'   => 'boolean',
    ];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function movementOrdersAsSource(): HasMany
    {
        return $this->hasMany(MovementOrder::class, 'source_location_id');
    }

    public function movementOrdersAsDestination(): HasMany
    {
        return $this->hasMany(MovementOrder::class, 'destination_location_id');
    }

    public function distanceFromInboundZone(SpatialCoordinate $inboundOrigin): float
    {
        return $this->coordinates->calculateEuclideanDistance($inboundOrigin);
    }

    public function isColdStorage(): bool
    {
        return $this->zone->isColdStorage();
    }
}
