<?php

namespace App\DTOs\PublicMenu;

use App\Models\ItemGroup;

final readonly class PublicMenuGroupDTO
{
    /**
     * @param list<PublicMenuItemDTO> $items
     */
    public function __construct(
        public string $id,
        public string $name,
        public int $displayOrder,
        public array $items,
    ) {}

    public static function fromModel(ItemGroup $group): self
    {
        return new self(
            id: $group->id,
            name: $group->name,
            displayOrder: (int) $group->display_order,
            items: $group->items
                ->map(fn ($item) => PublicMenuItemDTO::fromModel($item))
                ->values()
                ->all(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'display_order' => $this->displayOrder,
            'items' => array_map(
                fn (PublicMenuItemDTO $item) => $item->toArray(),
                $this->items,
            ),
        ];
    }
}
