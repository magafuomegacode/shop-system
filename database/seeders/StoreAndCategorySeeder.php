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

        // ============================================================
        // 🧹 STEP 1 — Cleanup: delete all stores except SCENTS & DECOR
        // ============================================================
        $keepStores = ['SCENTS', 'DECOR'];

        $storesToDelete = Store::where('shop_id', $shop->id)
            ->whereNotIn('name', $keepStores)
            ->get();

        foreach ($storesToDelete as $oldStore) {
            // Detach categories first
            Category::where('store_id', $oldStore->id)->update(['store_id' => null]);
            $oldStore->delete();
            $this->command->warn("🗑️  Deleted old store: {$oldStore->name}");
        }

        // ============================================================
        // 🧹 STEP 2 — Cleanup: delete orphaned categories
        //          (any category with store_id = null)
        // ============================================================
        $orphanedCategories = Category::whereNull('store_id')->get();

        foreach ($orphanedCategories as $cat) {
            $cat->delete();
            $this->command->warn("🗑️  Deleted orphan category: {$cat->name}");
        }

        // ============================================================
        // 🌱 STEP 3 — Seed the 2 stores and their categories
        // ============================================================
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

                // Merged in from old standalone stores
                'ARTIFICIAL FOUNTAIN DECOR',
                'CARPET',
                'DOOR MATS',
                'FLOWERS',
                'RUGS',
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

            $this->command->info("🏬 Store: {$store->name} (id={$store->id})");

            foreach ($categories as $categoryName) {

                $category = Category::updateOrCreate(
                    ['name' => $categoryName],
                    [
                        'store_id'    => $store->id,
                        'description' => "Category under {$storeName}",
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
        $this->command->info("✅ Done: {$storeCount} new stores, {$categoryCount} new categories.");
    }
}