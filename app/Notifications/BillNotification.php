<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class BillNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;
    protected $billData;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking, array $billData = [])
    {
        $this->booking = $booking;
        $this->billData = $billData;
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
        $totalAmount = number_format($this->booking->calculateTotalAmount(), 2);
        $advanceAmount = number_format($this->booking->advance_payment_amount, 2);
        $remainingAmount = number_format($this->booking->getRemainingAmount(), 2);
        
        $weddingDate = $this->booking->wedding_date ? 
            \Carbon\Carbon::parse($this->booking->wedding_date)->format('l, F j, Y') : 
            'To be confirmed';
        
        $bookingUrl = URL::signedRoute('booking.show', ['booking' => $this->booking->id]);

        return (new MailMessage)
            ->subject('Wedding Booking Bill - Wet Water Resort')
            ->greeting("Dear {$this->booking->contact_name},")
            ->line('Please find your detailed wedding booking bill below.')
            ->line('')
            ->line('**📋 BOOKING SUMMARY**')
            ->line("**Booking Reference:** WMD-{$this->booking->id}")
            ->line("**Customer:** {$this->booking->contact_name}")
            ->line("**Wedding Date:** {$weddingDate}")
            ->line("**Venue:** {$this->booking->hall_name}")
            ->line("**Package:** " . ($this->booking->package->name ?? 'Selected Package'))
            ->line("**Estimated Guests:** {$this->booking->customization_guest_count}")
            ->line('')
            ->line('**💰 FINANCIAL BREAKDOWN**')
            ->line("• **Package Cost:** Rs. " . number_format($this->booking->package_price, 2))
            ->line("• **Additional Services:** Rs. " . number_format($this->calculateAdditionalServices(), 2))
            ->line("• **Decorations:** Rs. " . number_format($this->calculateDecorations(), 2))
            ->line("• **Catering Extras:** Rs. " . number_format($this->calculateCateringExtras(), 2))
            ->line("• **Venue & Service Charges:** Rs. " . number_format($this->calculateVenueCharges(), 2))
            ->line('')
            ->line("**Subtotal:** Rs. " . number_format($this->calculateSubtotal(), 2))
            ->line("**Service Charge (10%):** Rs. " . number_format($this->calculateServiceCharge(), 2))
            ->line("**Taxes (5%):** Rs. " . number_format($this->calculateTaxes(), 2))
            ->line('')
            ->line("**🎯 GRAND TOTAL: Rs. {$totalAmount}**")
            ->line('')
            ->line('**💳 PAYMENT STATUS**')
            ->line("• **Advance Paid (20%):** Rs. {$advanceAmount} ✅")
            ->line("• **Remaining Balance:** Rs. {$remainingAmount}")
            ->line("• **Due Date:** On wedding day")
            ->line('')
            ->line('**📝 TERMS & CONDITIONS**')
            ->line('• Advance payment is non-refundable')
            ->line('• Full payment is due 14 days prior to the event date')
            ->line('• Children aged 6-12 years will be charged at 50% of the adult rate')
            ->line('• Venue charge includes 5 hours free, additional hours at Rs. 25,000/hour')
            ->line('• Functions must end by 12:00 midnight')
            ->line('')
            ->action('View Full Booking Details', $bookingUrl)
            ->line('')
            ->line('**📞 CONTACT INFORMATION**')
            ->line('For any questions about your bill or booking:')
            ->line('• Phone: +94 11 234 5678')
            ->line('• Email: info@wetwaterresort.com')
            ->line('• Office Hours: 9:00 AM - 6:00 PM (Monday to Saturday)')
            ->line('')
            ->line('Thank you for choosing Wet Water Resort for your special day!')
            ->salutation('Best regards,')
            ->salutation('Wet Water Resort')
            ->salutation('Accounts Department');
    }

    /**
     * Calculate additional services cost
     */
    private function calculateAdditionalServices(): float
    {
        // This would typically come from the booking's related services
        // For now, return a calculated value based on booking data
        return $this->booking->bookingAdditionalServices()
            ->join('additional_services', 'booking_additional_services.service_id', '=', 'additional_services.id')
            ->sum('additional_services.price') ?? 0;
    }

    /**
     * Calculate decorations cost
     */
    private function calculateDecorations(): float
    {
        return $this->booking->bookingDecorations()
            ->join('decorations', 'booking_decorations.decoration_id', '=', 'decorations.id')
            ->sum(\DB::raw('decorations.price * booking_decorations.quantity')) ?? 0;
    }

    /**
     * Calculate catering extras cost
     */
    private function calculateCateringExtras(): float
    {
        $cateringTotal = $this->booking->bookingCatering()->sum('total_price') ?? 0;
        $customCateringTotal = $this->booking->bookingCateringItems()->sum('price') ?? 0;
        return $cateringTotal + $customCateringTotal;
    }

    /**
     * Calculate venue and service charges
     */
    private function calculateVenueCharges(): float
    {
        // Standard charges as per the system
        $venueCharge = 100000; // Rs. 100,000 base venue charge
        $electricityCharge = 6000; // Rs. 6,000
        $multimediaCharge = 7500; // Rs. 7,500
        $chairCoverCharge = ($this->booking->customization_guest_count ?? 0) * 100; // Rs. 100 per cover
        
        return $venueCharge + $electricityCharge + $multimediaCharge + $chairCoverCharge;
    }

    /**
     * Calculate subtotal
     */
    private function calculateSubtotal(): float
    {
        return ($this->booking->package_price ?? 0) + 
               $this->calculateAdditionalServices() + 
               $this->calculateDecorations() + 
               $this->calculateCateringExtras() + 
               $this->calculateVenueCharges();
    }

    /**
     * Calculate service charge (10%)
     */
    private function calculateServiceCharge(): float
    {
        return $this->calculateSubtotal() * 0.10;
    }

    /**
     * Calculate taxes (5%)
     */
    private function calculateTaxes(): float
    {
        return $this->calculateSubtotal() * 0.05;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'type' => 'bill_notification',
            'total_amount' => $this->booking->calculateTotalAmount(),
            'advance_payment_amount' => $this->booking->advance_payment_amount,
            'remaining_amount' => $this->booking->getRemainingAmount(),
            'bill_data' => $this->billData
        ];
    }
}