<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'category_id',   // ✅ NEW
        'name',
        'sku',
        'unit',
        'size',
        'specs',         // ✅ NEW — JSON column
        'cost_price',
        'selling_price',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'size'          => 'decimal:2',
        'specs'         => 'array',          // ✅ auto JSON encode/decode
        'cost_price'    => 'decimal:2',
        'selling_price' => 'decimal:2',
        'is_active'     => 'boolean',
    ];

    // ===== Relationships =====

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * ✅ NEW — Category this product belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    // ===== Helpers =====

    /**
     * Get full display name: "Product Name (Size Unit)"
     * Mfano: "Coca Cola (500 ml)"
     */
    public function getFullNameAttribute(): string
    {
        if ($this->size && $this->unit) {
            $sizeValue = rtrim(rtrim(number_format((float) $this->size, 2, '.', ''), '0'), '.');

            $unitLabels = [
                'kg'    => 'kg',
                'g'     => 'g',
                'litre' => 'L',
                'ml'    => 'ml',
                'metre' => 'm',
            ];

            $shortUnit = $unitLabels[$this->unit] ?? $this->unit;

            return "{$this->name} ({$sizeValue} {$shortUnit})";
        }

        return $this->name;
    }

    /**
     * Get size + unit display (e.g. "500 ml", "1.5 kg")
     */
    public function getSizeDisplayAttribute(): string
    {
        if ($this->size && $this->unit) {
            $sizeValue = rtrim(rtrim(number_format((float) $this->size, 2, '.', ''), '0'), '.');

            $unitLabels = [
                'kg'    => 'kg',
                'g'     => 'g',
                'litre' => 'L',
                'ml'    => 'ml',
                'metre' => 'm',
            ];

            $shortUnit = $unitLabels[$this->unit] ?? $this->unit;

            return "{$sizeValue} {$shortUnit}";
        }

        return '—';
    }
}