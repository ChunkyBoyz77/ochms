<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ocs;
use App\Models\Landlord;
use App\Models\AdminProfile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    // SHOW FORMS
    public function createOcs(): View
    {
        return view('auth.ocs-auth.register-ocs');
    }

    public function createLandlord(): View
    {
        return view('auth.register-landlord');
    }

    public function createAdmin(): View
    {
        return view('auth.register-admin');
    }

    // STORE FORMS
    public function storeOcs(Request $request): RedirectResponse
    {
        $request->merge(['role' => 'ocs']);
        return $this->store($request);
    }

    public function storeLandlord(Request $request): RedirectResponse
    {
        $request->merge(['role' => 'landlord']);
        return $this->store($request);
    }

    public function storeAdmin(Request $request): RedirectResponse
    {
        $request->merge(['role' => 'admin']);
        return $this->store($request);
    }

   

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => ['required', 'in:ocs,landlord,admin'],
        ]);

        $phoneNumber = $request->country_code . $request->phone_local;

        DB::transaction(function () use ($request, &$user, $phoneNumber) {

            // Create base user
            $user = User::create([
                'name'     => $request->name,
                'password' => Hash::make($request->password),
                'email'    => $request->email,
                'phone_number'  => $phoneNumber ?? null,
                'role'     => $request->role,
            ]);

            // Create role-specific profile
            match ($user->role) {
                'ocs' => Ocs::create([
                    'user_id' => $user->id,
                    'matric_id' => $request->matric_id,
                    'faculty' => $request->faculty,
                    'course' => $request->course,
                    'study_year'=> $request->study_year,
                    'current_semester'=> $request->current_semester,

                ]),

                'landlord' => Landlord::create([
                    'user_id'          => $user->id,
                    'screening_status' => 'pending',
                    'ic_number'        => $request->ic_number ?? null,
                ]),

                'admin' => AdminProfile::create([
                    'user_id' => $user->id,
                    'staff_id' => $request->staff_id ?? null,
                ]),
            };
        });

        Auth::login($user);

        return redirect()->route(match ($user->role) {
            'ocs'      => 'home',
            'landlord' => 'landlord.dashboard',
            'admin'    => 'admin.dashboard',
        });
    }


}
