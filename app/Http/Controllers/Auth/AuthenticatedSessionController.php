<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('welcome');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        /**
         * ==========================
         * ADMIN LOGIN (STAFF ID)
         * ==========================
         */
        if ($request->filled('staff_id')) {

            $request->validate([
                'staff_id' => ['required', 'string'],
                'password' => ['required', 'string'],
            ]);

            $adminProfile = \App\Models\JhepaAdmin::where(
                'staff_id',
                $request->staff_id
            )->first();

            if (
                ! $adminProfile ||
                ! \Illuminate\Support\Facades\Hash::check(
                    $request->password,
                    $adminProfile->user->password
                )
            ) {
                return back()
                    ->with('login_error', 'Incorrect Staff ID or password.')
                    ->with('show_auth_modal', true)
                    ->withInput();
            }

            Auth::login($adminProfile->user, $request->boolean('remember'));
            $request->session()->regenerate();

        } else {

            /**
             * ==========================
             * NORMAL LOGIN (EMAIL)
             * ==========================
             */
            $request->validate([
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string'],
            ]);

            if (! Auth::attempt(
                $request->only('email', 'password'),
                $request->boolean('remember')
            )) {
                return back()
                    ->with('login_error', 'Incorrect email or password.')
                    ->with('show_auth_modal', true)
                    ->withInput();
            }

            $request->session()->regenerate();
        }

        /**
         * ==========================
         * ROLE-BASED REDIRECT
         * ==========================
         */
        $role = auth()->user()->role;

        if ($role === 'ocs') {
            return redirect()->route('home');
        }

        if ($role === 'landlord') {
            return redirect()->route('landlord.dashboard');
        }

        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect('/');
    }




    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user(); 

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user && $user->role === 'admin') {
            return redirect()->route('admin.login');
        }

        if ($user && $user->role === 'landlord') {
            return redirect()->route('landlord.auth');
        }

        return redirect('/');
    }

}
