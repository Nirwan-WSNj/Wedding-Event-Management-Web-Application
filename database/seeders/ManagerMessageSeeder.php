<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ManagerMessage;
use App\Models\User;
use App\Models\Booking;

class ManagerMessageSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get a customer user for sample messages
        $customer = User::where('role', 'customer')->first();
        $booking = Booking::first();

        // Create sample system messages
        ManagerMessage::createSystemMessage(
            'System Update Available',
            'A new system update is available with improved booking management features. Please review the changelog for details.',
            'normal',
            ['version' => '2.1.0', 'features' => ['improved_dashboard', 'call_tracking']]
        );

        ManagerMessage::createSystemMessage(
            'Database Backup Completed',
            'Daily database backup has been completed successfully. All booking data is secure.',
            'low',
            ['backup_time' => now()->subHours(2)->toISOString(), 'size' => '45.2MB']
        );

        ManagerMessage::createSystemMessage(
            'High Traffic Alert',
            'Website is experiencing higher than normal traffic. Monitor booking system performance.',
            'high',
            ['traffic_increase' => '150%', 'concurrent_users' => 45]
        );

        // Create sample customer inquiries
        if ($customer) {
            ManagerMessage::createCustomerInquiry(
                'Question about Wedding Package Pricing',
                'Hi, I would like to know more details about the Golden Wedding Package. Can you provide information about what decorations are included and if we can customize the menu? Also, is there a discount for off-season bookings?',
                $customer->id,
                $booking?->id,
                [
                    'customer_phone' => '+1234567890',
                    'preferred_contact' => 'email',
                    'wedding_date' => '2025-09-15',
                    'guest_count' => 150
                ]
            );

            ManagerMessage::createCustomerInquiry(
                'Urgent: Change in Wedding Date',
                'We need to change our wedding date from September 15th to October 20th due to family circumstances. Is this possible? We have already paid the advance payment.',
                $customer->id,
                $booking?->id,
                [
                    'customer_phone' => '+1234567890',
                    'original_date' => '2025-09-15',
                    'new_date' => '2025-10-20',
                    'reason' => 'family_circumstances'
                ]
            );
        }

        // Create sample booking updates
        if ($booking) {
            ManagerMessage::createBookingUpdate(
                'Visit Confirmation Required',
                'Customer has submitted a visit request for ' . ($booking->hall->name ?? 'Unknown Hall') . '. Please review and confirm the visit date.',
                $booking->id,
                'high',
                [
                    'visit_date' => $booking->visit_date,
                    'visit_time' => $booking->visit_time,
                    'customer_name' => $booking->contact_name,
                    'action_required' => 'visit_confirmation'
                ]
            );

            ManagerMessage::createBookingUpdate(
                'Booking Details Updated',
                'Customer has updated their booking details including guest count and special requirements.',
                $booking->id,
                'normal',
                [
                    'updated_fields' => ['guest_count', 'special_requirements'],
                    'new_guest_count' => $booking->guest_count,
                    'update_time' => now()->subHours(1)->toISOString()
                ]
            );
        }

        // Create sample payment notifications
        if ($booking) {
            ManagerMessage::createPaymentNotification(
                'Advance Payment Received',
                'Customer has made the advance payment for their wedding booking. Please verify and confirm the payment.',
                $booking->id,
                'high',
                [
                    'amount' => $booking->advance_payment_amount ?? 50000,
                    'payment_method' => 'bank_transfer',
                    'reference_number' => 'TXN' . rand(100000, 999999),
                    'customer_name' => $booking->contact_name
                ]
            );
        }

        // Create sample visit request messages
        if ($booking) {
            ManagerMessage::createVisitRequest(
                'New Visit Request - Urgent',
                'Customer has requested an urgent visit for tomorrow to finalize wedding arrangements. They mentioned they have a tight timeline.',
                $booking->id,
                $customer?->id,
                [
                    'requested_date' => now()->addDay()->format('Y-m-d'),
                    'requested_time' => '14:00',
                    'urgency' => 'high',
                    'reason' => 'tight_timeline',
                    'customer_phone' => '+1234567890'
                ]
            );
        }

        // Create some older messages to show history
        ManagerMessage::create([
            'type' => 'system',
            'subject' => 'Monthly Report Generated',
            'message' => 'Monthly booking report for ' . now()->subMonth()->format('F Y') . ' has been generated and is ready for review.',
            'priority' => 'normal',
            'is_read' => true,
            'read_at' => now()->subDays(5),
            'created_at' => now()->subDays(7),
            'metadata' => [
                'report_period' => now()->subMonth()->format('Y-m'),
                'total_bookings' => 23,
                'revenue' => 1250000
            ]
        ]);

        ManagerMessage::create([
            'type' => 'customer_inquiry',
            'subject' => 'Catering Menu Customization',
            'message' => 'Can we add some traditional Sri Lankan dishes to our wedding menu? We would like to include hoppers and kottu for the late-night snack.',
            'from_user_id' => $customer?->id,
            'booking_id' => $booking?->id,
            'priority' => 'normal',
            'is_read' => true,
            'read_at' => now()->subDays(3),
            'created_at' => now()->subDays(4),
            'metadata' => [
                'customer_phone' => '+1234567890',
                'requested_items' => ['hoppers', 'kottu'],
                'meal_type' => 'late_night_snack'
            ]
        ]);
    }
}