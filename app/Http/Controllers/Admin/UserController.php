<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    // Roles an Admin may give to an account (Admin accounts are made by the seeder only)
    private function assignableRoles()
    {
        return Role::whereIn('name', ['Officer', 'Adviser', 'Student'])->orderBy('name')->get();
    }

    // List users with search, role filter and pages of 10
    public function index(Request $request): View
    {
        $query = User::with('role');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($roleId = $request->query('role_id')) {
            $query->where('role_id', $roleId);
        }

        $users = $query->orderBy('last_name')->paginate(10)->withQueryString();
        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    // Show the "new user" form
    public function create(): View
    {
        return view('admin.users.create', ['roles' => $this->assignableRoles()]);
    }

    // Save a new user
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email:rfc', 'max:255', 'unique:users,email'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
            'role_id'     => ['required', Rule::in($this->assignableRoles()->pluck('id'))],
        ]);

        User::create([
            'first_name'  => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'last_name'   => $data['last_name'],
            'email'       => $data['email'],
            'password'    => $data['password'],   // hashed by the model
            'role_id'     => $data['role_id'],
            'is_active'   => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created.');
    }

    // Show the "edit user" form
    public function edit(User $user): View|RedirectResponse
    {
        if ($user->hasRole('Admin')) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Admin accounts cannot be edited here. Use My Profile.');
        }

        return view('admin.users.edit', ['user' => $user, 'roles' => $this->assignableRoles()]);
    }

    // Save changes to a user
    public function update(Request $request, User $user): RedirectResponse
    {
        if ($user->hasRole('Admin')) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Admin accounts cannot be edited here. Use My Profile.');
        }

        $data = $request->validate([
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email:rfc', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password'    => ['nullable', 'string', 'min:8', 'confirmed'],
            'role_id'     => ['required', Rule::in($this->assignableRoles()->pluck('id'))],
        ]);

        $user->first_name  = $data['first_name'];
        $user->middle_name = $data['middle_name'] ?? null;
        $user->last_name   = $data['last_name'];
        $user->email       = $data['email'];
        $user->role_id     = $data['role_id'];
        $user->is_active   = $request->boolean('is_active');

        // Change the password only if a new one was typed
        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    // Switch an account on or off (approve / deactivate)
    public function toggle(User $user): RedirectResponse
    {
        if ($user->hasRole('Admin')) {
            return back()->with('error', 'Admin accounts cannot be switched off here.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('success', $user->is_active ? 'Account activated.' : 'Account deactivated.');
    }

    // Delete a user (UserPolicy decides who may)
    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('delete', $user);   // 403 if not allowed

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }
}