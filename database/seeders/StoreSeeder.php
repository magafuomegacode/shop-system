<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $shop = Shop::first();

        if (!$shop) {
            $this->command->error('❌ No shop found. Run ShopSeeder first.');
            return;
        }

        $stores = [
            ['name' => 'Vinywaji', 'type' => 'beverages', 'location' => 'Section A'],
            ['name' => 'Losheni',  'type' => 'lotions',   'location' => 'Section B'],
        ];

        foreach ($stores as $store) {
            Store::updateOrCreate(
                ['shop_id' => $shop->id, 'name' => $store['name']],
                [
                    'type'      => $store['type'],
                    'location'  => $store['location'],
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('✅ Stores ready!');
    }
}