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
        return view('auth.login');
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
                return back()->withErrors([
                    'staff_id' => trans('auth.failed'),
                ]);
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
                return back()->withErrors([
                    'email' => trans('auth.failed'),
                ]);
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
            return redirect()->route('auth.admin-dashboard');
        }

        return redirect('/');
    }




    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
