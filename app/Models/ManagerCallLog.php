<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagerCallLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'manager_id',
        'call_status',
        'call_notes',
        'call_attempted_at',
        'customer_phone',
        'customer_name',
        'call_duration_seconds',
        'call_outcome',
        'call_metadata'
    ];

    protected $casts = [
        'call_attempted_at' => 'datetime',
        'call_duration_seconds' => 'integer',
        'call_metadata' => 'array'
    ];

    /**
     * Get the booking that owns the call log
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the manager who made the call
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Scope for successful calls
     */
    public function scopeSuccessful($query)
    {
        return $query->where('call_status', 'successful');
    }

    /**
     * Scope for failed calls
     */
    public function scopeFailed($query)
    {
        return $query->whereIn('call_status', ['no_answer', 'busy', 'invalid_number']);
    }

    /**
     * Scope for calls by manager
     */
    public function scopeByManager($query, $managerId)
    {
        return $query->where('manager_id', $managerId);
    }

    /**
     * Scope for recent calls
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('call_attempted_at', '>=', now()->subDays($days));
    }

    /**
     * Get call duration in minutes
     */
    public function getCallDurationMinutesAttribute()
    {
        return $this->call_duration_seconds ? round($this->call_duration_seconds / 60, 1) : null;
    }

    /**
     * Get formatted call duration
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->call_duration_seconds) {
            return 'N/A';
        }

        $minutes = floor($this->call_duration_seconds / 60);
        $seconds = $this->call_duration_seconds % 60;

        if ($minutes > 0) {
            return "{$minutes}m {$seconds}s";
        } else {
            return "{$seconds}s";
        }
    }

    /**
     * Get call status color for UI
     */
    public function getStatusColorAttribute()
    {
        switch($this->call_status) {
            case 'successful':
                return 'green';
            case 'no_answer':
                return 'yellow';
            case 'busy':
                return 'orange';
            case 'invalid_number':
                return 'red';
            default:
                return 'gray';
        }
    }

    /**
     * Get call status icon
     */
    public function getStatusIconAttribute()
    {
        switch($this->call_status) {
            case 'successful':
                return 'ri-phone-line';
            case 'no_answer':
                return 'ri-phone-lock-line';
            case 'busy':
                return 'ri-phone-lock-line';
            case 'invalid_number':
                return 'ri-phone-lock-line';
            default:
                return 'ri-question-line';
        }
    }

    /**
     * Get call outcome color for UI
     */
    public function getOutcomeColorAttribute()
    {
        switch($this->call_outcome) {
            case 'visit_approved':
                return 'green';
            case 'visit_rejected':
                return 'red';
            case 'reschedule_requested':
                return 'yellow';
            case 'no_decision':
                return 'gray';
            default:
                return 'gray';
        }
    }

    /**
     * Get time since call
     */
    public function getTimeSinceCallAttribute()
    {
        return $this->call_attempted_at->diffForHumans();
    }

    /**
     * Check if call was successful
     */
    public function isSuccessful()
    {
        return $this->call_status === 'successful';
    }

    /**
     * Check if call resulted in approval
     */
    public function resultedInApproval()
    {
        return $this->call_outcome === 'visit_approved';
    }

    /**
     * Get call statistics for a manager
     */
    public static function getStatsForManager($managerId, $period = '30d')
    {
        $query = static::where('manager_id', $managerId);

        // Apply time filter
        switch ($period) {
            case '7d':
                $query->where('call_attempted_at', '>=', now()->subWeek());
                break;
            case '30d':
                $query->where('call_attempted_at', '>=', now()->subMonth());
                break;
            case '90d':
                $query->where('call_attempted_at', '>=', now()->subDays(90));
                break;
        }

        $total = $query->count();
        $successful = $query->where('call_status', 'successful')->count();
        $approvals = $query->where('call_outcome', 'visit_approved')->count();
        $avgDuration = $query->whereNotNull('call_duration_seconds')->avg('call_duration_seconds');

        return [
            'total_calls' => $total,
            'successful_calls' => $successful,
            'success_rate' => $total > 0 ? round(($successful / $total) * 100, 1) : 0,
            'approvals' => $approvals,
            'approval_rate' => $successful > 0 ? round(($approvals / $successful) * 100, 1) : 0,
            'avg_duration_seconds' => $avgDuration ? round($avgDuration) : null,
            'avg_duration_minutes' => $avgDuration ? round($avgDuration / 60, 1) : null,
            'by_status' => $query->groupBy('call_status')
                ->selectRaw('call_status, count(*) as count')
                ->pluck('count', 'call_status'),
            'by_outcome' => $query->groupBy('call_outcome')
                ->selectRaw('call_outcome, count(*) as count')
                ->pluck('count', 'call_outcome')
        ];
    }

    /**
     * Get overall call statistics
     */
    public static function getOverallStats($period = '30d')
    {
        $query = static::query();

        // Apply time filter
        switch ($period) {
            case '7d':
                $query->where('call_attempted_at', '>=', now()->subWeek());
                break;
            case '30d':
                $query->where('call_attempted_at', '>=', now()->subMonth());
                break;
            case '90d':
                $query->where('call_attempted_at', '>=', now()->subDays(90));
                break;
        }

        $total = $query->count();
        $successful = $query->where('call_status', 'successful')->count();
        $approvals = $query->where('call_outcome', 'visit_approved')->count();
        $avgDuration = $query->whereNotNull('call_duration_seconds')->avg('call_duration_seconds');

        return [
            'total_calls' => $total,
            'successful_calls' => $successful,
            'success_rate' => $total > 0 ? round(($successful / $total) * 100, 1) : 0,
            'approvals' => $approvals,
            'approval_rate' => $successful > 0 ? round(($approvals / $successful) * 100, 1) : 0,
            'avg_duration_seconds' => $avgDuration ? round($avgDuration) : null,
            'avg_duration_minutes' => $avgDuration ? round($avgDuration / 60, 1) : null,
            'by_manager' => $query->join('users', 'manager_call_logs.manager_id', '=', 'users.id')
                ->groupBy('manager_id', 'users.first_name', 'users.last_name')
                ->selectRaw('manager_id, users.first_name, users.last_name, count(*) as call_count, 
                           sum(case when call_status = "successful" then 1 else 0 end) as successful_count,
                           sum(case when call_outcome = "visit_approved" then 1 else 0 end) as approval_count')
                ->get()
                ->map(function($item) {
                    return [
                        'manager_id' => $item->manager_id,
                        'manager_name' => $item->first_name . ' ' . $item->last_name,
                        'call_count' => $item->call_count,
                        'successful_count' => $item->successful_count,
                        'approval_count' => $item->approval_count,
                        'success_rate' => $item->call_count > 0 ? round(($item->successful_count / $item->call_count) * 100, 1) : 0,
                        'approval_rate' => $item->successful_count > 0 ? round(($item->approval_count / $item->successful_count) * 100, 1) : 0
                    ];
                })
        ];
    }
}