<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hall;

class HallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $halls = [
            [
                'name' => 'Grand Ballroom',
                'description' => 'Celebrate in unparalleled luxury with crystal chandeliers, a grand stage, and cutting-edge acoustics for a majestic wedding.',
                'capacity' => 500,
                'price' => 5500.00,
                'image' => 'GrandBallroom.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Jubilee Ballroom',
                'description' => 'Transform your day into a fairytale with this octagonal, pillarless ballroom, adorned with Victorian skylights and colonial charm.',
                'capacity' => 200,
                'price' => 4200.00,
                'image' => 'jublieeballroom.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Garden Pavilion',
                'description' => 'Embrace nature\'s embrace in this romantic outdoor pavilion, surrounded by lush gardens and twinkling string lights.',
                'capacity' => 300,
                'price' => 3500.00,
                'image' => 'GardenPavilion.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Royal Heritage Hall',
                'description' => 'Honor tradition with this culturally rich hall, blending Sri Lankan heritage with modern elegance for a timeless wedding.',
                'capacity' => 200,
                'price' => 4800.00,
                'image' => 'RoyalHeritage.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Riverside Garden',
                'description' => 'Celebrate your special day surrounded by nature\'s tranquility. The riverside garden offers a scenic backdrop of flowing water and greenery for a romantic outdoor ceremony.',
                'capacity' => 150,
                'price' => 2500.00,
                'image' => 'Riverside Garden.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($halls as $hall) {
            Hall::updateOrCreate(
                ['name' => $hall['name']],
                $hall
            );
        }
    }
}