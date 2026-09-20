<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'shop_id', 'store_id', 'full_name', 'username', 'email',
        'password', 'role', 'phone', 'is_active', 'created_by',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'last_login' => 'datetime',
        'password'   => 'hashed',
    ];

    // ----- Relationships -----

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function createdUsers()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'cashier_id');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function loginLogs()
    {
        return $this->hasMany(LoginLog::class);
    }

    // ----- Role helpers -----

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isCashier(): bool
    {
        return $this->role === 'cashier';
    }

    // Check if this user can be created by another user
    public function canBeCreatedBy(User $creator): bool
    {
        // Admin can create Owner; Owner can create Cashier
        if ($creator->isAdmin() && $this->role === 'owner') return true;
        if ($creator->isOwner() && $this->role === 'cashier') return true;
        return false;
    }
}