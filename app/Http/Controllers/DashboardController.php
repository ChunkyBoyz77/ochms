<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function landlordIndex()
    {
        $landlord = Auth::user()->landlord;

        /* =========================
        STATS
        ========================= */
        $totalProperties = Listing::where('landlord_id', $landlord->id)->count();

        $activeListings = Listing::where('landlord_id', $landlord->id)
            ->where('status', 'published')
            ->count();

        $pendingRequests = Listing::where('landlord_id', $landlord->id)
            ->where('status', 'requested')
            ->count();

        $averageRating = Review::whereHas('listing', fn ($q) =>
            $q->where('landlord_id', $landlord->id)
        )->avg('rating');

        $averageRating = $averageRating
            ? number_format($averageRating, 1)
            : null;

        /* =========================
        ACTIVITY FEED
        ========================= */

        // 📩 Booking Requests
        $bookingActivities = Listing::where('landlord_id', $landlord->id)
            ->where('status', 'requested')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($listing) => [
                'type' => 'booking',
                'title' => 'New booking request',
                'subtitle' => $listing->title,
                'created_at' => $listing->updated_at,
            ]);

        //Reviews
        $reviewActivities = Review::whereHas('listing', fn ($q) =>
                $q->where('landlord_id', $landlord->id)
            )
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($review) => [
                'type' => 'review',
                'title' => "New review ({$review->rating}/5)",
                'subtitle' => $review->listing->title,
                'created_at' => $review->created_at,
            ]);

        //Merge & sort
        $activities = collect()
            ->merge($bookingActivities)
            ->merge($reviewActivities)
            ->sortByDesc('created_at')
            ->take(6);

        return view('auth.landlord-auth.landlord-dashboard', compact(
            'totalProperties',
            'activeListings',
            'pendingRequests',
            'averageRating',
            'activities'
        ));
    }
}
