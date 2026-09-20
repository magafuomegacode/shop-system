<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'username', 'status', 'ip_address', 'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Static helper: record a login attempt
    public static function record(
        ?int $userId,
        string $username,
        string $status
    ): self {
        return static::create([
            'user_id'    => $userId,
            'username'   => $username,
            'status'     => $status,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}