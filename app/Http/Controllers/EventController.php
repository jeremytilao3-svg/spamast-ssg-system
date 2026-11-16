<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    // List events (newest first) with search and pages of 10
    public function index(Request $request): View
    {
        $query = Event::with('sessions');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%");
            });
        }

        $events = $query->orderByDesc('event_date')->paginate(10)->withQueryString();

        return view('events.index', compact('events'));
    }

    // Show one event with its sessions
    public function show(Event $event): View
    {
        $event->load('sessions', 'creator');

        return view('events.show', compact('event'));
    }
}