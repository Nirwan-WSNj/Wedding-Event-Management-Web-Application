<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingDecoration extends Model
{
    protected $table = 'booking_decorations';

    protected $fillable = [
        'booking_id',
        'decoration_id',
        'quantity'
    ];

    protected $casts = [
        'quantity' => 'integer'
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function decoration(): BelongsTo
    {
        return $this->belongsTo(Decoration::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($bookingDecoration) {
            if ($bookingDecoration->decoration_id && !Decoration::where('id', $bookingDecoration->decoration_id)->exists()) {
                throw new \Exception('Invalid decoration selected.');
            }
            
            if (!$bookingDecoration->quantity) {
                $bookingDecoration->quantity = 1;
            }
        });
    }
}