<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Listing;
use App\Models\Ocs;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $ocsIds = Ocs::pluck('id')->all();

        Listing::all()->each(function ($listing) use ($ocsIds) {

            // Randomly decide if this listing gets reviews
            if (rand(0, 1) === 0) {
                return;
            }

            // Random OCS (not necessarily last occupant)
            $ocsId = collect($ocsIds)->random();

            // Stay period
            $stayUntil = Carbon::now()->subDays(rand(10, 120));
            $stayFrom  = (clone $stayUntil)->subMonths(rand(3, 12));

            Review::updateOrCreate(
                [
                    'listing_id' => $listing->id,
                    'ocs_id'     => $ocsId,
                ],
                [
                    'rating'     => rand(3, 5),
                    'review'     => fake()->randomElement([
                        'Comfortable stay and responsive landlord.',
                        'Clean property and close to campus.',
                        'Good experience overall.',
                        'Affordable and well maintained.',
                        'Would recommend to other students.',
                    ]),
                    'stay_from'  => $stayFrom,
                    'stay_until' => $stayUntil,
                ]
            );
        });
    }
}
