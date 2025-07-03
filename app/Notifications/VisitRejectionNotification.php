<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VisitRejectionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;
    protected $rejectionReason;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking, string $rejectionReason)
    {
        $this->booking = $booking;
        $this->rejectionReason = $rejectionReason;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Visit Request Update - WM Demo')
            ->greeting('Dear ' . $this->booking->contact_name . ',')
            ->line('We regret to inform you that your visit request for your wedding booking has been declined.')
            ->line('**Booking Details:**')
            ->line('- Booking ID: #' . $this->booking->id)
            ->line('- Hall: ' . ($this->booking->hall_name ?? 'N/A'))
            ->line('- Package: ' . ($this->booking->package->name ?? 'N/A'))
            ->line('- Requested Visit Date: ' . ($this->booking->visit_date ? $this->booking->visit_date->format('F j, Y') : 'N/A'))
            ->line('')
            ->line('**Reason for Decline:**')
            ->line($this->rejectionReason)
            ->line('')
            ->line('We understand this may be disappointing. Please feel free to contact us to discuss alternative options or to address any concerns.')
            ->line('You can reach us at:')
            ->line('- Phone: +94 123 456 789')
            ->line('- Email: info@wmdemo.com')
            ->line('')
            ->line('We appreciate your understanding and look forward to helping you find the perfect solution for your special day.')
            ->salutation('Best regards, The WM Demo Team');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'rejection_reason' => $this->rejectionReason,
            'contact_name' => $this->booking->contact_name,
            'hall_name' => $this->booking->hall_name,
            'visit_date' => $this->booking->visit_date?->format('Y-m-d'),
        ];
    }
}