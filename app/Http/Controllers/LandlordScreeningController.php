<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\LandlordVerificationApproved;
use App\Mail\LandlordVerificationRejected;
use App\Models\Listing;
use App\Models\Review;



class LandlordScreeningController extends Controller
{
    /* =========================
     * DASHBOARD ROUTER
     * ========================= */
    public function index()
    {
        $landlord = Auth::user()->landlord;

        if (!$landlord) {
            abort(403);
        }

        if ($landlord->screening_submitted_at && $landlord->screening_status !== 'approved') {
        return view(
            'manage_landlord_screening.landlord-verification-status',
            compact('landlord')
        );
    }

        if ($landlord->screening_status !== 'approved') {
            return view(
                'manage_landlord_screening.landlord-pending-dashboard',
                [
                    'landlord' => $landlord,
                    'progress' => $this->calculateProgress($landlord),
                ]
            );
        }

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

    /* =========================
     * VERIFICATION PAGE
     * ========================= */
    public function verification()
    {
        $landlord = Auth::user()->landlord;

        if (!$landlord) {
            abort(403);
        }

        $completed = 0;

        if (session('verification_files.ic_pic')) $completed++;
        if (session('verification_files.proof_of_address')) $completed++;
        if (session('verification_screening.completed')) $completed++;

        $progress = (int) round(($completed / 3) * 100);

        return view(
            'manage_landlord_screening.landlord-verification',
            compact('landlord', 'progress')
        );
    }

    /* =========================
     * STORE FILES IN SESSION
     * ========================= */
    public function storeInSession(Request $request)
    {
        $request->validate([
            'ic_pic' => 'nullable|file|mimes:pdf,jpg,jpeg|max:10240',
            'proof_of_address' => 'nullable|file|mimes:pdf,jpg,jpeg|max:10240',
        ]);

        $verificationFiles = session('verification_files', [
            'ic_pic' => null,
            'proof_of_address' => null,
        ]);

        foreach (['ic_pic', 'proof_of_address'] as $field) {
            if ($request->hasFile($field)) {

                // 🔥 DELETE OLD TEMP FILE IF EXISTS
                if (
                    isset($verificationFiles[$field]['temp_path']) &&
                    Storage::disk('local')->exists($verificationFiles[$field]['temp_path'])
                ) {
                    Storage::disk('local')->delete($verificationFiles[$field]['temp_path']);
                }

                $file = $request->file($field);

                $verificationFiles[$field] = [
                    'temp_path' => $file->store('temp_verification', 'local'),
                    'name' => $file->getClientOriginalName(),
                    'type' => $file->getMimeType(),
                ];
            }
        }


        session(['verification_files' => $verificationFiles]);

        return response()->json(['success' => true]);
    }

    /* =========================
     * FILE PREVIEW (SESSION)
     * ========================= */
    public function previewSessionFile(string $type)
    {
        if (!in_array($type, ['ic_pic', 'proof_of_address'])) {
            abort(404);
        }

        $files = session('verification_files');

        if (
            !isset($files[$type]['temp_path']) ||
            !Storage::disk('local')->exists($files[$type]['temp_path'])
        ) {
            abort(404);
        }

        return response()->file(
            Storage::disk('local')->path($files[$type]['temp_path']),
            [
                'Content-Type' => $files[$type]['type'],
                'Content-Disposition' => 'inline',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]
        );

    }



    /* =========================
     * BACKGROUND SCREENING PAGE
     * ========================= */
    public function screening()
    {
        if (
            !session('verification_files.ic_pic') ||
            !session('verification_files.proof_of_address')
        ) {
            return redirect()->route('landlord.verification');
        }

        $screening = session('verification_screening', []);

        $completed = 2;
        if (!empty($screening['bank_name'])) $completed++;
        if (isset($screening['has_criminal_record'])) $completed++;
        if (!empty($screening['agreement_accepted'])) $completed++;

        $progress = ($completed / 5) * 100;

        return view(
            'manage_landlord_screening.landlord-background-screening',
            compact('progress')
        );
    }

    /* =========================
     * SAVE SCREENING → SESSION
     * ========================= */
    public function saveScreeningToSession(Request $request)
    {
        $screening = session('verification_screening', []);

        if ($request->has('bank_name')) {
            $request->validate([
                'bank_name' => 'required|string',
                'bank_account_num' => 'required|string',
            ]);

            $screening['bank_name'] = $request->bank_name;
            $screening['bank_account_num'] = $request->bank_account_num;
        }

        if ($request->has('has_criminal_record')) {
            $screening['has_criminal_record'] = $request->has_criminal_record;
            $screening['criminal_record_details'] = $request->criminal_record_details;
        }

        if ($request->has('agreement_accepted')) {
            $screening['agreement_accepted'] = true;
            $screening['completed'] = true;
        }

        session(['verification_screening' => $screening]);

        return response()->json(['success' => true]);
    }

    /* =========================
     * FINAL SUBMIT → DATABASE
     * ========================= */
    public function finalizeVerification()
    {
        $landlord = Auth::user()->landlord;

        $files = session('verification_files');
        $screening = session('verification_screening');

        if (
            !$files ||
            !$screening ||
            empty($screening['completed'])
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Verification is incomplete'
            ], 400);
        }

        foreach (['ic_pic', 'proof_of_address'] as $field) {

            if (
                empty($files[$field]['temp_path']) ||
                !Storage::disk('local')->exists($files[$field]['temp_path'])
            ) {
                return response()->json([
                    'success' => false,
                    'message' => "Uploaded file missing: {$field}. Please re-upload."
                ], 422);
            }

            $finalPath = Storage::disk('public')->putFile(
                'landlord_verification',
                new \Illuminate\Http\File(
                    Storage::disk('local')->path($files[$field]['temp_path'])
                )
            );

            $landlord->$field = $finalPath;

            Storage::disk('local')->delete($files[$field]['temp_path']);
        }


        // Save screening data
        $landlord->bank_name = $screening['bank_name'];
        $landlord->bank_account_num = $screening['bank_account_num'];
        $landlord->has_criminal_record = $screening['has_criminal_record'];
        $landlord->criminal_record_details = $screening['criminal_record_details'] ?? null;
        $landlord->agreement_accepted = true;
        $landlord->screening_status = 'pending';
        $landlord->screening_submitted_at = Carbon::now();
        $landlord->save();

        // Clear session AFTER success
        session()->forget([
            'verification_files',
            'verification_screening'
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    /* =========================
     * DATABASE PROGRESS
     * ========================= */
    private function calculateProgress($landlord): int
    {
        $steps = [
            !empty($landlord->ic_pic),
            !empty($landlord->proof_of_address),
            !empty($landlord->bank_account_num),
            $landlord->has_criminal_record !== null,
            $landlord->agreement_accepted,
        ];

        return (int) round((collect($steps)->filter()->count() / 5) * 100);
    }

    /* =========================
     * VIEW VERIFICATION
     * ========================= */
    public function viewApplication()
    {
        $landlord = Auth::user()->landlord;

        if (!$landlord || !$landlord->screening_submitted_at) {
            abort(403);
        }

        return view(
            'manage_landlord_screening.landlord-verification-status',
            compact('landlord')
        );
    }

    /* =========================
 * EDIT VERIFICATION
 * ========================= */
    public function editApplication(Request $request)
    {
        $landlord = Auth::user()->landlord;

        if (
            !$landlord ||
            !$landlord->screening_submitted_at ||
            $landlord->screening_status === 'approved'
        ) {
            abort(403);
        }

        // Validate the request
        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'bank_account_num' => 'required|string|max:255',
            'ic_pic' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'proof_of_address' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'has_criminal_record' => 'required|boolean',
            'criminal_record_details' => 'nullable|string|max:1000',
            'agreement_accepted' => 'required|accepted',
        ]);

        // Prepare data for update
        $updateData = [
            'bank_name' => $validated['bank_name'],
            'bank_account_num' => $validated['bank_account_num'],
            'has_criminal_record' => $validated['has_criminal_record'],
            'criminal_record_details' => $validated['has_criminal_record'] ? $validated['criminal_record_details'] : null,
            'agreement_accepted' => true,
            'screening_status' => 'pending', // Reset to pending after edit
            'screening_submitted_at' => now(), // Update submission time
        ];

        // Handle IC/Passport file upload
        if ($request->hasFile('ic_pic')) {
            // Delete old file if exists
            if ($landlord->ic_pic) {
                Storage::disk('public')->delete($landlord->ic_pic);
            }
            $updateData['ic_pic'] = $request->file('ic_pic')->store('verification/ic', 'public');
        }

        // Handle Proof of Address file upload
        if ($request->hasFile('proof_of_address')) {
            // Delete old file if exists
            if ($landlord->proof_of_address) {
                Storage::disk('public')->delete($landlord->proof_of_address);
            }
            $updateData['proof_of_address'] = $request->file('proof_of_address')->store('verification/proof_of_address', 'public');
        }

        // Update landlord record
        $landlord->update($updateData);

        return redirect()
            ->route('landlord.dashboard')
            ->with('success', 'Verification application updated successfully.');
    }


    /* =========================
     * WITHDRAW VERIFICATION
     * ========================= */
    public function withdraw()
    {
        $landlord = Auth::user()->landlord;

        if (!$landlord || !$landlord->screening_submitted_at) {
            abort(403);
        }


        $landlord->update([
            'ic_pic' => null,
            'proof_of_address' => null,
            'bank_account_num' => null,
            'bank_name' => null,
            'has_criminal_record' => null,
            'criminal_record_details' => null,
            'screening_submitted_at' => null,
            'agreement_accepted' => false,
        ]);

        return redirect()
            ->route('landlord.dashboard')
            ->with('withdraw_success', 'Verification application withdrawn.');
    }

    public function adminVerificationIndex()
    {
        $landlords = \App\Models\Landlord::with('user')
            ->whereNotNull('screening_submitted_at')
            ->where('screening_status', 'pending')
            ->orderBy('screening_submitted_at', 'asc')
            ->get();

        $listings = Listing::with([
            'landlord',       // landlord model
            'landlord.user',  // landlord → user
            'images'
        ])
        ->where('status', 'pending')
        ->orderBy('created_at', 'asc')
        ->get();

        return view('manage_landlord_screening.admin-verification-index', compact('landlords', 'listings'));
    }

    public function adminVerificationShow(\App\Models\Landlord $landlord)
    {
        if (!$landlord->screening_submitted_at) {
            abort(404);
        }

        return view('manage_landlord_screening.admin-verification', compact('landlord'));
    }

    public function adminApproveVerification(\App\Models\Landlord $landlord)
    {
        $now = Carbon::now();
        $admin = Auth::id();
        if ($landlord->screening_status !== 'pending') {
            abort(403);
        }

        $landlord->update([
            'reviewed_by_admin_id' => $admin,
            'screening_status' => 'approved',
            'screening_reviewed_at' => $now,
        ]);


        Mail::to($landlord->user->email)
            ->send(new LandlordVerificationApproved($landlord));


        return redirect()
            ->route('admin.verifications.index')
            ->with('success', 'Landlord verification approved.');
    }

    public function adminRejectVerification(Request $request,\App\Models\Landlord $landlord)
    {
        $now = Carbon::now();
        $admin = Auth::id();

        if ($landlord->screening_status !== 'pending') {
            abort(403);
        }

        $landlord->update([
            'reviewed_by_admin_id' => $admin,
            'screening_status' => 'rejected',
            'screening_reviewed_at' => $now,
            'screening_notes' => $request->reason,
        ]);

        return redirect()
            ->route('admin.verifications.index')
            ->with('error', 'Landlord verification rejected.');
    }

    public function adminRequestResubmission(
        \Illuminate\Http\Request $request,
        \App\Models\Landlord $landlord
    ) {
        if ($landlord->screening_status !== 'pending') {
            abort(403);
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $landlord->update([
            'screening_status' => 'pending',
            'screening_notes' => $request->reason, // <-- ADD COLUMN IF NOT EXISTS
        ]);

        Mail::to($landlord->user->email)
        ->send(new LandlordVerificationRejected(
            $landlord,
            $request->reason
        ));

        return redirect()
            ->route('admin.verifications.index')
            ->with('success', 'Resubmission requested from landlord.');
    }




}
