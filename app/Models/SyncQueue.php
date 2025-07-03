<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyncQueue extends Model
{
    use HasFactory;

    protected $table = 'sync_queue';

    protected $fillable = [
        'sync_type',
        'sync_data',
        'priority',
        'status',
        'retry_count',
        'max_retries',
        'scheduled_at',
        'processed_at',
        'error_message'
    ];

    protected $casts = [
        'sync_data' => 'array',
        'retry_count' => 'integer',
        'max_retries' => 'integer',
        'scheduled_at' => 'datetime',
        'processed_at' => 'datetime'
    ];

    /**
     * Scope for pending sync items
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for processing sync items
     */
    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    /**
     * Scope for completed sync items
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for failed sync items
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for items ready to process
     */
    public function scopeReadyToProcess($query)
    {
        return $query->where('status', 'pending')
            ->where(function($q) {
                $q->whereNull('scheduled_at')
                  ->orWhere('scheduled_at', '<=', now());
            });
    }

    /**
     * Scope for items that can be retried
     */
    public function scopeRetryable($query)
    {
        return $query->where('status', 'failed')
            ->whereColumn('retry_count', '<', 'max_retries');
    }

    /**
     * Scope by priority
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope for high priority items
     */
    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'critical']);
    }

    /**
     * Scope for critical priority items
     */
    public function scopeCritical($query)
    {
        return $query->where('priority', 'critical');
    }

    /**
     * Mark as processing
     */
    public function markAsProcessing()
    {
        $this->update([
            'status' => 'processing',
            'processed_at' => now()
        ]);
    }

    /**
     * Mark as completed
     */
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'processed_at' => now()
        ]);
    }

    /**
     * Mark as failed
     */
    public function markAsFailed($errorMessage = null)
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
            'processed_at' => now()
        ]);
    }

    /**
     * Increment retry count
     */
    public function incrementRetry()
    {
        $this->increment('retry_count');
        
        if ($this->retry_count >= $this->max_retries) {
            $this->markAsFailed('Maximum retry attempts exceeded');
        } else {
            $this->update(['status' => 'pending']);
        }
    }

    /**
     * Check if item can be retried
     */
    public function canRetry()
    {
        return $this->status === 'failed' && $this->retry_count < $this->max_retries;
    }

    /**
     * Check if item is overdue
     */
    public function isOverdue($minutes = 30)
    {
        return $this->status === 'processing' && 
               $this->processed_at && 
               $this->processed_at->addMinutes($minutes)->isPast();
    }

    /**
     * Get priority weight for sorting
     */
    public function getPriorityWeightAttribute()
    {
        switch($this->priority) {
            case 'critical':
                return 4;
            case 'high':
                return 3;
            case 'medium':
                return 2;
            case 'low':
                return 1;
            default:
                return 0;
        }
    }

    /**
     * Get priority color for UI
     */
    public function getPriorityColorAttribute()
    {
        switch($this->priority) {
            case 'critical':
                return 'danger';
            case 'high':
                return 'warning';
            case 'medium':
                return 'primary';
            case 'low':
                return 'secondary';
            default:
                return 'secondary';
        }
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute()
    {
        switch($this->status) {
            case 'completed':
                return 'success';
            case 'processing':
                return 'info';
            case 'failed':
                return 'danger';
            case 'pending':
                return 'warning';
            default:
                return 'secondary';
        }
    }

    /**
     * Get time since scheduled
     */
    public function getTimeSinceScheduledAttribute()
    {
        return $this->scheduled_at ? $this->scheduled_at->diffForHumans() : null;
    }

    /**
     * Get processing duration
     */
    public function getProcessingDurationAttribute()
    {
        if ($this->processed_at && $this->created_at) {
            return $this->created_at->diffInSeconds($this->processed_at);
        }
        return null;
    }

    /**
     * Create a new sync queue item
     */
    public static function queue($syncType, $data, $priority = 'medium', $scheduledAt = null, $maxRetries = 3)
    {
        return static::create([
            'sync_type' => $syncType,
            'sync_data' => $data,
            'priority' => $priority,
            'scheduled_at' => $scheduledAt,
            'max_retries' => $maxRetries
        ]);
    }

    /**
     * Get next items to process
     */
    public static function getNextToProcess($limit = 10)
    {
        return static::readyToProcess()
            ->orderByRaw("
                CASE priority 
                    WHEN 'critical' THEN 4 
                    WHEN 'high' THEN 3 
                    WHEN 'medium' THEN 2 
                    WHEN 'low' THEN 1 
                    ELSE 0 
                END DESC
            ")
            ->orderBy('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Clean up old completed items
     */
    public static function cleanup($daysToKeep = 7)
    {
        return static::where('status', 'completed')
            ->where('processed_at', '<', now()->subDays($daysToKeep))
            ->delete();
    }

    /**
     * Reset stuck processing items
     */
    public static function resetStuckItems($minutes = 30)
    {
        return static::where('status', 'processing')
            ->where('processed_at', '<', now()->subMinutes($minutes))
            ->update([
                'status' => 'pending',
                'processed_at' => null
            ]);
    }

    /**
     * Get queue statistics
     */
    public static function getStatistics()
    {
        return [
            'total' => static::count(),
            'pending' => static::pending()->count(),
            'processing' => static::processing()->count(),
            'completed' => static::completed()->count(),
            'failed' => static::failed()->count(),
            'retryable' => static::retryable()->count(),
            'by_priority' => static::groupBy('priority')
                ->selectRaw('priority, count(*) as count')
                ->pluck('count', 'priority'),
            'by_type' => static::groupBy('sync_type')
                ->selectRaw('sync_type, count(*) as count')
                ->pluck('count', 'sync_type'),
            'avg_processing_time' => static::completed()
                ->whereNotNull('processed_at')
                ->selectRaw('AVG(TIMESTAMPDIFF(SECOND, created_at, processed_at)) as avg_time')
                ->value('avg_time')
        ];
    }
}