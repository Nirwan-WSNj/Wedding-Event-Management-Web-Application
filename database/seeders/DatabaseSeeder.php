<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(AdminManagerSeeder::class);

        User::updateOrCreate(
            ['email' => 'customer@wmdemo.test'],
            [
                'first_name' => 'Sandesh',
                'last_name' => 'Nirwan',
                'phone' => '+94773456789',
                'password' => Hash::make(env('DEMO_USER_PASSWORD', 'change-me-locally')),
                'role' => 'customer',
                'user_code' => 'CUS-0003',
                'status' => 'active',
                'email_verified_at' => now(),
                'password_changed_at' => now(),
            ]
        );

        $this->call(DemoVenueSeeder::class);
        $this->call(ProfessionalBookingDemoSeeder::class);
        $this->call(ProfessionalWorkflowSeeder::class);
    }
}
