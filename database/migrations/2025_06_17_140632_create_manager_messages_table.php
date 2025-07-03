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
        Schema::create('manager_messages', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['system', 'customer_inquiry', 'booking_update', 'payment_notification', 'visit_request']);
            $table->string('subject');
            $table->text('message');
            $table->unsignedBigInteger('from_user_id')->nullable(); // Customer who sent the message
            $table->unsignedBigInteger('to_manager_id')->nullable(); // Manager receiving the message
            $table->unsignedBigInteger('booking_id')->nullable(); // Related booking if any
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->json('metadata')->nullable(); // Additional data like customer phone, email, etc.
            $table->timestamps();

            $table->foreign('from_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('set null');
            
            $table->index(['to_manager_id', 'is_read', 'created_at']);
            $table->index(['type', 'created_at']);
            $table->index(['booking_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manager_messages');
    }
};