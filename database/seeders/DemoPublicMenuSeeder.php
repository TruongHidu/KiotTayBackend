<?php

namespace Database\Seeders;

use App\Enums\ItemAvailabilityStatus;
use App\Enums\ItemType;
use App\Enums\RestaurantStatus;
use App\Enums\SubscriptionStatus;
use App\Models\Item;
use App\Models\ItemGroup;
use App\Models\Package;
use App\Models\Restaurant;
use App\Models\RestaurantSubscription;
use Illuminate\Database\Seeder;

class DemoPublicMenuSeeder extends Seeder
{
    public function run(): void
    {
        $package = Package::where('code', 'BASIC')->firstOrFail();

        $restaurant = Restaurant::updateOrCreate(
            ['public_order_token' => 'demo-menu'],
            [
                'name' => 'Quan Demo KiotTay',
                'address' => '123 Duong Demo, Quan 1',
                'phone' => '0900000000',
                'status' => RestaurantStatus::ACTIVE->value,
            ],
        );

        RestaurantSubscription::updateOrCreate(
            [
                'restaurant_id' => $restaurant->id,
                'package_id' => $package->id,
            ],
            [
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(30)->toDateString(),
                'status' => SubscriptionStatus::ACTIVE->value,
                'activated_at' => now(),
            ],
        );

        $noodleGroup = ItemGroup::updateOrCreate(
            [
                'restaurant_id' => $restaurant->id,
                'name' => 'Mon Mi',
            ],
            [
                'display_order' => 1,
                'is_active' => true,
            ],
        );

        $drinkGroup = ItemGroup::updateOrCreate(
            [
                'restaurant_id' => $restaurant->id,
                'name' => 'Do uong',
            ],
            [
                'display_order' => 2,
                'is_active' => true,
            ],
        );

        $items = [
            [
                'name' => 'Mi Hai San',
                'group_id' => $noodleGroup->id,
                'sale_price' => 89000,
                'image_url' => 'https://images.unsplash.com/photo-1617093727343-374698b1b08d?q=80&w=1000',
            ],
            [
                'name' => 'Mi Cay Dac Biet',
                'group_id' => $noodleGroup->id,
                'sale_price' => 95000,
                'image_url' => 'https://images.unsplash.com/photo-1557872943-16a5ac26437e?q=80&w=1000',
            ],
            [
                'name' => 'Mi Tom Cay',
                'group_id' => $noodleGroup->id,
                'sale_price' => 99000,
                'image_url' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?q=80&w=1000',
            ],
            [
                'name' => 'Tra Sen Vang',
                'group_id' => $drinkGroup->id,
                'sale_price' => 45000,
                'image_url' => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=1000&q=80',
            ],
        ];

        foreach ($items as $item) {
            Item::updateOrCreate(
                [
                    'restaurant_id' => $restaurant->id,
                    'name' => $item['name'],
                ],
                [
                    'item_group_id' => $item['group_id'],
                    'item_type' => ItemType::MENU_ITEM->value,
                    'unit' => 'Phan',
                    'image_url' => $item['image_url'],
                    'description' => 'Mon demo de kiem tra giao dien public menu.',
                    'cost_price' => 0,
                    'sale_price' => $item['sale_price'],
                    'is_active' => true,
                    'availability_status' => ItemAvailabilityStatus::IN_STOCK->value,
                ],
            );
        }

        $this->command->info('Demo public menu seeded (token: demo-menu)');
    }
}
