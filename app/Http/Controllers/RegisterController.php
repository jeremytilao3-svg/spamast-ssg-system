<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegisterController extends Controller
{
    // Show the registration page
    public function show(): View
    {
        return view('auth.register');
    }

    // Save a new account (runs when the register form is submitted)
    public function store(Request $request): RedirectResponse
    {
        // 1. Check the form. If something is wrong, Laravel sends the
        //    visitor back to the form with error messages.
        $data = $request->validate([
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email:rfc', 'max:255', 'unique:users,email'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // 2. Public registration always creates a Student
        $studentRole = Role::where('name', 'Student')->firstOrFail();

        // 3. Save the user (the password is hashed automatically by the model)
        User::create([
            'first_name'  => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'last_name'   => $data['last_name'],
            'email'       => $data['email'],
            'password'    => $data['password'],
            'role_id'     => $studentRole->id,
        ]);

        // 4. Go to the login page with a success message
        return redirect()->route('login')
            ->with('success', 'Registration successful. You can now log in.');
    }
}