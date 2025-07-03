<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class PaymentConfirmationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;
    protected $paymentDetails;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking, array $paymentDetails = [])
    {
        $this->booking = $booking;
        $this->paymentDetails = $paymentDetails;
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
        $advanceAmount = number_format($this->booking->advance_payment_amount, 2);
        $totalAmount = number_format($this->booking->calculateTotalAmount(), 2);
        $remainingAmount = number_format($this->booking->getRemainingAmount(), 2);
        
        $paymentMethod = $this->paymentDetails['payment_method'] ?? 'Not specified';
        $transactionId = $this->paymentDetails['transaction_id'] ?? null;
        
        $bookingUrl = URL::signedRoute('booking.show', ['booking' => $this->booking->id]);
        $continueBookingUrl = route('booking') . '#step-5';

        $mail = (new MailMessage)
            ->subject('Payment Confirmed - Complete Your Wedding Booking')
            ->greeting("Dear {$this->booking->contact_name},")
            ->line('Excellent! Your advance payment has been confirmed.')
            ->line('')
            ->line('**Payment Summary:**')
            ->line("• **Advance Payment:** Rs. {$advanceAmount} ✅")
            ->line("• **Payment Method:** {$paymentMethod}")
            ->when($transactionId, function ($mail) use ($transactionId) {
                return $mail->line("• **Transaction ID:** {$transactionId}");
            })
            ->line("• **Total Wedding Cost:** Rs. {$totalAmount}")
            ->line("• **Remaining Balance:** Rs. {$remainingAmount}")
            ->line('')
            ->line('**🎉 You can now complete your wedding details!**')
            ->line('Your booking is now unlocked for final customization. Please log in to complete:')
            ->line('• Final wedding date confirmation')
            ->line('• Bride and groom details')
            ->line('• Ceremony and reception timings')
            ->line('• Any additional special requests')
            ->line('')
            ->action('Complete Wedding Details', $continueBookingUrl)
            ->line('')
            ->line('**Important Reminders:**')
            ->line("• The remaining balance of Rs. {$remainingAmount} is due on your wedding day")
            ->line('• Please complete your wedding details within 7 days to secure your preferred date')
            ->line('• Contact us at +94 11 234 5678 for any questions or changes')
            ->line('')
            ->line('**Wedding Details:**')
            ->line("• **Venue:** {$this->booking->hall_name}")
            ->line("• **Package:** " . ($this->booking->package->name ?? 'Selected Package'))
            ->line("• **Estimated Guests:** {$this->booking->customization_guest_count}")
            ->when($this->booking->customization_wedding_type, function ($mail) {
                return $mail->line("• **Wedding Type:** {$this->booking->customization_wedding_type}");
            })
            ->line('')
            ->line('Thank you for choosing Wet Water Resort for your special day!')
            ->salutation('Best regards,')
            ->salutation('The Wet Water Resort Team')
            ->salutation('📞 +94 11 234 5678 | 📧 info@wetwaterresort.com');

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'type' => 'payment_confirmation',
            'advance_payment_amount' => $this->booking->advance_payment_amount,
            'total_amount' => $this->booking->calculateTotalAmount(),
            'remaining_amount' => $this->booking->getRemainingAmount(),
            'payment_details' => $this->paymentDetails
        ];
    }
}