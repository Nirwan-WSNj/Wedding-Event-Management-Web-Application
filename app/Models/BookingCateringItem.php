<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingCateringItem extends Model
{
    protected $table = 'booking_catering_items';

    protected $fillable = [
        'booking_id',
        'category',
        'item_name',
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            if (!$item->booking_id) {
                throw new \Exception('Booking ID is required for catering item.');
            }
            
            if (!$item->item_name) {
                throw new \Exception('Item name is required for catering item.');
            }
            
            if (!$item->price) {
                $item->price = 0;
            }
        });
    }
}