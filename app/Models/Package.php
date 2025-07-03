<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Package extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'min_guests',
        'max_guests',
        'additional_guest_price',
        'image',
        'features',
        'highlight',
        'is_active',
        'manager_approval_required',
        'compatible_halls',
        'seasonal_pricing',
        // 'booking_count', // Calculated field, not fillable
        // 'total_revenue' // Calculated field, not fillable
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'min_guests' => 'integer',
        'max_guests' => 'integer',
        'additional_guest_price' => 'decimal:2',
        'booking_count' => 'integer',
        'total_revenue' => 'decimal:2',
        'highlight' => 'boolean',
        'is_active' => 'boolean',
        'manager_approval_required' => 'boolean',
        'features' => 'array',
        'compatible_halls' => 'array',
        'seasonal_pricing' => 'array'
    ];

    /**
     * App\Models\Package
     *
     * @property float $price
     */

    // Relationships
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function cateringMenus(): BelongsToMany
    {
        return $this->belongsToMany(CateringMenu::class, 'package_catering_menus')
                    ->withTimestamps();
    }

    /**
     * Get the halls compatible with this package.
     */
    public function halls(): BelongsToMany
    {
        return $this->belongsToMany(Hall::class, 'package_hall_compatibility')
            ->withPivot('compatibility_score', 'special_pricing', 'special_features', 'is_recommended', 'compatibility_notes')
            ->withTimestamps();
    }

    /**
     * Get the package hall compatibility records.
     */
    public function hallCompatibility(): HasMany
    {
        return $this->hasMany(PackageHallCompatibility::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWithinBudget($query, $maxBudget)
    {
        return $query->where('price', '<=', $maxBudget);
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
    public function calculateTotalPrice(int $guestCount = 1, array $additionalServices = []): float
    {
        $total = $this->price;

        // Add catering costs based on guest count
        foreach ($this->cateringMenus as $menu) {
            $total += $menu->price_per_person * $guestCount;
        }

        // Add costs for additional services
        foreach ($additionalServices as $serviceId) {
            $service = AdditionalService::find($serviceId);
            if ($service) {
                $total += $service->price;
            }
        }

        return round($total, 2);
    }

    public function getIncludedServices(): array
    {
        return [
            'catering_menus' => $this->cateringMenus()->pluck('name')->toArray(),
            'base_price' => $this->price,
            'description' => $this->description,
        ];
    }

    public function isAvailable(): bool
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

    // Deep Integration Methods

    /**
     * Check if package is compatible with hall.
     */
    public function isCompatibleWithHall($hallId): bool
    {
        if (is_array($this->compatible_halls) && in_array($hallId, $this->compatible_halls)) {
            return true;
        }
        
        return $this->halls()->where('halls.id', $hallId)->exists();
    }

    /**
     * Get compatibility score with hall.
     */
    public function getCompatibilityScoreWithHall($hallId): int
    {
        $compatibility = $this->hallCompatibility()
            ->where('hall_id', $hallId)
            ->first();
            
        return $compatibility ? $compatibility->compatibility_score : 0;
    }

    /**
     * Get effective price for hall (special pricing if available).
     */
    public function getEffectivePriceForHall($hallId): float
    {
        $compatibility = $this->hallCompatibility()
            ->where('hall_id', $hallId)
            ->first();
            
        return $compatibility && $compatibility->special_pricing 
            ? $compatibility->special_pricing 
            : $this->price;
    }

    /**
     * Calculate price for specific guest count.
     */
    public function calculatePriceForGuests(int $guestCount): float
    {
        $basePrice = $this->price;
        
        if ($guestCount > $this->max_guests) {
            $additionalGuests = $guestCount - $this->max_guests;
            $basePrice += $additionalGuests * $this->additional_guest_price;
        }
        
        return $basePrice;
    }

    /**
     * Calculate seasonal pricing.
     */
    public function calculateSeasonalPrice($date = null): float
    {
        if (!$this->seasonal_pricing || !$date) {
            return $this->price;
        }
        
        $month = \Carbon\Carbon::parse($date)->month;
        $multiplier = $this->seasonal_pricing[$month] ?? 1.0;
        
        return $this->price * $multiplier;
    }

    /**
     * Check if package requires manager approval.
     */
    public function requiresManagerApproval(): bool
    {
        return $this->manager_approval_required;
    }

    /**
     * Get package statistics.
     */
    public function getStatistics(): array
    {
        return [
            'total_bookings' => $this->bookings()->count(),
            'confirmed_bookings' => $this->bookings()->where('advance_payment_paid', true)->count(),
            'pending_bookings' => $this->bookings()->where('advance_payment_paid', false)->count(),
            'total_revenue' => $this->bookings()->where('advance_payment_paid', true)->sum('advance_payment_amount'),
            'average_guest_count' => $this->getAverageGuestCount(),
            'booking_success_rate' => $this->getBookingSuccessRate(),
            'most_popular_hall' => $this->getMostPopularHall(),
            'seasonal_performance' => $this->getSeasonalPerformance()
        ];
    }

    /**
     * Get the booking success rate.
     */
    public function getBookingSuccessRate(): float
    {
        $totalBookings = $this->bookings()->count();
        $confirmedBookings = $this->bookings()->where('advance_payment_paid', true)->count();
        
        return $totalBookings > 0 ? round(($confirmedBookings / $totalBookings) * 100, 1) : 0;
    }

    /**
     * Get the average guest count for this package.
     */
    public function getAverageGuestCount(): float
    {
        return $this->bookings()
            ->whereNotNull('guest_count')
            ->avg('guest_count') ?? $this->bookings()
            ->whereNotNull('customization_guest_count')
            ->avg('customization_guest_count') ?? 0;
    }

    /**
     * Get most popular hall for this package.
     */
    public function getMostPopularHall()
    {
        return $this->bookings()
            ->with('hall')
            ->groupBy('hall_id')
            ->selectRaw('hall_id, count(*) as booking_count')
            ->orderBy('booking_count', 'desc')
            ->first()?->hall;
    }

    /**
     * Get seasonal performance data.
     */
    public function getSeasonalPerformance()
    {
        return $this->bookings()
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as bookings, SUM(advance_payment_amount) as revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');
    }

    /**
     * Update booking statistics.
     */
    public function updateBookingStatistics(): void
    {
        // Use updateQuietly to avoid triggering events and infinite loop
        $this->updateQuietly([
            'booking_count' => $this->bookings()->count(),
            'total_revenue' => $this->bookings()->where('advance_payment_paid', true)->sum('advance_payment_amount')
        ]);
    }

    /**
     * Get recommended halls for this package.
     */
    public function getRecommendedHalls()
    {
        return $this->halls()
            ->wherePivot('is_recommended', true)
            ->orderByPivot('compatibility_score', 'desc')
            ->get();
    }

    /**
     * Get all compatible halls ordered by compatibility score.
     */
    public function getCompatibleHallsOrdered()
    {
        return $this->halls()
            ->orderByPivot('compatibility_score', 'desc')
            ->get();
    }

    /**
     * Scope for packages within guest range.
     */
    public function scopeForGuestCount($query, int $guestCount)
    {
        return $query->where('min_guests', '<=', $guestCount)
                    ->where('max_guests', '>=', $guestCount);
    }

    /**
     * Scope for packages compatible with a hall.
     */
    public function scopeCompatibleWithHall($query, $hallId)
    {
        return $query->whereJsonContains('compatible_halls', $hallId)
                    ->orWhereHas('halls', function($q) use ($hallId) {
                        $q->where('halls.id', $hallId);
                    });
    }

    /**
     * Scope for packages requiring manager approval.
     */
    public function scopeRequiringApproval($query)
    {
        return $query->where('manager_approval_required', true);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($package) {
            // Prevent deletion if there are upcoming confirmed bookings
            if ($package->bookings()
                ->where('event_date', '>=', now())
                ->where('status', Booking::STATUS_CONFIRMED)
                ->exists()) {
                return false;
            }
        });

        // Update statistics when package is updated
        static::updated(function ($package) {
            if ($package->wasChanged(['price', 'min_guests', 'max_guests', 'additional_guest_price'])) {
                // Trigger recalculation of affected bookings
                // event(new \App\Events\PackageUpdated($package)); // Commented out - event class doesn't exist
                // $package->updateBookingStatistics(); // Commented out to prevent infinite loop
            }
        });
    }
}