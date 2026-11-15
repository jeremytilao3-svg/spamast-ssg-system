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
        // Check only the fields we allow
        $data = $request->validate([
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email:rfc', 'max:255', 'unique:users,email'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Everyone who registers publicly is a Student (never chosen by the visitor)
        $studentRole = Role::where('name', 'Student')->firstOrFail();

        // The password is hashed automatically by the model.
        // is_active is false: an Admin must approve the account first.
        User::create([
            'first_name'  => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'last_name'   => $data['last_name'],
            'email'       => $data['email'],
            'password'    => $data['password'],
            'role_id'     => $studentRole->id,
            'is_active'   => false,
        ]);

        return redirect()->route('login')
            ->with('success', 'Registration successful. Your account must be approved by an Admin before you can log in.');
    }
}