<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
    | 2. Create Listing (no media yet)
    |--------------------------------------------------------------------------
    */
    $listing = Listing::create([
        'landlord_id' => Auth::id(),
        'student_id' => null,
        'title' => $validated['title'],
        'property_type' => $validated['property_type'],
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

    /* ===================================================== */
    /* STUDENT: REQUEST BOOKING (future)                     */
    /* ===================================================== */

    public function requestBooking(Request $request, Listing $listing)
    {
        // Placeholder
        // Will create a booking request record
    }

    /* ===================================================== */
    /* LANDLORD: APPROVE BOOKING                             */
    /* ===================================================== */

    public function approveBooking(Request $request)
    {
        // Placeholder
        // Assign student_id + mark listing as occupied
    }

    /* ===================================================== */
    /* LANDLORD: REJECT BOOKING                              */
    /* ===================================================== */

    public function rejectBooking(Request $request)
    {
        // Placeholder
    }
}

