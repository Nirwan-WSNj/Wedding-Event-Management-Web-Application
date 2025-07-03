<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Decoration extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function bookingDecorations(): HasMany
    {
        return $this->hasMany(BookingDecoration::class);
    }

    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class, 'booking_decorations')
                    ->withPivot('price', 'notes')
                    ->withTimestamps();
    }

    public function weddingTypes()
    {
        return $this->hasMany(WeddingTypeDecoration::class);
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
        return $query->withCount('bookingDecorations')
                    ->orderByDesc('booking_decorations_count')
                    ->limit($limit);
    }

    // Methods
    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function getUsageCount(): int
    {
        return $this->bookingDecorations()
                    ->whereHas('booking', function($query) {
                        $query->where('status', Booking::STATUS_CONFIRMED);
                    })
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

        static::deleting(function ($decoration) {
            // Prevent deletion if there are upcoming confirmed bookings using this decoration
            if ($decoration->bookings()
                ->where('event_date', '>=', now())
                ->where('status', Booking::STATUS_CONFIRMED)
                ->exists()) {
                return false;
            }
        });
    }
}