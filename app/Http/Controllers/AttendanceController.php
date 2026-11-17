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

        // Institute and program filters are remembered in the session
        if ($request->boolean('clear')) {
            // "Clear Filters": forget the saved filters
            session()->forget(['filters.institute_id', 'filters.program_id']);
            $instituteId = null;
            $programId = null;
        } elseif ($request->hasAny(['institute_id', 'program_id'])) {
            // The form was submitted: use these values and save them for next time
            $instituteId = $request->input('institute_id');
            $programId = $request->input('program_id');
            session(['filters.institute_id' => $instituteId, 'filters.program_id' => $programId]);
        } else {
            // Nothing in the URL: use what was saved before
            $instituteId = session('filters.institute_id');
            $programId = session('filters.program_id');
        }

        // Eager loading: student, user, program, institute and session are loaded together
        $query = Attendance::with(['student.user', 'student.program.institute', 'session'])
            ->whereHas('session', fn ($q) => $q->where('event_id', $event->id));

        // Filter: session
        if ($request->filled('session_id')) {
            $query->where('event_session_id', $request->session_id);
        }

        // Filter: institute
        if (filled($instituteId)) {
            $query->whereHas('student.program', fn ($q) => $q->where('institute_id', $instituteId));
        }

        // Filter: program
        if (filled($programId)) {
            $query->whereHas('student', fn ($q) => $q->where('program_id', $programId));
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

        return view('attendance.index', compact(
            'event', 'attendances', 'institutes', 'programs', 'students', 'instituteId', 'programId'
        ));
    }
}