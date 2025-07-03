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
            // Check if columns exist before adding them
            if (!Schema::hasColumn('bookings', 'visit_call_attempts')) {
                $table->integer('visit_call_attempts')->default(0)->after('visit_confirmed_at');
            }
            
            if (!Schema::hasColumn('bookings', 'last_call_attempt_at')) {
                $table->timestamp('last_call_attempt_at')->nullable()->after('visit_call_attempts');
            }
            
            if (!Schema::hasColumn('bookings', 'last_call_status')) {
                $table->enum('last_call_status', ['successful', 'no_answer', 'busy', 'invalid_number'])->nullable()->after('last_call_attempt_at');
            }
            
            if (!Schema::hasColumn('bookings', 'last_call_notes')) {
                $table->text('last_call_notes')->nullable()->after('last_call_status');
            }
            
            if (!Schema::hasColumn('bookings', 'visit_confirmation_method')) {
                $table->enum('visit_confirmation_method', ['phone_call', 'email', 'auto_approval'])->nullable()->after('last_call_notes');
            }
            
            if (!Schema::hasColumn('bookings', 'visit_rejected')) {
                $table->boolean('visit_rejected')->default(false)->after('visit_confirmation_notes');
            }
            
            if (!Schema::hasColumn('bookings', 'visit_rejected_at')) {
                $table->timestamp('visit_rejected_at')->nullable()->after('visit_rejected');
            }
            
            if (!Schema::hasColumn('bookings', 'visit_rejection_reason')) {
                $table->text('visit_rejection_reason')->nullable()->after('visit_rejected_at');
            }
            
            if (!Schema::hasColumn('bookings', 'callback_scheduled')) {
                $table->boolean('callback_scheduled')->default(false)->after('visit_rejection_reason');
            }
            
            if (!Schema::hasColumn('bookings', 'callback_date')) {
                $table->date('callback_date')->nullable()->after('callback_scheduled');
            }
            
            if (!Schema::hasColumn('bookings', 'callback_time')) {
                $table->string('callback_time')->nullable()->after('callback_date');
            }
            
            if (!Schema::hasColumn('bookings', 'callback_notes')) {
                $table->text('callback_notes')->nullable()->after('callback_time');
            }
            
            if (!Schema::hasColumn('bookings', 'callback_scheduled_by')) {
                $table->unsignedBigInteger('callback_scheduled_by')->nullable()->after('callback_notes');
            }
            
            if (!Schema::hasColumn('bookings', 'callback_scheduled_at')) {
                $table->timestamp('callback_scheduled_at')->nullable()->after('callback_scheduled_by');
            }
        });

        // Skip foreign key for now - can be added later if needed
        // Schema::table('bookings', function (Blueprint $table) {
        //     if (Schema::hasColumn('bookings', 'callback_scheduled_by')) {
        //         try {
        //             $table->foreign('callback_scheduled_by')->references('id')->on('users')->onDelete('set null');
        //         } catch (Exception $e) {
        //             // Foreign key might already exist
        //         }
        //     }
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop foreign key first if it exists
            try {
                $table->dropForeign(['callback_scheduled_by']);
            } catch (Exception $e) {
                // Foreign key might not exist
            }
            
            // Drop columns if they exist
            $columnsToRemove = [
                'visit_call_attempts',
                'last_call_attempt_at',
                'last_call_status',
                'last_call_notes',
                'visit_confirmation_method',
                'visit_rejected',
                'visit_rejected_at',
                'visit_rejection_reason',
                'callback_scheduled',
                'callback_date',
                'callback_time',
                'callback_notes',
                'callback_scheduled_by',
                'callback_scheduled_at'
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('bookings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};