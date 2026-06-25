<?php

declare(strict_types=1);

namespace App\ValueObjects;

use InvalidArgumentException;

final class PhysicalDimension
{
    public function __construct(
        public readonly float $weight,
        public readonly float $volume,
    ) {
        if ($weight <= 0) {
            throw new InvalidArgumentException('Weight must be a positive value.');
        }

        if ($volume <= 0) {
            throw new InvalidArgumentException('Volume must be a positive value.');
        }
    }

    public function fitsWithin(PhysicalDimension $capacity): bool
    {
        return $this->weight <= $capacity->weight && $this->volume <= $capacity->volume;
    }

    public function multiply(int $quantity): self
    {
        return new self(
            weight: $this->weight * $quantity,
            volume: $this->volume * $quantity,
        );
    }

    public function equals(PhysicalDimension $other): bool
    {
        return $this->weight === $other->weight && $this->volume === $other->volume;
    }

    public function toArray(): array
    {
        return ['weight' => $this->weight, 'volume' => $this->volume];
    }

    public function __toString(): string
    {
        return "{$this->weight}kg / {$this->volume}m³";
    }
}
