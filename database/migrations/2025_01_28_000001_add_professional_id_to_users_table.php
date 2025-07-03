<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('professional_id', 20)->nullable()->unique()->after('id');
        });

        // Generate professional IDs for existing users
        $this->generateProfessionalIds();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('professional_id');
        });
    }

    /**
     * Generate professional IDs for existing users
     */
    private function generateProfessionalIds(): void
    {
        // Get all users grouped by role
        $roles = ['customer', 'admin', 'manager'];
        
        foreach ($roles as $role) {
            $users = User::where('role', $role)->orderBy('id')->get();
            $counter = 1;
            
            foreach ($users as $user) {
                $prefix = match($role) {
                    'customer' => 'CUS',
                    'admin' => 'A',
                    'manager' => 'M',
                    default => 'CUS'
                };
                
                $professionalId = $prefix . str_pad($counter, 4, '0', STR_PAD_LEFT);
                $user->update(['professional_id' => $professionalId]);
                $counter++;
            }
        }
    }
};