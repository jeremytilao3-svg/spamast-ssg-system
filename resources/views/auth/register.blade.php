<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Registration</title>
</head>
<body>
    <h1>SPAMAST SSG QR Attendance System</h1>
    <h2>Student Registration</h2>

    {{-- Show validation errors, if any --}}
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register.store') }}">
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

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
</body>
</html>