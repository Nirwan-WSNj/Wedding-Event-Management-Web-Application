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
            // Add the missing JSON columns that are required for the booking form
            if (!Schema::hasColumn('bookings', 'customization_decorations_additional')) {
                $table->json('customization_decorations_additional')->nullable()->after('customization_wedding_type');
            }
            
            if (!Schema::hasColumn('bookings', 'customization_catering_custom')) {
                $table->json('customization_catering_custom')->nullable()->after('customization_decorations_additional');
            }
            
            if (!Schema::hasColumn('bookings', 'customization_additional_services_selected')) {
                $table->json('customization_additional_services_selected')->nullable()->after('customization_catering_custom');
            }
            
            // Also add the catering menu ID column if missing
            if (!Schema::hasColumn('bookings', 'customization_catering_selected_menu_id')) {
                $table->string('customization_catering_selected_menu_id')->nullable()->after('customization_additional_services_selected');
            }
            
            // Add selected_menu_id if missing (this is used as an alias for customization_catering_selected_menu_id)
            if (!Schema::hasColumn('bookings', 'selected_menu_id')) {
                $table->string('selected_menu_id')->nullable()->after('customization_catering_selected_menu_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'customization_decorations_additional',
                'customization_catering_custom',
                'customization_additional_services_selected',
                'customization_catering_selected_menu_id',
                'selected_menu_id'
            ]);
        });
    }
};