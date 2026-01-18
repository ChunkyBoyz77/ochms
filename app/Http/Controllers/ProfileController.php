<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show profile edit page based on role
     */
    public function edit()
    {
        $user = Auth::user();

        return match ($user->role) {
            'ocs'      => view('auth.ocs-auth.ocs-profile', compact('user')),
            'landlord' => view('auth.landlord-auth.landlord-profile', compact('user')),
            'admin'    => view('auth.admin-auth.admin-profile', compact('user')),
            default    => abort(403),
        };
    }

    /**
     * Update profile (USER + ROLE)
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        /* ================= USER (COMMON FIELDS) ================= */
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone_number'  => 'nullable|string|max:20',
            'password'      => 'nullable|confirmed|min:8',
            'profile_pic'   => 'nullable|image|max:2048',
        ]);

        $user->fill([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        

        if ($request->hasFile('profile_pic')) {

            // Delete old profile picture if exists
            if ($user->profile_pic) {
                Storage::disk('public')->delete($user->profile_pic);
            }

            $user->profile_pic = $request->file('profile_pic')
                ->store('profile_pics', 'public');
        }


        $user->save();

        /* ================= ROLE-SPECIFIC ================= */
        match ($user->role) {
            'ocs'      => $this->updateOCS($request, $user),
            'landlord' => $this->updateLandlord($request, $user),
            'admin'    => null, // admin profile is read-only for now
            default    => null,
        };

        return back()->with('success', 'Profile updated successfully.');
    }

    /* ==========================================================
       OCS PROFILE UPDATE
       ========================================================== */
    protected function updateOCS(Request $request, $user)
    {
        $request->validate([
            'faculty'          => 'required|string|max:100',
            'course'           => 'required|string|max:100',
            'study_year'       => 'required|integer|min:1|max:6',
            'current_semester' => 'required|integer|min:1|max:8',
        ]);

        $user->ocs()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'faculty'          => $request->faculty,
                'course'           => $request->course,
                'study_year'       => $request->study_year,
                'current_semester' => $request->current_semester,
            ]
        );
    }

    /* ==========================================================
       LANDLORD PROFILE UPDATE
       ========================================================== */
    protected function updateLandlord(Request $request, $user)
    {
        $request->validate([
            'bank_name'          => 'nullable|string|max:100',
            'bank_account_num'   => 'nullable|string|max:50',
            'ic_pic'             => 'nullable|image|max:2048',
            'supporting_document'   => 'nullable|image|max:2048',
        ]);

        $landlord = $user->landlord()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'bank_name'        => $request->bank_name,
                'bank_account_num' => $request->bank_account_num,
            ]
        );

        if ($request->hasFile('ic_pic')) {
            $landlord->ic_pic =
                $request->file('ic_pic')->store('landlord_docs', 'public');
        }

        if ($request->hasFile('supporting_document')) {
            $landlord->supporting_document =
                $request->file('supporting_document')->store('landlord_docs', 'public');
        }

        $landlord->save();
    }

    /**
     * Delete account (optional / future use)
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();



        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
