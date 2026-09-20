<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Beverages',  'description' => 'Drinks and juices', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lotions',    'description' => 'Body lotions and creams', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Soaps',      'description' => 'Bathing and washing soaps', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Food',       'description' => 'Food items', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}