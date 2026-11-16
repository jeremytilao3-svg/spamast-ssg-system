<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\EventSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class EventSessionController extends Controller
{
    // Open or close check-in / check-out of one session (manual events only)
    public function toggle(Request $request, EventSession $session): RedirectResponse
    {
        // Only Officers may change an event (EventPolicy)
        Gate::authorize('update', $session->event);

        $data = $request->validate([
            'type' => ['required', Rule::in(['check_in', 'check_out'])],
        ]);

        // Scheduled events open and close by the clock, not by hand
        if ($session->event->availability !== 'manual') {
            return back()->with('error', 'Only manual events can be opened or closed by hand.');
        }

        $column = $data['type'] . '_open';   // check_in_open or check_out_open

        $session->update([$column => ! $session->$column]);

        $label = $data['type'] === 'check_in' ? 'Check-in' : 'Check-out';
        $state = $session->$column ? 'opened' : 'closed';

        return back()->with('success', "$label $state.");
    }
}