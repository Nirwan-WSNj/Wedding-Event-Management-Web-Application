<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfessionalBookingDemoSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::where('user_code', 'CUS-0003')->first() ?? User::where('role', 'customer')->first();

        if (!$customer) {
            return;
        }

        $now = now();

        $bookings = [
            [
                'id' => 1,
                'user_id' => $customer->id,
                'hall_id' => 2,
                'package_id' => 1,
                'wedding_type_id' => 1,
                'status' => Booking::STATUS_CONFIRMED,
                'hall_name' => 'Grand Ballroom',
                'hall_booking_date' => now()->addMonths(2)->toDateString(),
                'package_name' => 'Infinity Package',
                'package_price' => 1250000,
                'guest_count' => 280,
                'selected_menu_id' => 3,
                'contact_name' => 'Sandesh Nirwan',
                'contact_email' => $customer->email,
                'contact_phone' => $customer->phone,
                'visit_date' => now()->addDays(5)->toDateString(),
                'visit_time' => '10:30:00',
                'visit_submitted' => true,
                'visit_confirmed' => true,
                'visit_confirmed_at' => $now,
                'visit_confirmed_by' => 'MGR-0002',
                'visit_confirmation_notes' => 'Manager confirmed the premium wedding visit and payment plan.',
                'special_requests' => 'Prefer warm gold lighting and a traditional Kandyan entrance.',
                'wedding_groom_name' => 'Sandesh Nirwan',
                'wedding_bride_name' => 'Amandi Perera',
                'wedding_date' => now()->addMonths(2)->toDateString(),
                'event_date' => now()->addMonths(2)->toDateString(),
                'start_time' => '17:30',
                'end_time' => '23:30',
                'total_amount' => 3070000,
                'terms_agreed' => true,
                'privacy_agreed' => true,
                'advance_payment_required' => true,
                'advance_payment_amount' => 614000,
                'advance_payment_paid' => true,
                'advance_payment_paid_at' => $now,
                'advance_payment_method' => 'bank_transfer',
                'step5_unlocked' => true,
                'workflow_step' => 'payment_confirmed',
                'workflow_notes' => 'Advance payment confirmed. Customer can complete final wedding details.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'user_id' => $customer->id,
                'hall_id' => 3,
                'package_id' => 3,
                'wedding_type_id' => 3,
                'status' => Booking::STATUS_PENDING,
                'hall_name' => 'Garden Pavilion',
                'hall_booking_date' => now()->addMonths(3)->toDateString(),
                'package_name' => 'Golden Package',
                'package_price' => 780000,
                'guest_count' => 180,
                'selected_menu_id' => 2,
                'contact_name' => 'Sandesh Nirwan',
                'contact_email' => $customer->email,
                'contact_phone' => $customer->phone,
                'visit_date' => now()->addDays(8)->toDateString(),
                'visit_time' => '15:00:00',
                'visit_submitted' => true,
                'visit_confirmed' => false,
                'special_requests' => 'Outdoor aisle styling and sunset photo timing required.',
                'wedding_groom_name' => 'Sandesh Nirwan',
                'wedding_bride_name' => 'Amandi Perera',
                'wedding_date' => now()->addMonths(3)->toDateString(),
                'event_date' => now()->addMonths(3)->toDateString(),
                'start_time' => '16:30',
                'end_time' => '22:00',
                'total_amount' => 1739000,
                'terms_agreed' => true,
                'privacy_agreed' => true,
                'advance_payment_required' => false,
                'advance_payment_paid' => false,
                'workflow_step' => 'call_pending',
                'workflow_notes' => 'Visit submitted by customer. Manager call required.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($bookings as $booking) {
            Booking::updateOrCreate(['id' => $booking['id']], $booking);
        }

        DB::table('booking_catering')->updateOrInsert(
            ['booking_id' => 1, 'menu_id' => 3],
            ['guest_count' => 280, 'price_per_person' => 6500, 'total_price' => 1820000, 'special_requests' => 'Include mocktail bar.', 'created_at' => $now, 'updated_at' => $now]
        );

        DB::table('booking_decorations')->updateOrInsert(
            ['booking_id' => 1, 'decoration_id' => 2],
            ['quantity' => 1, 'created_at' => $now, 'updated_at' => $now]
        );

        DB::table('booking_additional_services')->updateOrInsert(
            ['booking_id' => 1, 'service_id' => 6],
            ['created_at' => $now, 'updated_at' => $now]
        );
    }
}
