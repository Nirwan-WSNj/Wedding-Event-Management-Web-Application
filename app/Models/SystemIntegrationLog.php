<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SystemIntegrationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'integration_type',
        'source_component',
        'target_component',
        'integration_data',
        'status',
        'error_message',
        'processing_time_ms',
        'triggered_by'
    ];

    protected $casts = [
        'integration_data' => 'array',
        'processing_time_ms' => 'integer'
    ];

    /**
     * Get the user who triggered the integration
     */
    public function triggeredBy()
    {
        return $this->belongsTo(User::class, 'triggered_by');
    }

    /**
     * Scope for successful integrations
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope for failed integrations
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for partial integrations
     */
    public function scopePartial($query)
    {
        return $query->where('status', 'partial');
    }

    /**
     * Scope for integrations by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('integration_type', $type);
    }

    /**
     * Scope for integrations by source component
     */
    public function scopeBySource($query, $source)
    {
        return $query->where('source_component', $source);
    }

    /**
     * Scope for integrations by target component
     */
    public function scopeByTarget($query, $target)
    {
        return $query->where('target_component', $target);
    }

    /**
     * Scope for recent integrations
     */
    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    /**
     * Scope for slow integrations
     */
    public function scopeSlow($query, $thresholdMs = 1000)
    {
        return $query->where('processing_time_ms', '>', $thresholdMs);
    }

    /**
     * Get processing time in seconds
     */
    public function getProcessingTimeSecondsAttribute()
    {
        return $this->processing_time_ms ? round($this->processing_time_ms / 1000, 3) : null;
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute()
    {
        switch($this->status) {
            case 'success':
                return 'success';
            case 'failed':
                return 'danger';
            case 'partial':
                return 'warning';
            default:
                return 'secondary';
        }
    }

    /**
     * Get status icon for UI
     */
    public function getStatusIconAttribute()
    {
        switch($this->status) {
            case 'success':
                return 'ri-check-line';
            case 'failed':
                return 'ri-close-line';
            case 'partial':
                return 'ri-error-warning-line';
            default:
                return 'ri-question-line';
        }
    }

    /**
     * Get integration type icon
     */
    public function getTypeIconAttribute()
    {
        switch($this->integration_type) {
            case 'package_created':
                return 'ri-gift-line';
            case 'package_updated':
                return 'ri-edit-box-line';
            case 'package_deleted':
                return 'ri-delete-bin-line';
            case 'booking_progression':
                return 'ri-arrow-right-line';
            case 'visit_approval':
                return 'ri-calendar-check-line';
            case 'payment_confirmation':
                return 'ri-money-dollar-circle-line';
            case 'hall_update':
                return 'ri-building-line';
            default:
                return 'ri-link-line';
        }
    }

    /**
     * Check if integration was successful
     */
    public function isSuccessful()
    {
        return $this->status === 'success';
    }

    /**
     * Check if integration failed
     */
    public function isFailed()
    {
        return $this->status === 'failed';
    }

    /**
     * Check if integration was slow
     */
    public function isSlow($thresholdMs = 1000)
    {
        return $this->processing_time_ms && $this->processing_time_ms > $thresholdMs;
    }

    /**
     * Get time since integration
     */
    public function getTimeSinceAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get related booking if integration data contains booking_id
     */
    public function getRelatedBookingAttribute()
    {
        if (isset($this->integration_data['booking_id'])) {
            return Booking::find($this->integration_data['booking_id']);
        }
        return null;
    }

    /**
     * Get related package if integration data contains package_id
     */
    public function getRelatedPackageAttribute()
    {
        if (isset($this->integration_data['package_id'])) {
            return Package::find($this->integration_data['package_id']);
        }
        return null;
    }

    /**
     * Get integration statistics
     */
    public static function getStatistics($period = '24h')
    {
        $query = static::query();
        
        // Apply time filter
        switch ($period) {
            case '1h':
                $query->where('created_at', '>=', now()->subHour());
                break;
            case '24h':
                $query->where('created_at', '>=', now()->subDay());
                break;
            case '7d':
                $query->where('created_at', '>=', now()->subWeek());
                break;
            case '30d':
                $query->where('created_at', '>=', now()->subMonth());
                break;
        }

        $total = $query->count();
        $successful = $query->where('status', 'success')->count();
        $failed = $query->where('status', 'failed')->count();
        $partial = $query->where('status', 'partial')->count();
        
        $avgProcessingTime = $query->whereNotNull('processing_time_ms')->avg('processing_time_ms');
        $slowIntegrations = $query->where('processing_time_ms', '>', 1000)->count();

        return [
            'total' => $total,
            'successful' => $successful,
            'failed' => $failed,
            'partial' => $partial,
            'success_rate' => $total > 0 ? round(($successful / $total) * 100, 2) : 0,
            'avg_processing_time_ms' => $avgProcessingTime ? round($avgProcessingTime, 2) : null,
            'slow_integrations' => $slowIntegrations,
            'by_type' => $query->groupBy('integration_type')
                ->selectRaw('integration_type, count(*) as count, avg(processing_time_ms) as avg_time')
                ->get()
                ->keyBy('integration_type'),
            'by_component' => [
                'sources' => $query->groupBy('source_component')
                    ->selectRaw('source_component, count(*) as count')
                    ->pluck('count', 'source_component'),
                'targets' => $query->groupBy('target_component')
                    ->selectRaw('target_component, count(*) as count')
                    ->pluck('count', 'target_component')
            ]
        ];
    }

    /**
     * Get recent failed integrations for monitoring
     */
    public static function getRecentFailures($limit = 10)
    {
        return static::failed()
            ->with('triggeredBy')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get performance metrics
     */
    public static function getPerformanceMetrics($period = '24h')
    {
        $query = static::whereNotNull('processing_time_ms');
        
        // Apply time filter
        switch ($period) {
            case '1h':
                $query->where('created_at', '>=', now()->subHour());
                break;
            case '24h':
                $query->where('created_at', '>=', now()->subDay());
                break;
            case '7d':
                $query->where('created_at', '>=', now()->subWeek());
                break;
        }

        return [
            'avg_processing_time' => $query->avg('processing_time_ms'),
            'min_processing_time' => $query->min('processing_time_ms'),
            'max_processing_time' => $query->max('processing_time_ms'),
            'slow_integrations_count' => $query->where('processing_time_ms', '>', 1000)->count(),
            'very_slow_integrations_count' => $query->where('processing_time_ms', '>', 5000)->count(),
            'by_type' => $query->groupBy('integration_type')
                ->selectRaw('integration_type, avg(processing_time_ms) as avg_time, max(processing_time_ms) as max_time')
                ->get()
                ->keyBy('integration_type')
        ];
    }

    /**
     * Clean up old logs
     */
    public static function cleanup($daysToKeep = 90)
    {
        return static::where('created_at', '<', now()->subDays($daysToKeep))->delete();
    }

    /**
     * Log a successful integration
     */
    public static function logSuccess($type, $source, $target, $data, $processingTime = null, $triggeredBy = null)
    {
        return static::create([
            'integration_type' => $type,
            'source_component' => $source,
            'target_component' => $target,
            'integration_data' => $data,
            'status' => 'success',
            'processing_time_ms' => $processingTime,
            'triggered_by' => $triggeredBy ?? auth()->id()
        ]);
    }

    /**
     * Log a failed integration
     */
    public static function logFailure($type, $source, $target, $data, $errorMessage, $processingTime = null, $triggeredBy = null)
    {
        return static::create([
            'integration_type' => $type,
            'source_component' => $source,
            'target_component' => $target,
            'integration_data' => $data,
            'status' => 'failed',
            'error_message' => $errorMessage,
            'processing_time_ms' => $processingTime,
            'triggered_by' => $triggeredBy ?? auth()->id()
        ]);
    }
}