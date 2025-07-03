<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;

class RestorePackagesSeeder extends Seeder
{
    public function run()
    {
        // Delete existing packages safely (respects foreign key constraints)
        Package::query()->delete();
        
        $packages = [
            [
                'name' => 'Golden Elegance Package',
                'description' => 'Our premium wedding package featuring luxurious decorations, gourmet catering, and professional photography. Perfect for couples who want the ultimate wedding experience.',
                'price' => 750000.00,
                'features' => [
                    'Premium floral arrangements',
                    'Professional photography (8 hours)',
                    'Videography with drone shots',
                    'Gourmet 5-course dinner',
                    'Premium bar service',
                    'Wedding cake (3-tier)',
                    'Bridal suite for wedding night',
                    'Wedding coordinator',
                    'Live band performance',
                    'Luxury transportation'
                ],
                'highlight' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Silver Romance Package',
                'description' => 'A beautiful mid-range package that includes elegant decorations, quality catering, and essential photography services for your special day.',
                'price' => 450000.00,
                'features' => [
                    'Elegant floral decorations',
                    'Professional photography (6 hours)',
                    'Quality 4-course dinner',
                    'Standard bar service',
                    'Wedding cake (2-tier)',
                    'Basic sound system',
                    'Bridal preparation room',
                    'Wedding coordinator assistance'
                ],
                'highlight' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Bronze Classic Package',
                'description' => 'An affordable yet elegant package perfect for intimate weddings. Includes essential services to make your day memorable.',
                'price' => 250000.00,
                'features' => [
                    'Basic floral arrangements',
                    'Photography (4 hours)',
                    'Standard 3-course dinner',
                    'Basic beverage service',
                    'Simple wedding cake',
                    'Sound system rental',
                    'Basic decorations'
                ],
                'highlight' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Platinum Luxury Package',
                'description' => 'The ultimate luxury wedding experience with premium everything. No detail is overlooked in this exclusive package.',
                'price' => 1200000.00,
                'features' => [
                    'Designer floral arrangements',
                    'Professional photography & videography team',
                    'Luxury 7-course tasting menu',
                    'Premium champagne service',
                    'Custom wedding cake design',
                    'Live orchestra performance',
                    'Luxury bridal suite (2 nights)',
                    'Personal wedding planner',
                    'Fireworks display',
                    'Luxury car rental',
                    'Spa services for bride & groom',
                    'Welcome cocktail reception'
                ],
                'highlight' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Garden Party Package',
                'description' => 'Perfect for outdoor garden weddings with natural beauty and rustic charm. Includes weather contingency planning.',
                'price' => 350000.00,
                'features' => [
                    'Garden-style floral arrangements',
                    'Outdoor photography session',
                    'BBQ & garden party catering',
                    'Outdoor bar setup',
                    'Rustic wedding cake',
                    'Acoustic music setup',
                    'Weather protection tent',
                    'Garden lighting'
                ],
                'highlight' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Royal Heritage Package',
                'description' => 'Experience the grandeur of royal weddings with traditional elements and modern luxury combined.',
                'price' => 950000.00,
                'features' => [
                    'Traditional royal decorations',
                    'Heritage-style photography',
                    'Royal feast with traditional cuisine',
                    'Classical music ensemble',
                    'Royal wedding cake (4-tier)',
                    'Traditional ceremony setup',
                    'Royal carriage entrance',
                    'Heritage venue styling'
                ],
                'highlight' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Beach Paradise Package',
                'description' => 'A romantic beachside wedding package with ocean views and tropical ambiance.',
                'price' => 550000.00,
                'features' => [
                    'Beachside ceremony setup',
                    'Tropical floral arrangements',
                    'Sunset photography session',
                    'Seafood buffet dinner',
                    'Beach bar service',
                    'Tropical wedding cake',
                    'Beach bonfire reception',
                    'Ocean view accommodation'
                ],
                'highlight' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Intimate Celebration Package',
                'description' => 'Perfect for small, intimate weddings with close family and friends. Quality over quantity.',
                'price' => 180000.00,
                'features' => [
                    'Intimate venue decoration',
                    'Personal photographer (3 hours)',
                    'Gourmet dinner for small group',
                    'Personalized service',
                    'Custom small wedding cake',
                    'Acoustic music',
                    'Intimate lighting setup'
                ],
                'highlight' => false,
                'is_active' => true,
            ]
        ];

        foreach ($packages as $packageData) {
            Package::create($packageData);
        }
        
        echo "Successfully restored " . count($packages) . " wedding packages!\n";
    }
}