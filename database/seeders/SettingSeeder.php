<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\Shop;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $shop = Shop::first();

        if (!$shop) {
            $this->command->error('❌ No shop found. Run ShopSeeder first.');
            return;
        }

        $settings = [
            'system_name'    => 'Duka System',
            'phone'          => '0712345678',
            'email'          => 'info@duka.co.tz',
            'address'        => 'Rukwa, Tanzania',
            'currency'       => 'TSh',
            'receipt_header' => 'Asante kwa kununua!',
            'receipt_footer' => 'Karibu tena!',
            'discount_max_percent' => 20,
            'discount_allowed'     => '1',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['shop_id' => $shop->id, 'setting_key' => $key],
                [
                    'setting_value' => $value,
                    'setting_group' => 'general',
                    'updated_by'    => null,
                ]
            );
        }

        $this->command->info('✅ Settings ready!');
    }
}