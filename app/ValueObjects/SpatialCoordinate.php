<?php

declare(strict_types=1);

namespace App\ValueObjects;

use InvalidArgumentException;

final class SpatialCoordinate
{
    public function __construct(
        public readonly int $x,
        public readonly int $y,
        public readonly int $z,
    ) {
        if ($x < 0 || $y < 0 || $z < 0) {
            throw new InvalidArgumentException('Spatial coordinates must be non-negative.');
        }
    }

    public function calculateEuclideanDistance(SpatialCoordinate $target): float
    {
        return sqrt(
            ($this->x - $target->x) ** 2 +
            ($this->y - $target->y) ** 2 +
            ($this->z - $target->z) ** 2
        );
    }

    public function equals(SpatialCoordinate $other): bool
    {
        return $this->x === $other->x
            && $this->y === $other->y
            && $this->z === $other->z;
    }

    public function toArray(): array
    {
        return ['x_coord' => $this->x, 'y_coord' => $this->y, 'z_coord' => $this->z];
    }

    public function __toString(): string
    {
        return "({$this->x}, {$this->y}, {$this->z})";
    }
}
