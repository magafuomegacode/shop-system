<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('settings')->insert([
            ['shop_id' => 1, 'setting_key' => 'shop_name',            'setting_value' => 'My Shop',         'setting_group' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['shop_id' => 1, 'setting_key' => 'currency',             'setting_value' => 'TSh',             'setting_group' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['shop_id' => 1, 'setting_key' => 'date_format',          'setting_value' => 'd/m/Y',           'setting_group' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['shop_id' => 1, 'setting_key' => 'timezone',             'setting_value' => 'Africa/Dar_es_Salaam', 'setting_group' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['shop_id' => 1, 'setting_key' => 'tax_enabled',          'setting_value' => '0',               'setting_group' => 'tax',     'created_at' => now(), 'updated_at' => now()],
            ['shop_id' => 1, 'setting_key' => 'tax_percent',          'setting_value' => '0',               'setting_group' => 'tax',     'created_at' => now(), 'updated_at' => now()],
            ['shop_id' => 1, 'setting_key' => 'discount_allowed',     'setting_value' => '1',               'setting_group' => 'discount','created_at' => now(), 'updated_at' => now()],
            ['shop_id' => 1, 'setting_key' => 'discount_max_percent', 'setting_value' => '20',              'setting_group' => 'discount','created_at' => now(), 'updated_at' => now()],
            ['shop_id' => 1, 'setting_key' => 'low_stock_alert',      'setting_value' => '5',               'setting_group' => 'stock',   'created_at' => now(), 'updated_at' => now()],
            ['shop_id' => 1, 'setting_key' => 'receipt_header',       'setting_value' => 'Thank you!',      'setting_group' => 'receipt', 'created_at' => now(), 'updated_at' => now()],
            ['shop_id' => 1, 'setting_key' => 'receipt_footer',       'setting_value' => 'Goods sold are not returnable', 'setting_group' => 'receipt', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}