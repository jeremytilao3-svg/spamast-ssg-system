@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <p><a href="{{ route('admin.users.index') }}">&larr; Back to users</a></p>

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="flash-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')

        <p>
            <label>First Name</label><br>
            <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
        </p>
        <p>
            <label>Middle Name (optional)</label><br>
            <input type="text" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}">
        </p>
        <p>
            <label>Last Name</label><br>
            <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
        </p>
        <p>
            <label>Email</label><br>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
        </p>

        <p>
            <label>Role</label><br>
            <select name="role_id" required>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id) == $role->id)>{{ $role->name }}</option>
                @endforeach
            </select>
        </p>
        <p>
            <label>
                <input type="checkbox" name="is_active" value="1" @checked($errors->any() ? old('is_active') : $user->is_active)> Active
            </label>
        </p>

        <h3>Reset Password (leave blank to keep the current password)</h3>
        <p>
            <label>New Password</label><br>
            <input type="password" name="password">
        </p>
        <p>
            <label>Confirm New Password</label><br>
            <input type="password" name="password_confirmation">
        </p>

        <button type="submit">Save Changes</button>
    </form>
@endsection