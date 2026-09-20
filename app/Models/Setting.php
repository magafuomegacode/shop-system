<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'shop_id',
        'setting_key',
        'setting_value',
        'setting_group',
        'updated_by',
    ];

    /**
     * Get a setting value for a shop.
     */
    public static function get(int $shopId, string $key, $default = null)
    {
        $row = static::where('shop_id', $shopId)
            ->where('setting_key', $key)
            ->first();

        return $row && $row->setting_value !== null
            ? $row->setting_value
            : $default;
    }

    /**
     * Set a setting value for a shop.
     */
    public static function set(int $shopId, string $key, $value, string $group = 'general'): void
    {
        static::updateOrCreate(
            ['shop_id' => $shopId, 'setting_key' => $key],
            [
                'setting_value' => $value,
                'setting_group' => $group,
                'updated_by'    => auth()->id() ?? 1,
            ]
        );
    }

    /**
     * Set many settings at once.
     */
    public static function setMany(int $shopId, array $data, string $group = 'general'): void
    {
        foreach ($data as $key => $value) {
            static::set($shopId, $key, $value, $group);
        }
    }
}