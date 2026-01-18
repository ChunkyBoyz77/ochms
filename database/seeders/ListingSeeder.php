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
            'address' => 'No 12, Jalan Mentiga Jaya 1, Taman Mentiga Jaya, 26600 Pekan, Pahang',
            'lat' => 3.5463,
            'lng' => 103.4296,
            'distance' => 1.4,
        ],
        [
            'address' => 'No 8, Jalan Mentiga Jaya 3, Taman Mentiga Jaya, 26600 Pekan, Pahang',
            'lat' => 3.5459,
            'lng' => 103.4308,
            'distance' => 1.6,
        ],
        [
            'address' => 'No 25, Jalan LKNP 2, Taman LKNP, 26600 Pekan, Pahang',
            'lat' => 3.5435,
            'lng' => 103.4269,
            'distance' => 2.0,
        ],
        [
            'address' => 'No 14, Jalan LKNP 5, Taman LKNP, 26600 Pekan, Pahang',
            'lat' => 3.5442,
            'lng' => 103.4257,
            'distance' => 2.2,
        ],
        [
            'address' => 'No 6, Jalan Beruas Makmur Jaya 1, Pekan, Pahang',
            'lat' => 3.5398,
            'lng' => 103.4324,
            'distance' => 3.3,
        ],
        [
            'address' => 'No 18, Jalan Beruas Makmur Jaya 3, Pekan, Pahang',
            'lat' => 3.5389,
            'lng' => 103.4315,
            'distance' => 3.5,
        ],
        [
            'address' => 'No 33, Kampung Beruas, 26600 Pekan, Pahang',
            'lat' => 3.5372,
            'lng' => 103.4339,
            'distance' => 3.9,
        ],
        [
            'address' => 'No 51, Kampung Beruas Tengah, Pekan, Pahang',
            'lat' => 3.5366,
            'lng' => 103.4351,
            'distance' => 4.2,
        ],
        [
            'address' => 'No 7, Kampung Tegak, 26600 Pekan, Pahang',
            'lat' => 3.5491,
            'lng' => 103.4218,
            'distance' => 2.8,
        ],
        [
            'address' => 'No 19, Kampung Tegak Hilir, Pekan, Pahang',
            'lat' => 3.5503,
            'lng' => 103.4232,
            'distance' => 3.0,
        ],
    ];

        $amenitiesPool = [
            'WiFi',
            'Parking',
            'Air Conditioning',
            'Washing Machine',
            'Security',
            'Furnished',
        ];

        foreach (Landlord::all() as $landlord) {
            foreach ($locations as $loc) {

                $propertyType = fake()->randomElement([
                    'Room',
                    'Apartment',
                    'House'
                ]);

                //Property-type-aware defaults
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
                    'ocs_id' => null,
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
