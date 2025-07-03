<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeddingType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relationships
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function decorations()
    {
        return $this->hasMany(WeddingTypeDecoration::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Methods
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
}