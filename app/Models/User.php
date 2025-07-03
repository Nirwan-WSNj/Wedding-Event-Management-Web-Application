<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Notifications\CustomResetPasswordNotification;

/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property string $role
 * @property string|null $profile_photo_path
 * @property string|null $last_login_ip
 * @property int $login_attempts
 * @property string|null $password_changed_at
 * @property string|null $last_login_at
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read string $full_name
 * @property-read string $profile_photo_url
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Use string IDs instead of auto-incrementing integers
    public $incrementing = false;
    protected $keyType = 'string';

    // Role constants
    public const ROLE_CUSTOMER = 'customer';
    public const ROLE_USER = 'customer'; // Alias for clarity
    public const ROLE_ADMIN = 'admin';
    public const ROLE_MANAGER = 'manager';

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'role',
        'profile_photo_path',
        'last_login_at',
        'last_login_ip',
        'login_attempts',
        'password_changed_at'
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
        'login_attempts' => 'integer'
    ];

    protected $appends = [
        'full_name',
        'avatar_url'
    ];
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getFullNameAttribute()
    {
        return ucwords("{$this->first_name} {$this->last_name}");
    }

    public function getAvatarUrlAttribute()
    {
        return $this->profile_photo_path 
            ? asset('storage/' . $this->profile_photo_path)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&color=7F9CF5&background=EBF4FF';
    }
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isManager()
    {
        return $this->role === self::ROLE_MANAGER;
    }

    public function isCustomer()
    {
        return $this->role === self::ROLE_CUSTOMER;
    }

    public function isUser()
    {
        return $this->role === self::ROLE_USER || $this->role === self::ROLE_CUSTOMER;
    }

    public function incrementLoginAttempts()
    {
        $this->increment('login_attempts');
        $this->save();
    }

    public function resetLoginAttempts()
    {
        $this->login_attempts = 0;
        $this->save();
    }

    public function updatePassword($password)
    {
        $this->update([
            'password' => Hash::make($password),
            'password_changed_at' => now()
        ]);
    }

    public function passwordExpired()
    {
        if (!$this->password_changed_at) {
            return true;
        }
        $date = $this->password_changed_at instanceof Carbon
            ? $this->password_changed_at
            : Carbon::parse($this->password_changed_at);
        return $date->addDays(90)->isPast();
    }

    public function recordLogin($ip)
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip,
            'login_attempts' => 0
        ]);
    }

    public function scopeActive($query)
    {
        return $query;
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

    /**
     * Get the URL for the user's profile photo
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }
        
        // Fallback to UI Avatars service
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&color=7F9CF5&background=EBF4FF&size=200';
    }

    /**
     * Get the virtual ID for display (e.g., cus 1, ad 2, hm 3)
     *
     * @return string
     */
    public function getVirtualIdAttribute()
    {
        if ($this->role === self::ROLE_ADMIN) {
            $prefix = 'ad ';
        } elseif ($this->role === self::ROLE_MANAGER) {
            $prefix = 'hm ';
        } else {
            $prefix = 'cus ';
        }
        return $prefix . $this->id;
    }

    /**
     * Generate professional ID for new users
     */
    public function generateProfessionalId(): string
    {
        switch($this->role) {
            case self::ROLE_ADMIN:
                $prefix = 'A';
                break;
            case self::ROLE_MANAGER:
                $prefix = 'M';
                break;
            case self::ROLE_CUSTOMER:
                $prefix = 'CUS';
                break;
            default:
                $prefix = 'CUS';
                break;
        }

        // Get the latest user with the same role to determine next number
        $latestUser = self::where('role', $this->role)
                         ->where('id', 'like', $prefix.'%')
                         ->orderByDesc('id')
                         ->first();

        $number = 1;
        if ($latestUser && $latestUser->id) {
            $lastNumber = (int)substr($latestUser->id, strlen($prefix));
            $number = $lastNumber + 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        parent::booted();
        
        static::creating(function ($user) {
            if (!$user->id) {
                $user->id = $user->generateProfessionalId();
            }
        });
    }

    /**
     * Get the professional display ID (same as ID now)
     */
    public function getProfessionalDisplayIdAttribute(): string
    {
        return $this->id;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
}