<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Penalty;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ReportController extends Controller
{
    // List of events: pick one to see its report
    public function index(): View
    {
        Gate::authorize('view-reports');

        $events = Event::orderByDesc('event_date')->paginate(10);

        return view('reports.index', compact('events'));
    }

    // Report of one event
    public function show(Event $event): View
    {
        Gate::authorize('view-reports');

        // Query Builder: attendances joined to sessions, students, programs and institutes.
        // Only rows with a check-in count.
        $base = DB::table('attendances as a')
            ->join('event_sessions as es', 'es.id', '=', 'a.event_session_id')
            ->join('students as s', 's.id', '=', 'a.student_id')
            ->join('programs as p', 'p.id', '=', 's.program_id')
            ->join('institutes as i', 'i.id', '=', 'p.institute_id')
            ->where('es.event_id', $event->id)
            ->whereNotNull('a.check_in_at');

        // Totals per institute and program (a student counts once, even with several sessions)
        $summary = (clone $base)
            ->selectRaw("
                i.code AS institute_code,
                p.code AS program_code,
                COUNT(DISTINCT a.student_id) AS total,
                COUNT(DISTINCT CASE WHEN s.sex = 'Male' THEN a.student_id END) AS male,
                COUNT(DISTINCT CASE WHEN s.sex = 'Female' THEN a.student_id END) AS female
            ")
            ->groupBy('i.code', 'p.code')
            ->orderBy('i.code')
            ->orderBy('p.code')
            ->get();

        // Grand totals: each student has only one program, so the rows can be added up
        $grand = [
            'total'  => $summary->sum('total'),
            'male'   => $summary->sum('male'),
            'female' => $summary->sum('female'),
        ];

        // Every student with a check-in, one row per session
        $students = (clone $base)
            ->join('users as u', 'u.id', '=', 's.user_id')
            ->select(
                's.student_no', 'u.last_name', 'u.first_name',
                'p.code as program_code', 'i.code as institute_code',
                'es.period', 'a.check_in_at', 'a.check_out_at'
            )
            ->orderBy('i.code')
            ->orderBy('p.code')
            ->orderBy('s.student_no')
            ->orderBy('es.period')
            ->get()
            ->map(function ($row) {
                // The Query Builder returns plain text, so we turn the times into Carbon dates
                $row->check_in_at = $row->check_in_at ? Carbon::parse($row->check_in_at) : null;
                $row->check_out_at = $row->check_out_at ? Carbon::parse($row->check_out_at) : null;

                return $row;
            });

        // Fines: every penalty of this event, with each student's program loaded
        $penalties = Penalty::with('student.program')
            ->where('event_id', $event->id)
            ->get();

        // Step 1: group the penalties by program code (one bucket per program)
        // Step 2: turn each bucket into one row: program, number of students, total fines
        // Step 3: biggest total first, then renumber the list from 0
        $fineSummary = $penalties
            ->groupBy(fn ($penalty) => $penalty->student?->program?->code ?? 'No program')
            ->map(fn ($group) => [
                'program'  => $group->first()->student?->program,
                'students' => $group->pluck('student_id')->unique()->count(),
                'total'    => $group->sum('amount'),
            ])
            ->sortByDesc('total')
            ->values();

        // Grand total of all fines of the event (must match the fines page)
        $grandTotal = $penalties->sum('amount');

        return view('reports.show', compact('event', 'summary', 'grand', 'students', 'fineSummary', 'grandTotal'));
    }
}