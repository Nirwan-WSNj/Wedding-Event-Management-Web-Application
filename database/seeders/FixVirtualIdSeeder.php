<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class FixVirtualIdSeeder extends Seeder
{
    public function run(): void
    {
        foreach (User::all() as $user) {
            if ($user->role === User::ROLE_ADMIN) {
                $prefix = 'ad ';
            } elseif ($user->role === User::ROLE_MANAGER) {
                $prefix = 'hm ';
            } else {
                $prefix = 'cus ';
            }
            // Store the virtual id in a new column if you want, or just print for verification
            // Example: $user->virtual_id_db = $prefix . $user->id; $user->save();
            // For now, just output for verification
            echo $prefix . $user->id . "\n";
        }
    }
}
