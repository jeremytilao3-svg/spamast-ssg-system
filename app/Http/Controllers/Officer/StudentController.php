<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Institute;
use App\Models\Program;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StudentController extends Controller
{
    // List students with search, filters and pages of 15
    public function index(Request $request): View
    {
        // Eager load the related rows so the page needs only a few queries
        $query = Student::with(['user', 'program.institute']);

        // Search by student number or by the student's name
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('student_no', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by institute (through the student's program)
        if ($instituteId = $request->query('institute_id')) {
            $query->whereHas('program', fn ($q) => $q->where('institute_id', $instituteId));
        }

        // Filter by program
        if ($programId = $request->query('program_id')) {
            $query->where('program_id', $programId);
        }

        $students   = $query->orderBy('student_no')->paginate(15)->withQueryString();
        $institutes = Institute::orderBy('code')->get();
        $programs   = Program::orderBy('code')->get();

        return view('officer.students.index', compact('students', 'institutes', 'programs'));
    }

    // Show the "new student" form
    public function create(): View
    {
        return view('officer.students.create', [
            'institutes' => Institute::orderBy('code')->get(),
            'programs'   => Program::orderBy('code')->get(),
        ]);
    }

    // Save a new student (user account + student record, in one transaction)
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email:rfc', 'max:255'],
            'student_no'  => ['required', 'string', 'max:50', 'unique:students,student_no'],
            'program_id'  => ['required', 'exists:programs,id'],
            'sex'         => ['required', Rule::in(['Male', 'Female'])],
            'year_level'  => ['required', 'integer', 'between:1,6'],
        ]);

        $studentRole = Role::where('name', 'Student')->firstOrFail();

        DB::transaction(function () use ($data, $studentRole) {
            // Find the account by email, or create it.
            // The student number is the temporary password (the model hashes it).
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'first_name'  => $data['first_name'],
                    'middle_name' => $data['middle_name'] ?? null,
                    'last_name'   => $data['last_name'],
                    'password'    => $data['student_no'],
                    'role_id'     => $studentRole->id,
                    'is_active'   => true,
                ]
            );

            // An email that already belongs to a non-student, or to a student who
            // already has a record, must not be reused
            if (! $user->wasRecentlyCreated
                && (! $user->hasRole('Student') || Student::where('user_id', $user->id)->exists())) {
                throw ValidationException::withMessages([
                    'email' => 'This email already belongs to another account.',
                ]);
            }

            // Create the student record linked to that account
            Student::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'program_id' => $data['program_id'],
                    'student_no' => $data['student_no'],
                    'sex'        => $data['sex'],
                    'year_level' => $data['year_level'],
                ]
            );
        });

        \Illuminate\Support\Facades\Cache::forget('officer.stats');
        \Illuminate\Support\Facades\Cache::forget('admin.stats');
        return redirect()->route('officer.students.index')->with('success', 'Student created.');
    }

    // Show the "edit student" form
    public function edit(Student $student): View
    {
        return view('officer.students.edit', [
            'student'    => $student->load('user', 'program'),
            'institutes' => Institute::orderBy('code')->get(),
            'programs'   => Program::orderBy('code')->get(),
        ]);
    }

    // Save changes to both rows in one transaction
    public function update(Request $request, Student $student): RedirectResponse
    {
        $data = $request->validate([
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email:rfc', 'max:255', Rule::unique('users', 'email')->ignore($student->user_id)],
            'student_no'  => ['required', 'string', 'max:50', Rule::unique('students', 'student_no')->ignore($student->id)],
            'program_id'  => ['required', 'exists:programs,id'],
            'sex'         => ['required', Rule::in(['Male', 'Female'])],
            'year_level'  => ['required', 'integer', 'between:1,6'],
        ]);

        DB::transaction(function () use ($student, $data) {
            // The login account
            $student->user->update([
                'first_name'  => $data['first_name'],
                'middle_name' => $data['middle_name'] ?? null,
                'last_name'   => $data['last_name'],
                'email'       => $data['email'],
            ]);

            // The student record
            $student->update([
                'program_id' => $data['program_id'],
                'student_no' => $data['student_no'],
                'sex'        => $data['sex'],
                'year_level' => $data['year_level'],
            ]);
        });

        \Illuminate\Support\Facades\Cache::forget('officer.stats');
        \Illuminate\Support\Facades\Cache::forget('admin.stats');
        return redirect()->route('officer.students.index')->with('success', 'Student updated.');
    }

    // Delete the user account; the student record goes with it (cascade)
    public function destroy(Student $student): RedirectResponse
    {
        $student->user->delete();

        \Illuminate\Support\Facades\Cache::forget('officer.stats');
        \Illuminate\Support\Facades\Cache::forget('admin.stats');
        return redirect()->route('officer.students.index')->with('success', 'Student deleted.');
    }
}