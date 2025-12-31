<?php

namespace App\Services;

use App\Models\Listing;

class ListingBadgeService
{
    public static function resolve(Listing $listing): array
    {
        $badges = [];

        $amenities = $listing->amenities ?? [];

        /* =========================
           PHASE 1: STRUCTURED RULES
        ========================= */
        if ($listing->status === 'published') {
            $badges[] = self::badge(
                'Verified Listing',
                'green',
                'fa-circle-check'
            );
        }

        if ($listing->distance_to_umpsa !== null) {
            if ($listing->distance_to_umpsa <= 1) {
                $badges[] = self::badge(
                    'Walking Distance to UMPSA',
                    'green',
                    'fa-person-walking'
                );
            } elseif ($listing->distance_to_umpsa <= 3) {
                $badges[] = self::badge(
                    'Near UMPSA',
                    'green',
                    'fa-road'
                );
            }
        }

        if (in_array('WiFi', $amenities)) {
            $badges[] = self::badge('WiFi Included');
        }

        if (in_array('Furnished', $amenities)) {
            $badges[] = self::badge('Fully Furnished');
        }

        if (in_array('Air Conditioning', $amenities)) {
            $badges[] = self::badge('Air-Conditioned');
        }

        if (in_array('Parking', $amenities)) {
            $badges[] = self::badge('Parking Available');
        }

        if (in_array('Security', $amenities)) {
            $badges[] = self::badge('Secure Property');
        }

        if ($listing->property_type === 'Room') {
            $badges[] = self::badge('Student Room');
        }

        

        

        /* =========================
           PHASE 2: POLICY HEURISTICS
        ========================= */

        $policyText = strtolower(implode(' ', array_filter([
            $listing->policy_additional,
            $listing->policy_refund,
            $listing->policy_cancellation,
            $listing->policy_late_payment,
        ])));

        if (str_contains($policyText, 'pet')) {
            $badges[] = self::badge('Pet Friendly');
        }

        if (str_contains($policyText, 'no pet')) {
            $badges[] = self::badge('No Pets Allowed');
        }

        if (str_contains($policyText, 'student')) {
            $badges[] = self::badge('Student Preferred');
        }

        if (str_contains($policyText, 'smoking') && str_contains($policyText, 'no')) {
            $badges[] = self::badge('No Smoking');
        }

        if (str_contains($policyText, 'family')) {
            $badges[] = self::badge('Family Friendly');
        }

        return self::unique($badges);
    }

    private static function badge(
        string $label,
        string $style = 'gray',
        ?string $icon = null
    ): array {
        return compact('label', 'style', 'icon');
    }


    private static function unique(array $badges): array
    {
        return collect($badges)
            ->unique('label')
            ->values()
            ->toArray();
    }
}
