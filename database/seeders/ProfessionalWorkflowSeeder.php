<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProfessionalWorkflowSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::where('user_code', 'CUS-0003')->first() ?? User::where('role', 'customer')->first();
        $manager = User::where('user_code', 'MGR-0002')->first() ?? User::where('role', 'manager')->first();
        $admin = User::where('user_code', 'ADM-0001')->first() ?? User::where('role', 'admin')->first();
        $booking = Booking::find(1);

        if (!$customer || !$manager) {
            return;
        }

        $now = now();

        if (Schema::hasTable('event_leads')) {
            DB::table('event_leads')->updateOrInsert(
                ['lead_number' => 'LEAD-2026-0001'],
                [
                    'customer_id' => $customer->id,
                    'assigned_manager_id' => $manager->id,
                    'customer_name' => 'Sandesh Nirwan',
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'preferred_contact_method' => 'phone',
                    'event_type' => 'wedding',
                    'preferred_event_date' => now()->addMonths(2)->toDateString(),
                    'estimated_guest_count' => 280,
                    'estimated_budget' => 3200000,
                    'source' => 'website',
                    'status' => 'converted',
                    'contacted_at' => $now,
                    'converted_at' => $now,
                    'notes' => 'Premium Kandyan wedding inquiry converted into confirmed booking.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $leadId = Schema::hasTable('event_leads') ? DB::table('event_leads')->where('lead_number', 'LEAD-2026-0001')->value('id') : null;

        if (Schema::hasTable('event_proposals')) {
            DB::table('event_proposals')->updateOrInsert(
                ['proposal_number' => 'PROP-2026-0001'],
                [
                    'lead_id' => $leadId,
                    'booking_id' => $booking?->id,
                    'customer_id' => $customer->id,
                    'created_by' => $manager->id,
                    'venue_amount' => 650000,
                    'package_amount' => 1250000,
                    'catering_amount' => 1820000,
                    'decoration_amount' => 120000,
                    'service_amount' => 260000,
                    'discount_amount' => 30000,
                    'tax_amount' => 0,
                    'total_amount' => 4070000,
                    'valid_until' => now()->addDays(14)->toDateString(),
                    'status' => 'accepted',
                    'sent_at' => $now,
                    'accepted_at' => $now,
                    'terms' => 'Proposal includes venue, package, catering, decoration, and selected paid services.',
                    'notes' => 'Accepted after manager follow-up call.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $proposalId = Schema::hasTable('event_proposals') ? DB::table('event_proposals')->where('proposal_number', 'PROP-2026-0001')->value('id') : null;

        if (Schema::hasTable('event_contracts')) {
            DB::table('event_contracts')->updateOrInsert(
                ['contract_number' => 'CON-2026-0001'],
                [
                    'proposal_id' => $proposalId,
                    'booking_id' => $booking?->id,
                    'customer_id' => $customer->id,
                    'terms_version' => 'v1.0',
                    'contract_file_path' => 'contracts/CON-2026-0001.pdf',
                    'status' => 'signed',
                    'sent_at' => $now,
                    'signed_at' => $now,
                    'signed_by_name' => 'Sandesh Nirwan',
                    'signed_by_email' => $customer->email,
                    'special_terms' => 'Final guest count must be confirmed seven days before event date.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        if (Schema::hasTable('event_invoices')) {
            DB::table('event_invoices')->updateOrInsert(
                ['invoice_number' => 'INV-2026-0001'],
                [
                    'booking_id' => $booking?->id,
                    'proposal_id' => $proposalId,
                    'customer_id' => $customer->id,
                    'issue_date' => now()->toDateString(),
                    'due_date' => now()->addDays(7)->toDateString(),
                    'subtotal' => 4070000,
                    'discount_amount' => 30000,
                    'tax_amount' => 0,
                    'total_amount' => 4040000,
                    'paid_amount' => 614000,
                    'status' => 'partially_paid',
                    'notes' => 'Advance payment received. Balance due before final event confirmation.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $invoiceId = Schema::hasTable('event_invoices') ? DB::table('event_invoices')->where('invoice_number', 'INV-2026-0001')->value('id') : null;

        if ($invoiceId && Schema::hasTable('event_invoice_installments')) {
            DB::table('event_invoice_installments')->updateOrInsert(
                ['invoice_id' => $invoiceId, 'label' => 'Advance payment'],
                ['amount' => 614000, 'due_date' => now()->toDateString(), 'paid_amount' => 614000, 'payment_method' => 'bank_transfer', 'transaction_reference' => 'TRX-DEMO-0001', 'paid_at' => $now, 'status' => 'paid', 'notes' => 'Confirmed by manager.', 'created_at' => $now, 'updated_at' => $now]
            );
            DB::table('event_invoice_installments')->updateOrInsert(
                ['invoice_id' => $invoiceId, 'label' => 'Final balance'],
                ['amount' => 3426000, 'due_date' => now()->addMonths(1)->toDateString(), 'paid_amount' => 0, 'status' => 'pending', 'notes' => 'Due before final event execution.', 'created_at' => $now, 'updated_at' => $now]
            );
        }

        if ($booking && Schema::hasTable('calendar_holds')) {
            DB::table('calendar_holds')->updateOrInsert(
                ['hall_id' => $booking->hall_id, 'event_date' => $booking->event_date, 'start_time' => $booking->start_time, 'end_time' => $booking->end_time, 'status' => 'converted'],
                ['booking_id' => $booking->id, 'lead_id' => $leadId, 'hold_type' => 'confirmed_booking', 'expires_at' => null, 'created_by' => $manager->id, 'notes' => 'Converted from lead hold to confirmed wedding booking.', 'created_at' => $now, 'updated_at' => $now]
            );
        }

        if ($booking && Schema::hasTable('banquet_event_orders')) {
            DB::table('banquet_event_orders')->updateOrInsert(
                ['beo_number' => 'BEO-2026-0001'],
                ['booking_id' => $booking->id, 'prepared_by' => $admin?->id, 'event_date' => $booking->event_date, 'setup_time' => '12:00:00', 'guest_arrival_time' => '17:00:00', 'service_start_time' => '19:30:00', 'event_end_time' => '23:30:00', 'final_guest_count' => 280, 'room_setup' => 'Round tables, luxury head table, dance floor, LED wall.', 'menu_notes' => 'Luxury banquet menu with mocktail bar.', 'decor_notes' => 'Golden stage backdrop and Kandyan entrance.', 'av_notes' => 'LED wall, wireless microphones, basic sound support.', 'staffing_notes' => 'Event manager, banquet captain, service team, AV operator.', 'status' => 'approved', 'approved_at' => $now, 'created_at' => $now, 'updated_at' => $now]
            );
        }

        $beoId = Schema::hasTable('banquet_event_orders') ? DB::table('banquet_event_orders')->where('beo_number', 'BEO-2026-0001')->value('id') : null;

        if ($booking && $beoId && Schema::hasTable('event_timeline_items')) {
            $timeline = [
                ['16:30:00', 'Final hall inspection', 'Manager and banquet captain inspect setup.', 'Operations', 1],
                ['17:00:00', 'Guest arrival', 'Welcome drinks and reception desk open.', 'Guest Relations', 2],
                ['18:00:00', 'Couple entrance', 'Traditional Kandyan entrance with cultural dancers.', 'Coordination', 3],
                ['19:30:00', 'Dinner service', 'Luxury banquet buffet opens.', 'Catering', 4],
                ['22:45:00', 'Send-off moment', 'Photo and video team coordinates final exit.', 'Media', 5],
            ];
            foreach ($timeline as [$time, $title, $description, $team, $order]) {
                DB::table('event_timeline_items')->updateOrInsert(
                    ['booking_id' => $booking->id, 'title' => $title],
                    ['beo_id' => $beoId, 'item_time' => $time, 'description' => $description, 'responsible_team' => $team, 'sort_order' => $order, 'created_at' => $now, 'updated_at' => $now]
                );
            }
        }

        if (Schema::hasTable('event_tasks')) {
            DB::table('event_tasks')->updateOrInsert(
                ['title' => 'Confirm final guest count', 'booking_id' => $booking?->id],
                ['lead_id' => $leadId, 'assigned_to' => $manager->id, 'description' => 'Call customer and confirm final guest count before final invoice.', 'due_date' => now()->addWeeks(3)->toDateString(), 'priority' => 'high', 'status' => 'todo', 'created_at' => $now, 'updated_at' => $now]
            );
            DB::table('event_tasks')->updateOrInsert(
                ['title' => 'Prepare final BEO printout', 'booking_id' => $booking?->id],
                ['lead_id' => $leadId, 'assigned_to' => $admin?->id, 'description' => 'Prepare final banquet event order for operations team.', 'due_date' => now()->addWeeks(6)->toDateString(), 'priority' => 'medium', 'status' => 'todo', 'created_at' => $now, 'updated_at' => $now]
            );
        }
    }
}
