<?php

declare(strict_types=1);

namespace App\DTOs;

use Illuminate\Http\Request;

/**
 * Immutable data transfer object for creating a MovementOrder.
 * Validates and normalises raw request input before it touches the domain.
 */
final readonly class MovementOrderDTO
{
    public function __construct(
        public int $productId,
        public int $destinationLocationId,
        public int $quantity,
        public string $type,
        public ?int $sourceLocationId = null,
        public ?string $notes = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $validated = $request->validate([
            'product_id'              => ['required', 'integer', 'exists:products,id'],
            'destination_location_id' => ['required', 'integer', 'exists:locations,id'],
            'quantity'                => ['required', 'integer', 'min:1'],
            'type'                    => ['required', 'string', 'in:inbound,outbound,transfer'],
            'source_location_id'      => ['nullable', 'integer', 'exists:locations,id'],
            'notes'                   => ['nullable', 'string', 'max:1000'],
        ]);

        return new self(
            productId:             (int) $validated['product_id'],
            destinationLocationId: (int) $validated['destination_location_id'],
            quantity:              (int) $validated['quantity'],
            type:                  $validated['type'],
            sourceLocationId:      isset($validated['source_location_id'])
                                       ? (int) $validated['source_location_id']
                                       : null,
            notes:                 $validated['notes'] ?? null,
        );
    }
}
