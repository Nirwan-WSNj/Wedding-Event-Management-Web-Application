<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure the bookings table user_id column is properly configured for string IDs
        if (Schema::hasTable('bookings')) {
            // First, check if there are any foreign key constraints to drop
            try {
                Schema::table('bookings', function (Blueprint $table) {
                    $table->dropForeign(['user_id']);
                });
            } catch (\Exception $e) {
                // Foreign key might not exist, continue
            }

            // Ensure user_id column is string type and can handle professional IDs
            Schema::table('bookings', function (Blueprint $table) {
                $table->string('user_id', 20)->nullable()->change();
            });

            // Re-add the foreign key constraint with proper string reference
            Schema::table('bookings', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            });
        }

        // Ensure other related tables also have proper string user_id columns if they exist
        $relatedTables = ['booking_payments', 'contacts'];
        
        foreach ($relatedTables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'user_id')) {
                try {
                    Schema::table($tableName, function (Blueprint $table) {
                        $table->dropForeign(['user_id']);
                    });
                } catch (\Exception $e) {
                    // Foreign key might not exist, continue
                }

                Schema::table($tableName, function (Blueprint $table) {
                    $table->string('user_id', 20)->nullable()->change();
                });

                Schema::table($tableName, function (Blueprint $table) {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                });
            }
        }

        // Add indexes for better performance with string IDs
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->index(['user_id', 'status'], 'bookings_user_status_idx');
                $table->index(['user_id', 'visit_submitted'], 'bookings_user_visit_idx');
                $table->index(['user_id', 'advance_payment_paid'], 'bookings_user_payment_idx');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not easily reversible due to the complexity of the changes
        // If rollback is needed, restore from backup
    }
};