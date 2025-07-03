<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class VisitConfirmationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;
    protected $managerNotes;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking, string $managerNotes = null)
    {
        $this->booking = $booking;
        $this->managerNotes = $managerNotes;
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
        $visitDate = $this->booking->visit_date ? 
            \Carbon\Carbon::parse($this->booking->visit_date)->format('l, F j, Y') : 
            'Date to be confirmed';
        
        $visitTime = $this->booking->visit_time ?? 'Time to be confirmed';
        
        $advanceAmount = number_format($this->booking->advance_payment_amount, 2);
        
        $bookingUrl = URL::signedRoute('booking.show', ['booking' => $this->booking->id]);

        return (new MailMessage)
            ->subject('Visit Confirmed - Wet Water Resort Wedding Booking')
            ->greeting("Dear {$this->booking->contact_name},")
            ->line('Great news! Your visit request has been confirmed by our manager.')
            ->line("**Visit Details:**")
            ->line("• **Venue:** {$this->booking->hall_name}")
            ->line("• **Date:** {$visitDate}")
            ->line("• **Time:** {$visitTime}")
            ->line("• **Purpose:** {$this->booking->visit_purpose}")
            ->line('')
            ->line('**Next Steps:**')
            ->line("To secure your wedding date and proceed with final planning, an advance payment of **Rs. {$advanceAmount}** (20% of estimated total) is required.")
            ->line('')
            ->line('**Payment Instructions:**')
            ->line('• Contact our office at **+94 11 234 5678** to make the payment')
            ->line('• Payment can be made via cash, credit card, or bank transfer')
            ->line('• Once payment is confirmed, you can complete your wedding details online')
            ->line('')
            ->when($this->managerNotes, function ($mail) {
                return $mail->line("**Manager's Notes:** {$this->managerNotes}");
            })
            ->action('View Booking Details', $bookingUrl)
            ->line('')
            ->line('**Important:** Please bring a valid ID and any specific questions about your wedding requirements during the visit.')
            ->line('')
            ->line('We look forward to welcoming you to Wet Water Resort!')
            ->salutation('Best regards,')
            ->salutation('The Wet Water Resort Team')
            ->salutation('📞 +94 11 234 5678 | 📧 info@wetwaterresort.com');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'type' => 'visit_confirmation',
            'hall_name' => $this->booking->hall_name,
            'visit_date' => $this->booking->visit_date,
            'visit_time' => $this->booking->visit_time,
            'advance_payment_amount' => $this->booking->advance_payment_amount,
            'manager_notes' => $this->managerNotes
        ];
    }
}