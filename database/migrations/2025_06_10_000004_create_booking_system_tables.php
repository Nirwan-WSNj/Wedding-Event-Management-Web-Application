<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Halls
        Schema::create('halls', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('capacity');
            $table->string('image')->nullable();
            $table->json('features')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Add this for deleted_at
        });

        // Disabled: bookings, packages, and wedding_types tables are created in a later migration.
        /*
        // Packages
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->boolean('highlight')->default(false);
            $table->string('image')->nullable();
            $table->json('features')->nullable();
            $table->timestamps();
        });

        // Wedding Types
        Schema::create('wedding_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // Decorations
        Schema::create('decorations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('image')->nullable();
            $table->foreignId('wedding_type_id')->nullable()->constrained('wedding_types');
            $table->timestamps();
        });

        // Catering Menus
        Schema::create('catering_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('details')->nullable();
            $table->foreignId('package_id')->nullable()->constrained('packages');
            $table->timestamps();
        });

        // Catering Items
        Schema::create('catering_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->decimal('price', 10, 2);
            $table->string('unit', 50)->nullable();
            $table->timestamps();
        });

        // Additional Services
        Schema::create('additional_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('image')->nullable();
            $table->enum('type', ['compulsory', 'optional', 'paid'])->default('optional');
            $table->timestamps();
        });

        // Disabled: bookings table is created in a later migration.
        /*
        // Bookings
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('hall_id')->constrained('halls');
            $table->foreignId('package_id')->constrained('packages');
            $table->foreignId('wedding_type_id')->nullable()->constrained('wedding_types');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->string('hall_name');
            $table->date('hall_booking_date');
            $table->string('package_name');
            $table->decimal('package_price', 10, 2);
            $table->integer('guest_count');
            $table->string('wedding_type_time_slot', 50)->nullable();
            $table->date('catholic_day1_date')->nullable();
            $table->date('catholic_day2_date')->nullable();
            $table->foreignId('selected_menu_id')->nullable()->constrained('catering_menus');
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone', 20);
            $table->string('visit_purpose')->nullable();
            $table->string('visit_purpose_other')->nullable();
            $table->date('visit_date')->nullable();
            $table->time('visit_time')->nullable();
            $table->text('special_requests')->nullable();
            $table->string('groom_name');
            $table->string('bride_name');
            $table->string('groom_email')->nullable();
            $table->string('bride_email')->nullable();
            $table->string('groom_phone', 20);
            $table->string('bride_phone', 20)->nullable();
            $table->date('wedding_date');
            $table->date('alternative_date1')->nullable();
            $table->date('alternative_date2')->nullable();
            $table->time('ceremony_time');
            $table->time('reception_time');
            $table->text('additional_notes')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('service_charge', 10, 2);
            $table->decimal('taxes', 10, 2);
            $table->decimal('grand_total', 10, 2);
            $table->boolean('terms_agreed')->default(false);
            $table->boolean('privacy_agreed')->default(false);
            $table->timestamps();
        });

        // Booking Decorations
        Schema::create('booking_decorations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings');
            $table->foreignId('decoration_id')->constrained('decorations');
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });

        // Booking Services
        Schema::create('booking_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings');
            $table->foreignId('service_id')->constrained('additional_services');
            $table->timestamps();
        });

        // Booking Catering Items
        Schema::create('booking_catering_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings');
            $table->foreignId('item_id')->nullable()->constrained('catering_items');
            $table->string('custom_name')->nullable();
            $table->string('category');
            $table->decimal('price', 10, 2);
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });

        // Booking Payments
        Schema::create('booking_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method', 50);
            $table->string('transaction_id')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->dateTime('payment_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        */
    }

    public function down()
    {
        Schema::dropIfExists('booking_payments');
        Schema::dropIfExists('booking_catering_items');
        Schema::dropIfExists('booking_services');
        Schema::dropIfExists('booking_decorations');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('additional_services');
        Schema::dropIfExists('catering_items');
        Schema::dropIfExists('catering_menus');
        Schema::dropIfExists('decorations');
        Schema::dropIfExists('wedding_types');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('halls');
    }
};
