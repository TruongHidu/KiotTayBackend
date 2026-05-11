<?php

namespace App\DTOs\PublicMenu;

use App\Models\Item;

final readonly class PublicMenuItemDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $description,
        public ?string $imageUrl,
        public string $salePrice,
        public string $unit,
        public string $availabilityStatus,
    ) {}

    public static function fromModel(Item $item): self
    {
        return new self(
            id: $item->id,
            name: $item->name,
            description: $item->description,
            imageUrl: $item->image_url,
            salePrice: (string) $item->sale_price,
            unit: $item->unit,
            availabilityStatus: $item->availability_status->value,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image_url' => $this->imageUrl,
            'sale_price' => $this->salePrice,
            'unit' => $this->unit,
            'availability_status' => $this->availabilityStatus,
        ];
    }
}
