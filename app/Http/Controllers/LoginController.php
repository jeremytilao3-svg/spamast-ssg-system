<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    // Show the login page
    public function show(): View
    {
        return view('auth.login');
    }

    // Check the email and password (runs when the login form is submitted)
    public function store(Request $request): RedirectResponse
    {
        // 1. Make sure both fields were filled in
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Try to log in ("remember" is the Remember Me checkbox)
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'The email or password is incorrect.'])
                ->onlyInput('email');
        }

        // 3. Block accounts that are switched off
        if (! Auth::user()->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'Your account is inactive or awaiting Admin approval.'])
                ->onlyInput('email');
        }

        // 4. Start a fresh session, then go to the page they wanted
        //    (or to the dashboard that matches their role)
        $request->session()->regenerate();

        return redirect()->intended(route($request->user()->dashboardRoute()));
    }

    // Log out (runs when the logout form is submitted)
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();                            // forget the logged-in user
        $request->session()->invalidate();         // destroy the old session
        $request->session()->regenerateToken();    // new CSRF token

        return redirect()->route('login');
    }
}