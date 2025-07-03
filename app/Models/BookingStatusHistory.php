<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'previous_status',
        'new_status',
        'changed_by',
        'change_reason',
        'change_metadata',
        'changed_at'
    ];

    protected $casts = [
        'change_metadata' => 'array',
        'changed_at' => 'datetime'
    ];

    /**
     * Get the booking that owns the status history
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the user who made the change
     */
    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    /**
     * Scope for recent changes
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('changed_at', '>=', now()->subDays($days));
    }

    /**
     * Scope for changes by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('changed_by', $userId);
    }

    /**
     * Scope for specific status changes
     */
    public function scopeStatusChange($query, $from, $to)
    {
        return $query->where('previous_status', $from)->where('new_status', $to);
    }

    /**
     * Get time since change
     */
    public function getTimeSinceChangeAttribute()
    {
        return $this->changed_at->diffForHumans();
    }

    /**
     * Get status change description
     */
    public function getChangeDescriptionAttribute()
    {
        if ($this->previous_status && $this->new_status) {
            return "Changed from {$this->previous_status} to {$this->new_status}";
        } elseif ($this->new_status) {
            return "Set to {$this->new_status}";
        }
        return "Status updated";
    }

    /**
     * Check if this was a progression (positive change)
     */
    public function isProgression()
    {
        $progressionMap = [
            'pending' => 1,
            'visit_requested' => 2,
            'visit_approved' => 3,
            'payment_pending' => 4,
            'payment_confirmed' => 5,
            'confirmed' => 6,
            'completed' => 7
        ];

        $previousLevel = $progressionMap[$this->previous_status] ?? 0;
        $newLevel = $progressionMap[$this->new_status] ?? 0;

        return $newLevel > $previousLevel;
    }

    /**
     * Check if this was a regression (negative change)
     */
    public function isRegression()
    {
        return !$this->isProgression() && $this->previous_status !== $this->new_status;
    }
}