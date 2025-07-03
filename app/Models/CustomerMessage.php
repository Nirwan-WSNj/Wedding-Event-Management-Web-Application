<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Mail;

class CustomerMessage extends Model
{
    protected $fillable = [
        'user_id',
        'booking_id',
        'subject',
        'message',
        'type',
        'priority',
        'status',
        'is_read',
        'replied_at',
        'metadata',
        'customer_email',
        'customer_name',
        'customer_phone'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_read' => 'boolean',
        'replied_at' => 'datetime'
    ];

    // Message types
    const TYPE_INQUIRY = 'inquiry';
    const TYPE_COMPLAINT = 'complaint';
    const TYPE_FEEDBACK = 'feedback';
    const TYPE_BOOKING_RELATED = 'booking_related';
    const TYPE_GENERAL = 'general';

    // Priority levels
    const PRIORITY_LOW = 'low';
    const PRIORITY_NORMAL = 'normal';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    // Status levels
    const STATUS_NEW = 'new';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_REPLIED = 'replied';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_CLOSED = 'closed';

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(MessageReply::class, 'message_id');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Methods
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    public function markAsReplied()
    {
        $this->update([
            'status' => self::STATUS_REPLIED,
            'replied_at' => now()
        ]);
    }

    public function addReply($content, $isFromManager = true, $managerName = null)
    {
        $reply = $this->replies()->create([
            'content' => $content,
            'is_from_manager' => $isFromManager,
            'manager_name' => $managerName,
            'sent_at' => now()
        ]);

        // Update message status
        if ($isFromManager) {
            $this->markAsReplied();
        }

        // Send email notification to customer
        if ($isFromManager && $this->customer_email) {
            $this->sendEmailReply($content, $managerName);
        }

        return $reply;
    }

    private function sendEmailReply($content, $managerName)
    {
        try {
            Mail::send('emails.customer-reply', [
                'customerName' => $this->customer_name,
                'originalSubject' => $this->subject,
                'replyContent' => $content,
                'managerName' => $managerName ?: 'Wet Water Resort Team',
                'messageId' => $this->id
            ], function ($message) {
                $message->to($this->customer_email, $this->customer_name)
                        ->subject('Re: ' . $this->subject)
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send email reply', [
                'message_id' => $this->id,
                'customer_email' => $this->customer_email,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getPriorityColorAttribute()
    {
        switch($this->priority) {
            default:
                return 'gray';
        }
    }

    public function getStatusColorAttribute()
    {
        switch($this->status) {
            default:
                return 'gray';
        }
    }

    public function getFormattedTypeAttribute()
    {
        switch($this->type) {
            default:
                return 'General';
        }
    }

    public static function createFromContactForm($data, $userId = null)
    {
        return self::create([
            'user_id' => $userId,
            'subject' => self::generateSubject($data),
            'message' => self::formatContactMessage($data),
            'type' => self::determineType($data),
            'priority' => self::determinePriority($data),
            'status' => self::STATUS_NEW,
            'customer_email' => $data['email'],
            'customer_name' => $data['firstName'] . ' ' . $data['lastName'],
            'customer_phone' => $data['phone'],
            'metadata' => [
                'source' => 'contact_form',
                'event_type' => $data['eventType'],
                'preferred_date' => $data['date'] ?? null,
                'guest_count' => $data['guestCount'] ?? null,
                'submitted_at' => now()->toISOString()
            ]
        ]);
    }

    private static function generateSubject($data)
    {
        $eventTypes = [
            'wedding' => 'Wedding',
            'engagement' => 'Engagement Party',
            'rehearsal' => 'Rehearsal Dinner',
            'reception' => 'Reception',
            'other' => 'Event'
        ];

        $eventType = $eventTypes[$data['eventType']] ?? 'Event';
        $customerName = $data['firstName'] . ' ' . $data['lastName'];
        
        if (isset($data['date'])) {
            $eventDate = new \DateTime($data['date']);
            return "New {$eventType} Inquiry - {$customerName} ({$eventDate->format('M Y')})";
        }

        return "New {$eventType} Inquiry - {$customerName}";
    }

    private static function formatContactMessage($data)
    {
        $eventTypes = [
            'wedding' => 'Wedding',
            'engagement' => 'Engagement Party',
            'rehearsal' => 'Rehearsal Dinner',
            'reception' => 'Reception Only',
            'other' => 'Other Event'
        ];

        $content = "CUSTOMER DETAILS:\n";
        $content .= "Name: {$data['firstName']} {$data['lastName']}\n";
        $content .= "Email: {$data['email']}\n";
        $content .= "Phone: {$data['phone']}\n\n";
        
        $content .= "EVENT DETAILS:\n";
        $content .= "Event Type: " . ($eventTypes[$data['eventType']] ?? 'Other') . "\n";
        
        if (isset($data['date'])) {
            $eventDate = new \DateTime($data['date']);
            $content .= "Preferred Date: {$eventDate->format('F j, Y')} ({$eventDate->format('l')})\n";
        }
        
        if (isset($data['guestCount'])) {
            $content .= "Estimated Guest Count: {$data['guestCount']} guests\n";
        }
        
        $content .= "\nCUSTOMER MESSAGE:\n";
        $content .= $data['message'] . "\n\n";
        
        $content .= "Form submitted: " . now()->format('F j, Y \a\t g:i A');
        
        return $content;
    }

    private static function determineType($data)
    {
        if (in_array($data['eventType'], ['wedding', 'engagement', 'rehearsal', 'reception'])) {
            return self::TYPE_INQUIRY;
        }
        return self::TYPE_GENERAL;
    }

    private static function determinePriority($data)
    {
        // Check if the event date is soon
        if (isset($data['date'])) {
            $eventDate = new \DateTime($data['date']);
            $now = new \DateTime();
            $monthsDiff = $now->diff($eventDate)->m + ($now->diff($eventDate)->y * 12);
            
            if ($monthsDiff <= 1) {
                return self::PRIORITY_URGENT;
            } elseif ($monthsDiff <= 3) {
                return self::PRIORITY_HIGH;
            }
        }

        // Large events get higher priority
        if (isset($data['guestCount']) && $data['guestCount'] >= 200) {
            return self::PRIORITY_HIGH;
        }

        return self::PRIORITY_NORMAL;
    }
}