<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    // One method per role, each showing its own dashboard page

    public function admin(): View
    {
        return view('dashboards.admin');
    }

    public function officer(): View
    {
        return view('dashboards.officer');
    }

    public function adviser(): View
    {
        return view('dashboards.adviser');
    }

    public function student(): View
    {
        return view('dashboards.student');
    }
}