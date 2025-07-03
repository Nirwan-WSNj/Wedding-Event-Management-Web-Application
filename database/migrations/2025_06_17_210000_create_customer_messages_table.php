<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_messages', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->onDelete('set null');
            $table->string('subject');
            $table->text('message');
            $table->enum('type', ['inquiry', 'complaint', 'feedback', 'booking_related', 'general'])->default('general');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->enum('status', ['new', 'in_progress', 'replied', 'resolved', 'closed'])->default('new');
            $table->boolean('is_read')->default(false);
            $table->timestamp('replied_at')->nullable();
            $table->json('metadata')->nullable();
            $table->string('customer_email');
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['status', 'priority']);
            $table->index(['is_read', 'created_at']);
            $table->index('customer_email');
            $table->index('type');

            // Foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_messages');
    }
};