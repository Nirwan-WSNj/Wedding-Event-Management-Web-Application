<?php

namespace App\Models;

use App\Notifications\CustomResetPasswordNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public $incrementing = true;
    protected $keyType = 'int';

    public const ROLE_CUSTOMER = 'customer';
    public const ROLE_USER = 'customer';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_MANAGER = 'manager';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'role',
        'user_code',
        'status',
        'profile_photo_path',
        'last_login_at',
        'last_login_ip',
        'login_attempts',
        'password_changed_at',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'login_attempts' => 'integer',
    ];

    protected $appends = [
        'full_name',
        'avatar_url',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim(ucwords("{$this->first_name} {$this->last_name}"));
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->profile_photo_path
            ? asset('storage/' . $this->profile_photo_path)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function getProfilePhotoUrlAttribute(): string
    {
        return $this->profile_photo_path
            ? asset('storage/' . $this->profile_photo_path)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&color=7F9CF5&background=EBF4FF&size=200';
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isManager(): bool
    {
        return $this->role === self::ROLE_MANAGER;
    }

    public function isCustomer(): bool
    {
        return $this->role === self::ROLE_CUSTOMER;
    }

    public function isUser(): bool
    {
        return $this->isCustomer();
    }

    public function incrementLoginAttempts(): void
    {
        $this->increment('login_attempts');
    }

    public function resetLoginAttempts(): void
    {
        $this->forceFill(['login_attempts' => 0])->save();
    }

    public function updatePassword($password): void
    {
        $this->update([
            'password' => Hash::make($password),
            'password_changed_at' => now(),
        ]);
    }

    public function passwordExpired(): bool
    {
        if (!$this->password_changed_at) {
            return true;
        }

        $date = $this->password_changed_at instanceof Carbon
            ? $this->password_changed_at
            : Carbon::parse($this->password_changed_at);

        return $date->copy()->addDays(90)->isPast();
    }

    public function recordLogin($ip): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip,
            'login_attempts' => 0,
        ]);
    }

    public function scopeActive($query)
    {
        return Schema::hasColumn('users', 'status')
            ? $query->where('status', 'active')
            : $query;
    }

    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        });
    }

    public function getVirtualIdAttribute(): string
    {
        return $this->user_code ?: $this->generateProfessionalCode();
    }

    public function getProfessionalDisplayIdAttribute(): string
    {
        return $this->getVirtualIdAttribute();
    }

    public function generateProfessionalCode(): string
    {
        $prefix = match ($this->role) {
            self::ROLE_ADMIN => 'ADM',
            self::ROLE_MANAGER => 'MGR',
            default => 'CUS',
        };

        $nextNumber = ((int) $this->id) ?: ((int) static::max('id') + 1);

        return $prefix . '-' . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }

    protected static function booted(): void
    {
        static::created(function ($user) {
            if (Schema::hasColumn('users', 'user_code') && empty($user->user_code)) {
                $user->forceFill(['user_code' => $user->generateProfessionalCode()])->saveQuietly();
            }
        });
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
}
