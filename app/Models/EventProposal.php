<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventProposal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'proposal_number',
        'lead_id',
        'booking_id',
        'customer_id',
        'created_by',
        'venue_amount',
        'package_amount',
        'catering_amount',
        'decoration_amount',
        'service_amount',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'valid_until',
        'status',
        'sent_at',
        'accepted_at',
        'terms',
        'notes',
    ];

    protected $casts = [
        'venue_amount' => 'decimal:2',
        'package_amount' => 'decimal:2',
        'catering_amount' => 'decimal:2',
        'decoration_amount' => 'decimal:2',
        'service_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'valid_until' => 'date',
        'sent_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    public function lead()
    {
        return $this->belongsTo(EventLead::class, 'lead_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
