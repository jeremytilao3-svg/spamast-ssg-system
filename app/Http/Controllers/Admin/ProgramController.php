<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProgramController extends Controller
{
    // List programs (joined with their institute) with search and pages of 10
    public function index(Request $request): View
    {
        $query = DB::table('programs')
            ->join('institutes', 'programs.institute_id', '=', 'institutes.id')
            ->select('programs.*', 'institutes.code as institute_code');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('programs.code', 'like', "%{$search}%")
                  ->orWhere('programs.name', 'like', "%{$search}%")
                  ->orWhere('institutes.code', 'like', "%{$search}%");
            });
        }

        // withQueryString() keeps the search when you go to page 2
        $programs = $query->orderBy('programs.code')->paginate(10)->withQueryString();

        return view('admin.programs.index', compact('programs'));
    }

    // Show the "new program" form
    public function create(): View
    {
        $institutes = DB::table('institutes')->orderBy('code')->get();

        return view('admin.programs.create', compact('institutes'));
    }

    // Save a new program
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'institute_id' => ['required', 'exists:institutes,id'],
            'code'         => ['required', 'string', 'max:20', 'unique:programs,code'],
            'name'         => ['required', 'string', 'max:255'],
        ]);

        // Query Builder does not fill the dates by itself, so we set them
        DB::table('programs')->insert([
            'institute_id' => $data['institute_id'],
            'code'         => $data['code'],
            'name'         => $data['name'],
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect()->route('admin.programs.index')->with('success', 'Program created.');
    }

    // Show the "edit program" form
    public function edit(int $program): View
    {
        $row = DB::table('programs')->where('id', $program)->first();
        abort_if($row === null, 404);   // no such program

        $institutes = DB::table('institutes')->orderBy('code')->get();

        return view('admin.programs.edit', ['program' => $row, 'institutes' => $institutes]);
    }

    // Save changes to a program
    public function update(Request $request, int $program): RedirectResponse
    {
        abort_unless(DB::table('programs')->where('id', $program)->exists(), 404);

        $data = $request->validate([
            'institute_id' => ['required', 'exists:institutes,id'],
            // The code must be unique, but this program may keep its own code
            'code'         => ['required', 'string', 'max:20', Rule::unique('programs', 'code')->ignore($program)],
            'name'         => ['required', 'string', 'max:255'],
        ]);

        DB::table('programs')->where('id', $program)->update([
            'institute_id' => $data['institute_id'],
            'code'         => $data['code'],
            'name'         => $data['name'],
            'updated_at'   => now(),
        ]);

        return redirect()->route('admin.programs.index')->with('success', 'Program updated.');
    }

    // Delete a program (only if it has no students)
    public function destroy(int $program): RedirectResponse
    {
        if (DB::table('students')->where('program_id', $program)->exists()) {
            return back()->with('error', 'Cannot delete: this program still has students.');
        }

        DB::table('programs')->where('id', $program)->delete();

        return redirect()->route('admin.programs.index')->with('success', 'Program deleted.');
    }
}