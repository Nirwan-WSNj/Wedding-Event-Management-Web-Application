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
        Schema::create('manager_call_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('manager_id');
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->enum('call_status', ['successful', 'no_answer', 'busy', 'invalid_number']);
            $table->text('call_notes')->nullable();
            $table->timestamp('call_attempted_at');
            $table->timestamps();

            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('manager_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->index(['booking_id', 'call_attempted_at']);
            $table->index(['manager_id', 'call_attempted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manager_call_logs');
    }
};