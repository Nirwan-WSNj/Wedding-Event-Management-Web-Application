<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;

class UpdatePackagesSeeder extends Seeder
{
    public function run()
    {
        // Delete existing packages safely
        Package::query()->delete();
        
        $packages = [
            [
                'name' => 'Basic Package',
                'description' => 'Perfect for intimate weddings with essential services. Up to 100 guests with additional guest pricing at Rs. 2,500/person.',
                'price' => 300000.00,
                'image' => 'basic-package.jpg', // We'll handle image later
                'features' => [
                    'Poruwa decoration setup',
                    'Traditional oil lamp ceremony',
                    'Basic table decorations',
                    'Head table decoration',
                    'Standard entrance décor with floral touches',
                    'Complimentary welcome signage',
                    'Standard wedding menus',
                    'Basic selection of soft drinks',
                    'DJ entertainment (4 hours)'
                ],
                'highlight' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Golden Package',
                'description' => 'Elegant mid-range package with premium decorations and enhanced services. Up to 150 guests with additional guest pricing at Rs. 3,000/person.',
                'price' => 450000.00,
                'image' => 'golden-package.jpg', // We'll handle image later
                'features' => [
                    'Elegant altar or stage setup with themed décor',
                    'Customized vow or blessing ceremony',
                    'Elegant entrance decorations',
                    'Oil lamp with floral arrangements',
                    'Table decorations with centerpieces',
                    'Luxury head table decorations',
                    'Setty back decorations',
                    'Assorted bites (chicken, sausage, chickpea, mixture)',
                    'Selection of soft drinks (Coca-Cola, Sprite, Shandy)',
                    'Standard wedding menu',
                    'DJ music (6 hours)',
                    'Milk/champagne fountain',
                    'Jayamangala Gatha performance'
                ],
                'highlight' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Infinity Package',
                'description' => 'Our premium luxury package with unlimited features and VIP treatment. Up to 150 guests with flexible pricing for larger groups.',
                'price' => 450000.00,
                'image' => 'infinity-package.jpg', // We'll handle image later
                'features' => [
                    'Premium Poruwa setup with designer decorations',
                    'Ashtaka ceremony with traditional elements',
                    'Luxury entrance decorations with floral arrangements',
                    'Designer oil lamp with premium decorations',
                    'Elegant table decorations with custom centerpieces',
                    'VIP head table decorations',
                    'Premium setty back decorations',
                    'Unlimited premium bites (chicken, sausage, chickpea, boiled vegetables)',
                    'Unlimited beverages (Coca-Cola, Sprite, Soda, Shandy)',
                    'Luxury wedding buffet with chef\'s specialties',
                    'Professional DJ music (full event)',
                    'Milk/champagne fountain with lighting effects',
                    'Welcome dance performance',
                    'Jayamangala Gatha with traditional dancers'
                ],
                'highlight' => true, // This is marked as POPULAR
                'is_active' => true,
            ]
        ];

        foreach ($packages as $packageData) {
            Package::create($packageData);
        }
        
        echo "Successfully created " . count($packages) . " new wedding packages!\n";
    }
}