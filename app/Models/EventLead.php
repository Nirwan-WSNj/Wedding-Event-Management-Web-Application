<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventLead extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'lead_number',
        'customer_id',
        'assigned_manager_id',
        'customer_name',
        'email',
        'phone',
        'preferred_contact_method',
        'event_type',
        'preferred_event_date',
        'estimated_guest_count',
        'estimated_budget',
        'source',
        'status',
        'contacted_at',
        'converted_at',
        'notes',
    ];

    protected $casts = [
        'preferred_event_date' => 'date',
        'estimated_guest_count' => 'integer',
        'estimated_budget' => 'decimal:2',
        'contacted_at' => 'datetime',
        'converted_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function assignedManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_manager_id');
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(EventProposal::class, 'lead_id');
    }
}
