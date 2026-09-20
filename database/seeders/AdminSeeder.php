<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'shop_id'    => 1,
            'store_id'   => null,                    // Admin is not tied to a specific store
            'full_name'  => 'System Administrator',
            'username'   => 'mkcoder',
            'email'      => 'mkcoder1820@gmail.com',
            'password'   => Hash::make('mkcoder1234'), // change after first login
            'role'       => 'admin',
            'phone'      => '0700000000',
            'is_active'  => true,
            'created_by' => null,                    // Admin has no creator
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}