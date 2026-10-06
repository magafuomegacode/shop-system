<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Shop;
use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreAndCategorySeeder extends Seeder
{
    public function run(): void
    {
        $shop = Shop::first();

        if (!$shop) {
            $this->command->error('❌ No shop found. Create a shop first.');
            return;
        }

        $data = [

            // ================= SCENTS =================
            'SCENTS' => [
                'Oil Bared Perfume',
                'Essential Oils',
                'Humidifier',
                'Body Mist',
                'Body Splash / Spray',
                'Air Fresheners',
                'Air Pocket',
                'Car Pocket',
                'Bowry',
                'Stasoft',
            ],

            // ================= DECOR =================
            'DECOR' => [
                'White Duvet',
                'Coloured Duvet',
                'White Bedsheets',
                'Polycotton Bedsheets',
                'Cotton Bedsheets',
                'Throw Pillow',
                '45×45 Pillow Cases',
                'Sleeping Pillow — VIP',
                'Sleeping Pillow — Regular',
                'Sleeping Pillow Cases',
                'Door Sialers',
                'Throw Blankets',
                'Table Runners',
                'fireplace',
                'Bedside Lamps',
                'Standing Lamps',
                '2mtrs Turkish Curtains',
                '1.5mtrs Chance Curtains',
            ],

            // ================= CARPET =================
            'CARPET' => [
                'Floor Carpet',
                'Wall-to-Wall Carpet',
                'Carpet Tiles',
            ],

            // ================= FLOWERS =================
            'FLOWERS' => [
                'Artificial Flowers',
                'Natural Flowers',
                'Flower Vases',
                'Flower Arrangements',
            ],

            // ================= RUGS =================
            'RUGS' => [
                'Small Rugs',
                'Medium Rugs',
                'Large Rugs',
                'Round Rugs',
            ],

            // ================= ARTIFICIAL FOUNTAIN DECOR =================
            'ARTIFICIAL FOUNTAIN DECOR' => [
                'Indoor Fountains',
                'Outdoor Fountains',
                'Tabletop Fountains',
                'Wall Fountains',
            ],

            // ================= DOOR MATS =================
            'DOOR MATS' => [
                'Indoor Door Mats',
                'Outdoor Door Mats',
                'Rubber Door Mats',
                'Coir Door Mats',
            ],
        ];

        $storeCount    = 0;
        $categoryCount = 0;

        foreach ($data as $storeName => $categories) {

            $type = ($storeName === 'SCENTS') ? 'scent' : 'decor';

            $store = Store::firstOrCreate(
                [
                    'shop_id' => $shop->id,
                    'name'    => $storeName,
                ],
                [
                    'type'      => $type,
                    'location'  => null,
                    'is_active' => true,
                ]
            );

            if ($store->wasRecentlyCreated) {
                $storeCount++;
            }

            $this->command->info("🏬 Store: {$store->name}");

            foreach ($categories as $categoryName) {

                // ✅ categories are global — no shop_id / store_id
                $category = Category::firstOrCreate(
                    ['name' => $categoryName],
                    [
                        'description' => "Category for {$storeName}",
                        'is_active'   => true,
                    ]
                );

                if ($category->wasRecentlyCreated) {
                    $categoryCount++;
                }

                $this->command->line("   ↳ {$categoryName}");
            }
        }

        $this->command->info('');
        $this->command->info("✅ Done: {$storeCount} stores, {$categoryCount} categories created.");
    }
}