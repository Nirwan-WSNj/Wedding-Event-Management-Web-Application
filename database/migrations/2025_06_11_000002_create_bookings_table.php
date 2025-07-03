<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Disabled: This migration is redundant and causes table already exists error. Table is created elsewhere.
        // Schema::create('bookings', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('user_id')->constrained('users');
        //     $table->foreignId('hall_id')->nullable()->constrained('halls');
        //     $table->string('hall_name')->nullable();
        //     $table->date('hall_booking_date')->nullable();
        //     $table->foreignId('package_id')->nullable()->constrained('packages');
        //     $table->decimal('package_price', 12, 2)->nullable();
        //     $table->integer('customization_guest_count')->nullable();
        //     $table->string('customization_wedding_type')->nullable();
        //     $table->string('customization_wedding_type_time_slot')->nullable();
        //     $table->date('customization_catholic_day1_date')->nullable();
        //     $table->date('customization_catholic_day2_date')->nullable();
        //     $table->json('customization_decorations_additional')->nullable();
        //     $table->unsignedBigInteger('customization_catering_selected_menu_id')->nullable();
        //     $table->json('customization_catering_custom')->nullable();
        //     $table->json('customization_additional_services_selected')->nullable();
        //     $table->string('contact_name')->nullable();
        //     $table->string('contact_email')->nullable();
        //     $table->string('contact_phone')->nullable();
        //     $table->string('contact_visit_purpose')->nullable();
        //     $table->string('contact_visit_purpose_other')->nullable();
        //     $table->text('contact_special_requests')->nullable();
        //     $table->date('visit_date')->nullable();
        //     $table->string('visit_time')->nullable();
        //     $table->string('wedding_groom_name')->nullable();
        //     $table->string('wedding_bride_name')->nullable();
        //     $table->string('wedding_groom_email')->nullable();
        //     $table->string('wedding_bride_email')->nullable();
        //     $table->string('wedding_groom_phone')->nullable();
        //     $table->string('wedding_bride_phone')->nullable();
        //     $table->date('wedding_date')->nullable();
        //     $table->date('wedding_alternative_date1')->nullable();
        //     $table->date('wedding_alternative_date2')->nullable();
        //     $table->string('wedding_ceremony_time')->nullable();
        //     $table->string('wedding_reception_time')->nullable();
        //     $table->text('wedding_additional_notes')->nullable();
        //     $table->boolean('wedding_terms_agreed')->nullable();
        //     $table->boolean('wedding_privacy_agreed')->nullable();
        //     $table->date('event_date')->nullable();
        //     $table->string('start_time')->nullable();
        //     $table->string('end_time')->nullable();
        //     $table->string('status')->default('pending');
        //     $table->timestamp('cancelled_at')->nullable();
        //     $table->string('cancellation_reason')->nullable();
        //     $table->decimal('total_amount', 12, 2)->nullable();
        //     $table->softDeletes();
        //     $table->timestamps();
        // });
    }

    public function down()
    {
        // Schema::dropIfExists('bookings');
    }
};
// This migration is now obsolete. bookings table is created in 2025_06_10_999999_wedding_system_complete_structure.php
