<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class RegisterController extends Controller
{
    // Show the registration page (hand the "auth.register" view to the browser)
    public function show(): View
    {
        return view('auth.register');
    }
}