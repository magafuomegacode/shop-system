<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Unda Shop kama haipo (ili tupate shop_id halali)
        $shop = Shop::firstOrCreate(
            ['id' => 1],
            [
                'name'      => 'Duka Kuu',
                'phone'     => '0700000000',
                'location'  => 'Dar es Salaam',
                'is_active' => true,
            ]
        );

        // 2. Unda Admin kama haipo (kwa kutumia username kama key)
        $admin = User::updateOrCreate(
            ['username' => 'mkcoder'],
            [
                'shop_id'    => $shop->id,
                'store_id'   => null,
                'full_name'  => 'System Administrator',
                'email'      => 'mkcoder1820@gmail.com',
                'password'   => Hash::make('mkcoder1234'),
                'role'       => 'admin',
                'phone'      => '0700000000',
                'is_active'  => true,
                'created_by' => null,
            ]
        );

        $this->command->info('✅ Admin user ready!');
        $this->command->info('   Username: mkcoder');
        $this->command->info('   Password: mkcoder1234');
        $this->command->info('   Role:     admin');
    }
}