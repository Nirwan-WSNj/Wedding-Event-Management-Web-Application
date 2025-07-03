<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingPayment extends Model
{
    use HasFactory;

    protected $table = 'booking_payments'; // Explicitly set to match the migration

    protected $fillable = [
        'booking_id', 'amount', 'payment_method', 'transaction_id', 'status', 'payment_date', 'notes'
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
    ];

    protected $primaryKey = 'id'; // Explicitly define primary key
    public $incrementing = true; // Ensure auto-increment is enabled
    protected $keyType = 'int'; // Ensure integer key type

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function scopeValid($query)
    {
        return $query->whereNotNull('booking_id')
                     ->where('amount', '>', 0);
    }
}