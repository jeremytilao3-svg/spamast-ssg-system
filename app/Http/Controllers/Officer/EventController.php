<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class EventController extends Controller
{
    // Which sessions an event needs: whole day = morning + afternoon, half day = one session
    private function periodsFor(?string $dayType): array
    {
        return $dayType === 'whole_day' ? ['morning', 'afternoon'] : ['half_day'];
    }

    // Validation rules (the session times depend on the day type and availability)
    private function rules(Request $request): array
    {
        $rules = [
            'title'         => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'venue'         => ['required', 'string', 'max:255'],
            'event_date'    => ['required', 'date'],
            'day_type'      => ['required', Rule::in(['whole_day', 'half_day'])],
            'availability'  => ['required', Rule::in(['scheduled', 'manual'])],
            'fine_per_miss' => ['required', 'numeric', 'min:0', 'max:999999.99'],
        ];

        foreach ($this->periodsFor($request->input('day_type')) as $period) {
            $p = "sessions.$period";

            if ($request->input('availability') === 'scheduled') {
                // Scheduled events: every time is required, and each end is after its start
                $rules["$p.check_in_start"]  = ['required', 'date_format:H:i'];
                $rules["$p.check_in_end"]    = ['required', 'date_format:H:i', "after:$p.check_in_start"];
                $rules["$p.check_out_start"] = ['required', 'date_format:H:i'];
                $rules["$p.check_out_end"]   = ['required', 'date_format:H:i', "after:$p.check_out_start"];
            } else {
                // Manual events: times are optional
                foreach (['check_in_start', 'check_in_end', 'check_out_start', 'check_out_end'] as $field) {
                    $rules["$p.$field"] = ['nullable', 'date_format:H:i'];
                }
            }
        }

        return $rules;
    }

    // The four times of one session, read from the form
    private function sessionTimes(Request $request, string $period): array
    {
        return [
            'check_in_start'  => $request->input("sessions.$period.check_in_start"),
            'check_in_end'    => $request->input("sessions.$period.check_in_end"),
            'check_out_start' => $request->input("sessions.$period.check_out_start"),
            'check_out_end'   => $request->input("sessions.$period.check_out_end"),
        ];
    }

    // Only these event columns may come from the form
    private function eventFields(array $data): array
    {
        return Arr::only($data, [
            'title', 'description', 'venue', 'event_date',
            'day_type', 'availability', 'fine_per_miss',
        ]);
    }

    // Show the "new event" form
    public function create(): View
    {
        return view('officer.events.create');
    }

    // Save a new event and its sessions in one transaction
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules($request));

        DB::transaction(function () use ($request, $data) {
            $event = Event::create($this->eventFields($data) + ['created_by' => $request->user()->id]);

            foreach ($this->periodsFor($data['day_type']) as $period) {
                $event->sessions()->create(['period' => $period] + $this->sessionTimes($request, $period));
            }
        });

        return redirect()->route('events.index')->with('success', 'Event created.');
    }

    // Show the "edit event" form
    public function edit(Event $event): View
    {
        Gate::authorize('update', $event);

        // Sessions keyed by period, so the form can fill morning, afternoon or half_day
        $sessions = $event->sessions()->get()->keyBy('period');

        return view('officer.events.edit', compact('event', 'sessions'));
    }

    // Save changes to an event
    public function update(Request $request, Event $event): RedirectResponse
    {
        Gate::authorize('update', $event);

        $data = $request->validate($this->rules($request));

        DB::transaction(function () use ($request, $event, $data) {
            $dayTypeChanged = $event->day_type !== $data['day_type'];

            // The sessions cannot be rebuilt once students have scanned
            if ($dayTypeChanged && $event->attendances()->exists()) {
                throw ValidationException::withMessages([
                    'day_type' => 'The day type cannot change after students have scanned.',
                ]);
            }

            $event->update($this->eventFields($data));

            if ($dayTypeChanged) {
                // Whole day <-> half day: replace the sessions
                $event->sessions()->delete();

                foreach ($this->periodsFor($data['day_type']) as $period) {
                    $event->sessions()->create(['period' => $period] + $this->sessionTimes($request, $period));
                }
            } else {
                // Same day type: keep the sessions (and their attendance), update the times only
                foreach ($event->sessions as $session) {
                    $session->update($this->sessionTimes($request, $session->period));
                }
            }
        });

        return redirect()->route('events.show', $event)->with('success', 'Event updated.');
    }

    // Delete an event (the policy blocks it once students have scanned)
    public function destroy(Event $event): RedirectResponse
    {
        Gate::authorize('delete', $event);

        $event->delete();   // its sessions are removed by cascade

        return redirect()->route('events.index')->with('success', 'Event deleted.');
    }
}