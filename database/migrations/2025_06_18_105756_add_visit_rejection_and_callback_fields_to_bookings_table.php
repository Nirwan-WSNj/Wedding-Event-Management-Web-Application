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
            // Only add the missing visit_rejected_by field
            if (!Schema::hasColumn('bookings', 'visit_rejected_by')) {
                $table->string('visit_rejected_by')->nullable()->after('visit_rejected_at');
            }
        });
        
        // Skip foreign key constraints for now due to table reference issues
        // They can be added later if needed
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop only the column we added
            if (Schema::hasColumn('bookings', 'visit_rejected_by')) {
                $table->dropColumn('visit_rejected_by');
            }
        });
    }
};
