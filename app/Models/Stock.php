<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id', 'product_id', 'quantity', 'min_quantity',
    ];

    protected $casts = [
        'quantity'     => 'integer',
        'min_quantity' => 'integer',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Check if stock is below minimum
    public function isLow(): bool
    {
        return $this->quantity <= $this->min_quantity;
    }
}