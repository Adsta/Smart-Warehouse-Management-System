<?php

declare(strict_types=1);

namespace App\Casts;

use App\ValueObjects\SpatialCoordinate;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/**
 * @implements CastsAttributes<SpatialCoordinate, array<string, int>>
 */
class SpatialCoordinateCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): SpatialCoordinate
    {
        if (!isset($attributes['x_coord'], $attributes['y_coord'], $attributes['z_coord'])) {
            throw new InvalidArgumentException('Model is missing spatial coordinate columns.');
        }

        return new SpatialCoordinate(
            x: (int) $attributes['x_coord'],
            y: (int) $attributes['y_coord'],
            z: (int) $attributes['z_coord'],
        );
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if (!$value instanceof SpatialCoordinate) {
            throw new InvalidArgumentException('Value must be an instance of SpatialCoordinate.');
        }

        return $value->toArray();
    }
}
