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
            // Add package_price column if it doesn't exist
            if (!Schema::hasColumn('bookings', 'package_price')) {
                $table->decimal('package_price', 10, 2)->nullable()->after('package_id');
            }
            
            // Add hall_name column if it doesn't exist
            if (!Schema::hasColumn('bookings', 'hall_name')) {
                $table->string('hall_name')->nullable()->after('hall_id');
            }
            
            // Ensure visit_confirmed_by can handle string IDs (for manager professional IDs)
            if (Schema::hasColumn('bookings', 'visit_confirmed_by')) {
                $table->string('visit_confirmed_by', 20)->nullable()->change();
            }
        });
        
        // Add foreign key for visit_confirmed_by if it doesn't exist
        try {
            Schema::table('bookings', function (Blueprint $table) {
                $table->foreign('visit_confirmed_by')->references('id')->on('users')->onDelete('set null');
            });
        } catch (\Exception $e) {
            // Foreign key might already exist
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['package_price']);
        });
    }
};