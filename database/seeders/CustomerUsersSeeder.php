<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomerUsersSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $customers = [
            [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@example.com',
                'phone' => '+94771234567',
                'role' => 'customer',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah.johnson@example.com',
                'phone' => '+94772345678',
                'role' => 'customer',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Brown',
                'email' => 'michael.brown@example.com',
                'phone' => '+94773456789',
                'role' => 'customer',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Emily',
                'last_name' => 'Davis',
                'email' => 'emily.davis@example.com',
                'phone' => '+94774567890',
                'role' => 'customer',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Wilson',
                'email' => 'david.wilson@example.com',
                'phone' => '+94775678901',
                'role' => 'customer',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        ];

        foreach ($customers as $customer) {
            User::create($customer);
        }

        $this->command->info('Customer users created successfully with professional IDs!');
    }
}