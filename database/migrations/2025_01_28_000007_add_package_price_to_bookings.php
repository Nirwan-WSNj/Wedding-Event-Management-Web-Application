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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'package_price')) {
                $table->dropColumn('package_price');
            }
        });
    }
};