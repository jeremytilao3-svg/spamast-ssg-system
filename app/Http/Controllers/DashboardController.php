<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\Institute;
use App\Models\Penalty;
use App\Models\Program;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    // One method per role, each showing its own dashboard page with cached numbers

    public function admin(): View
    {
        // Cache plain numbers for 10 minutes, never Eloquent models
        $stats = Cache::remember('admin.stats', now()->addMinutes(10), function () {
            return [
                // Users per role name, for example ['Admin' => 1, 'Student' => 22]
                'users_per_role' => DB::table('users')
                    ->join('roles', 'roles.id', '=', 'users.role_id')
                    ->groupBy('roles.name')
                    ->selectRaw('roles.name AS role_name, COUNT(*) AS total')
                    ->pluck('total', 'role_name')
                    ->all(),
                'pending'    => User::where('is_active', false)->count(),
                'institutes' => Institute::count(),
                'programs'   => Program::count(),
            ];
        });

        return view('dashboards.admin', compact('stats'));
    }

    public function officer(): View
    {
        $stats = Cache::remember('officer.stats', now()->addMinutes(10), function () {
            return [
                'students'        => Student::count(),
                'upcoming_events' => Event::whereDate('event_date', '>=', today())->count(),
                'unpaid_fines'    => Penalty::where('status', 'unpaid')->count(),
                'announcements'   => Announcement::count(),
            ];
        });

        $latest = Announcement::latest()->take(3)->get();
        return view('dashboards.officer', compact('stats', 'latest'));
    }

    public function adviser(): View
    {
        $stats = Cache::remember('adviser.stats', now()->addMinutes(10), function () {
            return [
                'upcoming_events'    => Event::whereDate('event_date', '>=', today())->count(),
                'attendance_records' => Attendance::count(),
                'unpaid_fines'       => Penalty::where('status', 'unpaid')->count(),
            ];
        });

        $latest = Announcement::latest()->take(3)->get();
        return view('dashboards.adviser', compact('stats', 'latest'));
    }

    public function student(Request $request): View
    {
        $user = $request->user();

        // One key per student: personal numbers are never shared between students
        $stats = Cache::remember('student.stats.' . $user->id, now()->addMinutes(10), function () use ($user) {
            return [
                'upcoming_events' => Event::whereDate('event_date', '>=', today())->count(),
                'my_attendance'   => $user->student
                    ? Attendance::where('student_id', $user->student->id)->count()
                    : 0,
            ];
        });

        $latest = Announcement::latest()->take(3)->get();
        return view('dashboards.student', compact('stats', 'latest'));
    }
}