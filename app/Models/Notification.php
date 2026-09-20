<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'user_id',
        'type',
        'title',
        'message',
        'link',
        'icon',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // ===== Relationships =====

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ===== Static Helper (imebadilishwa: push → send) =====

    /**
     * Send a new notification.
     */
    public static function send(
        int $shopId,
        string $type,
        string $title,
        ?string $message = null,
        ?string $link = null,
        ?string $icon = null,
        ?int $userId = null
    ): self {
        return static::create([
            'shop_id' => $shopId,
            'user_id' => $userId,
            'type'    => $type,
            'title'   => $title,
            'message' => $message,
            'link'    => $link,
            'icon'    => $icon,
            'is_read' => false,
        ]);
    }

    // ===== Scopes =====

    public function scopeForUser($query, int $shopId, ?int $userId = null)
    {
        return $query->where('shop_id', $shopId)
            ->where(function ($q) use ($userId) {
                $q->whereNull('user_id');
                if ($userId) {
                    $q->orWhere('user_id', $userId);
                }
            });
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderByDesc('created_at');
    }

    // ===== Helpers =====

    public function getIconColorAttribute(): string
    {
        return match ($this->type) {
            'product_added' => 'from-blue-500 to-cyan-500',
            'sale_made'     => 'from-green-500 to-emerald-500',
            'low_stock'     => 'from-yellow-500 to-orange-500',
            default         => 'from-indigo-500 to-purple-500',
        };
    }

    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }
}