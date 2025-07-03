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
        // Add missing columns to bookings table
        if (!Schema::hasColumn('bookings', 'visit_purpose')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->string('visit_purpose', 191)->nullable()->after('visit_time');
            });
        }
        if (!Schema::hasColumn('bookings', 'visit_purpose_other')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->string('visit_purpose_other', 191)->nullable()->after('visit_purpose');
            });
        }
        if (!Schema::hasColumn('bookings', 'wedding_alternative_date1')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->date('wedding_alternative_date1')->nullable()->after('wedding_date');
            });
        }
        if (!Schema::hasColumn('bookings', 'wedding_alternative_date2')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->date('wedding_alternative_date2')->nullable()->after('wedding_alternative_date1');
            });
        }

        // Create booking_catering_items table if it does not exist
        if (!Schema::hasTable('booking_catering_items')) {
            Schema::create('booking_catering_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('booking_id');
                $table->string('category', 191);
                $table->string('item_name', 191);
                $table->decimal('price', 10, 2);
                $table->timestamps();
                $table->index('booking_id', 'booking_catering_items_booking_id_foreign');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['visit_purpose', 'visit_purpose_other', 'wedding_alternative_date1', 'wedding_alternative_date2']);
        });
        Schema::dropIfExists('booking_catering_items');
    }
};
