<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ProfessionalUsersSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Clear existing users
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create admin user
        User::create([
            'id' => 'A0001',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@weddingmanagement.com',
            'phone' => '+94771234567',
            'role' => 'admin',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // Create manager user
        User::create([
            'id' => 'M0001',
            'first_name' => 'Manager',
            'last_name' => 'User',
            'email' => 'manager@weddingmanagement.com',
            'phone' => '+94772345678',
            'role' => 'manager',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // Create customer users
        $customers = [
            [
                'id' => 'CUS0001',
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@example.com',
                'phone' => '+94773456789',
                'role' => 'customer',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
            [
                'id' => 'CUS0002',
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah.johnson@example.com',
                'phone' => '+94774567890',
                'role' => 'customer',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
            [
                'id' => 'CUS0003',
                'first_name' => 'Michael',
                'last_name' => 'Brown',
                'email' => 'michael.brown@example.com',
                'phone' => '+94775678901',
                'role' => 'customer',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
            [
                'id' => 'CUS0004',
                'first_name' => 'Emily',
                'last_name' => 'Davis',
                'email' => 'emily.davis@example.com',
                'phone' => '+94776789012',
                'role' => 'customer',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
            [
                'id' => 'CUS0005',
                'first_name' => 'David',
                'last_name' => 'Wilson',
                'email' => 'david.wilson@example.com',
                'phone' => '+94777890123',
                'role' => 'customer',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        ];

        foreach ($customers as $customer) {
            User::create($customer);
        }

        $this->command->info('Professional users created successfully!');
        $this->command->info('Admin: A0001 (admin@weddingmanagement.com)');
        $this->command->info('Manager: M0001 (manager@weddingmanagement.com)');
        $this->command->info('Customers: CUS0001 to CUS0005');
    }
}