<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
</head>
<body>
    <h1>My Profile</h1>
    <p><a href="{{ route('main') }}">Back to main page</a></p>

    {{-- Success message after saving --}}
    @if (session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    {{-- Validation errors --}}
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
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

        <h3>Change Password (leave blank to keep your current password)</h3>
        <p>
            <label>Current Password</label><br>
            <input type="password" name="current_password">
        </p>
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
</body>
</html>