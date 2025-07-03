<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop all existing tables in reverse order to avoid foreign key constraints
        Schema::dropIfExists('booking_payments');
        Schema::dropIfExists('booking_additional_services');
        Schema::dropIfExists('booking_catering');
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
        Schema::dropIfExists('users');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('password_reset_tokens');

        // USERS TABLE
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 191)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 191);
            $table->enum('role', ['customer', 'admin', 'manager'])->default('customer');
            $table->string('phone', 191)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // HALLS TABLE
        Schema::create('halls', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('capacity');
            $table->boolean('is_active')->default(true); // Add this line
            $table->string('image', 191)->nullable();
            $table->json('features')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Add this for deleted_at
        });

        // PACKAGES TABLE
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->boolean('is_active')->default(true); // Add this line
            $table->string('image', 191)->nullable();
            $table->timestamps();
            $table->softDeletes(); // Add this for deleted_at
        });

        // WEDDING TYPES TABLE
        Schema::create('wedding_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true); // Add this line
            $table->timestamps();
        });

        // CATERING MENUS TABLE
        Schema::create('catering_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191);
            $table->text('description')->nullable();
            $table->decimal('price_per_person', 8, 2);
            $table->foreignId('package_id')->nullable()->constrained('packages')->onDelete('set null');
            $table->timestamps();
        });

        // ADDITIONAL SERVICES TABLE
        Schema::create('additional_services', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->enum('type', ['compulsory', 'optional', 'paid'])->default('optional');
            $table->timestamps();
        });

        // BOOKINGS TABLE
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('hall_id')->constrained('halls')->onDelete('cascade');
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->foreignId('wedding_type_id')->nullable()->constrained('wedding_types')->onDelete('set null');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->date('event_date')->nullable(); // Add this column
            $table->time('start_time')->nullable(); // Add this column
            $table->time('end_time')->nullable(); // Add this column
            $table->date('hall_booking_date');
            $table->decimal('package_price', 10, 2);
            $table->integer('guest_count')->nullable();
            $table->string('wedding_type_time_slot', 50)->nullable();
            $table->date('catholic_day1_date')->nullable();
            $table->date('catholic_day2_date')->nullable();
            $table->string('contact_name', 191)->nullable();
            $table->string('contact_email', 191)->nullable();
            $table->string('contact_phone', 191)->nullable();
            $table->date('visit_date')->nullable();
            $table->time('visit_time')->nullable();
            $table->text('special_requests')->nullable();
            $table->string('wedding_groom_name', 191)->nullable();
            $table->string('wedding_bride_name', 191)->nullable();
            $table->string('wedding_groom_email', 191)->nullable();
            $table->string('wedding_bride_email', 191)->nullable();
            $table->string('wedding_groom_phone', 191)->nullable();
            $table->string('wedding_bride_phone', 191)->nullable();
            $table->date('wedding_date')->nullable();
            $table->string('wedding_ceremony_time', 191)->nullable();
            $table->string('wedding_reception_time', 191)->nullable();
            $table->text('wedding_additional_notes')->nullable();
            $table->boolean('terms_agreed')->default(false);
            $table->boolean('privacy_agreed')->default(false);
            $table->decimal('total_amount', 12, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancellation_reason', 255)->nullable();
            $table->string('hall_name')->nullable(); // Add this column
            $table->integer('customization_guest_count')->nullable(); // Add this column
            $table->string('customization_wedding_type')->nullable(); // Add this column
        });

        // BOOKING CATERING TABLE
        Schema::create('booking_catering', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('menu_id')->constrained('catering_menus')->onDelete('cascade');
            $table->integer('guest_count');
            $table->decimal('price_per_person', 8, 2);
            $table->decimal('total_price', 10, 2);
            $table->text('special_requests')->nullable();
            $table->timestamps();
        });

        // BOOKING ADDITIONAL SERVICES TABLE
        Schema::create('booking_additional_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('additional_services')->onDelete('cascade');
            $table->timestamps();
        });

        // BOOKING PAYMENTS TABLE
        Schema::create('booking_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash','credit_card','bank_transfer','cheque']);
            $table->string('transaction_id', 191)->nullable();
            $table->enum('status', ['pending','completed','failed','refunded'])->default('pending');
            $table->dateTime('payment_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // CONTACTS TABLE
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('name', 191);
            $table->string('email', 191);
            $table->string('phone', 191)->nullable();
            $table->string('purpose', 191)->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
        });

        // PASSWORD RESET TOKENS TABLE
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email', 191)->index();
            $table->string('token', 191);
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_payments');
        Schema::dropIfExists('booking_additional_services');
        Schema::dropIfExists('booking_catering');
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
        Schema::dropIfExists('users');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('password_reset_tokens');
    }
};
