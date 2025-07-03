<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

class AdditionalService extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'per_guest_price',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'per_guest_price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function bookingServices(): HasMany
    {
        return $this->hasMany(BookingAdditionalService::class, 'service_id');
    }

    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class, 'booking_additional_services', 'service_id')
                    ->withPivot('price', 'notes')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWithinBudget($query, $maxPrice)
    {
        return $query->where('price', '<=', $maxPrice);
    }

    public function scopePopular($query, $limit = 5)
    {
        return $query->withCount('bookingServices')
                    ->orderByDesc('booking_services_count')
                    ->limit($limit);
    }

    // Methods
    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function getUsageCount(): int
    {
        return Cache::remember("service_{$this->id}_usage_count", 3600, function () {
            return $this->bookingServices()
                        ->whereHas('booking', function($query) {
                            $query->where('status', Booking::STATUS_CONFIRMED);
                        })
                        ->count();
        });
    }

    public function calculatePriceForGuests(int $guestCount): float
    {
        return $this->price + ($this->per_guest_price ?? 0) * $guestCount;
    }

    public function getRecentBookings($limit = 5)
    {
        return $this->bookings()
                    ->with('user')
                    ->where('status', Booking::STATUS_CONFIRMED)
                    ->orderByDesc('created_at')
                    ->limit($limit)
                    ->get();
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($service) {
            if ($service->bookings()
                ->where('event_date', '>=', now())
                ->where('status', Booking::STATUS_CONFIRMED)
                ->where('event_date', '<=', now()->addMonths(6))
                ->exists()) {
                return false;
            }
        });
    }
}