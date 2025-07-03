<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManagerMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'subject',
        'message',
        'from_user_id',
        'to_manager_id',
        'booking_id',
        'priority',
        'is_read',
        'read_at',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeForManager($query, $managerId = null)
    {
        return $query->where(function($q) use ($managerId) {
            $q->whereNull('to_manager_id')
              ->orWhere('to_manager_id', $managerId);
        });
    }

    // Helper methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    public function getPriorityColorAttribute()
    {
        switch($this->priority) {
            case 'urgent':
                return 'red';
            case 'high':
                return 'orange';
            case 'normal':
                return 'blue';
            case 'low':
                return 'gray';
            default:
                return 'blue';
        }
    }

    public function getTypeIconAttribute()
    {
        switch($this->type) {
            case 'system':
                return '⚙️';
            case 'customer_inquiry':
                return '💬';
            case 'booking_update':
                return '📅';
            case 'payment_notification':
                return '💰';
            case 'visit_request':
                return '🏨';
            default:
                return '📧';
        }
    }

    public function getTypeColorAttribute()
    {
        switch($this->type) {
            case 'system':
                return 'blue';
            case 'customer_inquiry':
                return 'green';
            case 'booking_update':
                return 'purple';
            case 'payment_notification':
                return 'yellow';
            case 'visit_request':
                return 'indigo';
            default:
                return 'gray';
        }
    }

    // Static methods for creating messages
    public static function createSystemMessage($subject, $message, $priority = 'normal', $metadata = [])
    {
        return self::create([
            'type' => 'system',
            'subject' => $subject,
            'message' => $message,
            'priority' => $priority,
            'metadata' => $metadata
        ]);
    }

    public static function createCustomerInquiry($subject, $message, $fromUserId, $bookingId = null, $metadata = [])
    {
        return self::create([
            'type' => 'customer_inquiry',
            'subject' => $subject,
            'message' => $message,
            'from_user_id' => $fromUserId,
            'booking_id' => $bookingId,
            'metadata' => $metadata
        ]);
    }

    public static function createBookingUpdate($subject, $message, $bookingId, $priority = 'normal', $metadata = [])
    {
        return self::create([
            'type' => 'booking_update',
            'subject' => $subject,
            'message' => $message,
            'booking_id' => $bookingId,
            'priority' => $priority,
            'metadata' => $metadata
        ]);
    }

    public static function createPaymentNotification($subject, $message, $bookingId, $priority = 'high', $metadata = [])
    {
        return self::create([
            'type' => 'payment_notification',
            'subject' => $subject,
            'message' => $message,
            'booking_id' => $bookingId,
            'priority' => $priority,
            'metadata' => $metadata
        ]);
    }

    public static function createVisitRequest($subject, $message, $bookingId, $fromUserId = null, $metadata = [])
    {
        return self::create([
            'type' => 'visit_request',
            'subject' => $subject,
            'message' => $message,
            'booking_id' => $bookingId,
            'from_user_id' => $fromUserId,
            'priority' => 'high',
            'metadata' => $metadata
        ]);
    }
}