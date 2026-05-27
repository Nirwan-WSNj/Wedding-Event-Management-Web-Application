<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('event_leads')) {
            Schema::create('event_leads', function (Blueprint $table) {
                $table->id();
                $table->string('lead_number')->unique();
                $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('assigned_manager_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('customer_name');
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('preferred_contact_method')->default('phone');
                $table->string('event_type')->default('wedding');
                $table->date('preferred_event_date')->nullable();
                $table->unsignedInteger('estimated_guest_count')->nullable();
                $table->decimal('estimated_budget', 12, 2)->nullable();
                $table->string('source')->default('website');
                $table->enum('status', ['new', 'contacted', 'tour_scheduled', 'quoted', 'converted', 'lost'])->default('new')->index();
                $table->timestamp('contacted_at')->nullable();
                $table->timestamp('converted_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('event_proposals')) {
            Schema::create('event_proposals', function (Blueprint $table) {
                $table->id();
                $table->string('proposal_number')->unique();
                $table->foreignId('lead_id')->nullable()->constrained('event_leads')->nullOnDelete();
                $table->foreignId('booking_id')->nullable()->constrained('bookings')->cascadeOnDelete();
                $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->decimal('venue_amount', 12, 2)->default(0);
                $table->decimal('package_amount', 12, 2)->default(0);
                $table->decimal('catering_amount', 12, 2)->default(0);
                $table->decimal('decoration_amount', 12, 2)->default(0);
                $table->decimal('service_amount', 12, 2)->default(0);
                $table->decimal('discount_amount', 12, 2)->default(0);
                $table->decimal('tax_amount', 12, 2)->default(0);
                $table->decimal('total_amount', 12, 2)->default(0);
                $table->date('valid_until')->nullable();
                $table->enum('status', ['draft', 'sent', 'accepted', 'rejected', 'expired'])->default('draft')->index();
                $table->timestamp('sent_at')->nullable();
                $table->timestamp('accepted_at')->nullable();
                $table->text('terms')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('event_contracts')) {
            Schema::create('event_contracts', function (Blueprint $table) {
                $table->id();
                $table->string('contract_number')->unique();
                $table->foreignId('proposal_id')->nullable()->constrained('event_proposals')->nullOnDelete();
                $table->foreignId('booking_id')->nullable()->constrained('bookings')->cascadeOnDelete();
                $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('terms_version')->default('v1.0');
                $table->string('contract_file_path')->nullable();
                $table->enum('status', ['draft', 'sent', 'signed', 'cancelled'])->default('draft')->index();
                $table->timestamp('sent_at')->nullable();
                $table->timestamp('signed_at')->nullable();
                $table->string('signed_by_name')->nullable();
                $table->string('signed_by_email')->nullable();
                $table->text('special_terms')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('event_invoices')) {
            Schema::create('event_invoices', function (Blueprint $table) {
                $table->id();
                $table->string('invoice_number')->unique();
                $table->foreignId('booking_id')->nullable()->constrained('bookings')->cascadeOnDelete();
                $table->foreignId('proposal_id')->nullable()->constrained('event_proposals')->nullOnDelete();
                $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
                $table->date('issue_date');
                $table->date('due_date')->nullable();
                $table->decimal('subtotal', 12, 2)->default(0);
                $table->decimal('discount_amount', 12, 2)->default(0);
                $table->decimal('tax_amount', 12, 2)->default(0);
                $table->decimal('total_amount', 12, 2)->default(0);
                $table->decimal('paid_amount', 12, 2)->default(0);
                $table->enum('status', ['draft', 'sent', 'partially_paid', 'paid', 'overdue', 'cancelled'])->default('draft')->index();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('event_invoice_installments')) {
            Schema::create('event_invoice_installments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('invoice_id')->constrained('event_invoices')->cascadeOnDelete();
                $table->string('label');
                $table->decimal('amount', 12, 2);
                $table->date('due_date')->nullable();
                $table->decimal('paid_amount', 12, 2)->default(0);
                $table->string('payment_method')->nullable();
                $table->string('transaction_reference')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->enum('status', ['pending', 'paid', 'overdue', 'cancelled'])->default('pending')->index();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('calendar_holds')) {
            Schema::create('calendar_holds', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hall_id')->constrained('halls')->cascadeOnDelete();
                $table->foreignId('booking_id')->nullable()->constrained('bookings')->cascadeOnDelete();
                $table->foreignId('lead_id')->nullable()->constrained('event_leads')->nullOnDelete();
                $table->date('event_date');
                $table->time('start_time')->nullable();
                $table->time('end_time')->nullable();
                $table->enum('hold_type', ['soft_hold', 'confirmed_booking', 'maintenance', 'blocked'])->default('soft_hold')->index();
                $table->enum('status', ['active', 'released', 'expired', 'converted'])->default('active')->index();
                $table->timestamp('expires_at')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->unique(['hall_id', 'event_date', 'start_time', 'end_time', 'status'], 'calendar_hold_unique_slot');
            });
        }

        if (!Schema::hasTable('banquet_event_orders')) {
            Schema::create('banquet_event_orders', function (Blueprint $table) {
                $table->id();
                $table->string('beo_number')->unique();
                $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
                $table->foreignId('prepared_by')->nullable()->constrained('users')->nullOnDelete();
                $table->date('event_date')->nullable();
                $table->time('setup_time')->nullable();
                $table->time('guest_arrival_time')->nullable();
                $table->time('service_start_time')->nullable();
                $table->time('event_end_time')->nullable();
                $table->unsignedInteger('final_guest_count')->nullable();
                $table->text('room_setup')->nullable();
                $table->text('menu_notes')->nullable();
                $table->text('decor_notes')->nullable();
                $table->text('av_notes')->nullable();
                $table->text('staffing_notes')->nullable();
                $table->enum('status', ['draft', 'approved', 'distributed', 'completed'])->default('draft')->index();
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('event_timeline_items')) {
            Schema::create('event_timeline_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
                $table->foreignId('beo_id')->nullable()->constrained('banquet_event_orders')->cascadeOnDelete();
                $table->time('item_time')->nullable();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('responsible_team')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('event_tasks')) {
            Schema::create('event_tasks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')->nullable()->constrained('bookings')->cascadeOnDelete();
                $table->foreignId('lead_id')->nullable()->constrained('event_leads')->nullOnDelete();
                $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
                $table->string('title');
                $table->text('description')->nullable();
                $table->date('due_date')->nullable();
                $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium')->index();
                $table->enum('status', ['todo', 'in_progress', 'done', 'cancelled'])->default('todo')->index();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('event_tasks');
        Schema::dropIfExists('event_timeline_items');
        Schema::dropIfExists('banquet_event_orders');
        Schema::dropIfExists('calendar_holds');
        Schema::dropIfExists('event_invoice_installments');
        Schema::dropIfExists('event_invoices');
        Schema::dropIfExists('event_contracts');
        Schema::dropIfExists('event_proposals');
        Schema::dropIfExists('event_leads');
    }
};
