<?php

namespace Database\Seeders;

use App\Models\Shop;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        $shop = Shop::updateOrCreate(
            ['id' => 1],
            [
                'name'      => 'My Shop',
                'location'  => 'Rukwa',
                'phone'     => '0712345678',
                'email'     => 'info@myshop.co.tz',
                'is_active' => true,
            ]
        );

        $this->command->info('✅ Shop ready!');
        $this->command->info('   Name: ' . $shop->name);
        $this->command->info('   ID:   ' . $shop->id);
    }
}