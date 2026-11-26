<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Registration - SPAMAST SSG</title>
    @include('auth._style')
</head>
<body>
    <div class="auth-card">
        <p class="auth-brand">SPAMAST SSG</p>
        <p class="auth-sub">QR Attendance System</p>
        <h2>Student Registration</h2>

        {{-- Show validation errors, if any --}}
        @if ($errors->any())
            <div class="msg-err">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <div class="field">
                <label>First Name</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" required>
            </div>
            <div class="field">
                <label>Middle Name (optional)</label>
                <input type="text" name="middle_name" value="{{ old('middle_name') }}">
            </div>
            <div class="field">
                <label>Last Name</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" required>
            </div>
            <div class="field">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="field">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="field">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn">Register</button>
        </form>

        <p class="auth-link">Already have an account? <a href="{{ route('login') }}">Login here</a></p>
    </div>
</body>
</html>
