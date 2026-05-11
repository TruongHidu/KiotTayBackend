<?php

namespace App\DTOs\PublicMenu;

use App\Models\Restaurant;

final readonly class PublicMenuDTO
{
    /**
     * @param list<PublicMenuGroupDTO> $groups
     */
    public function __construct(
        public PublicMenuRestaurantDTO $restaurant,
        public array $groups,
    ) {}

    public static function fromRestaurant(Restaurant $restaurant): self
    {
        return new self(
            restaurant: PublicMenuRestaurantDTO::fromModel($restaurant),
            groups: $restaurant->itemGroups
                ->map(fn ($group) => PublicMenuGroupDTO::fromModel($group))
                ->values()
                ->all(),
        );
    }

    public function toArray(): array
    {
        return [
            'restaurant' => $this->restaurant->toArray(),
            'groups' => array_map(
                fn (PublicMenuGroupDTO $group) => $group->toArray(),
                $this->groups,
            ),
        ];
    }
}
