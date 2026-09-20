<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('shops')->insert([
            'id'         => 1,
            'name'       => 'My shop',
            'location'   => 'Rukwa',
            'phone'      => '0712345678',
            'email'      => 'info@myshop.co.tz',
            'is_active'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}