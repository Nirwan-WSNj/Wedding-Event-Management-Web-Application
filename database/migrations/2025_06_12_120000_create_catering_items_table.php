<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('catering_items')) {
            Schema::create('catering_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('menu_id');
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2)->default(0);
                $table->softDeletes();
                $table->timestamps();
                $table->foreign('menu_id')->references('id')->on('catering_menus')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('catering_items');
    }
};
