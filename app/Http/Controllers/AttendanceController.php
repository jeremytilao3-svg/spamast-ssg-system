<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Event;
use App\Models\Institute;
use App\Models\Program;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    // Attendance of one event (Officers and Advisers)
    public function index(Request $request, Event $event): View
    {
        $event->load('sessions');

        // Eager loading: student, user, program, institute and session are loaded together
        $query = Attendance::with(['student.user', 'student.program.institute', 'session'])
            ->whereHas('session', fn ($q) => $q->where('event_id', $event->id));

        // Filter: session
        if ($request->filled('session_id')) {
            $query->where('event_session_id', $request->session_id);
        }

        // Filter: institute
        if ($request->filled('institute_id')) {
            $query->whereHas('student.program', fn ($q) => $q->where('institute_id', $request->institute_id));
        }

        // Filter: program
        if ($request->filled('program_id')) {
            $query->whereHas('student', fn ($q) => $q->where('program_id', $request->program_id));
        }

        $attendances = $query->orderBy('event_session_id')
            ->orderBy('check_in_at')
            ->paginate(20)
            ->withQueryString();

        $institutes = Institute::orderBy('code')->get();
        $programs = Program::orderBy('code')->get();

        // Only Officers need the student list (for the "add scan" form)
        $students = Gate::allows('manage-ssg')
            ? Student::with('user')->orderBy('student_no')->get()
            : collect();

        return view('attendance.index', compact('event', 'attendances', 'institutes', 'programs', 'students'));
    }
}