<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CateringMenu extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price_per_person',
        'is_active',
        'minimum_guests',
        'maximum_guests'
    ];

    protected $casts = [
        'price_per_person' => 'decimal:2',
        'is_active' => 'boolean',
        'minimum_guests' => 'integer',
        'maximum_guests' => 'integer'
    ];

    // Relationships
    public function menuItems(): HasMany
    {
        return $this->hasMany(CateringItem::class, 'menu_id');
    }

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'package_catering_menus')
                    ->withTimestamps();
    }

    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class, 'booking_catering')
                    ->withPivot('guest_count', 'price_per_person', 'total_price', 'special_requests')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForGuestCount($query, $guestCount)
    {
        return $query->where('minimum_guests', '<=', $guestCount)
                    ->where(function($query) use ($guestCount) {
                        $query->where('maximum_guests', '>=', $guestCount)
                              ->orWhereNull('maximum_guests');
                    });
    }

    public function scopeWithinBudget($query, $maxPricePerPerson)
    {
        return $query->where('price_per_person', '<=', $maxPricePerPerson);
    }

    public function scopePopular($query, $limit = 5)
    {
        return $query->withCount(['bookings' => function($query) {
            $query->where('status', Booking::STATUS_CONFIRMED);
        }])
        ->orderByDesc('bookings_count')
        ->limit($limit);
    }

    // Methods
    public function calculateTotalPrice(int $guestCount): float
    {
        if ($this->minimum_guests > $guestCount || 
            ($this->maximum_guests && $this->maximum_guests < $guestCount)) {
            throw new \InvalidArgumentException('Guest count out of allowed range');
        }

        return round($this->price_per_person * $guestCount, 2);
    }

    public function isAvailableForGuestCount(int $guestCount): bool
    {
        return $guestCount >= $this->minimum_guests && 
               (!$this->maximum_guests || $guestCount <= $this->maximum_guests);
    }

    public function getMenuItemsByCategory(): array
    {
        return $this->menuItems()
                    ->get()
                    ->groupBy('category')
                    ->toArray();
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function getBookingCount(): int
    {
        return $this->bookings()
                    ->where('status', Booking::STATUS_CONFIRMED)
                    ->count();
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

        static::deleting(function ($menu) {
            // Prevent deletion if there are upcoming confirmed bookings using this menu
            if ($menu->bookings()
                ->where('event_date', '>=', now())
                ->where('status', Booking::STATUS_CONFIRMED)
                ->exists()) {
                return false;
            }
        });
    }
}