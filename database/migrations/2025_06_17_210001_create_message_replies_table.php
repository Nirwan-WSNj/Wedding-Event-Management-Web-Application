<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('message_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('customer_messages')->onDelete('cascade');
            $table->text('content');
            $table->boolean('is_from_manager')->default(true);
            $table->string('manager_name')->nullable();
            $table->timestamp('sent_at');
            $table->boolean('email_sent')->default(false);
            $table->timestamp('email_sent_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['message_id', 'sent_at']);
            $table->index('is_from_manager');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message_replies');
    }
};