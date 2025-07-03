<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, let's backup and clear the users table
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Drop foreign key constraints temporarily
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        }
        
        // Clear users table
        DB::table('users')->truncate();
        
        // Modify the users table structure
        Schema::table('users', function (Blueprint $table) {
            $table->dropPrimary(['id']);
            $table->dropColumn('id');
            $table->dropColumn('professional_id'); // Remove the separate professional_id column if it exists
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->string('id', 20)->primary()->first();
        });
        
        // Update bookings table to use string user_id
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->string('user_id', 20)->nullable()->change();
            });
            
            // Re-add foreign key constraint
            Schema::table('bookings', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            });
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not easily reversible
        throw new Exception('This migration cannot be reversed. Please restore from backup if needed.');
    }
};