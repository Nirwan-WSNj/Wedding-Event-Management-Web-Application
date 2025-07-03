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
            // Visit confirmation fields
            $table->boolean('visit_submitted')->default(false)->after('visit_time');
            $table->boolean('visit_confirmed')->default(false)->after('visit_submitted');
            $table->timestamp('visit_confirmed_at')->nullable()->after('visit_confirmed');
            $table->unsignedBigInteger('visit_confirmed_by')->nullable()->after('visit_confirmed_at');
            $table->text('visit_confirmation_notes')->nullable()->after('visit_confirmed_by');
            
            // Advance payment fields
            $table->boolean('advance_payment_required')->default(false)->after('visit_confirmation_notes');
            $table->decimal('advance_payment_amount', 10, 2)->nullable()->after('advance_payment_required');
            $table->boolean('advance_payment_paid')->default(false)->after('advance_payment_amount');
            $table->timestamp('advance_payment_paid_at')->nullable()->after('advance_payment_paid');
            $table->string('advance_payment_method')->nullable()->after('advance_payment_paid_at');
            $table->text('advance_payment_notes')->nullable()->after('advance_payment_method');
            
            // Step 5 access control
            $table->boolean('step5_unlocked')->default(false)->after('advance_payment_notes');
            
            // Foreign key for visit confirmed by (manager)
            $table->foreign('visit_confirmed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['visit_confirmed_by']);
            $table->dropColumn([
                'visit_submitted',
                'visit_confirmed',
                'visit_confirmed_at',
                'visit_confirmed_by',
                'visit_confirmation_notes',
                'advance_payment_required',
                'advance_payment_amount',
                'advance_payment_paid',
                'advance_payment_paid_at',
                'advance_payment_method',
                'advance_payment_notes',
                'step5_unlocked'
            ]);
        });
    }
};