<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Customer
        if (!User::where('email', 'customer@example.com')->exists()) {
            User::create([
                'first_name' => 'Test',
                'last_name' => 'Customer',
                'email' => 'customer@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'email_verified_at' => now(),
            ]);
        }

        // Remove direct admin and manager creation here to avoid duplicates
        // AdminManagerSeeder will handle admin and manager users

        // Fix user codes for all users to match new format
        // $this->call(\Database\Seeders\FixUserCodesSeeder::class);

        // Call AdminManagerSeeder (handles admin and manager users)
        $this->call(AdminManagerSeeder::class);

        // Populate demo venue data (halls, packages, wedding types, catering menus)
        $this->call(\Database\Seeders\DemoVenueSeeder::class);
        
        // Populate sample booking and payment data
        // $this->call(\Database\Seeders\BookingPaymentSeeder::class); // removed, seeder deleted
    }
}