<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ManagerNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'manager_id',
        'notification_type',
        'title',
        'message',
        'notification_data',
        'priority',
        'is_read',
        'read_at',
        'is_actionable',
        'action_url',
        'expires_at'
    ];

    protected $casts = [
        'notification_data' => 'array',
        'is_read' => 'boolean',
        'is_actionable' => 'boolean',
        'read_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    /**
     * Get the manager that owns the notification
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope for actionable notifications
     */
    public function scopeActionable($query)
    {
        return $query->where('is_actionable', true);
    }

    /**
     * Scope for notifications by priority
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope for urgent notifications
     */
    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent');
    }

    /**
     * Scope for high priority notifications
     */
    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'urgent']);
    }

    /**
     * Scope for notifications by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('notification_type', $type);
    }

    /**
     * Scope for non-expired notifications
     */
    public function scopeActive($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope for expired notifications
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null
        ]);
    }

    /**
     * Check if notification is expired
     */
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if notification is urgent
     */
    public function isUrgent()
    {
        return $this->priority === 'urgent';
    }

    /**
     * Check if notification is high priority
     */
    public function isHighPriority()
    {
        return in_array($this->priority, ['high', 'urgent']);
    }

    /**
     * Get time since notification was created
     */
    public function getTimeSinceCreatedAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get priority color for UI
     */
    public function getPriorityColorAttribute()
    {
        switch($this->priority) {
            case 'urgent':
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
     * Get priority icon for UI
     */
    public function getPriorityIconAttribute()
    {
        switch($this->priority) {
            case 'urgent':
                return 'ri-alarm-warning-line';
            case 'high':
                return 'ri-error-warning-line';
            case 'medium':
                return 'ri-information-line';
            case 'low':
                return 'ri-chat-3-line';
            default:
                return 'ri-notification-line';
        }
    }

    /**
     * Get notification type icon
     */
    public function getTypeIconAttribute()
    {
        switch($this->notification_type) {
            case 'visit_request':
                return 'ri-calendar-check-line';
            case 'payment_pending':
                return 'ri-money-dollar-circle-line';
            case 'booking_update':
                return 'ri-edit-line';
            case 'package_created':
                return 'ri-gift-line';
            case 'package_updated':
                return 'ri-edit-box-line';
            case 'system':
                return 'ri-settings-line';
            case 'customer_inquiry':
                return 'ri-question-line';
            default:
                return 'ri-notification-line';
        }
    }

    /**
     * Get related booking if notification data contains booking_id
     */
    public function getRelatedBookingAttribute()
    {
        if (isset($this->notification_data['booking_id'])) {
            return Booking::find($this->notification_data['booking_id']);
        }
        return null;
    }

    /**
     * Get related package if notification data contains package_id
     */
    public function getRelatedPackageAttribute()
    {
        if (isset($this->notification_data['package_id'])) {
            return Package::find($this->notification_data['package_id']);
        }
        return null;
    }

    /**
     * Get related hall if notification data contains hall_id
     */
    public function getRelatedHallAttribute()
    {
        if (isset($this->notification_data['hall_id'])) {
            return Hall::find($this->notification_data['hall_id']);
        }
        return null;
    }

    /**
     * Create a visit request notification
     */
    public static function createVisitRequestNotification($managerId, Booking $booking)
    {
        return static::create([
            'manager_id' => $managerId,
            'notification_type' => 'visit_request',
            'title' => 'New Visit Request',
            'message' => "Customer {$booking->contact_name} has requested a venue visit for {$booking->hall_name}",
            'notification_data' => [
                'booking_id' => $booking->id,
                'customer_name' => $booking->contact_name,
                'customer_phone' => $booking->contact_phone,
                'hall_name' => $booking->hall_name,
                'visit_date' => $booking->visit_date,
                'visit_time' => $booking->visit_time
            ],
            'priority' => 'high',
            'is_actionable' => true,
            'action_url' => route('manager.visits.show', $booking->id),
            'expires_at' => now()->addDays(3)
        ]);
    }

    /**
     * Create a payment pending notification
     */
    public static function createPaymentPendingNotification($managerId, Booking $booking)
    {
        return static::create([
            'manager_id' => $managerId,
            'notification_type' => 'payment_pending',
            'title' => 'Payment Confirmation Required',
            'message' => "Advance payment of Rs. " . number_format($booking->advance_payment_amount) . " needs confirmation for {$booking->contact_name}",
            'notification_data' => [
                'booking_id' => $booking->id,
                'customer_name' => $booking->contact_name,
                'amount' => $booking->advance_payment_amount,
                'hall_name' => $booking->hall_name
            ],
            'priority' => 'medium',
            'is_actionable' => true,
            'action_url' => route('manager.payments.confirm', $booking->id),
            'expires_at' => now()->addDays(7)
        ]);
    }

    /**
     * Create a package update notification
     */
    public static function createPackageUpdateNotification($managerId, Package $package, array $changes)
    {
        return static::create([
            'manager_id' => $managerId,
            'notification_type' => 'package_updated',
            'title' => 'Package Updated',
            'message' => "Package '{$package->name}' has been updated by admin",
            'notification_data' => [
                'package_id' => $package->id,
                'package_name' => $package->name,
                'changes' => $changes
            ],
            'priority' => 'low',
            'is_actionable' => false,
            'expires_at' => now()->addDays(7)
        ]);
    }

    /**
     * Bulk mark notifications as read for a manager
     */
    public static function markAllAsReadForManager($managerId)
    {
        return static::where('manager_id', $managerId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
    }

    /**
     * Clean up expired notifications
     */
    public static function cleanupExpired()
    {
        return static::where('expires_at', '<=', now()->subDays(30))->delete();
    }

    /**
     * Get notification statistics for a manager
     */
    public static function getStatsForManager($managerId)
    {
        $base = static::where('manager_id', $managerId);
        
        return [
            'total' => $base->count(),
            'unread' => $base->where('is_read', false)->count(),
            'urgent' => $base->where('priority', 'urgent')->where('is_read', false)->count(),
            'actionable' => $base->where('is_actionable', true)->where('is_read', false)->count(),
            'by_type' => $base->groupBy('notification_type')->selectRaw('notification_type, count(*) as count')->pluck('count', 'notification_type')->toArray()
        ];
    }
}