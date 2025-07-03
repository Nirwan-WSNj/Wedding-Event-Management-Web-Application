<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Decoration;
use App\Models\AdditionalService;

class BookingSystemSeeder extends Seeder
{
    public function run()
    {
        // Seed decorations if they don't exist
        $decorations = [
            [
                'id' => 1,
                'name' => 'Grand Floral Arches',
                'description' => 'Elaborate arches adorned with premium flowers, tailored to wedding type.',
                'price' => 25000.00,
                'image' => 'floral-arches.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Chair Covers (White, Cream, Black)',
                'description' => 'Elegant covers for all guest chairs with bows.',
                'price' => 100.00,
                'image' => 'chair-covers.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Uplighting & Special Effects',
                'description' => 'Customizable LED uplighting and spotlighting for ambiance.',
                'price' => 15000.00,
                'image' => 'lighting-effects.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Premium Table Centerpieces',
                'description' => 'Sophisticated and elaborate table decorations with fresh flowers.',
                'price' => 8000.00,
                'image' => 'centerpieces.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Custom Photo Booth Backdrop',
                'description' => 'Personalized backdrop for photo opportunities.',
                'price' => 10000.00,
                'image' => 'photo-backdrop.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($decorations as $decoration) {
            Decoration::updateOrCreate(['id' => $decoration['id']], $decoration);
        }

        // Ensure additional services exist with correct IDs
        $services = [
            [
                'id' => 1,
                'name' => 'Complimentary Stay Room',
                'description' => 'A comfortable room for the couple prior to the event.',
                'price' => 0.00,
                'type' => 'compulsory',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Complimentary Changing Room',
                'description' => 'Private space for bridal party preparations.',
                'price' => 0.00,
                'type' => 'compulsory',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Basic Photography Locations',
                'description' => 'Access to scenic spots within the resort for photos.',
                'price' => 0.00,
                'type' => 'optional',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Guest Parking',
                'description' => 'Ample parking space for all attendees.',
                'price' => 0.00,
                'type' => 'optional',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Basic Sound System for Speeches',
                'description' => 'Microphone and speakers for announcements and toasts.',
                'price' => 0.00,
                'type' => 'optional',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'name' => 'Multimedia Projector & Screen',
                'description' => 'High-quality projector and screen for presentations or videos.',
                'price' => 7500.00,
                'type' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'name' => 'Live Band Performance',
                'description' => 'Professional live music for entertainment during reception.',
                'price' => 40000.00,
                'type' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'name' => 'Cultural Dancers',
                'description' => 'Traditional Sri Lankan dance performance.',
                'price' => 20000.00,
                'type' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9,
                'name' => 'Fireworks Display',
                'description' => 'Spectacular fireworks show for a grand finale.',
                'price' => 50000.00,
                'type' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 10,
                'name' => 'Professional Photography & Videography Package',
                'description' => 'Comprehensive coverage of your event by expert photographers and videographers.',
                'price' => 75000.00,
                'type' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($services as $service) {
            AdditionalService::updateOrCreate(['id' => $service['id']], $service);
        }

        $this->command->info('Booking system data seeded successfully!');
    }
}