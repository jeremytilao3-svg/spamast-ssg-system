<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LoginController extends Controller
{
    // Show the login page (hand the "auth.login" view to the browser)
    public function show(): View
    {
        return view('auth.login');
    }
}