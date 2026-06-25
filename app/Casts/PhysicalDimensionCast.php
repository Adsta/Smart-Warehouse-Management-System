<?php

declare(strict_types=1);

namespace App\Casts;

use App\ValueObjects\PhysicalDimension;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/**
 * @implements CastsAttributes<PhysicalDimension, array<string, float>>
 */
class PhysicalDimensionCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): PhysicalDimension
    {
        if (!isset($attributes['weight'], $attributes['volume'])) {
            throw new InvalidArgumentException('Model is missing physical dimension columns.');
        }

        return new PhysicalDimension(
            weight: (float) $attributes['weight'],
            volume: (float) $attributes['volume'],
        );
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if (!$value instanceof PhysicalDimension) {
            throw new InvalidArgumentException('Value must be an instance of PhysicalDimension.');
        }

        return $value->toArray();
    }
}
