<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    // List all announcements, newest first, 10 per page
    public function index(): View
    {
        $announcements = Announcement::with('user')->latest()->paginate(10);

        return view('announcements.index', compact('announcements'));
    }

    // Show one announcement with its attachment link
    public function show(Announcement $announcement): View
    {
        $announcement->load('user');

        return view('announcements.show', compact('announcement'));
    }
}
