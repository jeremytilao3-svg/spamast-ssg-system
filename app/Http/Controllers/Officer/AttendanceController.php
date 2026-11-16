<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller
{
    // Add (or fix) a scan for a student
    public function store(Request $request, Event $event): RedirectResponse
    {
        $data = $request->validate([
            // The session must belong to THIS event
            'event_session_id' => ['required', Rule::exists('event_sessions', 'id')->where('event_id', $event->id)],
            'student_id'       => ['required', 'exists:students,id'],
            'check_in_at'      => ['required', 'date'],
            'check_out_at'     => ['nullable', 'date', 'after_or_equal:check_in_at'],
        ]);

        // One row per session and student: update it if it exists, create it if not
        Attendance::updateOrCreate(
            ['event_session_id' => $data['event_session_id'], 'student_id' => $data['student_id']],
            ['check_in_at' => $data['check_in_at'] ?? null, 'check_out_at' => $data['check_out_at'] ?? null]
        );

        return redirect()->route('attendance.index', $event)->with('success', 'Attendance saved.');
    }

    // Correct the times of one attendance row
    public function update(Request $request, Attendance $attendance): RedirectResponse
    {
        $data = $request->validate([
            'check_in_at'  => ['required', 'date'],
            'check_out_at' => ['nullable', 'date', 'after_or_equal:check_in_at'],
        ]);

        $attendance->update([
            'check_in_at'  => $data['check_in_at'] ?? null,
            'check_out_at' => $data['check_out_at'] ?? null,
        ]);

        return back()->with('success', 'Attendance updated.');
    }

    // Remove one attendance row
    public function destroy(Attendance $attendance): RedirectResponse
    {
        $attendance->delete();

        return back()->with('success', 'Attendance deleted.');
    }
}