<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    // Show the profile form for the logged-in user
    public function edit(Request $request): View
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    // Save the changes
    public function update(Request $request): RedirectResponse
    {
        // Always the logged-in user, never an ID from the form
        $user = $request->user();

        $data = $request->validate([
            'first_name'       => ['required', 'string', 'max:255'],
            'middle_name'      => ['nullable', 'string', 'max:255'],
            'last_name'        => ['required', 'string', 'max:255'],
            'email'            => ['required', 'email:rfc', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            // Password change is optional; if used, the current password is required
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password'         => ['nullable', 'string', 'min:8', 'confirmed'],
            'photo'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // Only these fields may change (never role_id, is_active or is_admin)
        $user->first_name  = $data['first_name'];
        $user->middle_name = $data['middle_name'] ?? null;
        $user->last_name   = $data['last_name'];
        $user->email       = $data['email'];

        // Change the password only if a new one was typed (the model hashes it)
        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        // New profile photo: delete the old file, then store the new one
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $request->file('photo')->store('avatars', 'public');
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated.');
    }
}