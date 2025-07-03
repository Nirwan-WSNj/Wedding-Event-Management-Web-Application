<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingCatering extends Model
{
    protected $table = 'booking_catering';

    protected $fillable = [
        'booking_id',
        'menu_id',
        'guest_count',
        'price_per_person',
        'total_price',
        'special_requests'
    ];

    protected $casts = [
        'guest_count' => 'integer',
        'price_per_person' => 'decimal:2',
        'total_price' => 'decimal:2'
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(CateringMenu::class, 'menu_id');
    }

    public function calculateTotalPrice(): float
    {
        if (!$this->guest_count || !$this->price_per_person) {
            throw new \InvalidArgumentException('Guest count and price per person are required');
        }
        
        return round($this->guest_count * $this->price_per_person, 2);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($bookingCatering) {
        $bookingCatering->loadMissing('menu');
        if (!$bookingCatering->price_per_person) {
        $bookingCatering->price_per_person = $bookingCatering->menu->price_per_person;
        }
        
        if (!$bookingCatering->total_price) {
        $bookingCatering->total_price = $bookingCatering->calculateTotalPrice();
        }
        
        // Validate guest count against menu limits
        if (!$bookingCatering->menu->isAvailableForGuestCount($bookingCatering->guest_count)) {
        throw new \InvalidArgumentException('Guest count is outside the allowed range for this menu');
        }
        });
        
        static::updating(function ($bookingCatering) {
        $bookingCatering->loadMissing('menu');
        if ($bookingCatering->isDirty(['guest_count', 'price_per_person'])) {
        $bookingCatering->total_price = $bookingCatering->calculateTotalPrice();
        }
        
        if ($bookingCatering->isDirty('guest_count') && 
        !$bookingCatering->menu->isAvailableForGuestCount($bookingCatering->guest_count)) {
        throw new \InvalidArgumentException('Guest count is outside the allowed range for this menu');
        }
        });
    }
}