<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, let's create a backup of current user data
        $users = DB::table('users')->get();
        
        // Create a temporary table to store the mapping
        Schema::create('user_id_mapping', function (Blueprint $table) {
            $table->bigInteger('old_id');
            $table->string('new_id', 20);
            $table->primary('old_id');
        });
        
        // Generate professional IDs and store mapping
        $roleCounters = ['customer' => 1, 'admin' => 1, 'manager' => 1];
        
        foreach ($users as $user) {
            $prefix = match($user->role) {
                'customer' => 'CUS',
                'admin' => 'A',
                'manager' => 'M',
                default => 'CUS'
            };
            
            $newId = $prefix . str_pad($roleCounters[$user->role], 4, '0', STR_PAD_LEFT);
            $roleCounters[$user->role]++;
            
            // Store the mapping
            DB::table('user_id_mapping')->insert([
                'old_id' => $user->id,
                'new_id' => $newId
            ]);
        }
        
        // Update foreign key references in other tables
        $this->updateForeignKeyReferences();
        
        // Now modify the users table structure
        Schema::table('users', function (Blueprint $table) {
            // Add new professional_id column temporarily
            $table->string('new_id', 20)->nullable()->after('id');
        });
        
        // Update users with new IDs
        foreach ($users as $user) {
            $mapping = DB::table('user_id_mapping')->where('old_id', $user->id)->first();
            DB::table('users')->where('id', $user->id)->update(['new_id' => $mapping->new_id]);
        }
        
        // Drop the old id column and rename new_id to id
        Schema::table('users', function (Blueprint $table) {
            $table->dropPrimary(['id']);
            $table->dropColumn('id');
            $table->dropColumn('professional_id'); // Remove the separate professional_id column
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('new_id', 'id');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->primary('id');
        });
        
        // Clean up
        Schema::dropIfExists('user_id_mapping');
    }
    
    /**
     * Update foreign key references in other tables
     */
    private function updateForeignKeyReferences(): void
    {
        // Update bookings table user_id references
        if (Schema::hasTable('bookings')) {
            $bookings = DB::table('bookings')->whereNotNull('user_id')->get();
            foreach ($bookings as $booking) {
                $mapping = DB::table('user_id_mapping')->where('old_id', $booking->user_id)->first();
                if ($mapping) {
                    DB::table('bookings')->where('id', $booking->id)->update(['user_id' => $mapping->new_id]);
                }
            }
            
            // Change user_id column type to string
            Schema::table('bookings', function (Blueprint $table) {
                $table->string('user_id', 20)->nullable()->change();
            });
        }
        
        // Update any other tables that reference user_id
        // Add more tables here as needed
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a complex migration that's difficult to reverse
        // In production, you'd want to create a more sophisticated rollback
        throw new Exception('This migration cannot be reversed automatically. Please restore from backup if needed.');
    }
};