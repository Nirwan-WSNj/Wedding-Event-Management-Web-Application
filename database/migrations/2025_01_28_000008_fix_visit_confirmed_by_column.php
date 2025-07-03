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
        // First, drop the foreign key constraint
        try {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropForeign(['visit_confirmed_by']);
            });
        } catch (\Exception $e) {
            // Foreign key might not exist
        }

        // Change the column type to string
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('visit_confirmed_by', 20)->nullable()->change();
        });

        // Re-add the foreign key constraint
        try {
            Schema::table('bookings', function (Blueprint $table) {
                $table->foreign('visit_confirmed_by')->references('id')->on('users')->onDelete('set null');
            });
        } catch (\Exception $e) {
            // Log the error but don't fail the migration
            echo "Warning: Could not add foreign key constraint for visit_confirmed_by: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key
        try {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropForeign(['visit_confirmed_by']);
            });
        } catch (\Exception $e) {
            // Ignore
        }

        // Change back to integer
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('visit_confirmed_by')->nullable()->change();
        });
    }
};