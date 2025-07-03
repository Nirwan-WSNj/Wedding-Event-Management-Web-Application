<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Email notification tracking
            if (!Schema::hasColumn('bookings', 'visit_confirmation_email_sent')) {
                $table->boolean('visit_confirmation_email_sent')->default(false)->after('visit_confirmation_notes');
            }
            
            if (!Schema::hasColumn('bookings', 'visit_confirmation_email_sent_at')) {
                $table->timestamp('visit_confirmation_email_sent_at')->nullable()->after('visit_confirmation_email_sent');
            }
            
            if (!Schema::hasColumn('bookings', 'payment_confirmation_email_sent')) {
                $table->boolean('payment_confirmation_email_sent')->default(false)->after('advance_payment_notes');
            }
            
            if (!Schema::hasColumn('bookings', 'payment_confirmation_email_sent_at')) {
                $table->timestamp('payment_confirmation_email_sent_at')->nullable()->after('payment_confirmation_email_sent');
            }
            
            if (!Schema::hasColumn('bookings', 'bill_email_sent')) {
                $table->boolean('bill_email_sent')->default(false)->after('payment_confirmation_email_sent_at');
            }
            
            if (!Schema::hasColumn('bookings', 'bill_email_sent_at')) {
                $table->timestamp('bill_email_sent_at')->nullable()->after('bill_email_sent');
            }
            
            // Manager workflow control
            if (!Schema::hasColumn('bookings', 'manager_call_required')) {
                $table->boolean('manager_call_required')->default(true)->after('visit_submitted');
            }
            
            if (!Schema::hasColumn('bookings', 'manager_call_completed')) {
                $table->boolean('manager_call_completed')->default(false)->after('manager_call_required');
            }
            
            if (!Schema::hasColumn('bookings', 'manager_call_completed_at')) {
                $table->timestamp('manager_call_completed_at')->nullable()->after('manager_call_completed');
            }
            
            if (!Schema::hasColumn('bookings', 'manager_call_completed_by')) {
                $table->unsignedBigInteger('manager_call_completed_by')->nullable()->after('manager_call_completed_at');
            }
            
            // Workflow validation
            if (!Schema::hasColumn('bookings', 'workflow_step')) {
                $table->enum('workflow_step', [
                    'draft',           // Initial state
                    'visit_submitted', // Customer submitted visit request
                    'call_pending',    // Manager needs to call customer
                    'call_completed',  // Manager completed call
                    'visit_confirmed', // Visit confirmed by manager
                    'payment_pending', // Advance payment required
                    'payment_confirmed', // Payment confirmed, Step 5 unlocked
                    'details_completed', // Final wedding details completed
                    'booking_finalized'  // Booking fully completed
                ])->default('draft')->after('status');
            }
            
            if (!Schema::hasColumn('bookings', 'workflow_notes')) {
                $table->text('workflow_notes')->nullable()->after('workflow_step');
            }
            
            // Customer communication preferences
            if (!Schema::hasColumn('bookings', 'preferred_contact_method')) {
                $table->enum('preferred_contact_method', ['phone', 'email', 'both'])->default('both')->after('contact_phone');
            }
            
            if (!Schema::hasColumn('bookings', 'best_call_time')) {
                $table->string('best_call_time')->nullable()->after('preferred_contact_method');
            }
            
            // Manager assignment for call handling
            if (!Schema::hasColumn('bookings', 'assigned_manager_id')) {
                $table->unsignedBigInteger('assigned_manager_id')->nullable()->after('workflow_notes');
            }
            
            if (!Schema::hasColumn('bookings', 'assigned_at')) {
                $table->timestamp('assigned_at')->nullable()->after('assigned_manager_id');
            }
        });

        // Add foreign key constraints
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'manager_call_completed_by')) {
                try {
                    $table->foreign('manager_call_completed_by')->references('id')->on('users')->onDelete('set null');
                } catch (Exception $e) {
                    // Foreign key might already exist
                }
            }
            
            if (Schema::hasColumn('bookings', 'assigned_manager_id')) {
                try {
                    $table->foreign('assigned_manager_id')->references('id')->on('users')->onDelete('set null');
                } catch (Exception $e) {
                    // Foreign key might already exist
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop foreign keys first
            try {
                $table->dropForeign(['manager_call_completed_by']);
                $table->dropForeign(['assigned_manager_id']);
            } catch (Exception $e) {
                // Foreign keys might not exist
            }
            
            // Drop columns if they exist
            $columnsToRemove = [
                'visit_confirmation_email_sent',
                'visit_confirmation_email_sent_at',
                'payment_confirmation_email_sent',
                'payment_confirmation_email_sent_at',
                'bill_email_sent',
                'bill_email_sent_at',
                'manager_call_required',
                'manager_call_completed',
                'manager_call_completed_at',
                'manager_call_completed_by',
                'workflow_step',
                'workflow_notes',
                'preferred_contact_method',
                'best_call_time',
                'assigned_manager_id',
                'assigned_at'
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('bookings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};