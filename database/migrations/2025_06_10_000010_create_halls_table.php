<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Disabled: This migration is redundant and causes table already exists error. Table is created elsewhere.
        // Schema::create('halls', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->integer('capacity')->nullable();
        //     $table->decimal('price', 12, 2)->nullable();
        //     $table->text('description')->nullable();
        //     $table->string('image')->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();
        // });
    }

    public function down()
    {
        // Schema::dropIfExists('halls');
    }
};
