<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\ListingImage;
use App\Models\Review;
use App\Services\ListingBadgeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingApprovedMail;
use App\Mail\BookingRejectedMail;
use Illuminate\Support\Facades\DB;

class RentalBookingController extends Controller
{
    public function saveListingDraft(Request $request)
    {
        $section = $request->input('section');

        $draft = session()->get('listing_draft', []);

        switch ($section) {

            case 'basic':
            case 'location':
            case 'pricing':
            case 'rules':
                $draft[$section] = $request->except(['_token', 'section']);
                break;

            case 'media':
                $media = [];

                // Property photos
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $file) {
                        $media['images'][] = [
                            'original_name' => $file->getClientOriginalName(),
                            'temp_path' => $file->store('draft/listings/images', 'public'),
                        ];
                    }
                }

                // Grant / document
                if ($request->hasFile('grant')) {
                    $media['grant'] = [
                        'original_name' => $request->file('grant')->getClientOriginalName(),
                        'temp_path' => $request->file('grant')->store('draft/listings/grants', 'public'),
                    ];
                }

                $draft['media'] = $media;
                break;
        }

        session()->put('listing_draft', $draft);

        return response()->json([
            'message' => 'Draft saved',
            'draft' => $draft,
        ]);
    }



    /* ===================================================== */
    /* LANDLORD: CREATE PROPERTY LISTING                     */
    /* ===================================================== */

public function createListing(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | 1. Validate NON-media fields only
    |--------------------------------------------------------------------------
    */
    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'property_type' => ['required', 'in:Room,Apartment,House'],
        'bedrooms' => ['required', 'integer', 'min:0'],
        'bathrooms' => ['required', 'integer', 'min:0'],
        'beds' => ['required', 'integer', 'min:0'],
        'max_occupants' => ['required', 'integer', 'min:1'],
        'description' => ['required', 'string'],
        'address' => ['required', 'string'],

        'latitude' => ['nullable', 'numeric'],
        'longitude' => ['nullable', 'numeric'],
        'distance_to_umpsa' => ['nullable', 'numeric'],

        'monthly_rent' => ['required', 'numeric', 'min:0'],
        'deposit' => ['nullable', 'numeric', 'min:0'],

        'amenities' => ['nullable', 'array'],

        'policy_cancellation' => ['nullable', 'string'],
        'policy_refund' => ['nullable', 'string'],
        'policy_early_movein' => ['nullable', 'string'],
        'policy_late_payment' => ['nullable', 'string'],
        'policy_additional' => ['nullable', 'string'],
    ], [
        //Error messages
        'title.required' => 'Listing title is required.',
        'title.max' => 'Listing title cannot exceed 255 characters.',

        'property_type.required' => 'Please select a property type.',
        'property_type.in' => 'Invalid property type selected.',

        'description.required' => 'Please provide a description of the property.',


        'bedrooms.required' => 'Number of bedrooms is required.',
        'bedrooms.integer' => 'Bedrooms must be a whole number.',
        'bedrooms.min' => 'Bedrooms cannot be negative.',

        'bathrooms.required' => 'Number of bathrooms is required.',
        'bathrooms.integer' => 'Bathrooms must be a whole number.',
        'bathrooms.min' => 'Bathrooms cannot be negative.',

        'beds.required' => 'Number of beds is required.',
        'beds.integer' => 'Beds must be a whole number.',
        'beds.min' => 'Beds cannot be negative.',

        'max_occupants.required' => 'Maximum number of occupants is required.',
        'max_occupants.integer' => 'Maximum occupants must be a whole number.',
        'max_occupants.min' => 'There must be at least one occupant.',

        'address.required' => 'Property address is required.',
        'latitude.numeric' => 'Invalid latitude value.',
        'longitude.numeric' => 'Invalid longitude value.',
        'distance_to_umpsa.numeric' => 'Invalid distance value.',

        'monthly_rent.required' => 'Monthly rent is required.',
        'monthly_rent.numeric' => 'Monthly rent must be a valid number.',
        'monthly_rent.min' => 'Monthly rent cannot be negative.',

        'deposit.numeric' => 'Deposit must be a valid number.',
        'deposit.min' => 'Deposit cannot be negative.',
        'deposit.max' => 'Deposit cannot exceed 200% of monthly rent.',

        'amenities.array' => 'Invalid amenities selection.',

        'policy_cancellation.string' => 'Cancellation policy must be valid text.',
        'policy_refund.string' => 'Refund policy must be valid text.',
        'policy_early_movein.string' => 'Early move-in policy must be valid text.',
        'policy_late_payment.string' => 'Late payment policy must be valid text.',
        'policy_additional.string' => 'Additional rules must be valid text.',
    ]);

    if ($validated['property_type'] === 'Room') {
        $validated['bedrooms'] = 1;
        $validated['beds'] = 1;
        $validated['max_occupants'] = 1;
    }

    /*
    |--------------------------------------------------------------------------
    | 2. Create Listing (no media yet)
    |--------------------------------------------------------------------------
    */
    $listing = Listing::create([
        'landlord_id' => Auth::user()->landlord->id,
        'student_id' => null,
        'title' => $validated['title'],
        'property_type' => $validated['property_type'],
        'bedrooms' => $validated['bedrooms'],
        'bathrooms' => $validated['bathrooms'],
        'beds' => $validated['beds'],
        'max_occupants' => $validated['max_occupants'],
        'description' => $validated['description'],
        'address' => $validated['address'],
        'latitude' => $validated['latitude'] ?? null,
        'longitude' => $validated['longitude'] ?? null,
        'distance_to_umpsa' => $validated['distance_to_umpsa'] ?? null,
        'monthly_rent' => $validated['monthly_rent'],
        'deposit' => $validated['deposit'] ?? null,
        'amenities' => $validated['amenities'] ?? [],
        'policy_cancellation' => $validated['policy_cancellation'] ?? null,
        'policy_refund' => $validated['policy_refund'] ?? null,
        'policy_early_movein' => $validated['policy_early_movein'] ?? null,
        'policy_late_payment' => $validated['policy_late_payment'] ?? null,
        'policy_additional' => $validated['policy_additional'] ?? null,
        'status' => 'pending',
    ]);

     
    /*
    |--------------------------------------------------------------------------
    | 3. Move MEDIA from session draft → permanent storage
    |--------------------------------------------------------------------------
    */
    $draft = session('listing_draft');

    if (!empty($draft['media'])) {
        
        /* ================= IMAGES ================= */
        if (!empty($draft['media']['images'])) {
            foreach ($draft['media']['images'] as $index => $img) {

                $oldPath = $img['temp_path'];

                if (!Storage::disk('public')->exists($oldPath)) {
                    continue;
                }

                $newPath = str_replace(
                    'draft/listings/images',
                    "listings/{$listing->id}/images",
                    $oldPath
                );

                Storage::disk('public')->move($oldPath, $newPath);

                $listing->images()->create([
                    'image_path' => $newPath,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }
    }

    /* ================= DOCUMENT ================= */
    if (!empty($draft['media']['grant'])) {

    $oldPath = $draft['media']['grant']['temp_path'];

    if (Storage::disk('public')->exists($oldPath)) {

        $newPath = str_replace(
            'draft/listings/grants',
            "listings/{$listing->id}/documents",
            $oldPath
        );

        Storage::disk('public')->move($oldPath, $newPath);

        $listing->update([
            'grant_document_path' => $newPath,
        ]);
    }
}


    /*
    |--------------------------------------------------------------------------
    | 4. Cleanup draft session
    |--------------------------------------------------------------------------
    */
    session()->forget('listing_draft');

    /*
    |--------------------------------------------------------------------------
    | 5. Redirect
    |--------------------------------------------------------------------------
    */
    return redirect()
        ->route('landlord.listings')
        ->with('success', 'Listing submitted for review.');
}

public function landlordListings()
{
    $landlordId = Auth::user()->landlord->id;

    $pendingListings = Listing::with(['images'])
        ->where('landlord_id', $landlordId)
        ->where('status', 'pending')
        ->latest()
        ->get()
        ->map(function ($listing) {
            $listing->badges = ListingBadgeService::resolve($listing);
            return $listing;
        })
        ->each(function ($listing) {
            $listing->avg_rating = round($listing->reviews->avg('rating'), 1);
            $listing->reviews_count = $listing->reviews->count();
        });

    $allListings = Listing::with(['images'])
        ->where('landlord_id', $landlordId)
        ->whereNotIn('status', ['pending'])
        ->latest()
        ->get()
        ->map(function ($listing) {
            $listing->badges = ListingBadgeService::resolve($listing);
            return $listing;
        })
        ->each(function ($listing) {
            $listing->avg_rating = round($listing->reviews->avg('rating'), 1);
            $listing->reviews_count = $listing->reviews->count();
        });

    return view('manage_rental_booking.landlord-rentallist', compact(
        'pendingListings',
        'allListings'
    ));
}

public function show(Listing $listing)
{
    abort_if($listing->landlord_id !== Auth::user()->landlord->id, 403);

    $listing->load([
        'images',
        'reviews.ocs.user', // ✅ THIS WAS MISSING
    ]);

    $reviews = $listing->reviews;
    
    $listing->badges = \App\Services\ListingBadgeService::resolve($listing);
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

public function showAllMedias(Listing $listing)
{
    abort_if($listing->landlord_id !==  Auth::user()->landlord->id, 403);

    return view(
        'manage_rental_booking.landlord-listing-medias',
        compact('listing')
    );
}

public function update(Request $request, Listing $listing)
{
    // ✅ Ownership check (simple & explicit)
    if ($listing->landlord_id !== Auth::user()->landlord->id) {
        abort(403);
    }

    /*
    |--------------------------------------------------------------------------
    | 1. Validate NON-media fields only (same as store)
    |--------------------------------------------------------------------------
    */
    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'property_type' => ['required', 'in:Room,Apartment,House'],
        'bedrooms' => ['required', 'integer', 'min:0'],
        'bathrooms' => ['required', 'integer', 'min:0'],
        'beds' => ['required', 'integer', 'min:0'],
        'max_occupants' => ['required', 'integer', 'min:1'],

        'description' => ['required', 'string'],
        'address' => ['required', 'string'],

        'latitude' => ['nullable', 'numeric'],
        'longitude' => ['nullable', 'numeric'],
        'distance_to_umpsa' => ['nullable', 'numeric'],

        'monthly_rent' => ['required', 'numeric', 'min:0'],
        'deposit' => ['nullable', 'numeric', 'min:0'],

        'amenities' => ['nullable', 'array'],

        'policy_cancellation' => ['nullable', 'string'],
        'policy_refund' => ['nullable', 'string'],
        'policy_early_movein' => ['nullable', 'string'],
        'policy_late_payment' => ['nullable', 'string'],
        'policy_additional' => ['nullable', 'string'],
    ]);

    /*
    |--------------------------------------------------------------------------
    | 2. Update listing fields (NO media yet)
    |--------------------------------------------------------------------------
    */
    $listing->update([
        'title' => $validated['title'],
        'property_type' => $validated['property_type'],
        'bedrooms' => $validated['bedrooms'],
        'bathrooms' => $validated['bathrooms'],
        'beds' => $validated['beds'],
        'max_occupants' => $validated['max_occupants'],
        'description' => $validated['description'],
        'address' => $validated['address'],
        'latitude' => $validated['latitude'] ?? null,
        'longitude' => $validated['longitude'] ?? null,
        'distance_to_umpsa' => $validated['distance_to_umpsa'] ?? null,
        'monthly_rent' => $validated['monthly_rent'],
        'deposit' => $validated['deposit'] ?? null,
        'amenities' => $validated['amenities'] ?? [],
        'policy_cancellation' => $validated['policy_cancellation'] ?? null,
        'policy_refund' => $validated['policy_refund'] ?? null,
        'policy_early_movein' => $validated['policy_early_movein'] ?? null,
        'policy_late_payment' => $validated['policy_late_payment'] ?? null,
        'policy_additional' => $validated['policy_additional'] ?? null,
    ]);

    /*
    |--------------------------------------------------------------------------
    | 3. Move MEDIA from session draft → permanent storage
    |--------------------------------------------------------------------------
    */
    $draft = session('listing_draft');

    if (!empty($draft['media']['images'])) {
        foreach ($draft['media']['images'] as $index => $img) {

            $oldPath = $img['temp_path'];

            if (!Storage::disk('public')->exists($oldPath)) {
                continue;
            }

            $newPath = str_replace(
                'draft/listings/images',
                "listings/{$listing->id}/images",
                $oldPath
            );

            Storage::disk('public')->move($oldPath, $newPath);

            $listing->images()->create([
                'image_path' => $newPath,
                'is_primary' => false,
                'sort_order' => $listing->images()->count(),
            ]);
        }
    }

    if (!empty($draft['media']['grant'])) {

        $oldPath = $draft['media']['grant']['temp_path'];

        if (Storage::disk('public')->exists($oldPath)) {

            $newPath = str_replace(
                'draft/listings/grants',
                "listings/{$listing->id}/documents",
                $oldPath
            );

            Storage::disk('public')->move($oldPath, $newPath);

            $listing->update([
                'grant_document_path' => $newPath,
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 4. Cleanup draft session
    |--------------------------------------------------------------------------
    */
    session()->forget('listing_draft');

    /*
    |--------------------------------------------------------------------------
    | 5. Redirect
    |--------------------------------------------------------------------------
    */
    return redirect()
        ->route('landlord.listings')
        ->with('success', 'Listing updated successfully.');
}

public function deleteImage($id)
{
    $image = ListingImage::findOrFail($id);

    // Ownership check
    if ($image->listing->landlord_id !==  Auth::user()->landlord->id) {
        abort(403);
    }

    // Delete file
    if (Storage::disk('public')->exists($image->image_path)) {
        Storage::disk('public')->delete($image->image_path);
    }

    // Delete DB record
    $image->delete();

    return response()->json(['success' => true]);
}

public function deleteGrant(Listing $listing)
{
    if ($listing->landlord_id !==  Auth::user()->landlord->id) {
        abort(403);
    }

    if ($listing->grant_document_path &&
        Storage::disk('public')->exists($listing->grant_document_path)) {
        Storage::disk('public')->delete($listing->grant_document_path);
    }

    $listing->update([
        'grant_document_path' => null
    ]);

    return response()->json(['success' => true]);
}



public function edit(Listing $listing)
{
    // ✅ Simple ownership check (same pattern as show)
    abort_if($listing->landlord_id !==  Auth::user()->landlord->id, 403);

    return view('manage_rental_booking.landlord-edit-listing', [
        'listing' => $listing,
    ]);
}



public function destroy(Listing $listing)
{
    if ($listing->landlord_id !==  Auth::user()->landlord->id) {
        abort(403);
    }

    $listing->delete();

    return redirect()
        ->route('landlord.listings')
        ->with('success', 'Listing deleted successfully.');
}

public function adminListingShow(Listing $listing)
{
    // Only pending listings can be reviewed
    if ($listing->status !== 'pending') {
        abort(404);
    }

    // Load relations needed by the view
    $listing->load([
        'images',
        'landlord.user',
    ]);

    return view(
        'manage_rental_booking.admin-review-listing',
        compact('listing')
    );
}

public function adminListingApprove(Listing $listing)
{
    if ($listing->status !== 'pending') {
        abort(400, 'Listing is not pending approval.');
    }

    $listing->update([
        'status' => 'published',
        'published_at' => now(),
    ]);

    return redirect()
        ->route('admin.verifications.index')
        ->with('success', 'Listing has been approved and published.');
}

public function adminListingReject(Request $request, Listing $listing)
{
    if ($listing->status !== 'pending') {
        abort(400, 'Listing is not pending approval.');
    }

    $request->validate([
        'reason' => ['nullable', 'string', 'max:1000'],
    ]);

    $listing->update([
        'status' => 'rejected',
    ]);

    return redirect()
        ->route('admin.verifications.index')
        ->with('success', 'Listing has been rejected.');
}


public function browse(Request $request, ?string $area = null)
{
    $umpsaLat = 3.5435;
    $umpsaLng = 103.4289;

    // ✅ Always define areaName (prevents undefined variable issues)
    $areaName = 'All Areas';

    // 1️⃣ Base query — ALWAYS all published listings
    $query = Listing::query()
        ->where('status', 'published')
        ->whereNotNull('latitude')
        ->whereNotNull('longitude');

    // 2️⃣ Optional: area from URL
    if ($area) {
        $areaName = \Str::title(str_replace('-', ' ', $area));
        $query->where('address', 'LIKE', "%{$areaName}%");
    }

    // 3️⃣ Optional: search bar
    if ($request->filled('q')) {
        $search = $request->q;
        $query->where(function ($q) use ($search) {
            $q->where('title', 'LIKE', "%{$search}%")
              ->orWhere('address', 'LIKE', "%{$search}%");
        });
    }

    // 4️⃣ Optional: property type filter
    if ($request->filled('type')) {
        $query->whereIn('property_type', (array) $request->type);
    }

    // 5️⃣ Optional: amenities filter
    if ($request->filled('amenities')) {
        foreach ((array) $request->amenities as $amenity) {
            $query->whereJsonContains('amenities', $amenity);
        }
    }

    // 6️⃣ Optional: price filter
    if ($request->filled('max_price')) {
        $query->where('monthly_rent', '<=', $request->max_price);
    }

    // 7️⃣ Distance calculation (Haversine)
    $query->select('*')->selectRaw("
        6371 * acos(
            cos(radians(?)) *
            cos(radians(latitude)) *
            cos(radians(longitude) - radians(?)) +
            sin(radians(?)) *
            sin(radians(latitude))
        ) AS distance_to_umpsa
    ", [$umpsaLat, $umpsaLng, $umpsaLat]);

    $query->orderBy('distance_to_umpsa');

    // 8️⃣ Pagination
    $listings = $query
        ->with(['images'])
        ->withAvg('reviews', 'rating')
        ->withCount('reviews')
        ->where('status', 'published')
        ->paginate(6)
        ->withQueryString();

    // ✅ 9️⃣ ATTACH BADGES (THIS IS THE FIX)
    $listings->getCollection()->transform(function ($listing) {
        $listing->badges = \App\Services\ListingBadgeService::resolve($listing);
        return $listing;
    });

    return view('manage_rental_booking.ocs-browse-listing', [
        'listings' => $listings,
        'areaName' => $areaName,
    ]);
}



public function ocsShow(Listing $listing)
{
    abort_if($listing->status !== 'published', 404);

    $listing->load([
        'images',
        'landlord.user',
        'reviews.ocs.user',
    ]);

    $reviews = $listing->reviews;

    $listing->badges = \App\Services\ListingBadgeService::resolve($listing);
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

    return view('manage_rental_booking.ocs-property-details', compact(
        'listing',
        'reviews',
        'reviewCount',
        'averageRating',
        'ratingBreakdown'
    ));
}

public function browseMap(Request $request)
{
    $umpsaLat = 3.5435;
    $umpsaLng = 103.4289;

    $areaName = 'All Areas';

    // Copy the exact same query logic from browse()
    $query = Listing::query()
        ->where('status', 'published')
        ->whereNotNull('latitude')
        ->whereNotNull('longitude');

    if ($request->filled('q')) {
        $search = $request->q;
        $query->where(function ($q) use ($search) {
            $q->where('title', 'LIKE', "%{$search}%")
              ->orWhere('address', 'LIKE', "%{$search}%");
        });
    }

    if ($request->filled('type')) {
        $query->whereIn('property_type', (array) $request->type);
    }

    if ($request->filled('amenities')) {
        foreach ((array) $request->amenities as $amenity) {
            $query->whereJsonContains('amenities', $amenity);
        }
    }

    if ($request->filled('max_price')) {
        $query->where('monthly_rent', '<=', $request->max_price);
    }

    $query->select('*')->selectRaw("
        6371 * acos(
            cos(radians(?)) *
            cos(radians(latitude)) *
            cos(radians(longitude) - radians(?)) +
            sin(radians(?)) *
            sin(radians(latitude))
        ) AS distance_to_umpsa
    ", [$umpsaLat, $umpsaLng, $umpsaLat]);

    $query->orderBy('distance_to_umpsa');

    // Get ALL results (not paginated)
    $listings = $query
        ->with(['images'])
        ->withAvg('reviews', 'rating')
        ->withCount('reviews')
        ->get();

    // Attach badges
    $listings->transform(function ($listing) {
        $listing->badges = \App\Services\ListingBadgeService::resolve($listing);
        return $listing;
    });

    return view('manage_rental_booking.ocs-browse-map', [
        'listings' => $listings,
        'areaName' => $areaName,
    ]);
}

public function showAllMediasOcs(Listing $listing)
{
    return view(
        'manage_rental_booking.ocs-listing-medias',
        compact('listing')
    );
}




    /* ===================================================== */
    /* STUDENT: REQUEST BOOKING (future)                     */
    /* ===================================================== */

    public function requestBooking(Request $request, Listing $listing)
    {
        abort_if($listing->status !== 'published', 403);

        $user = Auth::user();
        abort_if(!$user || !$user->ocs, 403);

        $ocsId = $user->ocs->id;

        //Check if this OCS already has an active request
        $hasActiveRequest = Listing::where('ocs_id', $ocsId)
            ->whereIn('status', ['requested', 'occupied'])
            ->exists();

        if ($hasActiveRequest) {
            return redirect()
                ->route('ocs.listings.browse', $listing)
                ->with('success', 'Booking request submitted successfully.');
        }

        $listing->update([
            'status' => 'requested',
            'ocs_id' => $ocsId,
        ]);

        return redirect()
            ->route('ocs.listings.browse', $listing)
            ->with('success', 'Booking request submitted successfully.');
    }


    public function landlordBookingRequests()
{
    $landlordId = Auth::user()->landlord->id;

    $requests = Listing::with([
            'images',
            'ocs.user',
        ])
        ->where('landlord_id', $landlordId)
        ->where('status', 'requested')
        ->latest()
        ->get();

    return view(
        'manage_rental_booking.landlord-request-index',
        compact('requests')
    );
}


/* ===================================================== */
/* LANDLORD: VIEW SINGLE BOOKING REQUEST                 */
/* ===================================================== */

public function landlordBookingShow(Listing $listing)
{
    abort_if(
        $listing->landlord_id !== Auth::user()->landlord->id,
        403
    );

    abort_if($listing->status !== 'requested', 404);

    $listing->load([
        'images',
        'ocs.user',
    ]);

    return view(
        'manage_rental_booking.landlord-review-request',
        compact('listing')
    );
}


/* ===================================================== */
/* LANDLORD: APPROVE BOOKING                             */
/* ===================================================== */

public function approveBooking(Listing $listing)
{
    abort_if(
        $listing->landlord_id !== Auth::user()->landlord->id,
        403
    );

    abort_if($listing->status !== 'requested', 400);

    

    $listing->update([
        'status' => 'occupied',
    ]);

     Mail::to($listing->ocs->user->email)
        ->send(new BookingApprovedMail($listing));

    return redirect()
        ->route('landlord.booking.requests')
        ->with('request_success', 'Booking approved successfully.');
}


/* ===================================================== */
/* LANDLORD: REJECT BOOKING                              */
/* ===================================================== */

public function rejectBooking(Request $request, Listing $listing)
{
    abort_if(
        $listing->landlord_id !== Auth::user()->landlord->id,
        403
    );

    abort_if($listing->status !== 'requested', 400);

    $ocsEmail = $listing->ocs->user->email;

    $listing->update([
        'status' => 'published',
        'ocs_id' => null,
    ]);

    Mail::to($ocsEmail)
        ->send(new BookingRejectedMail($listing));

    return redirect()
        ->route('landlord.booking.requests')
        ->with('request_error', 'Booking request rejected.');
}

public function myBookingRequests()
{
    // Must be OCS
    abort_if(!Auth::user()->ocs, 403);

    $ocsId = Auth::user()->ocs->id;

    $listings = Listing::with(['images', 'landlord.user', 'reviews'])
        ->where(function ($query) use ($ocsId) {
                $query->where('ocs_id', $ocsId)
                    ->orWhere('last_occupied_ocs_id', $ocsId);
            })
        ->whereIn('status', ['requested', 'approved', 'rejected', 'published', 'occupied'])
        ->latest()
        ->get();

    $reviews = Review::with('listing.images')
        ->where('ocs_id', $ocsId)
        ->latest()
        ->get();

    return view(
        'manage_rental_booking.ocs-booking-tracker',
        compact('listings','reviews')
    );
}

public function terminateRent(Request $request, Listing $listing)
{
    // Ownership check
    abort_if(
        $listing->landlord_id !== auth()->user()->landlord->id,
        403
    );

    // Only occupied listings can be terminated
    abort_if($listing->status !== 'occupied', 400);

    DB::transaction(function () use ($listing) {

        $listing->update([
            'last_occupied_ocs_id' => $listing->ocs_id,
            'ocs_id' => null,
            'status' => 'published',
        ]);

    });

    return redirect()
        ->route('landlord.listings')
        ->with('success', 'Tenancy terminated. Listing is now available again.');
}



}

