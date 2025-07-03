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
        Schema::table('manager_messages', function (Blueprint $table) {
            // Drop the foreign key first
            $table->dropForeign(['from_user_id']);
            
            // Change the column type to string to match users table
            $table->string('from_user_id')->nullable()->change();
            $table->string('to_manager_id')->nullable()->change();
            
            // Re-add the foreign key
            $table->foreign('from_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manager_messages', function (Blueprint $table) {
            // Drop the foreign key
            $table->dropForeign(['from_user_id']);
            
            // Change back to bigint
            $table->unsignedBigInteger('from_user_id')->nullable()->change();
            $table->unsignedBigInteger('to_manager_id')->nullable()->change();
            
            // Re-add the foreign key
            $table->foreign('from_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }
};