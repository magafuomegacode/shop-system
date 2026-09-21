<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ShopSeeder::class,        // 1. Create the shop first
            StoreSeeder::class,       // 2. Create default stores (Vinywaji, Losheni)
            AdminSeeder::class,       // 3. Create the Admin user (only this is seeded)
            SettingSeeder::class,     // 5. Default settings
        ]);
    }
}