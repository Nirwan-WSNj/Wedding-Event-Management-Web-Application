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
            // Call tracking fields
            $table->integer('visit_call_attempts')->default(0)->after('visit_confirmed_at');
            $table->timestamp('last_call_attempt_at')->nullable()->after('visit_call_attempts');
            $table->enum('last_call_status', ['successful', 'no_answer', 'busy', 'invalid_number'])->nullable()->after('last_call_attempt_at');
            $table->text('last_call_notes')->nullable()->after('last_call_status');
            
            // Visit confirmation method tracking
            $table->enum('visit_confirmation_method', ['phone_call', 'email', 'auto_approval'])->nullable()->after('last_call_notes');
            $table->text('visit_confirmation_notes')->nullable()->after('visit_confirmation_method');
            
            // Visit rejection tracking
            $table->boolean('visit_rejected')->default(false)->after('visit_confirmation_notes');
            $table->timestamp('visit_rejected_at')->nullable()->after('visit_rejected');
            $table->text('visit_rejection_reason')->nullable()->after('visit_rejected_at');
            
            // Callback scheduling
            $table->boolean('callback_scheduled')->default(false)->after('visit_rejection_reason');
            $table->date('callback_date')->nullable()->after('callback_scheduled');
            $table->string('callback_time')->nullable()->after('callback_date');
            $table->text('callback_notes')->nullable()->after('callback_time');
            $table->unsignedBigInteger('callback_scheduled_by')->nullable()->after('callback_notes');
            $table->timestamp('callback_scheduled_at')->nullable()->after('callback_scheduled_by');
            
            // Add foreign key for callback_scheduled_by
            $table->foreign('callback_scheduled_by')->references('id')->on('users')->onDelete('set null');
            
            // Add indexes for better performance
            $table->index(['visit_confirmed', 'visit_submitted']);
            $table->index(['callback_scheduled', 'callback_date']);
            $table->index(['visit_rejected', 'visit_rejected_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['callback_scheduled_by']);
            
            // Drop indexes
            $table->dropIndex(['visit_confirmed', 'visit_submitted']);
            $table->dropIndex(['callback_scheduled', 'callback_date']);
            $table->dropIndex(['visit_rejected', 'visit_rejected_at']);
            
            // Drop columns
            $table->dropColumn([
                'visit_call_attempts',
                'last_call_attempt_at',
                'last_call_status',
                'last_call_notes',
                'visit_confirmation_method',
                'visit_confirmation_notes',
                'visit_rejected',
                'visit_rejected_at',
                'visit_rejection_reason',
                'callback_scheduled',
                'callback_date',
                'callback_time',
                'callback_notes',
                'callback_scheduled_by',
                'callback_scheduled_at'
            ]);
        });
    }
};