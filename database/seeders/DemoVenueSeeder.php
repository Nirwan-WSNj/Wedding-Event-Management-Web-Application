<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

class DemoVenueSeeder extends Seeder
{
    public function run()
    {
        // Truncate the halls table to ensure no duplicates
        \DB::table('halls')->truncate();
        // Insert all 5 required halls with updated details and features
        \DB::table('halls')->insert([
            [
                'name' => 'Jubilee Ballroom',
                'description' => 'Transform your day into a fairytale with this octagonal, pillarless ballroom, adorned with Victorian skylights and colonial charm.',
                'capacity' => 200,
                'price' => 4200,
                'image' => 'jublieeballroom.jpg',
                'features' => json_encode(['Indoor', '7,956 sq ft', 'Up to 200 Guests']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Grand Ballroom',
                'description' => 'Celebrate in unparalleled luxury with crystal chandeliers, a grand stage, and cutting-edge acoustics for a majestic wedding.',
                'capacity' => 500,
                'price' => 5500,
                'image' => 'GrandBallroom.jpg',
                'features' => json_encode(['Indoor', '10,000 sq ft', 'Up to 500 Guests']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Garden Pavilion',
                'description' => 'Embrace nature’s embrace in this romantic outdoor pavilion, surrounded by lush gardens and twinkling string lights.',
                'capacity' => 300,
                'price' => 3500,
                'image' => 'GardenPavilion.jpg',
                'features' => json_encode(['Outdoor', '7,500 sq ft', 'Up to 300 Guests']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Royal Heritage Hall',
                'description' => 'Honor tradition with this culturally rich hall, blending Sri Lankan heritage with modern elegance for a timeless wedding.',
                'capacity' => 200,
                'price' => 4800,
                'image' => 'RoyalHeritage.jpg',
                'features' => json_encode(['Indoor', '5,000 sq ft', 'Up to 200 Guests']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Riverside Garden',
                'description' => 'Celebrate your special day surrounded by nature\'s tranquility. The riverside garden offers a scenic backdrop of flowing water and greenery for a romantic outdoor ceremony.',
                'capacity' => 150,
                'price' => 2500,
                'image' => 'Riverside Garden.jpg',
                'features' => json_encode(['Semi-outdoor', '4,000 sq ft', 'Up to 150 Guests']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insert sample packages (features column removed)
        DB::table('packages')->insertOrIgnore([
            [
                'id' => 'package-basic',
                'name' => 'Basic Package',
                'description' => 'Essential services package',
                'price' => 450000,
                'image' => 'basic-package.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 'package-golden',
                'name' => 'Golden Package',
                'description' => 'Popular enhanced package',
                'price' => 580000,
                'image' => 'golden-package.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insert sample wedding types
        DB::table('wedding_types')->insertOrIgnore([
            [
                'id' => 1,
                'name' => 'Catholic',
                'description' => 'Traditional Catholic wedding',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Hindu',
                'description' => 'Traditional Hindu wedding',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insert sample catering menus (price column removed)
        DB::table('catering_menus')->insertOrIgnore([
            [
                'id' => 1,
                'name' => 'Classic Menu',
                'description' => 'A classic wedding menu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Premium Menu',
                'description' => 'A premium wedding menu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
