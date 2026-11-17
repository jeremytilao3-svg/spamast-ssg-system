<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\EventSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // The logged-in student scans IN to a session
    public function checkIn(Request $request, EventSession $session): RedirectResponse
    {
        $student = $request->user()->student;

        // A student account without a student record cannot scan
        abort_unless($student, 403, 'Your account has no linked student record.');

        if (! $session->isCheckInOpen()) {
            return back()->with('error', 'Check-in is closed.');
        }

        // One row per session and student: scanning twice does not create a second row
        $attendance = Attendance::firstOrCreate(
            ['event_session_id' => $session->id, 'student_id' => $student->id],
            ['check_in_at' => now()]
        );

        if (! $attendance->wasRecentlyCreated) {
            return back()->with('error', 'You already checked in.');
        }

        \Illuminate\Support\Facades\Cache::forget('adviser.stats');
        \Illuminate\Support\Facades\Cache::forget('student.stats.' . $request->user()->id);
        return back()->with('success', 'Checked in.');
    }

    // The logged-in student scans OUT of a session
    public function checkOut(Request $request, EventSession $session): RedirectResponse
    {
        $student = $request->user()->student;

        abort_unless($student, 403, 'Your account has no linked student record.');

        if (! $session->isCheckOutOpen()) {
            return back()->with('error', 'Check-out is closed.');
        }

        // Only a row that has a check-in and no check-out yet (otherwise 404)
        $attendance = Attendance::where('event_session_id', $session->id)
            ->where('student_id', $student->id)
            ->whereNull('check_out_at')
            ->firstOrFail();

        $attendance->update(['check_out_at' => now()]);

        return back()->with('success', 'Checked out.');
    }
}