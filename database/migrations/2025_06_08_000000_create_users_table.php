<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['customer', 'admin', 'manager'])->default('customer');
            $table->string('profile_photo_path', 2048)->nullable();
            $table->string('phone')->nullable();
            $table->string('suspension_reason')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->unsignedInteger('login_attempts')->default(0);
            $table->timestamp('password_changed_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            // Add indexes for better query performance
            $table->index('role');
            $table->index(['first_name', 'last_name']);
            $table->index('phone');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
