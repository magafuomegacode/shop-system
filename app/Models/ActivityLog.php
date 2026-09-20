<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'shop_id', 'store_id',
        'action', 'module', 'description',
        'reference_id', 'reference_table',
        'old_values', 'new_values',
        'ip_address', 'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // Static helper: log any activity
    public static function log(
        ?int $userId,
        int $shopId,
        ?int $storeId,
        string $action,
        string $module,
        ?string $description = null,
        ?int $referenceId = null,
        ?string $referenceTable = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): self {
        return static::create([
            'user_id'         => $userId,
            'shop_id'         => $shopId,
            'store_id'        => $storeId,
            'action'          => $action,
            'module'          => $module,
            'description'     => $description,
            'reference_id'    => $referenceId,
            'reference_table' => $referenceTable,
            'old_values'      => $oldValues,
            'new_values'      => $newValues,
            'ip_address'      => request()->ip(),
            'user_agent'      => request()->userAgent(),
        ]);
    }
}