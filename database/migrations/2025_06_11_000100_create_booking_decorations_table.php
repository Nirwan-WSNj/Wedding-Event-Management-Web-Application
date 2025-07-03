<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('booking_decorations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('decoration_id');
            $table->integer('quantity')->default(1);
            $table->timestamps();
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('decoration_id')->references('id')->on('decorations')->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('booking_decorations');
    }
};
