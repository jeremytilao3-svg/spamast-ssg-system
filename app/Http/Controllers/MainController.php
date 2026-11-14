<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class MainController extends Controller
{
    // Show the main page for logged-in users
    public function index(): View
    {
        return view('main');
    }
}