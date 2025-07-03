<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop redundant fields
            $table->dropColumn(['hall_name', 'package_price', 'total_amount']);
            // Drop JSON fields (move to pivots)
            $table->dropColumn([
                'customization_decorations_additional',
                'customization_catering_custom',
                'customization_additional_services_selected',
            ]);
        });
        // Optionally, add foreign key constraints if not present
        // Schema::table('bookings', function (Blueprint $table) {
        //     $table->foreign('user_id')->references('id')->on('users');
        //     $table->foreign('hall_id')->references('id')->on('halls');
        //     $table->foreign('package_id')->references('id')->on('packages');
        //     $table->foreign('wedding_type_id')->references('id')->on('wedding_types');
        // });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('hall_name')->nullable();
            $table->decimal('package_price', 10, 2)->nullable();
            $table->decimal('total_amount', 12, 2)->nullable();
            $table->json('customization_decorations_additional')->nullable();
            $table->json('customization_catering_custom')->nullable();
            $table->json('customization_additional_services_selected')->nullable();
        });
    }
};
