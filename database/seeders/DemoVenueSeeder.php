<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DemoVenueSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

        foreach ([
            'booking_catering_items',
            'booking_additional_services',
            'booking_services',
            'booking_decorations',
            'booking_catering',
            'bookings',
            'catering_items',
            'catering_menus',
            'additional_services',
            'decorations',
            'wedding_types',
            'packages',
            'halls',
        ] as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $now = now();

        DB::table('halls')->insert([
            [
                'id' => 1,
                'name' => 'Jubilee Ballroom',
                'description' => 'A refined pillarless ballroom for elegant indoor weddings, engagement ceremonies, and premium receptions.',
                'capacity' => 200,
                'price' => 420000,
                'image' => 'halls/jubilee-ballroom.jpg',
                'features' => json_encode(['Pillarless ballroom', 'Victorian skylight', 'Bridal changing suite', 'Stage lighting', 'Premium AC']),
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'name' => 'Grand Ballroom',
                'description' => 'A luxury large-capacity ballroom with chandeliers, wide stage access, and full audio-visual support.',
                'capacity' => 500,
                'price' => 650000,
                'image' => 'halls/grand-ballroom.jpg',
                'features' => json_encode(['Grand stage', 'Crystal chandeliers', 'LED wall support', 'VIP lounge', 'Large dance floor']),
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'name' => 'Garden Pavilion',
                'description' => 'A romantic garden venue for sunset ceremonies, outdoor portraits, and semi-open wedding receptions.',
                'capacity' => 300,
                'price' => 520000,
                'image' => 'halls/garden-pavilion.jpg',
                'features' => json_encode(['Outdoor garden', 'Canopy setup', 'Photo corners', 'Ambient string lights', 'Rain backup area']),
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'name' => 'Royal Heritage Hall',
                'description' => 'A culturally styled wedding hall for Kandyan, low-country, Hindu, and traditional ceremonies.',
                'capacity' => 250,
                'price' => 480000,
                'image' => 'halls/royal-heritage-hall.jpg',
                'features' => json_encode(['Traditional entrance', 'Poruwa area', 'Cultural decor', 'Family lounge', 'Ceremonial lighting']),
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5,
                'name' => 'Riverside Garden',
                'description' => 'A calm riverside venue designed for intimate weddings, registry ceremonies, and private family receptions.',
                'capacity' => 150,
                'price' => 360000,
                'image' => 'halls/riverside-garden.jpg',
                'features' => json_encode(['Riverside backdrop', 'Private lawn', 'Intimate reception deck', 'Golden-hour portraits', 'Outdoor dining']),
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('packages')->insert([
            [
                'id' => 1,
                'name' => 'Infinity Package',
                'description' => 'Signature full-service package for luxury weddings with premium styling, menu coordination, and manager-guided workflow.',
                'price' => 1250000,
                'min_guests' => 150,
                'max_guests' => 500,
                'additional_guest_price' => 6500,
                'image' => 'packages/infinity-package.jpg',
                'features' => json_encode(['Dedicated event manager', 'Premium floral styling', 'Luxury head table', 'Full AV support', 'Complimentary couple suite']),
                'highlight' => true,
                'is_active' => true,
                'manager_approval_required' => true,
                'compatible_halls' => json_encode([1, 2, 3, 4]),
                'seasonal_pricing' => json_encode(['peak_markup_percent' => 12, 'off_peak_discount_percent' => 5]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'name' => 'Basic Package',
                'description' => 'Clean and elegant starter package for intimate weddings with essential venue, setup, and service support.',
                'price' => 450000,
                'min_guests' => 50,
                'max_guests' => 180,
                'additional_guest_price' => 3500,
                'image' => 'packages/basic-package.jpg',
                'features' => json_encode(['Venue setup', 'Standard head table', 'Basic sound system', 'Welcome table', 'Event coordinator support']),
                'highlight' => false,
                'is_active' => true,
                'manager_approval_required' => true,
                'compatible_halls' => json_encode([1, 4, 5]),
                'seasonal_pricing' => json_encode(['peak_markup_percent' => 8, 'off_peak_discount_percent' => 4]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'name' => 'Golden Package',
                'description' => 'Balanced premium package for modern Sri Lankan weddings with upgraded decor, catering options, and media support.',
                'price' => 780000,
                'min_guests' => 100,
                'max_guests' => 350,
                'additional_guest_price' => 4800,
                'image' => 'packages/golden-package.jpg',
                'features' => json_encode(['Enhanced decor theme', 'Premium buffet coordination', 'Photography desk support', 'Stage backdrop', 'Manager call approval']),
                'highlight' => false,
                'is_active' => true,
                'manager_approval_required' => true,
                'compatible_halls' => json_encode([1, 2, 3, 4]),
                'seasonal_pricing' => json_encode(['peak_markup_percent' => 10, 'off_peak_discount_percent' => 5]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('wedding_types')->insert([
            ['id' => 1, 'name' => 'Kandyan Wedding', 'description' => 'Traditional Kandyan ceremony with poruwa, cultural rituals, and heritage styling.', 'image' => 'wedding-types/kandyan.jpg', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'Low-Country Wedding', 'description' => 'Southern-style traditional wedding with elegant local customs and reception flow.', 'image' => 'wedding-types/low-country.jpg', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'European Wedding', 'description' => 'Modern white-wedding setup with aisle ceremony, floral arch, and reception styling.', 'image' => 'wedding-types/european.jpg', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => 'Indian Wedding', 'description' => 'Colorful multi-ritual wedding flow with mandap styling and extended celebration support.', 'image' => 'wedding-types/indian.jpg', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'name' => 'Catholic Wedding', 'description' => 'Church and reception-focused wedding flow with day-one and day-two scheduling support.', 'image' => 'wedding-types/catholic.jpg', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('decorations')->insert([
            ['id' => 1, 'name' => 'Ivory Floral Poruwa', 'description' => 'Classic ivory poruwa with jasmine, orchid, and brass-lamp details.', 'price' => 85000, 'image' => 'decorations/ivory-floral-poruwa.jpg', 'style' => 'traditional', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'Golden Stage Backdrop', 'description' => 'Gold-accented reception stage with layered drapery and warm lighting.', 'price' => 120000, 'image' => 'decorations/golden-stage-backdrop.jpg', 'style' => 'luxury', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'Garden Aisle Styling', 'description' => 'Outdoor aisle with lanterns, petal pathway, and floral arch.', 'price' => 95000, 'image' => 'decorations/garden-aisle-styling.jpg', 'style' => 'outdoor', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => 'Crystal Head Table', 'description' => 'Premium couple table styling with crystal accents and fresh flowers.', 'price' => 65000, 'image' => 'decorations/crystal-head-table.jpg', 'style' => 'modern', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('catering_menus')->insert([
            ['id' => 1, 'name' => 'Classic Sri Lankan Buffet', 'description' => 'Balanced Sri Lankan wedding buffet with rice, curries, salads, dessert, and beverages.', 'price_per_person' => 3200, 'is_active' => true, 'minimum_guests' => 50, 'maximum_guests' => 250, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'Premium Fusion Menu', 'description' => 'Sri Lankan and Western fusion buffet for premium reception dining.', 'price_per_person' => 4800, 'is_active' => true, 'minimum_guests' => 100, 'maximum_guests' => 400, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'Luxury Banquet Menu', 'description' => 'High-end banquet selection with live stations, desserts, and mocktail service.', 'price_per_person' => 6500, 'is_active' => true, 'minimum_guests' => 150, 'maximum_guests' => 500, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => 'Vegetarian Celebration Menu', 'description' => 'Vegetarian-focused wedding menu with South Asian and Sri Lankan selections.', 'price_per_person' => 3600, 'is_active' => true, 'minimum_guests' => 50, 'maximum_guests' => 300, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'name' => 'Intimate Garden Menu', 'description' => 'Light garden-party dining for small outdoor weddings and registry celebrations.', 'price_per_person' => 2800, 'is_active' => true, 'minimum_guests' => 20, 'maximum_guests' => 150, 'created_at' => $now, 'updated_at' => $now],
        ]);

        $items = [
            [1, 'Welcome Passion Fruit Mocktail', 'beverage', 'Fresh welcome drink for arriving guests.', ['vegetarian']],
            [1, 'Yellow Rice with Cashew', 'main_course', 'Fragrant yellow rice served with roasted cashew garnish.', ['vegetarian']],
            [1, 'Chicken Black Curry', 'main_course', 'Traditional Sri Lankan black curry with rich spice notes.', ['halal']],
            [1, 'Watalappan', 'dessert', 'Classic coconut-jaggery dessert for wedding buffets.', ['vegetarian']],
            [2, 'Seafood Cocktail Starter', 'appetizer', 'Chilled seafood starter with citrus dressing.', []],
            [2, 'Herb Roast Chicken', 'main_course', 'Western-style roast chicken with gravy and seasonal vegetables.', ['halal']],
            [2, 'Pasta Live Station', 'special', 'Chef-attended pasta station with vegetarian and chicken options.', ['vegetarian', 'halal']],
            [2, 'Chocolate Mousse Cups', 'dessert', 'Individual chocolate mousse dessert cups.', ['vegetarian']],
            [3, 'Mocktail Bar Service', 'beverage', 'Premium non-alcoholic mocktail station for reception guests.', ['vegetarian']],
            [3, 'Carving Station', 'special', 'Live carving station with premium roast selection.', ['halal']],
            [3, 'Mini Dessert Platter', 'dessert', 'Assorted mini desserts for luxury banquets.', ['vegetarian']],
            [4, 'Paneer Tikka', 'appetizer', 'Vegetarian paneer starter with mild spice marinade.', ['vegetarian']],
            [4, 'Vegetable Biryani', 'main_course', 'Festive vegetarian biryani with raita and chutney.', ['vegetarian']],
            [5, 'Garden Canapes', 'appetizer', 'Light canapes suitable for outdoor receptions.', ['vegetarian']],
            [5, 'Fresh Fruit Dessert Cups', 'dessert', 'Seasonal fruit cups for intimate garden events.', ['vegetarian', 'vegan']],
        ];

        foreach ($items as $index => [$menuId, $name, $category, $description, $dietary]) {
            DB::table('catering_items')->insert([
                'id' => $index + 1,
                'menu_id' => $menuId,
                'name' => $name,
                'description' => $description,
                'category' => $category,
                'dietary_info' => json_encode($dietary),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        DB::table('additional_services')->insert([
            ['id' => 1, 'name' => 'Guest Parking Coordination', 'description' => 'Managed parking and guest direction support.', 'price' => 25000, 'image' => 'services/parking.jpg', 'type' => 'compulsory', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'Basic Sound System', 'description' => 'Microphones, speakers, and basic audio support.', 'price' => 45000, 'image' => 'services/basic-sound.jpg', 'type' => 'compulsory', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'Photography Location Support', 'description' => 'Reserved venue photo locations and support staff.', 'price' => 30000, 'image' => 'services/photo-location.jpg', 'type' => 'optional', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => 'Live Band', 'description' => 'Three-hour live band performance for reception.', 'price' => 180000, 'image' => 'services/live-band.jpg', 'type' => 'paid', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'name' => 'Cultural Dancers', 'description' => 'Traditional welcome and ceremony dance performance.', 'price' => 95000, 'image' => 'services/cultural-dancers.jpg', 'type' => 'paid', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'name' => 'Full Photo and Video Package', 'description' => 'Full-day photography, cinematography, and highlight film.', 'price' => 260000, 'image' => 'services/photo-video.jpg', 'type' => 'paid', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'name' => 'Multimedia LED Wall', 'description' => 'LED wall setup for couple entrance, live feed, and memories.', 'price' => 150000, 'image' => 'services/led-wall.jpg', 'type' => 'paid', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 8, 'name' => 'Fireworks Exit Moment', 'description' => 'Controlled outdoor sparkle/firework send-off moment where permitted.', 'price' => 110000, 'image' => 'services/fireworks.jpg', 'type' => 'paid', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
