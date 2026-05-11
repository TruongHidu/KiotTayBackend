<?php

namespace App\Services;

use App\DTOs\PublicMenu\PublicMenuDTO;
use App\Enums\FeatureCode;
use App\Enums\ItemAvailabilityStatus;
use App\Models\Restaurant;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PublicMenuService
{
    public function getByToken(string $token): PublicMenuDTO
    {
        $restaurant = Restaurant::query()
            ->where('public_order_token', $token)
            ->with([
                'activeSubscription.package.features',
                'itemGroups' => fn ($query) => $query
                    ->where('is_active', true)
                    ->orderBy('display_order'),
                'itemGroups.items' => fn ($query) => $query
                    ->where('is_active', true)
                    ->where('availability_status', ItemAvailabilityStatus::IN_STOCK->value)
                    ->orderBy('name'),
            ])
            ->first();

        if (! $restaurant) {
            throw new NotFoundHttpException('Menu not found.');
        }

        if (! $restaurant->isAccessible()) {
            throw new AccessDeniedHttpException('Restaurant is not accessible.');
        }

        if (! $restaurant->hasFeature(FeatureCode::MENU_MANAGEMENT->value)) {
            throw new AccessDeniedHttpException('Menu is not available for this restaurant.');
        }

        return PublicMenuDTO::fromRestaurant($restaurant);
    }
}
