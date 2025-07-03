<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

/**
 * App\Models\Booking
 */
class Booking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'hall_id', 'package_id', 'wedding_type_id', 'status', 'event_date',
        'start_time', 'end_time', 'hall_booking_date', 'package_price', 'guest_count',
        'wedding_type_time_slot', 'catholic_day1_date', 'catholic_day2_date', 'contact_name',
        'contact_email', 'contact_phone', 'visit_date', 'visit_time', 'visit_purpose', 
        'visit_purpose_other', 'special_requests', 'wedding_groom_name', 'wedding_bride_name', 
        'wedding_groom_email', 'wedding_bride_email', 'wedding_groom_phone', 'wedding_bride_phone', 
        'wedding_date', 'wedding_alternative_date1', 'wedding_alternative_date2',
        'wedding_ceremony_time', 'wedding_reception_time', 'wedding_additional_notes', 
        'terms_agreed', 'privacy_agreed', 'total_amount', 'cancelled_at', 'cancellation_reason', 
        'hall_name', 'customization_guest_count', 'customization_wedding_type',
        'customization_decorations_additional', 'customization_catering_custom',
        'customization_additional_services_selected', 'visit_submitted', 'visit_confirmed',
        'visit_confirmed_at', 'visit_confirmed_by', 'visit_confirmation_notes',
        'advance_payment_required', 'advance_payment_amount', 'advance_payment_paid',
        'advance_payment_paid_at', 'advance_payment_method', 'advance_payment_notes',
        'step5_unlocked', 'workflow_step', 'workflow_notes', 'preferred_contact_method',
        'best_call_time', 'assigned_manager_id', 'assigned_at', 'manager_call_required',
        'manager_call_completed', 'manager_call_completed_at', 'manager_call_completed_by',
        'visit_confirmation_email_sent', 'visit_confirmation_email_sent_at',
        'payment_confirmation_email_sent', 'payment_confirmation_email_sent_at',
        'bill_email_sent', 'bill_email_sent_at', 'visit_confirmation_method',
        'customization_catering_selected_menu_id', 'selected_menu_id',
        'visit_rejected', 'visit_rejected_at', 'visit_rejected_by', 'visit_rejection_reason',
        'visit_call_attempts', 'last_call_attempt_at', 'last_call_status', 'last_call_notes',
        'callback_scheduled', 'callback_date', 'callback_time', 'callback_notes',
        'callback_scheduled_by', 'callback_scheduled_at'
    ];

    protected $casts = [
        'event_date' => 'date',
        'hall_booking_date' => 'date',
        'visit_date' => 'date',
        'wedding_date' => 'date',
        'wedding_alternative_date1' => 'date',
        'wedding_alternative_date2' => 'date',
        'catholic_day1_date' => 'date',
        'catholic_day2_date' => 'date',
        'package_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'advance_payment_amount' => 'decimal:2',
        'customization_decorations_additional' => 'array',
        'customization_catering_custom' => 'array',
        'customization_additional_services_selected' => 'array',
        'terms_agreed' => 'boolean',
        'privacy_agreed' => 'boolean',
        'visit_submitted' => 'boolean',
        'visit_confirmed' => 'boolean',
        'advance_payment_required' => 'boolean',
        'advance_payment_paid' => 'boolean',
        'step5_unlocked' => 'boolean',
        'manager_call_required' => 'boolean',
        'manager_call_completed' => 'boolean',
        'visit_confirmation_email_sent' => 'boolean',
        'payment_confirmation_email_sent' => 'boolean',
        'bill_email_sent' => 'boolean',
        'visit_confirmed_at' => 'datetime',
        'advance_payment_paid_at' => 'datetime',
        'manager_call_completed_at' => 'datetime',
        'visit_confirmation_email_sent_at' => 'datetime',
        'payment_confirmation_email_sent_at' => 'datetime',
        'bill_email_sent_at' => 'datetime',
        'assigned_at' => 'datetime',
        'deleted_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'visit_rejected' => 'boolean',
        'visit_rejected_at' => 'datetime',
        'last_call_attempt_at' => 'datetime',
        'callback_scheduled' => 'boolean',
        'callback_date' => 'date',
        'callback_scheduled_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';

    // Workflow step constants
    const WORKFLOW_DRAFT = 'draft';
    const WORKFLOW_VISIT_SUBMITTED = 'visit_submitted';
    const WORKFLOW_CALL_PENDING = 'call_pending';
    const WORKFLOW_CALL_COMPLETED = 'call_completed';
    const WORKFLOW_VISIT_CONFIRMED = 'visit_confirmed';
    const WORKFLOW_PAYMENT_PENDING = 'payment_pending';
    const WORKFLOW_PAYMENT_CONFIRMED = 'payment_confirmed';
    const WORKFLOW_DETAILS_COMPLETED = 'details_completed';
    const WORKFLOW_BOOKING_FINALIZED = 'booking_finalized';

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hall(): BelongsTo
    {
        return $this->belongsTo(Hall::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function weddingType(): BelongsTo
    {
        return $this->belongsTo(WeddingType::class);
    }

    public function visitConfirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'visit_confirmed_by');
    }

    public function assignedManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_manager_id');
    }

    public function managerCallCompletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_call_completed_by');
    }

    public function visitRejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'visit_rejected_by');
    }

    public function callbackScheduledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'callback_scheduled_by');
    }

    public function bookingCatering(): HasMany
    {
        return $this->hasMany(BookingCatering::class);
    }

    public function bookingDecorations(): HasMany
    {
        return $this->hasMany(BookingDecoration::class);
    }

    public function bookingAdditionalServices(): HasMany
    {
        return $this->hasMany(BookingAdditionalService::class);
    }

    public function bookingCateringItems(): HasMany
    {
        return $this->hasMany(BookingCateringItem::class);
    }

    public function bookingPayments(): HasMany
    {
        return $this->hasMany(BookingPayment::class);
    }

    // Alias methods for backward compatibility
    public function catering(): HasMany
    {
        return $this->bookingCatering();
    }

    public function decorations(): HasMany
    {
        return $this->bookingDecorations();
    }

    public function services(): HasMany
    {
        return $this->bookingAdditionalServices();
    }

    public function cateringItems(): HasMany
    {
        return $this->bookingCateringItems();
    }

    public function payments(): HasMany
    {
        return $this->bookingPayments();
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', Carbon::today())
                    ->where('status', self::STATUS_CONFIRMED);
    }

    // Status check methods
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isUpcoming(): bool
    {
        return $this->event_date >= Carbon::today() && 
               $this->status === self::STATUS_CONFIRMED;
    }

    // Business logic methods
    public function updateStatus(string $status): bool
    {
        if (!in_array($status, [
            self::STATUS_PENDING,
            self::STATUS_CONFIRMED,
            self::STATUS_CANCELLED,
            self::STATUS_COMPLETED
        ])) {
            return false;
        }

        $this->status = $status;
        return $this->save();
    }

    public function confirm(): bool
    {
        return $this->updateStatus(self::STATUS_CONFIRMED);
    }

    public function cancel(): bool
    {
        return $this->updateStatus(self::STATUS_CANCELLED);
    }

    public function complete(): bool
    {
        return $this->updateStatus(self::STATUS_COMPLETED);
    }

    /**
     * Submit visit request (Step 4) - UPDATED WITH WORKFLOW
     */
    public function submitVisitRequest(): bool
    {
        try {
            // Use update method instead of individual attribute assignment
            return $this->update([
                'visit_submitted' => true,
                'manager_call_required' => true,
                'workflow_step' => 'call_pending',
                'workflow_notes' => 'Visit request submitted by customer. Manager call required for confirmation.',
                'updated_at' => now()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in submitVisitRequest', [
                'booking_id' => $this->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Confirm visit by manager after successful phone call - CRITICAL WORKFLOW STEP
     * This method enforces the proper workflow sequence and sends email notifications
     */
    public function confirmVisitAfterCall(User $manager, array $callData = []): bool
    {
        try {
            // Validate that visit was submitted first
            if (!$this->visit_submitted) {
                throw new \Exception('Visit must be submitted before it can be confirmed.');
            }

            // Validate that only managers can confirm visits
            if ($manager->role !== 'manager') {
                throw new \Exception('Only managers can confirm visit requests.');
            }

            // Validate that manager call is required and not yet completed
            if (!$this->manager_call_required) {
                throw new \Exception('Manager call is not required for this booking.');
            }

            if ($this->manager_call_completed && $this->visit_confirmed) {
                throw new \Exception('Visit has already been confirmed.');
            }

            // Calculate 20% advance payment requirement
            $totalAmount = $this->calculateTotalAmount();
            $advancePaymentAmount = round($totalAmount * 0.20, 2);

            // Use update method to avoid SQL formatting issues
            $updateData = [
                'manager_call_completed' => true,
                'manager_call_completed_at' => now(),
                'manager_call_completed_by' => $manager->id,
                'visit_confirmed' => true,
                'visit_confirmed_at' => now(),
                'visit_confirmed_by' => $manager->id,
                'visit_confirmation_notes' => $callData['notes'] ?? null,
                'visit_confirmation_method' => 'phone_call',
                'workflow_step' => 'payment_pending',
                'workflow_notes' => 'Visit confirmed by manager after successful phone call. Advance payment required.',
                'advance_payment_required' => true,
                'advance_payment_amount' => $advancePaymentAmount,
                'step5_unlocked' => false,
                'updated_at' => now()
            ];

            $saved = $this->update($updateData);
            
            if ($saved) {
                // Send visit confirmation email to customer
                $this->sendVisitConfirmationEmail($callData['notes'] ?? null);
            }
            
            return $saved;
            
        } catch (\Exception $e) {
            \Log::error('Error in confirmVisitAfterCall', [
                'booking_id' => $this->id,
                'manager_id' => $manager->id,
                'error' => $e->getMessage()
            ]);
            throw $e; // Re-throw to maintain the exception behavior
        }
    }

    /**
     * Legacy method for backward compatibility
     */
    public function confirmVisit(User $manager, string $notes = null): bool
    {
        return $this->confirmVisitAfterCall($manager, ['notes' => $notes]);
    }

    /**
     * Mark advance payment as paid - FINAL WORKFLOW STEP
     * This unlocks Step 5 access for the customer and sends notifications
     */
    public function markAdvancePaymentPaid(string $paymentMethod = null, string $notes = null, array $paymentDetails = []): bool
    {
        try {
            // Validate that visit was confirmed first
            if (!$this->visit_confirmed) {
                throw new \Exception('Visit must be confirmed before marking advance payment as paid.');
            }

            // Validate that advance payment is required
            if (!$this->advance_payment_required || $this->advance_payment_amount <= 0) {
                throw new \Exception('No advance payment is required for this booking.');
            }

            // Prevent double payment marking
            if ($this->advance_payment_paid) {
                throw new \Exception('Advance payment has already been marked as paid.');
            }

            // Use update method to avoid SQL formatting issues
            $updateData = [
                'advance_payment_paid' => true,
                'advance_payment_paid_at' => now(),
                'advance_payment_method' => $paymentMethod,
                'advance_payment_notes' => $notes,
                'workflow_step' => 'payment_confirmed',
                'workflow_notes' => 'Advance payment confirmed. Customer can now complete final wedding details (Step 5).',
                'step5_unlocked' => true,
                'updated_at' => now()
            ];

            $saved = $this->update($updateData);
            
            if ($saved) {
                // Send payment confirmation email to customer
                $this->sendPaymentConfirmationEmail($paymentDetails);
                
                // Send detailed bill email to customer
                $this->sendBillEmail();
            }
            
            return $saved;
            
        } catch (\Exception $e) {
            \Log::error('Error in markAdvancePaymentPaid', [
                'booking_id' => $this->id,
                'error' => $e->getMessage()
            ]);
            throw $e; // Re-throw to maintain the exception behavior
        }
    }

    /**
     * Check if Step 5 should be accessible
     */
    public function canAccessStep5(): bool
    {
        return $this->visit_confirmed && $this->advance_payment_paid && $this->step5_unlocked;
    }

    /**
     * Check if visit is submitted but not confirmed
     */
    public function isVisitPending(): bool
    {
        return $this->visit_submitted && !$this->visit_confirmed;
    }

    /**
     * Check if advance payment is required but not paid
     */
    public function isAdvancePaymentPending(): bool
    {
        return $this->advance_payment_required && !$this->advance_payment_paid;
    }

    /**
     * Get the remaining amount after advance payment
     */
    public function getRemainingAmount(): float
    {
        $total = $this->calculateTotalAmount();
        $advancePaid = $this->advance_payment_paid ? $this->advance_payment_amount : 0;
        return round($total - $advancePaid, 2);
    }

    /**
     * Get visit status for filtering
     */
    public function getVisitStatus(): string
    {
        if (!$this->visit_submitted) {
            return 'draft';
        } elseif ($this->visit_submitted && !$this->visit_confirmed) {
            return 'pending';
        } elseif ($this->visit_confirmed && !$this->advance_payment_paid) {
            return 'payment_pending';
        } elseif ($this->advance_payment_paid) {
            return 'completed';
        } else {
            return 'confirmed';
        }
    }

    /**
     * Send visit confirmation email to customer
     */
    public function sendVisitConfirmationEmail(string $managerNotes = null): bool
    {
        try {
            if ($this->visit_confirmation_email_sent) {
                return true; // Already sent
            }

            // Check if user exists before sending notification
            if (!$this->user) {
                \Log::warning('Cannot send visit confirmation email - user not found', [
                    'booking_id' => $this->id,
                    'user_id' => $this->user_id,
                    'customer_email' => $this->contact_email
                ]);
                return false;
            }

            $this->user->notify(new \App\Notifications\VisitConfirmationNotification($this, $managerNotes));
            
            $this->visit_confirmation_email_sent = true;
            $this->visit_confirmation_email_sent_at = now();
            $this->saveQuietly(); // Avoid triggering model events
            
            \Log::info('Visit confirmation email sent', [
                'booking_id' => $this->id,
                'customer_email' => $this->contact_email,
                'sent_at' => now()
            ]);
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send visit confirmation email', [
                'booking_id' => $this->id,
                'customer_email' => $this->contact_email,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send payment confirmation email to customer
     */
    public function sendPaymentConfirmationEmail(array $paymentDetails = []): bool
    {
        try {
            if ($this->payment_confirmation_email_sent) {
                return true; // Already sent
            }

            // Check if user exists before sending notification
            if (!$this->user) {
                \Log::warning('Cannot send payment confirmation email - user not found', [
                    'booking_id' => $this->id,
                    'user_id' => $this->user_id,
                    'customer_email' => $this->contact_email
                ]);
                return false;
            }

            $this->user->notify(new \App\Notifications\PaymentConfirmationNotification($this, $paymentDetails));
            
            $this->payment_confirmation_email_sent = true;
            $this->payment_confirmation_email_sent_at = now();
            $this->saveQuietly(); // Avoid triggering model events
            
            \Log::info('Payment confirmation email sent', [
                'booking_id' => $this->id,
                'customer_email' => $this->contact_email,
                'payment_amount' => $this->advance_payment_amount,
                'sent_at' => now()
            ]);
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send payment confirmation email', [
                'booking_id' => $this->id,
                'customer_email' => $this->contact_email,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send detailed bill email to customer
     */
    public function sendBillEmail(array $billData = []): bool
    {
        try {
            if ($this->bill_email_sent) {
                return true; // Already sent
            }

            // Check if user exists before sending notification
            if (!$this->user) {
                \Log::warning('Cannot send bill email - user not found', [
                    'booking_id' => $this->id,
                    'user_id' => $this->user_id,
                    'customer_email' => $this->contact_email
                ]);
                return false;
            }

            $this->user->notify(new \App\Notifications\BillNotification($this, $billData));
            
            $this->bill_email_sent = true;
            $this->bill_email_sent_at = now();
            $this->saveQuietly(); // Avoid triggering model events
            
            \Log::info('Bill email sent', [
                'booking_id' => $this->id,
                'customer_email' => $this->contact_email,
                'total_amount' => $this->calculateTotalAmount(),
                'sent_at' => now()
            ]);
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send bill email', [
                'booking_id' => $this->id,
                'customer_email' => $this->contact_email,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get workflow status for manager dashboard
     */
    public function getWorkflowStatus(): array
    {
        return [
            'current_step' => $this->workflow_step,
            'visit_submitted' => $this->visit_submitted,
            'manager_call_required' => $this->manager_call_required,
            'manager_call_completed' => $this->manager_call_completed,
            'visit_confirmed' => $this->visit_confirmed,
            'advance_payment_required' => $this->advance_payment_required,
            'advance_payment_paid' => $this->advance_payment_paid,
            'step5_unlocked' => $this->step5_unlocked,
            'emails_sent' => [
                'visit_confirmation' => $this->visit_confirmation_email_sent,
                'payment_confirmation' => $this->payment_confirmation_email_sent,
                'bill' => $this->bill_email_sent
            ],
            'next_action' => $this->getNextRequiredAction()
        ];
    }

    /**
     * Get the next required action for this booking
     */
    public function getNextRequiredAction(): string
    {
        if (!$this->visit_submitted) {
            return 'Customer needs to submit visit request';
        }
        
        if ($this->manager_call_required && !$this->manager_call_completed) {
            return 'Manager needs to call customer';
        }
        
        if ($this->manager_call_completed && !$this->visit_confirmed) {
            return 'Manager needs to confirm visit';
        }
        
        if ($this->visit_confirmed && !$this->advance_payment_paid) {
            return 'Customer needs to pay advance amount';
        }
        
        if ($this->advance_payment_paid && !$this->step5_unlocked) {
            return 'Manager needs to unlock Step 5';
        }
        
        if ($this->step5_unlocked && $this->workflow_step !== self::WORKFLOW_DETAILS_COMPLETED) {
            return 'Customer needs to complete wedding details';
        }
        
        return 'Booking process completed';
    }

    /**
     * Check if manager call is pending
     */
    public function isManagerCallPending(): bool
    {
        return $this->manager_call_required && !$this->manager_call_completed && $this->visit_submitted;
    }

    /**
     * Check if this booking requires manager attention
     */
    public function requiresManagerAttention(): bool
    {
        return $this->isManagerCallPending() || 
               ($this->visit_confirmed && $this->advance_payment_required && !$this->advance_payment_paid);
    }

    /**
     * Calculate the total amount for the booking including all services
     */
    public function calculateTotalAmount(): float
    {
        $total = 0;
        
        // Package price
        $total += floatval($this->package_price ?? 0);
        
        // Hall price (if separate from package)
        if ($this->hall) {
            $total += floatval($this->hall->price ?? 0);
        }
        
        // Decorations
        $decorationTotal = $this->bookingDecorations()
            ->join('decorations', 'booking_decorations.decoration_id', '=', 'decorations.id')
            ->sum(\DB::raw('decorations.price * booking_decorations.quantity'));
        $total += $decorationTotal;
        
        // Additional services
        $serviceTotal = $this->bookingAdditionalServices()
            ->join('additional_services', 'booking_additional_services.service_id', '=', 'additional_services.id')
            ->sum('additional_services.price');
        $total += $serviceTotal;
        
        // Catering
        $cateringTotal = $this->bookingCatering()->sum('total_price');
        $total += $cateringTotal;
        
        // Custom catering items
        $customCateringTotal = $this->bookingCateringItems()->sum('price');
        $total += $customCateringTotal;
        
        return round($total, 2);
    }

    /**
     * Check if a time slot is available for booking
     */
    public static function checkAvailability($hallId, $date, $startTime, $endTime, $excludeBookingId = null): bool
    {
        if (Carbon::parse($startTime) >= Carbon::parse($endTime)) {
            throw new \Exception('End time must be after start time.');
        }

        if (Carbon::parse($date)->isPast()) {
            throw new \Exception('Cannot book for past dates.');
        }

        $query = self::where('hall_id', $hallId)
            ->where('event_date', $date)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
                });
            })
            ->where('status', '!=', self::STATUS_CANCELLED);

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return !$query->exists();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            // Validate availability before creating
            if ($booking->hall_id && $booking->event_date && $booking->start_time && $booking->end_time) {
                if (!self::checkAvailability($booking->hall_id, $booking->event_date, $booking->start_time, $booking->end_time)) {
                    throw new \Exception('The selected time slot is not available.');
                }
            }
        });

        static::updating(function ($booking) {
            // Validate availability before updating if relevant fields changed
            if ($booking->isDirty(['hall_id', 'event_date', 'start_time', 'end_time'])) {
                if ($booking->hall_id && $booking->event_date && $booking->start_time && $booking->end_time) {
                    if (!self::checkAvailability($booking->hall_id, $booking->event_date, $booking->start_time, $booking->end_time, $booking->id)) {
                        throw new \Exception('The selected time slot is not available.');
                    }
                }
            }
        });

        static::saved(function ($booking) {
            // Recalculate total amount after saving
            $newTotal = $booking->calculateTotalAmount();
            if ($booking->total_amount != $newTotal) {
                $booking->total_amount = $newTotal;
                $booking->saveQuietly(); // Avoid infinite loop
            }
        });
    }
}