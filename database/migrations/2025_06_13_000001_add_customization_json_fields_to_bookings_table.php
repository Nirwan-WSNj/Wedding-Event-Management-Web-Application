<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->json('customization_decorations_additional')->nullable()->after('customization_wedding_type');
            $table->json('customization_catering_custom')->nullable()->after('customization_decorations_additional');
            $table->json('customization_additional_services_selected')->nullable()->after('customization_catering_custom');
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'customization_decorations_additional',
                'customization_catering_custom',
                'customization_additional_services_selected'
            ]);
        });
    }
};
