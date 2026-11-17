<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InstituteController extends Controller
{
    // List institutes with search and pages of 10
    public function index(Request $request): View
    {
        $query = DB::table('institutes');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // withQueryString() keeps the search when you go to page 2
        $institutes = $query->orderBy('code')->paginate(10)->withQueryString();

        return view('admin.institutes.index', compact('institutes'));
    }

    // Show the "new institute" form
    public function create(): View
    {
        return view('admin.institutes.create');
    }

    // Save a new institute
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:institutes,code'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        // Query Builder does not fill the dates by itself, so we set them
        DB::table('institutes')->insert([
            'code'       => $data['code'],
            'name'       => $data['name'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \Illuminate\Support\Facades\Cache::forget('admin.stats');
        return redirect()->route('admin.institutes.index')->with('success', 'Institute created.');
    }

    // Show the "edit institute" form
    public function edit(int $institute): View
    {
        $row = DB::table('institutes')->where('id', $institute)->first();
        abort_if($row === null, 404);   // no such institute

        return view('admin.institutes.edit', ['institute' => $row]);
    }

    // Save changes to an institute
    public function update(Request $request, int $institute): RedirectResponse
    {
        abort_unless(DB::table('institutes')->where('id', $institute)->exists(), 404);

        $data = $request->validate([
            // The code must be unique, but this institute may keep its own code
            'code' => ['required', 'string', 'max:20', Rule::unique('institutes', 'code')->ignore($institute)],
            'name' => ['required', 'string', 'max:255'],
        ]);

        DB::table('institutes')->where('id', $institute)->update([
            'code'       => $data['code'],
            'name'       => $data['name'],
            'updated_at' => now(),
        ]);

        \Illuminate\Support\Facades\Cache::forget('admin.stats');
        return redirect()->route('admin.institutes.index')->with('success', 'Institute updated.');
    }

    // Delete an institute (only if it has no programs)
    public function destroy(int $institute): RedirectResponse
    {
        if (DB::table('programs')->where('institute_id', $institute)->exists()) {
            return back()->with('error', 'Cannot delete: this institute still has programs.');
        }

        DB::table('institutes')->where('id', $institute)->delete();

        \Illuminate\Support\Facades\Cache::forget('admin.stats');
        return redirect()->route('admin.institutes.index')->with('success', 'Institute deleted.');
    }
}