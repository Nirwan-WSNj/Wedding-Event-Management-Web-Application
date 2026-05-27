<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('bookings')) {
            return;
        }

        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'visit_submitted')) {
                $table->boolean('visit_submitted')->default(false);
            }
            if (!Schema::hasColumn('bookings', 'visit_confirmed')) {
                $table->boolean('visit_confirmed')->default(false);
            }
            if (!Schema::hasColumn('bookings', 'visit_confirmed_at')) {
                $table->timestamp('visit_confirmed_at')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'visit_confirmed_by')) {
                $table->unsignedBigInteger('visit_confirmed_by')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'visit_confirmation_notes')) {
                $table->text('visit_confirmation_notes')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'advance_payment_required')) {
                $table->boolean('advance_payment_required')->default(false);
            }
            if (!Schema::hasColumn('bookings', 'advance_payment_amount')) {
                $table->decimal('advance_payment_amount', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('bookings', 'advance_payment_paid')) {
                $table->boolean('advance_payment_paid')->default(false);
            }
            if (!Schema::hasColumn('bookings', 'advance_payment_paid_at')) {
                $table->timestamp('advance_payment_paid_at')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'advance_payment_method')) {
                $table->string('advance_payment_method')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'advance_payment_notes')) {
                $table->text('advance_payment_notes')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'step5_unlocked')) {
                $table->boolean('step5_unlocked')->default(false);
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('bookings')) {
            return;
        }

        Schema::table('bookings', function (Blueprint $table) {
            foreach ([
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
                'step5_unlocked',
            ] as $column) {
                if (Schema::hasColumn('bookings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
