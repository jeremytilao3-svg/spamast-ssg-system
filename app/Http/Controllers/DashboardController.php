<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    // One method per role. All show the same page for now (real dashboards come in step 4.7)

    public function admin(): View
    {
        return view('main');
    }

    public function officer(): View
    {
        return view('main');
    }

    public function adviser(): View
    {
        return view('main');
    }

    public function student(): View
    {
        return view('main');
    }
}