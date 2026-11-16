<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Institute;
use App\Models\Penalty;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenaltyController extends Controller
{
    // Fines of one event (Officers and Advisers)
    public function index(Request $request, Event $event): View
    {
        $query = Penalty::with(['student.user', 'student.program.institute'])
            ->where('event_id', $event->id);

        // Filter: institute
        if ($request->filled('institute_id')) {
            $query->whereHas('student.program', fn ($q) => $q->where('institute_id', $request->institute_id));
        }

        // Filter: program
        if ($request->filled('program_id')) {
            $query->whereHas('student', fn ($q) => $q->where('program_id', $request->program_id));
        }

        // Filter: status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Total of ALL the filtered fines, not only the current page
        $total = (clone $query)->sum('amount');

        $penalties = $query->orderByDesc('amount')->paginate(20)->withQueryString();

        $institutes = Institute::orderBy('code')->get();
        $programs = Program::orderBy('code')->get();

        return view('penalties.index', compact('event', 'penalties', 'total', 'institutes', 'programs'));
    }
}