<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\Penalty;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PenaltyController extends Controller
{
    // Work out the fines of one event for all students
    public function generate(Event $event): RedirectResponse
    {
        // Every session has a check-in scan and a check-out scan
        $required = $event->sessions()->count() * 2;

        // Counted scans per student: every non-null check_in_at and check_out_at
        $counted = Attendance::whereHas('session', fn ($q) => $q->where('event_id', $event->id))
            ->get()
            ->groupBy('student_id')
            ->map(fn ($rows) => $rows->whereNotNull('check_in_at')->count()
                + $rows->whereNotNull('check_out_at')->count());

        DB::transaction(function () use ($event, $required, $counted) {
            foreach (Student::pluck('id') as $studentId) {
                $missed = max($required - ($counted[$studentId] ?? 0), 0);

                // Nothing missed: no fine
                if ($missed === 0) {
                    Penalty::where('event_id', $event->id)->where('student_id', $studentId)->delete();
                    continue;
                }

                // Only missed_scans and amount are set, so sanction and status are kept
                Penalty::updateOrCreate(
                    ['event_id' => $event->id, 'student_id' => $studentId],
                    ['missed_scans' => $missed, 'amount' => $missed * $event->fine_per_miss]
                );
            }
        });

        return redirect()->route('penalties.index', $event)->with('success', 'Fines generated.');
    }

    // Set the sanction and the status of one fine
    public function update(Request $request, Penalty $penalty): RedirectResponse
    {
        $data = $request->validate([
            'sanction' => ['nullable', 'string', 'max:255'],
            'status'   => ['required', Rule::in(['unpaid', 'paid', 'served'])],
        ]);

        $penalty->update($data);

        return back()->with('success', 'Fine updated.');
    }

    // Remove one fine
    public function destroy(Penalty $penalty): RedirectResponse
    {
        $penalty->delete();

        return back()->with('success', 'Fine deleted.');
    }
}