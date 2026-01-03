<?php

namespace Database\Seeders;

use App\Models\Listing;
use App\Models\Landlord;
use Illuminate\Database\Seeder;

class ListingSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            [
                'address' => 'Taman Universiti, Pekan, Pahang',
                'lat' => 3.5449,
                'lng' => 103.4281,
                'distance' => 0.8,
            ],
            [
                'address' => 'Taman Sri Mahkota, Pekan, Pahang',
                'lat' => 3.5518,
                'lng' => 103.4392,
                'distance' => 2.3,
            ],
            [
                'address' => 'Taman Bestari, Pekan, Pahang',
                'lat' => 3.5326,
                'lng' => 103.4171,
                'distance' => 4.9,
            ],
            [
                'address' => 'Bandar Pekan Lama, Pekan, Pahang',
                'lat' => 3.4939,
                'lng' => 103.3905,
                'distance' => 8.6,
            ],
            [
                'address' => 'Peramu Jaya, Pekan, Pahang',
                'lat' => 3.4632,
                'lng' => 103.4038,
                'distance' => 12.4,
            ],
        ];

        $amenitiesPool = [
            'WiFi',
            'Parking',
            'Air Conditioning',
            'Washing Machine',
            'Security',
            'Furnished',
            'Study Room',
            'CCTV',
        ];

        foreach (Landlord::all() as $landlord) {
            foreach ($locations as $loc) {

                $propertyType = fake()->randomElement([
                    'Room',
                    'Apartment',
                    'House'
                ]);

                // 🧠 Property-type-aware defaults
                if ($propertyType === 'Room') {
                    $bedrooms = 1;
                    $bathrooms = 1;
                    $beds = 1;
                    $maxOccupants = 1;
                    $rent = fake()->numberBetween(350, 550);
                } elseif ($propertyType === 'Apartment') {
                    $bedrooms = fake()->numberBetween(2, 3);
                    $bathrooms = fake()->numberBetween(1, 2);
                    $beds = fake()->numberBetween(2, 4);
                    $maxOccupants = fake()->numberBetween(2, 4);
                    $rent = fake()->numberBetween(600, 900);
                } else { // House
                    $bedrooms = fake()->numberBetween(3, 5);
                    $bathrooms = fake()->numberBetween(2, 3);
                    $beds = fake()->numberBetween(3, 6);
                    $maxOccupants = fake()->numberBetween(4, 6);
                    $rent = fake()->numberBetween(800, 1200);
                }

                Listing::create([
                    'landlord_id' => $landlord->id,
                    'ocs_id' => null, // ✅ keep null
                    'title' => fake()->randomElement([
                        'Fully Furnished Student '.$propertyType,
                        'Affordable '.$propertyType.' Near UMPSA',
                        'Modern '.$propertyType.' for Students',
                    ]),
                    'property_type' => $propertyType,
                    'bedrooms' => $bedrooms,
                    'bathrooms' => $bathrooms,
                    'beds' => $beds,
                    'max_occupants' => $maxOccupants,
                    'description' => fake()->paragraphs(3, true),
                    'address' => $loc['address'],
                    'latitude' => $loc['lat'],
                    'longitude' => $loc['lng'],
                    'distance_to_umpsa' => $loc['distance'],
                    'monthly_rent' => $rent,
                    'deposit' => fake()->numberBetween(300, 1000),
                    'amenities' => fake()->randomElements(
                        $amenitiesPool,
                        fake()->numberBetween(3, 6)
                    ),
                    'status' => 'published',
                    'published_at' => now()->subDays(
                        fake()->numberBetween(1, 30)
                    ),
                ]);
            }
        }
    }
}
