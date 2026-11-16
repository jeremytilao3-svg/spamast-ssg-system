<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    // List of events (Officer, Adviser and Student): Upcoming or Past tab, search, date
    public function index(Request $request): View
    {
        // The tab is "upcoming" unless the URL says "past"
        $tab = $request->query('tab') === 'past' ? 'past' : 'upcoming';

        $query = Event::with('sessions');

        // Search by title or venue
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%");
            });
        }

        // Search by one date
        if ($request->filled('date')) {
            $query->whereDate('event_date', $request->date);
        }

        // Upcoming = today or later (soonest first). Past = before today (latest first).
        if ($tab === 'upcoming') {
            $query->whereDate('event_date', '>=', today())->orderBy('event_date');
        } else {
            $query->whereDate('event_date', '<', today())->orderByDesc('event_date');
        }

        $events = $query->paginate(10)->withQueryString();

        return view('events.index', compact('events', 'tab'));
    }

    // One event with its session schedule
    public function show(Event $event): View
    {
        $event->load('sessions', 'creator');

        return view('events.show', compact('event'));
    }
}