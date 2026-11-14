<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MainController extends Controller
{
    // /main is only a signpost: send the user to the dashboard for their role
    public function view(Request $request): RedirectResponse
    {
        return redirect()->route($request->user()->dashboardRoute());
    }
}