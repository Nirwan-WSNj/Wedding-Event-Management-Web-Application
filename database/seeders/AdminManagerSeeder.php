<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class AdminManagerSeeder extends Seeder
{
    public function run()
    {
        // Create admin user if not exists
        User::firstOrCreate(
            ['email' => 'admin@wmdemo.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'phone' => '0000000000',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // Create manager user if not exists
        User::firstOrCreate(
            ['email' => 'manager@wmdemo.com'],
            [
                'first_name' => 'Manager',
                'last_name' => 'User',
                'phone' => '0000000001',
                'password' => Hash::make('password123'),
                'role' => 'manager',
            ]
        );
    }
}
