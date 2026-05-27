<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventContract extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'contract_number', 'proposal_id', 'booking_id', 'customer_id',
        'terms_version', 'contract_file_path', 'status', 'sent_at', 'signed_at',
        'signed_by_name', 'signed_by_email', 'special_terms'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'signed_at' => 'datetime',
    ];

    public function proposal()
    {
        return $this->belongsTo(EventProposal::class, 'proposal_id');
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
