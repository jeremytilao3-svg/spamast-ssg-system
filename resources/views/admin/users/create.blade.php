@extends('layouts.app')

@section('title', 'Add User')

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

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <p>
            <label>First Name</label><br>
            <input type="text" name="first_name" value="{{ old('first_name') }}" required>
        </p>
        <p>
            <label>Middle Name (optional)</label><br>
            <input type="text" name="middle_name" value="{{ old('middle_name') }}">
        </p>
        <p>
            <label>Last Name</label><br>
            <input type="text" name="last_name" value="{{ old('last_name') }}" required>
        </p>
        <p>
            <label>Email</label><br>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </p>
        <p>
            <label>Password</label><br>
            <input type="password" name="password" required>
        </p>
        <p>
            <label>Confirm Password</label><br>
            <input type="password" name="password_confirmation" required>
        </p>
        <p>
            <label>Role</label><br>
            <select name="role_id" required>
                <option value="">Choose a role</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>{{ $role->name }}</option>
                @endforeach
            </select>
        </p>
        <p>
            <label>
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', '1'))> Active
            </label>
        </p>

        <button type="submit">Create User</button>
    </form>
@endsection