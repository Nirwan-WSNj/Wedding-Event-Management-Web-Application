<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminManagerSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'sandesh.nirwan@wmdemo.com'],
            [
                'first_name' => 'Sandesh',
                'last_name' => 'Nirwan',
                'phone' => '+94771234567',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'user_code' => 'ADM-0001',
                'status' => 'active',
                'email_verified_at' => now(),
                'password_changed_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'manager@wmdemo.com'],
            [
                'first_name' => 'Nimesha',
                'last_name' => 'Perera',
                'phone' => '+94772345678',
                'password' => Hash::make('password123'),
                'role' => 'manager',
                'user_code' => 'MGR-0002',
                'status' => 'active',
                'email_verified_at' => now(),
                'password_changed_at' => now(),
            ]
        );
    }
}
