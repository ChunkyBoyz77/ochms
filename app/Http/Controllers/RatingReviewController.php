<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingReviewController extends Controller
{
    public function create(Listing $listing)
    {
        abort_if(
            $listing->last_occupied_ocs_id !== Auth::user()->ocs->id,
            403
        );

        return view('manage_ratings_review.ocs-rating-review', compact('listing'));
    }

    public function store(Request $request, Listing $listing)
    {
        abort_if(
            $listing->last_occupied_ocs_id !== Auth::user()->ocs->id,
            403
        );

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'review' => ['nullable', 'string', 'max:2000'],
            'stay_from' => ['required', 'date'],
            'stay_until' => ['required', 'date', 'after_or_equal:stay_from'],
        ]);

        Review::create([
            'listing_id' => $listing->id,
            'ocs_id' => Auth::user()->ocs->id,
            'rating' => $validated['rating'],
            'review' => $validated['review'],
            'stay_from' => $validated['stay_from'],
            'stay_until' => $validated['stay_until'],
        ]);

        $exists = Review::where('listing_id', $listing->id)
            ->where('ocs_id', auth()->user()->ocs->id)
            ->exists();



        return redirect()
            ->route('ocs.bookings.index')
            ->with('success', 'Thank you for your review!');
    }

    /* ========================= */
    /* EDIT REVIEW               */
    /* ========================= */
    public function edit(Review $review)
    {
        abort_if(
            $review->ocs_id !== Auth::user()->ocs->id,
            403
        );

        $review->load('listing.images');

        return view(
            'manage_ratings_review.ocs-review-edit',
            [
                'review' => $review,
                'listing' => $review->listing,
            ]
        );
    }


    /* ========================= */
    /* UPDATE REVIEW             */
    /* ========================= */
    public function update(Request $request, Review $review)
    {
        abort_if(
            $review->ocs_id !== Auth::user()->ocs->id,
            403
        );

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'review' => ['nullable', 'string', 'max:2000'],
            'stay_from' => ['required', 'date'],
            'stay_until' => ['required', 'date', 'after_or_equal:stay_from'],
        ]);

        $review->update($validated);

        return redirect()
            ->route('ocs.bookings.index')
            ->with('success', 'Review updated successfully.');
    }


    /* ========================= */
    /* DELETE REVIEW             */
    /* ========================= */
    public function destroy(Review $review)
    {
        abort_if(
            $review->ocs_id !== Auth::user()->ocs->id,
            403
        );

        $review->delete();

        return redirect()
            ->route('ocs.bookings.index')
            ->with('success', 'Review deleted successfully.');
    }

    public function show(Listing $listing)
    {
        $listing->load([
            'images',
            'landlord.user',
            'reviews.ocs.user', // 👈 IMPORTANT
        ]);

        $reviews = $listing->reviews;

        $reviewCount = $reviews->count();
        $averageRating = $reviewCount > 0
            ? round($reviews->avg('rating'), 1)
            : 0;

        // Rating distribution (1–5 stars)
        $ratingBreakdown = collect([1,2,3,4,5])->mapWithKeys(function ($star) use ($reviews, $reviewCount) {
            $count = $reviews->where('rating', $star)->count();
            return [
                $star => $reviewCount
                    ? round(($count / $reviewCount) * 100)
                    : 0
            ];
        });

        return view('manage_rental_booking.landlord-property-details', compact(
            'listing',
            'reviews',
            'reviewCount',
            'averageRating',
            'ratingBreakdown'
        ));
    }

}
