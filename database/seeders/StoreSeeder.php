<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('stores')->insert([
            [
                'shop_id'    => 1,
                'name'       => 'Store Vinywaji',
                'type'       => 'beverages',
                'location'   => 'Section A',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shop_id'    => 1,
                'name'       => 'Store Losheni',
                'type'       => 'lotions',
                'location'   => 'Section B',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}