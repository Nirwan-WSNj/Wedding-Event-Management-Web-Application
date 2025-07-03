<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageReply extends Model
{
    protected $fillable = [
        'message_id',
        'content',
        'is_from_manager',
        'manager_name',
        'sent_at',
        'email_sent',
        'email_sent_at'
    ];

    protected $casts = [
        'is_from_manager' => 'boolean',
        'sent_at' => 'datetime',
        'email_sent' => 'boolean',
        'email_sent_at' => 'datetime'
    ];

    // Relationships
    public function message(): BelongsTo
    {
        return $this->belongsTo(CustomerMessage::class, 'message_id');
    }

    // Methods
    public function markEmailSent()
    {
        $this->update([
            'email_sent' => true,
            'email_sent_at' => now()
        ]);
    }
}