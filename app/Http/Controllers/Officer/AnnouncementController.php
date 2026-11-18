<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    // Same rules for create and edit; attachment is optional, max 5 MB (5120 KB)
    private function rules(): array
    {
        return [
            'title'      => ['required', 'string', 'max:255'],
            'body'       => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    // Show the empty form
    public function create(): View
    {
        Gate::authorize('manage-ssg');

        return view('officer.announcements.create');
    }

    // Save a new announcement
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage-ssg');

        $data = $request->validate($this->rules());

        // Store the file (if any) in storage/app/public/announcements
        $path = $request->hasFile('attachment')
            ? $request->file('attachment')->storeAs('announcements', $this->attachmentName($request), 'public')
            : null;

        Announcement::create([
            'user_id'    => $request->user()->id,
            'title'      => $data['title'],
            'body'       => $data['body'],
            'attachment' => $path,
        ]);

        Cache::forget('officer.stats');

        return redirect()->route('announcements.index')->with('success', 'Announcement posted.');
    }

    // Show the form filled with the current values
    public function edit(Announcement $announcement): View
    {
        Gate::authorize('manage-ssg');

        return view('officer.announcements.edit', compact('announcement'));
    }

    // Save the changes; a new file replaces (and deletes) the old one
    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        Gate::authorize('manage-ssg');

        $data = $request->validate($this->rules());

        $path = $announcement->attachment;

        if ($request->hasFile('attachment')) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            $path = $request->file('attachment')->storeAs('announcements', $this->attachmentName($request), 'public');
        }

        $announcement->update([
            'title'      => $data['title'],
            'body'       => $data['body'],
            'attachment' => $path,
        ]);

        return redirect()->route('announcements.show', $announcement)->with('success', 'Announcement updated.');
    }

    // Delete the announcement and its file
    public function destroy(Announcement $announcement): RedirectResponse
    {
        Gate::authorize('manage-ssg');

        if ($announcement->attachment) {
            Storage::disk('public')->delete($announcement->attachment);
        }

        $announcement->delete();

        Cache::forget('officer.stats');

        return redirect()->route('announcements.index')->with('success', 'Announcement deleted.');
    }

    /**
     * Readable, unique file name: slug of the title + 8 random characters + extension.
     */
    private function attachmentName(Request $request): string
    {
        return Str::slug($request->input('title')) . '-' . Str::random(8) . '.' . $request->file('attachment')->extension();
    }
}
